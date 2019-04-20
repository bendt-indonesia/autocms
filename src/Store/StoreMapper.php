<?php

namespace Bendt\autocms\Store;

use Bendt\autocms\Models\Language;
use Bendt\autocms\Models\PageGroup;

class StoreMapper
{
    public static function MAP($key)
    {
        switch ($key)
        {
            case 'language':
                return function() { return Language::all(); };
            case 'page_group':
                return function() { return PageGroup::all(); };
            default:
                return null;
        }
    }
}
