# Artisan Destroy Commands for Laravel 

[![Latest Version on Packagist](https://img.shields.io/packagist/v/msazzuhair/laravel-artisan-destroy.svg?style=flat-square)](https://packagist.org/packages/msazzuhair/laravel-artisan-destroy)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/msazzuhair/laravel-artisan-destroy/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/msazzuhair/laravel-artisan-destroy/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/msazzuhair/laravel-artisan-destroy/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/msazzuhair/laravel-artisan-destroy/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/msazzuhair/laravel-artisan-destroy.svg?style=flat-square)](https://packagist.org/packages/msazzuhair/laravel-artisan-destroy)

⚠️⚠️ Do not install this package on your production environment. ⚠️⚠️

A (maybe?) missing destroy command for anyone who has second thoughts. This will delete artisan-generated files from your project.

Let's say, you have generated some files using the `artisan make` command. Then you think of a better name, or the generated files don't have a proper name based on the Laravel naming convention. This simple package will help you clean up those messes.

Just replace your `artisan make:...` command with `artisan destroy:...` and you're good to go. These commands will also warn you if the file you are trying to delete is untracked or has uncommitted changes.

I have used some of these commands on my projects, so ️they should work.

## Development Progress

| Class        | Command                | Destroy Command | Test |
|--------------|------------------------|-----------------|------|
| Cast         | `destroy:cast`         | ✅               | ✅    |
| Channel      | `destroy:channel`      | ✅               | ✅    |
| Component    | `destroy:component`    | ✅               |      |
| Controller   | `destroy:controller`   | ✅               |      |
| Console      | `destroy:console`      | ✅               |      |
| Event        | `destroy:event`        | ✅               |      |
| Exception    | `destroy:exception`    | ✅               |      |
| Factory      | `destroy:factory`      | ✅               |      |
| Job          | `destroy:job`          | ✅               |      |
| Listener     | `destroy:listener`     | ✅               |      |
| Mail         | `destroy:mail`         | ✅               |      |
| Middleware   | `destroy:middleware`   | ✅               |      |
| Migration    | `destroy:migration`    | ✅               |      |
| Model        | `destroy:model`        | ✅               |      |
| Notification | `destroy:notification` | ✅               |      |
| Observer     | `destroy:observer`     | ✅               |      |
| Policy       | `destroy:policy`       | ✅               |      |
| Provider     | `destroy:provider`     | ✅               |      |
| Request      | `destroy:request`      | ✅               |      |
| Resource     | `destroy:resource`     | ✅               |      |
| Rule         | `destroy:rule`         | ✅               |      |
| Scope        | `destroy:scope`        | ✅               |      |
| Seeder       | `destroy:seeder`       | ✅               |      |
| Test         | `destroy:test`         | ✅               |      |
| View         | `destroy:view`         | ✅               |      |


## Optional Requirements

- Git. This package will check if the file to be deleted is tracked and clean. If git is not installed, you'll need to use the `--force` option or disable git checking through the config file.

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

```bash
php artisan destroy:model <Model Name>
```

You can use artisan help to see all available options for each command. For example:

```bash
php artisan help destroy:model
```
```bash
Description:
  Delete an Eloquent model class

Usage:
  destroy:model [options] [--] <name>

Arguments:
  name                  The name of the model

Options:
  -a, --all             Delete a migration, seeder, factory, policy, resource controller, and form request classes for the model
  -c, --controller      Delete a controller for the model
  -f, --factory         Delete a factory for the model
      --force           Delete the class without prompting for confirmation
  -m, --migration       Delete a migration file for the model
      --policy          Delete a policy for the model
  -s, --seed            Delete a seeder for the model
  -R, --requests        Delete new form request classes and use them in the resource controller
  -t, --test            Delete any accompanying PHPUnit test for the model and every related classes that is going to also be deleted
  -h, --help            Display help for the given command. When no command is given display help for the list command
  -q, --quiet           Do not output any message
  -V, --version         Display this application version
      --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
  -n, --no-interaction  Do not ask any interactive question
      --env[=ENV]       The environment the command should run under
  -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug
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
