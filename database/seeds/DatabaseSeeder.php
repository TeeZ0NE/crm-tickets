<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
	    $this->call(UsersTableSeeder::class);
	    $this->call(ServicesTableSeeder::class);
	    $this->call(DeadlineSeeder::class);
	    $this->call(PrioritiesSeeder::class);
	    $this->call(StatusesSeeder::class);
	    $this->call(IntervalsSeeder::class);
	    $this->call(EmailSeeder::class);
	    $this->call(AdminNikSeeder::class);
    }
}
