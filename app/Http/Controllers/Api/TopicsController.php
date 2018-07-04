<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Transformers\TopicTransformer;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function store(TopicRequest $request, Topic $topic)
    {
        $attributes = $request->only(['title', 'body', 'category_id']);
        $topic->fill($attributes);
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);
    }

    public function update(Topic $topic, TopicRequest $request)
    {
        $this->authorize('update', $topic);
        $attributes = $request->only(['title', 'body', 'category_id']);
        $topic->update($attributes);
        return $this->response->item($topic, new TopicTransformer())
            ->setStatusCode(201);
    }
}
