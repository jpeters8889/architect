<?php

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImageUploadRequest extends FormRequest
{
    public function rules()
    {
        return [
          'files' => 'required|array',
          'files.*' => 'required|image'
        ];
    }
}