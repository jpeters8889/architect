<?php

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ButtonRequest extends FormRequest
{
    public function rules()
    {
        return [
            'blueprint' => 'required',
            'button' => 'required',
            'id' => 'required|integer',
        ];
    }
}
