<?php

namespace Bendt\Autocms\Seeder;

use Bendt\Autocms\Services\LanguageService;
use Bendt\Autocms\Models\Language;

class LanguageSeeder
{
    public static function create($name, $iso)
    {
        $lang = Language::where('iso',$iso)->first();
        if(!$lang) {
            $lang = LanguageService::create([
                'name' => $name,
                'iso' => $iso,
            ]);
        }

        return $lang;
    }
}
