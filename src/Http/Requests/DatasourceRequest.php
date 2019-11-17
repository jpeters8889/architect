<?php

namespace JPeters\Architect\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DatasourceRequest extends FormRequest
{
    public function rules()
    {
        return [
            'dataSource' => 'required|string',
            'searchTerm' => 'required|string',
        ];
    }
}