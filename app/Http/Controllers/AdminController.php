<?php

namespace App\Http\Controllers;

use App\User;
use App\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function login(Request $request)
    {
        if($request->isMethod('post')){
    		$data = $request->input();
            $adminCount = Admin::where(['name' => $data['name'],'password'=>md5($data['password']),'status'=>1])->count(); 
            if($adminCount > 0){
                //echo "Success"; die;
                Session::put('adminSession', $data['name']);
                return redirect('/admin/dashboard');
        	}else{
                //echo "failed"; die;
                return redirect('/admin')->with('flash_message_error','Invalid Username or Password');
        	}
    	}
    	return view('admin.admin_login');
    }

    public function dashboard()
    {
        return view('admin.dashbord');
    }
    public function setting()
    {
        
        $adminDetails = Admin::where(['name'=>Session::get('adminSession')])->first();

        // echo "<pre>"; print_r($adminDetails); die;
        return view('admin.setting')->with(compact('adminDetails'));
    }
    public function chkPassword(Request $request){
        $data = $request->all();
        // echo "<pre>"; print_r($data); die;
        // echo "<pre>"; print_r($data['current_pwd']); die;
        
        $adminCount = Admin::where(['name' => Session::get('adminSession'),'password'=>md5($data['current_pwd'])])->count(); 
        // echo "<pre>"; print($adminCount); die;
    
        if ($adminCount == 1) {
                //echo '{"valid":true}';die;
                echo "true"; die;
            } else {
                //echo '{"valid":false}';die;
                echo "false"; die;
            }
    }

    public function updatePassword(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //echo "<pre>"; print_r($data); die;
            $adminCount = Admin::where(['name' => Session::get('adminSession'),'password'=>md5($data['current_pwd'])])->count();

            if ($adminCount == 1) {
                // here you know data is valid
                $password = md5($data['new_pwd']);
                Admin::where('name',Session::get('adminSession'))->update(['password'=>$password]);
                return redirect('/admin/setting')->with('flash_message_success', 'Password updated successfully.');
            }else{
                return redirect('/admin/setting')->with('flash_message_error', 'Current Password entered is incorrect.');
            }

            
        }
    }
    public function logout(){
        Session::flush();
        return redirect('/admin')->with('flash_message_success', 'Logged out successfully.');
       
    }
}
