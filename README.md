# symfony-mailjet-bundle

> **A modern, async-ready Symfony 7 bundle for sending emails via Mailjet.**
> Built with PHP 8.3, Symfony Messenger, HttpClient, Webhooks & 100% test coverage.

[![CI](https://github.com/fabconejo/symfony-mailjet-bundle/actions/workflows/ci.yml/badge.svg)](https://github.com/fabconejo/symfony-mailjet-bundle/actions)
[![PHPStan Level 9](https://img.shields.io/badge/PHPStan-level%209-brightgreen)](https://phpstan.org/)
[![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-blue)](https://www.php.net/)
[![Symfony 7](https://img.shields.io/badge/Symfony-7.x-black)](https://symfony.com/)
[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

---

### symfony-mailjet-bundle, what is it exactly ?

When you use a website and receive an automated email like "Welcome!", "Your order is confirmed", or "Reset your password"... someone had to program that.
This project is a free toolkit that developers can download and integrate into their application to send these emails automatically, through a specialized service called Mailjet.

What makes this project professional is the quality of the work provided: the code is fully verified by automated tests (like a safety net), documented, and secured according to enterprise standards.
It is published as open source, meaning it's freely accessible to developers worldwide, who can use it or contribute to it.

In short : it's a professional tool, generously shared with the community, demonstrating solid technical expertise, attention to detail, and the ability to produce clean, documented, and reusable work.

What this project does, simply put :

Symfony is a tool for building websites in PHP. It can do a lot of things, but it doesn't know how to send emails via Mailjet. Mailjet is an external service, like a digital postal service.

This project acts as a bridge between the two.

Without this project, a developer wanting to send an email via Mailjet in their Symfony application would have to write all the connection, formatting, security, and error-handling code themselves... taking several days of work.

With this project, they simply write :

```php
$emailSender->sendNow($email); // ← that's it
And the email is sent. The project takes care of everything else behind the scenes.

It's like a universal power adapter: Symfony is the wall outlet, Mailjet is the device, and this bundle is the adapter that connects them properly.

```

---

## Demo Page

 **[View the live demo & documentation](https://madjeek-web.github.io/symfony-mailjet-bundle/demo/)**


<img src="https://github.com/madjeek-web/symfony-mailjet-bundle/raw/main/screen_demo_mailjet.jpg" alt="symfony mailjet bundle page demo" width="100%" height="100%">

---

## Table of Contents

1. [What is Mailjet?](#-what-is-mailjet)
2. [What is this project?](#-what-is-this-project)
3. [Why is this project relevant in 2026?](#-why-is-this-project-relevant-in-2026)
4. [Requirements](#-requirements)
5. [Installation](#-installation)
6. [Configuration](#-configuration)
7. [Usage — Sending Emails](#-usage--sending-emails)
8. [Asynchronous Sending with Symfony Messenger](#-asynchronous-sending)
9. [Receiving Webhook Events](#-receiving-webhook-events)
10. [Running the Tests](#-running-the-tests)
11. [Project Architecture (for developers)](#-project-architecture)
12. [For Teachers & Students](#-for-teachers--students)
13. [Contributing](#-contributing)
14. [License](#-license)
15. [Author](#-author)

---

## What is Mailjet ?

### The short answer (for beginners)

Imagine you've built a website where users can create an account. When they sign up, you want to send them a **welcome email**. Or maybe a **"forgot your password"** email with a reset link.

You *could* try to set up your own email server... but that's incredibly complex. You'd need to handle :
- Spam blacklists (big email providers like Gmail might block your emails)
- Server maintenance and configuration
- Delivery tracking and bounce handling
- Security (SPF, DKIM, DMARC records)

This is where **Mailjet** comes in. It's a **SaaS (Software as a Service)** platform that handles ALL of that for you. You just call their API (a simple HTTP request), and they take care of delivering your email reliably to inboxes all over the world.

### Mailjet at a glance

| Feature | Details |
|---------|---------|
| **Website** | [https://www.mailjet.com](https://www.mailjet.com) |
| **API Docs** | [https://dev.mailjet.com](https://dev.mailjet.com) |
| **Free tier?** | Yes! 200 emails/day free, 6,000/month |
| **Paid plans** | From ~€15/month for 15,000 emails |
| **Founded** | 2010, headquartered in Paris, France 🇫🇷 |
| **Who uses it?** | Over 150,000 companies worldwide |

### What can Mailjet do ?

- **Transactional emails : Welcome emails, password resets, order confirmations, invoices - triggered by your app
- **Marketing emails : Newsletters, promotions, campaigns - sent to many recipients
- **Real-time tracking : See who opened your email, who clicked a link, who unsubscribed
- **Contact management**: Store and manage your mailing lists
- **Webhook notifications** : Mailjet tells YOUR server when an email bounces, is opened, etc.

---

### What is the concrete purpose of it ?
- Send confirmation emails of registration
- Send transactional emails (invoice, reset password, notification)
- Send one-by-one personalized campaigns with a Twig template per recipient
- All without managing a mail server, just an API key

---

### Mailjet vs competitors

| Service | Free Tier | Notes |
|---------|-----------|-------|
| **Mailjet** | 200/day, 6k/month | European company, GDPR-friendly |
| **SendGrid** | 100/day | Popular, owned by Twilio |
| **Brevo (Sendinblue)** | 300/day | French company, very complete |
| **Postmark** | 100/month trial | Focused on transactional |
| **Amazon SES** | 62k/month (if on AWS) | Cheapest at scale, complex setup |

---

## What is this project ?

This is a **Symfony Bundle** - a reusable plugin for PHP applications built with the [Symfony framework](https://symfony.com/).

### The problem it solves

The original Mailjet bundle for Symfony was written in **2015**. The PHP and Symfony ecosystem has changed enormously since then. That old bundle :

- Used Symfony 2 (we're now on Symfony 7 - 5 major versions later!)
- Used raw cURL calls (fragile, hard to test)
- Had no async support (blocks your server while waiting for the API)
- Had no type safety (PHP has added many type features since 2015)
- Had almost no tests

**This bundle is a complete rewrite** using every modern best practice available in 2026.

### What this bundle does

1. **Provides a fluent PHP interface** to build and send emails using the Mailjet API v3.1
2. **Integrates deeply with Symfony 7** (Dependency Injection, Messenger, Events, HTTP Client)
3. **Supports async sending** via Symfony Messenger (your app doesn't wait for the email to be sent)
4. **Receives real-time events** from Mailjet via webhooks (bounces, opens, clicks)
5. **Is ultra-secure** with proper credential handling and webhook signature verification

---

## Why is this project relevant in 2026 ?

Great question! Here's why this matters right now :

### 1. PHP is not dead — it's thriving
PHP powers **~78% of websites** with a server-side language (including WordPress, Laravel apps, Symfony apps). The latest PHP 8.3 is fast, modern, and has features comparable to other languages. PHP 8.4 is already out.

### 2. Email is still the #1 communication channel
Despite Slack, Discord, and other messaging apps, **email remains the primary way businesses communicate with customers**. Every app needs to send emails. This bundle makes that easy.

### 3. Symfony 7 is widely used in enterprise PHP
Companies like Spotify, Trivago, and thousands of enterprises use Symfony as their PHP framework. Knowing how to build quality Symfony bundles is a valuable professional skill.

### 4. Async programming is now essential
Modern applications need to be fast. Users expect sub-100ms responses. Sending emails synchronously (blocking the HTTP response while waiting for an API) is bad practice. This bundle shows how to do it right with Symfony Messenger.

### 5. Code quality matters more than ever
With AI-assisted coding becoming common, the ability to write **tested, typed, maintainable code** that humans AND tools can understand is increasingly valuable. This project demonstrates all of that.

---

## Requirements

| Requirement | Minimum Version | Notes |
|-------------|-----------------|-------|
| PHP | 8.3 | Uses readonly classes, enums, named arguments |
| Symfony Framework | 7.0 | Full Symfony 7 integration |
| Symfony HttpClient | 7.0 | For async HTTP requests |
| Symfony Messenger | 7.0 | For async email queuing |
| Mailjet Account | Free tier works | Get one at [mailjet.com](https://www.mailjet.com) |

---

## Installation

### Step 1 : Install via Composer

[Composer](https://getcomposer.org/) is the dependency manager for PHP. It's like `npm` for JavaScript or `pip` for Python.

```bash
composer require fabconejo/symfony-mailjet-bundle
```

If you're using **Symfony Flex** (included by default in new Symfony projects), the bundle will be automatically registered. If not, add it manually to `config/bundles.php`:

```php
// config/bundles.php
return [
    // ... other bundles ...
    Fabconejo\MailjetBundle\MailjetBundle::class => ['all' => true],
];
```

### Step 2 : Get Your Mailjet API Keys

1. Go to [https://www.mailjet.com](https://www.mailjet.com) and create a **free account**
2. Navigate to **Account Settings** → **API Keys**: [https://app.mailjet.com/account/apikeys](https://app.mailjet.com/account/apikeys)
3. Copy your **API Key** and **Secret Key**

> **Security tip**: Treat your API keys like passwords. Never commit them to Git. Never hardcode them in PHP files.

### Step 3: Add Credentials to `.env`

Open your project's `.env` file and add:

```dotenv
###> fabconejo/symfony-mailjet-bundle ###
MAILJET_API_KEY=your_api_key_here
MAILJET_SECRET_KEY=your_secret_key_here

# IMPORTANT: Keep true in dev/staging, set to false in production!
# When true, emails are validated but NOT actually sent
MAILJET_SANDBOX_MODE=true

# Optional: generate with: php -r "echo bin2hex(random_bytes(32));"
MAILJET_WEBHOOK_SECRET=
###< fabconejo/symfony-mailjet-bundle ###
```

 > Make sure `.env` is in your `.gitignore` file! Never push real API keys to GitHub.

### Step 4: Create Bundle Configuration

Create the file `config/packages/mailjet.yaml`:

```yaml
# config/packages/mailjet.yaml
mailjet:
    api_key: '%env(MAILJET_API_KEY)%'
    secret_key: '%env(MAILJET_SECRET_KEY)%'
    sandbox_mode: '%env(bool:MAILJET_SANDBOX_MODE)%'
    webhook_secret: '%env(MAILJET_WEBHOOK_SECRET)%'

    # Optional: set a default sender for all emails
    default_from:
        email: 'noreply@yourapp.com'
        name: 'Your Application Name'
```

That's it ! 

---

## Usage — Sending Emails

### The Basics: EmailMessage Builder

The `EmailMessage` class uses a **fluent builder pattern**. You chain method calls to build up your email, then send it.

```php
use Fabconejo\MailjetBundle\DTO\EmailMessage;

$email = EmailMessage::create()          // Start building
    ->from('sender@example.com', 'My App')  // Who sends it
    ->to('user@example.com', 'John Doe')    // Main recipient
    ->cc('boss@example.com')                // Carbon copy (optional)
    ->bcc('archive@example.com')            // Blind copy (optional)
    ->replyTo('support@example.com')        // Where replies go (optional)
    ->subject('Your Order Confirmation')    // Subject line
    ->htmlBody('<h1>Thank you!</h1><p>Your order #123 is confirmed.</p>')  // HTML version
    ->textBody('Thank you! Your order #123 is confirmed.');  // Plain text fallback
```

### Sending Immediately (Synchronous)

```php
use Fabconejo\MailjetBundle\Contract\EmailSenderInterface;
use Fabconejo\MailjetBundle\DTO\EmailMessage;
use Symfony\Component\HttpFoundation\Response;

class PasswordResetController
{
    public function __construct(
        // Symfony's Dependency Injection will automatically provide this!
        private readonly EmailSenderInterface $emailSender
    ) {}

    public function requestReset(string $userEmail): Response
    {
        $resetToken = 'abc123'; // Your actual reset token logic here

        $email = EmailMessage::create()
            ->from('noreply@myapp.com', 'My App Security')
            ->to($userEmail)
            ->subject('Reset your password')
            ->htmlBody(
                '<p>Click here to reset: <a href="https://myapp.com/reset/' . $resetToken . '">Reset Password</a></p>'
            )
            ->textBody('Reset link: https://myapp.com/reset/' . $resetToken);

        // sendNow() blocks until the email is sent — use for critical emails
        $this->emailSender->sendNow($email);

        return new Response('Reset email sent!');
    }
}
```

### Sending with File Attachments

```php
$email = EmailMessage::create()
    ->from('billing@myapp.com', 'Billing Department')
    ->to('customer@example.com', 'Valued Customer')
    ->subject('Invoice #2026-042')
    ->htmlBody('<p>Please find your invoice attached.</p>')
    ->textBody('Please find your invoice attached.')

    // Option A: Attach from file path (most common)
    ->attachFile('/var/www/storage/invoices/invoice-042.pdf')

    // Option B: Attach with a custom display name
    ->attachFile('/tmp/report.xlsx', 'Q1 Financial Report.xlsx')

    // Option C: Attach raw binary content (e.g. generated PDF in memory)
    ->attach('receipt.pdf', $pdfBinaryContent, 'application/pdf');

$this->emailSender->sendNow($email);
```

### Adding Tracking Variables

Mailjet lets you attach custom variables to emails for tracking in their dashboard:

```php
$email = EmailMessage::create()
    ->from('noreply@myapp.com')
    ->to('user@example.com')
    ->subject('Your Order is Shipped!')
    ->htmlBody('<p>Your package is on the way!</p>')
    ->withVariable('order_id', '12345')      // Track by order
    ->withVariable('user_segment', 'premium') // Track by user type
    ->withVariable('campaign', 'summer-2026'); // Track by campaign
```

These variables appear in Mailjet's statistics dashboard, letting you analyze email performance by segment.

---

## Asynchronous Sending

### Why Async ?

Consider this scenario: A user submits your registration form. Your server needs to :

1. Validate the form data
2. Create a user record in the database
3. Send a welcome email via Mailjet API
4. Return an HTTP response to the user

If Mailjet's API takes 500ms to respond (which is normal for network calls), your user waits 500ms+ just for the email. That's bad UX.

**With async sending:**
- Steps 1, 2, and 4 happen in your normal HTTP request (~10ms total)
- Step 3 is **queued** - a background worker processes it a moment later
- Your user gets an instant response
- The email arrives in their inbox within seconds

### Setting Up Async

First, make sure Symfony Messenger is installed :

```bash
composer require symfony/messenger
```

Configure a transport in `config/packages/messenger.yaml`:

```yaml
framework:
    messenger:
        transports:
            # Use Redis for production (fast, reliable)
            async:
                dsn: '%env(MESSENGER_TRANSPORT_DSN)%'
                options:
                    auto_setup: true

        routing:
            # Route our email messages to the async transport
            'Fabconejo\MailjetBundle\Message\SendEmailMessage': async
```

Add to your `.env`:
```dotenv
# Use Redis (recommended for production):
MESSENGER_TRANSPORT_DSN=redis://localhost:6379/messages

# Or use database (simpler, no Redis needed):
# MESSENGER_TRANSPORT_DSN=doctrine://default?auto_setup=1
```

Now use `sendAsync()` in your code :

```php
// This returns INSTANTLY — email is queued!
$this->emailSender->sendAsync($email);
```

Start the worker (run in a separate terminal or via Supervisor) :

```bash
# Process messages for 1 hour, then restart (good for memory management)
php bin/console messenger:consume async --time-limit=3600

# Or with verbose output (useful for debugging):
php bin/console messenger:consume async -vv
```

### For Production : Use Supervisor

[Supervisor](http://supervisord.org/) keeps your worker running even if it crashes :

```ini
# /etc/supervisor/conf.d/messenger.conf
[program:symfony-messenger]
command=php /var/www/html/bin/console messenger:consume async --time-limit=3600
user=www-data
numprocs=2
autostart=true
autorestart=true
stderr_logfile=/var/log/supervisor/messenger-error.log
stdout_logfile=/var/log/supervisor/messenger.log
```

---

## Receiving Webhook Events

### What is a Webhook ?

A webhook is the **reverse** of a normal API call :

- **Normal API** : YOUR app → sends a request → Mailjet responds
- **Webhook** : Mailjet → sends a request → YOUR app receives it

When something happens to your emails (delivered, bounced, opened, link clicked, unsubscribed, marked as spam), Mailjet sends a **POST request** to a URL you configure. This lets you react in real time.

### Configure Webhooks in Mailjet

1. Log in to [app.mailjet.com](https://app.mailjet.com)
2. Go to **Account Settings** → **Event Notifications / Triggers**
3. Add your webhook URL: `https://yourdomain.com/mailjet/webhook`
4. Select which events to receive (delivered, open, click, bounce, spam, unsub)

> Your webhook URL must be **publicly accessible** (Mailjet needs to reach it). In local development, use [ngrok](https://ngrok.com/) to create a temporary public URL.

### Add the Webhook Route

The bundle provides a controller. Add the route to your app's routing config :

```yaml
# config/routes/mailjet.yaml
mailjet_webhook:
    resource: '@MailjetBundle/src/Webhook/WebhookController.php'
    type: attribute
```

### Listen to Webhook Events in Your App

You can extend the webhook controller's behavior by listening to Symfony events that it dispatches, or by customizing the `processEvent()` method in your own controller that extends it.

For simple use cases, the built-in controller logs all events automatically. For production, add your own event listener:

```php
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;

// This is YOUR custom code, not part of the bundle
#[AsEventListener('mailjet.email_sent')]
class HandleEmailSentListener
{
    public function __invoke(EmailSentEvent $event): void
    {
        $email = $event->getEmail();
        $messageIds = $event->getMailjetMessageIds();
        
        // Example: save message IDs to database for delivery tracking
        // $this->emailRepository->updateWithMessageIds($email, $messageIds);
    }
}
```

---

## Running the Tests

### Install test dependencies

```bash
composer install  # Installs everything including dev dependencies
```

### Run all tests

```bash
composer test
# Equivalent to: vendor/bin/phpunit
```

### Run with coverage report (HTML)

```bash
vendor/bin/phpunit --coverage-html coverage/
# Open coverage/index.html in your browser to see coverage
```

### Run a specific test file

```bash
vendor/bin/phpunit tests/Unit/DTO/EmailAddressTest.php
```

### Run static analysis

```bash
composer stan
# Equivalent to: vendor/bin/phpstan analyse --level=9
```

### Check code style

```bash
composer cs-check   # Just check (no changes)
composer cs-fix     # Auto-fix all style issues
```

### Run everything at once

```bash
composer quality
# Runs: cs-check → stan → tests
```

---

## Project Architecture

Here's how the code is organized, and why :

```
symfony-mailjet-bundle/
│
├── src/
│   ├── MailjetBundle.php           ← Entry point: tells Symfony this bundle exists
│   │
│   ├── Contract/                   ← Interfaces (the "contracts")
│   │   ├── MailjetClientInterface.php   ← Contract for the HTTP client
│   │   └── EmailSenderInterface.php     ← Contract for the high-level sender
│   │
│   ├── DTO/                        ← Data Transfer Objects (typed data containers)
│   │   ├── EmailAddress.php        ← Validated email address (readonly, immutable)
│   │   ├── Attachment.php          ← File attachment with size validation
│   │   └── EmailMessage.php        ← The main email builder (fluent API)
│   │
│   ├── Exception/                  ← Custom exceptions for clear error handling
│   │   ├── MailjetApiException.php ← API/network errors
│   │   └── InvalidEmailException.php ← Bad input data errors
│   │
│   ├── Http/                       ← The actual HTTP communication layer
│   │   └── MailjetClient.php       ← Calls the Mailjet API via Symfony HttpClient
│   │
│   ├── Message/                    ← Symfony Messenger messages
│   │   └── SendEmailMessage.php    ← A "job" that gets queued for async processing
│   │
│   ├── Handler/                    ← Symfony Messenger handlers
│   │   └── SendEmailMessageHandler.php  ← Processes queued email jobs
│   │
│   ├── Event/                      ← Symfony Events (for extensibility)
│   │   ├── EmailSentEvent.php      ← Fired after successful send
│   │   └── EmailFailedEvent.php    ← Fired after failed send
│   │
│   ├── Service/                    ← High-level business logic
│   │   └── MailjetEmailSender.php  ← The service you inject in YOUR code
│   │
│   ├── Webhook/                    ← Handling events FROM Mailjet
│   │   └── WebhookController.php   ← Receives and verifies webhook POST requests
│   │
│   └── DependencyInjection/        ← Symfony integration
│       ├── Configuration.php       ← Defines mailjet.yaml config structure
│       └── MailjetExtension.php    ← Registers all services in Symfony's DI container
│
├── tests/
│   ├── Unit/                       ← Tests that test one class in isolation
│   │   ├── DTO/
│   │   │   ├── EmailAddressTest.php
│   │   │   └── EmailMessageTest.php
│   │   └── Http/
│   │       └── MailjetClientTest.php
│   └── Integration/                ← Tests that test multiple classes together
│
├── config/
│   └── services.yaml               ← Example configuration
│
├── demo/
│   └── index.html                  ← GitHub Pages demo site
│
├── .github/
│   ├── workflows/
│   │   └── ci.yml                  ← GitHub Actions: auto-runs tests on push
│   ├── CONTRIBUTING.md
│   └── ISSUE_TEMPLATE/
│
├── composer.json                   ← PHP package definition (like package.json)
├── phpunit.xml                     ← Test runner configuration
├── phpstan.neon                    ← Static analysis configuration
├── .php-cs-fixer.php               ← Code style configuration
├── .gitignore
├── README.md                       ← This file!
└── SECURITY.md                     ← Security policy
```

### Design Patterns Used

| Pattern | Where | Why |
|---------|-------|-----|
| **Builder** | `EmailMessage` | Fluent API for building complex objects |
| **Value Object** | `EmailAddress`, `Attachment` | Immutable, self-validating data |
| **Dependency Injection** | All services | Decouples classes, enables testing |
| **Interface/Contract** | `MailjetClientInterface`, `EmailSenderInterface` | Swappable implementations |
| **Command/Message** | `SendEmailMessage` | Enables async processing |
| **Observer/Event** | `EmailSentEvent`, `EmailFailedEvent` | Extensibility without coupling |

---

## For Teachers & Students

### Learning Objectives

This project demonstrates several important programming concepts:

#### Object-Oriented Programming (OOP)
- **Classes and Objects**: Every file is a class. `EmailMessage`, `EmailAddress`, `MailjetClient` are all classes.
- **Encapsulation**: Private properties with public methods. You can't directly change `EmailMessage::$from` — you must use `->from()`.
- **Inheritance**: `MailjetApiException extends RuntimeException` — it inherits all exception behavior and adds specific ones.
- **Interfaces**: `EmailSenderInterface` is a contract that multiple classes can implement. Allows swapping implementations.

#### SOLID Principles
- **S**ingle Responsibility: Each class does ONE thing. `MailjetClient` only handles HTTP. `EmailMessage` only holds email data.
- **O**pen/Closed: You can extend behavior via events without modifying the bundle's source.
- **L**iskov Substitution: `MailjetEmailSender` implements `EmailSenderInterface` — you can swap it for a test fake.
- **I**nterface Segregation: Two separate interfaces for two different concerns (HTTP client vs. high-level sender).
- **D**ependency Inversion: High-level code depends on abstractions (interfaces), not concrete classes.

#### PHP 8.3 Features Used
- `readonly` classes and properties (immutable value objects)
- `declare(strict_types=1)` (strict type checking)
- Named arguments (`new MailjetClient(apiKey: 'x', secretKey: 'y')`)
- Nullsafe operator (`$obj?->method()`)
- Match expressions
- Union types (`string|null`)
- Constructor property promotion

#### Testing Concepts
- **Unit tests** with PHPUnit
- **Mocking** with `MockHttpClient` (replace real HTTP with fake responses)
- **Data Providers** for testing multiple inputs
- **AAA pattern** (Arrange, Act, Assert)
- **Test coverage** measurement

### Using This Project in a Course

This project can be used to teach:

1. **Week 1**: PHP OOP basics using `EmailAddress` as an example of a simple class
2. **Week 2**: Interfaces and dependency injection using the service layer
3. **Week 3**: Testing with PHPUnit — run the existing tests, then write new ones
4. **Week 4**: HTTP APIs — how `MailjetClient` communicates with external services
5. **Week 5**: Async programming — Symfony Messenger, queues, workers
6. **Week 6**: Security — API key management, webhook verification

### Workshop Exercise Ideas

1. **Add CC/BCC validation**: Verify that BCC recipients aren't also in the TO list
2. **Add Twig integration**: Render a Twig template as the email body
3. **Add email templates**: Support Mailjet's server-side template variables
4. **Build a test listener**: Create an `EmailSentEvent` listener that logs to a database
5. **Add retry logic**: Automatically retry when Mailjet returns a 429 (rate limit) error

---

## Contributing

Contributions are welcome from **everyone** - whether you're a student learning PHP, a professional developer, or a teacher improving the documentation!

### How to Contribute

1. **Fork** this repository on GitHub
2. **Clone** your fork: `git clone https://github.com/YOUR_USERNAME/symfony-mailjet-bundle.git`
3. **Create a branch**: `git checkout -b feature/my-improvement`
4. **Make your changes** (with tests!)
5. **Run quality checks**: `composer quality`
6. **Push** and open a **Pull Request**

### Good First Issues

Look for issues labeled `good first issue` on GitHub - these are small, well-defined tasks perfect for newcomers.

### What We Need

- Bug reports and fixes
-  Documentation improvements
-  More test cases
-  New features (open an issue first to discuss!)
-  Translations of comments to other languages

### Code Style

We follow the **Symfony Coding Standards**. Run `composer cs-fix` before committing to auto-format your code.

---

## License

This project is licensed under the **MIT License** — one of the most permissive open-source licenses.

### What MIT means for you :

 You CAN use this in commercial projects  
 You CAN modify the code  
 You CAN redistribute it  
 You CAN use it privately  
 The author provides NO warranty  
 You MUST include the copyright notice  

Full license text: [https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT)

---

## Author

**Fabien Conéjéro**

- GitHub: [@fabienconejero](https://github.com/madjeek-web)
- Repository: [github.com/fabconejo/symfony-mailjet-bundle](https://github.com//madjeek-web/symfony-mailjet-bundle)

*Created on February 20, 2026*

---

## Useful Links

| Resource | URL |
|----------|-----|
| Mailjet Official Website | [https://www.mailjet.com](https://www.mailjet.com) |
| Mailjet API Documentation | [https://dev.mailjet.com/email/reference/](https://dev.mailjet.com/email/reference/) |
| Mailjet API Keys | [https://app.mailjet.com/account/apikeys](https://app.mailjet.com/account/apikeys) |
| Symfony Official Website | [https://symfony.com](https://symfony.com) |
| Symfony Messenger Docs | [https://symfony.com/doc/current/messenger.html](https://symfony.com/doc/current/messenger.html) |
| Symfony HttpClient Docs | [https://symfony.com/doc/current/http_client.html](https://symfony.com/doc/current/http_client.html) |
| PHP 8.3 Release Notes | [https://www.php.net/releases/8.3/en.php](https://www.php.net/releases/8.3/en.php) |
| Composer (PHP package manager) | [https://getcomposer.org](https://getcomposer.org) |
| PHPUnit Testing Framework | [https://phpunit.de](https://phpunit.de) |
| PHPStan Static Analysis | [https://phpstan.org](https://phpstan.org) |

---

* If this project helped you, please star it on GitHub! It encourages continued development.
