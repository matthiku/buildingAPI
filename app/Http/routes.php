<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
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
// get latest power data (no auth req'd)
$app->get(   '/powerlog/latest',          'PowerLogController@latest' );

// only with authentication
$app->post(  '/powerlog',                 'PowerLogController@store' );


/**
 * TEMPerature logging routes
 */
// get latest power data (no auth req'd)
$app->get(   '/templog/latest',          'TempLogController@latest' );

// only with authentication
$app->post(  '/templog',                 'TempLogController@store' );



/**
 * EVENT logging routes
 */
// get latest power data (no auth req'd)
$app->get(   '/eventlog/latest',          'EventLogController@latest' );

// only with authentication
$app->post(  '/eventlog',                 'EventLogController@store' );

