<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListenerRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'blueprint' => 'required',
            'event' => 'required',
            'column' => 'required',
            'value' => 'required',
        ];
    }
}
