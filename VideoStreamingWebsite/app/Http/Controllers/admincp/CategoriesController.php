<?php

namespace App\Http\Controllers\admincp;
use App\Models\CategoriesModel;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Models\CountriesModel;
use App\Models\VideoCatModel;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
class CategoriesController extends Controller
{
	public function get_categories(){
		$categories = CategoriesModel::OrderBy('created_at','DESC')->get();
		return view('admincp.categories.index')->with('categories',$categories);
	}

	public function get_addcategories(){
		$get_country=CountriesModel::get();
		return view('admincp.categories.add')->with('country',$get_country);
	}
	public function post_addcategories(Request $get){

		$get_country=CountriesModel::get();

		$rules = [
			'title_name' => 'required|min:3',
			'poster' => 'required|image|mimes:jpeg,png,jpg'
		];

		$validator = Validator::make($get->all(), $rules);
		if($validator->fails()){
			return back()->withErrors($validator)->withInput();
		}
		$addcategories = new CategoriesModel();
		$addcategories->title_name= $get->title_name;
		$addcategories->post_name= str_slug($get->title_name,"-");
		$addcategories->status= $get->status;

		$file = Input::File('poster');

		$extension =$file->getClientOriginalExtension();
		$destinationPath = public_path()."/upload/categories/";
		$filename = "Categories_Poster_".str_slug($_POST["title_name"],'_').".".$extension;
		$file->move($destinationPath, $filename);
		$addcategories->poster =$filename;

		if($addcategories->save()){
			return redirect('admincp/categories')->with('msg','Add Categories Successfully !');
		}
		return redirect('admincp/add-categories')->with('msgerro','Add Categories Field !');

	}
	public function get_editcategories($id){
		$editcategories = CategoriesModel::find ($id);
		if($editcategories){
			return view('admincp.categories.edit')->with('editcategories',$editcategories);
		}
	}

	public function post_editcategories(Request $get, $id){
		$editcategories =CategoriesModel::where('id','=',$id)->update([
				'title_name' => $get->title_name,
				'post_name'  => str_slug($get->title_name,"-"),
				'recomment' => isset($get->recomment) ? CategoriesModel::RECOMMENT_ACTIVE : CategoriesModel::RECOMMENT_INACTIVE,
				'status' => $get->status
				]);
		$file = Input::File('poster');

		if($file){
			$extension =$file->getClientOriginalExtension();

			$notAllowed = array("exe","php","asp","pl","bat","js","jsp","sh","doc","docx","xls","xlsx");

			$destinationPath = public_path()."/upload/categories/";

			$filename = "Categories_Poster_".str_slug($_POST["title_name"],'_').".".$extension;

			if(!in_array($extension,$notAllowed))
			{
				$file->move($destinationPath, $filename);
				$update_editcategories = CategoriesModel::find($id)->update(array('poster'=>$filename));

			}else{
				return redirect('admincp/edit-categories/'.$id.'')->with('msgerro','File type not allowed!');
			}
		}
		if($editcategories){
			return redirect('admincp/categories')->with('msg','Update Successfully !');
		}
		return redirect('admincp/edit-categories/'.$id.'')->with('msgerro','Update field !');
	}

	public function get_deletecategories($id){
		$deletecategories = CategoriesModel::find($id);
		if(!empty($deletecategories)){
			if(!empty($deletecategories->poster)){
				if(File::exists(public_path() .'/upload/categories/' .$deletecategories->poster)){
					unlink(public_path() .'/upload/categories/' .$deletecategories->poster);
				}
			}
			VideoCatModel::where('cat_id', '=', $deletecategories->id)->delete();
			if($deletecategories->delete()) {
				return redirect('admincp/categories')->with('msg','Deleted Successfully !');
			}
			return redirect('admincp/categories')->with('msgerro','Can not delete this categories');
		}
		return redirect('admincp/categories')->with('msgerro','Categories not found.');
	}

	public function post_list_category(Request $post) {
		$start = $post->start;
		$length = $post->length;
		$col = $post->columns;
		$order = $post['order'][0];
		$orderby = $col[$order['column']]['data'];
		$orderby = $orderby==1?"title_name":$orderby;
		$sortasc = $order['dir'];
		$criteria = "1=1";
		if($col[1]['search']['value']!="") {
			$keyword = $col[1]['search']['value'];
			$criteria = "table_categories.title_name LIKE '%".$keyword."%'";
		}
		$recordsTotal = CategoriesModel::count();
		$recordsFiltered = CategoriesModel::select('id')
						->whereRaw($criteria)
						->count();
		$get_list = CategoriesModel::select('categories.*')
						->whereRaw($criteria)
						->orderBy($orderby, $sortasc)
						->limit($length)->offset($start)
						->get();

		$result = array(
			'data' => $get_list,
			'draw' => $post['draw'],
			'recordsTotal' => $recordsTotal,
			'recordsFiltered' => $recordsFiltered
		);

		return \Response::json($result);
	}

	public function delete_all_category($string_id) {
		$get = CategoriesModel::whereRaw('id IN(' . $string_id . ')')->get();
		$deletedCategories = true;
		if ($get) {
			foreach ($get as $key => $value) {
				if(!empty($value->poster)){
					if(File::exists(public_path() .'/upload/categories/' .$value->poster)){
						unlink(public_path() .'/upload/categories/' .$value->poster);
					}
				}
				VideoCatModel::where('cat_id', '=', $value->id)->delete();
				$checker = $value->delete();
				if (!$checker)
					$deletedCategories = false;
			}
			if ($deletedCategories) {
				return redirect('admincp/categories')->with('msg', 'Deleted categories successfully!');
			} else {
				return redirect('admincp/categories')->with('msgerror', 'Deleted categories unsuccessfully!');
			}
		}
		return redirect('admincp/categories')->with('msgerror', 'Request not found !');
	}

}

