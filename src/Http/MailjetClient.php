<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\Http;

use Fabconejo\MailjetBundle\Contract\MailjetClientInterface;
use Fabconejo\MailjetBundle\DTO\EmailMessage;
use Fabconejo\MailjetBundle\Exception\MailjetApiException;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

/**
 * MailjetClient — The HTTP client that talks to the Mailjet API
 *
 * 🌐 What this class does:
 * This is the "real" implementation of MailjetClientInterface.
 * It takes an EmailMessage, formats it as JSON, and sends it to
 * https://api.mailjet.com/v3.1/send using HTTP POST.
 *
 * 🔑 Authentication:
 * Mailjet uses "Basic Authentication" — you send your API key and secret
 * encoded in the HTTP Authorization header. It's like a username and password
 * for the API.
 *
 * 🔧 Symfony HttpClient:
 * We use Symfony's built-in HttpClient instead of raw cURL.
 * Why? It's:
 * - Mockable in tests (you can fake HTTP responses)
 * - Supports async requests out of the box
 * - Has automatic retry logic
 * - Better error messages and debugging
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class MailjetClient implements MailjetClientInterface
{
    /** The Mailjet API endpoint for sending emails (version 3.1) */
    private const API_URL = 'https://api.mailjet.com/v3.1/send';

    /** Maximum emails per batch request (Mailjet limitation) */
    private const MAX_BATCH_SIZE = 50;

    /** Request timeout in seconds */
    private const TIMEOUT_SECONDS = 30;

    /**
     * @param HttpClientInterface $httpClient Symfony's HTTP client (injected by Symfony DI)
     * @param string              $apiKey     Your Mailjet API Key (from your .env file)
     * @param string              $secretKey  Your Mailjet Secret Key (from your .env file)
     * @param LoggerInterface     $logger     For logging successes and failures
     * @param bool                $sandboxMode If true, emails are NOT actually sent (perfect for dev/staging!)
     */
    public function __construct(
        private readonly HttpClientInterface $httpClient,
        private readonly string $apiKey,
        private readonly string $secretKey,
        private readonly LoggerInterface $logger,
        private readonly bool $sandboxMode = false,
    ) {
        // Validate that API credentials are not empty
        if (empty($apiKey) || empty($secretKey)) {
            throw new \InvalidArgumentException(
                'Mailjet API key and secret key cannot be empty. '
                . 'Set MAILJET_API_KEY and MAILJET_SECRET_KEY in your .env file.'
            );
        }
    }

    /**
     * Send a single email via Mailjet API v3.1
     *
     * 📤 Process:
     * 1. Convert EmailMessage to array format
     * 2. Wrap it in {"Messages": [...]} (as Mailjet requires)
     * 3. Send HTTP POST with JSON body
     * 4. Check the response status
     * 5. Return the decoded JSON response
     *
     * @param EmailMessage $message The email to send
     * @return array<string, mixed> The Mailjet API response
     * @throws MailjetApiException On any API or network error
     */
    public function send(EmailMessage $message): array
    {
        return $this->doSend([$message->toArray()]);
    }

    /**
     * Send multiple emails in one API request (batch mode)
     * Much more efficient than sending one by one!
     *
     * @param EmailMessage[] $messages
     * @return array<string, mixed>
     * @throws MailjetApiException
     * @throws \InvalidArgumentException If more than 50 messages provided
     */
    public function sendBatch(array $messages): array
    {
        if (count($messages) > self::MAX_BATCH_SIZE) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Batch size of %d exceeds the maximum allowed by Mailjet (%d). '
                    . 'Split your messages into smaller batches.',
                    count($messages),
                    self::MAX_BATCH_SIZE
                )
            );
        }

        if (empty($messages)) {
            throw new \InvalidArgumentException('Cannot send an empty batch. Provide at least one EmailMessage.');
        }

        // Convert all EmailMessage objects to arrays
        $messagesData = array_map(
            fn(EmailMessage $msg) => $msg->toArray(),
            $messages
        );

        return $this->doSend($messagesData);
    }

    /**
     * The core sending logic — builds and executes the HTTP request
     *
     * @param array<int, array<string, mixed>> $messagesData
     * @return array<string, mixed>
     * @throws MailjetApiException
     */
    private function doSend(array $messagesData): array
    {
        // Build the request payload
        $payload = [
            'Messages'    => $messagesData,
            // SandboxMode: when true, Mailjet validates your request but does NOT send the email
            // Perfect for dev and staging environments!
            'SandboxMode' => $this->sandboxMode,
        ];

        // Log that we're about to send (useful for debugging)
        $this->logger->info('Sending email via Mailjet API', [
            'recipient_count' => count($messagesData),
            'sandbox_mode'    => $this->sandboxMode,
            // Note: we don't log the full payload to avoid exposing email content in logs
        ]);

        try {
            // Make the HTTP POST request to Mailjet
            $response = $this->httpClient->request(
                'POST',
                self::API_URL,
                [
                    // Basic Auth: base64(apiKey:secretKey) in the Authorization header
                    'auth_basic' => [$this->apiKey, $this->secretKey],

                    // Tell the server we're sending JSON
                    'headers'    => [
                        'Content-Type' => 'application/json',
                        'Accept'       => 'application/json',
                        // Custom user agent so Mailjet can identify our bundle in their logs
                        'User-Agent'   => 'symfony-mailjet-bundle/1.0 (PHP/' . \PHP_VERSION . ')',
                    ],

                    // The request body — PHP array is automatically JSON-encoded
                    'json'       => $payload,

                    // Wait max 30 seconds for a response
                    'timeout'    => self::TIMEOUT_SECONDS,
                ]
            );

            // Get the HTTP status code (200 = OK, 401 = Unauthorized, etc.)
            $statusCode = $response->getStatusCode();

            // Get the response body and decode it from JSON to PHP array
            $responseBody = $response->toArray(throw: false);

        } catch (TransportExceptionInterface $e) {
            // TransportException means we couldn't even connect to Mailjet
            // (network down, DNS failure, timeout, etc.)
            throw new MailjetApiException(
                sprintf('Network error while connecting to Mailjet API: %s', $e->getMessage()),
                0,
                [],
                $e
            );
        }

        // Check if the request succeeded
        // Mailjet returns 200 for success, anything else is an error
        if ($statusCode !== 200) {
            $errorMessage = $responseBody['ErrorMessage']
                ?? $responseBody['ErrorInfo']
                ?? 'Unknown API error';

            $this->logger->error('Mailjet API returned an error', [
                'status_code'   => $statusCode,
                'error_message' => $errorMessage,
            ]);

            throw new MailjetApiException(
                sprintf(
                    'Mailjet API error (HTTP %d): %s. Check your API credentials and email content.',
                    $statusCode,
                    $errorMessage
                ),
                $statusCode,
                $responseBody
            );
        }

        // Check for partial failures in batch mode
        // Even with HTTP 200, individual messages might have failed
        $this->checkForPartialFailures($responseBody);

        $this->logger->info('Email(s) sent successfully via Mailjet', [
            'message_count' => count($messagesData),
            'sandbox_mode'  => $this->sandboxMode,
        ]);

        return $responseBody;
    }

    /**
     * Checks if any messages in a batch failed, even if HTTP status was 200
     *
     * Mailjet can return HTTP 200 but still have individual message errors.
     * We log warnings for these but don't throw an exception unless ALL failed.
     *
     * @param array<string, mixed> $responseBody
     */
    private function checkForPartialFailures(array $responseBody): void
    {
        if (!isset($responseBody['Messages']) || !is_array($responseBody['Messages'])) {
            return; // Can't check — response format unexpected
        }

        foreach ($responseBody['Messages'] as $index => $messageResult) {
            if (isset($messageResult['Status']) && $messageResult['Status'] !== 'success') {
                $this->logger->warning('Mailjet: individual message in batch had non-success status', [
                    'message_index' => $index,
                    'status'        => $messageResult['Status'] ?? 'unknown',
                    'errors'        => $messageResult['Errors'] ?? [],
                ]);
            }
        }
    }
}
