<?php

namespace App\Http\Requests\Api;


class ImageRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'type' => 'required|string|in:topic,avatar',
        ];

        if ($this->type == 'avatar') {
            $rules['image'] = 'required|mimes:jpeg,bmp,png,gif|dimensions:min_width=200,min_height=200';
        } else {
            $rules['image'] = 'required|mimes:jpeg,bmp,png,gif';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.dimensions' => '图片清晰度不够，需要宽和高都在 200px 以上',
        ];
    }
}
