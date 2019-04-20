<?php

namespace Bendt\autocms\Seeder;

use Bendt\autocms\Models\Config;

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
