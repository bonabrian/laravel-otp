# OTP Generator & Validator

![Github License](https://img.shields.io/github/license/bonabrian/laravel-otp?style=for-the-badge)

## Installation

Via Composer

```bash
composer require bonabrian/laravel-otp
```

To publish the config file for laravel

```bash
php artisan otp:publish
```

## Usage in Laravel

Import the facade class
```php
use Bonabrian\Otp\Facades\Otp;
```

**Generate an OTP:**

```php
$code = Otp::generate($secret);
```

The generated OTP above will only be validated using the same secret within the default expiry time.

> **TIP:** OTP is generally used for verification. So the easiest way of determining the `secret` is the user's email/username or phone number or maybe user ID.

**Validate an OTP:**

```php
$valid = Otp::validate($code, $secret);
```

**Other Generate & Validate options:**

```php
// Generate
Otp::setDigits(4)->generate($secret); // 4 Digits
Otp::setExpiry(30)->generate($secret); // 30 min expiry
Otp::setDigits(4)->setExpiry(30)->generate($secret); // 4 Digits, 30 min expiry
```

Make sure to set the same config during validating.
Example:

```php
// Example 1
Otp::validate($code, $secret); // false
Otp::setDigits(4)->validate($code, $secret); // true

// Example 2
Otp::validate($code, $secret); // false
Otp::setExpiry(30)->validate($codde, $secret); // true

// Example 3
Otp::validate($code, $secret); // false
Otp::setDigits(4)->setExpiry(30)->validate($code, $secret); // true
