<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Cache;

class UsersController extends Controller
{
    public function store(UserRequest $request)
    {
        $verifyData = Cache::get($request->verification_key);
        if (!$verifyData) {
            return $this->response->error('短信验证码已过期', 422);
        }

        if (!hash_equals((string)$verifyData['code'], $request->verification_code)) {
            return $this->response->errorUnauthorized('短信验证码错误');
        }

        $user = User::create([
            'name' => $request->username,
            'password' => bcrypt($request->password),
            'phone' => $verifyData['phone'],
        ]);

        Cache::forget($request->verification_key);

        return $this->response->created();
    }
}
