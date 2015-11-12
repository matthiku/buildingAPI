<?php namespace App\Http\Controllers;

/* 

list of methods per Routing table

METHOD      URL                     CONTROLLER
-------------------------------------------------------------------------
$app->get(   '/eventlog/latest',          'EventLogController@latest' );
// only with authentication
$app->post(  '/eventlog',                 'EventLogController@store' );

*/

use Laravel\Lumen\Routing\Controller as BaseController;

// 'event' table model
use App\EventLog;

// methods to access the http form data
use Illuminate\Http\Request;


class EventLogController extends Controller
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
        $data = EventLog::orderBy('updated_at', 'DESC')->first();
        return $this->createSuccessResponse( $data, 200 );
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
        $data = EventLog::create( $request->all() );
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
            'event_id'   => 'required|numeric|exists:events,id',
            'eventStart' => 'required|date_format:"G:i"',
            'estimateOn' => 'date_format:"G:i"',
            'actualOn'   => 'date_format:"G:i"',
            'actualOff'  => 'date_format:"G:i"',
        ];        
        /* from the migration:
            $table->integer(  'event_id'  );
            $table->time(     'eventStart');
            $table->time(     'estimateOn');
            $table->time(     'actualOn'  );
            $table->time(     'actualOff' );
        */
        // if validation fails, it will produce an error response }
        $this->validate($request, $rules);

    }



}
