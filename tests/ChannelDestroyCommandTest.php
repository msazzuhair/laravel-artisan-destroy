<?php

test('class ChannelDestroyCommand is present', function () {
    expect(class_exists(\Msazzuhair\LaravelArtisanDestroy\Commands\ChannelDestroyCommand::class))->toBeTrue();
});

it('can not delete non existent command class', function () {
    \Pest\Laravel\artisan('destroy:channel', ['name' => 'MissingDummyChannel'])
        ->expectsOutput('Channel "App\\Broadcasting\\MissingDummyChannel" doesn\'t exist.');

});

it('can forcefully delete a command class', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:channel', ['name' => 'DummyChannel']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Broadcasting/DummyChannel.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:channel', ['name' => 'DummyChannel', '--force' => true]);

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Broadcasting/DummyChannel.php')))->toBeFalse();
});

it('deletes the command class when confirmed', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:channel', ['name' => 'DummyChannel']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Broadcasting/DummyChannel.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:channel', ['name' => 'DummyChannel'])
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes');

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Broadcasting/DummyChannel.php')))->toBeFalse();
});

it('keeps the command class when aborted', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:channel', ['name' => 'DummyChannel']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Broadcasting/DummyChannel.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:channel', ['name' => 'DummyChannel'])
        ->expectsConfirmation('Are you sure you want to proceed?', 'no');

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Broadcasting/DummyChannel.php')))->toBeTrue();
});

it('shows warning if the command class file is untracked', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:channel', ['name' => 'UntrackedDummyChannel']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Broadcasting/UntrackedDummyChannel.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:channel', ['name' => 'UntrackedDummyChannel'])
        ->expectsOutput('The file is not tracked by git. Any deletion will be irreversible.')
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes')
        ->assertSuccessful();

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Broadcasting/UntrackedDummyChannel.php')))->toBeFalse();
});

it('shows warning if there are uncommited changes', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:channel', ['name' => 'DirtyDummyChannel']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Broadcasting/DirtyDummyChannel.php')))->toBeTrue();

    // Add DirtyDummyChannel.php to git
    shell_exec('git -C '.base_path().' add '.\app_path('Broadcasting/DirtyDummyChannel.php'));

    // Commit DirtyDummyChannel.php
    shell_exec('git -C '.base_path().' commit --quiet -m "Add DirtyDummyChannel.php"');

    // Add some newlines to the file
    $content = file_get_contents(\app_path('Broadcasting/DirtyDummyChannel.php'));
    $content .= "\n\$x = 1;\n\n\n";
    file_put_contents(\app_path('Broadcasting/DirtyDummyChannel.php'), $content);

    // Delete the class
    \Pest\Laravel\artisan('destroy:channel', ['name' => 'DirtyDummyChannel'])
        ->expectsOutput('The file has uncommited changes. Any deletion will be irreversible.')
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes')
        ->assertSuccessful();

    // Check if the generated class does not exist
    expect(file_exists(\app_path('Broadcasting/DirtyDummyChannel.php')))->toBeFalse();
});

it('asks for the name of command class to be deleted when name is not supplied', function () {
    // Generate a dummy eloquent cast class
    \Pest\Laravel\artisan('make:channel', ['name' => 'DummyChannel']);

    // Check if the generated class exists
    expect(file_exists(\app_path('Broadcasting/DummyChannel.php')))->toBeTrue();

    // Delete the class
    \Pest\Laravel\artisan('destroy:channel')
        ->expectsQuestion('What is the name of the channel that you would like to delete?', 'DummyChannel')
        ->expectsConfirmation('Are you sure you want to proceed?', 'yes')
        ->assertSuccessful();
});
