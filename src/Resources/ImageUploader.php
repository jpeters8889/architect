<?php

namespace JPeters\Architect\Resources;

use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use JPeters\Architect\Contracts\ImageUploaderContract;

class ImageUploader implements ImageUploaderContract
{
    public function upload(UploadedFile $file, $directory = '')
    {
        return $file->store(storage_path($directory));
    }

    public function retrieve($filename, $directory = '')
    {
        return Storage::url($directory . $filename);
    }
}
