<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\CaptchaRequest;
use Gregwar\Captcha\CaptchaBuilder;
use function GuzzleHttp\Psr7\str;
use Illuminate\Http\Request;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $phone = $request->phone;
        $captcha = $captchaBuilder->build();

        $captchaKey = 'captcha:' . str_random(15);
        $expiredAt = now()->addMinutes(2);

        if (!app()->environment('production')) {
            $code = 1234;
        } else {
            $code = $captcha->getPhrase();
        }
        \Cache::put($captchaKey, [
            'code' => $code,
            'phone' => $phone,
        ], $expiredAt);

        return $this->response->array([
            'captcha_key' => $captchaKey,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline(),
        ])->setStatusCode(201);
    }
}
