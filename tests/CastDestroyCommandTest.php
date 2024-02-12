<?php
test('class CastDestroyCommand is present', function () {
    expect(class_exists(\Msazzuhair\LaravelArtisanDestroy\Commands\CastDestroyCommand::class))->toBeTrue();
});

it('can not delete non existent custom Eloquent cast class', function () {
    \Pest\Laravel\artisan('destroy:cast', ['name' => 'MissingDummyCast'])
        ->expectsOutput('Cast "App\\Casts\\MissingDummyCast" doesn\'t exist.');

});

it('can forcefully delete a custom Eloquent cast class', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:cast', ['name' => 'DummyCast']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Casts/DummyCast.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:cast', ['name' => 'DummyCast', '--force' => true]);

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Casts/DummyCast.php')))->toBeFalse();
});

it('deletes the custom Eloquent cast class when confirmed', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:cast', ['name' => 'DummyCast']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Casts/DummyCast.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:cast', ['name' => 'DummyCast'])
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes');

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Casts/DummyCast.php')))->toBeFalse();
});

it('keeps the custom Eloquent cast class when aborted', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:cast', ['name' => 'DummyCast']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Casts/DummyCast.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:cast', ['name' => 'DummyCast'])
        ->expectsConfirmation('Are you sure you want to proceed?', 'no');

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Casts/DummyCast.php')))->toBeTrue();
});

it('shows warning if the custom Eloquent cast class file is untracked', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:cast', ['name' => 'UntrackedDummyCast']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Casts/UntrackedDummyCast.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:cast', ['name' => 'UntrackedDummyCast'])
        ->expectsOutput('The file is not tracked by git. Any deletion will be irreversible.')
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes')
        ->assertSuccessful();

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Casts/UntrackedDummyCast.php')))->toBeFalse();
});

it('shows warning if there are uncommited changes', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:cast', ['name' => 'DirtyDummyCast']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Casts/DirtyDummyCast.php')))->toBeTrue();

    // Add DirtyDummyCast.php to git
    shell_exec('git -C ' . base_path() . ' add ' . \app_path('Casts/DirtyDummyCast.php'));

    // Commit DirtyDummyCast.php
    shell_exec('git -C ' . base_path() . ' commit --quiet -m "Add DirtyDummyCast.php"');

    // Add some newlines to the file
    $content = file_get_contents(\app_path('Casts/DirtyDummyCast.php'));
    $content .= "\n\$x = 1;\n\n\n";
    file_put_contents(\app_path('Casts/DirtyDummyCast.php'), $content);

    // Delete the class
    \Pest\Laravel\artisan('destroy:cast', ['name' => 'DirtyDummyCast'])
        ->expectsOutput('The file has uncommited changes. Any deletion will be irreversible.')
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes')
        ->assertSuccessful();

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Casts/DirtyDummyCast.php')))->toBeFalse();
});

it('asks for the name of custom Eloquent cast class to be deleted when name is not supplied', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:cast', ['name' => 'DummyCast']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Casts/DummyCast.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:cast')
        ->expectsQuestion('What is the name of the cast that you would like to delete?', 'DummyCast')
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes')
        ->assertSuccessful();
});
