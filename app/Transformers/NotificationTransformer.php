<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9
 * Time: 17:06
 */

namespace App\Transformers;


use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $notification)
    {
        return [
            'id'  => $notification->id,
            'data' => $notification->data,
            'read_at' => $notification->read_at ? $notification->read_at->toDateTimeString() : null,
            'created_at' => $notification->created_at->toDateTimeString(),
            'updated_at' => $notification->updated_at->toDateTimeString(),
        ];
    }
}