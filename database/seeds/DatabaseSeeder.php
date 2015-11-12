<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		$this->call('OAuthClientSeeder');

		/* this was a one-off seeder ...*/
		DB::unprepared(
			"INSERT INTO `events` ( `created_at`, `ipaddr`, `status`, `seed`, `weekday`, `start`, `end`, `title`, `repeats`, `nextdate`, `rooms`, `targetTemp`) VALUES
				( '2015-07-28 22:05:05', 'Matthias', 'OK', 35130, 'Tuesday', '20:00:00', '21:45:00', 'Tuesday Night Service', 'weekly', '2015-08-04', '2', 21),
				( '2015-07-26 13:35:04', 'matthias', 'OK', 48901, 'Sunday', '09:45:00', '13:15:00', 'Sunday Morning Service', 'weekly', '2015-08-02', '1,2', 21),
				( '2015-07-30 10:30:08', 'matthias', 'OK', 51435, 'Saturday', '10:00:00', '10:15:00', 'Mens Meeting', 'monthly', '2015-08-30', '2', 20),
				( '2015-05-18 20:10:09', 'matthias', 'OK', 70850, 'Monday', '19:30:00', '19:55:00', 'Elders Meeting', 'biweekly', '2015-06-01', '2', 21),
				( '2015-05-27 08:50:14', 'matthias', 'OK', 75847, 'Wednesday', '19:00:00', '21:00:00', 'Ladies Crafts', 'once', '2015-05-27', '2', 22),
				( '2015-06-05 11:30:05', 'matthias', 'OK', 97893, 'Monday', '10:00:00', '11:40:00', 'Ladies Prayer meeting', 'monthly', '2015-06-08', '2', 22);"
		);
	}

}
