# Contributing to symfony-mailjet-bundle

Thank you for your interest in contributing! 🎉
This project is **open source** and welcomes contributions from developers of **all levels**.

---

## 🚀 How to Contribute

### 1. Fork & Clone
```bash
# Fork the repo on GitHub, then:
git clone https://github.com/YOUR_USERNAME/symfony-mailjet-bundle.git
cd symfony-mailjet-bundle
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Create a Branch
```bash
# Use descriptive branch names:
git checkout -b feature/add-template-support
git checkout -b fix/webhook-signature-validation
git checkout -b docs/improve-readme
```

### 4. Make Your Changes

Write clean, commented code. If you're unsure, look at existing code for style guidance.

### 5. Run Quality Checks
```bash
composer test        # Run all tests
composer stan        # Run PHPStan static analysis
composer cs-check    # Check code style
# Or run everything at once:
composer quality
```

### 6. Write Tests

Every new feature or bug fix **must** include tests. We aim for **100% code coverage**.

### 7. Open a Pull Request

Push to your fork and open a PR against the `main` branch. Describe what you changed and why.

---

## 📋 Code Standards

- PHP 8.3+ features (readonly, enums, named arguments, fibers)
- Strict types (`declare(strict_types=1)` in every file)
- PSR-4 autoloading, PSR-12 code style
- PHPStan Level 9 (maximum strictness)
- Descriptive comments in English
- No hardcoded credentials — ever

---

## 💡 Ideas for Contributions

- [ ] Twig integration (render email templates automatically)
- [ ] Symfony Mailer bridge (use as a Symfony Mailer transport)
- [ ] Rate limiter integration (auto-throttle on 429 errors)
- [ ] OpenTelemetry traces for monitoring
- [ ] Mailjet contact list management API
- [ ] Email preview in Symfony's web profiler

---

## 🤝 Code of Conduct

Be respectful and constructive. This project is a learning environment.
Everyone is welcome — beginners and experts alike!

---

*Author: Fabien Conéjéro — License: MIT*
