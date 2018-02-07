<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CountReportModel extends Model  {

	protected $table="count_report";
	protected $fillable = ['report_status', 'count_report'];

}
