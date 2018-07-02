<?php

namespace App\Http\Controllers\backup_ctrl;


use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use App\MTLCompany;
use Validator;
use Carbon;
use Session;
use Excel;
use Auth;
use DB;


class BudgetController extends Controller
{
    /**
    * Return View file
    * @var array
    */

    protected $x;
    protected $MsgErr;  
    protected $messageErr;
    protected $total_budget;
    protected $budget_year;
    protected $company_id;
    protected $budget_detail;
    protected $budget_master;

    public function upload_budget_str() {
    	$yearNow  = Carbon\Carbon::now()->year;
    	$year5  = Carbon\Carbon::now()->addyear(5)->year;	
    	

    	return view('budget.upload_budget_str', compact('yearNow', 'year5'));
    }

    public function ImportBudget(Request $request) {
    	
    	$x = 0;
    	$total_budget = 0;


    	//clear Session
        Session::forget('budget_detail');
        Session::forget('budget_master');
        Session::forget('ErrorExcel');	
        //end Clear Session

        $budget_detail=[];
        $budget_master=[];
        $company_name='';


        if($request->hasFile('import_file')) {
        	$path = $request->file('import_file')->getRealPath(); 
            $data = Excel::load($path, function($reader) {
                    $reader->ignoreEmpty();
                    $results = $reader->get()->toArray();
            })->get();

            dd($data);

            $company = DB::table('mtl_company')->where('id','=',$request->company_id)->first();

            $company_name = $company->company_name;

            $company_id = 0;

            if(!empty($data) && $data->count()){
            	//get data using foreach
                foreach ($data as $key => $value){
                	$this->$x = $x;
                	//Get Company_id
                	if ($x==1) {   
                		if (!empty($value['3'])) {
                		    $input = array('company_id'   =>  $value['3']);
                		} else {
                			$input = array('company_id'   =>  null);
                		}
                        $rules = array('company_id' => 'required|numeric');
                        $messages = array('required' => 'kolom C, Baris 3 (company ID harus diisi) ,Upload Template Input budget untuk company ID yang benar. ');

                        $validation = Validator::make($input, $rules, $messages); 
                        if ($validation->fails()){ //validation fail
                            $failedRules = $validator->failed();
    						if(isset($failedRules['company_id']['required'])) {
    							$messages = array('Err' => 'kolom C, Baris 3 (company ID harus diisi) ,Upload Template Input budget untuk company ID yang benar.');
    						}
    						if(isset($failedRules['company_id']['numeric'])) {
    							$messages = array('Err' => 'Company ID harus dalam numeric/angka ');
    						} 
                        } else {
                        	$company_id =  $value['3'] ;
                        }

                	}
                	//get Budget Year
                	if ($x==2) {
                		$yearNow  = Carbon\Carbon::now()->year;
    					$year5  = Carbon\Carbon::now()->addyear(5)->year;
                		
    					if (!empty($value['4'])) {
    					    $input = array('budget_year'   =>  $value['4']);
    					} else {
    						$input = array('budget_year'   =>  null);
    					}
                        $rules = ['budget_year' => 'required|numeric|exists:mtl_budget|between:' . $yearNow . ',' . $year5];	

                        $messages = array('required' => 'kolom C, Baris 4 Budget year tidak boleh kosong',
                                          'exists'  => 'company ID tidak ada di master company, Upload Template Input budget untuk kode company ID yang benar. ',
                                      	  'between' => 'Budget year harus dalam range ' . $yearNow . ' dan ' . $year5);

                        $validation = Validator::make($input, $rules, $messages); 

                        if ($validation->fails()){ //validation fail
                            $failedRules = $validator->failed();
    						if(isset($failedRules['budget_year']['exists'])) {
    							$messages = array('Err' => 'Budget Tahun  '  . $value['2'] . ' Sudah ada didatabase!');
    						}
    						if(isset($failedRules['budget_year']['between'])) {
    							$messages = array('Err' => 'Budget year harus dalam range ' . $yearNow . ' dan ' . $year5);
    						}
    						if(isset($failedRules['budget_year']['numeric'])) {
    							$messages = array('Err' => 'Budget year harus dalam numeric/angka ');
    						}

                        } else { 
                        	$budget_year =  $value['4'];
                        }

                	}
                	//Get Details Data
                	if ($x>5) {
                		//brand_id
                		if (!empty($value['2'])) {
                		     $input = array('brand_id' => $value['2']);
                		     $rules = ['brand_id' => 'required|numeric|exists:mtl_brand_produk,id'];                		
                		     $messages = array('required' => 'brand id tidak boleh kosong',
                		                		'numeric' => 'type brand id harus numeric',
                		                		'exists'  => 'brand ID tidak ada di master brand product, Upload Template Input budget untuk  brand id yang benar. ');
                		
                		    $validation = Validator::make($input, $rules, $messages);
                		    if($validation->fails()) {
                		    	$MsgErr[] = ($messages);
							 } else {
                		     	$brand_id = $value['2']; 
                		     }
                		} else {
                			$messages = ['Err' => 'brand_id tidak boleh kosong'];
                			$MsgErr[] = ($messages);	
                		}
                		//Category Activity ID
                		if (!empty($value['5'])) {
                			$input = array('cat_activity_id' => $value['5']);
                			$rules = ['cat_activity_id' => 'required|numeric|exists:mtl_kategory_activity,id'];
                		
                		    $messages = array('required' => 'Kategory Activity tidak boleh kosong',
                		                	  'numeric' => 'Kategory Activity harus numeric',
                		                	  'exists'  => 'Kategory Activity tidak ada di master Kategory Activity, Upload Template Input budget untuk  Kategory Activity yang benar. ');
                		
                		    $validation = Validator::make($input, $rules, $messages);
                		    if($validation->fails()) {
                		    	$MsgErr[] = ($messages);
							} else {
                		     	$cat_activity_id = $value['5']; 
                		    }

                		} else {
                			$messages = ['Err' => 'Kategory Activity tidak boleh kosong'];
                			$MsgErr[] = ($messages);
                		}
                		//Category Region ID
                		if (!empty($value['7'])) {
                			$input = array('region_id' => $value['7']);
                			$rules = ['region_id' => 'required|numeric|exists:mtl_region,id'];
                		
                		    $messages = array('required' => 'Region ID tidak boleh kosong',
                		                	  'numeric' => 'Region ID harus numeric',
                		                	  'exists'  => 'Region ID tidak ada di master Kategory Activity, Upload Template Input budget untuk Region ID yang benar. ');
                		
                		    $validation = Validator::make($input, $rules, $messages);
                		    if($validation->fails()) {
                		    	$MsgErr[] = ($messages);
							} else {
                		     	$region_id = $value['7']; 
                		    }
                		}  

                		if (!empty($value['9'])) {
                			$input = array('mt_account_id' => $value['9']);
                			$rules = ['mt_account_id' => 'required|numeric|exists:mtl_mt_account,id'];
                		
                		    $messages = array('required' => 'Account ID tidak boleh kosong',
                		                	  'numeric' => 'Account ID harus numeric',
                		                	  'exists'  => 'Account ID tidak ada di master Kategory Activity, Upload Template Input budget untuk Account ID yang benar. ');
                		
                		    $validation = Validator::make($input, $rules, $messages);
                		    if($validation->fails()) {
                		    	$MsgErr[] = ($messages);
							} else {
                		     	$region_id = $value['9']; 
                		    }
                		}  
                	
                		if (!empty($value['11'])) {
                			$input = array('budget_amount' => $value['11']);
                			$rules = ['budget_amount' => 'required|numeric'];
                		
                		    $messages = array('required' => 'Amount Budget tidak boleh kosong, Input 0 jika tidak ada budget di tahun input budget',
                		                	  'numeric' => 'Amount Budget harus numeric');
                		                	  
                		    $validation = Validator::make($input, $rules, $messages);
                		    if($validation->fails()) {
                		    	$MsgErr[] = ($messages);
							} else {
                		     	$budget_amount = $value['11'];
                		     	$total_budget = $total_budget + $budget_amount; 
                		    }
                		}

                		if (!empty($value['2'])) {
                			if (!empty($value['9']) and empty($value['7'])){
                			        $budget_detail[] = [
                			        	'cat_activity_id' => $value['5'],
                			        	'activity' => $value['6'],
                			            'brand_id' => $value['2'],
                			            'brand_code' => $value['3'],
                			            'brand_name' => $value['4'],
                			            'mt_account_id' => $value['9'],
                			            'account_name' => $value['10'],
                			            'region_id' => null,
                			            'region_name' => null,
                			            'budget_amount' => $value['11'],
                			            'created_by' => auth::id(),
                			            'updated_by' => auth::id()
                			];}

                			if (!empty($value['7']) and empty($value['9'])){
                			        $budget_detail[] = [
                			        	'cat_activity_id' => $value['5'],
                			        	'activity' => $value['6'],
                			            'brand_id' => $value['2'],
                			            'brand_code' => $value['3'],
                			            'brand_name' => $value['4'],                			            
                			            'mt_account_id' => null,
                			            'account_name' => null,                			            
                			            'region_id' => $value['7'],
                			            'region_name' => $value['8'],
                			            'budget_amount' => $value['11'],
                			            'created_by' => auth::id(),
                			            'updated_by' => auth::id()
                			];}

                			if (empty($value['7']) and empty($value['9'])){
                			        $budget_detail[] = [
                			        	'cat_activity_id' => $value['5'],
                			        	'activity' => $value['6'],
                			            'brand_id' => $value['2'],
                			            'brand_code' => $value['3'],
                			            'brand_name' => $value['4'],
                			            'mt_account_id' => null,
                			            'account_name' => null,
                			            'region_id' => null,
                			            'region_name' => null,
                			            'budget_amount' => $value['11'],
                			            'created_by' => auth::id(),
                			            'updated_by' => auth::id()
                			];}
                		}



                	} 

                	$this->$x = $this->$x  + 1;
                    $x=$x+1;
                }


             	$budget_master[] = [
             							'budget_year' => $budget_year,
             							'total_budget' => $total_budget,
             							'company_id' => $company_id,
             							'created_by' => auth::id(),
             							'updated_by' => auth::id()
             					   ];   

  				Session::put('budget_detail', $budget_detail);
		        Session::put('budget_master', $budget_master);
		        if (!empty($MsgErr)) {
		        		        Session::put('ErrorExcel',$MsgErr );}           					   
            }


            return view('budget.budget_sentralisasi', compact('company_name'));
        }


    } 

    public function Save_Budget(Request $request) {

    	$budget_detail = Session::get('budget_detail');
        $budget_master = Session::get('budget_master'); 

        $save_mst_budget = []; 
        $budget_header_id = DB::select("select nextval('mtl_budget_id_seq') as header_id");

        foreach($budget_header_id as  $value) {
            $id = $value->header_id;
        }

        //dd($budget_master);
        foreach($budget_master as $value) {
        	$budget_year = $value['budget_year'];
        	$company_id = $value['company_id'];
        	$total_budget = $value['total_budget'];
        }

        $save_mst_budget = [
        	'id' => $id,
        	'budget_year' => $budget_year,
        	'company_id' => $company_id,
        	'total_budget' => $total_budget,
        	'created_by' => Auth::user()->id,
        	'updated_by' => Auth::user()->id
        ];

        DB::begintransaction(); 

        DB::table('mtl_budget')->insert(array($save_mst_budget));

        $save_detail[]='';

        //Save Order Detail
        $i=1;
        foreach ($budget_detail as $value) {

            $save_detail = [
             'budget_hdr_id' => $id,
             'cat_activity_id' => $value['cat_activity_id'],
             'brand_id' => $value['brand_id'],
             'mt_account_id' => $value['mt_account_id'],
             'region_id' => $value['region_id'], 
             'budget_amount' => $value['budget_amount'],
             'created_by' => Auth::user()->id,
             'updated_by' => Auth::user()->id];
             
             DB::table('mtl_budget_str_detail')->insert(array($save_detail));

             $i=$i+1;
             $save_detail[] ="";
        }

        DB::commit();

        $Success = "Budget Tahun" . $budget_year . 'berhasil di Simpan!';


        Session::forget('budget_detail');
        Session::forget('budget_master'); 
        Session::forget('ErrorExcel');

        return view('budget.upload_budget_str');

    }
}
