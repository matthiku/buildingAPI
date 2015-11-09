<?php namespace App\Http\Controllers;

/*

METHOD      URL                     CONTROLLER
---------------------------------------------------
GET         /events                 EventController@index
POST        /events                 EventController@store  (+ form data)
GET         /events/{event}         EventController@show
PUT         /events/{event}         EventController@update (+ form data)
PATCH       /events/{event}         EventController@update (+ form data)
DELETE      /events/{event}         EventController@destroy

*/


use Laravel\Lumen\Routing\Controller as BaseController;

class Controller extends BaseController
{



    //generic method to return data
    public function createSuccessResponse($data, $code)
    {
        return response()->json(['data' => $data], $code);
    }



    //generic method to return error message
    public function createErrorResponse($message, $code)
    {
        return response()->json(['message' => $message, 'code' => $code], $code);
    }



}
