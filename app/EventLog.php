<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
* table to record event-driven activities 
*
* (formerly named 'heating_logs')
*/
class EventLog extends Model
{
    
    function __construct(argument)
    {
        # code...
    }


    // fields that cannot be changed via the API
    protected $hidden = [ 'timestamp' ];

    // fields that can be changed via the API
    protected $fillable = [ 'event_id', 'eventStart', 'estimateOn', 'actualOn', 'actualOff' ];


    // define relationship
    public function event() 
    {
        return $this->belongsTo('App\Event');
    }

}