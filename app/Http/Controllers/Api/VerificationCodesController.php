<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\VerificationCodeRequest;
use Illuminate\Http\Request;
use Cache;
use Overtrue\EasySms\EasySms;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class VerificationCodesController extends Controller
{
    public function store(VerificationCodeRequest $request, EasySms $easySms)
    {
        $verifyData = Cache::get($request->captcha_key);
        if (!$verifyData) {
            return $this->response->error('图片验证码已过期', 422);
        }

        if (!hash_equals((string)$verifyData['code'], $request->captcha_code)) {
            Cache::forget($request->captcha_key);
            return $this->response->errorUnauthorized('图片验证码错误');
        }

        $phone = $verifyData['phone'];

        if (!app()->environment('production')) {
            $code = 1234;
        } else {
            $code = str_pad(mt_rand(1, 9999), 4, 0, STR_PAD_LEFT);
            try {
                $result = $easySms->send($phone, [
                    'content' => "【Lbbs社区】您的验证码是{$code}。如非本人操作，请忽略本短信",
                ]);
            } catch (NoGatewayAvailableException $e) {
                $message = $e->getException('yunpian')->getMessage();
                return $this->response->errorInternal($message ?? '短信发送异常');
            }

        }
        $key = 'verificationCode:' . str_random(15);
        $expiredAt = now()->addMinutes(10);
        Cache::put($key, ['code' => $code, 'phone' => $phone], $expiredAt);
        Cache::forget($request->captcha_key);

        $data = [
            'verification_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
        ];

        return $this->response->array($data)->setStatusCode(201);
    }
}
