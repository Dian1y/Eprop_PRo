<?php

namespace App\Http\Controllers\backup_ctrl;

use Illuminate\Support\MessageBag;
use Illuminate\Http\Request; 
use Response;
use Validator;
use Carbon;
use Session;
use Illuminate\Support\Facades\Input;
use Excel;
use Auth;
use DB;

class EpropSentralCtrl_5May18 extends Controller
{
	/**
    * Return View file
    * @var array
    */

    protected $FirstPageSentra;
    protected $SecondPageSentra;
    protected $ThirdPageSentra;
    protected $FourthPageSentra;
    protected $FifthPageSentra;

	public function InputSentra() {
		
		$user_info = Session::get('user_info');

		foreach ($user_info as  $value) {
			$person_name = $value->person_name;
			$person_id = $value->person_id;
			$user_group_akses = $value->group_akses_id;
		}


		/*$company = DB::table('user_access_info_v')->where('group_id', '=',$user_group_akses)->where('access_key','=', 'COMPANY')->get();

		$company_access = count($company);

		foreach ($company as $value) {
			$company_id = $value->company_id;
			break;
		}*/

		$brand = DB::table('user_access_info_v')->where('group_id', '=',$user_group_akses)
								->where('access_key', '=','BRAND')->get();
		
		$kat_aktivity = Session::get('user_kat_act');

		//
		$katActivity = [];

		foreach ($kat_aktivity as  $value) {

			$katActivity = array_merge($katActivity,array($value->access_id));
		}

		//
		$division = Session::get('user_division');

		foreach ($division as $value) {
			$division_id = $value->access_id;
			break; 
		} 
		
		 $user_activity = DB::table('activity_division_v')->wherein('kategory_id',$katActivity)->where('division_id','=',$division_id)->select('activity_id', 'activity_name')->get();

		$yearNow = Carbon\Carbon::now()->year;
		$year5  = Carbon\Carbon::now()->addyear(5)->year;

		return view('eprop.eprop_sentra_1', compact('person_name', 'person_id','yearNow','year5', 'company', 'brand', 'user_activity'));
	}


    public function PostFirstPage(Request $request) {
    	 Session::forget('FirstPageSentra');
         Session::forget('det_prop_activity');
         Session::forget('det_prop_brand');
         Session::forget('det_prop_varian');
         Session::forget('det_prop_branch'); 
         Session::forget('det_prop_executor');

    	 $date_range =  strrpos($request->eprop_date, '-', 1);

    	 $startDate = new Carbon\Carbon(trim(substr($request->eprop_date,0,$date_range-1)));
    	 $startDate->format('Y-m-d');

    	 $EndDate = new Carbon\Carbon(trim(substr($request->eprop_date,$date_range+1)));
    	 $EndDate->format('Y-m-d');
    	 
    	 $FirstPageSentra = [];

    	 $FirstPageSentra [] = ['reco_no' => $request->reco,
    	 						'budget_year' => $request->year,
    	 						'company_id' => $request->company_id,
    	 						'division_id' => $request->divisi_id,
								'market_type_id' => $request->market_type_id,
								'reco_no' => $request->reco,
								'description' => $request->title,  	 					
    	 						'start_date' => $startDate,
    	 						'end_date' => $EndDate,
    	 						'originator' => Auth::user()->id 
    	 						];

         $det_prop_division = []; 
         $division =$request->input('divisi_id');
         $det_prop_division [] = [ 'division_id' => $division];
        /* $divID = "";
         if (!empty($division)) {
            foreach ($division as $key => $value){
                $det_prop_division [] = [
                'division_id' => $value ];    
                if ($divID == "") {
                    $divID = (int)$value['division_id'] ;    
                } else {
                        $divID = $divID . ',' . (int)$value['division_id'] ;    
                }     
            } 
        }    */



         $det_prop_market= []; 
         $marketType =$request->input('market_type_id'); 
         $marketID = "";
         if (!empty($marketType)) {      
            foreach ($marketType as $key => $value){
                $det_prop_market [] = [
                'market_type_id' => $value ];    
                if ($marketID == "") {
                    $marketID = (int)$value  ;    
                } else {
                        $marketID = $marketID . ',' . (int)$value ;    
                }    
            } 
        }    	 

        $det_prop_activity = []; 
    	 $activity =$request->input('activity_id');
    	 if (!empty($activity)) {
    	    foreach ($activity as $key => $value){
    	    	$det_prop_activity [] = [
    	        'activity_id' => $value ];         
    	    } 
    	}

         $det_prop_brand = []; 
    	 $brand =$request->input('brand_id');
    	 if (!empty($brand)) {
    	    foreach ($brand as $key => $value){
    	        $det_prop_brand [] = [
    	        'brand_id' => $value ];         
    	    }  
    	 }

         $det_prop_varian = []; 
    	 $varian =$request->input('varian_id');
    	 if (!empty($varian)) {
    	    foreach ($varian as $key => $value){
    	    	$det_prop_varian [] = [
    	        'varian_id' => $value ];         
    	    }  
    	}

         $det_prop_branch = []; 
    	 $branch =$request->input('branch_id');
    	 if (!empty($branch)) {
    	    foreach ($branch as $key => $value){
    	    	$det_prop_branch [] = [
    	        'branch_id' => $value ];         
    	    }  
    	}

         $det_prop_executor = []; 
    	 $executor =$request->input('executor_id');
    	 if (!empty($executor)) {
    	    foreach ($executor as $key => $value){
    	    	$det_prop_executor [] = [
    	        'executor_id' => $value ];         
    	    }  
    	 }

         Session::put('FirstPageSentra', $FirstPageSentra);
         Session::put('det_prop_activity', $det_prop_activity);
         Session::put('det_prop_brand', $det_prop_brand);
         Session::put('det_prop_varian', $det_prop_varian);
         Session::put('det_prop_branch', $det_prop_branch); 
         Session::put('det_prop_executor', $det_prop_executor);
         Session::put('det_prop_division', $det_prop_division);
         Session::put('det_prop_market', $det_prop_market);

//         dd($det_prop_activity, $det_prop_brand,$det_prop_varian, $det_prop_branch, $det_prop_executor, $det_prop_division, $det_prop_market);

         $user_market_type = Session::get('user_market_type');
         $user_market_type = collect($user_market_type);
         $market = $user_market_type->whereIN('access_id', $request->market_type_id);
         
         foreach ($market as $key => $value) {
         	$market_code = $value->access_code;
         }

        //if ($market_code == 'NKA' or $market_code == 'LKA') {
            $customer_info = Session::get('customer_info')->whereIn('area_id', $request->branch_id);
        	$account_mt = DB::table('mtl_mt_channel')->where('type_market_code','=',$market_code)->get();

            $store = DB::table('mtl_gt_store')->where('type_market_code', '=', $market_code)->get();
            return view('eprop.eprop_sentra_2_MT', compact('account_mt','store', 'customer_info'));
        //} else {
	 	// 	return view('eprop.eprop_sentra_2_GT');      		
        //}
    }

    
    public function PostSecondMTPage(Request $request)  {

        Session::forget('SndPageSentra');
        Session::put('det_prop_account');
        Session::put('det_prop_store');

        $eproptype = $request->eproptype;

        $SndPageSentra = [];

         $SndPageSentra [] = ['background' => $request->background,
                              'objective' => $request->objective,
                                'mechanism' => $request->mechanism,
                                'estimate_cost' => $request->estimate_cost,
                                'kpi' => $request->kpi
                                ];

        $det_prop_account = []; 
        $det_prop_disti = []; 
        if ($eproptype == 'MT') {
                $account_mt =$request->input('account_mt');
                 if (!empty($account_mt)) {
                    foreach ($account_mt as $key => $value){
                        $det_prop_account [] = [
                        'account_id' => $value ];         
                    } 
                }
        }

        if ($eproptype == 'GT') {
                $distributor =$request->input('distributor_id');
                 if (!empty($distributor)) {
                    foreach ($distributor as $key => $value){
                        $det_prop_disti [] = [
                        'distributor_id' => $value ];         
                    } 
                }
        }

         $det_prop_store = []; 
         $store_mt =$request->input('store_mt');
         if (!empty($store_mt)) {
            foreach ($store_mt as $key => $value){
                $det_prop_store [] = [
                'store_id' => $value ];         
            } 
        } 

         Session::put('SndPageSentra', $SndPageSentra);
         Session::put('det_prop_account', $det_prop_account);
         Session::put('det_prop_disti', $det_prop_disti);
         Session::put('det_prop_store', $det_prop_store);

         $SndPageSentra = collect($SndPageSentra);
         $SndPageSentra = $SndPageSentra->toArray();

         $FirstPageSentra = (Session::get('FirstPageSentra'));  
            
         foreach ($FirstPageSentra as $key => $value) {
                $budget_year = $value['budget_year'];
                $company_id = $value['company_id']; 
            }   

         /*//get division_code
         $user_division = Session::get('user_division');
         $user_division = collect($user_division)->where('access_id',$division_id);
         foreach ($user_division as $key => $value) {
              $user_div_code = $value->access_code;

          } 

        */

         //Merge, Cross Join Session To Get show Budget Breakdwon

         if (Session::has('det_prop_activity')) {
                  $det_prop_activity = Session::Get('det_prop_activity'); 
                  $activity_id =""; 
                  foreach ($det_prop_activity as $value){
                    if ($activity_id == "") {
                        $activity_id = (int)$value['activity_id'] ;    
                    } else {
                        $activity_id = $activity_id . ',' . (int)$value['activity_id'] ;    
                    }          
                  }  

              }
         if (Session::has('det_prop_brand')) {
                  $det_prop_brand = Session::Get('det_prop_brand');
                  $brand_id =""; 
                  foreach ($det_prop_brand as $value){
                    if ($brand_id == "") {
                        $brand_id = (int)$value['brand_id'] ;    
                    } else {
                        $brand_id = $brand_id . ',' . (int)$value['brand_id'] ;    
                    }          
                  } 
              }

         if (Session::has('det_prop_division')) {
                  $det_prop_division = Session::Get('det_prop_division');
                  $division_id =""; 
                  foreach ($det_prop_division as $value){
                    if ($division_id == "") {
                        $division_id = (int)$value['division_id'] ;    
                    } else {
                        $division_id = $division_id . ',' . (int)$value['division_id'] ;    
                    }          
                  } 
              }

            //  dd($det_prop_division);

         if (Session::has('det_prop_varian')) {
                  $det_prop_varian = Session::Get('det_prop_varian');
                  $produk_id=""; 
                  foreach ($det_prop_varian as $value){
                      if ($produk_id == "") {
                         $produk_id = (int)$value['varian_id'] ;    
                      } else {
                         $produk_id = $produk_id . ',' . (int)$value['varian_id'] ;    
                      } 
                   } 
              }      
         if (Session::has('det_prop_branch')) {
                $det_prop_branch = Session::Get('det_prop_branch'); 
                $branch_id =""; 
                foreach ($det_prop_branch as $value){
                if ($branch_id == "") {
                    $branch_id = (int)$value['branch_id'] ;    
                } else {
                        $branch_id = $branch_id . ',' . (int)$value['branch_id'] ;    
                    }
                    
                }  

                  $person_info = Session::get('person_info');
                  $region_branch = collect($person_info)->wherein('area_id',$branch_id )->pluck('region_id');
  
         }  



       //$Budget_break = DB::table('budget_view')->wherein('company_id','=', )
        //if ($user_div_code == 'NKA') {
                 if (Session::has('det_prop_account')) {
                        $det_prop_account = Session::Get('det_prop_account');
                        $account_mt =""; 
                        foreach ($det_prop_account as $value){
                        if ($account_mt == "") {
                            $account_mt = (int)$value['account_id'] ;    
                        } else {
                                $account_mt = $account_mt . ',' .(int)$value['account_id'] ;    
                            }
                            
                        }  
                 }   

                 $det_prop_executor = Session::get('det_prop_executor');

                 $budget_breakdown = DB::table('eprop_budget_breakdown_mt_v')
                                                ->where('budget_year','=', $budget_year)             
                                                ->where('division_id', '=',  $division_id)      
                                                ->whereIn('activity_id', $det_prop_activity)
                                                ->whereIn('brand_id',$det_prop_brand)
                                                ->whereIn('area_id',$det_prop_branch)
                                                ->whereIn('account_id',$det_prop_account)
                                                ->whereIn('person_id',$det_prop_executor)
                                                ->whereIn('produk_id',$det_prop_varian)->get();

               
                

                $activity_id = '{' . $account_mt . '}';
                $brand_id = serialize($det_prop_brand);
                $branch_id = serialize($det_prop_branch);
                $account_mt = serialize($det_prop_account);
                $produk_id = serialize($det_prop_varian);
                $executor = serialize($det_prop_executor);

                $wf_check = DB::select('Select * From test_array_p(?,?,?,?,?,?,?,?)',[$activity_id, $brand_id, $account_mt ,$branch_id,$produk_id, $division_id, $budget_year, $executor]);                                                


                 Session::put('budget_breakdown', $budget_breakdown);

                 //dd($budget_breakdown,$det_prop_activity,$det_prop_brand, $det_prop_branch);  

                 $cat_activity_id = [];
                 $mt_account_id = [];
                 $brand_id = [];
                 foreach ($budget_breakdown as $key => $value) {
                        $cat_activity_id [] = [
                        'cat_activity_id' => $value->kategory_id ];                        
                        $mt_account_id [] = [
                        'mt_account_id' => $value->account_id ];
                        $brand_id [] = [
                        'brand_id' => $value->brand_id ];
                 }                                                       


                $budget_info = DB::table('budget_view')
                                                ->where('budget_year','=', $budget_year)  
                                                ->whereIn('cat_activity_id', $cat_activity_id)
                                                ->whereIn('mt_account_id',$mt_account_id)   
                                                ->whereIn('brand_id',$brand_id)->get();   

                Session::put('budget_info', $budget_info);

        //}

         return view('eprop.eprop_sentra_3', compact('budget_breakdown','budget_info'));  
    }


    public function PostThridPage(Request $request) {

        $budget_info = Session::get('budget_info'); 
        $budgetbreak   = Session::get('budget_breakdown') ;
        $budgetAmount = $request->budgetallocation;
 
        $row_number = $request->row_number;
        $combine_arr = array_combine($row_number, $budgetAmount);
 

        //$SumBudgetBreak = collect($SumBudgetBreak)->sum('budgetAmount')->groupBy('')

        foreach ($budgetbreak as $key => $value) {
            foreach ($combine_arr as $bkey => $budget) {
                if ($value->row_number == $bkey) {
                    $value->budget_amount = intval($budget);
                }
            } 
        }

       // dd($combine_arr, $request->all(),Session::get('budget_breakdown'));

       // dd($budgetbreak, Session::get('budget_info'));
       
        $budgetApprove = [];
        $AmountApprv=0;
        $Total_amount=0;
        foreach ($budget_info as $key => $value) {
            $filtered = $budgetbreak->where('kategory_id',$value->cat_activity_id)
                                   ->where('brand_code', $value->brand_code)
                                   ->where('account_name',$value->account_name);

            $filtered->all();

            $AmountApprv = 0;
            foreach ($filtered as $filterkey => $filtervalue) {
                $AmountApprv = $AmountApprv + $filtervalue->budget_amount;
             }
             $outstanding = ($value->current_budget - $AmountApprv);
             $Total_amount = $Total_amount + $AmountApprv;
             $budgetApprove[] = [
                                  'company_code'  =>  $value->company_code,
                                  'company_name'  =>  $value->company_name,
                                  'budget_year'  =>  $value->budget_year,
                                  'company_id'  =>  $value->company_id,
                                  'total_budget'  =>  $value->total_budget,
                                  'budget_hdr_id'  =>  $value->budget_hdr_id,
                                  'cat_activity_id'  =>  $value->cat_activity_id,
                                  'brand_id'  =>  $value->brand_id,
                                  'mt_account_id'  =>  $value->mt_account_id,
                                  'begining_budget'  =>  $value->begining_budget,
                                  'current_budget'  =>  $value->current_budget,
                                  'region_id'  =>  $value->region_id,
                                  'id'  =>  $value->id,
                                  'account_name'  =>  $value->account_name,
                                  'type_market_code'  =>  $value->type_market_code,
                                  'region'  =>  $value->region,
                                  'brand_code'  =>  $value->brand_code,
                                  'brand_name'  =>  $value->brand_name,
                                  'product_type'  =>  $value->product_type,
                                  'product_sub_type'  =>  $value->product_sub_type,
                                  'kategory_name'  =>  $value->kategory_name,
                                  'budget_tobe_Apprv'  => $AmountApprv,
                                  'outstanding'  => $outstanding
                            ];
        }



        $result = $budgetbreak->groupBy(['account_name']);
        $result->toArray();

        dd('budgetApprove');

        return view ('eprop.eprop_sentra_4', compact('budgetApprove','Total_amount'));

    }


    public function PostFourthPage(Request $request) {

    }



    public function getBrand($company_id)
    { 
         
        $user_info = Session::get('user_info');

		foreach ($user_info as $key => $value) {
			$user_group_akses = $value->group_akses_id;
		}

        $listofbrand = DB::table('user_access_info_v')->where('group_id', '=',$user_group_akses)
        				->where('access_key', '=','BRAND')->where('company_id','=', $company_id)
        				->where('group_id','=', $user_group_akses)->pluck('access_id', 'access_name');              

        //return Response::json($listofbrand);
        return json_encode( $listofbrand);
    }


    public function getActivity($division_id)
    { 
   
    	$user_info = Session::get('user_info');

    	$user_group_akses = '';
		foreach ($user_info as $key => $value) { 
			$user_group_akses = $value->group_akses_id;
		}

        $user_activity = DB::table('activity_division_v')->where('division_id', '=',$division_id)->pluck('activity_id', 'activity_name');

        return json_encode($user_activity);

    }

    public function getExecutor(Request $request, $branch_id)
    { 
    

        $executors = DB::table('person_area_view')->where('area_id','=', $branch_id)
                    ->select('person_id', 'name')->distinct()->get();

        return Response::json($executors);

    }

    public function getVarian($brand_id)
    { 
  	

    	$Varian = DB::table('mtl_produk')->where('brand_id','=', $brand_id)
    				->select('id', 'produk_name')->distinct()->get();

        return Response::json($Varian);

    }
}
