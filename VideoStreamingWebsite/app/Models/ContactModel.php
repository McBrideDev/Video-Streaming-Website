<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ContactModel extends Model  {

	protected $table="contact";
	protected $fillable = ['email', 'name', 'type', 'message', 'reply', 'status'];
	
}
