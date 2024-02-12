<?php

namespace Msazzuhair\LaravelArtisanDestroy\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Msazzuhair\LaravelArtisanDestroy\LaravelArtisanDestroyServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Msazzuhair\\LaravelArtisanDestroy\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelArtisanDestroyServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        config()->set('database.default', 'testing');

        /*
        $migration = include __DIR__.'/../database/migrations/create_laravel-artisan-destroy_table.php.stub';
        $migration->up();
        */

        // Initialize empty git repository on vendor/orchestra/testbench-core/laravel
        shell_exec('git init vendor/orchestra/testbench-core/laravel');

        // Disable commit signing to avoid gpg prompts
        shell_exec('git -C vendor/orchestra/testbench-core/laravel config commit.gpgsign false');

        shell_exec('git -C vendor/orchestra/testbench-core/laravel config core.safecrlf false');
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        shell_exec('rm -rf vendor/orchestra/testbench-core/laravel/.git');
    }
}
