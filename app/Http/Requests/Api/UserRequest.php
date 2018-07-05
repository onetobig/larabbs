<?php

namespace App\Http\Requests\Api;


class UserRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->method()) {
            case 'POST':
                return [
                    'name' => 'required|string|max:255',
                    'password' => 'required|string|min:6',
                    'verification_code' => 'required|string',
                    'verification_key' => 'required|string',
                ];
            case 'PATCH':
                $user_id = \Auth::guard('api')->id();
                return [
                    'name' => 'string|max:255',
                    'email' => 'email|unique:users',
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,' . $user_id,
                ];
        }
    }

    public function attributes()
    {
        return [
            'verification_code' => '短信验证码',
            'verification_key' => '短信验证码 key',
        ];
    }
}
