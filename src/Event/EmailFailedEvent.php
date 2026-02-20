<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\Event;

use Fabconejo\MailjetBundle\DTO\EmailMessage;
use Fabconejo\MailjetBundle\Exception\MailjetApiException;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * EmailFailedEvent — Dispatched when an email FAILS to send
 *
 * Listen to this event to:
 * - Alert your monitoring system (Sentry, Datadog, PagerDuty)
 * - Save failed emails to a database for retry later
 * - Notify an admin via Slack or another channel
 * - Deactivate invalid email addresses in your user database
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class EmailFailedEvent extends Event
{
    /** The event name */
    public const NAME = 'mailjet.email_failed';

    public function __construct(
        private readonly EmailMessage $email,
        private readonly MailjetApiException $exception,
    ) {
    }

    /** Get the email that failed to send */
    public function getEmail(): EmailMessage
    {
        return $this->email;
    }

    /** Get the exception that caused the failure */
    public function getException(): MailjetApiException
    {
        return $this->exception;
    }

    /** Convenience method: was this a rate limit error? */
    public function wasRateLimited(): bool
    {
        return $this->exception->isRateLimited();
    }

    /** Convenience method: was this an auth error? */
    public function wasAuthenticationError(): bool
    {
        return $this->exception->isAuthenticationError();
    }
}
