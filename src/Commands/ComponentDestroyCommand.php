<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Illuminate\Support\Str;
use Msazzuhair\LaravelArtisanDestroy\Traits\DeletesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'destroy:component')]
class ComponentDestroyCommand extends DestroyerCommand
{
    use DeletesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:component';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a view component class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Component';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->option('view')) {
            $this->deleteView(function () {
                $this->info($this->type.' created successfully.');
            });

            return;
        }

        if (parent::handle() === false && ! $this->option('force')) {
            return false;
        }

        if (! $this->option('inline')) {
            $this->deleteView();
        }
    }

    /**
     * Write the view for the component.
     *
     * @param  callable|null  $onSuccess
     * @return void
     */
    protected function deleteView($onSuccess = null)
    {
        $path = $this->viewPath(
            str_replace('.', '/', 'components.'.$this->getView()).'.blade.php'
        );

        if (! $this->files->exists($path)) {
            $this->error('View does not exist.');

            return;
        }

        $this->files->delete($path);

        if ($onSuccess) {
            $onSuccess();
        }
    }

    /**
     * Get the view name relative to the components directory.
     *
     * @return string view
     */
    protected function getView()
    {
        $name = str_replace('\\', '/', $this->argument('name'));

        return collect(explode('/', $name))
            ->map(function ($part) {
                return Str::kebab($part);
            })
            ->implode('.');
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\View\Components';
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
            ['inline', null, InputOption::VALUE_NONE, 'Delete a component that renders an inline view'],
            ['view', null, InputOption::VALUE_NONE, 'Delete an anonymous component with only a view'],
        ];
    }
}
