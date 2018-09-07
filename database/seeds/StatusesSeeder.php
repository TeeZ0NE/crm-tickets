<?php

use Illuminate\Database\Seeder;
use App\Models\Status;

class StatusesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $status_m = new Status;
        $status_m->name = 'in progress';
        $status_m->save();
    }
}
