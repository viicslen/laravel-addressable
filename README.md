# Laravel Addressable

[![Latest Version on Packagist](https://img.shields.io/packagist/v/viicslen/laravel-addressable.svg?style=flat-square)](https://packagist.org/packages/viicslen/laravel-addressable)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/viicslen/laravel-addressable/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/viicslen/laravel-addressable/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/viicslen/laravel-addressable/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/viicslen/laravel-addressable/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/viicslen/laravel-addressable.svg?style=flat-square)](https://packagist.org/packages/viicslen/laravel-addressable)

## Installation

You can install the package via composer:

```bash
composer require viicslen/laravel-addressable
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="laravel-addressable-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-addressable-config"
```

This is the contents of the published config file:

```php
return [
];
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="laravel-addressable-views"
```

## Usage

```php
// ToDo: add usage instructions
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Victor R](https://github.com/viicslen)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
