<?php namespace App\Http\Controllers;

/* 

list of methods as per Routing table

METHOD      URL                     CONTROLLER
-------------------------------------------------------------------------
// only with authentication
$app->get(   '/settings',                 'SettingController@index'  );
$app->get(   '/settings/{setting}',       'SettingController@show'   );
$app->post(  '/settings',                 'SettingController@store'  );
$app->put(   '/settings/{id}',            'SettingController@update' );
$app->patch( '/settings/{id}',            'SettingController@update' );
$app->delete('/settings/{id}',            'SettingController@destroy');

*/

use Laravel\Lumen\Routing\Controller as BaseController;

// 'setting' table model
use App\Setting;

// methods to access the http form data
use Illuminate\Http\Request;


class SettingController extends Controller
{



    // use OAuth in ALL methods!
    public function __construct()
    {
        $this->middleware( 'oauth' );
    }





    /**
     *
     * Get ALL settings
     *
     */
    public function index()
    {
        $data = Setting::all();
        return $this->createSuccessResponse( $data, 200 );
    }





    /**
     *
     * Get a specific setting by 'key'
     *
     */
    public function show($key)
    {        
        $setting = Setting::where('key', $key)->get();
        if ($setting) {
            return $this->createSuccessResponse( $setting, 200 );
        }
        return $this->createErrorResponse( "Setting with key $key not found!", 404 );
    }





    /**
     *
     * CREATE a new setting
     *
     */
    public function store(Request $request)
    {
        // validate form data
        $this->validateRequest($request);

        // create a new setting record in the DB table and return a confirmation
        $setting = Setting::create( $request->all() );
        return $this->createSuccessResponse( "A new setting with id {$setting->id} was created", 201 );
    }





    
    /**
     *
     * UPDATE a specific setting
     *
     */
    public function update(Request $request, $id)
    {
        $setting = Setting::find( $id );

        if ($setting) {
            
            // validate form data
            $this->validateRequest($request);

            // modify each field
            $setting->key   = $request->key; 
            $setting->value = $request->value; 
            if ( $request->has('note') ) {
                $setting->note  = $request->note; 
            }
            
            // update setting record in the DB table and return a confirmation
            $setting->save();

            return $this->createSuccessResponse( "The setting with id {$setting->id} was updated", 202 );
        }

        return $this->createErrorResponse( "Setting with id $id not found!", 404 );
    }




    
    /**
     *
     * DELETE a specific setting
     *
     * We cannot actually delete records but mark them with status=OLD
     * because we (might) have linked records in the settingLogs table
     */
    public function destroy($id)
    {
        $setting = Setting::find($id);

        if ($setting) {
            $setting->delete();
            return $this->createSuccessResponse( "The setting with id $id was deleted.", 200 );
        }

        return $this->createErrorResponse( "Setting with id $id not found!", 404 );
    }




    
    /**
     *
     * validate the fields received from the URL/POST methods
     *
     */ 
    function validateRequest($request) {

        $rules = 
        [
            'key'       => 'required|max:25',
            'value'     => 'required|max:155',
            'note'      => 'max:255',
        ];        
        /* from the migration:
            $table->increments('id');
            $table->char('key',   25);
            $table->char('value',155);
            $table->char('note', 255);
            $table->timestamps();
        */
        // if validation fails, it will produce an error response }
        $this->validate($request, $rules);

    }



}
