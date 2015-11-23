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
        // in the context of logfiles, finding no data for a certain timespan is no error, so we will always return with code 200
        return $this->createErrorResponse( "no data found for $from $to", 200 );
    }






    /**
     *
     * CREATE a new record
     *
     */
    public function store(Request $request)
    {

        // if a record with the same "updated_at" value already exists, 
        // we need to find out if we can log that previous one under an older timestamp 
        $updated_at = $request->updated_at;
        // check if the current timestamp already exists
        $count = PowerLog::where('updated_at', $updated_at )->count();
        if ($count) {
            // since this record already exists, we check if a record of the previous second also exists
            // first, subtrace one second from our timestamp
            $updated_at = $this->getAnotherTimestamp($updated_at,-1);
            // now check if there is a record with that older timestamp
            $prevData = PowerLog::where('updated_at', $updated_at )->get();
            if (! count($prevData)) {
                // no older timestamp exists, so we can
                // push the last record one second back
                $oldData = PowerLog::where( 'updated_at', $request->updated_at )
                                  ->update(['updated_at' => $updated_at]);
            }
        }

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
            'updated_at'=> 'unique:power_logs',
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


    // add or subtract a second interval from a mysql timestamp string (yyyy-mm-dd hh:mm:ss)
    function getAnotherTimestamp( $updated_at, $diff ) {
        $date = date_create( $updated_at );
        date_add($date, date_interval_create_from_date_string($diff . "seconds" ));
        return strftime( "%Y-%m-%d %H:%M:%S", date_timestamp_get($date) );
    }


}
