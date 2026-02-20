<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\DTO;

use Fabconejo\MailjetBundle\Exception\InvalidEmailException;

/**
 * Attachment — Represents a file attached to an email
 *
 * 📎 What this does:
 * When you want to attach a PDF invoice or image to an email,
 * you use this class. It reads the file, converts it to Base64,
 * and packages it in the format Mailjet expects.
 *
 * 🔐 What is Base64?
 * Files are binary data (0s and 1s). To send them through JSON/HTTP,
 * we encode them as text using Base64. It looks like:
 * "SGVsbG8gV29ybGQ=" — that's "Hello World" in Base64.
 * Mailjet decodes it back to the original file on their end.
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final readonly class Attachment
{
    /** Maximum allowed file size: 15 MB in bytes */
    private const MAX_FILE_SIZE_BYTES = 15 * 1024 * 1024;

    /**
     * @param string $filename   The filename as it will appear in the email (e.g. "invoice.pdf")
     * @param string $content    The raw binary content of the file
     * @param string $mimeType   The MIME type (e.g. "application/pdf", "image/png")
     *                           See: https://developer.mozilla.org/en-US/docs/Web/HTTP/MIME_types
     * @param bool   $inline     If true, the file is embedded inline (for images in HTML body)
     *                           If false, it shows as a downloadable attachment
     *
     * @throws InvalidEmailException If the file is too large
     */
    public function __construct(
        public string $filename,
        public string $content,
        public string $mimeType,
        public bool $inline = false,
    ) {
        $size = strlen($content);

        if ($size > self::MAX_FILE_SIZE_BYTES) {
            throw new InvalidEmailException(
                sprintf(
                    'Attachment "%s" is %s bytes, which exceeds the maximum allowed size of %s bytes (15 MB). '
                    . 'Consider compressing the file or using a cloud storage link instead.',
                    $filename,
                    number_format($size),
                    number_format(self::MAX_FILE_SIZE_BYTES)
                )
            );
        }
    }

    /**
     * Create an Attachment from a file path on the server
     *
     * This is a "named constructor" — a convenient static factory method.
     *
     * Usage:
     *   $attachment = Attachment::fromPath('/var/invoices/invoice-123.pdf');
     *
     * @param string      $filePath The absolute path to the file on the server
     * @param string|null $filename Optional custom display name (defaults to the actual filename)
     *
     * @throws \RuntimeException If the file does not exist or cannot be read
     * @throws InvalidEmailException If the file exceeds the size limit
     */
    public static function fromPath(string $filePath, string|null $filename = null): self
    {
        if (!file_exists($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException(
                sprintf('The file at path "%s" does not exist or cannot be read.', $filePath)
            );
        }

        $content = file_get_contents($filePath);

        if ($content === false) {
            throw new \RuntimeException(
                sprintf('Failed to read the file at path "%s".', $filePath)
            );
        }

        // mime_content_type() detects the MIME type automatically from the file content
        $mimeType = mime_content_type($filePath) ?: 'application/octet-stream';
        $displayName = $filename ?? basename($filePath);

        return new self($displayName, $content, $mimeType);
    }

    /**
     * Convert this attachment to the format Mailjet API v3.1 expects
     *
     * @return array<string, string> Ready-to-use array for JSON encoding
     */
    public function toArray(): array
    {
        return [
            // Base64-encode the raw binary content for safe transmission in JSON
            'Base64Content' => base64_encode($this->content),
            'Filename'      => $this->filename,
            'ContentType'   => $this->mimeType,
        ];
    }
}
