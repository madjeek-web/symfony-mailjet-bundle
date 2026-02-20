<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\DTO;

use Fabconejo\MailjetBundle\Exception\InvalidEmailException;

/**
 * EmailMessage — The main object that represents ONE email to be sent
 *
 * 📧 Think of this like filling out a physical letter:
 *   - From: who sends it
 *   - To: who receives it (can be multiple people)
 *   - CC: other recipients who receive a copy (visible to everyone)
 *   - BCC: hidden recipients (they get a copy but nobody knows)
 *   - Subject: the title of the email
 *   - HTML body: the styled content (like a webpage)
 *   - Text body: plain text fallback (for email clients that don't support HTML)
 *   - Attachments: files to include
 *
 * 🔧 Builder Pattern:
 * Instead of a constructor with 10+ parameters, we use a fluent builder:
 *
 *   $email = EmailMessage::create()
 *       ->from('sender@example.com', 'My App')
 *       ->to('user@example.com', 'John Doe')
 *       ->subject('Welcome!')
 *       ->htmlBody('<h1>Hello World</h1>');
 *
 * Each method returns $this (the same object), so you can chain calls.
 * This is called a "Fluent Interface".
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class EmailMessage
{
    /** The person/address sending this email */
    private EmailAddress|null $from = null;

    /** The "Reply-To" address — where replies go if different from "from" */
    private EmailAddress|null $replyTo = null;

    /** The main recipients — people who receive the email directly */
    /** @var EmailAddress[] */
    private array $to = [];

    /** CC recipients — people who get a copy (visible to all) */
    /** @var EmailAddress[] */
    private array $cc = [];

    /** BCC recipients — hidden recipients (invisible to others) */
    /** @var EmailAddress[] */
    private array $bcc = [];

    /** The subject line (title) of the email */
    private string|null $subject = null;

    /** The HTML version of the email body (with styling) */
    private string|null $htmlBody = null;

    /** The plain text version (no HTML) — used as a fallback */
    private string|null $textBody = null;

    /** Files attached to this email */
    /** @var Attachment[] */
    private array $attachments = [];

    /**
     * Optional custom variables sent with the email.
     * Useful for tracking: ["order_id" => "123", "user_id" => "456"]
     * These appear in Mailjet's statistics dashboard.
     *
     * @var array<string, mixed>
     */
    private array $variables = [];

    /**
     * Custom headers to add to the email (advanced use)
     * Example: ['X-Custom-Header' => 'my-value']
     *
     * @var array<string, string>
     */
    private array $headers = [];

    /**
     * The priority of the email (1 = highest, 5 = lowest)
     * Default is 2 (high priority for transactional emails)
     */
    private int $priority = 2;

    /**
     * Named constructor — cleaner way to start building an email
     *
     * Instead of: $email = new EmailMessage();
     * You write:   $email = EmailMessage::create();
     *
     * Both do the same thing, but create() reads more naturally.
     */
    public static function create(): self
    {
        return new self();
    }

    // =========================================================================
    // SENDER METHODS
    // =========================================================================

    /**
     * Set who this email is FROM
     *
     * @param string      $email The sender's email address
     * @param string|null $name  The sender's display name (optional but recommended!)
     *                           Email clients show "My App <noreply@myapp.com>" which looks professional
     */
    public function from(string $email, string|null $name = null): self
    {
        $this->from = new EmailAddress($email, $name);

        return $this; // Return $this so you can chain more method calls
    }

    /**
     * Set the Reply-To address
     * When the recipient clicks "Reply", their email goes here instead of to "from"
     * Useful for: newsletters where replies should go to support@example.com
     */
    public function replyTo(string $email, string|null $name = null): self
    {
        $this->replyTo = new EmailAddress($email, $name);

        return $this;
    }

    // =========================================================================
    // RECIPIENT METHODS
    // =========================================================================

    /**
     * Add a TO recipient (main recipient)
     * You can call this multiple times to add multiple recipients
     */
    public function to(string $email, string|null $name = null): self
    {
        $this->to[] = new EmailAddress($email, $name);

        return $this;
    }

    /**
     * Add a CC (Carbon Copy) recipient
     * They receive the email and everyone can see they received it
     */
    public function cc(string $email, string|null $name = null): self
    {
        $this->cc[] = new EmailAddress($email, $name);

        return $this;
    }

    /**
     * Add a BCC (Blind Carbon Copy) recipient
     * They receive the email but nobody else knows they received it
     * Commonly used for: logging, compliance, secret monitoring
     */
    public function bcc(string $email, string|null $name = null): self
    {
        $this->bcc[] = new EmailAddress($email, $name);

        return $this;
    }

    // =========================================================================
    // CONTENT METHODS
    // =========================================================================

    /**
     * Set the subject line (the title shown in the inbox)
     * Keep it under 60 characters for best readability on mobile!
     */
    public function subject(string $subject): self
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * Set the HTML body (the styled email content)
     *
     * This is a full HTML string. You can use your Twig templates:
     *   ->htmlBody($twig->render('emails/welcome.html.twig', ['user' => $user]))
     *
     * 💡 TIP: Always provide a textBody too! Some email clients (like old corporate
     *          Outlook) don't support HTML, and spam filters prefer emails with both.
     */
    public function htmlBody(string $html): self
    {
        $this->htmlBody = $html;

        return $this;
    }

    /**
     * Set the plain text body (fallback for clients that don't support HTML)
     *
     * Keep this as a simple, readable version of your HTML content.
     * Strip out all the HTML tags and keep just the text.
     */
    public function textBody(string $text): self
    {
        $this->textBody = $text;

        return $this;
    }

    // =========================================================================
    // ATTACHMENT METHODS
    // =========================================================================

    /**
     * Attach a file to this email using a file path
     *
     * Example:
     *   ->attachFile('/var/www/invoices/invoice-456.pdf')
     *   ->attachFile('/tmp/report.xlsx', 'Monthly Report.xlsx')
     */
    public function attachFile(string $filePath, string|null $filename = null): self
    {
        $this->attachments[] = Attachment::fromPath($filePath, $filename);

        return $this;
    }

    /**
     * Attach a file using raw binary content (useful when you generate files in memory)
     *
     * Example: attaching a dynamically generated PDF without saving to disk
     *   ->attach('invoice.pdf', $pdfContent, 'application/pdf')
     */
    public function attach(string $filename, string $content, string $mimeType): self
    {
        $this->attachments[] = new Attachment($filename, $content, $mimeType);

        return $this;
    }

    // =========================================================================
    // ADVANCED OPTIONS
    // =========================================================================

    /**
     * Add custom tracking variables (shown in Mailjet statistics)
     *
     * Example:
     *   ->withVariable('order_id', '12345')
     *   ->withVariable('user_type', 'premium')
     */
    public function withVariable(string $key, mixed $value): self
    {
        $this->variables[$key] = $value;

        return $this;
    }

    /**
     * Add a custom email header
     *
     * Example:
     *   ->withHeader('X-Campaign-ID', 'summer-sale-2026')
     */
    public function withHeader(string $name, string $value): self
    {
        $this->headers[$name] = $value;

        return $this;
    }

    /**
     * Set email priority (1 = highest, 5 = lowest)
     * Most email clients show a priority indicator based on this.
     */
    public function withPriority(int $priority): self
    {
        if ($priority < 1 || $priority > 5) {
            throw new \InvalidArgumentException('Email priority must be between 1 (highest) and 5 (lowest).');
        }

        $this->priority = $priority;

        return $this;
    }

    // =========================================================================
    // VALIDATION
    // =========================================================================

    /**
     * Check that this email has all required fields before sending
     *
     * @throws InvalidEmailException If required fields are missing
     */
    public function validate(): void
    {
        if ($this->from === null) {
            throw new InvalidEmailException('The "from" address is required. Call ->from() on your EmailMessage.');
        }

        if (empty($this->to)) {
            throw new InvalidEmailException('At least one "to" recipient is required. Call ->to() on your EmailMessage.');
        }

        if ($this->subject === null || trim($this->subject) === '') {
            throw new InvalidEmailException('The subject is required. Call ->subject() on your EmailMessage.');
        }

        if ($this->htmlBody === null && $this->textBody === null) {
            throw new InvalidEmailException(
                'Either an HTML body or a text body is required. '
                . 'Call ->htmlBody() or ->textBody() on your EmailMessage.'
            );
        }
    }

    // =========================================================================
    // CONVERSION TO API FORMAT
    // =========================================================================

    /**
     * Convert this EmailMessage to the format Mailjet API v3.1 expects
     *
     * Mailjet expects a JSON body like this:
     * {
     *   "Messages": [{
     *     "From": {"Email": "from@example.com", "Name": "Sender"},
     *     "To": [{"Email": "to@example.com", "Name": "Recipient"}],
     *     "Subject": "Hello!",
     *     "HTMLPart": "<h1>Hello</h1>",
     *     "TextPart": "Hello"
     *   }]
     * }
     *
     * @return array<string, mixed> The message as an array ready for JSON encoding
     */
    public function toArray(): array
    {
        $this->validate();

        // Build the base message structure
        $message = [
            'From'     => $this->from->toArray(),
            'To'       => array_map(fn(EmailAddress $addr) => $addr->toArray(), $this->to),
            'Subject'  => $this->subject,
            'Priority' => $this->priority,
        ];

        // Only include optional fields if they have values
        // (Mailjet's API doesn't like empty arrays or null values)

        if ($this->replyTo !== null) {
            $message['ReplyTo'] = $this->replyTo->toArray();
        }

        if (!empty($this->cc)) {
            $message['Cc'] = array_map(fn(EmailAddress $addr) => $addr->toArray(), $this->cc);
        }

        if (!empty($this->bcc)) {
            $message['Bcc'] = array_map(fn(EmailAddress $addr) => $addr->toArray(), $this->bcc);
        }

        if ($this->htmlBody !== null) {
            $message['HTMLPart'] = $this->htmlBody;
        }

        if ($this->textBody !== null) {
            $message['TextPart'] = $this->textBody;
        }

        if (!empty($this->attachments)) {
            $message['Attachments'] = array_map(
                fn(Attachment $att) => $att->toArray(),
                $this->attachments
            );
        }

        if (!empty($this->variables)) {
            $message['Variables'] = $this->variables;
        }

        if (!empty($this->headers)) {
            $message['Headers'] = $this->headers;
        }

        return $message;
    }

    // =========================================================================
    // GETTERS (read-only access to private properties)
    // =========================================================================

    public function getFrom(): EmailAddress|null
    {
        return $this->from;
    }

    /** @return EmailAddress[] */
    public function getTo(): array
    {
        return $this->to;
    }

    public function getSubject(): string|null
    {
        return $this->subject;
    }
}
