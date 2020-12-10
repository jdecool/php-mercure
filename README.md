Mercure PHP component for HTTPlug
=================================

[![Build Status](https://github.com/jdecool/php-mercure/workflows/Continuous%20Integration/badge.svg)](https://github.com/jdecool/php-mercure/actions?query=workflow%3A%22Continuous+Integration%22)
[![Latest Stable Version](https://poser.pugx.org/jdecool/php-mercure/v/stable.png)](https://packagist.org/packages/jdecool/php-mercure/README.md)

This is a fork of the official [`symfony/mercure`](https://github.com/symfony/mercure) component using `HTTPlug` client.

Thanks to [Kévin Dunglas](https://github.com/dunglas/) for his incredible work on [Mercure.rocks](Mercure.rocks).
Thanks to all Symfony & Mercure contributors.

> Mercure is a protocol allowing to push data updates to web browsers and other
  HTTP clients in a convenient, fast, reliable and battery-efficient way.
  It is especially useful to publish real-time updates of resources served through
  web APIs, to reactive web and mobile apps.

This component implements the "publisher" part of [the Mercure Protocol](https://mercure.rocks).

## Install it

Install using [composer](https://getcomposer.org), you need to install a [PSR-18](https://www.php-fig.org/psr/psr-18/) compliant HTTP client.

Example using Guzzle:

```bash
composer require jdecool/mercure guzzlehttp/guzzle http-interop/http-factory-guzzle
```

## Getting started

```php
// change these values accordingly to your hub installation
define('HUB_URL', 'https://demo.mercure.rocks/.well-known/mercure');
define('JWT', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJtZXJjdXJlIjp7InN1YnNjcmliZSI6WyJmb28iLCJiYXIiXSwicHVibGlzaCI6WyJmb28iXX19.LRLvirgONK13JgacQ_VbcjySbVhkSmHy3IznH3tA9PM');

use JDecool\Mercure\Jwt\StaticJwtProvider;
use JDecool\Mercure\PublisherFactory;
use JDecool\Mercure\Update;

$factory = new PublisherFactory();
$publisher = $factory->create(HUB_URL, new StaticJwtProvider(JWT));
$response = $publisher(new Update('https://example.com/books/1.jsonld', 'Hi from Symfony!'));
```
