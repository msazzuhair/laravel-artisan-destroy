<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Illuminate\Support\Str;
use Msazzuhair\LaravelArtisanDestroy\Traits\DeletesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\multiselect;

#[AsCommand(name: 'destroy:model')]
class ModelDestroyCommand extends DestroyerCommand
{
    use DeletesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an Eloquent model class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Model';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false) {
            return false;
        }

        if ($this->option('all')) {
            $this->input->setOption('factory', true);
            $this->input->setOption('seed', true);
            $this->input->setOption('migration', true);
            $this->input->setOption('controller', true);
            $this->input->setOption('policy', true);
        }

        if ($this->option('factory')) {
            $this->deleteFactory();
        }

        if ($this->option('migration')) {
            $this->deleteMigration();
        }

        if ($this->option('seed')) {
            $this->deleteSeeder();
        }

        if ($this->option('controller')) {
            $this->deleteController();
        }

        if ($this->option('policy')) {
            $this->deletePolicy();
        }
    }

    /**
     * Delete a model factory for the model.
     *
     * @return void
     */
    protected function deleteFactory()
    {
        $factory = Str::studly($this->argument('name'));

        $this->call('destroy:factory', [
            'name' => "{$factory}Factory",
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * Delete a migration file for the model.
     *
     * @return void
     */
    protected function deleteMigration()
    {
        $table = Str::snake(Str::pluralStudly(class_basename($this->argument('name'))));

        $this->call('destroy:migration', [
            'name' => "create_{$table}_table",
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * Delete a seeder file for the model.
     *
     * @return void
     */
    protected function deleteSeeder()
    {
        $seeder = Str::studly(class_basename($this->argument('name')));

        $this->call('destroy:seeder', [
            'name' => "{$seeder}Seeder",
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * Delete a controller for the model.
     *
     * @return void
     */
    protected function deleteController()
    {
        $controller = Str::studly(class_basename($this->argument('name')));

        $modelName = $this->qualifyClass($this->getNameInput());

        $this->call('destroy:controller', array_filter([
            'name' => "{$controller}Controller",
            '--force' => $this->option('force'),
            '--model-name' => $modelName,
            '--requests' => $this->option('requests') || $this->option('all'),
            '--test' => $this->option('test'),
        ]));
    }

    /**
     * Delete a policy file for the model.
     *
     * @return void
     */
    protected function deletePolicy()
    {
        $policy = Str::studly(class_basename($this->argument('name')));

        $this->call('destroy:policy', [
            'name' => "{$policy}Policy",
            '--force' => $this->option('force'),
        ]);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return is_dir(app_path('Models')) ? $rootNamespace.'\\Models' : $rootNamespace;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['all', 'a', InputOption::VALUE_NONE, 'Delete a migration, seeder, factory, policy, resource controller, and form request classes for the model'],
            ['controller', 'c', InputOption::VALUE_NONE, 'Delete a controller for the model'],
            ['factory', 'f', InputOption::VALUE_NONE, 'Delete a factory for the model'],
            ['force', null, InputOption::VALUE_NONE, 'Delete the class without prompting for confirmation'],
            ['migration', 'm', InputOption::VALUE_NONE, 'Delete a migration file for the model'],
            ['policy', null, InputOption::VALUE_NONE, 'Delete a policy for the model'],
            ['seed', 's', InputOption::VALUE_NONE, 'Delete a seeder for the model'],
            ['requests', 'R', InputOption::VALUE_NONE, 'Delete new form request classes and use them in the resource controller'],
            ['test', 't', InputOption::VALUE_NONE, 'Delete any accompanying PHPUnit test for the model and every related classes that is going to also be deleted'],
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

        collect(multiselect('Would you like to also delete any of the following?', [
            'seed' => 'Database Seeder',
            'factory' => 'Factory',
            'migration' => 'Migration',
            'policy' => 'Policy',
            'controller' => 'Controller',
            'requests' => 'Form Requests',
            'test' => 'PHPUnit Test',
        ]))->each(fn ($option) => $input->setOption($option, true));
    }
}
