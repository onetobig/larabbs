<?php

namespace App\Http\Requests\Api;


class ImageRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'type' => 'required|string|in:avatar,topic',
        ];

        switch ($this->input('type')) {
            case 'avatar':
                $rules['image'] = [
                    'required',
                    'mimes:jpeg,bmp,png,gif',
                    'dimensions:min_width=200,min_height=200',
                ];
                break;
            case 'topic':
                $rules['image'] = [
                    'required',
                    'mimes:jpeg,bmp,png,gif',
                ];
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'image.dimensions' => '图片清晰度不够，宽和高需要 200px 以上',
        ];
    }
}
