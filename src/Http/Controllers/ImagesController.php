<?php

namespace JPeters\Architect\Http\Controllers;

use Throwable;
use Illuminate\Support\Collection;
use Intervention\Image\ImageManager;
use JPeters\Architect\Http\Requests\ImageUploadRequest;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImagesController extends BaseController
{
    public function upload(ImageManager $imageManager, ImageUploadRequest $request)
    {
        try {
            $uploadDirectory = config('architect.upload_directory');

            return (new Collection($request->files->all()['files']))
                ->transform(static function (UploadedFile $file) use ($imageManager, $uploadDirectory) {
                    $filename = $file->getClientOriginalName();
                    $image = $imageManager->make($file->getRealPath())
                        ->save($url = "{$uploadDirectory}/{$filename}");

                    return [
                        'path' => $url,
                        'filename' => $filename,
                        'width' => $image->width(),
                        'height' => $image->height(),
                    ];
                })
                ->values();
        } catch (Throwable $e) {
            abort(500, 'There was an error uploading the images');
        }
    }
}