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
        'middleware' => ['bindings', ]
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
        });

        $api->group([
            'middleware' => 'api.throttle',
            'expires' => config('api.rate_limits.access.expires'),
            'limit' => config('api.rate_limits.access.limit'),
        ], function ($api) {

        });
    });
});

