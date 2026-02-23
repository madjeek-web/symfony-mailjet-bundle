<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\Exception;

/**
 * MailjetApiException — Thrown when the Mailjet API returns an error
 *
 * 🚨 What is an Exception?
 * An Exception is a way to signal that something went WRONG.
 * Instead of returning null or false (which is confusing), we "throw"
 * an exception with a clear message explaining what happened.
 *
 * The calling code can "catch" this exception and handle it:
 *   try {
 *       $sender->sendNow($email);
 *   } catch (MailjetApiException $e) {
 *       $logger->error('Email failed: ' . $e->getMessage());
 *       // Show user a friendly error message instead of crashing
 *   }
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class MailjetApiException extends \RuntimeException
{
    /**
     * @param string     $message    Human-readable description of what went wrong
     * @param int        $statusCode The HTTP status code returned by Mailjet (e.g. 401, 500)
     *                               See: https://dev.mailjet.com/email/reference/
     * @param array<string, mixed> $responseBody The full response body from Mailjet for debugging
     * @param \Throwable|null $previous  The original exception that caused this one (if any)
     */
    public function __construct(
        string $message,
        private readonly int $statusCode = 0,
        private readonly array $responseBody = [],
        \Throwable|null $previous = null,
    ) {
        // Call the parent Exception constructor with message and previous exception
        parent::__construct($message, $statusCode, $previous);
    }

    /**
     * Get the HTTP status code from the Mailjet API response
     *
     * Common status codes:
     * - 200: Success (but might have partial failures in batch)
     * - 400: Bad request (invalid email format, missing fields)
     * - 401: Unauthorized (wrong API key or secret)
     * - 429: Too Many Requests (rate limit exceeded — slow down!)
     * - 500: Mailjet server error (try again later)
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the full response body from Mailjet for debugging
     * This can contain detailed error messages from the API.
     *
     * @return array<string, mixed>
     */
    public function getResponseBody(): array
    {
        return $this->responseBody;
    }

    /**
     * Check if this was a rate-limiting error (HTTP 429)
     * If true, you should wait before retrying!
     */
    public function isRateLimited(): bool
    {
        return $this->statusCode === 429;
    }

    /**
     * Check if this was an authentication error (HTTP 401)
     * If true, check your API key and secret key in config.
     */
    public function isAuthenticationError(): bool
    {
        return $this->statusCode === 401;
    }
}
