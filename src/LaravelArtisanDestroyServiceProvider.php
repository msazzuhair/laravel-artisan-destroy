<?php

namespace Msazzuhair\LaravelArtisanDestroy;

use Illuminate\Foundation\Console\CastDestroyCommand;
use Illuminate\Foundation\Console\ChannelDestroyCommand;
use Illuminate\Foundation\Console\ComponentDestroyCommand;
use Illuminate\Foundation\Console\ConsoleDestroyCommand;
use Illuminate\Foundation\Console\EventDestroyCommand;
use Illuminate\Foundation\Console\ExceptionDestroyCommand;
use Illuminate\Foundation\Console\JobDestroyCommand;
use Illuminate\Foundation\Console\ListenerDestroyCommand;
use Illuminate\Foundation\Console\MailDestroyCommand;
use Illuminate\Foundation\Console\ModelDestroyCommand;
use Illuminate\Foundation\Console\NotificationDestroyCommand;
use Illuminate\Foundation\Console\ObserverDestroyCommand;
use Illuminate\Foundation\Console\PolicyDestroyCommand;
use Illuminate\Foundation\Console\ProviderDestroyCommand;
use Illuminate\Foundation\Console\RequestDestroyCommand;
use Illuminate\Foundation\Console\ResourceDestroyCommand;
use Illuminate\Foundation\Console\RuleDestroyCommand;
use Illuminate\Foundation\Console\ScopeDestroyCommand;
use Illuminate\Foundation\Console\TestDestroyCommand;
use Illuminate\Foundation\Console\ViewDestroyCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Msazzuhair\LaravelArtisanDestroy\Commands\LaravelArtisanDestroyCommand;

class LaravelArtisanDestroyServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-artisan-destroy')
            ->hasConfigFile()
            ->hasCommands([
                CastDestroyCommand::class,
                ChannelDestroyCommand::class,
                ComponentDestroyCommand::class,
                ConsoleDestroyCommand::class,
                EventDestroyCommand::class,
                ExceptionDestroyCommand::class,
                JobDestroyCommand::class,
                ListenerDestroyCommand::class,
                MailDestroyCommand::class,
                ModelDestroyCommand::class,
                NotificationDestroyCommand::class,
                ObserverDestroyCommand::class,
                PolicyDestroyCommand::class,
                ProviderDestroyCommand::class,
                RequestDestroyCommand::class,
                ResourceDestroyCommand::class,
                RuleDestroyCommand::class,
                ScopeDestroyCommand::class,
                TestDestroyCommand::class,
                ViewDestroyCommand::class
            ]);
    }
}
