<?php

namespace Msazzuhair\LaravelArtisanDestroy;

use Msazzuhair\LaravelArtisanDestroy\Commands\CastDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ChannelDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ComponentDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ConsoleDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\EventDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ExceptionDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\JobDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ListenerDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\MailDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ModelDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\NotificationDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ObserverDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\PolicyDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ProviderDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\RequestDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ResourceDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\RuleDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ScopeDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\TestDestroyCommand;
use Msazzuhair\LaravelArtisanDestroy\Commands\ViewDestroyCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

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
