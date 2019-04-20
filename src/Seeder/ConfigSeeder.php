<?php

namespace Bendt\Autocms\Seeder;

use Bendt\Autocms\Models\Config;

class ConfigSeeder
{
    public static function seed($keyvaluepairs)
    {
        foreach ($keyvaluepairs as $key => $value)
        {
            (new Config([
                "name" => $key,
                "value" => $value
            ]))->save();
        }
    }
}
