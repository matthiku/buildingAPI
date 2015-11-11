<?php namespace App;

use Illuminate\Database\Eloquent\Model;

/**
*
* TempLog records temperature measurements over time
*
*/
class TempLog extends Model
{
    
    function __construct(argument)
    {
        $this->middleware( 'oauth', ['except' => ['index', 'show', 'byStatus'] ] );
    }


    // fields that can be changed via the API
    protected $fillable = [ 'timestamp', 'mainroom', 'auxtemp', 'frontroom', 'heating_on', 'power', 'outdoor', 'babyroom' ];


}