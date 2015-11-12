<?php namespace App\Http\Controllers;

/* 

list of methods per Routing table

METHOD      URL                     CONTROLLER
-------------------------------------------------------------------------
// get latest power data (no auth req'd)
$app->get(   '/templog/latest',          'TempLogController@latest' );
// only with authentication
$app->post(  '/templog',                 'TempLogController@store' );

*/

use Laravel\Lumen\Routing\Controller as BaseController;

// 'event' table model
use App\PowerLog;

// methods to access the http form data
use Illuminate\Http\Request;


class PowerLogController extends Controller
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
        $events = PowerLog::orderBy('updated_at', 'DESC')->first();
        return $this->createSuccessResponse( $events, 200 );
    }





    /**
     *
     * CREATE a new record
     *
     */
    public function store(Request $request)
    {
        // validate form data
        $this->validateRequest($request);

        // create a new event record in the DB table and return a confirmation
        $data = PowerLog::create( $request->all() );
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
            'power'     => 'required|numeric',
            'heating_on'=> 'required|boolean',
            'boiler_on' => 'required|boolean',
        ];        
        /* from the migration:
            $table->timestamp('updated_at');
            $table->integer(  'power'     );
            $table->boolean(  'heating_on');
            $table->boolean(  'boiler_on' );
        */
        // if validation fails, it will produce an error response }
        $this->validate($request, $rules);

    }



}
