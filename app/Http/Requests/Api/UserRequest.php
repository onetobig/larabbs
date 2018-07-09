<?php

namespace App\Http\Requests\Api;


class UserRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'username' => 'required|string|regex:/^[A-Za-z0-9\-\_]+$/|max:255',
                    'password' => 'required|string|min:6',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string',
                ];
        }
    }

    public function attributes()
    {
        return [
            'verification_key' => '手机验证码 key',
            'verification_code' => '手机验证码',
        ];
    }

    public function messages()
    {
        return [
            'username.regex' => '用户名必须是由字母、数字、横杠、下划线组成',
        ];
    }
}
