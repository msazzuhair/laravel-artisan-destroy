<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

use function Laravel\Prompts\suggest;

#[AsCommand(name: 'destroy:event')]
class EventDestroyCommand extends DestroyerCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an event class';

    /**
     * The type of class being generated.
     */
    protected $type = 'Event';

    /**
     * Execute the console command.
     *
     * @return void
     *
     * @throws FileNotFoundException
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }

        if ($this->option('listener')) {
            $this->deleteListener();
        }
    }

    /**
     * Delete a controller for the model.
     *
     * @return void
     */
    protected function deleteListener()
    {
        if (is_string($this->option('listener'))) {
            $this->call('destroy:listener', array_filter([
                'name' => $this->option('listener'),
            ]));
        } else {
            $listener = Str::beforeLast($this->argument('name'), 'Event');

            $this->call('destroy:listener', array_filter([
                'name' => "{$listener}Listener",
            ]));
        }
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function doesNotExist($rawName)
    {
        return ! class_exists($rawName) &&
            ! $this->files->exists($this->getPath($this->qualifyClass($rawName)));
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Events';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['listener', 'l', InputOption::VALUE_OPTIONAL, 'Delete this event listener class'],
            ['force', 'f', InputOption::VALUE_NONE, 'Delete the class without prompting for confirmation'],
        ];
    }

    /**
     * Get a list of possible listener names.
     *
     * @return array<int, string>
     */
    protected function possibleListeners()
    {
        $listenerPath = app_path('Listeners');

        if (! is_dir($listenerPath)) {
            return [];
        }

        return collect(Finder::create()->files()->depth(0)->in($listenerPath))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Interact further with the user if they were prompted for missing arguments.
     *
     * @return void
     */
    protected function afterPromptingForMissingArguments(InputInterface $input, OutputInterface $output)
    {
        if ($this->didReceiveOptions($input)) {
            return;
        }

        $listeners = $this->choice(
            'What is the name of the listener class that should be deleted? (Optional)',
            $this->possibleListeners(),
        );

        if ($listeners) {
            $input->setOption('listener', $listeners);
        }
    }
}
