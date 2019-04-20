<?php
namespace Bendt\autocms\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    private static function appendExtension($file, $name)
    {
        $ext = $file->getClientOriginalExtension();
        return $name . '.' . $ext;
    }

    public static function generateFilename($file, $path, $name)
    {
        $image_name = self::appendExtension($file, $name);
        return $path . $image_name;
    }

    public static function generateThumbs($full_path)
    {
        $path_parts = pathinfo($full_path);
        $img = Image::make(storage_path()."/app/public/".$full_path);
        $width = $img->width();
        $height = $img->height();

        if(config('bendt-thumbs.aspectRatio')) {
            if(config('bendt-thumbs.aspectBase') == 'width') {
                $img->widen(config('bendt-thumbs.width'));
            } else {
                $img->heighten(config('bendt-thumbs.height'));
            }
        } else {
            $img->resize(config('bendt-thumbs.width'),config('bendt-thumbs.height'));
        }

        $dirname = storage_path()."/app/public/".$path_parts['dirname']."/";

        if(!config('bendt-thumbs.replaceImage')) {
            $dirname = $dirname."/thumbs/";
            if (!file_exists($dirname)) {
                mkdir($dirname, 0777, true);
            }
        }

        $img->save($dirname.$path_parts['basename']);
    }

    public static function processAndSave($model, $field_name, $image = null)
    {
        $with_image = !is_null($image);
        $default_path = '/upload/';

        // Insert Image Url
        if($with_image) {
            $file_name = md5(microtime());
            $model->image_url = ImageService::generateFilename($image, $default_path, $file_name);
        }

        $model->save();

        //Save uploaded Image
        if($with_image && isset($file_name))
        {
            self::save($image, $default_path, $file_name);
        }
    }

    public static function remove($image_url) {
        Storage::delete('public' . $image_url);
    }

    public static function save($file, $path, $name = null, $thumbs = false){
        if($name != null) {
            $image_name = self::appendExtension($file, $name);
            $file->storeAs("public" . $path, $image_name);
            if($thumbs) self::generateThumbs($path . $image_name);

            return $path . $image_name;
        } else {
            $path = $file->store("public" . $path);
            if($thumbs) self::generateThumbs(substr($path,6));

            return substr($path,6);
        }
    }

    public static function saveBase64($raw, $path, $name = null){
        $img = Image::make($raw);

        if(config('bendt-thumbs.aspectRatio')) {
            if(config('bendt-thumbs.aspectBase') == 'width') {
                $img->widen(config('bendt-thumbs.width'));
            } else {
                $img->heighten(config('bendt-thumbs.height'));
            }
        } else {
            $img->resize(config('bendt-thumbs.width'),config('bendt-thumbs.height'));
        }

        $img->save(storage_path()."/app/public".$path."thumbs/".$name);
    }

    public static function slug_with_number($string)
    {
        return str_slug($string . '-' . rand(0,9) . rand(0,9) . rand(0,9));
    }
}
