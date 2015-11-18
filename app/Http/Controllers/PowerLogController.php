<?php namespace App\Http\Controllers;

/* 

list of methods per Routing table

METHOD      URL                     CONTROLLER
-------------------------------------------------------------------------
$app->get(   '/powerlog',                 'PowerLogController@index' );
// get latest power data (no auth req'd)
$app->get(   '/powerlog/latest',          'PowerLogController@latest' );
// only with authentication
$app->post(  '/powerlog',                 'PowerLogController@store'  );

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
        $this->middleware( 'oauth', ['only' => ['store'] ] );
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
     * Get records selected by URI parameters
     * (default is last 1 hour)
     *
      *   Possible parameters:
      *   HOWMUCH : (integer) number of ...
      *   UNIT    : (string)  ... hours, days, weeks etc
      *   FROM: specific date or PHP's strtotime() syntax
      *   TO  : specific date or PHP's strtotime() syntax
      *   (specific dates must be in this format: "Y-m-d H:i:s")      
      *  example: howmuch=1, unit=days - give me the exact date/time 24 hours ago
      *       or: from=yesterday
      *       or: from=2 weeks ago
      *       or: http://buildingapi.app/templog?from=2015-11-12%2000:00:01&to=2015-11-13%2023:59:59
     */
    public function index(Request $request)
    {

        //$this->validateRequest($request);

        // create a date range based on the 'requested' arguments, 
        // defaulting to 1 hour back from now
        list($from, $to) = $this->findDateRange($request);

        $data = PowerLog::whereBetween('updated_at', [$from, $to] )->get();
        if (count($data)) {
            return $this->createSuccessResponse( $data, 200 );
        }
        return $this->createErrorResponse( "no data found for $from $to", 404 );
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
            'updated_at'=> 'unique:updated_at',
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
