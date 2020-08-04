<?php

declare(strict_types=1);

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'blueprint' => 'required',
            'name' => 'required',
            'value' => 'required',
            'order' => 'required|in:up,down',
        ];
    }
}
