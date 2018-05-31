<?php

namespace App\Http\Requests\Api;

use Dingo\Api\Http\FormRequest as BaseFormRequest;
#use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class FormRequest extends BaseFormRequest
{
    public function authorize()
    {
        return true;
    }
}
