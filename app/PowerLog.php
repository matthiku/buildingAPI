<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
*
* TempLog records temperature measurements over time
*
*/
class PowerLog extends Model
{
    
    function __construct()
    {
        $this->middleware( 'oauth', ['except' => ['index', 'show', 'byStatus'] ] );
    }


    // fields that can be changed via the API
    protected $fillable = [ 'updated_at', 'heating_on', 'power', 'boiler_on' ];


}