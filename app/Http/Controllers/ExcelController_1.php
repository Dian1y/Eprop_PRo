<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
//use Illuminate\Support\Facades\Redirect;
use App\Product;
use Session;
use Excel;
use Illuminate\Support\Facades\Auth;
use DB;

class ExcelController extends Controller
{
	/**
    * Return View file
    * @var array
    */

    protected $x;
    protected $messageErr;
    protected $validateErr;
    protected $ttl_dlv_qty1;
    protected $ttl_dlv_qty2;
    protected $ttl_dlv_qty3;
    protected $ttl_dlv_qty4;
    protected $prices;
    protected $detailOrder;
    protected $rekapOrder;
    protected $FleetErr;
    protected $trx_dtl;
    protected $trx_rkp;

    public function importExport() 
    { 
    	return view('upload_excel');
    }

    /**
    * File Export Code
    *
    * @var array
    */

    public function downloadExcel(Request $request, $type)
    {
    	$data = Product::get()->toArray();
    	return Excel::create('itsolustionstuff_example',function($excel) use ($data) {
    		$excel->sheet('mySheet', function($sheet) use ($data)
    		{
    				$sheet->fromArray($data);
    		});
    	})->download($type); 
    } 

    /**
    * Import file into database code
    *
    * @var array
    */

    public function importExcel(Request $request){
    	$ListFleet=[];
        dd('import');
        print_r('Delete sessions...');
    	if($request->hasFile('import_file')) {
    		//get excel file
            $path = $request->file('import_file')->getRealPath(); 
            $data = Excel::load($path, function($reader) {
                    $reader->ignoreEmpty();
                    $results = $reader->get()->toArray();
            })->get();
            //check if $data is not empty
            $x = 0;
            $ttl_dlv_qty1=0;
            $ttl_dlv_qty2=0;
            $ttl_dlv_qty3=0;
            $ttl_dlv_qty4=0;
            if(!empty($data) && $data->count()){
            	//get group harga 
                $cust_order = DB::table('customer_info')->where('cust_ship_id','=', $request['disti'])
                              ->get();
                foreach($cust_order as $key =>$custdata){
                    $group_harga =$custdata->group_harga;
                } 

                $item_prices = DB::table('mtl_harga')->where('group_harga', $group_harga)->get();  
                $this->prices = $item_prices;

                //get data using foreach
                foreach ($data as $key => $value){
                	$this->$x = $x;
                    //Validation 
                    if($x == 3) { //check Periode
                    	$input = ['period'   =>  $value['6']];
                        $rules = ['period' => 'required|date'];
                        $messages = [ 'required' => 'Periode CMO harus Di isi',
                                       'date'  => 'Format type Periode di Excel harus Format Date!'];
                        $validator = Validator::make($input, $rules, $messages); 
                        if (!$validator->fails()){ //validation not fail
                        	$period = $value['6']->format('Y-m-d');
                        }	
                    } //cek periode
                    if($x == 7){ //Delivery Date
						if(!empty($value['15'])){ //delivery date 1
							$input = ['delivery_date_1'   =>  $value['15']];
	                        $rules = ['delivery_date_1' => 'date'];
	                        $messages = [ 'date'  => 'Format Tanggal Pengiriman ke-1 (Kolom 15/Kolom R di Excel) harus Format Date!'];
	                        $validator = Validator::make($input, $rules, $messages);  
	                        if (!$validator->fails()){ //success
	                        	$delivery_date_1 = $value['15']->format('Y-m-d');
	                        } //success                                 
						} //delivery date 1
						if(!empty($value['16'])){ //delivery date 2
							$input = ['delivery_date_2'   =>  $value['16']];
	                        $rules = ['delivery_date_2' => 'date'];
	                        $messages = [ 'date'  => 'Format Tanggal Pengiriman ke-2 (Kolom 16/Kolom S di Excel) harus Format Date!'];
	                        $validator = Validator::make($input, $rules, $messages);  
	                        if (!$validator->fails()){ //success
	                        	$delivery_date_1 = $value['16']->format('Y-m-d');
	                        } //success                                 
						} //delivery date 2
						if(!empty($value['17'])){ //delivery date 3
							$input = ['delivery_date_3'   =>  $value['17']];
	                        $rules = ['delivery_date_3' => 'date'];
	                        $messages = [ 'date'  => 'Format Tanggal Pengiriman ke-3 (Kolom 17/Kolom T di Excel) harus Format Date!'];
	                        $validator = Validator::make($input, $rules, $messages);  
	                        if (!$validator->fails()){ //success
	                        	$delivery_date_1 = $value['17']->format('Y-m-d');
	                        } //success                                 
						} //delivery date 3
						if(!empty($value['18'])){ //delivery date 4
							$input = ['delivery_date_4'   =>  $value['18']];
	                        $rules = ['delivery_date_4' => 'date'];
	                        $messages = [ 'date'  => 'Format Tanggal Pengiriman ke-4 (Kolom 18/Kolom U di Excel) harus Format Date!'];
	                        $validator = Validator::make($input, $rules, $messages);  
	                        if (!$validator->fails()){ //success
	                        	$delivery_date_4 = $value['18']->format('Y-m-d');
	                        } //success                                 
						} //delivery date 4
                    } //Delivery Date                    
                    if($x > 7){ //details 
                    	if(!empty($delivery_date_1) and !empty($kodeitem)) { //delivery Qty 1
                    		$input = ['delivery_qty1'   =>  $value['15']];
                    		   if (!empty($kodeitem)) { //kodeitem Ada
                    		   		$rules = ['delivery_qty1' => 'required',
				                		      'delivery_qty1' => 'numeric'];		
                    		   } //kodeitem ada
                    		   else { //kodeitem Tdk Ada
                    		   		$rules = ['delivery_qty1' => 'numeric'];	
                    		   } //kodeitem Tdk Ada
                    		   $validator = Validator::make($input, $rules, $messages);  
		                        if (!$validator->fails()){ //success
		                        	$delivery_qty1 = $value['15'];
                                    $ttl_dlv_qty1 = $ttl_dlv_qty1 + $delivery_qty1;
		                        } //success  

                    	} //delivery Qty 1
                    	if(!empty($delivery_date_2) and !empty($kodeitem)) { //delivery Qty 2
                    		$input = ['delivery_qty2'   =>  $value['16']];
                    		   if (!empty($kodeitem)) { //kodeitem Ada
                    		   		$rules = ['delivery_qty2' => 'required',
				                		      'delivery_qty2' => 'numeric'];		
                    		   } //kodeitem ada
                    		   else { //kodeitem Tdk Ada
                    		   		$rules = ['delivery_qty2' => 'numeric'];	
                    		   } //kodeitem Tdk Ada
                    		   if (!$validator->fails()){ //success
		                        	$delivery_qty2 = $value['16'];
                                    $ttl_dlv_qty2 = $ttl_dlv_qty2 + $delivery_qty2;
		                        } //success  

                    	} //delivery Qty 2
                    	if(!empty($delivery_date_3) and !empty($kodeitem)) { //delivery Qty 3
                    		$input = ['delivery_qty3'   =>  $value['17']];
                    		   if (!empty($kodeitem)) { //kodeitem Ada
                    		   		$rules = ['delivery_qty' => 'required',
				                		      'delivery_qty3' => 'numeric'];		
                    		   } //kodeitem ada
                    		   else { //kodeitem Tdk Ada
                    		   		$rules = ['delivery_qty3' => 'numeric'];	
                    		   } //kodeitem Tdk Ada
                    		   if (!$validator->fails()){ //success
		                        	$delivery_qty3 = $value['17'];
                                    $ttl_dlv_qty3 = $ttl_dlv_qty3 + $delivery_qty3;
		                        } //success  
                    	} //delivery Qty 3
                    	if(!empty($delivery_date_4) and !empty($kodeitem)) { //delivery Qty 4
                    		$input = ['delivery_qty4'   =>  $value['18']];
                    		   if (!empty($kodeitem)) { //kodeitem Ada
                    		   		$rules = ['delivery_qty' => 'required',
				                		      'delivery_qty4' => 'numeric'];		
                    		   } //kodeitem ada
                    		   else { //kodeitem Tdk Ada
                    		   		$rules = ['delivery_qty4' => 'numeric'];	
                    		   } //kodeitem Tdk Ada
                    		   if (!$validator->fails()){ //success
		                        	$delivery_qty4 = $value['18'];
                                    $ttl_dlv_qty4 = $ttl_dlv_qty4 + $delivery_qty4;
		                        } //success  

                    	} //delivery Qty 4
                    } //Details
					//End of Validation	
					//Insert Into Array Trx Details
					if ((!empty($delivery_date_1) and !empty($kodeitem))) { //insert 1
						$trx_dtl[] = [
                        				'kode_item' => $value['1'], 
                                        'kode_descr' => $value['2'], 
                                        'uom_trx' => $value['3'], 
                                        'stock_awal_cycle' => $value['5'], 
                                        'sp' => $value['6'],
                                        'total_stock' => $value['7'],
                                        'est_sales' => $value['8'],
                                        'est_stock_akhir' => $value['9'],
                                        'est_sales_nextmonth' => $value['10'],
                                        'buffer' => $value['11'],
                                        'average' => $value['12'],
                                        'doi' => $value['13'],
                                        'cmo' =>  $value['14'],
                                        'delivery_date' => $delivery_date_1,
                                        'delivery_num' => '1',
                                        'delivery_qty' => $value['15'],
                                        'total_cmo' => $value['19'],
                                        'price_crt' => $this->itemPrice($value['1']),
                                        'subtotal' => ($this->itemPrice($value['1']) * $value['15']),
                                        'disc_percent' => $this->itemdisc($value['1']),
                                        'disc_value' => $this->getDiscVal($value['1'],$value['15']),
                                        'netto' => $this->getNett($value['1'],$value['15']),
                                        'ppn' => $this->getPPN($value['1'],$value['15']),
                                        'extended_price' => $this->getExtAmount($value['1'],$value['15']) ];
					} //insert 1
					if ((!empty($delivery_date_1) and !empty($kodeitem))) { //insert 2
						$trx_dtl[] = [
                                        'kode_item' => $value['1'], 
                                        'kode_descr' => $value['2'], 
                                        'uom_trx' => $value['3'], 
                                        'stock_awal_cycle' => $value['5'], 
                                        'sp' => $value['6'],
                                        'total_stock' => $value['7'],
                                        'est_sales' => $value['8'],
                                        'est_stock_akhir' => $value['9'],
                                        'est_sales_nextmonth' => $value['10'],
                                        'buffer' => $value['11'],
                                        'average' => $value['12'],
                                        'doi' => $value['13'],
                                        'cmo' =>  $value['14'],
                                        'delivery_date' => $delivery_date_2,
                                        'delivery_num' => '2',
                                        'delivery_qty' => $value['16'],
                                        'total_cmo' => $value['19'],
                                        'price_crt' => $this->itemPrice($value['1']),
                                        'subtotal' => ($this->itemPrice($value['1']) * $value['16']) ,
                                        'disc_percent' => $this->itemdisc($value['1']),
                                        'disc_value' => $this->getDiscVal($value['1'],$value['16']),
                                        'netto' => $this->getNett($value['1'],$value['16']),
                                        'ppn' => $this->getPPN($value['1'],$value['16']),
                                        'extended_price' => $this->getExtAmount($value['1'],$value['16']) ];
					} //Insert 2
					if ((!empty($delivery_date_3) and !empty($kodeitem))) { //Insert 3
						$trx_dtl[] = [
                                        'kode_item' => $value['1'], 
                                        'kode_descr' => $value['2'], 
                                        'uom_trx' => $value['3'], 
                                        'stock_awal_cycle' => $value['5'], 
                                        'sp' => $value['6'],
                                        'total_stock' => $value['7'],
                                        'est_sales' => $value['8'],
                                        'est_stock_akhir' => $value['9'],
                                        'est_sales_nextmonth' => $value['10'],
                                        'buffer' => $value['11'],
                                        'average' => $value['12'],
                                        'doi' => $value['13'],
                                        'cmo' =>  $value['14'],
                                        'delivery_date' => $delivery_date_3,
                                        'delivery_num' => '3',
                                        'delivery_qty' => $value['17'],
                                        'total_cmo' => $value['19'] ,
                                        'price_crt' => $this->itemPrice($value['1']),
                                        'subtotal' => ($this->itemPrice($value['1']) * $value['17']),
                                        'disc_percent' => $this->itemdisc($value['1']),
                                        'disc_value' => $this->getDiscVal($value['1'],$value['17']),
                                        'netto' => $this->getNett($value['1'],$value['17']),
                                        'ppn' => $this->getPPN($value['1'],$value['17']),
                                        'extended_price' => $this->getExtAmount($value['1'],$value['17']) ];
					} //Insert 3
					if ((!empty($delivery_date_4)and !empty($kodeitem))) { //Insert 4
						$trx_dtl[] = [
                                        'kode_item' => $value['1'], 
                                        'kode_descr' => $value['2'], 
                                        'uom_trx' => $value['3'], 
                                        'stock_awal_cycle' => $value['5'], 
                                        'sp' => $value['6'],
                                        'total_stock' => $value['7'],
                                        'est_sales' => $value['8'],
                                        'est_stock_akhir' => $value['9'],
                                        'est_sales_nextmonth' => $value['10'],
                                        'buffer' => $value['11'],
                                        'average' => $value['12'],
                                        'doi' => $value['13'],
                                        'cmo' =>  $value['14'],
                                        'delivery_date' => $delivery_date_4,
                                        'delivery_num' => '4',
                                        'delivery_qty' => $value['18'],
                                        'total_cmo' => $value['19'],
                                        'price_crt' => $this->itemPrice($value['1']),
                                        'subtotal' => ($this->itemPrice($value['1']) * $value['18']),
                                        'disc_percent' => $this->itemdisc($value['1']),
                                        'disc_value' => $this->getDiscVal($value['1'],$value['18']),
                                        'netto' => $this->getNett($value['1'],$value['18']),
                                        'ppn' => $this->getPPN($value['1'],$value['18']),
                                        'extended_price' => $this->getExtAmount($value['1'],$value['18']) ];
					} //Insert 4
                //end for each $data
                }
                 	$this->$x = $this->$x  + 1;
                    $x=$x+1;             	
            } //end $excel Data  
    	} //end has File excel

    	$FleetCrt = (Session::get('minFleetCrt'))->toArray();
        $FleetVal = (Session::get('minFleetVal'))->toArray();
        $FleetType = (Session::get('minFleetType'))->toArray();

        foreach($FleetCrt as $values){
                $minFleetCrt = $values->value_name;
        }
        foreach ($FleetVal as $values){
                $minFleetVal = $values->value_name;
        }
        foreach($FleetType as $values){
                $minFleetType = $values->value_name;
        } 
        $cust_order = DB::table('customer_info')->where('cust_ship_id','=', $request['disti'])->get();         
        foreach($cust_order as $key =>$custdata){ 
                    $moq =$custdata->MOQ;
                    $fleet= $custdata->default_fleet;
                } 
         //put date delivery in array
        if(!empty($delivery_date_1)){
                $trx_rkp[] = ['datedlv' => $delivery_date_1,
                            'num'  => 1,
                            'total_crt' => $ttl_dlv_qty1,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_2)){
                $trx_rkp[] = ['datedlv' => $delivery_date_2,
                            'num'  => 2,
                            'total_crt' => $ttl_dlv_qty2,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_3)){
                $trx_rkp[] = ['datedlv' => $delivery_date_3,
                            'num'  => 3,
                            'total_crt' => $ttl_dlv_qty3,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_4)){
                $trx_rkp[] = ['datedlv' => $delivery_date_4,
                            'num'  => 4,
                            'total_crt' => $ttl_dlv_qty4,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }

        $this->rekapOrder = $trx_rkp;
        $this->detailOrder = $trx_dtl;
        $this->validateErr = $messageErr;

        $getFleet= $this->getfleet();
         
        Session::put('trx_detail',$trx_dtl);
        Session::put('cust_order', $cust_order);
        Session::put('DetailFleet', $getFleet);
        return view('orders.upload_order'); 
    } //End of importExcel




    public function importExcel(Request $request){ 

        $ListFleet=[];
        if($request->hasFile('import_file')) { 
            //get excel file
            $path = $request->file('import_file')->getRealPath(); 
            $data = Excel::load($path, function($reader) {
                    $reader->ignoreEmpty();
                    $results = $reader->get()->toArray();
            })->get();
            //check if $data is not empty
            $x = 0;
            $ttl_dlv_qty1=0;
            $ttl_dlv_qty2=0;
            $ttl_dlv_qty3=0;
            $ttl_dlv_qty4=0;
            if(!empty($data) && $data->count()){
                print_r($x);
                //get group harga 
                $cust_order = DB::table('customer_info')->where('cust_ship_id','=', $request['disti'])
                              ->get();

                foreach($cust_order as $key =>$custdata){
                    $group_harga =$custdata->group_harga;
                }        
                $item_prices = DB::table('mtl_harga')->where('group_harga', $group_harga)->get();  
                $this->prices = $item_prices;
            
                //get data using foreach

                foreach ($data as $key => $value){
                    $this->$x = $x;
                    //cek for period format date type 
                    if($x == 3){ //Periode
                        //echo('$x == 3');
                        $validator = Validator::make(
                                        ['period' => $value['6']],
                                        ['period' => 'required|date']
                                     );    
                        //if validate fails
                        if ($validator->fails()){
                                 $messageErr[] = ['errField' => $value['6'], 'errMsg' => $validator->messages()]; 
                                  
                             } else {
                                 $period = $value['6']->format('Y-m-d');
                        }

                                            //end $x == 3
                    }
                    if($x == 7){//Delivery Date
                        //echo('$x == 7');
                        //delivery date 1
                        if(!empty($value['15'])) {
                            $validator = Validator::make(
                                                ['delivery_date_1' => $value['15']],
                                                ['delivery_date_1' => 'required|date']
                                         );   
                            //if fails ..
                            if ($validator->fails()){
                                $messageErr[] = ['errField' => $value['15'], 'errMsg' => $validator->messages()];
                             } else {
                                $delivery_date_1 = $value['15']->format('Y-m-d'); 
                             }  
                         //delivery date 1    
                        }    
                        //delivery date 2
                        if(!empty($value['16'])) {
                            $validator = Validator::make(
                                                ['delivery_date_2' => $value['16']],
                                                ['delivery_date_2' => 'required|date']
                                         );   
                            //if fails ..
                            if ($validator->fails()){
                                $messageErr[] = ['errField' => $value['16'], 'errMsg' => $validator->messages()];
                             } else {
                                $delivery_date_2 = $value['16']->format('Y-m-d');
                             }  
                         //delivery date 2   
                        }   
                        //delivery date 3
                        if(!empty($value['17'])) {
                            $validator = Validator::make(
                                                ['delivery_date_3' => $value['17']],
                                                ['delivery_date_3' => 'required|date']            
                                         );   
                            //if fails ..
                            if ($validator->fails()){
                                $messageErr[] = ['errField' => $value['17'], 'errMsg' => $validator->messages()];
                             } else {
                                $delivery_date_3 = $value['17']->format('Y-m-d');
                             }  
                         //delivery date 3   
                        }   
                        //delivery date 4
                        if(!empty($value['18'])) {
                            $validator = Validator::make(
                                                ['delivery_date_4' => $value['18']],
                                                ['delivery_date_4' => 'required|date']
                                         );   
                            //if fails ..
                            if ($validator->fails()){
                                $messageErr[] = ['errField' => $value['18'], 'errMsg' => $validator->messages()];
                             } else {
                                $delivery_date_4 = $value['18']->format('Y-m-d');
                             }  
                         //delivery date 3   
                        }    

                    //end $x == 7    
                    }

                    //check for details 
                    if($x > 7){
                        //cek kodeitem 
                        if(!empty($value['1'])) {
                            $validator = Validator::make(
                                            ['kodeitem' => $value['1']], 
                                            ['kodeitem' => 'required|exists:mtl_items']
                                        );
                            if ($validator->fails()){ 
                                $messageErr[] = ['errField' => $value['1'] , 'errMsg' => $validator->messages()];
                                //dd($validator->messages()->kodeitem);
                             } else {
                                $kodeitem = $value['1'];
                            
                            }  
                            //end kodeitem
                        }    
                            //cek delivery 1 qty must be numeric
                            if(!empty($delivery_date_1) and !empty($kodeitem)){
                                $validator = validator::make(
                                                ['delivery_qty1' => $value['15']],
                                                ['delivery_qty1' => 'required|numeric|']
                                            );
                                if ($validator->fails()){
                                    $messageErr[] = ['errField' => $delivery_date_1 , 'errMsg' => $validator->messages()];
                                 } else {
                                    $delivery_qty1 = $value['15'];
                                    $ttl_dlv_qty1 = $ttl_dlv_qty1 + $delivery_qty1;
                                }
                            //end check delivery 1    
                            }
                            //cek delivery 2 qty must be numeric
                            if(!empty($delivery_date_2) and !empty($kodeitem)){
                                $validator = validator::make(
                                                ['delivery_qty2' => $value['16']],
                                                ['delivery_qty2' => 'required|numeric|']
                                            );
                                if ($validator->fails()){
                                    $messageErr[] = ['errField' => $delivery_qty2, 'errMsg' => $validator->messages()];
                                 } else {
                                    $delivery_qty2 = $value['16'];
                                    $ttl_dlv_qty2 = $ttl_dlv_qty2 + $delivery_qty2;
                                }
                            //end check delivery 2    
                            }
                            //cek delivery 3 qty must be numeric
                            if(!empty($delivery_date_3) and !empty($kodeitem)){
                                $validator = validator::make(
                                                ['delivery_qty3' => $value['17']],
                                                ['delivery_qty3' => 'required|numeric|']
                                            );
                                if ($validator->fails()){
                                    $messageErr[] = ['errField' => $delivery_qty3, 'errMsg' => $validator->messages()];
                                 } else {
                                    $delivery_qty3 = $value['17'];
                                    $ttl_dlv_qty3 = $ttl_dlv_qty3 + $delivery_qty3;
                                }
                            //end check delivery 3    
                            }
                            //cek delivery 4 qty must be numeric
                            //dd($messageErr);
                            if(!empty($delivery_date_4) and !empty($kodeitem)){
                                $validator = validator::make(
                                                ['delivery_qty4' => $value['18']],
                                                ['delivery_qty4' => 'required|numeric|']
                                            );
                                if ($validator->fails()){
                                    $messageErr[] = ['errField' => $delivery_qty4, 'errMsg' => $validator->messages()];
                                 } else {
                                    $delivery_qty4 = $value['18'];
                                    $ttl_dlv_qty4 = $ttl_dlv_qty4 + $delivery_qty4;
                                }
                            //end check delivery 4    
                            }
                            if ((!empty($delivery_date_1) and !empty($kodeitem)) ){
                                            $trx_dtl[] = [
                                            'kode_item' => $value['1'], 
                                            'kode_descr' => $value['2'], 
                                            'uom_trx' => $value['3'], 
                                            'stock_awal_cycle' => $value['5'], 
                                            'sp' => $value['6'],
                                            'total_stock' => $value['7'],
                                            'est_sales' => $value['8'],
                                            'est_stock_akhir' => $value['9'],
                                            'est_sales_nextmonth' => $value['10'],
                                            'buffer' => $value['11'],
                                            'average' => $value['12'],
                                            'doi' => $value['13'],
                                            'cmo' =>  $value['14'],
                                            'delivery_date' => $delivery_date_1,
                                            'delivery_num' => '1',
                                            'delivery_qty' => $value['15'],
                                            'total_cmo' => $value['19'],
                                            'price_crt' => $this->itemPrice($value['1']),
                                            'subtotal' => ($this->itemPrice($value['1']) * $value['15']),
                                            'disc_percent' => $this->itemdisc($value['1']),
                                            'disc_value' => $this->getDiscVal($value['1'],$value['15']),
                                            'netto' => $this->getNett($value['1'],$value['15']),
                                            'ppn' => $this->getPPN($value['1'],$value['15']),
                                            'extended_price' => $this->getExtAmount($value['1'],$value['15']) ];
                            }
                            if ((!empty($delivery_date_2) and !empty($kodeitem))){
                                            $trx_dtl[] = [
                                            'kode_item' => $value['1'], 
                                            'kode_descr' => $value['2'], 
                                            'uom_trx' => $value['3'], 
                                            'stock_awal_cycle' => $value['5'], 
                                            'sp' => $value['6'],
                                            'total_stock' => $value['7'],
                                            'est_sales' => $value['8'],
                                            'est_stock_akhir' => $value['9'],
                                            'est_sales_nextmonth' => $value['10'],
                                            'buffer' => $value['11'],
                                            'average' => $value['12'],
                                            'doi' => $value['13'],
                                            'cmo' =>  $value['14'],
                                            'delivery_date' => $delivery_date_2,
                                            'delivery_num' => '2',
                                            'delivery_qty' => $value['16'],
                                            'total_cmo' => $value['19'],
                                            'price_crt' => $this->itemPrice($value['1']),
                                            'subtotal' => ($this->itemPrice($value['1']) * $value['16']) ,
                                            'disc_percent' => $this->itemdisc($value['1']),
                                            'disc_value' => $this->getDiscVal($value['1'],$value['16']),
                                            'netto' => $this->getNett($value['1'],$value['16']),
                                            'ppn' => $this->getPPN($value['1'],$value['16']),
                                            'extended_price' => $this->getExtAmount($value['1'],$value['16']) ];
                            }
                            if ((!empty($delivery_date_3) and !empty($kodeitem))){
                                            $trx_dtl[] = [
                                            'kode_item' => $value['1'], 
                                            'kode_descr' => $value['2'], 
                                            'uom_trx' => $value['3'], 
                                            'stock_awal_cycle' => $value['5'], 
                                            'sp' => $value['6'],
                                            'total_stock' => $value['7'],
                                            'est_sales' => $value['8'],
                                            'est_stock_akhir' => $value['9'],
                                            'est_sales_nextmonth' => $value['10'],
                                            'buffer' => $value['11'],
                                            'average' => $value['12'],
                                            'doi' => $value['13'],
                                            'cmo' =>  $value['14'],
                                            'delivery_date' => $delivery_date_3,
                                            'delivery_num' => '3',
                                            'delivery_qty' => $value['17'],
                                            'total_cmo' => $value['19'] ,
                                            'price_crt' => $this->itemPrice($value['1']),
                                            'subtotal' => ($this->itemPrice($value['1']) * $value['17']),
                                            'disc_percent' => $this->itemdisc($value['1']),
                                            'disc_value' => $this->getDiscVal($value['1'],$value['17']),
                                            'netto' => $this->getNett($value['1'],$value['17']),
                                            'ppn' => $this->getPPN($value['1'],$value['17']),
                                            'extended_price' => $this->getExtAmount($value['1'],$value['17']) ];
                            }
                            if ((!empty($delivery_date_4)and !empty($kodeitem))){
                                            $trx_dtl[] = [
                                            'kode_item' => $value['1'], 
                                            'kode_descr' => $value['2'], 
                                            'uom_trx' => $value['3'], 
                                            'stock_awal_cycle' => $value['5'], 
                                            'sp' => $value['6'],
                                            'total_stock' => $value['7'],
                                            'est_sales' => $value['8'],
                                            'est_stock_akhir' => $value['9'],
                                            'est_sales_nextmonth' => $value['10'],
                                            'buffer' => $value['11'],
                                            'average' => $value['12'],
                                            'doi' => $value['13'],
                                            'cmo' =>  $value['14'],
                                            'delivery_date' => $delivery_date_4,
                                            'delivery_num' => '4',
                                            'delivery_qty' => $value['18'],
                                            'total_cmo' => $value['19'],
                                            'price_crt' => $this->itemPrice($value['1']),
                                            'subtotal' => ($this->itemPrice($value['1']) * $value['18']),
                                            'disc_percent' => $this->itemdisc($value['1']),
                                            'disc_value' => $this->getDiscVal($value['1'],$value['18']),
                                            'netto' => $this->getNett($value['1'],$value['18']),
                                            'ppn' => $this->getPPN($value['1'],$value['18']),
                                            'extended_price' => $this->getExtAmount($value['1'],$value['18']) ];
                            }

                        }    
                      
                    $this->$x = $this->$x  + 1;
                    $x=$x+1;    
                    print_r($x);
                  }   
                //end foreach    
                
                //dd($data);
                //if(isset($messageErr)){
                 //   dd($trx_dtl);    
               //}
                
            //end if !empty $data    
            }
        //end has file    
        }   

        //dd($messageErr);
        $FleetCrt = (Session::get('minFleetCrt'))->toArray();
        $FleetVal = (Session::get('minFleetVal'))->toArray();
        $FleetType = (Session::get('minFleetType'))->toArray();

        foreach($FleetCrt as $values){
                $minFleetCrt = $values->value_name;
        }
        foreach ($FleetVal as $values){
                $minFleetVal = $values->value_name;
        }
        foreach($FleetType as $values){
                $minFleetType = $values->value_name;
        } 

        $cust_order = DB::table('customer_info')->where('cust_ship_id','=', $request['disti'])->get();         
        foreach($cust_order as $key =>$custdata){ 
                    $moq =$custdata->MOQ;
                    $fleet= $custdata->default_fleet;
                } 


         //put date delivery in array
        if(!empty($delivery_date_1)){
                $trx_rkp[] = ['datedlv' => $delivery_date_1,
                            'num'  => 1,
                            'total_crt' => $ttl_dlv_qty1,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_2)){
                $trx_rkp[] = ['datedlv' => $delivery_date_2,
                            'num'  => 2,
                            'total_crt' => $ttl_dlv_qty2,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_3)){
                $trx_rkp[] = ['datedlv' => $delivery_date_3,
                            'num'  => 3,
                            'total_crt' => $ttl_dlv_qty3,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_4)){
                $trx_rkp[] = ['datedlv' => $delivery_date_4,
                            'num'  => 4,
                            'total_crt' => $ttl_dlv_qty4,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }

        /*$this->rekapOrder = $trx_rkp;
        $this->detailOrder = $trx_dtl;
        $this->validateErr = $messageErr;
*/
        $getFleet= $this->getfleet();
         
        /*Session::put('trx_detail',$trx_dtl);
        Session::put('cust_order', $cust_order);
        Session::put('DetailFleet', $getFleet);
        Session::put('MessageErr', $this->validateErr);*/
        return view('orders.upload_order'); 

    //end import export
       
    }
}
