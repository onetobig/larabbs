<?php

namespace App\Http\Requests\Api;

class VerificationCodeRequest extends FormRequest
{
    public function rules()
    {
        return [
            'captcha_key' => 'required',
            'captcha_code' => 'required',
        ];
    }
}
