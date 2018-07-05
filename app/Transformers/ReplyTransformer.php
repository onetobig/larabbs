<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/5
 * Time: 16:06
 */

namespace App\Transformers;


use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract
{
    public function transform(Reply $item)
    {
        return [
            'id' => $item->id,
            'topic_id' => $item->topic_id,
            'user_id' => $item->user_id,
            'content' => $item->content,
            'updated_at' => $item->updated_at->toDateTimeString(),
            'created_at' => $item->created_at->toDateTimeString(),
        ];
    }
}