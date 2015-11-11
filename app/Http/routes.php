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



/**
 * Requesting an access token for OAuth2
 */
$app->post('/oauth/access_token', function() use ($app) {
    // as per Wiki, but produces error:
    //return Response::json(Authorizer::issueAccessToken());
    // as per Udemy course:
    return response()->json($app->make('oauth2-server.authorizer')->issueAccessToken());
});



/**
 * EVENTS table management routes
 */

// no auth req'd
$app->get(   '/events',                 'EventController@index'   );
$app->get(   '/events/{event}',         'EventController@show'    );
$app->get(   '/events/status/{status}', 'EventController@byStatus');

// only with authentication
$app->post(  '/events',                 'EventController@store'  );
$app->put(   '/events/{event}',         'EventController@update' );
$app->patch( '/events/{event}',         'EventController@update' );
$app->delete('/events/{event}',         'EventController@destroy');




/**
 * POWER logging routes
 */
// no auth req'd
// get latest power data
$app->get(   '/powerlog/latest',          'PowerLogController@latest' );

// only with authentication
$app->post(  '/powerlog',                 'PowerLogController@store' );

