<?php

it('shows warning if git is not initialized in current workdir', function () {
    // Remove .git folder from base_path()
    shell_exec('rm -rf vendor/orchestra/testbench-core/laravel/.git');

    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:cast', ['name' => 'NoGitDummyCast']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Casts/NoGitDummyCast.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:cast', ['name' => 'NoGitDummyCast'])
        ->expectsOutput('Git is not initialized in this project. Any deletion will be irreversible.')
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes')
        ->assertSuccessful();

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Casts/NoGitDummyCast.php')))->toBeFalse();
});
