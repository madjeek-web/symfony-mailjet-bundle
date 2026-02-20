<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\DTO;

use Fabconejo\MailjetBundle\Exception\InvalidEmailException;

/**
 * EmailAddress — A simple, typed Value Object for an email address
 *
 * 🤔 What is a "Value Object" (DTO)?
 * A DTO (Data Transfer Object) is a simple object that just HOLDS data.
 * It doesn't have business logic — it's just a container with validation.
 *
 * Why use this instead of a plain string?
 * - A plain string "john@example.com" gives no guarantees
 * - This class ENSURES the email is valid before you can even create it
 * - It's "readonly" — once created, it CANNOT be changed (immutable)
 *   This prevents bugs where someone accidentally changes the recipient!
 *
 * 🆕 PHP 8.2 Feature: readonly class
 * All properties are automatically readonly — set once, never changed.
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final readonly class EmailAddress
{
    /**
     * @param string $email The email address (e.g. "john@example.com")
     * @param string|null $name Optional display name (e.g. "John Doe")
     *                          When set, the email shows as: John Doe <john@example.com>
     *
     * @throws InvalidEmailException If the email format is not valid
     */
    public function __construct(
        public string $email,
        public string|null $name = null,
    ) {
        // Validate the email format immediately when creating the object
        // filter_var() is a built-in PHP function that checks email format
        if (!filter_var($email, \FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException(
                sprintf(
                    'The email address "%s" is not valid. Please provide a proper format like "name@domain.com".',
                    $email
                )
            );
        }
    }

    /**
     * Convert this object to the JSON format that Mailjet API expects
     *
     * Mailjet v3.1 API wants this format:
     * { "Email": "john@example.com", "Name": "John Doe" }
     *
     * @return array<string, string> Ready-to-JSON-encode array
     */
    public function toArray(): array
    {
        $data = ['Email' => $this->email];

        // Only add Name if it was provided — Mailjet doesn't want empty Name fields
        if ($this->name !== null && $this->name !== '') {
            $data['Name'] = $this->name;
        }

        return $data;
    }

    /**
     * Human-readable string representation
     * Example output: "John Doe <john@example.com>" or just "john@example.com"
     */
    public function __toString(): string
    {
        if ($this->name !== null && $this->name !== '') {
            return sprintf('%s <%s>', $this->name, $this->email);
        }

        return $this->email;
    }
}
