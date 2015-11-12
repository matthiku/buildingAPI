<?php namespace App\Http\Controllers;

/* 

list of methods per Routing table

METHOD      URL                     CONTROLLER
-------------------------------------------------------------------------
$app->get(   '/templog/latest',          'TempLogController@latest' );
// only with authentication
$app->post(  '/templog',                 'TempLogController@store' );

*/

use Laravel\Lumen\Routing\Controller as BaseController;

// 'event' table model
use App\TempLog;

// methods to access the http form data
use Illuminate\Http\Request;


class TempLogController extends Controller
{



    // use OAuth in all methods 'latest'
    public function __construct()
    {
        $this->middleware( 'oauth', ['except' => ['latest'] ] );
    }





    /**
     *
     * Show LATEST record
     *
     */
    public function latest()
    {
        $data = TempLog::orderBy('updated_at', 'DESC')->first();
        return $this->createSuccessResponse( $data, 200 );
    }





    /**
     *
     * CREATE a new event
     *
     */
    public function store(Request $request)
    {
        // validate form data
        $this->validateRequest($request);
        // create a new event record in the DB table and return a confirmation
        $data = TempLog::create( $request->all() );
        return $this->createSuccessResponse( ["A new log record was created: ", $data->toJson()], 201 );
    }






    
    /**
     *
     * validate the fields received from the URL/POST methods
     *
     */ 
    function validateRequest($request) {

        $rules = 
        [
            'mainroom'   => 'numeric',
            'auxtemp'    => 'numeric',
            'frontroom'  => 'numeric',
            'heating_on' => 'numeric',
            'power'      => 'numeric',
            'outdoor'    => 'numeric',
            'babyroom'   => 'numeric',
        ];        
        /* from the migration:
            $table->decimal(  'mainroom',  2,2);
            $table->decimal(  'auxtemp',   2,2);
            $table->decimal(  'frontroom', 2,2);
            $table->decimal(  'heating_on',2,2);
            $table->integer(  'power'         );
            $table->decimal(  'outdoor',   2,2);
            $table->decimal(  'babyroom',  2,2);
        */
        // if validation fails, it will produce an error response }
        $this->validate($request, $rules);

    }



}
