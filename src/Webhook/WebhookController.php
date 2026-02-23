<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\Webhook;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * WebhookController — Receives real-time events FROM Mailjet
 *
 * 📡 What is a Webhook?
 * A webhook is the opposite of a normal API call:
 *
 * Normal: YOUR app → calls → Mailjet API
 * Webhook: Mailjet → calls → YOUR app
 *
 * When something happens to your emails (delivered, bounced, opened, clicked,
 * spam complaint...), Mailjet sends a POST request to your webhook URL.
 * This lets you react in real-time!
 *
 * 🔧 Setup in Mailjet Dashboard:
 * 1. Go to https://app.mailjet.com/account/triggers
 * 2. Add your webhook URL: https://yourdomain.com/mailjet/webhook
 * 3. Select which events to receive (sent, opened, clicked, bounced, etc.)
 *
 * ⚠️ SECURITY:
 * Anyone could potentially call your webhook URL and send fake events.
 * We verify the request using a shared secret token (HMAC signature).
 * This ensures the request really came from Mailjet.
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class WebhookController
{
    public function __construct(
        private readonly EventDispatcherInterface $eventDispatcher,
        private readonly LoggerInterface $logger,
        private readonly string $webhookSecret,
    ) {
    }

    /**
     * Handle incoming webhook events from Mailjet
     *
     * Mailjet sends POST requests to this URL with JSON payloads like:
     * [
     *   {"event": "open", "email": "user@example.com", "time": 1677700000, ...},
     *   {"event": "click", "url": "https://example.com", ...}
     * ]
     *
     * Events can be: sent, open, click, bounce, spam, unsub, blocked, hardbounce, softbounce
     */
    #[Route('/mailjet/webhook', name: 'mailjet_webhook', methods: ['POST'])]
    public function handle(Request $request): JsonResponse
    {
        // =====================================================================
        // STEP 1: Verify this request actually came from Mailjet (security!)
        // =====================================================================
        if (!$this->isValidRequest($request)) {
            $this->logger->warning('Mailjet webhook received with invalid signature — possible spoofing attempt', [
                'ip' => $request->getClientIp(),
            ]);

            // Return 401 Unauthorized to reject the request
            return new JsonResponse(
                ['error' => 'Invalid webhook signature'],
                Response::HTTP_UNAUTHORIZED
            );
        }

        // =====================================================================
        // STEP 2: Parse the JSON payload
        // =====================================================================
        $content = $request->getContent();

        if (empty($content)) {
            return new JsonResponse(['error' => 'Empty request body'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $events = json_decode($content, associative: true, flags: \JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->logger->error('Mailjet webhook: failed to parse JSON body', ['error' => $e->getMessage()]);
            return new JsonResponse(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        // Mailjet can send multiple events in one request
        // Normalize single events to array
        if (!is_array($events) || isset($events['event'])) {
            $events = [$events];
        }

        // =====================================================================
        // STEP 3: Process each event
        // =====================================================================
        $processedCount = 0;

        foreach ($events as $event) {
            if (!is_array($event) || !isset($event['event'])) {
                continue;
            }

            $this->processEvent($event);
            $processedCount++;
        }

        $this->logger->info('Mailjet webhook processed', ['event_count' => $processedCount]);

        // Always return 200 OK — Mailjet will retry if we return an error
        return new JsonResponse(['processed' => $processedCount]);
    }

    /**
     * Process a single webhook event
     *
     * @param array<string, mixed> $event
     */
    private function processEvent(array $event): void
    {
        $eventType = $event['event'] ?? 'unknown';
        $email     = $event['email'] ?? 'unknown';

        $this->logger->info('Mailjet webhook event received', [
            'type'  => $eventType,
            'email' => $email,
            'time'  => $event['time'] ?? null,
        ]);

        // You can add more specific handling here:
        // match ($eventType) {
        //     'bounce'      => $this->handleBounce($event),
        //     'open'        => $this->handleOpen($event),
        //     'click'       => $this->handleClick($event),
        //     'spam'        => $this->handleSpam($event),
        //     'unsub'       => $this->handleUnsubscribe($event),
        //     default       => null,
        // }

        // For now, just log all events. Add your own business logic here!
    }

    /**
     * Verify the webhook request signature
     *
     * Mailjet can optionally sign webhook requests.
     * If no secret is configured, we accept all requests (less secure).
     *
     * For production, ALWAYS set MAILJET_WEBHOOK_SECRET in your .env!
     */
    private function isValidRequest(Request $request): bool
    {
        // If no secret is configured, skip verification (dev mode)
        if (empty($this->webhookSecret)) {
            $this->logger->warning(
                'Mailjet webhook secret is not configured. '
                . 'Set MAILJET_WEBHOOK_SECRET in your .env for security!'
            );

            return true; // Allow all requests when no secret is set
        }

        // Mailjet sends an X-Mailjet-Signature header with an HMAC-SHA256 signature
        $signature = $request->headers->get('X-Mailjet-Signature');

        if ($signature === null) {
            return false;
        }

        // Compute expected signature: HMAC-SHA256 of the request body using our secret
        $expectedSignature = hash_hmac('sha256', $request->getContent(), $this->webhookSecret);

        // Use hash_equals() instead of === for timing-safe comparison
        // This prevents "timing attacks" where an attacker guesses the signature
        // by measuring how long the comparison takes
        return hash_equals($expectedSignature, $signature);
    }
}
