<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Http\Request;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $captcha = $captchaBuilder->build();
        $key = 'captcha:' . str_random(15);
        $phone = $request->input('phone');
        $expiredAt = now()->addMinutes(2);
        \Cache::put($key, ['code' => $captcha->getPhrase(), 'phone' => $phone], $expiredAt);

        return $this->response->array([
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline(),
        ])->setStatusCode(201);
    }
}
