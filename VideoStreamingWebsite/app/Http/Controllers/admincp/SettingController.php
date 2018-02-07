<?php

namespace App\Http\Controllers\admincp;

use Illuminate\Http\Request;
use App\Models\UsersModel;
use App\Models\OptionModel;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Helper\AppHelper;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function get_setting (){
        $get_option = AppHelper::getSiteConfig();
        return view('admincp.option.theme_setting')->with('option', $get_option);
    }
    public function post_setting(Request $get){
        if ($get) {
            if($get->theme == NULL)
            {            
                return redirect('admincp/theme-setting')->with('msgerror', 'Theme Not Allow Null!');                
            }
            $site_theme = $get->theme;            
            $data = array(
                'site_theme' => $site_theme
            );
            $update_config = OptionModel::where('id', '=', $get->id)->update($data);
            AppHelper::clearSiteConfigCache();
            if ($update_config) {
                return redirect('admincp/theme-setting')->with('msg', 'Update config successfully!');
            } else {
                return redirect('admincp/theme-setting')->with('msgerror', 'Update config unsuccessfully!');
            }

        }
    }
}
