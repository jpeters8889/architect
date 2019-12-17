<?php

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListenerRequest extends FormRequest
{
    public function rules()
    {
        return [
            'blueprint' => 'required',
            'event' => 'required',
            'column' => 'required',
            'value' => 'required',
        ];
    }
}
