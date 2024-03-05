<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'destroy:rule')]
class RuleDestroyCommand extends DestroyerCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:rule';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a validation rule';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Rule';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Rules';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['force', 'f', InputOption::VALUE_NONE, 'Delete the class without prompting for confirmation'],
            ['implicit', 'i', InputOption::VALUE_NONE, 'Generate an implicit rule'],
        ];
    }
}
