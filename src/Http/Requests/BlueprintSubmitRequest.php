<?php

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlueprintSubmitRequest extends FormRequest
{
    public function rules()
    {
        return [
            '_blueprint' => 'required|string',
            '_state' => 'required|in:add,update',
            '_id' => 'required_if:_state,update',
        ];
    }
}
