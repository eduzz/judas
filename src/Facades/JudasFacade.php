<?php

namespace Eduzz\Judas\Facades;

use Eduzz\Judas\Judas;
use Illuminate\Support\Facades\Facade;

class JudasFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Judas';
    }
}
