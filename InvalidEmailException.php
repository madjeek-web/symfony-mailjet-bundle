<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration — Defines the valid configuration structure for this bundle
 *
 * 🔧 What is this?
 * This class defines what YOUR app can put in config/packages/mailjet.yaml.
 * Symfony uses this to:
 * 1. Validate your config (typos become clear error messages)
 * 2. Provide default values for optional settings
 * 3. Generate documentation for the config structure
 *
 * 📄 The generated config structure looks like this (mailjet.yaml):
 *
 *   mailjet:
 *     api_key: '%env(MAILJET_API_KEY)%'          # Required
 *     secret_key: '%env(MAILJET_SECRET_KEY)%'     # Required
 *     sandbox_mode: false                          # Optional (default: false)
 *     webhook_secret: '%env(MAILJET_WEBHOOK_SECRET)%'  # Optional but recommended
 *     default_from:
 *       email: 'noreply@myapp.com'                # Optional default sender
 *       name: 'My Application'                    # Optional default sender name
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        // "mailjet" is the root key in your config file
        $treeBuilder = new TreeBuilder('mailjet');

        $treeBuilder->getRootNode()
            ->children()
                // API Key — REQUIRED
                // Get it from: https://app.mailjet.com/account/apikeys
                ->scalarNode('api_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Your Mailjet API key. Get it from https://app.mailjet.com/account/apikeys')
                ->end()

                // Secret Key — REQUIRED
                ->scalarNode('secret_key')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Your Mailjet Secret key. Get it from https://app.mailjet.com/account/apikeys')
                ->end()

                // Sandbox Mode — Optional (default: false)
                // When true, emails are validated but NOT actually sent
                // Perfect for dev and staging environments!
                ->booleanNode('sandbox_mode')
                    ->defaultFalse()
                    ->info(
                        'When true, emails are validated but NOT sent. '
                        . 'Enable this in dev/staging: sandbox_mode: \'%kernel.debug%\''
                    )
                ->end()

                // Webhook Secret — Optional but strongly recommended for production
                ->scalarNode('webhook_secret')
                    ->defaultValue('')
                    ->info(
                        'Secret key for verifying webhook requests from Mailjet. '
                        . 'Set this to a random string and configure it in your Mailjet dashboard.'
                    )
                ->end()

                // Default sender — Optional
                // If set, you don't need to call ->from() on every email
                ->arrayNode('default_from')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('email')
                            ->defaultNull()
                            ->info('Default sender email address (e.g. noreply@myapp.com)')
                        ->end()
                        ->scalarNode('name')
                            ->defaultNull()
                            ->info('Default sender display name (e.g. My Application)')
                        ->end()
                    ->end()
                ->end()

            ->end()
        ;

        return $treeBuilder;
    }
}
