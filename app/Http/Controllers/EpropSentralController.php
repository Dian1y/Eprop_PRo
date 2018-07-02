<?php

namespace App\Http\Controllers;

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

class EpropSentralController extends Controller
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

		
		$kat_aktivity = Session::get('user_kat_act');
        $brand = Session::get('user_brand');
    
		//
		$katActivity = [];

		foreach ($kat_aktivity as  $value) {

			$katActivity = array_merge($katActivity,array($value->access_id));
		}

		//
		$division = Session::get('user_division');
        $ListVarian = [];
        $executors = [];
       // dd($division);

		foreach ($division as $value) {
      $division_id = $value->access_id;
			$division_code = $value->access_code;
			break; 
		} 

		
        $user_activity = [];
        if (isset($division_id)) {
            $user_activity = DB::table('activity_division_v')->wherein('kategory_id',$katActivity)->where('division_id','=',$division_id)->select('activity_id', 'activity_name')->get();
        }
                
        $yearNow = Carbon\Carbon::now()->year;
        $year5  = Carbon\Carbon::now()->addyear(5)->year;

        $targetEprop = DB::table('target_eprop_v')->where('divisions', 'LIKE',"%{$division_code}%")->get();
        
        return view('eprop.eprop_sentra_1', compact('person_name', 'targetEprop','person_id','yearNow','year5', 'company', 'brand', 'user_activity','ListVarian','executors'));
        
	}

  public function CancelledInput() {
    //forget all Sessions
    Session::forget('det_prop_activity');
    Session::forget('det_prop_brand');
    Session::forget('det_prop_varian');
    Session::forget('det_prop_branch'); 
    Session::forget('det_prop_executor');
    Session::forget('det_prop_division');
    Session::forget('det_prop_market');
    Session::forget('det_prop_account');
    Session::forget('det_prop_disti');
    Session::forget('det_prop_store');
    Session::forget('eproptype');
    Session::forget('budget_breakdown');
    Session::forget('budget_info');
    Session::forget('FirstPageSentra');
    Session::forget('SecondPageSentra');

    return redirect('home');    

  }

  /**
    * Next & Submit Controller
    */

  public function PostFirstPage(Request $request) {

    	 // Forget All Session and recreate again
       Session::forget('FirstPageSentra');
       Session::forget('det_prop_activity');
       Session::forget('det_prop_brand');
       Session::forget('det_prop_varian');
       Session::forget('det_prop_branch'); 
       Session::forget('det_prop_executor');


    	 $date_range =  strrpos($request->eprop_date, '-', 1);

    	 $startDate = new Carbon\Carbon(trim(substr($request->eprop_date,0,$date_range-1)));
       $start_date = $startDate->format('m-d-Y');
    	 $startDate->format('Y-m-d');

    	 $EndDate = new Carbon\Carbon(trim(substr($request->eprop_date,$date_range+1)));
    	 $end_date = $EndDate->format('m-d-Y');
       $EndDate->format('Y-m-d');


       $rangeDate = $start_date . ' - ' . $end_date;
    	 
    	 $FirstPageSentra = [];

    	 $FirstPageSentra [] = ['reco_no' => $request->reco,
                  	 						'budget_year' => $request->year,
                  	 						'company_id' => $request->company_id,
                  	 						'division_id' => $request->divisi_id,
              				 				  'market_type_id' => $request->market_type_id,
              								  'description' => $request->title,  	 					
                  	 						'start_date' => $startDate,
                  	 						'end_date' => $EndDate,
                                'rangeDate' => $rangeDate,
                                'total_budget' => $request->total_buget,
                                'background' => null,
                                'objective' => null,
                                'mechanism' => null,
                                'estimate_cost' => null,
                                'kpi' => null,
                                'eprop_amount' => null,
                                'attach_file' => null,
                                'target_name' => null,
                                'target_estimasi' => null,
                                'avg_sales_3month' => null,
                                'sales_value' => null,
                                'sales_compare' => null,
                                'cost_ratio' => null,
    	 						              'originator' => Auth::user()->person_id ,
                                'created_by' => Auth::user()->id ,
                                'updated_by' => Auth::user()->id 
    	 						];

        if ($request->chktarget == "on") {
          foreach ($FirstPageSentra as $key => $value) {
            $value['avg_sales_3month'] = $request->avg_sales;
            $value['sales_value'] = $request->sales_value;
            $value['sales_compare'] = $request->sales_compare;
            $value['cost_ratio'] = $request->cost_ratio;
          }

        }

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
         $det_prop_market [] = ['market_type_id' => $marketType ];    
         /*$marketID = "";
         if (!empty($marketType)) {      
            foreach ($marketType as $key => $value){
                
                if ($marketID == "") {
                    $marketID = (int)$value  ;    
                } else {
                        $marketID = $marketID . ',' . (int)$value ;    
                }    
            } 
        }    	 */

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


        $customer_info = Session::get('customer_info')->whereIn('area_id', $request->branch_id);
        $store = DB::table('mtl_gt_store')->where('type_market_code', '=', $market_code )->get();

        if ($market_code == 'NKA') {

            if (!empty($request->input('executor_id'))) {
            
                $account_mt = DB::table('mt_account_access_v')->whereIn('group_id', DB::table('mtl_persons')
                            ->whereIn('id',$request->input('executor_id'))->pluck('group_akses_id'))->distinct()->get();
            }

        } else {

                $account_mt = [];
        }
            return view('eprop.eprop_sentra_2_MT', compact('account_mt','store', 'customer_info'));
        
    }

    
    public function PostSecondMTPage(Request $request)  {

        $eproptype = $request->eproptype;

        /*$SndPageSentra = [];

         $SndPageSentra [] = ['background' => $request->background,
                              'objective' => $request->objective,
                                'mechanism' => $request->mechanism,
                                'estimate_cost' => $request->estimate_cost,
                                'kpi' => $request->kpi
                                ];*/

        $FirstPageSentra = Session::get('FirstPageSentra');

        foreach ($FirstPageSentra as $key => $value) {
            $SecondPageSentra [] = ['reco_no' => $value['reco_no'],
                                'budget_year' => $value['budget_year'],
                                'company_id' => $value['company_id'],
                                'division_id' => $value['division_id'],
                                'market_type_id' => $value['market_type_id'],
                                'description' => $value['description'],                       
                                'start_date' => $value['start_date'],
                                'end_date' => $value['end_date'],
                                'rangeDate' => $value['rangeDate'],
                                'total_budget' => $value['total_budget'],
                                'background' => $request->background,
                                'objective' => $request->objective,
                                'mechanism' => $request->mechanism,
                                'estimate_cost' => $request->estimate_cost,
                                'kpi' => $request->kpi,
                                'eprop_amount' => null,
                                'attach_file' => null,
                                'target_name' => $value['target_name'],
                                'target_estimasi' => $value['target_estimasi'],
                                'avg_sales_3month' => $value['avg_sales_3month'],
                                'sales_value' => $value['sales_value'],
                                'sales_compare' => $value['sales_compare'],
                                'cost_ratio' => $value['cost_ratio'],
                                'originator' => Auth::user()->person_id,
                                'created_by' => Auth::user()->id ,
                                'updated_by' => Auth::user()->id 
                                ];
        }

        Session::put('FirstPageSentra',$SecondPageSentra);
        Session::put('SecondPageSentra', $SecondPageSentra);

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

         //Session::put('SndPageSentra', $SndPageSentra); 
         Session::put('det_prop_account', $det_prop_account);
         Session::put('det_prop_disti', $det_prop_disti);
         Session::put('det_prop_store', $det_prop_store);
         Session::put('eproptype',$eproptype);

         $FirstPageSentra = (Session::get('FirstPageSentra'));              
         foreach ($FirstPageSentra as $key => $value) {
                $budget_year = $value['budget_year'];
                $company_id = $value['company_id']; 
            }   

         //Merge, Cross Join Session To Get show Budget Breakdwon

         if (Session::has('det_prop_activity')) {
                  $det_prop_activity = Session::Get('det_prop_activity'); }

         if (Session::has('det_prop_brand')) {
                  $det_prop_brand = Session::Get('det_prop_brand'); }

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

         if (Session::has('det_prop_varian')) {
                  $det_prop_varian = Session::Get('det_prop_varian'); }

         if (Session::has('det_prop_branch')) {
                $det_prop_branch = Session::Get('det_prop_branch'); 
                $person_info = Session::get('person_info');
                $region_branch = collect($person_info)->wherein('area_id',$det_prop_branch )->pluck('region_id');
         }  

        if (Session::has('det_prop_disti')) {
            $det_prop_disti = Session::Get('det_prop_disti'); }   

        if (Session::has('det_prop_account')) {
            $det_prop_account = Session::Get('det_prop_account'); }   

        
        $det_prop_executor = Session::get('det_prop_executor');

        if ($eproptype == 'MT') { 
                         $budget_breakdown = DB::table('eprop_budget_breakdown_mt_v')                          
                                                        ->where('budget_year','=', $budget_year)
                                                        ->whereIn('brand_id',$det_prop_brand)             
                                                        ->whereIn('person_id',$det_prop_executor)
                                                        ->where('division_id', '=',  $division_id) 
                                                        ->whereIn('area_id',$det_prop_branch)     
                                                        ->whereIn('activity_id', $det_prop_activity)
                                                        ->whereIn('account_id',$det_prop_account)
                                                        ->whereIn('produk_id',$det_prop_varian)->get();
        }
        if ($eproptype == 'GT') { 
                         $budget_breakdown = DB::table('eprop_budget_breakdown_gt_v')
                                                        ->where('budget_year','=', $budget_year)             
                                                        ->whereIn('brand_id',$det_prop_brand)  
                                                        ->whereIn('person_id',$det_prop_executor)
                                                        ->where('division_id', '=',  $division_id)                                                            
                                                        ->whereIn('activity_id', $det_prop_activity)
                                                        ->whereIn('area_id',$det_prop_branch)
                                                        ->whereIn('distributor_id',$det_prop_account)
                                                        ->whereIn('produk_id',$det_prop_varian)->get();
        }


        Session::put('budget_breakdown', $budget_breakdown);

        $jmlrow = count($budget_breakdown);

        if ($jmlrow <> 0) {
                $budgetEach = ($request->input('total_buget')/$jmlrow);
        } else {
            $budgetEach = 0;
        }


        foreach ($budget_breakdown as $key => $value) {
            $value->budget_amount = round($budgetEach);
        }
 


        $cat_activity_id = [];
        $mt_account_id = [];
        $mt_region_id =[];
        $brand_id = [];

        if ($eproptype == 'MT') { 
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
        }

        if ($eproptype == 'GT') { 
                foreach ($budget_breakdown as $key => $value) {
                    $cat_activity_id [] = [
                            'cat_activity_id' => $value->kategory_id ];                        
                        $mt_account_id [] = [
                            'region_id' => $value->region_id ];
                        $brand_id [] = [
                            'brand_id' => $value->brand_id ];
                        }        
                        
                $budget_info = DB::table('budget_view')
                                                ->where('budget_year','=', $budget_year)  
                                                ->whereIn('cat_activity_id', $cat_activity_id)
                                                ->whereIn('region_id',$region_id)   
                                                ->whereIn('brand_id',$brand_id)->get();   
        }

        
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
                    $value->budget_amount = ($budget);
                }
            } 
        }

       // dd($combine_arr, $request->all(),Session::get('budget_breakdown'));

       // dd($budgetbreak, Session::get('budget_info'));
       
        $budgetApprove = [];
        $AmountApprv=0;
        $Total_amount=0;

        if (Session::has('eproptype')) {
                $eproptype = Session::has('eproptype'); 
        }

        foreach ($budget_info as $key => $value) {
            if ($eproptype == 'GT') {
                $filtered = $budgetbreak->where('kategory_id',$value->cat_activity_id)
                                       ->where('brand_code', $value->brand_code)
                                       ->where('region_id',$value->region_id); 
            }
            if ($eproptype == 'MT') {
                $filtered = $budgetbreak->where('kategory_id',$value->cat_activity_id)
                                       ->where('brand_code', $value->brand_code)
                                       ->where('account_name',$value->account_name); }

            $filtered->all();

            $AmountApprv = 0;
            foreach ($filtered as $filterkey => $filtervalue) {
                $AmountApprv = $AmountApprv + $filtervalue->budget_amount;
             }
             $outstanding = ($value->budget10perc - $AmountApprv);
             $minus_budget = 'N';
             if ($outstanding < 0) {
                $minus_budget = 'Y';
             }
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
                                  'begining_budget'  =>  $value->budget10perc,
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
                                  'outstanding'  => $outstanding,
                                  'budget_str_detail_id' => $value->budget_str_detail_id
                            ];
        }

 

        if ($eproptype == 'GT') {
            $result = $budgetbreak->groupBy(['region_id']);                
        }
        if ($eproptype == 'MT') {
                $result = $budgetbreak->groupBy(['account_name']); }

        $result->toArray();

        $targetEprop = DB::table('target_eprop_v')->get();

        return view ('eprop.eprop_sentra_4', compact('budgetApprove','Total_amount','targetEprop', 'minus_budget'));

    }


    public function PostFourthPage(Request $request) {

        /*if($request->hasFile('pilih_file')) {
            $file = $request->file('pilih_file');  
            $name = $filename;  
            $path = md5(time().$name).$name;  
            $mime = $file->getClientMimeType();  
            $size = $file->getClientSize();
            //$upload = Upload::create(compact('name', 'path', 'mime', 'size'));
            $path = Storage::putFile('cmo', $request->file('import_file'));
            //$path = $request['disti']->storeAs('public/storage/cmo', $filename);
            Session::put('cmo_file', $path);
        }*/


         //Get attached File
       // dd(request()->all());

        /*if($request->hasfile('import_file')) {
            dd($files = $request->file('pilih_file'));
            $path = $request->pilih_file->getRealPath(); 
            $file = $request->file('pilih_file');  
            
            $name = $filename;  
            $path = md5(time().$name).$name;  
            $mime = $file->getClientMimeType();  
            $size = $file->getClientSize();
            //$upload = Upload::create(compact('name', 'path', 'mime', 'size'));
            $path = Storage::putFile('cmo', $request->file('import_file'));
            //$path = $request['disti']->storeAs('public/storage/cmo', $filename);
            Session::put('cmo_file', $path);
        }*/


        //saved for eprop


        //Detail Eprop
         $det_prop_activity = Session::get('det_prop_activity');
         $det_prop_brand = Session::get('det_prop_brand');
         $det_prop_varian = Session::get('det_prop_varian');
         $det_prop_branch = Session::get('det_prop_branch'); 
         $det_prop_executor = Session::get('det_prop_executor');
         $det_prop_division = Session::get('det_prop_division');
         $det_prop_market = Session::get('det_prop_market');
         $det_prop_account = Session::get('det_prop_account');
         $det_prop_disti = Session::get('det_prop_disti');
         $det_prop_store = Session::get('det_prop_store');
         $eproptype = Session::get('eproptype');
         $budget_breakdown = Session::get('budget_breakdown');
         $budget_info =Session::get('budget_info');
         $FirstPageSentra  = Session::get('FirstPageSentra');

         //dd($budget_breakdown, $budget_info);
       //Get Eprop Number  

        foreach ($FirstPageSentra as $key => $value) {
             $division_id =   $value['division_id'];
             $year = intval($value['budget_year']);
             $monYear = strtoupper($value['start_date']->format('M/Y'));
        } 


        if ($request->submit_btn == 'SUBMIT'){
            $EpropNumX = DB::select('Select * From get_eprop_number(?,?,?)',[intval($division_id), ($year),$monYear]);
            foreach ($EpropNumX as $key => $value) {
                 $EpropNum = $value->eprop_number;
             }  

             //save Eprop trx_proposal_mst
            $SecondPageSentra=[];
            $id = DB::select("select nextval('trx_proposal_mst_id_seq') as header_id");
                foreach($id as  $value) {
                    $Eprop_id = $value->header_id;
                }
        } else {
            $SecondPageSentra=[];
            $id = DB::select("select nextval('trx_proposal_mst_draft_id_seq') as header_id");
                foreach($id as  $value) {
                    $Eprop_id = $value->header_id;
                    $EpropNum = $value->header_id . '[Draft]' ;
                } 
        }

        foreach ($FirstPageSentra as $key => $value) {
             $total_budget = str_replace('.','', $request->total_budget);
             $SecondPageSentra [] = ['id' => $Eprop_id,
                                'reco_no' => $value['reco_no'],
                                'eprop_no' => $EpropNum,
                                'budget_year' => $value['budget_year'],
                                'company_id' => $value['company_id'],
                                'division_id' => intval($value['division_id']),
                                'market_type_id' => intval($value['market_type_id']),
                                'description' => $value['description'],                       
                                'start_date' => $value['start_date'],
                                'end_date' => $value['end_date'],
                                'background' => $value['background'],
                                'objective' => $value['objective'],
                                'mechanism' => $value['mechanism'],
                                'estimate_cost_desc' => $value['estimate_cost'],
                                'kpi' => $value['kpi'],
                                'eprop_amount' => ($total_budget),
                                'attach_file' => null,
                                'target_name' => $request->target,
                                'target_estimasi' => $request->jmltarget,
                                'avg_sales_3month' => $request->avg_sales,
                                'sales_value' => $request->sales_value,
                                'sales_compare' => $request->sales_compare,
                                'cost_ratio' => $request->cost_ratio,
                                'apprv_status' => 'NOT APPROVE',
                                'originator_id' => Auth::user()->person_id,
                                'created_by' => Auth::user()->id ,
                                'updated_by' => Auth::user()->id  
                                ];
         }
 
           // dd($SecondPageSentra);
            DB::begintransaction(); 
            if ($request->submit_btn == 'SUBMIT'){
                    DB::table('trx_proposal_mst')->insert(($SecondPageSentra));
            } else {
                    DB::table('trx_proposal_mst_draft')->insert(($SecondPageSentra));
            }
           // dd($budget_breakdown);
        foreach ($budget_breakdown as $key => $value) {
                $Savebudget_breakdown = [
                                  'account_id'  => $value->account_id ,
                                  'account_name'  => $value->account_name ,
                                  'activity_id'  => $value->activity_id ,
                                  'activity_name'  => str_replace(',','/', $value->activity_name) ,
                                  'area'  => $value->area,                           
                                  'area_id'  => $value->area_id ,
                                  'brand_code'  => $value->brand_code ,
                                  'brand_id'  => $value->brand_id ,
                                  'brand_name'  => $value->brand_name ,
                                  'budget_amount'  => $value->budget_amount ,
                                  'budget_year'  => $value->budget_year ,   
                                  'company_code'  => $value->company_code ,
                                  'company_nama'  => $value->company_name , 
                                  'company_id'  => $value->company_id ,     
                                  'header_id'  => $Eprop_id ,
                                  'kategory_id'  => $value->kategory_id ,
                                  'kategory_name'  => $value->kategory_name ,
                                  'division_name'  => $value->division_name ,
                                  'region_id'  => $value->region_id ,
                                  'region'  => $value->region ,
                                  'subregion'  => $value->subregion ,
                                  'produk_sub_type'  => $value->product_sub_type ,
                                  'kategory_id'  => $value->kategory_id ,
                                  'produk_id'  => $value->produk_id ,
                                  'produk_name'  => $value->produk_name ,
                                  'row_number'  => $value->row_number,
                                  'person_id'  => $value->person_id ,
                                  'person_name'  => $value->person_name ,
                                  'position_id'  => $value->position_id ,
                                  'position_name'  => $value->position_name ,
                                  'subregion_id'  => $value->subregion_id ,
                                  'budget_str_det_id' => $value->budget_str_detail_id,
                                  'created_by' => Auth::user()->id,
                                  'updated_by' => Auth::user()->id  
                                ];

                if ($request->submit_btn == 'SUBMIT'){
                    DB::table('trx_det_prop_budgetbreak')->insert(array($Savebudget_breakdown));                
                } else {
                    DB::table('trx_det_prop_draf_budgetbreak')->insert(array($Savebudget_breakdown));    
                }
            }    
 
           // dd($budget_info);

         foreach ($budget_info as $key => $value) {
                $Save_budget_info = [
                          'header_id'  => $Eprop_id ,              
                          'company_code'  => $value->company_code ,
                          'company_name'  => $value->company_name ,
                          'budget_year'  => $value->budget_year ,
                          'company_id'  => $value->company_id ,
                          'total_budget'  => $value->total_budget ,
                          'budget_hdr_id'  => $value->budget_hdr_id ,
                          'cat_activity_id'  => $value->cat_activity_id ,
                          'brand_id'  => $value->brand_id ,
                          'mt_account_id'  => $value->mt_account_id ,
                          'begining_budget'  => $value->begining_budget ,
                          'current_budget'  => $value->current_budget ,
                          'region_id'  => $value->region_id ,
                          'budget_id'  => $value->id ,
                          'account_name'  => $value->account_name    ,
                          'type_market_code'  => $value->type_market_code ,
                          'region'  => $value->region ,
                          'brand_code'  => $value->brand_code ,
                          'brand_name'  => $value->brand_name ,
                          'product_type'  => $value->product_type ,
                          'product_sub_type'  => $value->product_sub_type ,
                          'kategory_name'  => $value->kategory_name ,
                          'budget10perc'  => $value->budget10perc ,
                          'budget_str_det_id' => $value->budget_str_detail_id,
                          'created_by'  => Auth::user()->id ,
                          'updated_by'  => Auth::user()->id
                    ];
                    if ($request->submit_btn == 'SUBMIT'){
                        DB::table('trx_det_prop_budgetinfo')->insert(array($Save_budget_info));    
                    } else {
                        DB::table('trx_det_prop_draf_budgetinfo')->insert(array($Save_budget_info));   
                    }
                }

         foreach ($det_prop_activity as $key => $value) {
             $Save_activity = [
                                'header_id' => $Eprop_id,
                                'activity_id' => $value['activity_id'],
                                'created_by'  => Auth::user()->id ,
                                'updated_by'  => Auth::user()->id
                            ];
                if ($request->submit_btn == 'SUBMIT'){
                    DB::table('trx_det_prop_activity')->insert(array($Save_activity));    
                } else {
                    DB::table('trx_det_prop_draf_activity')->insert(array($Save_activity));   
                }                        
         }
 
         foreach ($det_prop_brand as $key => $value) {
             $Save_brand = [
                                'header_id' => $Eprop_id,
                                'brand_id' => $value['brand_id'],
                                'created_by'  => Auth::user()->id ,
                                'updated_by'  => Auth::user()->id
                            ];
                if ($request->submit_btn == 'SUBMIT'){
                    DB::table('trx_det_prop_brand')->insert(($Save_brand));
                } else {
                    DB::table('trx_det_prop_draf_brand')->insert(($Save_brand));     
                }
         }

         foreach ($det_prop_varian as $key => $value) {
             $Save_varian = [
                                'header_id' => $Eprop_id,
                                'varian_id' => $value['varian_id'],
                                'created_by'  => Auth::user()->id ,
                                'updated_by'  => Auth::user()->id
                            ];
                if ($request->submit_btn == 'SUBMIT'){                              
                    DB::table('trx_det_prop_variant')->insert(($Save_varian));
                } else {
                    DB::table('trx_det_prop_draf_variant')->insert(($Save_varian));                    
                }
         }

         foreach ($det_prop_branch as $key => $value) {
           $Save_branch = [
                                'header_id' => $Eprop_id,
                                'area_id' => $value['branch_id'],
                                'created_by'  => Auth::user()->id ,
                                'updated_by'  => Auth::user()->id
                            ];
                if ($request->submit_btn == 'SUBMIT'){                             
                    DB::table('trx_det_prop_branch')->insert(($Save_branch));
                } else {
                    DB::table('trx_det_prop_draf_branch')->insert(($Save_branch));                    
                }
         }

         foreach ($det_prop_disti as $key => $value) {
           $Save_disti = [
                                'header_id' => $Eprop_id,
                                'cust_ship_id' => $value['distributor_id'],
                                'created_by'  => Auth::user()->id ,
                                'updated_by'  => Auth::user()->id
                            ];
                if ($request->submit_btn == 'SUBMIT'){             
                    DB::table('trx_det_prop_distributor')->insert(($Save_disti));
                } else {
                    DB::table('trx_det_prop_draf_distributor')->insert(($Save_disti));
                }                    
         }

         foreach ($det_prop_account as $key => $value) {
           $Save_account = [
                                'header_id' => $Eprop_id,
                                'account_id' => $value['account_id'],
                                'created_by'  => Auth::user()->id ,
                                'updated_by'  => Auth::user()->id
                            ];
            if ($request->submit_btn == 'SUBMIT'){                             
                DB::table('trx_det_prop_account')->insert(($Save_account));
            } else {
                DB::table('trx_det_prop_draft_account')->insert(($Save_account));                
            }
         }

         foreach ($det_prop_store as $key => $value) {
           $Save_store = [
                                'header_id' => $Eprop_id,
                                'outlet_id' => $value['store_id'],
                                'created_by'  => Auth::user()->id ,
                                'updated_by'  => Auth::user()->id
                            ];

            if ($request->submit_btn == 'SUBMIT'){ 
                DB::table('trx_det_prop_outlet')->insert(($Save_store));
            } else {
                DB::table('trx_det_prop_draf_outlet')->insert(($Save_store));
            } 
         }         

         foreach ($det_prop_executor as $key => $value) {
             $Save_store = [
                                'header_id' => $Eprop_id,
                                'person_id' => $value['executor_id'],
                                'created_by'  => Auth::user()->id ,
                                'updated_by'  => Auth::user()->id
                            ];

            if ($request->submit_btn == 'SUBMIT'){                 
                DB::table('trx_det_prop_executor')->insert(($Save_store));
            } else {
                DB::table('trx_det_prop_draf_executor')->insert(($Save_store));
            }
         }

       DB::commit();
       
        if ($request->submit_btn == 'SUBMIT'){    
            //Get hirarki id
            $hirarki_id = DB::table('mtl_hirarki')
                          ->where('hirarki_type_apps', '=', 'EPROP')
                          ->where('division_id', '=',$division_id )
                          ->get();

            //dd($hirarki_id);                      

           /* foreach ($hirarki_id as $key => $value) {
                $idhirarki = $value->id;
            }

            //** Get User Info
            $userInfo = Session::get('user_info');
            foreach ($userInfo as $key => $value) {
                $person_id = $value->person_id;
                $position = $value->position_id;
            }


            //Set workflow First
            //person_id, hirarki_id, position_id, cust_ship_id, subject
            $wf_check = DB::select('Select * From insert_approvers(?,?,?,?,?,?,?,?)',[$person_id, $idhirarki,$position,$division_id,$Eprop_id, $attach_file,$id]);

            //dd($person_id, $idhirarki,$position,$cust_ship_id,$subject, $message, $attach_file);
            
            foreach ($wf_check as $key => $value) {
                $wf_key = $value->wf_keyid;
            }
     
            if(isset($wf_check)) {

              //Get Email Notif 
              $wfApprover = DB::table('wf_approval')
                            ->where('sender_pos_id', '=', $position)
                            ->where('wf_key_id', '=', $wf_key) 
                            ->first();
         
            

                event(new UserApproverEvent($wfApprover)) ;   

                WFApprover::where('id','=', $wfApprover->id)->update(array('mail_status'=>'SENT'));    
              

                                
            }

            **/
        }
        
        if ($request->submit_btn == 'SUBMIT'){    
            $submit = 'SUBMIT';
        } else {
            $submit = 'DRAFT';
        }

         Session::forget('det_prop_activity');
         Session::forget('det_prop_brand');
         Session::forget('det_prop_varian');
         Session::forget('det_prop_branch'); 
         Session::forget('det_prop_executor');
         Session::forget('det_prop_division');
         Session::forget('det_prop_market');
         Session::forget('det_prop_account');
         Session::forget('det_prop_disti');
         Session::forget('det_prop_store');
         Session::forget('eproptype');
         Session::forget('budget_breakdown');
         Session::forget('budget_info');
         Session::forget('FirstPageSentra') ;  
         Session::forget('SecondPageSentra') ;  

         return view('eprop.eprop_sentra_5',compact('EpropNum', 'submit'));    
    }  


   /**
    * Previous Page Controller
    */

    public function SentraPrevPage1() {
        $user_info = Session::get('user_info');

        foreach ($user_info as  $value) {
          $person_name = $value->person_name;
          $person_id = $value->person_id;
          $user_group_akses = $value->group_akses_id;
         }
        
        $kat_aktivity = Session::get('user_kat_act');
        $brand = Session::get('user_brand');
        
        $brandCollection = collect($brand)->pluck('brand_id');

        //
        $katActivity = [];

        foreach ($kat_aktivity as  $value) {

          $katActivity = array_merge($katActivity,array($value->access_id));
        }

        $market = Session::get('user_market_type');
        $market_type = Session::get('det_prop_market');

        foreach ($market_type as $key => $value) {
            $market_type = $value['market_type_id'];
        }

        collect($market)->where('access_id',$market_type);
        foreach ($market as $key => $value) {
            $market_code = $value->access_code;
        }

        if ($market_code == 'NKA'){
            $executors = DB::table('excutor_mt_v')->select('person_id','name')->distinct()->get();
        } else {
            $executors = DB::table('person_area_view')->where('area_id','=', $branch_id)->where('executor','=', 'Y')
                            ->select('person_id', 'name')->distinct()->get();
        }
        //
        $division = Session::get('user_division');
           // dd($division);

        foreach ($division as $value) {
          $division_id = $value->access_id;
          break; 
        } 

        $ListVarian = DB::table('mtl_produk')->whereIn('brand_id', $brandCollection)->select('id', 'produk_name')->distinct()->get();
        
        $user_activity = DB::table('activity_division_v')->wherein('kategory_id',$katActivity)->where('division_id','=',$division_id)->select('activity_id', 'activity_name')->get();
            
        $yearNow = Carbon\Carbon::now()->year;
        $year5  = Carbon\Carbon::now()->addyear(5)->year;
            
        return view('eprop.eprop_sentra_1', compact('person_name', 'ListVarian','executors' ,'person_id','yearNow','year5', 'company', 'brand', 'user_activity'));
    }

    public function SentraPrevPage2() {

            
        $market_type = Session::get('det_prop_market');
        foreach ($market_type as $key => $value) {
           $market_type_id = $value['market_type_id'];
        }

         $user_market_type = Session::get('user_market_type');
         $user_market_type = collect($user_market_type);
         $market = $user_market_type->whereIN('access_id', $market_type_id);

         foreach ($market as $key => $value) {
            $market_code = $value->access_code;
         }
 
         $branches = Session::get('det_prop_branch');
         $branches = collect($branches);
         $i = 1;
         $cntBranch = count($branches);
         $filterbranch="";
        foreach ($branches as $val) {
                $filterbranch = $filterbranch . $val['branch_id'] ;
                if ($cntBranch <> $i) {
                    $filterbranch = $filterbranch . ','  ;  
                }
                $i=$i+1;

         }

          $filterbranch = '['. $filterbranch . ']' ;

        $customer_info = DB::table('customer_info')->wherein('cust_ship_id',function($query){
                                $query->select('access_id')->where('access_key','=','DISTRIBUTOR')->from('user_access_info_v')
                                ->where('user_id',auth::user()->id);
                            })->wherein('area_id', Session::get('det_prop_branch'))->get();  


        $store = DB::table('mtl_gt_store')->where('type_market_code', '=', $market_code )->get();

        if ($market_code == 'NKA') {

            if (Session::has('det_prop_executor')) {
            
                $account_mt = DB::table('mt_account_access_v')->whereIn('group_id', DB::table('mtl_persons')
                            ->whereIn('id',(Session::get('det_prop_executor')))->pluck('group_akses_id'))->distinct()->get();
            }

        } else {

                $account_mt = [];
        }
            return view('eprop.eprop_sentra_2_MT', compact('account_mt','store', 'customer_info'));
        
    }

    public function SentraPrevPage3() {

       $budget_breakdown = Session::get('budget_breakdown');
        
       $budget_info = Session::get('budget_info');

       return view('eprop.eprop_sentra_3', compact('budget_breakdown','budget_info'));  
        
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

    public function getExecutor(Request $request, $branch_id, $market_type)
    { 
        
        $market = Session::get('user_market_type');
        $market = collect($market)->where('access_id',$market_type);
        foreach ($market as $key => $value) {
            $market_code = $value->access_code;
        }

        if ($market_code == 'NKA'){
            $executors = DB::table('excutor_mt_v')->select('person_id','name')->distinct()->get();
        } else {
            $executors = DB::table('person_area_view')->where('area_id','=', $branch_id)->where('executor','=', 'Y')
                            ->select('person_id', 'name')->distinct()->get();
        }

        return Response::json($executors);

    }

    public function getVarian($brand_id)
    { 

    	$Varian = DB::table('mtl_produk')->where('brand_id','=', $brand_id)
    				->select('id', 'produk_name')->distinct()->get();

        return Response::json($Varian);

    }

    public function getBudgetBreak($total_budget) 
    {
        $budgetbreak = Session::get('budget_breakdown');

        $jmlrow = count($budgetbreak);
        $budgetEach = ($total_budget/$jmlrow);

        foreach ($budgetbreak as $key => $value) {
            $value->budget_amount = $budgetEach;
        }

        return Response::json($budgetEach);
    }

    public function getEpropTarget($division) 
    {

      $user_division = Session::get('user_division');
      $division_code = $user_division->where('access_id',$division)->pluck('access_code');
      foreach ($division_code as $key => $value) {
        $divcode = $value;
        # code...
      }

      $target = DB::table('target_eprop_v')->where('divisions','LIKE', "%{$divcode}%")
            ->select('target_name', 'divisions')->distinct()->get();

        return Response::json($target);
    }
}
