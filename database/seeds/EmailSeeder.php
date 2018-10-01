<?php

use Illuminate\Database\Seeder;
use App\Models\Email;
class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $emails = ['vadim@hyperweb.com.ua','sergiy.petruk@secom.com.ua'];
        foreach ($emails as $email){
        	$email_m = new Email();
        	$email_m->email = $email;
        	$email_m->save();
        }
    }
}
