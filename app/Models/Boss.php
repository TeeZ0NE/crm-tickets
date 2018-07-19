<?php
namespace App\Models;
use App\Notifications\BossResetPaswdNotification;
use Illuminate\Contracts\Auth\{Authenticatable, CanResetPassword as CanResetPasswordContract};
use Illuminate\Auth\{Authenticatable as AuthenticableTrait, Passwords\CanResetPassword};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Boss extends Model implements Authenticatable,CanResetPasswordContract
{
	use AuthenticableTrait, CanResetPassword, Notifiable ;
	protected $fillable = ['name','email','password',];
	protected  $hidden = ['password', 'remember_token',];
	protected $guard = 'boss';
	/**
	 * Send the password reset notification.
	 *
	 * @param  string  $token
	 * @return void
	 */
	public function sendPasswordResetNotification($token)
	{
		$this->notify(new BossResetPaswdNotification($token));
	}
}
