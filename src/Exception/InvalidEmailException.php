<?php

declare(strict_types=1);

namespace Fabconejo\MailjetBundle\Exception;

/**
 * InvalidEmailException — Thrown when email data is invalid BEFORE sending
 *
 * This is different from MailjetApiException:
 * - InvalidEmailException: "Your data is wrong, I won't even try to send"
 *   (e.g. invalid email format, missing subject, no recipients)
 *
 * - MailjetApiException: "Your data was fine, but the API rejected it or failed"
 *   (e.g. network error, API key wrong, server crash)
 *
 * By having separate exception classes, you can catch them separately:
 *   catch (InvalidEmailException $e) → fix your code/data
 *   catch (MailjetApiException $e)   → handle network/API failures
 *
 * @author Fabien Conéjéro
 * @license MIT
 */
final class InvalidEmailException extends \InvalidArgumentException
{
    // No additional code needed — the parent class handles everything.
    // Having a distinct class name is the important part.
}
