<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 13:50
 */

namespace App\Transformers;


use App\Models\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $item)
    {
        return [
            'id' => $item->id,
            'name' => $item->name,
            'email' => $item->email,
            'avatar' => $item->avatar,
            'introduction' => $item->introduction,
            'bound_phone' => $item->phone ? true : false,
            'bound_wechat' => ($item->weixin_openid || $item->weixin_unionid) ?  true : false,
            'last_actived_at' => $item->last_actived_at->toDateTimeString(),
            'created_at' => $item->created_at->toDateTimeString(),
            'updated_at' => $item->updated_at->toDateTimeString(),
        ];
    }
}