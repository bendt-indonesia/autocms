<?php

namespace Bendt\Autocms\Controllers;

use App\Http\Controllers\MyController;
use Bendt\Autocms\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadController
{
    public function image(Request $request)
    {
        try {
            $this->validate($request, [
                'image' => 'required|image|max:10124'
            ]);
        }
        catch (\Exception $e) {
            return response(["success"=>0])->setStatusCode(500);
        }

        $file_name = md5(microtime());
        $image = $request->file('image');
        $image_url = ImageService::save($image, "/upload/images/", $file_name);


        return ["url" => Storage::url($image_url),"success"=>1];
    }
}
