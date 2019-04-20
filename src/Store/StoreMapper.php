<?php

namespace Bendt\Autocms\Store;

use Bendt\Autocms\Models\Language;
use Bendt\Autocms\Models\PageGroup;

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
