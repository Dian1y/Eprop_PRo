<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use DB;
use Session;
use Response;
use App\User_access;
use App\User;
use App\MtlPerson;

class Users extends Controller
{
    public function index() {  
    	$users = DB::table('users_view')->get(); 
        return view('user_setting/index', ['Users' => $users]);  
    }

    public function role() {
    	$users = User::paginate(10);
        return view('user_setting/index', ['Users' => $users]);  
    }

    public function create() { 
        $lrole = DB::table('roles')->where('id', '<>', 1)->get();
        $userApps = DB::table('user_applications')->get();
    	$lpersons = DB::table('person_view')->where('sts_code', '=', 'Y')->get();
    	//sdd($role);
    	return view('user_setting/create', compact('lrole','lpersons'));  
    }



    public function store(Request $request) {

        $this->validator($request->all())->validate();

        User::create([
            'name' => $request->input('name'),
            'username' => $request->input('username'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role_id' => $request->input('role_id'),
            'person_id' => $request->input('person_id'),
            'user_app' => $request->input('user_app'),
        ]);

        $Success = 'User Akses berhasil disimpan!';
        Session::flash('success', $Success); 

        $users = DB::table('users_view')->get(); 
        return view('user_setting/index', ['Users' => $users]);  
    }

    protected function validator(array $data)
    { 
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:20|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role_id' => 'required|',
            'person_id' => 'required'
        ]);
    }


    public function edit($id) {

        $custview = DB::table('customer_view')->get();
        $custview=collect($custview)->sortby('customer_ship_name');
        $userview = DB::table('users_view')->where('id', '=', $id)->get();
        //$useraccess = DB::table('user_access_v')->where('id', '=', $id)->get();
        //Session::put('user_access', $useraccess);
        Session::put('userview', $userview);
        return view('user_setting/user_akses', ['custview' => $custview]);
    }

    public function show($id) {
    	$userview = DB::table('users_view')->where('id', '=', $id)->get();
        $useraccess = DB::table('user_access_v')->where('id', '=', $id)->get();
        Session::put('user_access', $useraccess); 
        return view('user_setting/show', ['userview' => $userview]);
    }

     public function destroy(Request $request)
    {

        $userID = $request->input('id'); 
        $access_id = $request->input('access_id'); 
        User_access::find($access_id)->delete();
        
        $Success = 'Customer Akses untuk Berhasil Di Hapus!';

        $custview = DB::table('customer_view')->get();
        $custview=collect($custview)->sortby('customer_ship_name');
        $userview = DB::table('users_view')->where('id', '=', $userID)->get();
        $useraccess = DB::table('user_access_v')->where('id', '=', $userID)->get();
        Session::put('user_access', $useraccess);
        Session::put('userview', $userview); 
        return view('user_setting/user_akses', ['custview' => $custview]);  
    }

    public function getEmail($personid) {
        $getmail = MtlPerson::find($personid);
        $email = $getmail->email;
        $name = $getmail->name;      
        return Response::json($email);
    } 

    public function getName($personid) {
        $getName = MtlPerson::find($personid);
        $name = $getName->name;   
        return Response::json($name);
    } 

    
    public function store_old(Request $request) {

        $rules = array( 
            'cust_ship_id' => 'required|exists:mtl_customer_ship_to',
            'user_id' => 'required'
            );

        $validator = $this->validate($request, $rules);  
        $id =  $request->input('user_id');

        $users_access = new User_access;
        $users_access->user_id = $request->input('user_id');
        $users_access->cust_ship_id = $request->input('cust_ship_id');
        $users_access->created_by = Auth::user()->name;
        $users_access->updated_by = Auth::user()->name;
        $users_access->save();

        $Success = 'Customer Akses untuk berhasil disimpan!';
        Session::flash('success', $Success); 

        $custview = DB::table('customer_view')->get();
        $custview=collect($custview)->sortby('customer_ship_name');
        $userview = DB::table('users_view')->where('id', '<>', $id)->get();
        $useraccess = DB::table('user_access_v')->where('id', '=', $id)->get();
        Session::put('user_access', $useraccess);
        Session::put('userview', $userview);
        return view('user_setting/user_akses', ['custview' => $custview]);
    }
}


