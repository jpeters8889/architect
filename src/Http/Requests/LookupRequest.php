<?php

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LookupRequest extends FormRequest
{
    public function rules()
    {
        return [
          'blueprint' => 'required',
          'column' => 'required',
          'value' => 'required',
        ];
    }
}
