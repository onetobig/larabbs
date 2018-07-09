<?php

namespace App\Http\Controllers\Api;

use App\Handlers\ImageUploadHandler;
use App\Http\Requests\Api\ImageRequest;
use App\Models\Image;
use App\Transformers\ImageTransformer;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function store(ImageRequest $request, ImageUploadHandler $uploader, Image $image)
    {
        $type = $request->type;
        $user = $this->user();
        $size = $type === 'avatar' ? 362 : 1024;
        $result = $uploader->save($request->image, str_plural($type), $user->id, $size);

        $image->type = $type;
        $image->user_id = $user->id;
        $image->path = $result['path'];
        $image->save();

        return $this->response->item($image, new ImageTransformer())
            ->setStatusCode(201);
    }
}
