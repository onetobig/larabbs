<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $key = $request->input('verification_key');
        $verificationData = \Cache::get($key);
        if (!$verificationData) {
            return $this->response->error('验证码已失效', 422);
        }

        if (!hash_equals($verificationData['code'], $request->input('verification_code'))) {
            // 401
            return $this->response->errorUnauthorized('手机验证码错误');
        }

        $user = User::create([
            'name' => $request->input('name'),
            'password' => bcrypt($request->input('password')),
            'phone' => $verificationData['phone'],
        ]);

        \Cache::forget($key);

        return $this->response->created();
    }
}
