<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands\Routing;

use Illuminate\Console\Concerns\CreatesMatchingTest;
use Msazzuhair\LaravelArtisanDestroy\Commands\DestroyerCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'destroy:middleware')]
class MiddlewareDestroyCommand extends DestroyerCommand
{
    use CreatesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:middleware';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a middleware class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Middleware';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Middleware';
    }
}
