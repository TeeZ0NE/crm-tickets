<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailList extends Model
{
    public $timestamps = false;
    protected $fillable = ['mailable_id','email_id'];

	public function storeNewEmails(int $mailable_id, int $email_id)
	{
		return $this->insertGetId(['mailable_id'=>$mailable_id,'email_id'=>$email_id]);
    }

	/**
	 * Getting array of email ids which are in list
	 *
	 * @param int $mailable_id
	 * @return array
	 */
	public function getEmailsWithMailable(int $mailable_id):array
	{
		return $this->where('mailable_id',$mailable_id)->get()->pluck('email_id')->toArray();
    }

	/**
	 * Deleting emails id from list
	 *
	 * @param int $mailable_id
	 */
	public function deleteEmailsId4mailable(int $mailable_id):void
	{
		$this->where('mailable_id',$mailable_id)->delete();
    }
}
