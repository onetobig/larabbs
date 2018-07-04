<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 9:58
 */

namespace App\Transformers;


use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['roles'];

    public function transform(User $item)
    {
        return [
            'id' => (int) $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'avatar' => $item->avatar,
            'introduction' => $item->introduction,
            'bound_phone' => $item->phone ? true : false,
            'bound_wechat' => ($item->weixin_openid || $item->weixin_unionid) ? true : false,
            'last_actived_at' => $item->last_actived_at->toDateTimeString(),
            'created_at' => $item->created_at->toDateTimeString(),
            'updated_at' => $item->updated_at->toDateTimeString(),
        ];
    }

    public function includeRoles(User $user)
    {
        return $this->collection($user->roles, new RoleTransformer());
    }
}