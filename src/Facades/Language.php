<?php

namespace Bendt\autocms\Facades;

use Illuminate\Support\Facades\Facade;

class Language extends Facade
{
    protected static function getFacadeAccessor() { return 'languagemanager'; }
}
