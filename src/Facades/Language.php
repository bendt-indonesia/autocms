<?php

namespace Bendt\Autocms\Facades;

use Illuminate\Support\Facades\Facade;

class Language extends Facade
{
    protected static function getFacadeAccessor() { return 'languageManager'; }
}
