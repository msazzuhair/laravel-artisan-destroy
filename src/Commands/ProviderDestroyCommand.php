<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'destroy:provider')]
class ProviderDestroyCommand extends DestroyerCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a service provider class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Provider';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Providers';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Delete the class without prompting for confirmation'],
        ];
    }
}
