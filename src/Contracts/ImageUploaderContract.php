<?php

namespace JPeters\Architect\Contracts;

use Illuminate\Http\UploadedFile;

interface ImageUploaderContract
{
    public function upload(UploadedFile $file, $directory = '');

    public function retrieve($filename);
}
