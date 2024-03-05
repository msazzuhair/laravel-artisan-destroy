<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Msazzuhair\LaravelArtisanDestroy\Traits\DeletesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'destroy:view')]
class ViewDestroyCommand extends DestroyerCommand
{
    use DeletesMatchingTest;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a view';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'destroy:view';

    /**
     * The type of file being generated.
     *
     * @var string
     */
    protected $type = 'View';

    /**
     * Get the destination view path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        return $this->viewPath(
            $this->getNameInput().'.'.$this->option('extension'),
        );
    }

    /**
     * Get the desired view name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        $name = trim($this->argument('name'));

        $name = str_replace(['\\', '.'], '/', $this->argument('name'));

        return $name;
    }

    /**
     * Get the destination test case path.
     *
     * @return string
     */
    protected function getTestPath()
    {
        return base_path(
            Str::of($this->testClassFullyQualifiedName())
                ->replace('\\', '/')
                ->replaceFirst('Tests/Feature', 'tests/Feature')
                ->append('Test.php')
                ->value()
        );
    }

    /**
     * Delete the matching test case if requested.
     *
     * @param  string  $path
     */
    protected function handleTestDeletion($path): bool
    {
        if (! $this->option('test') && ! $this->option('pest')) {
            return false;
        }

        return File::delete($this->getTestPath());
    }

    /**
     * Get the class fully qualified name for the test.
     *
     * @return string
     */
    protected function testClassFullyQualifiedName()
    {
        $name = Str::of(Str::lower($this->getNameInput()))->replace('.'.$this->option('extension'), '');

        $namespacedName = Str::of(
            Str::of($name)
                ->replace('/', ' ')
                ->explode(' ')
                ->map(fn ($part) => Str::of($part)->ucfirst())
                ->implode('\\')
        )
            ->replace(['-', '_'], ' ')
            ->explode(' ')
            ->map(fn ($part) => Str::of($part)->ucfirst())
            ->implode('');

        return 'Tests\\Feature\\View\\'.$namespacedName;
    }

    /**
     * Get the view name for the test.
     *
     * @return string
     */
    protected function testViewName()
    {
        return Str::of($this->getNameInput())
            ->replace('/', '.')
            ->lower()
            ->value();
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['extension', null, InputOption::VALUE_OPTIONAL, 'The extension of the generated view', 'blade.php'],
            ['force', 'f', InputOption::VALUE_NONE, 'Delete the view without prompting for confirmation'],
        ];
    }
}
