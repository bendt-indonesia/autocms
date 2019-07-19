<?php

namespace Bendt\Autocms\Facades;

use Illuminate\Support\Facades\Facade;

class Config extends Facade
{
    protected static function getFacadeAccessor() { return 'configManager'; }
}
