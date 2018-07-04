<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 13:53
 */

namespace App\Transformers;


use App\Models\Reply;
use League\Fractal\TransformerAbstract;

class ReplyTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'topic'];

    public function transform(Reply $item)
    {
        return [
            'id' => $item->id,
            'user_id' => (int)$item->user_id,
            'topic_id' => (int)$item->topic_id,
            'content' => $item->content,
            'created_at' => $item->created_at->toDateTimeString(),
            'updated_at' => $item->updated_at->toDateTimeString(),
        ];
    }

    public function includeUser(Reply $reply)
    {
        return $this->item($reply->user, new UserTransformer());
    }

    public function includeTopic(Reply $reply)
    {
        return $this->item($reply->topic, new TopicTransformer());
    }
}