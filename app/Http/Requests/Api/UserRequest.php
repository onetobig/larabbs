<?php

namespace App\Http\Requests\Api;


class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'password' => 'required|string|min:6',
            'verification_code' => 'required|string',
            'verification_key' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'verification_code' => '短信验证码',
            'verification_key' => '短信验证码 key',
        ];
    }
}
