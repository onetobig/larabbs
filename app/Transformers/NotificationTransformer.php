<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 14:29
 */

namespace App\Transformers;


use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $item)
    {
        return [
            'id' => $item->id,
            'type' => $item->type,
            'data' => $item->data,
            'read_at' => $item->read_at ? $item->read_at->toDateTimeString() : null,
            'created_at' => $item->created_at->toDateTimeString(),
            'updated_at' => $item->updated_at->toDateTimeString(),
        ];
    }
}