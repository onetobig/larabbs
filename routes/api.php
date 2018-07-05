<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
$api = app(\Dingo\Api\Routing\Router::class);

$api->version('v1', [
    'namespace' => 'App\Http\Controllers\Api',
    'middleware' =>  ['serializer:array', 'bindings'],
], function ($api) {

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.sign.limit'),
        'expires' => config('api.rate_limits.sign.expires'),
    ], function ($api) {
        // 图片验证码
        $api->post('captchas', 'CaptchasController@store')
            ->name('api.captchas.store');
        // 短信验证码
        $api->post('verificationCodes', 'VerificationCodesController@store')
            ->name('api.verifications.store');
        // 用户注册
        $api->post('users', 'UsersController@store')
            ->name('api.users.store');
        // 第三方登录
        $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
            ->name('api.socialStore');
        // 登录
        $api->post('authorizations', 'AuthorizationsController@store')
            ->name('api.authorizations.store');
        // 刷新 token
        $api->put('authorizations/current', 'AuthorizationsController@update')
            ->name('api.authorizations.update');
        $api->delete('authorizations/current', 'AuthorizationsController@destroy')
            ->name('api.authorizations.destroy');
    });

    $api->group([
        'middleware' => 'api.throttle',
        'limit' => config('api.rate_limits.access.limit'),
        'expires' => config('api.rate_limits.access.expires'),
    ], function ($api) {
        // 游客可以访问的接口
        $api->get('categories', 'CategoriesController@index')
            ->name('api.categories.index');
        $api->get('topics', 'TopicsController@index')
            ->name('api.topics.index');
        $api->get('users/{user}/topics', 'TopicsController@userIndex')
            ->name('api.users.topics.userIndex');
        $api->get('topics/{topic}', 'TopicsController@show')
            ->name('api.topics.show');
        $api->get('topics/{topic}/replies', 'RepliesController@index')
            ->name('api.replies.index');
        $api->get('users/{user}/replies', 'RepliesController@userIndex')
            ->name('api.replies.userIndex');

        // 需要 token 访问的接口
        $api->group(['middleware' => 'api.auth'], function ($api) {
            // 当前登录用户信息
            $api->get('user', 'UsersController@me')
                ->name('api.user.me');
            // 编辑登录用户信息
            $api->patch('user', 'UsersController@update')
                ->name('api.user.update');
            // 图片资源
            $api->post('images', 'ImagesController@store')
                ->name('api.images.store');
            // 发布话题
            $api->post('topics', 'TopicsController@store')
                ->name('api.topics.store');
            $api->patch('topics/{topic}', 'TopicsController@update')
                ->name('api.topics.update');
            $api->delete('topics/{topic}', 'TopicsController@destroy')
                ->name('api.topics.destroy');
            $api->post('topics/{topic}/replies', 'RepliesController@store')
                ->name('api.topics.replies');
            $api->delete('topics/{topic}/replies/{reply}', 'RepliesController@destroy')
                ->name('api.topics.destroy');
            // 通知列表
            $api->get('user/notifications', 'NotificationsController@index')
                ->name('api.user.notifications.index');
        });
    });

});