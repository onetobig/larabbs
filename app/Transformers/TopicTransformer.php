<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/4
 * Time: 11:07
 */

namespace App\Transformers;


use App\Models\Topic;
use League\Fractal\TransformerAbstract;

class TopicTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['user', 'category'];
    public function transform(Topic $item)
    {
        return [
            'id' => $item->id,
            'title' => $item->title,
            'body' =>  $item->body,
            'user_id' => (int)$item->user_id,
            'category_id' => (int)$item->category_id,
            'reply_count' => (int)$item->reply_count,
            'view_count' => (int)$item->view_count,
            'last_reply_user_id' => (int)$item->last_reply_user_id,
            'excerpt' =>  $item->excerpt,
            'slug' =>  $item->slug,
            'created_at' => $item->created_at->toDateTimeString(),
            'updated_at' => $item->updated_at->toDateTimeString(),
        ];
    }

    public function includeUser(Topic $topic)
    {
        return $this->item($topic->user, new UserTransformer());
    }

    public function includeCategory(Topic $topic)
    {
        return $this->item($topic->category, new CategoryTransformer());
    }
}