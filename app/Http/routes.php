<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/


$app->get('/', function() {
    return view('home', []);
});


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
// set nextdate of a certain event (once an event is over)
$app->patch( '/events/{event}/nextdate/{date}', 'EventController@updateNextdate');
$app->patch( '/events/{event}/status/{status}', 'EventController@updateStatus'  );



/**
 * SETTINGS table management routes
 */
// only with authentication
$app->get(   '/settings',                 'SettingController@index'  );
$app->get(   '/settings/{setting}',       'SettingController@show'   );
$app->post(  '/settings',                 'SettingController@store'  );
$app->put(   '/settings/{id}',            'SettingController@update' );
$app->patch( '/settings/{id}',            'SettingController@update' );
$app->delete('/settings/{id}',            'SettingController@destroy');




/**
 * POWER logging routes
 */
// get latest power data (no auth req'd)
$app->get(   '/powerlog/latest',          'PowerLogController@latest' );

// only with authentication
$app->post(  '/powerlog',                 'PowerLogController@store'  );


/**
 * TEMPerature logging routes
 */
// get latest power data (no auth req'd)
$app->get(   '/templog/latest',          'TempLogController@latest' );

// only with authentication
$app->post(  '/templog',                 'TempLogController@store'  );



/**
 * EVENT logging routes
 */
// get latest power data (no auth req'd)
$app->get(   '/eventlog/latest',          'EventLogController@latest' );

// only with authentication
$app->post(  '/eventlog',                 'EventLogController@store'  );



/**
 * BUILDING logging routes
 */
// get latest Building data (no auth req'd)
$app->get(   '/buildinglog/latest',          'BuildingLogController@latest');
// only with authentication
$app->post(  '/buildinglog',                 'BuildingLogController@store' );


