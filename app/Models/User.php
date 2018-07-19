<?php

namespace App\Models;
use Illuminate\Contracts\Auth\{Authenticatable, CanResetPassword as CanResetPasswordContract};
use Illuminate\Auth\{Authenticatable as AuthenticableTrait, Passwords\CanResetPassword};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model implements Authenticatable,CanResetPasswordContract
{
	use AuthenticableTrait, CanResetPassword, Notifiable ;
	protected $fillable = ['name','email','password',];
	protected  $hidden = ['password', 'remember_token',];
}
