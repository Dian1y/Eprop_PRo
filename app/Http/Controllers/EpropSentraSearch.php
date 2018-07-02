<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag; 
use Response;
use Validator;
use Carbon;
use Session;
use Illuminate\Support\Facades\Input; 
use Excel;
use Auth;
use App\User;
use DB;


class EpropSentraSearch extends Controller
{
    public function index() {

        $originator = 'N';
        $person_info = Session::get('person_info');
        foreach ($person_info as $key => $value) {
            $originator = $value->originator;
        }


    	$user_info = Session::get('user_info');
    	foreach ($user_info as $key => $value) {
    		$administrator_flag = $value->administrator_flag;            
    		$user_group_akses = $value->group_akses_id;
            $person_id = $value->person_id;
            $person_type = $value->person_type;
    	}
 

    	if ($administrator_flag == 'N') {
    		$executor = DB::table('person_view')->where('executor','=','Y')->where('person_id','=',Auth::user()->person_id)->get();
	    	$eprop_no = DB::table('eprop_info_view')->select('eprop_no')->groupBy('eprop_no')->get();
	    	$account_mt = DB::table('mt_account_access_v')->whereIn('group_id', DB::table('mtl_persons')->where('id','=',Auth::user()->person_id)->pluck('group_akses_id'))->select('id','account_name')->distinct()->get();

	    	$user_division = DB::table('user_access_division_v')->select('id', 'division_name','group_id')->where('group_id', '=',$user_group_akses)->groupby()->orderby('division_name')->get(); 
	    	$brand = DB::table('user_access_brand_v')->where('group_id', '=',$user_group_akses)->get(); 
	    	$region = DB::table('person_area_view')->where('person_id','=',Auth::user()->person_id)->select('region_id', 'region')->groupby('region_id', 'region')->orderby('region')->get();
	    	$customer_info = (Session::get('customer_info'));
            $person_info = DB::table('person_area_view')->select('area_id', 'area')->where('person_id','=',Auth::user()->person_id)->groupby('area_id', 'area')->orderby('area')->get();

    	} else {
    		$eprop_summary = DB::table('eprop_info_view')->get();
    		$executor = DB::table('person_view')->where('executor','=','Y')->get();
    		$account_mt = DB::table('mt_account_access_v')->select('id','account_name')->get();
    		$eprop_no = DB::table('eprop_info_view')->select('eprop_no')->groupBy('eprop_no')->get();
    		$user_division = DB::table('mtl_division')->get();
    		$brand = DB::table('mtl_brand_produk')->get();
    		$customer_info = DB::table('mtl_customer_ship_to')->select('cust_ship_id','customer_ship_name')->get();
    		$region = DB::table('person_area_view')->select('region_id', 'region')->groupby('region_id', 'region')->orderby('region')->get();
    		$person_info = DB::table('person_area_view')->select('area_id', 'area')->groupby('area_id', 'area')->orderby('area')->get();

    	}


            	
		$status = DB::table('fnd_values')->where('group_value','=','Status_Approval')->get();
		$user_activity = DB::table('activity_division_v')->distinct()->get();


	    return view ('eprop_search.index',compact('eprop_no','customer_info','person_info','user_division','administrator_flag','executor','account_mt','status','brand','user_activity','region','originator'));

    }

    public function Search(Request $request) {
 
        if (is_null($request->divisi_id)) {
            $divisi_id = -1;
        } else {
            $divisi_id = $request->divisi_id;
        }
        if (is_null($request->region_id)) {
            $region_id = -1;
        } else {
            $region_id = $request->region_id;
        }
        if (is_null($request->branch_id)) {
            $branch_id = -1;
        } else {
            $branch_id = $request->branch_id;
        }
        if (is_null($request->brand_id)) {
            $brand_id = -1;
        } else {
            $brand_id = $request->brand_id;
        }
        if (is_null($request->activity_id)) {
            $activity_id = -1;
        } else {
            $activity_id = $request->activity_id;
        }
        if (is_null($request->cust_ship_id)) {
            $cust_ship_id = -1;
        } else {
            $cust_ship_id = $request->cust_ship_id;
        }        
        if (is_null($request->account_mt)) {
            $account = -1;
        } else {
            $account = $request->account_mt;
        }   
        if (is_null($request->status)) {
            $status = '-';
        } else {
            $status = $request->status;
        }        
        if (is_null($request->eprop_no)) {
            $eprop_num = '-';
        } else {
            $eprop_num = $request->eprop_no;
        }
 
        
        //dd($divisi_id);

    	$user_info = Session::get('user_info');

    	foreach ($user_info as $key => $value) {
    		$administrator_flag = $value->administrator_flag;            
    		$user_group_akses = $value->group_akses_id;
            $person_id = $value->person_id;
            $person_type = $value->person_type;
    	}

        $originator = 'N';
        $person_info = Session::get('person_info');
        foreach ($person_info as $key => $value) {
            $originator = $value->originator;
        }
 

    	if ($administrator_flag == 'N') {
    		$executor = DB::table('person_view')->where('executor','=','Y')->where('person_id','=',Auth::user()->person_id)->get();
	    	$eprop_no = DB::table('eprop_info_view')->select('eprop_no')->groupBy('eprop_no')->get();
	    	$account_mt = DB::table('mt_account_access_v')->where('group_id', '=', $user_group_akses)->select('id','account_name')->distinct()->get();
	    	$user_division = DB::table('user_access_division_v')->select('id', 'division_name','group_id')->where('group_id', '=',$user_group_akses)->groupby()->orderby('division_name')->get(); 
	    	$brand = DB::table('user_access_brand_v')->where('group_id', '=',$user_group_akses)->get(); 
	    	$region = DB::table('person_area_view')->select('region_id', 'region')->where('person_id','=',Auth::user()->person_id)->groupby('region_id', 'region')->orderby('region')->get();
	    	$customer_info = (Session::get('customer_info'));
            $person_info = DB::table('person_area_view')->select('area_id', 'area')->where('person_id','=',Auth::user()->person_id)->groupby('area_id', 'area')->orderby('area')->get();
           // $eprop_summary = DB::table('eprop_info_view')->get();
	    	$eprop_summary = DB::select('Select Distinct header_id, eprop_no, reco_no, eprop_reference_num, eprop_reference_id,description, budget_year, type_market, division_name, originator_name, position_name, apprv_status From eprop_view_query(?,?,?,?,?,?,?,?,?,?)',[$person_id, $divisi_id,$region_id,$branch_id,$brand_id, $activity_id,$cust_ship_id,$account, $status, $eprop_num]); 
    	} else {
    		//$eprop_summary = DB::table('eprop_info_view')->get();
    		$executor = DB::table('person_view')->where('executor','=','Y')->get();
    		$account_mt = DB::table('mt_account_access_v')->select('id','account_name')->get();
    		$eprop_no = DB::table('eprop_info_view')->select('eprop_no')->groupBy('eprop_no')->get();
    		$user_division = DB::table('mtl_division')->get();
    		$brand = DB::table('mtl_brand_produk')->get();
    		$customer_info = DB::table('mtl_customer_ship_to')->select('cust_ship_id','customer_ship_name')->get();
    		$region = DB::table('person_area_view')->select('region_id', 'region')->groupby('region_id', 'region')->orderby('region')->get();
    		$person_info = DB::table('person_area_view')->select('area_id', 'area')->groupby('area_id', 'area')->orderby('area')->get();
            $eprop_summary = DB::select('Select distinct header_id, eprop_no, reco_no, eprop_reference_num, eprop_reference_id,description, budget_year, type_market, division_name, originator_name, position_name, apprv_status From eprop_view_query(?,?,?,?,?,?,?,?,?,?)',[$person_id, $divisi_id,$region_id,$branch_id,$brand_id, $activity_id,$cust_ship_id,$account, $status, $eprop_num]);

    	}

    	
		$status = DB::table('fnd_values')->where('group_value','=','Status_Approval')->get();
		$user_activity = DB::table('activity_division_v')->distinct()->get();

        $Eproposal = DB::table('eprop_info_view')->select('id','eprop_no', 'reco_no', 'eprop_reference_num','description','budget_year','type_market','division_name','originator_name','position_name','apprv_status');

    	if (isset($request->divisi_id)) {
    		$Eproposal = $Eproposal->where('division_id','=',$request->divisi_id);
    	}
    	if (isset($request->region_id)) {
    		$Eproposal = $Eproposal->where('region_id','=',$request->region_id);
    	}
    	if (isset($request->branch_id)) {
    		$Eproposal = $Eproposal->where('area_id','=',$request->branch_id);
    	}
       	if (isset($request->executor_id)) {
    		$Eproposal = $Eproposal->where('executor_id','=',$request->executor_id);
    	}
        if (isset($request->brand_id)) {
    		$Eproposal = $Eproposal->where('brand_id','=',$request->brand_id);
    	}
        if (isset($request->activity_id)) {
    		$Eproposal = $Eproposal->where('activity_id','=',$request->activity_id);
    	}        
    	if (isset($request->cust_ship_id)) {
    		$Eproposal = $Eproposal->where('cust_ship_id','=',$request->cust_ship_id);
    	}    	
    	if (isset($request->account_mt)) {
    		$Eproposal = $Eproposal->where('account_id','=',$request->account_mt);
    	}    	
    	if (isset($request->status)) {
    		$Eproposal = $Eproposal->where('apprv_status','=',$request->status);
    	}    	
    	if (isset($request->eprop_no)) {
    		$Eproposal = $Eproposal->where('eprop_no','=',$request->eprop_no);
    	}

    	/*$eprop_summary = $Eproposal->groupby('id','eprop_no', 'reco_no', 'eprop_reference_num','description','budget_year','type_market','division_name','originator_name','position_name','apprv_status')->get(); */

    	//dd($Eproposal);

	    return view ('eprop_search.index',compact('eprop_summary','eprop_no','customer_info','person_info','user_division','administrator_flag','executor','account_mt','status','brand','user_activity','region','originator'));
    }
}
