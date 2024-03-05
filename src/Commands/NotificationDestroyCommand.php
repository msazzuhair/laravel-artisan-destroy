<?php

namespace Msazzuhair\LaravelArtisanDestroy\Commands;

use Msazzuhair\LaravelArtisanDestroy\Traits\DeletesMatchingTest;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputOption;

#[AsCommand(name: 'destroy:notification')]
class NotificationDestroyCommand extends DestroyerCommand
{
    use DeletesMatchingTest;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'destroy:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete a notification class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Notification';

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

        if ($this->option('markdown')) {
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
            str_replace('.', '/', $this->option('markdown')).'.blade.php'
        );

        if (! $this->files->exists($path)) {
            $this->error('Markdown template does not exist.');

            return;
        }

        $this->files->delete($path);
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace.'\Notifications';
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
            ['markdown', 'm', InputOption::VALUE_OPTIONAL, 'Delete a Markdown template for the notification'],
        ];
    }
}
