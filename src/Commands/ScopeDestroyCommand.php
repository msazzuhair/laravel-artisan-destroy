<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'destroy:scope')]
class ScopeDestroyCommand extends DestroyerCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:scope';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a scope class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Scope';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models\\Scopes' : $rootNamespace.'\Scopes';
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
