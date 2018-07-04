<?php

namespace App\Http\Requests\Api;


class UserRequest extends FormRequest
{
    public function rules()
    {
        switch ($this->getMethod()) {
            case 'POST':
                return [
                    'name' => 'required|regex:/^[A-Za-z\-\_]+$/|max:255',
                    'password' => 'required|min:6',
                    'verification_key' => 'required|string',
                    'verification_code' => 'required|string',
                ];
            case 'PATCH':
                $userId = \Auth::guard('api')->id();
                return [
                    'name' => 'regex:/^[A-Za-z\-\_]+$/|max:255',
                    'email' => 'email|unique:users,email,' . $userId,
                    'introduction' => 'max:80',
                    'avatar_image_id' => 'exists:images,id,type,avatar,user_id,' . $userId,
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
            'name.regex' => '用户名由英文字母、横杠、下划线组成',
            'email.unique' => '邮箱已被占用，请重新填写',
        ];
    }
}
