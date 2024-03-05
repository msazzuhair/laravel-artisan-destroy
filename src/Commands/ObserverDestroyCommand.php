<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use InvalidArgumentException;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\suggest;

#[AsCommand(name: 'destroy:observer')]
class ObserverDestroyCommand extends DestroyerCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:observer';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an observer class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Observer';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Observers';
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
