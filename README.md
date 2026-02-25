<p>
    <img src="https://github.com/madjeek-web/symfony-mailjet-bundle/raw/main/symfony-framework.webp" width="40%" height="40%" hspace="10" >
    <img src="https://github.com/madjeek-web/symfony-mailjet-bundle/raw/main/y-symfony.jpg" hspace="10" >
</p>


# symfony-mailjet-bundle

> **A modern, async-ready Symfony 7 bundle for sending emails via Mailjet.**
> Built with PHP 8.3, Symfony Messenger, HttpClient, Webhooks & 100% test coverage.

[![CI](https://github.com/madjeek-web/symfony-mailjet-bundle/actions/workflows/ci.yml/badge.svg)](https://github.com/madjeek-web/symfony-mailjet-bundle/actions)
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

It's a professional tool, generously shared with the community.

What this project does :

Symfony is a tool for building websites in PHP. It can do a lot of things, but it doesn't know how to send emails via Mailjet. Mailjet is an external service, like a digital postal service.

This project acts as a bridge between the two.

Without this project, a developer wanting to send an email via Mailjet in their Symfony application would have to write all the connection, formatting, security, and error-handling code themselves... taking several days of work.

With this project, they simply write :

```php
$emailSender->sendNow($email); // ← that's it
And the email is sent. The project takes care of everything else behind the scenes.

It's like a universal power adapter: Symfony is the wall outlet, Mailjet is the device, and this bundle is the adapter that connects them properly.

```
<img src="https://github.com/madjeek-web/symfony-mailjet-bundle/raw/main/screen_demo_mailjet.jpg" alt="symfony mailjet bundle page demo" width="100%" height="100%">

### Why send emails from Symfony rather than with your regular email service ?

When you send an email from your Gmail or Outlook inbox, you're using a human interface: you write a message, you click "Send", and one single person receives it. It's manual.

But when your website needs to automatically send 100 order confirmation emails in the middle of the night, or 10,000 personalized newsletters, your small regular email service can't keep up. It will:

Limit the number of sends (often 500 per day max)

Filter your emails as spam if you send too many

Crash if too many people sign up at the same time

The fundamental difference: With a regular email service, it's a human who sends. With Symfony + Mailjet, it's the code that sends, in an automated, industrialized, and reliable way.

Symfony is not going to "open Gmail" and click on buttons. It will communicate directly with Mailjet's servers via an API (a machine-to-machine language) to deliver hundreds of emails in one second, with precise statistics: who opened, who clicked, who didn't receive...

The real purpose: To ensure that your emails actually arrive in the inbox (not in spam), that it's reliable 24/7, and that your site remains fast even when sending tons of emails. Your personal email service is designed for you to communicate yourself, not to do the work of an industrial mail carrier for your website.

It's a bit like if you wanted to deliver pizzas. Your personal email inbox is your bike: it's perfect for bringing a pizza to your friend who lives nearby. But if suddenly 500 people order pizzas at the same time, you're not going to make it with your bike, you're going to be overwhelmed and the pizzas will arrive cold or not at all.

Symbole with Mailjet, it's like having a fleet of scooters with professional delivery drivers. They can deliver hundreds of orders at once, very quickly, and on top of that they tell you precisely who received their pizza, who ate it, and who wasn't home. Your little personal bike can't do all that.




## The right question to ask

The question "Can you send emails in Symfony with Mailjet?" doesn't really make sense, a bit like asking: " Can you cook pasta with a pressure cooker ? "

Actually, both options are possible :

You can cook pasta without a pressure cooker (in a normal pot)

You can cook pasta with a pressure cooker

But the pressure cooker won't cook the pasta all by itself for you

Here's the right way to understand
Mailjet is just a shipping service: It's like the Post Office or FedEx. Mailjet has super powerful servers that can send millions of emails very fast, with precise statistics, while avoiding spam.

Symfony is a letter writer: It's your PHP code that decides when to send an email, to whom, with which text. For example : "When a user signs up, create a welcome email with their first name."

So the real question is: "Can Symfony entrust its emails to Mailjet so that it sends them out ?"
Answer : YES, 100%. Symfony prepares the email content (the text, the recipient), then it gives it to Mailjet which takes care of fast and reliable shipping. It's like if you wrote a letter (Symfony) and gave it to FedEx (Mailjet) to send it super fast everywhere in the world, rather than putting it yourself in a neighborhood mailbox.

You don't choose between Symfony and Mailjet. You choose to use them together :

Symfony = your personal assistant who writes the emails automatically

Mailjet = your ultra-fast delivery service

Symfony = your personal assistant who writes the emails automatically. 

Ok but based on what criteria does it write the emails? randomly, according to the people, according to the profiles ? 

Now we get into the real functioning. Here's how Symfony decides what to write, simply.

Symfony does not write emails all by itself like magic
First super important thing: Symfony is not an artificial intelligence that invents the email text. It's not ChatGPT ! Symfony does what vous programmed it to do.

How does it work concretely ?
Imagine that Symfony is your super organized assistant but who only repeats what you taught it. It has letter templates (we call them templates) that YOU created in advance.

Example of a template that you write in your code:

text
"Hello [FIRSTNAME], thank you for ordering [PRODUCT] on our site!"
Then, Symfony will replace the words in brackets with the real data of each person.

Based on what criteria does Symfony decide the content?
Symfony looks in its database (its big address book with all the info) and it chooses according to what you asked it to check.

Criteria #1: The PERSON'S PROFILE
Symfony looks for the info stored about the user :

Their first name → to personalize "Hello Thomas" or "Hello Léa"

Their age → to suggest adapted games

Their city → to give info about an event near them

Their previous purchases → to recommend similar products

Criteria #2: The action that the person just did
This is the most important criterion ! 

Symfony decides to send an email when an event happens :

Sign-up → Automatic welcome email

Purchase validated → Confirmation email with the list of items

Forgotten password → Email with a link to reset it

Abandoned cart (the site detects that someone filled their cart but didn't pay) → Email like "Hey, you forgot your items !"

Birthday (if the date is in the database) → Email with a promo code

Criteria #3 : The PERSON'S BEHAVIOR (advanced version)
With tools like Mailjet, Symfony can even react to what people did BEFORE:

"They didn't open our last 3 emails" → we send them a different email to wake them up

"They clicked on the sneakers but didn't buy" → we send them a special promo on sneakers

Summary with a concrete example
Situation : Thomas (16 years old, fan of soccer games) signs up on your site.

You programmed: "When someone signs up, send the welcome email"

Symfony detects: "Alert ! Thomas just signed up !"

Symfony looks in its database: "He likes soccer, he's 16 years old"

Symfony takes the email template you created : "Welcome [FIRSTNAME]! Discover our favorite games for [AGE] year olds!"

Symfony replaces: "Welcome Thomas! Discover our favorite games for 16 year olds !"

Symfony hands the email to Mailjet which sends it to Thomas

So YOU are the boss! You decide everything: when to send, to whom, and which template to use. Symfony executes your orders at lightning speed.

so mailjet cannot do that itself ?
Can Mailjet do it all by itself ?
Short answer: YES and NO. This is where it gets interesting !

What Mailjet can do ALL BY ITSELF
Mailjet has its own little assistants (without you needing Symfony). It can:

- Send scheduled emails: "Every Monday at 10am, send this newsletter"
- Do simple automations : "When someone subscribes to my list, send them the welcome email"
- Segment according to profiles : "Show this block if the person is a boy, this other one if it's a girl"

How does it do it? Mailjet has a visual interface with "blocks" to click. You don't write code, you drag and drop to create scenarios.

But... the big limits of Mailjet all by itself
- It does NOT know your site: Mailjet doesn't know that a user just bought a soccer jersey on your site. It doesn't see that someone filled a cart. It is blind to what happens elsewhere than on its platform.

- It cannot react instantly: If Thomas signs up on your site at 3am, how would Mailjet know? Someone would have to go on Mailjet to trigger the send.

- It doesn't know all your data: Mailjet has its own small contact list. But it doesn't know Thomas's score on your game, his level, his friends, his complete history on your site.

This is where Symfony becomes essential
Symfony is the link between your site and Mailjet.

Your site (with Symfony) sees everything :

- It sees Thomas sign up

- It sees Thomas buy

- It sees Thomas reach level 10

- It sees Thomas lose his password

Each time, Symfony says: "Hey Mailjet, quick send this special email to Thomas !"

The restaurant analogy
Mailjet by itself = A meal delivery service. You can tell them: "Deliver a pizza to 15 Lilas Street every Friday evening." But you have to give them the address and tell them what to deliver.

Symfony + Mailjet = A full restaurant. Symfony is the chef in the kitchen and the waiter who takes orders. As soon as a customer orders, the waiter (Symfony) shouts to the kitchen (your code), and the kitchen calls the delivery person (Mailjet) to dispatch the dish immediately.

Why not do everything with Mailjet then ?
You could do everything with Mailjet IF :

Your site is very small

You just want to send basic newsletters

You accept having to configure everything manually on their site

But as soon as your site becomes a bit serious (automatic sign-ups, e-commerce, games with scores...), you really need Symfony to be the conductor.

In short : Mailjet is a great delivery person. But it's Symfony who knows when to call the delivery person and WHAT to give them to deliver.



## Can you send emails in Symfony with Mailjet ?

Yes, absolutely. And it's even one of the main uses for which Mailjet is designed.

On their site, they say it's "built for devs" and that you can "Integrate with our API in minutes and start sending". An API is a language that allows your Symfony code (PHP) to talk directly to Mailjet's servers to tell it to send an email.

It's not Mailjet "or" Symfony, it's Mailjet "and" Symfony working together.

Mailjet by itself vs. Mailjet + Symfony: which to choose ?
Imagine that Mailjet is an ultra-modern factory that prints and ships letters (emails) to millions of addresses. It has super fast machines, quality paper, and it knows exactly which letter was sent, read, or thrown away.

If you use Mailjet by itself (without Symfony) :
It's like going to that factory yourself with a list of names and addresses on a piece of paper. You use their on-site computer to type each letter one by one (via their "drag-and-drop" interface for newsletters). It's perfect for one-off marketing campaigns that you prepare by hand, like a monthly newsletter for your video game club.

If you combine Symfony and Mailjet :
Then, it's your website (built with Symfony) that becomes the conductor. It tells the Mailjet factory: "When a new user signs up, automatically send them this welcome email" or "When an order is validated, send the confirmation". Symfony handles the logic and the triggering (WHEN and WHY to send), and Mailjet handles the ultra-fast shipping and tracking (HOW to send it and knowing if it arrived properly).

Why is it better to combine them ?
It's automated and reliable: You don't need to go to the Mailjet site at 3 a.m. to send 100 order confirmation emails. Symfony does it by itself, and Mailjet ensures they arrive quickly.

It's more powerful: Symfony can send personalized emails with data from your database (e.g., "Congratulations [Player Name] for your score of [Level]!"). It's much stronger than writing them all by hand in Mailjet.

You keep Mailjet's power: Even by going through Symfony, you benefit from all of Mailjet's great tools: statistics (who opened the email), deliverability (not in spam), and contact management.

Why would it not be better (or simpler) ?
You need to know how to code: For Symfony to talk to Mailjet, you have to write PHP code, install libraries, and manage errors. It's a bit more work at the beginning.

For very simple sends: If you just need to send a newsletter to your list from time to time, directly using Mailjet's "drag-and-drop" editor is much simpler and faster. You don't need a developer for that. The Mailjet site is made for that, with templates and tools for "marketers".

In summary : The Symfony + Mailjet combination is the Formula 1 for websites that need to send emails automatically, in a personalized way, and on a large scale. Using Mailjet by itself is the practical scooter for creating nice newsletters without the headache. The choice depends on what you want to build !




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

<img src="https://github.com/madjeek-web/symfony-mailjet-bundle/raw/main/mailjet-full-logo-png.png" alt="mailjet bundle full img" width="100%" height="100%">

## What is Mailjet ?

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

- Transactional emails : Welcome emails, password resets, order confirmations, invoices - triggered by your app
- Marketing emails : Newsletters, promotions, campaigns - sent to many recipients
- Real-time tracking : See who opened your email, who clicked a link, who unsubscribed
- Contact management : Store and manage your mailing lists
- Webhook notifications : Mailjet tells YOUR server when an email bounces, is opened, etc.

---

### What is the concrete purpose of it ?
- Send confirmation emails of registration
- Send transactional emails (invoice, reset password, notification)
- Send one-by-one personalized campaigns with a Twig template per recipient
- All without managing a mail server, just an API key

### + + +
Mailjet is a comprehensive platform that combines sending marketing and transactional emails, designed to be used by both developers and marketing teams.

Here is what it allows you to do concretely:

Create professional emails easily
Thanks to its drag-and-drop editor, you can design responsive emails, forms, and landing pages, even without technical skills. You start from a blank page, a pre-designed template, or use AI to generate templates and write content adapted to your brand (tone, length, language).

Manage and collaborate as a team
The platform includes collaboration tools to create emails with multiple people, with permission systems to protect elements of your brand guidelines. You can start alone and then invite colleagues as needed.

Personalize and automate sends
You can segment your contacts by interests, send personalized messages (e.g., different offers based on recipient behavior) and automate journeys like onboarding or re-engagement, via a visual builder.

Improve deliverability
So that your emails actually arrive in the inbox (and not in spam), Mailjet offers address validation tools upstream, a preview of email rendering, and real-time performance tracking (open rates, clicks, etc.) with filtering of non-human interactions.

Benefit from expert support at scale
For high sending volumes, a dedicated expert supports you with configuration, best practices, and problem resolution, with personalized follow-up.

Integrate with your other tools
The platform easily connects to your other software (CRM, CMS, e-commerce) via preconfigured integrations or its API, which developers can use to program automated sends.

Optimize with AI
AI assistants help generate custom templates, adapt the tone of messages (informal or formal) in 21 languages, and suggest text lengths to improve performance.

In summary, Mailjet is a complete service that manages the entire email chain: from visual creation to technical deliverability, through marketing automation and results analysis. It is aimed as much at marketing teams as at developers who, as with the Symfony bundle, use it to send emails programmatically.

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

<img src="https://github.com/madjeek-web/symfony-mailjet-bundle/raw/main/symfony_01.png" alt="symfony mailjet black cover img 01" width="100%" height="100%">

## What is this project ?

This is a **Symfony Bundle** - a reusable plugin for PHP applications built with the [Symfony framework](https://symfony.com/).

all the modern best practices available in 2026

### What this bundle does

1. **Provides a fluent PHP interface** to build and send emails using the Mailjet API v3.1
2. **Integrates deeply with Symfony 7** (Dependency Injection, Messenger, Events, HTTP Client)
3. **Supports async sending** via Symfony Messenger (your app doesn't wait for the email to be sent)
4. **Receives real-time events** from Mailjet via webhooks (bounces, opens, clicks)
5. **Is ultra-secure** with proper credential handling and webhook signature verification

---

## Why is this project relevant in 2026 ?

Here's why this matters right now :

### 1. PHP is booming
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
composer require madjeek-web/symfony-mailjet-bundle
```

If you're using **Symfony Flex** (included by default in new Symfony projects), the bundle will be automatically registered. If not, add it manually to `config/bundles.php`:

```php
// config/bundles.php
return [
    // ... other bundles ...
    Madjeek-web\MailjetBundle\MailjetBundle::class => ['all' => true],
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
###> madjeek-web/symfony-mailjet-bundle ###
MAILJET_API_KEY=your_api_key_here
MAILJET_SECRET_KEY=your_secret_key_here

# IMPORTANT: Keep true in dev/staging, set to false in production!
# When true, emails are validated but NOT actually sent
MAILJET_SANDBOX_MODE=true

# Optional: generate with: php -r "echo bin2hex(random_bytes(32));"
MAILJET_WEBHOOK_SECRET=
###< madjeek-web/symfony-mailjet-bundle ###
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
use Madjeek\MailjetBundle\DTO\EmailMessage;

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
use Madjeek-web\MailjetBundle\Contract\EmailSenderInterface;
use Madjeek-web\MailjetBundle\DTO\EmailMessage;
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
            'Madjeek-web\MailjetBundle\Message\SendEmailMessage': async
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

This project demonstrates several important programming concepts :

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

Contributions are welcome from whether you're a student learning PHP, a professional developer, or a teacher improving the documentation.

### How to Contribute

1. Fork this repository on GitHub
2. Clone your fork : `git clone https://github.com/YOUR_USERNAME/symfony-mailjet-bundle.git`
3. Create a branch : `git checkout -b feature/my-improvement`
4. Make your changes (with tests!)
5. Run quality checks : `composer quality`
6. Push and open a **Pull Request**

### Good First Issues

Look for issues labeled `good first issue` on GitHub - these are small, well-defined tasks perfect for newcomers.

### What We Need

- Bug reports and fixes
-  Documentation improvements
-  More test cases
-  New features (open an issue first to discuss)
-  Translations of comments to other languages

### Code Style

We follow the **Symfony Coding Standards**. Run `composer cs-fix` before committing to auto-format your code.

---

## License

This project is licensed under the **MIT License** one of the most permissive open-source licenses.

### What MIT means for you :

 You CAN use this in commercial projects  
 You CAN modify the code  
 You CAN redistribute it  
 You CAN use it privately  
 The author provides NO warranty  
 You MUST include the copyright notice  

Full license text : [https://opensource.org/licenses/MIT](https://opensource.org/licenses/MIT)

---

## Author

**Fabien Conéjéro**

- GitHub: [@Fabien_Conéjéro](https://github.com/madjeek-web)
- Repository: [github.com/madjeek/symfony-mailjet-bundle](https://github.com//madjeek-web/symfony-mailjet-bundle)

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

* If this project helped you, please star it on GitHub ! It encourages continued development.
* My bundle is installable by any Symfony developer in the world with :
```bash
composer require madjeek-web/symfony-mailjet-bundle
```
* The package is published on Packagist (https://packagist.org)
* GitHub webhook configuration for automatic update : Yes
* Creation of the release v1.0.0 : Yes
* The package is published on Packagist : https://packagist.org/packages/madjeek-web/symfony-mailjet-bundle
