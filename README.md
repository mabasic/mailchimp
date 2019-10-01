# Mailchimp

It subscribes an email address to your mailchimp list.

[![Become a Patron](https://img.shields.io/badge/Become%20a-Patron-f96854.svg?style=for-the-badge)](https://www.patreon.com/laravelista)

## Overview

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

## Laravelista Sponsors & Backers

I would like to extend my thanks to the following sponsors & backers for funding my open-source journey. If you are interested in becoming a sponsor or backer, please visit the Laravelista [Backers page](https://laravelista.hr/backers).

## Contributing

Thank you for considering contributing to mabasic/mailchimp! The contribution guide can be found on the [Laravelista website](https://laravelista.hr/contributing).

## Code of Conduct

In order to ensure that the Laravelista community is welcoming to all, please review and abide by the [Code of Conduct](https://laravelista.hr/code-of-conduct).

## License

mabasic/mailchimp is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).