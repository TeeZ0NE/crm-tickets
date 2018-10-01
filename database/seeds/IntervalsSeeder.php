<?php

use Illuminate\Database\Seeder;
use App\Models\Interval;

class IntervalsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $intervals = ['today'=>'За сегодня','yesterday'=>'За вчера','start_of_month'=>'С начала месяца','За прошлый месяц'=>'prev_month'];
        foreach ($intervals as $interval=>$val){
        	$interval_m = new Interval();
        	$interval_m->name = $val;
        	$interval_m->url_attr = $interval;
        	$interval_m->save();
        }
    }
}
