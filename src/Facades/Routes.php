<?php

namespace Bendt\Autocms\Facades;

use Illuminate\Support\Facades\Facade;

class Routes extends Facade
{
    protected static function getFacadeAccessor() { return 'routeManager'; }
}
