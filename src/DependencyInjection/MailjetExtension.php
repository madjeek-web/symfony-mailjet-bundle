<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\DependencyInjection;

use Fabconejo\MailjetBundle\Contract\EmailSenderInterface;
use Fabconejo\MailjetBundle\Contract\MailjetClientInterface;
use Fabconejo\MailjetBundle\Http\MailjetClient;
use Fabconejo\MailjetBundle\Service\MailjetEmailSender;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Reference;

/**
 * MailjetExtension — Loads and configures all services for this bundle
 *
 * 🔧 What happens here?
 * When Symfony boots up, it reads all the bundle configurations and calls
 * the load() method of each Extension. This method registers all the
 * services in the Dependency Injection Container.
 *
 * The DI Container is like a "service registry" — you register all your
 * objects here, tell Symfony how to create them (what dependencies they need),
 * and Symfony creates and injects them automatically wherever needed.
 *
 * ✨ After this runs, you can do:
 *   // In a controller:
 *   public function __construct(private EmailSenderInterface $emailSender) {}
 *   // Symfony automatically injects the MailjetEmailSender!
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class MailjetExtension extends Extension
{
    /**
     * Called by Symfony when loading the bundle configuration
     *
     * @param array<int, array<string, mixed>> $configs   The raw config arrays from config files
     * @param ContainerBuilder                 $container The DI container we register services in
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        // Process and validate configuration using our Configuration class
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        // =====================================================================
        // REGISTER: MailjetClient (the HTTP API client)
        // =====================================================================
        $mailjetClientDefinition = new Definition(MailjetClient::class);
        $mailjetClientDefinition->setArguments([
            // 1. HttpClientInterface — Symfony's built-in HTTP client
            new Reference('http_client'),
            // 2. API Key from config
            $config['api_key'],
            // 3. Secret Key from config
            $config['secret_key'],
            // 4. Logger
            new Reference('logger'),
            // 5. Sandbox mode
            $config['sandbox_mode'],
        ]);
        // Tag this as a service implementing MailjetClientInterface
        $mailjetClientDefinition->addTag('monolog.logger', ['channel' => 'mailjet']);

        $container->setDefinition(MailjetClient::class, $mailjetClientDefinition);

        // Register the interface alias — so you can type-hint the interface
        $container->setAlias(MailjetClientInterface::class, MailjetClient::class);

        // =====================================================================
        // REGISTER: MailjetEmailSender (the high-level service)
        // =====================================================================
        $senderDefinition = new Definition(MailjetEmailSender::class);
        $senderDefinition->setArguments([
            // 1. The MailjetClient we registered above
            new Reference(MailjetClientInterface::class),
            // 2. Symfony's message bus for async dispatch
            new Reference('messenger.default_bus'),
        ]);
        $senderDefinition->setPublic(true); // Make it accessible via $container->get()

        $container->setDefinition(MailjetEmailSender::class, $senderDefinition);

        // Register the interface alias
        $container->setAlias(EmailSenderInterface::class, MailjetEmailSender::class)->setPublic(true);

        // =====================================================================
        // REGISTER: WebhookController
        // =====================================================================
        $webhookControllerDefinition = new Definition(\Fabconejo\MailjetBundle\Webhook\WebhookController::class);
        $webhookControllerDefinition->setArguments([
            new Reference('event_dispatcher'),
            new Reference('logger'),
            $config['webhook_secret'],
        ]);
        $webhookControllerDefinition->addTag('controller.service_arguments');

        $container->setDefinition(
            \Fabconejo\MailjetBundle\Webhook\WebhookController::class,
            $webhookControllerDefinition
        );

        // =====================================================================
        // Store config values as container parameters (useful for debugging)
        // =====================================================================
        $container->setParameter('mailjet.sandbox_mode', $config['sandbox_mode']);
        $container->setParameter('mailjet.default_from.email', $config['default_from']['email']);
        $container->setParameter('mailjet.default_from.name', $config['default_from']['name']);
    }

    /**
     * The alias used in config files
     * This is why your config file is "mailjet.yaml" and starts with "mailjet:"
     */
    public function getAlias(): string
    {
        return 'mailjet';
    }
}
