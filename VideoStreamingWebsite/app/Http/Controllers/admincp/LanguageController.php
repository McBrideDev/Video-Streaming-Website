<?php

namespace App\Http\Controllers\admincp;

use Illuminate\Http\Request;
use App\Models\LanguageSettingsModel;
use App\Models\LanguageModel;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Lang;
use Mcamara\LaravelLocalization\LaravelLocalization;

class LanguageController extends Controller
{
	public function getlanguageSetting(){
		$l_setting = LanguageSettingsModel::first();
		$languageList = LanguageModel::all();
		return view('admincp.language.settings', compact('l_setting', 'languageList'));
	}

	public function postLanguageSetting(Request $get){
		$setting = LanguageSettingsModel::find($get->id);
		if(empty($setting)){
			$setting =LanguageSettingsModel::create($get->all());
			if(!$setting->id){
				return back()->with('msgerror', 'Create failed. Please try again.');
			}
			return back()->with('msg', 'Update successfully');
		}
		$setting->fill($get->all());
		if($setting->save()){
			return back()->with('msg', 'Update successfully');
		}
		return back()->with('msgerror', 'Create failed. Please try again.');

	}

	/**
	* [getAddLanguage description]
	* @return [type] [description]
	*/
	public function getAddLanguage(){
		return view('admincp.language.language_add');
	}
	/**
	* [postAddLanguage description]
	* @return [type] [description]
	*/
	public function postAddLanguage(Request $get){
		$laravelLocation = new LaravelLocalization();
		$languageCodeAvailable = array_keys($laravelLocation->getSupportedLocales());
		$input = $get->all();

		if(isset($input['languageCode'])) {
			$input['languageCode'] = strtolower($input['languageCode']);
		}

		$rules = [
			'languageName' => 'required|max:40|min:2',
			'languageCode' => 'required|max:5|min:2|unique:language|in:'.implode(',',$languageCodeAvailable),
		];
		$validator = Validator::make($input, $rules);
		if($validator->fails()){
			return back()->withErrors($validator)->withInput() ;
		}
		$addNew = new  LanguageModel;
		$addNew->languageName = $input['languageName'];
		$addNew->languageCode = $input['languageCode'];
		$addNew->status = $input['status'];
		if(is_dir(base_path().'/resources/lang/'.$addNew->languageCode)){
			return back()->with('msgerror', 'folder already exist.');
		}
		$makeDir = mkdir(base_path().'/resources/lang/'.$addNew->languageCode, 0777, true);
		// Copy validator file
		$copyPaginationValidator = copy(base_path().'/resources/lang/en/pagination.php', base_path().'/resources/lang/'.$input['languageCode'].'/pagination.php');
		$copyAuthValidator = copy(base_path().'/resources/lang/en/auth.php', base_path().'/resources/lang/'.$input['languageCode'].'/auth.php');
		$copyPasswordsValidator = copy(base_path().'/resources/lang/en/passwords.php', base_path().'/resources/lang/'.$input['languageCode'].'/passwords.php');
		$copyValidation = copy(base_path().'/resources/lang/en/validation.php', base_path().'/resources/lang/'.$input['languageCode'].'/validation.php');
		$copyBaseLanguage = copy(base_path().'/resources/lang/en/base.php', base_path().'/resources/lang/'.$input['languageCode'].'/home.php');

		if($addNew->save() && $makeDir && $copyPaginationValidator && $copyAuthValidator && $copyPasswordsValidator && $copyValidation && $copyBaseLanguage){
			return redirect('admincp/language/all')->with('msg', 'Add successfully');
		}
		return back()->with('msgerror', 'Can not create language folder. Please check your permission');
	}

	public function getAllLanguage(){
		$languageList = LanguageModel::all();
		return view('admincp.language.language_all', compact('languageList'));
	}

	/**
	* [getEditLanguage description]
	* @return [type] [description]
	*/
	public function getEditLanguage($id){
		$language = LanguageModel::find($id);
		if(empty($language)){
			return back()->with('msgerror', 'Request not found');
		}
		return view('admincp.language.language_edit', compact('language'));
	}

	/**
	* [getUpdateLanguage description]
	* @return [type] [description]
	*/
	public function getUpdateLanguage(Request $get, $id){
		$laravelLocation = new LaravelLocalization();
		$languageCodeAvailable = array_keys($laravelLocation->getSupportedLocales());
		$input = $get->all();

		if(isset($input['languageCode'])) {
			$input['languageCode'] = strtolower($input['languageCode']);
		}
		$rules = [
			'languageName' => 'required|max:40|min:2',
			'languageCode' => 'required|max:5|min:2|in:'.implode(',',$languageCodeAvailable),
		];

		if(isset($input['languageCode'])) {
			$check = LanguageModel::where('languageCode','=', $input['languageCode'])->where('id', '!=', $id)->first();
			if(!empty($check)) {
				$rules['languageCode'] .= '|unique:language';
			}
		}

		$validator = Validator::make($input, $rules);
		if($validator->fails()){
			return back()->withErrors($validator)->withInput() ;
		}

		$language = LanguageModel::find($id);
		if(empty($language)){
			return back()->with('msgerror', 'Request not found');
		}
		if($language->languageName === $input['languageName'] && $language->languageCode === $input['languageCode'] && $language->status === $input['status']){
			return redirect('admincp/language/all')->with('msg', 'Nothing Change');
		}
		$currentLocale = $language->languageCode;
		$language->languageName = $input['languageName'];
		$language->languageCode = $input['languageCode'];
		$language->status = $input['status'];
		if($language->save()){
			if($language->languageCode !== $currentLocale){
				$renameLanguagePath = rename(base_path().'/resources/lang/'.$currentLocale, base_path().'/resources/lang/'.$language->languageCode);
			}
			return redirect('admincp/language/all')->with('msg', 'Updated successfully');
		}
		return back()->with('msgerror', 'Can not update . please try again');

	}

	/**
	* [getDeleteLanguage description]
	* @return [type] [description]
	*/
	public function getDeleteLanguage($id){

		$language = LanguageModel::find($id);

		if(empty($language)){
			return back()->with('msgerror', 'Request not found');
		}

		$dir =base_path().'/resources/lang/'.$language->languageCode;

		if(is_dir($dir)) {
			$files = array_diff(scandir($dir), array('.', '..'));
			foreach ($files as $file) {
				(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
			}
			rmdir($dir);
		}

		if($language->delete()){
			return redirect('admincp/language/all')->with('msg', 'delete successfully');
		}
		return back()->with('msgerror', 'Can not update . please try again');

	}
	/**
	* [getStranlateLanguage description]
	* @return [type] [description]
	*/
	public function getStranlateLanguage($id){
		$language = LanguageModel::find($id);
		if(empty($language)){
			return back()->with('msgerror', 'Request not found');
		}
		\App::setLocale($language->languageCode);
		if (Lang::has('home'))
		{
			$loader = app('translator')->getLoader();
			$locale = \Config::get('app.locale');
			$lines = $loader->load($locale, 'home', '*');
			return view('admincp.language.stranlate_edit', compact('lines', 'language'));
		}
	}

	/**
	* [getStranlateLanguage description]
	* @return [type] [description]
	*/
	public function addNewTranslation($id){
		$language = LanguageModel::find($id);
		if(empty($language)){
			return back()->with('msgerror', 'Request not found');
		}
		return view('admincp.language.stranlate_add', compact('language'));
	}
	/**
	* [postStranlateLanguage description]
	* @return [type] [description]
	*/
	public function postStranlateLanguage(Request $get, $id){

		$rules = [
			'dataKey' => 'required|alpha_dash',
			'dataValue' => 'required'
		];

		$validator = Validator::make($get->all(), $rules);
		if($validator->fails()){
			return response()->json([
					'code' => 422,
					'message' => $validator->errors()->all()[0],
					'status' => false
			], 422);
		}
		if (preg_match("/<script[^>]+>/i", $get->dataValue)) {
			return response()->json([
					'code' => 422,
					'message' => 'only text  format',
					'status' => false
			], 422);
		}
		if (preg_match("/<script[^>]+>/i", $get->dataKey)) {
			return response()->json([
					'code' => 422,
					'message' => 'only text  format',
					'status' => false
			], 422);
		}

		$language = LanguageModel::find($id);
		if(empty($language)){
			return response()->json([
					'message' => 'Language not found'
			],404);
		}
		\App::setLocale($language->languageCode);
		if (Lang::has('home'))
		{
			$loader = app('translator')->getLoader();
			$locale = \Config::get('app.locale');
			$lines = $loader->load($locale, 'home', '*');
			$string = '<?php'."\n"."return ["."\n";
			$found = false;
			$keyCompare = isset($get->oldKey) ? $get->oldKey : $get->dataKey;

			foreach ($lines as $key=> $value) {
				if($key == $keyCompare){
					$found = true;
					$string.= "'".$get->dataKey."' => '".str_replace("'","\'", $get->dataValue)."',"."\n";
				}else{
					$string.= "'".$key."' => '".str_replace("'","\'", $value)."',"."\n";
				}
			}

			if(!$found) {
				$string.= "'".$get->dataKey."' => '".str_replace("'","\'", $get->dataValue)."',"."\n";
			}

			$string.= '];';
			$file = base_path().'/resources/lang/'.$language->languageCode.'/home.php';
			\File::makeDirectory(base_path().'/resources/lang/'.$language->languageCode, 0777, true, true);

			file_put_contents($file, $string);
			return response()->json([
					'dataValue' => $get->dataValue,
					'dataKey' => $get->dataKey,
					'message' => 'updated successfully',
			], 201);
		}

	}

	public function postNewTranslation(Request $get, $id){
		$rules = [
			'dataKey' => 'required',
			'dataValue' => 'required'
		];

		$validator = Validator::make($get->all(), $rules);
		if($validator->fails()){
			return response()->json([
					'code' => 422,
					'message' => $validator->errors()->all()[0],
					'status' => false
			], 422);
		}
		if (preg_match("/<script[^>]+>/i", $get->dataValue)) {
			return response()->json([
					'code' => 422,
					'message' => 'only text  format',
					'status' => false
			], 422);
		}

		$language = LanguageModel::find($id);
		if(empty($language)){
			return response()->json([
					'message' => 'Language not found'
			],404);
		}
		\App::setLocale($language->languageCode);
		if (Lang::has('home'))
		{
			$loader = app('translator')->getLoader();
			$locale = \Config::get('app.locale');
			$lines = $loader->load($locale, 'home', '*');
			$string = '<?php'."\n"."return ["."\n";
			$found = false;

			foreach ($lines as $key=> $value) {
				if($key === $get->dataKey){
					$found = true;
					$string.= "'".$key."' => '".str_replace("'","\'", $get->dataValue)."',"."\n";
				}else{
					$string.= "'".$key."' => '".str_replace("'","\'", $value)."',"."\n";
				}
			}
			if(!$found) {
				$string.= "'".$get->dataKey."' => '".str_replace("'","\'", $get->dataValue)."',"."\n";
			}

			$string.= '];';

			$file = base_path().'/resources/lang/'.$language->languageCode.'/home.php';
			\File::makeDirectory(base_path().'/resources/lang/'.$language->languageCode, 0777, true, true);

			file_put_contents($file, $string);
			return redirect()->back()->with('msg','Add successfully');
		}
	}
}
