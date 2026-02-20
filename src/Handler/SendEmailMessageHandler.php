<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\Handler;

use Fabconejo\MailjetBundle\Contract\MailjetClientInterface;
use Fabconejo\MailjetBundle\Event\EmailFailedEvent;
use Fabconejo\MailjetBundle\Event\EmailSentEvent;
use Fabconejo\MailjetBundle\Exception\MailjetApiException;
use Fabconejo\MailjetBundle\Message\SendEmailMessage;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * SendEmailMessageHandler — Processes async email messages from the queue
 *
 * 🔧 How Symfony Messenger works:
 *
 *   1. Your code dispatches:   $bus->dispatch(new SendEmailMessage($email))
 *   2. Messenger puts it in a queue (Redis/database/etc.)
 *   3. A background worker picks it up
 *   4. Messenger finds the handler for this message type
 *   5. This handle() method is called
 *   6. The email is sent!
 *
 * The #[AsMessageHandler] attribute tells Symfony:
 * "This class handles SendEmailMessage objects"
 * Symfony registers this automatically — no manual config needed!
 *
 * 📋 Retry Logic:
 * If sending fails, Symfony Messenger can automatically retry.
 * Configure it in messenger.yaml with max_retries and delay settings.
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
#[AsMessageHandler]
final class SendEmailMessageHandler
{
    public function __construct(
        private readonly MailjetClientInterface $mailjetClient,
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LoggerInterface $logger,
    ) {
    }

    /**
     * This method is called by Symfony Messenger when a SendEmailMessage is dequeued
     *
     * @throws \Throwable Re-throws exceptions so Messenger can retry if configured
     */
    public function __invoke(SendEmailMessage $message): void
    {
        $email = $message->emailMessage;
        $subject = $email->getSubject() ?? '(no subject)';
        $recipientCount = count($email->getTo());

        $this->logger->info('Processing queued email', [
            'subject'          => $subject,
            'recipient_count'  => $recipientCount,
            'retry_count'      => $message->retryCount,
        ]);

        try {
            // Send the email via Mailjet API
            $response = $this->mailjetClient->send($email);

            // 🎉 Success! Dispatch an event so other parts of your app can react
            // Example: update a database record, log statistics, trigger follow-up actions
            $this->eventDispatcher->dispatch(
                new EmailSentEvent($email, $response),
                EmailSentEvent::NAME
            );

            $this->logger->info('Queued email sent successfully', [
                'subject' => $subject,
            ]);

        } catch (MailjetApiException $e) {
            $this->logger->error('Failed to send queued email', [
                'subject'     => $subject,
                'error'       => $e->getMessage(),
                'status_code' => $e->getStatusCode(),
                'retry_count' => $message->retryCount,
            ]);

            // Dispatch a failure event so you can react to it
            // Example: send an alert to your monitoring system, notify an admin
            $this->eventDispatcher->dispatch(
                new EmailFailedEvent($email, $e),
                EmailFailedEvent::NAME
            );

            // Re-throw so Symfony Messenger knows the processing failed
            // If retries are configured, Messenger will try again automatically
            throw $e;
        }
    }
}
