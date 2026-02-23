<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\Contract;

use Madjeek\MailjetBundle\DTO\EmailMessage;

/**
 * EmailSenderInterface — Contract for the high-level email sender service
 *
 * 🎯 Why a separate interface from MailjetClientInterface?
 *
 * - MailjetClientInterface deals with the HTTP/API layer (low level)
 * - EmailSenderInterface deals with the business logic layer (high level)
 *
 * The sender decides:
 *   → Should this be sent NOW (synchronously)?
 *   → Or queued for later (async via Symfony Messenger)?
 *
 * This separation is called "Separation of Concerns" — each class has ONE job.
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
interface EmailSenderInterface
{
    /**
     * Send an email immediately (synchronous — blocks until done)
     *
     * ⚡ Use this when you need instant confirmation.
     *    Example: password reset — the user is WAITING for this email.
     *
     * @param EmailMessage $message The email to send right now
     * @throws \Madjeek\MailjetBundle\Exception\MailjetApiException On API failure
     */
    public function sendNow(EmailMessage $message): void;

    /**
     * Queue an email for async delivery via Symfony Messenger
     *
     * 🚀 Use this for non-urgent emails.
     *    Example: welcome email, newsletter, invoices.
     *    Your HTTP response returns instantly and the email
     *    is processed in the background by a worker.
     *
     * @param EmailMessage $message The email to queue
     */
    public function sendAsync(EmailMessage $message): void;
}
