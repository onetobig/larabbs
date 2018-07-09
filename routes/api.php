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

$api->version('v1', function ($api) {
    $api->group([
        'namespace' => 'App\Http\Controllers\Api',
        'middleware' => ['bindings', 'serializer:array']
    ], function ($api) {
        $api->group([
            'middleware' => 'api.throttle',
            'expires' => config('api.rate_limits.sign.expires'),
            'limit' => config('api.rate_limits.sign.limit'),
        ], function ($api) {
            $api->post('captchas', 'CaptchasController@store')
                ->name('captchas.store');
            $api->post('verificationCodes', 'VerificationCodesController@store')
                ->name('verificationCodes.store');
            $api->post('users', 'UsersController@store')
                ->name('users.store');
            $api->post('socials/{social_type}/authorizations', 'AuthorizationsController@socialStore')
                ->name('socials.authorizations.store');
            $api->post('authorizations', 'AuthorizationsController@store')
                ->name('api.authorizations.store');
            $api->put('authorizations/current', 'AuthorizationsController@update')
                ->name('api.authorizations.update');
            $api->delete('authorizations/current', 'AuthorizationsController@destroy')
                ->name('api.authorizations.destroy');
        });

        $api->group([
            'middleware' => 'api.throttle',
            'expires' => config('api.rate_limits.access.expires'),
            'limit' => config('api.rate_limits.access.limit'),
        ], function ($api) {
            // 游客可访问的接口

            // 需要登录才可访问的接口
            $api->group([
                'middleware' => ['api.auth']
            ], function ($api) {
                $api->get('user', 'UsersController@me')
                    ->name('user.store');
                $api->patch('user', 'UsersController@update')
                    ->name('user.update');
                $api->post('images', 'ImagesController@store')
                    ->name('images.store');

            });

        });
    });
});

