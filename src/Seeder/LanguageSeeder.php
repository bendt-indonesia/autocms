<?php

namespace Bendt\autocms\Seeder;

use Bendt\autocms\Services\LanguageService;

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
