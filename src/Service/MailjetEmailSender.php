<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\Service;

use Madjeek\MailjetBundle\Contract\EmailSenderInterface;
use Madjeek\MailjetBundle\Contract\MailjetClientInterface;
use Madjeek\MailjetBundle\DTO\EmailMessage;
use Madjeek\MailjetBundle\Message\SendEmailMessage;
use Symfony\Component\Messenger\MessageBusInterface;

/**
 * MailjetEmailSender — The main service you use in your application
 *
 * 🎯 This is what you inject in YOUR controllers and services:
 *
 *   class RegistrationController
 *   {
 *       public function __construct(
 *           private readonly EmailSenderInterface $emailSender
 *       ) {}
 *
 *       public function register(Request $request): Response
 *       {
 *           // ... create user ...
 *
 *           // Send welcome email IMMEDIATELY
 *           $this->emailSender->sendNow(
 *               EmailMessage::create()
 *                   ->from('noreply@myapp.com', 'My App')
 *                   ->to($user->getEmail(), $user->getName())
 *                   ->subject('Welcome to My App!')
 *                   ->htmlBody($this->twig->render('emails/welcome.html.twig', ['user' => $user]))
 *           );
 *
 *           // OR send it in the background queue:
 *           $this->emailSender->sendAsync($email);
 *
 *           return new Response('User registered!');
 *       }
 *   }
 *
 * 💡 TIP: Inject EmailSenderInterface (not the concrete class).
 * This way, you can easily swap implementations in tests.
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class MailjetEmailSender implements EmailSenderInterface
{
    public function __construct(
        private readonly MailjetClientInterface $mailjetClient,
        private readonly MessageBusInterface $messageBus,
    ) {
    }

    /**
     * Send an email RIGHT NOW, synchronously
     *
     * ✅ Use when: The user is waiting for the email (password reset, OTP, etc.)
     * ❌ Avoid when: The email is optional/informational (welcome email, newsletter)
     *
     * This BLOCKS your code until the API call completes.
     * Usually takes 100–500ms.
     */
    public function sendNow(EmailMessage $message): void
    {
        // Directly call the API client — no queue, no delay
        $this->mailjetClient->send($message);
    }

    /**
     * Queue an email for background delivery
     *
     * ✅ Use when: You want a fast HTTP response and the email is not urgent
     * ✅ Use when: You're sending bulk emails or notifications
     * ❌ Avoid when: The user needs the email immediately to continue (OTP, reset link)
     *
     * This method returns INSTANTLY (< 1ms).
     * The email is processed by a Symfony Messenger worker running in the background.
     *
     * To start the worker:
     *   php bin/console messenger:consume async --time-limit=3600
     */
    public function sendAsync(EmailMessage $message): void
    {
        // Wrap the email in a "message" and dispatch it to the message bus
        // Messenger routes it to the configured transport (Redis, database, etc.)
        $this->messageBus->dispatch(new SendEmailMessage($message));
    }
}
