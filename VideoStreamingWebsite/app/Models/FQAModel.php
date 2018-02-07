<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class FQAModel extends Model  {

	protected $table="fqa";
	public static function get_list(){
		$list=FQAModel::where('status','=',1)->paginate(perPage());
		$html="";
		$html.='<div id="faqs">';
		foreach ($list as $result) {
			$html.='<h3><i class="fa fa-hand-o-right"></i> '.$result->question.'</h3>
			<div>
				<p>'.$result->answer.'</p>
			</div>';
		}
		$html.='</div>';
		return $html;
	}
}
