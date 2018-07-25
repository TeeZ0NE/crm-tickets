<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class UserAccess extends Mailable
{
    use Queueable, SerializesModels;
	private $name;
	private $email;
	private  $password;
    /**
     * Create a new message instance.
     *
     * @param string $name user name
     * @param  string $email
     * @param string $password
     * @return void
     */
	public function __construct($name, $email, $password)
	{
		$this->name = $name;
		$this->email = $email;
		$this->password = $password;
	}

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
	   return $this->markdown('emails.userAccess')->with([
	        'name'=>$this->name,
	        'email'=>$this->email,
	        'password'=>$this->password,
	        'url'=>route('home'),
        ]);
    }
}
