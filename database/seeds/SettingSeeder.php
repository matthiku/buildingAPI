<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SettingSeeder extends Seeder {

	/**
	 * Run the database seeds
	 *
	 * use: php artisan db:seed --class=SettingseSeeder
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$settingsList = [
			'heating'            => 'AUTO',
			'autoOnBelow'        =>  13,
			'autoOffAbove'       =>  25,
			'increasePerHour'    => '2.5' ,
			'TFHostAddr '        => '25.120.190.207',              
			'TFHostPort'         => '4223',
			'TFheatSwUID'        => '6D9',         
			'TFmainTempUID'      => 'bTh',
			'TFfrontRoomTempUID' => '6Jm',
			'TFauxTempUID'       => 'bSC',                           
			'TFlightUID'         => '7dw',                         
			'TFLCDUID'           => 'cYL',                        
			'reload '            =>  0,                            
			'restart '           =>  0,                            
			'wol '               =>  0,                            
			'debug '             =>  0,                            
			'silent '            =>  1,                            
			'reboot '            =>  0,                            
			'interval '          =>  5,                            
			'homepageAddr '      => 'ennisevangelicalchurch.org',    
			'tempPath '          => 'c:\\tmp\\',                  
			'sundayRecording '   => 'c:\\daten\\sunday.wma',            
		];

		foreach ($settingsList as $key => $value) {
			DB::table('settings')->insert([
				'key' => $key,
				'value' => $value
			]);
		}

	}

}
