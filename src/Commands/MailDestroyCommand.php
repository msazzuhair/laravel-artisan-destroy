<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Illuminate\Support\Str;
use Msazzuhair\LaravelArtisanDestroy\Traits\DeletesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'destroy:mail')]
class MailDestroyCommand extends DestroyerCommand
{
    use DeletesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete an email class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Mailable';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (parent::handle() === false && ! $this->option('force')) {
            return;
        }

        if ($this->option('markdown') !== false) {
            $this->deleteMarkdownTemplate();
        }
    }

    /**
     * Write the Markdown template for the mailable.
     *
     * @return void
     */
    protected function deleteMarkdownTemplate()
    {
        $path = $this->viewPath(
            str_replace('.', '/', $this->getView()).'.blade.php'
        );

        $this->files->delete($path);
    }

    /**
     * Get the view name.
     *
     * @return string
     */
    protected function getView()
    {
        $view = $this->option('markdown');

        if (! $view) {
            $name = str_replace('\\', '/', $this->argument('name'));

            $view = 'mail.'.collect(explode('/', $name))
                ->map(fn ($part) => Str::kebab($part))
                ->implode('.');
        }

        return $view;
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Mail';
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
            ['markdown', 'm', InputOption::VALUE_OPTIONAL, 'Delete a Markdown template for the mailable', false],
        ];
    }
}
