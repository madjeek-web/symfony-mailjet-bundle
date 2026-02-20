# Security Policy

## 🔐 Supported Versions

| Version | Supported          |
|---------|--------------------|
| 1.x     | ✅ Yes (current)   |
| < 1.0   | ❌ No              |

---

## 📣 Reporting a Vulnerability

**Please do NOT report security vulnerabilities through public GitHub Issues.**

If you discover a security vulnerability in this project, please report it privately:

- **Email**: open a [GitHub Security Advisory](https://github.com/fabconejo/symfony-mailjet-bundle/security/advisories/new)
- Or contact the author directly via GitHub: [@fabconejo](https://github.com/fabconejo)

### What to Include in Your Report

1. **Description**: What is the vulnerability? What can an attacker do?
2. **Affected versions**: Which version(s) are impacted?
3. **Reproduction steps**: How can we reproduce it?
4. **Impact assessment**: What data/systems could be affected?
5. **Suggested fix** (optional but appreciated!)

### Response Timeline

- **Acknowledgment**: Within 48 hours
- **Initial assessment**: Within 5 business days
- **Fix + disclosure**: Within 30 days (we follow responsible disclosure)

---

## 🛡️ Security Measures Built Into This Bundle

### 1. API Credential Protection
- **Never log API keys or secrets** — the bundle deliberately excludes credentials from all log output
- **Environment variables only** — credentials are never hardcoded; they must come from `.env`
- **Validation at construction** — empty credentials throw immediately, preventing silent failures

```php
// ✅ CORRECT — use environment variables
api_key: '%env(MAILJET_API_KEY)%'

// ❌ NEVER DO THIS — hardcoded in config
api_key: 'my_real_api_key_123'
```

### 2. Webhook Signature Verification

All incoming webhook requests from Mailjet are verified using **HMAC-SHA256** with a shared secret:

```php
// MailjetClient uses hash_equals() for timing-safe comparison
// This prevents timing attacks that could leak the secret
$expectedSignature = hash_hmac('sha256', $requestBody, $webhookSecret);
return hash_equals($expectedSignature, $receivedSignature);
```

**Why `hash_equals()` instead of `===`?**
A regular string comparison (`===`) takes different amounts of time depending on where the strings differ. An attacker could measure these tiny time differences to guess the secret character by character (this is called a **timing attack**). `hash_equals()` always takes the same amount of time, regardless of how different the strings are.

### 3. No Email Content in Logs

The bundle deliberately does NOT log:
- Email bodies (could contain personal/sensitive data — GDPR!)
- Attachment contents
- Custom variables or headers (could contain tokens, PII)

What IS logged:
- Recipient count
- Subject line *(note: keep subjects non-sensitive)*
- API response status
- Error messages (sanitized)

### 4. Input Validation

- **Email format validation**: Every `EmailAddress` object validates format on creation using PHP's built-in `filter_var()` with `FILTER_VALIDATE_EMAIL`
- **Attachment size limit**: Files over 15 MB are rejected before the API call (Mailjet's limit)
- **Batch size limit**: Batches over 50 messages are rejected with a clear error
- **Priority bounds**: Email priority must be 1–5

### 5. Immutable Data Transfer Objects

`EmailAddress` is a **readonly class** in PHP 8.2+. Once created, its properties cannot be changed. This prevents bugs where code accidentally mutates an email address mid-flow.

### 6. Sandbox Mode for Development

Enable sandbox mode to validate email structure **without sending real emails**:

```yaml
# config/packages/mailjet.yaml (dev environment)
mailjet:
    sandbox_mode: true
```

This sends the request to Mailjet but tells their API to validate and respond without actually delivering the email. No risk of accidentally emailing your customers from a dev server!

---

## 📋 Security Checklist for Production Deployment

Before going live, verify:

- [ ] `.env` is listed in `.gitignore` — **NEVER commit it**
- [ ] `MAILJET_API_KEY` and `MAILJET_SECRET_KEY` are set in production environment variables
- [ ] `MAILJET_SANDBOX_MODE=false` in production
- [ ] `MAILJET_WEBHOOK_SECRET` is set to a random 32+ character string
  - Generate one: `php -r "echo bin2hex(random_bytes(32));"`
- [ ] Webhook URL (`/mailjet/webhook`) is protected by HTTPS only
- [ ] Consider adding rate limiting to the webhook endpoint (e.g. Symfony RateLimiter)
- [ ] Review Mailjet's IP allowlist to restrict webhook origins

---

## 🔗 External Security Resources

- [Mailjet Security Overview](https://www.mailjet.com/security/)
- [OWASP API Security Top 10](https://owasp.org/API-Security/)
- [PHP Security Guide](https://phptherightway.com/#security)
- [Symfony Security Best Practices](https://symfony.com/doc/current/best_practices.html#security)

---

*This security policy was created alongside the initial release. It will be updated as the project evolves.*

*Author: Fabien Conéjéro — Last updated: February 20, 2026*
