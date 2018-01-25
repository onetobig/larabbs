<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/23
 * Time: 14:34
 */

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}
