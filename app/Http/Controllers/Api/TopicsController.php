<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\TopicRequest;
use App\Models\Topic;
use App\Models\User;
use App\Transformers\TopicsTransformer;
use Illuminate\Http\Request;

class TopicsController extends Controller
{
    public function store(TopicRequest $request, Topic $topic)
    {
        $attributes = $request->only('title', 'body', 'category_id');
        $topic->fill($attributes);
        $topic->user_id = $this->user()->id;
        $topic->save();

        return $this->response->item($topic, new TopicsTransformer())
            ->setStatusCode(201);
    }

    public function update(Topic $topic, TopicRequest $request)
    {
        $this->authorize('update', $topic);
        $attributes = $request->only('title', 'body', 'category_id');
        $topic->update($attributes);

        return $this->response->item($topic, new TopicsTransformer());
    }

    public function destroy(Topic $topic)
    {
        $this->authorize('destroy', $topic);
        $topic->delete();

        return $this->response->noContent();
    }

    public function index(Request $request, Topic $topic)
    {
        $query = $topic->query();

        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }

        switch ($request->order) {
            case 'recent':
                $query->recent();
                break;
            default:
                $query->recentReplied();
                break;
        }

        $topics = $query->paginate(20);
        return $this->response->paginator($topics, new TopicsTransformer());
    }

    public function userIndex(User $user, Request $request)
    {
        $topics = $user->topics()->recent()->paginate(20);

        return $this->response->paginator($topics, new TopicsTransformer());
    }

    public function show(Topic $topic)
    {
        return $this->response->item($topic, new TopicsTransformer());
    }
}
