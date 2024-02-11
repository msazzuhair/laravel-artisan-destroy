<?php

// config for Msazzuhair/LaravelArtisanDestroy
return [
    /* --------------------------------------------------------------------------
    | Enable deletion confirmation
    | --------------------------------------------------------------------------
    |
    | Set to true if you want to enable deletion confirmation. Defaults to TRUE.
    | Be careful when you set this to false. Any deletion will be irreversible.
    | You might want to use --force option instead of disabling this.
    | Disabling this will also disable git tracking confirmation.
    |
    */

    'confirmation' => true,

    /* --------------------------------------------------------------------------
    | Enable git checking
    | --------------------------------------------------------------------------
    |
    | This will check if the current working directory is a git repository.
    | It will also check if the file you are trying to delete is tracked by
    | git. Deleting untracked file will result in an error. You can still
    | delete any untracked files with the --force option.
    |
    */

    'git-tracking-confirmation' => true,
];
