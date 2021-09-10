<?php

namespace RayNl\LaravelSmtp2go\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \RayNl\LaravelSmtp2go\LaravelSmtp2goEmail
 */
class LaravelSmtp2goEmailFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'smtp2go-email';
    }
}
