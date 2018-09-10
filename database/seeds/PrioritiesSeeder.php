<?php

use Illuminate\Database\Seeder;
use App\Models\Priority;

class PrioritiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priority_m = new Priority;
        $priority_m->priority = 'n\a';
        $priority_m->save();
    }
}
