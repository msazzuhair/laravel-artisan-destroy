<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;
use Msazzuhair\LaravelArtisanDestroy\Traits\DeletesMatchingTest;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Finder\Finder;

abstract class DestroyerCommand extends Command implements PromptsForMissingInput
{
    /**
     * The filesystem instance.
     */
    protected $files;

    /**
     * The type of class being generated.
     */
    protected $type;

    /**
     * Delete a controller creator command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
    }

    /**
     * Execute the console command.
     *
     * @return bool|null
     *
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function handle()
    {

        $name = $this->qualifyClass($this->getNameInput());

        $path = $this->getPath($name);

        // Next, We will check to see if the class already exists. If it does not we will
        // abort the deletion process.
        if ($this->doesNotExist($this->getNameInput())) {
            $this->error($this->type.' "'.$name.'" doesn\'t exist.');

            return false;
        }

        if (! $this->confirmToProceed($path)) {
            return false;
        }

        $this->files->delete($path);

        $info = $this->type;

        if (in_array(DeletesMatchingTest::class, class_uses_recursive($this))) {
            if ($this->handleTestDeletion($path)) {
                $info .= ' and its test';
            }
        }

        if (windows_os()) {
            $path = str_replace('/', '\\', $path);
        }

        $this->info(sprintf('%s [%s] deleted successfully.', $info, $path));
    }

    /**
     * Parse the class name and format according to the root namespace.
     *
     * @param  string  $name
     */
    protected function qualifyClass($name)
    {
        $name = ltrim($name, '\\/');

        $name = str_replace('/', '\\', $name);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($name, $rootNamespace)) {
            return $name;
        }

        return $this->qualifyClass(
            $this->getDefaultNamespace(trim($rootNamespace, '\\')).'\\'.$name
        );
    }

    /**
     * Qualify the given model class base name.
     */
    protected function qualifyModel(string $model)
    {
        $model = ltrim($model, '\\/');

        $model = str_replace('/', '\\', $model);

        $rootNamespace = $this->rootNamespace();

        if (Str::startsWith($model, $rootNamespace)) {
            return $model;
        }

        return is_dir(app_path('Models'))
            ? $rootNamespace.'Models\\'.$model
            : $rootNamespace.$model;
    }

    /**
     * Get a list of possible model names.
     *
     * @return array<int, string>
     */
    protected function possibleModels()
    {
        $modelPath = is_dir(app_path('Models')) ? app_path('Models') : app_path();

        return collect(Finder::create()->files()->depth(0)->in($modelPath))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Get a list of possible event names.
     *
     * @return array<int, string>
     */
    protected function possibleEvents()
    {
        $eventPath = app_path('Events');

        if (! is_dir($eventPath)) {
            return [];
        }

        return collect(Finder::create()->files()->depth(0)->in($eventPath))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace;
    }

    /**
     * Determine if the class already exists.
     */
    protected function doesNotExist(string $rawName)
    {
        return ! $this->files->exists($this->getPath($this->qualifyClass($rawName)));
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     */
    protected function getPath($name)
    {
        $name = Str::replaceFirst($this->rootNamespace(), '', $name);

        return $this->laravel['path'].'/'.str_replace('\\', '/', $name).'.php';
    }

    /**
     * Get the full namespace for a given class, without the class name.
     *
     * @param  string  $name
     */
    protected function getNamespace($name)
    {
        return trim(implode('\\', array_slice(explode('\\', $name), 0, -1)), '\\');
    }

    /**
     * Get the desired class name from the input.
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Get the root namespace for the class.
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
    }

    /**
     * Get the model for the default guard's user provider.
     */
    protected function userProviderModel()
    {
        $config = $this->laravel['config'];

        $provider = $config->get('auth.guards.'.$config->get('auth.defaults.guard').'.provider');

        return $config->get("auth.providers.{$provider}.model");
    }

    /**
     * Get the first view directory path from the application configuration.
     *
     * @param  string  $path
     */
    protected function viewPath($path = '')
    {
        $views = $this->laravel['config']['view.paths'][0] ?? resource_path('views');

        return $views.($path ? DIRECTORY_SEPARATOR.$path : $path);
    }

    /**
     * Get the console command arguments.
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the '.strtolower($this->type)],
        ];
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => [
                'What is the name of the '.strtolower($this->type).' that you would like to delete?',
                match ($this->type) {
                    'Cast' => 'E.g. Json',
                    'Channel' => 'E.g. OrderChannel',
                    'Console command' => 'E.g. SendEmails',
                    'Component' => 'E.g. Alert',
                    'Controller' => 'E.g. UserController',
                    'Event' => 'E.g. PodcastProcessed',
                    'Exception' => 'E.g. InvalidOrderException',
                    'Factory' => 'E.g. PostFactory',
                    'Job' => 'E.g. ProcessPodcast',
                    'Listener' => 'E.g. SendPodcastNotification',
                    'Mailable' => 'E.g. OrderShipped',
                    'Middleware' => 'E.g. EnsureTokenIsValid',
                    'Model' => 'E.g. Flight',
                    'Notification' => 'E.g. InvoicePaid',
                    'Observer' => 'E.g. UserObserver',
                    'Policy' => 'E.g. PostPolicy',
                    'Provider' => 'E.g. ElasticServiceProvider',
                    'Request' => 'E.g. StorePodcastRequest',
                    'Resource' => 'E.g. UserResource',
                    'Rule' => 'E.g. Uppercase',
                    'Scope' => 'E.g. TrendingScope',
                    'Seeder' => 'E.g. UserSeeder',
                    'Test' => 'E.g. UserTest',
                    default => '',
                },
            ],
        ];
    }

    private function confirmToProceed($path)
    {
        // If force option is used, we don't need confirmation
        if ($this->option('force')) {
            return true;
        } elseif (config('artisan-destroy.confirmation', true)) {
            if (config('artisan-destroy.git-tracking-confirmation', true)) {
                // Check if git is present
                if (! is_dir(base_path().'/.git')) {
                    $this->error('Git is not initialized in this project. Any deletion will be irreversible.');
                }

                // Check if the file is tracked by git
                elseif (shell_exec('git -C '.base_path().' ls-files '.$path) == '') {
                    $this->error('The file is not tracked by git. Any deletion will be irreversible.');
                }

                // Check if the file has uncommited changes
                elseif (shell_exec('git -C '.base_path().' diff --name-only '.$path) != '') {
                    $this->error('The file has uncommited changes. Any deletion will be irreversible.');
                }
            }

            $confirmation = $this->confirm(
                'Are you sure you want to proceed?'
            );

            if (! $confirmation) {
                $this->error('Aborted.');
            }

            return $confirmation;
        }

        return false;
    }
}
