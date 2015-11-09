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

use App\Event;

use Laravel\Lumen\Routing\Controller as BaseController;

// methods to access the http form data
use Illuminate\Http\Request;


class EventController extends Controller
{
    
    /**
     * validate the fields received from the URL/POST methods
     */ 
    function validateRequest($request) {
        $rules = 
        [
            'seed'      => 'required|numeric',
            'title'     => 'required',
            'rooms'     => 'required',
            'start'     => 'required|time',
            'end'       => 'required|time',
            'targetTemp'=> 'required|numeric',
            'nextdate'  => 'required|date',
            'repeats'   => 'required|in:once,weekly,monthly,biweekly',
            'status'    => 'required|in:OK,DELETE,NEW,UPDATE,OLD,TAN-REQ,TANERR',
            'weekday'   => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday,Friday,Saturday'
        ];        
        /* from the migration:
            $table->string(     'ipaddr',15 ); // ipaddr or name of user
            $table->integer(    'seed'      ); // TAN
            $table->enum(       'status',   ['OK','DELETE','NEW','UPDATE','OLD','TAN-REQ','TANERR']);
            $table->enum(       'weekday',  ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday']);
            $table->time(       'start'     ); // start time of event
            $table->time(       'end'       ); // end time of event
            $table->string(     'title', 30 );
            $table->enum(       'repeats',  ['once','weekly','monthly','biweekly']);
            $table->date(       'nextdate'  ); // pre-calculated date for next occurence of this event
            $table->string(     'rooms',5   ); // list of affected rooms (numbers)
            $table->integer(    'targetTemp'); // desired room temperature for this event
        */
        // if validation fails, it will produce an error response }
        $this->validate($request, $rules);
    }




    /**
     * Show ALL events
     */
    public function index()
    {
        $events = Event::all();
        return $this->createSuccessResponse( $events, 200 );
    }


    /**
     * CREATE a new event
     */
    public function store(Request $request)
    {
        // validate form data
        $this->validateRequest($request);

        // create a new event record in the DB table and return a confirmation
        $event = Event::create( $request->all() );
        return $this->createSuccessResponse( "A new event with id {$event->id} was created", 201 );
    }



    /**
     * Show a specific event
     */
    public function show($id)
    {
        $event = Event::find($id);
        if ($event) {
            return $this->createSuccessResponse( $event, 200 );
        }
        return $this->createErrorResponse( "Event with id $id not found!", 404 );
    }


    
    /**
     * UPDATE a specific event
     */
    public function update($id)
    {
        return __METHOD__;
    }


    
    /**
     * DELETE a specific event
     */
    public function destroy($id)
    {
        return __METHOD__;
    }

}
