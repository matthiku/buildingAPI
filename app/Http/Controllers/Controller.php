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

// methods to access the http form data
use Illuminate\Http\Request;


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



    /**
     * Create the response for when a request fails validation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array  $errors
     * @return \Illuminate\Http\Response
     */
    protected function buildFailedValidationResponse(Request $request, array $errors)
    {
        // we use our own method to return teh validation error in JSON format
        return $this->createErrorResponse($errors, 422);
        
        /* the following was in the original code and needs to be ignored since 
           we always want to return a JSON and never redirect!
        return redirect()->to($this->getRedirectUrl())
                        ->withInput($request->input())
                        ->withErrors($errors, $this->errorBag());
        */
    }



}
