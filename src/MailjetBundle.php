<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * MailjetBundle — Main Bundle Entry Point
 *
 * 📦 What is a "Bundle" in Symfony?
 * Think of a Bundle like a plugin or extension for Symfony.
 * When you add this class, Symfony knows this bundle exists
 * and will load its configuration, services, and routes automatically.
 *
 * This class doesn't need much code — its parent (Bundle) does the heavy lifting.
 * We just extend it so Symfony can find and register everything.
 *
 * @author Fabien Conéjéro <https://github.com/fabconejo>
 * @license MIT
 * @since 1.0.0
 */
final class MailjetBundle extends Bundle
{
    // The parent Bundle class handles all the magic:
    // - Loading config from DependencyInjection/MailjetExtension.php
    // - Auto-registering the services
    // - Making the bundle available as "mailjet" in Symfony
}
