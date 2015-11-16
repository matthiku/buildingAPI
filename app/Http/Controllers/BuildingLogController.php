<?php namespace App\Http\Controllers;

/* 

list of methods per Routing table

METHOD         URL                              CONTROLLER
-------------------------------------------------------------------------
// get latest Building data (no auth req'd)
$app->get(   '/buildinglog/latest',          'BuildingLogController@latest' );
// only with authentication
$app->post(  '/buildinglog',                 'BuildingLogController@store' );

*/

use Laravel\Lumen\Routing\Controller as BaseController;

// 'event' table model
use App\BuildingLog;

// methods to access the http form data
use Illuminate\Http\Request;


class BuildingLogController extends Controller
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
        $events = BuildingLog::orderBy('updated_at', 'DESC')->first();
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
        $data = BuildingLog::create( $request->all() );
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
            'what'  => 'required',
            'where' => 'required',
            'text'  => 'required',
        ];        
        /* from the migration:
            $table->timestamp('updated_at');
            $table->char('what', 25);
            $table->char('where',55);
            $table->char('text', 255);
        */
        // if validation fails, it will produce an error response }
        $this->validate($request, $rules);

    }



}
