<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $userid = auth::user()->id;        
        $user_info = DB::table('user_info')->where('id', '=', $userid)->get();
        foreach($user_info as $values) {
            $user_role = $values->role_descr;
            $user_group_akses = $values->group_akses_id;
            $person_id = $values->person_id;
            $person_type = $values->person_type;
        }
        
        $user_access_info = DB::table('user_access_info_v')->where('user_id', '=',$userid)->get(); 
        $user_company = DB::table('user_access_info_v')->where('group_id', '=',$user_group_akses)->where('access_key','=', 'COMPANY')->get();
        $user_brand = DB::table('user_access_info_v')->where('group_id', '=',$user_group_akses)->where('access_key', '=','BRAND PRODUK')->get();
        $user_kat_act = DB::table('user_access_info_v')->where('group_id', '=',$user_group_akses)->where('access_key', '=','KATEGORY')->get();
        $user_market_type = DB::table('user_access_info_v')->where('group_id', '=',$user_group_akses)->where('access_key', '=','MARKET TYPE')->get();
        $user_division = DB::table('user_access_info_v')->where('group_id', '=',$user_group_akses)->where('access_key', '=','DIVISI')->get();
        $person_info = DB::table('person_area_view')->where('person_id','=',$person_id)->get();       
        $customer_info = DB::table('customer_info')->wherein('cust_ship_id',function($query){
            $query->select('access_id')->where('access_key','=','DISTRIBUTOR')->from('user_access_info_v')
            ->where('user_id',auth::user()->id);
        })->get();  

        if ($user_role == 'user_admin_menu') {
            //
        }

        $minFleetType = DB::table('fnd_values')->where('group_value','Min_MOQ_Fleet')->get(); 
        $minFleetVal = DB::table('fnd_values')->where('group_value','Min_MOQ_Value')->get(); 
        $minFleetCrt = DB::table('fnd_values')->where('group_value','Min_MOQ_Carton')->get(); 

        

        Session::put('user_info',$user_info);
        Session::put('customer_info',$customer_info);
        Session::put('user_company',$user_company);
        Session::put('user_kat_act',$user_kat_act);
        Session::put('user_brand',$user_brand);
        Session::put('user_market_type',$user_market_type);
        Session::put('user_division',$user_division);
        Session::put('minFleetCrt',$minFleetCrt);
        Session::put('minFleetVal',$minFleetVal);
        Session::put('minFleetType',$minFleetType);
        Session::put('person_info',$person_info);
        Session::put('person_type',$person_type);

        
        if ( ($person_type) == 'Distributor') {
                  $wf_approval = DB::table('cmo_approval_view')->where('recipient_id','=',$person_id)
                            ->where('mail_status','=','SENT')->get();    

                return view('dashboard.distributor_graph',compact('wf_approval')); 
        } else {
                $wf_approval = DB::table('all_approval_view')->where('recipient_id','=',$person_id)
                            ->where('mail_status','=','SENT')->get();   
                 return view('dashboard.sales_graph',compact('wf_approval')); 
        }
    }
}
