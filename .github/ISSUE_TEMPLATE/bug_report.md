name: 🐛 Bug Report
description: Report a bug or unexpected behavior
title: "[Bug]: "
labels: ["bug", "triage"]
body:
  - type: markdown
    attributes:
      value: |
        Thank you for taking the time to report a bug! 🙏
        Please fill out this form as completely as possible.

  - type: input
    id: php-version
    attributes:
      label: PHP Version
      placeholder: "e.g. PHP 8.3.2"
    validations:
      required: true

  - type: input
    id: symfony-version
    attributes:
      label: Symfony Version
      placeholder: "e.g. Symfony 7.1"
    validations:
      required: true

  - type: textarea
    id: description
    attributes:
      label: What happened?
      description: A clear description of the bug
    validations:
      required: true

  - type: textarea
    id: reproduce
    attributes:
      label: Steps to Reproduce
      description: How can we reproduce this bug?
      placeholder: |
        1. Configure bundle with...
        2. Call sendNow() with...
        3. Expected... but got...
    validations:
      required: true

  - type: textarea
    id: expected
    attributes:
      label: Expected Behavior
    validations:
      required: true
