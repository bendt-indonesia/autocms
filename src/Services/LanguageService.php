<?php

namespace Bendt\autocms\Services;

use Bendt\autocms\Models\Language as Model;

class LanguageService
{
    public static function getAll()
    {
        return Model::get();
    }

    public static function getById($id)
    {
        return Model::where('id', $id)->first();
    }

    public static function create($input)
    {
        $model = new Model($input);
        $model->save();

        return $model;
    }

    public static function update($id, $input)
    {
        $model = Model::find($id);
        $model->fill($input);
        $model->save();
    }

    public static function delete($id)
    {
        $model = Model::find($id);
        $model->delete();
    }
}
