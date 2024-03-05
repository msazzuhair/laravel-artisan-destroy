<?php

namespace Msazzuhair\LaravelArtisanDestroy\Traits;

use Illuminate\Support\Str;
use Symfony\Component\Console\Input\InputOption;

trait DeletesMatchingTest
{
    /**
     * Add the standard command options for generating matching tests.
     *
     * @return void
     */
    protected function deleteTestOptions()
    {
        foreach (['test' => 'PHPUnit', 'pest' => 'Pest'] as $option => $name) {
            $this->getDefinition()->addOption(new InputOption(
                $option,
                null,
                InputOption::VALUE_NONE,
                "Remove any accompanying {$name} test for the {$this->type}"
            ));
        }
    }

    /**
     * Create the matching test case if requested.
     *
     * @param  string  $path
     * @return bool
     */
    protected function handleTestDeletion($path)
    {
        if (! $this->option('test')) {
            return false;
        }

        return $this->callSilent('destroy:test', [
            'name' => Str::of($path)->after($this->laravel['path'])->beforeLast('.php')->append('Test')->replace('\\', '/'),
        ]) == 0;
    }
}
