<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\Event;

use Madjeek\MailjetBundle\DTO\EmailMessage;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * EmailSentEvent — Dispatched after an email is successfully sent
 *
 * 📡 What is the Symfony Event System?
 * Events are a way to let different parts of your application
 * communicate WITHOUT being tightly coupled to each other.
 *
 * Think of it like a radio broadcast:
 * - The sender dispatches an event (broadcasts a signal)
 * - Listeners subscribe to events they care about
 * - When the event fires, all listeners are notified
 *
 * 🎯 Why use events here?
 * When an email is sent, you might want to:
 * - Save a log entry to the database
 * - Update a "last_contacted_at" field on the user
 * - Increment a statistics counter
 * - Send a webhook to another service
 *
 * Without events, the MailjetClient would need to know about all of this.
 * With events, the MailjetClient just says "email sent!" and the listeners
 * handle the rest. Much cleaner!
 *
 * 💡 How to listen to this event in your application:
 *
 *   use Madjeek\MailjetBundle\Event\EmailSentEvent;
 *   use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
 *
 *   #[AsEventListener(EmailSentEvent::NAME)]
 *   class LogEmailSentListener {
 *       public function __invoke(EmailSentEvent $event): void {
 *           // $event->getEmail() — the EmailMessage that was sent
 *           // $event->getApiResponse() — Mailjet's response data
 *       }
 *   }
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class EmailSentEvent extends Event
{
    /** The event name — used to subscribe to this event */
    public const NAME = 'mailjet.email_sent';

    /**
     * @param EmailMessage         $email       The email that was successfully sent
     * @param array<string, mixed> $apiResponse The full response from Mailjet API
     */
    public function __construct(
        private readonly EmailMessage $email,
        private readonly array $apiResponse,
    ) {
    }

    /** Get the email that was sent */
    public function getEmail(): EmailMessage
    {
        return $this->email;
    }

    /**
     * Get the raw Mailjet API response
     * Contains message IDs, delivery status per recipient, etc.
     *
     * @return array<string, mixed>
     */
    public function getApiResponse(): array
    {
        return $this->apiResponse;
    }

    /**
     * Get the Mailjet Message IDs from the response
     * These IDs can be used to track delivery status in Mailjet's dashboard
     *
     * @return int[]
     */
    public function getMailjetMessageIds(): array
    {
        $ids = [];

        foreach ($this->apiResponse['Messages'] ?? [] as $message) {
            foreach ($message['To'] ?? [] as $recipient) {
                if (isset($recipient['MessageID'])) {
                    $ids[] = (int) $recipient['MessageID'];
                }
            }
        }

        return $ids;
    }
}
