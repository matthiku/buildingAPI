<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
* 
*/
class Event extends Model
{
    


    // fields that cannot be changed via the API
    protected $hidden = [ 'seed', 'ipaddr', 'created_at' ];

    // fields that can be changed via the API
    protected $fillable = [
        'id', 'status', 'weekday', 'start', 'end', 'updated_at', 
        'title', 'repeats', 'nextdate', 'rooms', 'targetTemp' 
    ];



    // define relationship
    public function eventLogs() 
    {
        return $this->hasMany('App\EventLog');
    }

}