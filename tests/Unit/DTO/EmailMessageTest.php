<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\Tests\Unit\DTO;

use Madjeek\MailjetBundle\DTO\EmailMessage;
use Madjeek\MailjetBundle\Exception\InvalidEmailException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * EmailMessageTest — Tests for the EmailMessage builder
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class EmailMessageTest extends TestCase
{
    #[Test]
    public function itBuildsACompleteEmailMessageSuccessfully(): void
    {
        $email = EmailMessage::create()
            ->from('sender@example.com', 'Sender Name')
            ->to('recipient@example.com', 'Recipient')
            ->subject('Hello World!')
            ->htmlBody('<h1>Hello</h1>')
            ->textBody('Hello');

        $array = $email->toArray();

        $this->assertSame(['Email' => 'sender@example.com', 'Name' => 'Sender Name'], $array['From']);
        $this->assertSame([['Email' => 'recipient@example.com', 'Name' => 'Recipient']], $array['To']);
        $this->assertSame('Hello World!', $array['Subject']);
        $this->assertSame('<h1>Hello</h1>', $array['HTMLPart']);
        $this->assertSame('Hello', $array['TextPart']);
    }

    #[Test]
    public function itSupportsChainingMultipleRecipients(): void
    {
        $email = EmailMessage::create()
            ->from('sender@example.com')
            ->to('alice@example.com', 'Alice')
            ->to('bob@example.com', 'Bob')
            ->to('carol@example.com', 'Carol')
            ->subject('Team update')
            ->textBody('Hi team!');

        $array = $email->toArray();

        $this->assertCount(3, $array['To']);
        $this->assertSame('alice@example.com', $array['To'][0]['Email']);
        $this->assertSame('bob@example.com', $array['To'][1]['Email']);
        $this->assertSame('carol@example.com', $array['To'][2]['Email']);
    }

    #[Test]
    public function itThrowsWhenFromIsMissing(): void
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessageMatches('/"from"/i');

        EmailMessage::create()
            ->to('recipient@example.com')
            ->subject('Test')
            ->htmlBody('<p>Test</p>')
            ->toArray();
    }

    #[Test]
    public function itThrowsWhenToIsMissing(): void
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessageMatches('/"to"/i');

        EmailMessage::create()
            ->from('sender@example.com')
            ->subject('Test')
            ->htmlBody('<p>Test</p>')
            ->toArray();
    }

    #[Test]
    public function itThrowsWhenSubjectIsMissing(): void
    {
        $this->expectException(InvalidEmailException::class);

        EmailMessage::create()
            ->from('sender@example.com')
            ->to('recipient@example.com')
            ->htmlBody('<p>Test</p>')
            ->toArray();
    }

    #[Test]
    public function itThrowsWhenBothBodiesAreMissing(): void
    {
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessageMatches('/html body or a text body/i');

        EmailMessage::create()
            ->from('sender@example.com')
            ->to('recipient@example.com')
            ->subject('Test')
            ->toArray();
    }

    #[Test]
    public function itIncludesCcAndBccWhenProvided(): void
    {
        $email = EmailMessage::create()
            ->from('sender@example.com')
            ->to('main@example.com')
            ->cc('cc@example.com')
            ->bcc('bcc@example.com')
            ->subject('Test')
            ->textBody('Test body');

        $array = $email->toArray();

        $this->assertArrayHasKey('Cc', $array);
        $this->assertArrayHasKey('Bcc', $array);
        $this->assertSame('cc@example.com', $array['Cc'][0]['Email']);
        $this->assertSame('bcc@example.com', $array['Bcc'][0]['Email']);
    }

    #[Test]
    public function itDoesNotIncludeEmptyCcOrBcc(): void
    {
        $email = EmailMessage::create()
            ->from('sender@example.com')
            ->to('main@example.com')
            ->subject('Test')
            ->textBody('Test body');

        $array = $email->toArray();

        $this->assertArrayNotHasKey('Cc', $array);
        $this->assertArrayNotHasKey('Bcc', $array);
    }

    #[Test]
    public function itAddsCustomVariables(): void
    {
        $email = EmailMessage::create()
            ->from('sender@example.com')
            ->to('user@example.com')
            ->subject('Order Confirmation')
            ->textBody('Your order is confirmed')
            ->withVariable('order_id', '12345')
            ->withVariable('user_type', 'premium');

        $array = $email->toArray();

        $this->assertArrayHasKey('Variables', $array);
        $this->assertSame('12345', $array['Variables']['order_id']);
        $this->assertSame('premium', $array['Variables']['user_type']);
    }

    #[Test]
    public function itEnforcesPriorityBounds(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        EmailMessage::create()->withPriority(10); // Must be 1-5
    }

    #[Test]
    public function itIncludesReplyToWhenProvided(): void
    {
        $email = EmailMessage::create()
            ->from('noreply@example.com')
            ->replyTo('support@example.com', 'Support Team')
            ->to('user@example.com')
            ->subject('Test')
            ->textBody('Test');

        $array = $email->toArray();

        $this->assertArrayHasKey('ReplyTo', $array);
        $this->assertSame('support@example.com', $array['ReplyTo']['Email']);
    }
}
