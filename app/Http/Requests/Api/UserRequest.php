<?php

namespace App\Http\Requests\Api;


class UserRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'required|regex:/^[A-Za-z\-\_]+$/|max:255',
            'password' => 'required|min:6',
            'verification_key' => 'required|string',
            'verification_code' => 'required|string',
        ];
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
            'name.regex' => '名称由英文字母、横杠、下划线组成',
        ];
    }
}
