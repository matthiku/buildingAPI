<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class OAuthClientSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		// $this->call('UserTableSeeder');
		
		DB::table('oauth_clients')->insert([
				'id'     => 'editor',
				'secret' => str_random(8),
				'name'   => 'Normal User'
			]);
		DB::table('oauth_clients')->insert([
				'id'     => 'admin',
				'secret' => str_random(8),
				'name'   => 'Administrator'
			]);
	}

}
