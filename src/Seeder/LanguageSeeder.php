<?php

namespace Bendt\Autocms\Seeder;

use Bendt\Autocms\Services\LanguageService;

class LanguageSeeder
{
    public static function create($name, $iso)
    {
        $model = LanguageService::create([
            'name' => $name,
            'iso' => $iso,
        ]);

        return $model;
    }
}
