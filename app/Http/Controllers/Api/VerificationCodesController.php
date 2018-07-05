<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Overtrue\EasySms\EasySms;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $captchaKey = $request->captcha_key;
        $captchaData = \Cache::get($captchaKey);
        if (!$captchaData) {
            return $this->response->error('图片验证码已失效', 422);
        }

        if (!hash_equals((string)$captchaData['code'], (string)$request->captcha_code)) {
            \Cache::forget($captchaKey);
            return $this->response->errorUnauthorized('图片验证码错误');
        }
        $phone = $captchaData['phone'];

        if (!app()->environment('production')) {
            $code = 1234;
        }else {
            $code = str_pad(mt_rand(1, 9999), 4, 0, STR_PAD_LEFT);
            try {
                $result = $easySms->send($phone, [
                    'content'  =>  "【Lbbs社区】您的验证码是{$code}。如非本人操作，请忽略本短信",
                ]);
            } catch (\Overtrue\EasySms\Exceptions\NoGatewayAvailableException $exception) {
                $message = $exception->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?? '短信发送异常');
            }
        }

        $key = 'verificationCode:' . str_random(15);
        $expiredAt = now()->addMinutes(15);
        \Cache::put($key, ['code' => $code, 'phone' => $phone], $expiredAt);
        // 清除图片验证码缓存
        \Cache::forget($captchaKey);

        return $this->response->array([
            'verification_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ])->setStatusCode(201);
    }
}
