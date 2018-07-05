<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verificationKey = $request->verification_key;
        $verificationData = \Cache::get($verificationKey);
        if (!$verificationData) {
            return $this->response->error('短信验证码已失效', 422);
        }

        if (!hash_equals((string)$verificationData['code'], (string)$request->verification_code)) {
            return $this->response->errorUnauthorized('短信验证码错误');
        }

        $user = User::create([
            'name' => $request->name,
            'phone' => $verificationData['phone'],
            'password' => bcrypt($request->password),
        ]);

        \Cache::forget($verificationKey);

        return $this->response->created();
    }
}
