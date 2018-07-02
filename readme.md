# Mailchimp

**It subscribes an email address to your mailchimp list.**

This package can be used as a stand-alone package with any PHP framework or as a Laravel package.

## Installation

```
composer require mabasic/mailchimp
```

## Usage

### Laravel

First, add this to your `config/services.php` file:

```php
'mailchimp' => [
    'key' => env('MAILCHIMP_KEY'),
    'dc' => env('MAILCHIMP_DATA_CENTER', 'us1')
]
```

> **Hint!** You can even add your `list_id` above if you only use one list in your application.

Then, add the correct values in your `.env` file:

```
MAILCHIMP_KEY=
MAILCHIMP_DATA_CENTER=
```

Finally, do this to subscribe an address:

```php
use Mabasic\Mailchimp\Facade as Mailchimp;

Mailchimp::subscribeAnAddress($list_id, $email);
```

### General PHP

```php
$mailchimp = new \Mabasic\Mailchimp\Mailchimp($key, $dc);

$mailchimp->subscribeAnAddress($list_id, $email);
```

## Tips

Read the source code for `src/Mailchimp.php` to better understand what `$dc` does and how to subscribe an address without a confirmation email. It is very educational.
