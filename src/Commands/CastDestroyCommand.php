<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'make:cast')]
class CastDestroyCommand extends DestroyerCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:cast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a custom Eloquent cast class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected string $type = 'Cast';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace): string
    {
        return $rootNamespace.'\Casts';
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Delete the class even if the cast already exists'],
            ['inbound', null, InputOption::VALUE_NONE, 'Generate an inbound cast class'],
        ];
    }
}
