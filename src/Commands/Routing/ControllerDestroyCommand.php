<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands\Routing;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Msazzuhair\LaravelArtisanDestroy\Commands\DestroyerCommand;
use Msazzuhair\LaravelArtisanDestroy\Traits\DeletesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use function Laravel\Prompts\confirm;

#[AsCommand(name: 'destroy:controller')]
class ControllerDestroyCommand extends DestroyerCommand
{
    use DeletesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a controller class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Http\Controllers';
    }

    public function handle() {
        parent::handle();

        $name = $this->qualifyClass($this->getNameInput());

        $this->deleteClass($name);
    }

    /**
     * Build the class with the given name.
     *
     * Remove the base controller import if we are already in the base namespace.
     *
     * @param  string  $name
     * @return string
     */
    protected function deleteClass($name)
    {
        $controllerNamespace = $this->getNamespace($name);

        $replace = [];

        if ($this->option('parent')) {
            $this->deleteRelatedParentModel();
        }

        if ($this->option('model')) {
            $this->deleteRelatedModel($replace);
        }

        if ($this->option('requests')) {
            $this->deleteFormRequestReplacements($replace, $this->inferModelClass());
        }
    }

    /**
     * Build the replacements for a parent controller.
     *
     * @return void
     */
    protected function deleteRelatedParentModel()
    {
        $parentModelClass = $this->parseModel($this->option('parent'));

        if (class_exists($parentModelClass)) {
            $this->call('destroy:model', ['name' => $parentModelClass]);
        }
    }

    /**
     * Build the model replacement values.
     *
     * @return void
     */
    protected function deleteRelatedModel(array $replace)
    {
        $modelClass = $this->parseModel($this->option('model'));

        if (class_exists($modelClass)) {
            $this->call('destroy:model', ['name' => $modelClass]);
        }
    }

    /**
     * Get the fully-qualified model class name.
     *
     * @param  string  $model
     * @return string
     *
     * @throws \InvalidArgumentException
     */
    protected function parseModel($model)
    {
        if (preg_match('([^A-Za-z0-9_/\\\\])', $model)) {
            throw new InvalidArgumentException('Model name contains invalid characters.');
        }

        return $this->qualifyModel($model);
    }

    /**
     * Build the model replacement values.
     *
     * @param  string  $modelClass
     * @return array
     */
    protected function deleteFormRequestReplacements(array $replace, $modelClass)
    {
        [$namespace, $storeRequestClass, $updateRequestClass] = [
            'Illuminate\\Http', 'Request', 'Request',
        ];

        $namespace = 'App\\Http\\Requests';

        [$storeRequestClass, $updateRequestClass] = $this->destroyFormRequests(
            $modelClass
        );
    }

    /**
     * Generate the form requests for the given model and classes.
     *
     * @param  string  $modelClass
     * @param  string  $storeRequestClass
     * @param  string  $updateRequestClass
     * @return array
     */
    protected function destroyFormRequests($modelClass)
    {
        $storeRequestClass = 'Store'.class_basename($modelClass).'Request';

        $this->call('destroy:request', [
            'name' => $storeRequestClass,
            '--force' => $this->option('force'),
        ]);

        $updateRequestClass = 'Update'.class_basename($modelClass).'Request';

        $this->call('destroy:request', [
            'name' => $updateRequestClass,
            '--force' => $this->option('force'),
        ]);

        return [$storeRequestClass, $updateRequestClass];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'Delete the model for a given resource controller'],
            ['model-name', 'M', InputOption::VALUE_OPTIONAL, 'Specify the model name. Also implies the name of the form request classes.'],
            ['parent', 'p', InputOption::VALUE_OPTIONAL, 'Delete the parent model for a nested resource controller class'],
            ['force', 'f', InputOption::VALUE_NONE, 'Delete the class without prompting for confirmation'],
            ['requests', 'R', InputOption::VALUE_NONE, 'Delete FormRequest classes for store and update'],
            ['test', 't', InputOption::VALUE_NONE, 'Delete any accompanying PHPUnit test for the controller'],
        ];
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

        $requests = confirm(
            "Do you want to also delete FormRequest classes for store and update if it's present?",
            false
        );

        if ($requests) {
            $input->setOption('requests', true);
        }
    }

    protected function inferModelClass()
    {
        if ($this->option('model-name')) {
            return $this->parseModel($this->option('model-name'));
        }

        // Remove the "Controller" suffix off the class name and use that as the model name
        return $this->parseModel(Str::beforeLast($this->argument('name'), 'Controller'));
    }
}
