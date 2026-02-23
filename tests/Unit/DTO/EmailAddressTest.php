<?php

declare(strict_types=1);

namespace Madjeek\MailjetBundle\Tests\Unit\DTO;

use Madjeek\MailjetBundle\DTO\EmailAddress;
use Madjeek\MailjetBundle\Exception\InvalidEmailException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

/**
 * EmailAddressTest — Unit tests for the EmailAddress DTO
 *
 * 🧪 What is a Unit Test?
 * A unit test verifies a single, small "unit" of code in isolation.
 * Here, we test only the EmailAddress class — nothing else.
 *
 * For each test method:
 * - We "arrange" the data (create inputs)
 * - We "act" (call the method we're testing)
 * - We "assert" (check the output is what we expected)
 * This is called the AAA pattern (Arrange, Act, Assert).
 *
 * Run these tests: vendor/bin/phpunit tests/Unit/DTO/EmailAddressTest.php
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class EmailAddressTest extends TestCase
{
    #[Test]
    public function itCreatesValidEmailAddressWithNameSuccessfully(): void
    {
        // Arrange
        $email = 'john@example.com';
        $name  = 'John Doe';

        // Act
        $emailAddress = new EmailAddress($email, $name);

        // Assert
        $this->assertSame($email, $emailAddress->email);
        $this->assertSame($name, $emailAddress->name);
    }

    #[Test]
    public function itCreatesValidEmailAddressWithoutName(): void
    {
        $emailAddress = new EmailAddress('test@example.com');

        $this->assertSame('test@example.com', $emailAddress->email);
        $this->assertNull($emailAddress->name);
    }

    #[Test]
    public function itThrowsExceptionForInvalidEmail(): void
    {
        // Expect an InvalidEmailException to be thrown
        $this->expectException(InvalidEmailException::class);
        $this->expectExceptionMessageMatches('/not valid/i');

        // This should throw because "not-an-email" is not a valid email address
        new EmailAddress('not-an-email');
    }

    /**
     * Test multiple invalid email formats using a Data Provider
     * Data Providers let you run the same test with different inputs — DRY!
     *
     * @return array<string, array{string}>
     */
    public static function invalidEmailProvider(): array
    {
        return [
            'missing @ symbol'   => ['notanemail'],
            'missing domain'     => ['user@'],
            'missing local part' => ['@domain.com'],
            'double @'           => ['user@@domain.com'],
            'spaces in email'    => ['user @domain.com'],
            'empty string'       => [''],
        ];
    }

    #[Test]
    #[DataProvider('invalidEmailProvider')]
    public function itRejectsInvalidEmailFormats(string $invalidEmail): void
    {
        $this->expectException(InvalidEmailException::class);
        new EmailAddress($invalidEmail);
    }

    #[Test]
    public function itConvertsToArrayWithName(): void
    {
        $emailAddress = new EmailAddress('jane@example.com', 'Jane Smith');

        $expected = [
            'Email' => 'jane@example.com',
            'Name'  => 'Jane Smith',
        ];

        $this->assertSame($expected, $emailAddress->toArray());
    }

    #[Test]
    public function itConvertsToArrayWithoutName(): void
    {
        $emailAddress = new EmailAddress('jane@example.com');

        $expected = ['Email' => 'jane@example.com'];

        // Name should NOT be in the array when null
        $this->assertSame($expected, $emailAddress->toArray());
        $this->assertArrayNotHasKey('Name', $emailAddress->toArray());
    }

    #[Test]
    public function itConvertsToStringWithName(): void
    {
        $emailAddress = new EmailAddress('john@example.com', 'John Doe');

        $this->assertSame('John Doe <john@example.com>', (string) $emailAddress);
    }

    #[Test]
    public function itConvertsToStringWithoutName(): void
    {
        $emailAddress = new EmailAddress('john@example.com');

        $this->assertSame('john@example.com', (string) $emailAddress);
    }

    #[Test]
    public function itIsImmutable(): void
    {
        // readonly properties cannot be modified — this verifies the readonly behavior
        $emailAddress = new EmailAddress('test@example.com', 'Test');

        // Attempt to modify should throw an Error (not an Exception)
        $this->expectException(\Error::class);

        // @phpstan-ignore-next-line (we're intentionally testing this throws)
        $emailAddress->email = 'hacked@example.com';
    }
}
