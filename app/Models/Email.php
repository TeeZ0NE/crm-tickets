<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Log;

class Email extends Model
{
	public $timestamps = False;
	protected $fillable = ['email'];

	public function setEmailAttribute($value)
	{
		return $this->attributes['email'] = strtolower($value);
	}

	/**
	 * Get list of emails
	 *
	 * @return \Illuminate\Database\Eloquent\Collection
	 */
	public function getAllEmails()
	{
		return $this::all()->sortBy('email');
	}

	public function getEmailsFromId(array $email_ids)
	{
		$mail_list = [];
		foreach ($email_ids as $email_id) {
			try {
				array_push($mail_list, $this->findOrFail($email_id)->email);
			} catch (ModelNotFoundException $mnf) {
				Log::error(sprintf('Email id %d not found', $email_id));
			}
		}
		return $mail_list;
	}

	public function updateEmail(int $id, string $email)
	{
		return $this->find($id)->update(['email'=>$email]);
	}

	public function destroyEmail(int $id)
	{
		return $this->find($id)->delete();
	}

	public function createNewRecord(string $email)
	{
		$created = $this->fill(['email'=>$email]);
		$created->save();
		return $created->id;
	}
}
