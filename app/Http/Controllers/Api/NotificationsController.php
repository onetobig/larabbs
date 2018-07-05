<?php

namespace App\Http\Controllers\Api;

use App\Transformers\NotificationTransformer;
use Illuminate\Http\Request;

class NotificationsController extends Controller
{
    public function index()
    {
        $user = $this->user();
        $notifications = $user->notifications()->paginate(20);
        return $this->response->paginator($notifications, new NotificationTransformer());
    }
}
