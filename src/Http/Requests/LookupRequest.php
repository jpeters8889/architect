<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LookupRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'blueprint' => 'required',
            'column' => 'required',
            'value' => 'required',
        ];
    }
}
