<?php

use Illuminate\Database\Seeder;
use App\Models\Deadline;

class DeadlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $values=["002000","004000","010000"];
        foreach ($values as $value){
        	$deadline_m = new Deadline();
        	$deadline_m->deadline = $value;
        	$deadline_m->save();
        }
    }
}
