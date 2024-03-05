<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands\Database\Migrations;

use Illuminate\Contracts\Console\PromptsForMissingInput;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Support\Composer;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Finder;

class MigrateDestroyCommand extends BaseCommand implements PromptsForMissingInput
{
    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'destroy:migration {name : The name of the migration}
        {--force : Force the operation to run without confirmation}
        {--path= : The location of the migration file}
        {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a migration file';

    /**
     * The migration creator instance.
     *
     * @var \Msazzuhair\LaravelArtisanDestroy\Commands\Database\Migrations\MigrationDestroyer
     */
    protected $destroyer;

    /**
     * The Composer instance.
     *
     * @var \Illuminate\Support\Composer
     *
     * @deprecated Will be removed in a future Laravel version.
     */
    protected $composer;

    /**
     * Create a new migration install command instance.
     *
     * @return void
     */
    public function __construct(MigrationDestroyer $destroyer, Composer $composer)
    {
        parent::__construct();

        $this->destroyer = $destroyer;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // It's possible for the developer to specify the tables to modify in this
        // schema operation. The developer may also specify if this table needs
        // to be freshly created so we can create the appropriate migrations.
        $name = Str::snake(trim($this->input->getArgument('name')));

        $migration = null;
        $possibleMigrations = $this->possibleMigrations();
        if (count($possibleMigrations) === 0) {
            $this->components->info('No migrations found.');

            return false;
        } elseif (count($possibleMigrations) === 1) {
            $migration = $possibleMigrations[0];
        } else {
            $migration = $this->choice(
                'What is the name of the migration file that should be deleted?',
                $this->possibleMigrations(),
            );
        }

        if (! $this->confirmToProceed($this->getMigrationPath().'/'.$migration.'.php')) {
            return false;
        }

        $this->deleteMigration($migration);
    }

    /**
     * Write the migration file to disk.
     *
     * @param  string  $name
     * @param  string  $table
     * @param  bool  $create
     * @return void
     *
     * @throws \Exception
     */
    protected function deleteMigration($name)
    {
        $file = $this->destroyer->delete(
            $name, $this->getMigrationPath()
        );

        $this->info(sprintf('Migration [%s] deleted successfully.', $file));
    }

    /**
     * Get migration path (either specified by '--path' option or default location).
     *
     * @return string
     */
    protected function getMigrationPath()
    {
        if (! is_null($targetPath = $this->input->getOption('path'))) {
            return ! $this->usingRealPath()
                            ? $this->laravel->basePath().'/'.$targetPath
                            : $targetPath;
        }

        return parent::getMigrationPath();
    }

    /**
     * Get a list of possible migration names.
     *
     * @return array<int, string>
     */
    protected function possibleMigrations()
    {
        $migrationPath = $this->getMigrationPath();

        if (! is_dir($migrationPath)) {
            return [];
        }

        $name = Str::snake(trim($this->input->getArgument('name')));

        return collect(Finder::create()->files()->name('/'.$name.'/')->depth(0)->in($migrationPath))
            ->map(fn ($file) => $file->getBasename('.php'))
            ->sort()
            ->values()
            ->all();
    }

    /**
     * Prompt for missing input arguments using the returned questions.
     *
     * @return array
     */
    protected function promptForMissingArgumentsUsing()
    {
        return [
            'name' => ['What is the name of the migration file (without the timestamp)?', $this->possibleMigrations()],
        ];
    }

    protected function confirmToProceed($path)
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
                'Are you sure you want to delete '.$path.'?'
            );

            if (! $confirmation) {
                $this->error('Aborted.');
            }

            return $confirmation;
        }

        return false;
    }
}
