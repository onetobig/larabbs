<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;
use Cache;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $phone = $request->phone;
        $captcha = $captchaBuilder->build();
        $key = 'captcha:' . str_random(15);
        $code = app()->environment('production') ? $captchaBuilder->getPhrase() : 1234;
        $expiredAt = now()->addMinutes(2);

        Cache::put($key, ['phone' => $phone, 'code' => $code], $expiredAt);

        return $this->response->array([
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline(),
        ])->setStatusCode(201);
    }
}
