# Artisan Destroy Commands for Laravel 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/msazzuhair/laravel-artisan-destroy.svg?style=flat-square)](https://packagist.org/packages/msazzuhair/laravel-artisan-destroy)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/msazzuhair/laravel-artisan-destroy/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/msazzuhair/laravel-artisan-destroy/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/msazzuhair/laravel-artisan-destroy/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/msazzuhair/laravel-artisan-destroy/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/msazzuhair/laravel-artisan-destroy.svg?style=flat-square)](https://packagist.org/packages/msazzuhair/laravel-artisan-destroy)

A missing destroy command for anyone who has second thoughts.

⚠️⚠️ Do not install this package on production environment. ⚠️⚠️

## Development Progress

| Class        | Destroy Command | Test |
|--------------|-----------------|------|
| Cast         | ✅               | ✅    |
| Channel      | ✅               | ✅    |
| Component    | ✅               |      |
| Console      | ✅               |      |
| Event        | ✅               |      |
| Exception    | ✅               |      |
| Job          | ✅               |      |
| Listener     | ✅               |      |
| Mail         | ✅               |      |
| Model        | ✅               |      |
| Notification | ✅               |      |
| Observer     | ✅               |      |
| Policy       | ✅               |      |
| Provider     | ✅               |      |
| Request      | ✅               |      |
| Resource     | ✅               |      |
| Rule         | ✅               |      |
| Scope        | ✅               |      |
| Test         | ✅               |      |
| View         | ✅               |      |


## Optional Requirements

- Git. This package will check if the file to be deleted is tracked and clean. If git is not installed you'll need to use `--force` option or you can disable git checking through config file. 

## Installation

You can install the package as a dev dependency via composer:

```bash
composer require --dev msazzuhair/laravel-artisan-destroy
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="laravel-artisan-destroy-config"
```

## Usage

```php
php artisan destroy:model <Model Name>
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

- [Muhammad Azzuhair](https://github.com/msazzuhair)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
