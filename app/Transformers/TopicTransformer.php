<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/9
 * Time: 16:15
 */

namespace App\Transformers;


use App\Models\Topic;
use League\Fractal\TransformerAbstract;

class TopicTransformer extends TransformerAbstract
{
    public function transform(Topic $topic)
    {
        return [
            'id' => $topic->id,
            'title' => $topic->title,
            'body' => $topic->body,
            'excerpt' => $topic->excerpt,
            'slug' => $topic->slug,
            'user_id' => (int)$topic->user_id,
            'category_id' => (int)$topic->category_id,
            'reply_count' => (int)$topic->reply_count,
            'view_count' => (int)$topic->view_count,
            'last_reply_user_id' => (int)$topic->last_reply_user_id,
            'created_at' => $topic->created_at->toDateTimeString(),
            'updated_at' => $topic->updated_at->toDateTimeString(),
        ];
    }
}