<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\Message;

use Fabconejo\MailjetBundle\DTO\EmailMessage;

/**
 * SendEmailMessage — A Symfony Messenger "Message" for async email sending
 *
 * 📬 What is Symfony Messenger?
 * Messenger is a Symfony component that allows you to send "messages"
 * (simple PHP objects) to a "message bus". The bus can either:
 *
 * a) Process the message IMMEDIATELY (synchronous)
 * b) Put it in a QUEUE (Redis, RabbitMQ, Amazon SQS, database...) so a
 *    background worker processes it later (asynchronous)
 *
 * 🎯 Why is this useful for emails?
 * Imagine your user registers on your website. You want to:
 * 1. Create their account in the database
 * 2. Send them a welcome email
 *
 * WITHOUT async: Both happen in the same HTTP request.
 * If Mailjet is slow (500ms), your page loads slowly.
 *
 * WITH async: You dispatch a message to the queue.
 * Your page responds instantly (<100ms).
 * A background worker sends the email a second later.
 * The user gets their email AND your app feels fast. Win-win!
 *
 * 🔧 Implementation:
 * This class is just a WRAPPER around EmailMessage.
 * It holds the data that the Handler (SendEmailMessageHandler) needs.
 * It implements MessageInterface to mark it as a Messenger message.
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final readonly class SendEmailMessage
{
    /**
     * @param EmailMessage $emailMessage The email to send
     * @param int          $retryCount   How many times this has been retried (internal use)
     */
    public function __construct(
        public EmailMessage $emailMessage,
        public int $retryCount = 0,
    ) {
    }

    /**
     * Create a new instance with an incremented retry count
     * Used when the handler needs to retry a failed send
     */
    public function withRetry(): self
    {
        return new self($this->emailMessage, $this->retryCount + 1);
    }
}
