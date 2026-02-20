<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\Contract;

use Fabconejo\MailjetBundle\DTO\EmailMessage;

/**
 * MailjetClientInterface — The "contract" for the API client
 *
 * 🤔 What is an Interface?
 * An interface is like a PROMISE or a CONTRACT.
 * It says: "Any class that implements me MUST have these methods."
 *
 * Why is this useful?
 * - In your tests, you can create a FAKE MailjetClient that doesn't
 *   actually call the Mailjet API. This makes tests fast and free.
 * - You could swap Mailjet for another email service (Brevo, Postmark)
 *   without changing the rest of your application.
 * - This is called "Dependency Inversion Principle" (the D in SOLID).
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
interface MailjetClientInterface
{
    /**
     * Send a single email message via the Mailjet API v3.1
     *
     * Returns the full API response as an associative array.
     * Example response: ['Messages' => [['Status' => 'success', 'To' => [...]]]]
     *
     * @param EmailMessage $message The email to send (our typed DTO object)
     * @return array<string, mixed> The raw JSON response from Mailjet decoded as array
     *
     * @throws \Fabconejo\MailjetBundle\Exception\MailjetApiException When the API call fails
     */
    public function send(EmailMessage $message): array;

    /**
     * Send multiple emails in a single API call (batch sending)
     *
     * Mailjet allows up to 50 messages per batch request.
     * This is much more efficient than calling send() 50 times separately.
     *
     * @param EmailMessage[] $messages An array of EmailMessage DTOs
     * @return array<string, mixed> The raw JSON response from Mailjet
     *
     * @throws \Fabconejo\MailjetBundle\Exception\MailjetApiException When the API call fails
     * @throws \InvalidArgumentException When more than 50 messages are provided
     */
    public function sendBatch(array $messages): array;
}
