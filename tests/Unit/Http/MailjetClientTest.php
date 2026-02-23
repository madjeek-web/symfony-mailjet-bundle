<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\Tests\Unit\Http;

use Madjeek\MailjetBundle\DTO\EmailMessage;
use Madjeek\MailjetBundle\Exception\MailjetApiException;
use Madjeek\MailjetBundle\Http\MailjetClient;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;

/**
 * MailjetClientTest — Tests for the HTTP API client
 *
 * 🔮 What is MockHttpClient?
 * We can't call the REAL Mailjet API in tests because:
 * 1. Tests would be slow (real HTTP calls)
 * 2. Tests would cost money (Mailjet has rate limits)
 * 3. Tests would be unreliable (internet down?)
 *
 * Instead, we use MockHttpClient — a fake HTTP client that returns
 * pre-configured responses. We tell it: "When you receive a POST request,
 * return this fake JSON response."
 *
 * This lets us test our code WITHOUT making real API calls!
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class MailjetClientTest extends TestCase
{
    /**
     * Build a test email message (reused across multiple tests)
     */
    private function createTestEmail(): EmailMessage
    {
        return EmailMessage::create()
            ->from('sender@example.com', 'Test Sender')
            ->to('recipient@example.com', 'Test Recipient')
            ->subject('Test Email Subject')
            ->htmlBody('<p>This is a test email</p>')
            ->textBody('This is a test email');
    }

    /**
     * Build a fake successful Mailjet API response
     *
     * @return array<string, mixed>
     */
    private function successfulApiResponse(): array
    {
        return [
            'Messages' => [
                [
                    'Status' => 'success',
                    'To'     => [
                        [
                            'Email'     => 'recipient@example.com',
                            'MessageID' => 1234567890,
                            'MessageHref' => 'https://api.mailjet.com/v3/message/1234567890',
                        ],
                    ],
                ],
            ],
        ];
    }

    #[Test]
    public function itSendsEmailSuccessfully(): void
    {
        // Arrange: create a mock HTTP client that returns a successful response
        $mockResponse = new MockResponse(
            json_encode($this->successfulApiResponse()),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $logger     = new NullLogger(); // NullLogger discards all log messages in tests

        $client = new MailjetClient(
            httpClient:   $httpClient,
            apiKey:       'test_api_key',
            secretKey:    'test_secret_key',
            logger:       $logger,
            sandboxMode:  false,
        );

        // Act
        $response = $client->send($this->createTestEmail());

        // Assert
        $this->assertArrayHasKey('Messages', $response);
        $this->assertSame('success', $response['Messages'][0]['Status']);
    }

    #[Test]
    public function itEnablesSandboxModeCorrectly(): void
    {
        $capturedBody = null;

        // Use a callback to capture what was sent to the API
        $mockResponse = function (string $method, string $url, array $options) use (&$capturedBody): MockResponse {
            // Capture the request body so we can inspect it
            $capturedBody = json_decode($options['body'], associative: true);

            return new MockResponse(
                json_encode(['Messages' => [['Status' => 'success', 'To' => []]]]),
                ['http_code' => 200]
            );
        };

        $httpClient = new MockHttpClient($mockResponse);
        $client     = new MailjetClient($httpClient, 'key', 'secret', new NullLogger(), sandboxMode: true);

        $client->send($this->createTestEmail());

        // Verify sandbox mode was included in the request
        $this->assertTrue($capturedBody['SandboxMode']);
    }

    #[Test]
    public function itThrowsOnUnauthorizedResponse(): void
    {
        $this->expectException(MailjetApiException::class);

        // Simulate a 401 Unauthorized response (wrong API key)
        $mockResponse = new MockResponse(
            json_encode(['ErrorMessage' => 'API key invalid', 'ErrorInfo' => '']),
            ['http_code' => 401]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client     = new MailjetClient($httpClient, 'wrong_key', 'wrong_secret', new NullLogger());

        $client->send($this->createTestEmail());
    }

    #[Test]
    public function itThrowsOnApiServerError(): void
    {
        $this->expectException(MailjetApiException::class);

        // Simulate a 500 Internal Server Error from Mailjet
        $mockResponse = new MockResponse(
            json_encode(['ErrorMessage' => 'Internal server error']),
            ['http_code' => 500]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client     = new MailjetClient($httpClient, 'key', 'secret', new NullLogger());

        $client->send($this->createTestEmail());
    }

    #[Test]
    public function itThrowsOnEmptyApiCredentials(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/cannot be empty/i');

        // Empty API key should throw immediately on construction
        new MailjetClient(
            new MockHttpClient(),
            apiKey:    '',   // ← empty!
            secretKey: 'secret',
            logger:    new NullLogger()
        );
    }

    #[Test]
    public function itRejectsBatchExceedingMaximumSize(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/exceeds the maximum/i');

        $client = new MailjetClient(new MockHttpClient(), 'key', 'secret', new NullLogger());

        // Create 51 emails (max is 50)
        $emails = array_fill(0, 51, $this->createTestEmail());

        $client->sendBatch($emails);
    }

    #[Test]
    public function itSendsBatchOfMultipleEmailsSuccessfully(): void
    {
        $responses = [
            ['Status' => 'success', 'To' => [['Email' => 'a@example.com', 'MessageID' => 1]]],
            ['Status' => 'success', 'To' => [['Email' => 'b@example.com', 'MessageID' => 2]]],
        ];

        $mockResponse = new MockResponse(
            json_encode(['Messages' => $responses]),
            ['http_code' => 200]
        );

        $httpClient = new MockHttpClient($mockResponse);
        $client     = new MailjetClient($httpClient, 'key', 'secret', new NullLogger());

        $emails   = [$this->createTestEmail(), $this->createTestEmail()];
        $response = $client->sendBatch($emails);

        $this->assertCount(2, $response['Messages']);
    }
}
