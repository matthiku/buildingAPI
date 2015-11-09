<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

$app->get('/', function() use ($app) {
    return $app->welcome();
});

$app->get(   '/events',           'EventController@index'  );
$app->post(  '/events',           'EventController@store'  );
$app->get(   '/events/{event}',   'EventController@show'   );
$app->put(   '/events/{event}',   'EventController@update' );
$app->patch( '/events/{event}',   'EventController@update' );
$app->delete('/events/{event}',   'EventController@destroy');

