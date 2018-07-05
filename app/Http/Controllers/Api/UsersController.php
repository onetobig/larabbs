<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\UserRequest;
use App\Models\Image;
use App\Models\User;
use App\Transformers\UserTransformer;
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

        return $this->response->item($user, new UserTransformer())
            ->setMeta([
                'access_token' => \Auth::guard('api')->fromUser($user),
                'type' => 'Bearer',
                'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60,
            ])
            ->setStatusCode(201);
    }

    public function me()
    {
        return $this->response->item($this->user, new UserTransformer());
    }

    public function update(UserRequest $request)
    {
        $attributes = $request->only(['name', 'email', 'introduction']);

        if ($request->avatar_image_id) {
            $image = Image::find($request->avatar_image_id);
            $attributes['avatar'] = $image->path;
        }

        $user = $this->user;
        $user->update($attributes);
        return $this->response->item($user, new UserTransformer());
    }
}
