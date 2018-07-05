<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 16:42
 */

namespace App\Transformers;


use Illuminate\Notifications\DatabaseNotification;
use League\Fractal\TransformerAbstract;

class NotificationTransformer extends TransformerAbstract
{
    public function transform(DatabaseNotification $item)
    {
        return [
            'id' =>  $item->id,
            'data' => $item->data,
            'read_at' =>  $item->read_at ? $item->read_at->toDateTimeString() : null,
            'created_at' => $item->created_at->toDateTimeString(),
            'updated_at' => $item->updated_at->toDateTimeString(),
        ];
    }
}