<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/28
 * Time: 15:26
 */

namespace App\Transformers;


use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'avatar'  => $user->avatar,
            'introduction' => $user->introduction,
            'bound_phone' => $user->phone ? true : false,
            'bound_wechat' => ($user->weixin_unionid || $user->weixin_openid) ? true : false,
            'last_actived_at' => $user->last_actived_at->toDateTimeString(),
            'created_at' => $user->created_at,
            'updated_at' => $user->updated_at,
        ];
    }
}