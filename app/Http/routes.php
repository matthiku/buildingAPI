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

// no auth req'd
$app->get(   '/events',           'EventController@index'  );
$app->get(   '/events/{event}',   'EventController@show'   );

/*/ only with authentication
$app->post(  '/events',          ['middleware' => 'auth.basic', 'EventController@store' ] );
$app->put(   '/events/{event}',  ['middleware' => 'auth.basic', 'EventController@update'] );
$app->patch( '/events/{event}',  ['middleware' => 'auth.basic', 'EventController@update'] );
$app->delete('/events/{event}',  ['middleware' => 'auth.basic', 'EventController@destroy']);

*/

$app->post('/oauth/access_token', function() {
    return 'asdf';
    return Response::json(Authorizer::issueAccessToken());
    //return response()->json($app->make('oauth2-server.authorizer')->issueAccessToken());
});

