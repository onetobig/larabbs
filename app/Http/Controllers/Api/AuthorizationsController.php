<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\AuthorizationRequest;
use App\Http\Requests\Api\SocialAuthorizationRequest;
use App\Models\User;
use Illuminate\Http\Request;

class AuthorizationsController extends Controller
{
    public function socialStore($type, SocialAuthorizationRequest $request)
    {
        if (!in_array($type, ['weixin'])) {
            return $this->response->errorBadRequest();
        }

        $driver = \Socialite::driver($type);

        try {
            if ($request->code) {
                $response = $driver->getAccessTokenResponse($request->code);
                $token = array_get($response, 'access_token');
            } else {
                $token = $request->access_token;
                if ($type === 'weixin') {
                    $driver->setOpenId($request->openid);
                }
            }
            $oauthUser = $driver->userFromToken($token);
        } catch (\Exception $e) {
            return $this->response->errorUnauthorized('参数错误，未获取用户信息');
        }

        switch ($type) {
            case 'weixin':
                $unionid = $oauthUser->offsetExists('unionid') ? $oauthUser->offsetGet('unionid') : null;
                if ($unionid) {
                    $user = User::where('weixin_unionid', $unionid)->first();
                } else {
                    $user = User::where('weixin_openid', $oauthUser->getId())->first();
                }

                if (!$user) {
                    $user = User::create([
                        'weixin_unionid' => $unionid,
                        'weixin_openid' => $oauthUser->getId(),
                        'name' => $oauthUser->getNickName(),
                        'avatar' => $oauthUser->getAvatar(),
                    ]);
                }
                break;
            default:
                return $this->response->errorBadRequest('未知登录方式');
                break;
        }

        $token = \Auth::guard('api')->fromUser($user);
        return $this->responseWithToken($token)->setStatusCode(201);
    }

    public function store(AuthorizationRequest $request)
    {
        $username = $request->username;

        filter_var($username, FILTER_VALIDATE_EMAIL) ?
            $credentials['email'] = $username :
            $credentials['phone'] = $username;
        $credentials['password'] = $request->password;

        if (!$token = \Auth::guard('api')->attempt($credentials)) {
            return $this->response->errorUnauthorized(trans('auth.failed'));
        }

        return $this->responseWithToken($token)
            ->setStatusCode(201);
    }

    private function responseWithToken($token)
    {
        return $this->response->array([
            'access_token' => $token,
            'type' => 'Bearer',
            'expires_in' => \Auth::guard('api')->factory()->getTTL() * 60,
        ]);
    }

    public function update()
    {
        $token = \Auth::guard('api')->refresh();
        return $this->responseWithToken($token);
    }

    public function destroy()
    {
        \Auth::guard('api')->logout();
        return $this->response->noContent();
    }
}
