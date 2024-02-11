<?php

namespace Msazzuhair\LaravelArtisanDestroy\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Msazzuhair\LaravelArtisanDestroy\LaravelArtisanDestroy
 */
class LaravelArtisanDestroy extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Msazzuhair\LaravelArtisanDestroy\LaravelArtisanDestroy::class;
    }
}
