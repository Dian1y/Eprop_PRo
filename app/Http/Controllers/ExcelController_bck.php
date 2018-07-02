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
                //$item_prices = collect($item_prices);
               // dd($item_prices);
                $this->prices = $item_prices;
            
                //get data using foreach
                foreach ($data as $key => $value){
                    $this->$x = $x;
                    //cek for period format date type
                    if($x == 3){
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
                    if($x == 7){
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
                             } else {
                                $kodeitem = $value['1'];
                            } 
                            //end kodeitem

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
                    }   
                    $this->$x = $this->$x  + 1;
                    $x=$x+1;    
                //end foreach    
                }
                //dd($data);
                //if(isset($messageErr)){
                   // dd($trx_dtl);    
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

        $this->rekapOrder = $trx_rkp;
        $this->detailOrder = $trx_dtl;
        $this->validateErr = $messageErr;

        $getFleet= $this->getfleet();
         
        Session::put('trx_detail',$trx_dtl);
        Session::put('cust_order', $cust_order);
     //   Session::put('FleetItem', $FleetItem);
        Session::put('MessageErr', $messageErr);
        return view('orders.upload_order'); 
    //end import export
       
    }

    public function itemPrice($item_code){
        //dd($this->prices);
        $get_prices = $this->prices;
        $get_prices = collect($get_prices);
        $filtered = $get_prices->where('kode_item', $item_code);
        $filtered = $filtered->all();
        foreach($filtered as $item) {
            $itemprice = $item->price_crt;
        }
        return $itemprice;
    }
   

   public function itemdisc($item_code){
        //dd($this->prices);
        $get_prices = $this->prices;
        $get_prices = collect($get_prices);
        $filtered = $get_prices->where('kode_item', $item_code);
        $filtered = $filtered->all();
        foreach($filtered as $item) {
            $getdisc = $item->discount;
        }
        return $getdisc;
    }

    public function getDiscVal($item_code,$qtyOrder){
        $get_prices = $this->prices;
        $get_prices = collect($get_prices);
        $filtered = $get_prices->where('kode_item', $item_code);
        $filtered = $filtered->all();
        //dd($filtered);
        foreach($filtered as $item) {
            $getdisc = $item->discount;
            $getprice = $item->price_crt;
        }
        //var_dump($getprice,$qtyOrder);
        $getDisval = ($qtyOrder*$getprice) * ($getdisc/100);

        return  $getDisval;

    }

    public function getNett($item_code,$qtyOrder){
        $get_prices = $this->prices;
        $get_prices = collect($get_prices);
        $filtered = $get_prices->where('kode_item', $item_code);
        $filtered = $filtered->all();
        foreach($filtered as $item) {
            $getdisc = $item->discount;
            $getprice = $item->price_crt;
        }

        $nett = ($qtyOrder*(float)$getprice) - (((float)$qtyOrder*(float)$getprice) * ((float)$getdisc/100));

        return  $nett;
    }

    public function getPPN($item_code,$qtyOrder){
        $get_prices = $this->prices;
        $get_prices = collect($get_prices);
        $filtered = $get_prices->where('kode_item', $item_code);
        $filtered = $filtered->all();
        foreach($filtered as $item) {
            $getdisc = $item->discount;
            $getprice = $item->price_crt;
        }

        $nett = (($qtyOrder*(float)$getprice) - (((float)$qtyOrder*(float)$getprice) * ((float)$getdisc/100)));

        $ppn = $nett * 0.1;

        return  $nett;
    }

    public function getExtAmount($item_code,$qtyOrder){

        $get_prices = $this->prices;
        $get_prices = collect($get_prices);
        $filtered = $get_prices->where('kode_item', $item_code);
        $filtered = $filtered->all();
        foreach($filtered as $item) {
            $getdisc = $item->discount;
            $getprice = $item->price_crt;
        }

        $nett = (($qtyOrder*(float)$getprice)) - (((float)$qtyOrder*(float)$getprice) * ((float)$getdisc/100));

        $extamount = $nett + ($nett * 0.01);

        return  $extamount;
    }

    public function getFleet(){
        $summary = $this->rekapOrder;
        $details = $this->detailOrder;
        $SumCount = count($summary);
        $summary = $summary;
        $details = collect($details); 
        $no = 0;   
        $sisaMin = 0;
        $jmlkirimMin =0;
        foreach($summary as $sum){
                $no = $no+1;
                $datedlv  = $sum['datedlv'];
                $ttlcarton = $sum['total_crt'];
                $sisa = ($ttlcarton % $sum['moq']);
                $jmlkirim = (($sum['total_crt']-$sisa)/$sum['moq']);
                if($sisa <> 0 and $no == $SumCount) {
                    $sisaMin = $sisa;                    
                    $jmlkirimMin = (($sisa)/$sum['minFleetCrt']); 
                } 
                if($sisa <> 0 and $no <> $SumCount and $jmlkirim < 1){
                   $msg = 'Pengiriman ke-' .$sum['num']. ' Kurang dari MOQ (' . $sum['moq']. ' Karton). Total Order ' . $ttlcarton . ' Karton, Tambahkan Order '. ($sum['moq'] - $sisa) . ' Karton';      
                }
                if($sisa <> 0 and $no <> $SumCount and $jmlkirim > 1){
                    $msg = 'Pengiriman ke-' . $sum['num'] . ' Kurang dari MOQ. Tambahkan Order '. ($sum['moq'] - $sisa) . ' Karton atau kurangi order ' . $sisa . 'Karton';   
                }

                //Cek apakah pengiriman terakhir
                $DefineFleet = $this->DefineFleet($sum['num'], $datedlv, $details,$sum['minFleetCrt'],$sum['minFleetVal'],$sum['minFleetType'],$sum['fleet'], $ttlcarton,$sum['moq'],$jmlkirim,$sisaMin, $jmlkirimMin);

                if(isset($DefineFleet)) {
                        if(empty($ListFleet)) {
                            $ListFleet = $DefineFleet;
                        } else {}
                        $ListFleet = array_merge($ListFleet, $DefineFleet); 
                    }
                
                /*if($sisa <> 0 and $no == $SumCount) {
                    $sisaMin = $sisa;                    
                    $jmlkirimMin = (($sisa)/$sum['minFleetCrt']);
                    if ($sisaMin == 0){
                        //Define Fleet
                        dd($details);
                        $DefineFleet = $this->DefineFleet($sum['num'], $datedlv, $details,$sum['minFleetCrt'],$sum['minFleetVal'],$sum['minFleetType'],$sum['fleet'], $ttlcarton,$sum['moq'],$jmlkirim,$sisaMin, $jmlkirimMin);
                    }
                    if ($sisaMin <> 0){
                        //cek Min Value
                        $DefineFleet = $this->DefineFleet($sum['num'], $datedlv, $details,$sum['minFleetCrt'],$sum['minFleetVal'],$sum['minFleetType'],$sum['fleet'], $ttlcarton,$sum['moq'],$jmlkirim,$sisaMin, $jmlkirimMin);
                    }


                    if(isset($DefineFleet)) {
                        if(empty($ListFleet)) {
                            $ListFleet = $DefineFleet;
                        } else {}
                        $ListFleet = array_merge($ListFleet, $DefineFleet); 
                    }

                } */
        }  

        //$dtlfiltered = $detail();
        /*if(isset($ListFleet)) {
                dd($ListFleet);
            }

        if (isset($msg)){
                $messageErr[] = ['errField' => 'Fleet', 'errMsg' => $msg]; }*/


    }

    public function DefineFleet($dlvNum, $tglkirim, $trxDetails, $minFleetCrt, $minFleetVal, $minFleetType,$fleet, $TotalCrt, $moq,$jmlkirim, $sisaMin, $jmlkirimMin) {
        $fleetKe = 1;
        $sisaTotal = $TotalCrt;
        $FleetQty=0;
        $minTotalVal=0;
        $currentQty=0;
        $SisafleetKe=0; 
        //FleetItem = 
        $trxDetails = collect($trxDetails);
        //dd($trxDetails);
        $trxDetails = $trxDetails->where('delivery_date', $tglkirim);
        $trxDetails = $trxDetails->sortBy('extended_price');  
        $FleetItem=[];
        foreach ($trxDetails as $value) {
            $price_crt = $value['price_crt'];
            $disc_percent = $value['disc_percent'];
            if ($FleetQty== $moq and $fleetKe <= $jmlkirim ){
                $fleetKe== $fleetKe +1;
                $FleetQty==0; 
            }
            //split the Order Qty
            
            if(($FleetQty + $value['delivery_qty']) > $moq ){                 
                $dlvQty = (($moq-$FleetQty));
                $FleetQty = $FleetQty + $dlvQty;
                $currentQty = $currentQty + $dlvQty;
                //hitung sisa
                $sisadlvQty = $value['delivery_qty'] - $dlvQty;
                $addFleet = 'True';
                $disc_value = ($price_crt * $dlvQty) * ($disc_percent/100);
                $netto = ($price_crt * $dlvQty) - $disc_value;
                $ppn = $netto * (10/100);
                $extended_price = $netto + $ppn;
                $sisaSubtotal = ($price_crt * $sisadlvQty);
                $sisaDiscVal = ($price_crt * $sisadlvQty) * ($disc_percent/100);
                $sisaNetto = ($price_crt * $sisadlvQty) - $sisaDiscVal;
                $sisaPpn = $sisaNetto * (10/100);
                $sisaExtended_price =  $sisaNetto + $sisaPpn;  
            } else {
                //print_r('hrsny ke sini , ');
                $dlvQty = $value['delivery_qty'];
                $sisadlvQty =0;
                $FleetQty = ($FleetQty + $dlvQty);
                $currentQty = $currentQty + $dlvQty; 
                $disc_value = $value['disc_value'];
                $netto = $value['netto'];
                $ppn = $value['ppn'];
                $extended_price = $value['extended_price'];
            }
           // var_dump($FleetQty);
            if ($FleetQty <= $moq and $fleetKe <= $jmlkirim){
                        if($sisadlvQty > 0 and $addFleet = 'True'){
                            $SisafleetKe = $fleetKe +1;
                            $addFleet = 'False';
                        }
                        $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                                        'delivery_num' =>$dlvNum,
                                        'Fleetke' =>$fleetKe,
                                        'Fleet' => $fleet,
                                        'kode_item' => $value['kode_item'],
                                        'kode_descr' => $value['kode_descr'],
                                        'delivery_qty' => $dlvQty,
                                        'price_crt' => $price_crt,
                                        'subtotal' =>$value['subtotal'],
                                        'disc_percent' => $disc_percent,
                                        'disc_value' => $disc_value,
                                        'netto' => $netto,
                                        'ppn' => $ppn,
                                        'extended_price' => $extended_price]; 
            }
            //kalau ada sisa
            if($sisadlvQty > 0 and $SisafleetKe <= $jmlkirim){               
                $SisafleetKe = $SisafleetKe-1;
                $sisakirim =0;
                $x=0;
                while ($sisadlvQty<>0) {    
                                $x=$x+1;
                                //print_r($x); 
                                //print_r(' , ');  
                               //print_r($value['kode_item']);                          
                                $SisafleetKe = $SisafleetKe + 1; 
                                if($sisadlvQty >= $moq) {
                                    $FleetQty=0;
                                    $sisakirim=$moq;
                                    $sisadlvQty=$sisadlvQty - $moq;
                                } elseif ($sisadlvQty < $moq) {
                                    $sisakirim=$sisadlvQty;
                                    $sisadlvQty = 0; 
                                    $FleetQty = $sisakirim;
                                }   
                                if ($SisafleetKe <= $jmlkirim){                     
                                             $fleetKe = $SisafleetKe;
                                             $FleetQty=$FleetQty+$sisadlvQty;
                                             $currentQty = $currentQty + $sisakirim; 
                                             $subtotal = $sisakirim * $price_crt;
                                             $disc_value = ($sisakirim * $price_crt) * ($disc_percent/100);
                                             $netto = $subtotal - $disc_value;
                                             $ppn = $netto * (10/100);
                                             $extended_price = $netto + $ppn;
                                             $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                                                             'delivery_num' =>$dlvNum,
                                                             'Fleetke' =>$fleetKe,
                                                             'Fleet' => $fleet,
                                                             'kode_item' => $value['kode_item'],
                                                             'kode_descr' => $value['kode_descr'],
                                                             'delivery_qty' => $sisakirim,
                                                             'price_crt' => $price_crt,
                                                             'subtotal' => $subtotal,
                                                             'disc_percent' => $disc_percent,
                                                             'disc_value' => $disc_value ,
                                                             'netto' => $netto,
                                                             'ppn' => $ppn,
                                                             'extended_price' => $extended_price];

                                }                        
                }
            }
            //qty lebih kecil dari moq
            if ($sisadlvQty > 0 and $SisafleetKe > $jmlkirim) { 
                $fleetKe = $SisafleetKe; 
                $FleetQty=$sisadlvQty;
                $SisaMOQ[] = ['delivery_date' => $value['delivery_date'],
                            'delivery_num' =>$dlvNum,
                            'Fleetke' =>$fleetKe,
                            'Fleet' => $minFleetType,
                            'kode_item' => $value['kode_item'],
                            'kode_descr' => $value['kode_descr'],
                            'delivery_qty' => $FleetQty, 
                            'price_crt' => $price_crt,
                            'subtotal' =>$sisaSubtotal,
                            'disc_percent' => $disc_percent,
                            'disc_value' => $sisaDiscVal,
                            'netto' => $sisaNetto, + 
                            'ppn' => $sisaPpn,
                            'extended_price' => $sisaExtended_price];
               // dd($SisaMOQ);                            
            }
            if ($sisadlvQty == 0 and $SisafleetKe > $jmlkirim) { 
                $SisaMOQ[] = ['delivery_date' => $value['delivery_date'],
                            'delivery_num' =>$dlvNum,
                            'Fleetke' =>$fleetKe,
                            'Fleet' => $minFleetType,
                            'kode_item' => $value['kode_item'],
                            'kode_descr' => $value['kode_descr'],
                            'delivery_qty' => $dlvQty, 
                            'price_crt' => $price_crt,
                            'subtotal' =>$sisaSubtotal,
                            'disc_percent' => $disc_percent,
                            'disc_value' => $sisaDiscVal,
                            'netto' => $sisaNetto, 
                            'ppn' => $sisaPpn,
                            'extended_price' => $sisaExtended_price];
                //dd($SisaMOQ);                            
            }

            /*if (empty($FleetDetails)) {
                $FleetDetails = $FleetItem;
                $this->FleetDetails = $FleetDetails;
            } else {
                $FleetDetails = array_merge($FleetDetails, $FleetItem);
                $this->FleetDetails = $FleetDetails;
            }*/

            /*dd($SisaMOQ);
            if(!empty($FleetDetails)){
                $FleetDetails = array_merge($FleetItem, $FleetItem);
            } else {
                $FleetDetails = collect($FleetItem);
            }*/
      } 
      //Define sisanya!!


      if(isset($SisaMOQ)){
        $SisaMOQ = collect($SisaMOQ);
        $DefineSisa = $this->DefineCDD ($dlvNum,$tglkirim,$SisaMOQ,$minFleetCrt,$minFleetVal,$minFleetType,$sisaMin, $jmlkirimMin,$fleetKe);    
        $FleetDetails = array_merge($FleetItem, $DefineSisa);  
        $this->FleetDetails = $FleetDetails;
      }

        //dd($FleetDetails);
        return $this->$FleetDetails;
    }


    //Define sisa 
    public function DefineCDD($dlvNum, $tglkirim, $SisaMOQ, $minFleetCrt, $minFleetVal, $minFleetType, $sisaMin, $jmlkirimMin, $fleet1){
        //dd($SisaMOQ);
        $SisaMOQ = collect($SisaMOQ)->sortBy('extended_price')->reverse()->toArray();  
        $ValueSisa =0;
        $JmlCrt = 0;
        $SisafleetKe =0;
        $fleetKe=$fleet1;
        $moq = $minFleetCrt;
        $fleet = $minFleetType; 
        $ValueOrder =0;
        $FleetItem=[];
        foreach ($SisaMOQ as $value) {
            $ValueSisa = $ValueSisa  + $value['extended_price'];
            $JmlCrt = $JmlCrt + $value['delivery_qty'];
        }
        if($sisaMin < $minFleetCrt) {
            if ($ValueSisa < $minFleetVal){
            $msg = 'Sisa Pengiriman ke ' . $fleetKe . ' dengan CDD Kurang. Tambahkan Order ' . ($minFleetCrt-$sisaMin) . ' Carton. Atau Tambahkan Value Order Rp.' . sprintf('%01.2f', ($minFleetVal - $ValueSisa));
            }

        }
        $CDDKe = 0;
        $sisaTotal = $sisaMin;
        $CDDQty=0;
        
        foreach($SisaMOQ as $value) {
            $price_crt = $value['price_crt'];
            $disc_percent = $value['disc_percent'];
            if ($CDDQty == $minFleetCrt and $fleetKe < ($jmlkirimMin + $fleet1) ){
                        $fleetKe==$fleetKe +1;
                        $CDDQty==0; 
                        $ValueOrder =0;
                }
  
            if(($CDDQty + $value['delivery_qty']) > $minFleetCrt ){
                $dlvQty = ($minFleetCrt - $CDDQty);
                $sisadlvQty = $value['delivery_qty'] - $dlvQty; 
                print_r(($CDDQty + $value['delivery_qty']));
                $CDDQty = $CDDQty + $dlvQty;          
                if ($CDDQty =0){
                                $fleetKe = $fleetKe + 1;}
                $SisafleetKe = $fleetKe+1;
                $disc_value = ($price_crt * $dlvQty) * ($disc_percent/100);
                $netto = ($price_crt * $dlvQty) - $disc_value;
                $ppn = $netto * (10/100);
                $extended_price = $netto + $ppn;
                $ValueOrder =$ValueOrder + $extended_price;
                //sisaan
                $sisaSubtotal = ($price_crt * $sisadlvQty);
                $sisaDiscVal = ($price_crt * $sisadlvQty) * ($disc_percent/100);
                $sisaNetto = ($price_crt * $sisadlvQty) - $sisaDiscVal;
                $sisaPpn = $sisaNetto * (10/100);
                $sisaExtended_price =  $sisaNetto + $sisaPpn;
                //
                 $addFleet = 'True';
            } else {
                $dlvQty = $value['delivery_qty'];
                $CDDQty = $CDDQty +  $value['delivery_qty'];
                $sisadlvQty=0;
                $disc_value = $value['disc_value'];
                $netto = $value['netto'];
                $ppn = ($netto * (10/100));
                $extended_price = $value['extended_price'];
                $ValueOrder =$ValueOrder + $extended_price;
            }
            /*print_r($CDDQty);
            print_r(' , ');
            print_r($moq);
            print_r(' , ');
            print_r($jmlkirimMin);
            print_r(' , ');
            print_r($fleet1);
            print_r(' , ');
            print_r($fleetKe);
            print_r(' , ');
            print_r($value['kode_item']);
            print_r(' // ');*/
            if ($CDDQty <= $moq and $fleetKe <= ($jmlkirimMin + $fleet1)){     
                        if($sisadlvQty > 0 and $addFleet = 'True'){
                            print_r($value['kode_item']);
                            print_r(' , ');
                            $SisafleetKe = $fleetKe +1;
                            $addFleet = 'False';
                            $CDDQty = 0;  
                            $ValueOrder = 0;   
                        } 
                        $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                                        'delivery_num' =>$dlvNum,
                                        'Fleetke' =>$fleetKe,
                                        'Fleet' => $fleet,
                                        'kode_item' => $value['kode_item'],
                                        'kode_descr' => $value['kode_descr'],
                                        'delivery_qty' => $dlvQty,
                                        'price_crt' => $price_crt,
                                        'subtotal' =>$value['subtotal'],
                                        'disc_percent' => $disc_percent,
                                        'disc_value' => $disc_value,
                                        'netto' => $netto,
                                        'ppn' => $ppn,
                                        'extended_price' => $extended_price]; 
            }

            if ($sisadlvQty > 0 and $SisafleetKe <= ($jmlkirimMin + $fleet1)) {
                $SisafleetKe = $SisafleetKe-1;
                $sisakirim =0;
                $x=0;  /*  
                print_r(' , ');
                print_r($sisadlvQty);
                print_r(' , '); */
                while ($sisadlvQty<>0) {
                        $x=$x+1;
                        $SisafleetKe = $SisafleetKe + 1; 
                        if($sisadlvQty >= $moq) {
                            $CDDQty=0;
                            $ValueOrder =0;
                            $sisakirim=$moq;
                            $sisadlvQty=$sisadlvQty - $moq;
                        } elseif ($sisadlvQty < $moq) {
                            $sisakirim=$sisadlvQty;
                            $sisadlvQty = 0; 
                            $FleetQty = $sisakirim;
                        }   
                        if ($SisafleetKe <= $jmlkirimMin){                     
                            $fleetKe = $SisafleetKe;
                            $CDDQty=$CDDQty+$sisadlvQty;
                            $currentQty = $currentQty + $sisakirim; 
                            $subtotal = $sisakirim * $price_crt;
                            $disc_value = ($sisakirim * $price_crt) * ($disc_percent/100);
                            $netto = $subtotal - $disc_value;
                            $ppn = $netto * (10/100);
                            $extended_price = $netto + $ppn;
                            $ValueOrder =$ValueOrder + $extended_price;
                            $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                                            'delivery_num' =>$dlvNum,
                                            'Fleetke' =>$fleetKe,
                                            'Fleet' => $fleet,
                                            'kode_item' => $value['kode_item'],
                                            'kode_descr' => $value['kode_descr'],
                                            'delivery_qty' => $sisakirim,
                                            'price_crt' => $price_crt,
                                            'subtotal' => $subtotal,
                                            'disc_percent' => $disc_percent,
                                            'disc_value' => $disc_value ,
                                            'netto' => $netto,
                                            'ppn' => $ppn,
                                            'extended_price' => $extended_price]; 
                        } 
                 }    
            }
            //qty lebih kecil dari moq
            if ($sisadlvQty > 0 and $SisafleetKe > ($jmlkirimMin + $fleet1)) {  
                $fleetKe = $SisafleetKe; 
                $CDDQty=$CDDQty + $sisadlvQty;
                $subtotal = $sisadlvQty * $price_crt;
                $disc_value = ($sisadlvQty * $price_crt) * ($disc_percent/100);
                $netto = $subtotal - $disc_value;
                $ppn = $netto * (10/100);
                $extended_price = $netto + $ppn;
                $ValueOrder =$ValueOrder + $extended_price;
                $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                            'delivery_num' =>$dlvNum,
                            'Fleetke' =>$fleetKe,
                            'Fleet' => $minFleetType,
                            'kode_item' => $value['kode_item'],
                            'kode_descr' => $value['kode_descr'],
                            'delivery_qty' => $sisadlvQty, 
                            'price_crt' => $price_crt,
                            'subtotal' =>$subtotal,
                            'disc_percent' => $disc_percent,
                            'disc_value' => $disc_value,
                            'netto' => $netto,  
                            'ppn' => $ppn,
                            'extended_price' => $extended_price];
               // dd($SisaMOQ);                            
            }
            if ($sisadlvQty == 0 and $SisafleetKe > ($jmlkirimMin + $fleet1)) {    
                //$CDDQty=$CDDQty + $sisadlvQty;  
                $subtotal = $dlvQty * $price_crt;
                $disc_value = ($dlvQty * $price_crt) * ($disc_percent/100);
                $netto = $subtotal - $disc_value;
                $ppn = $netto * (10/100);
                $extended_price = $netto + $ppn;
                //$ValueOrder =$ValueOrder + $extended_price;         
                $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                            'delivery_num' =>$dlvNum,
                            'Fleetke' =>$SisafleetKe,
                            'Fleet' => $minFleetType,
                            'kode_item' => $value['kode_item'],
                            'kode_descr' => $value['kode_descr'],
                            'delivery_qty' => $dlvQty, 
                            'price_crt' => $price_crt,
                            'subtotal' =>$subtotal,
                            'disc_percent' => $disc_percent,
                            'disc_value' => $disc_value,
                            'netto' => $netto, 
                            'ppn' => $ppn,
                            'extended_price' => $extended_price];
                //dd($SisaMOQ);                            
            }  
        } 
        
        if($CDDQty < $minFleetCrt) {
            if ($ValueOrder < $minFleetVal){
             $msg = 'Pengiriman ke ' . $fleetKe . ' dengan CDD Kurang. Tambahkan Order ' . ($minFleetCrt-$CDDQty) . ' Carton. Atau Tambahkan Value Order Rp.' . sprintf('%01.2f', ($minFleetVal - $ValueSisa));
            }

        }

        if (isset($msg)){
            
            $fleetErr[] = ['errField' => 'Fleet'  , 'errMsg' => $msg];   
            //dd($ErrMsg);
        }

        
        //dd($messageErr);
        
        return $FleetItem;

    }


    public function validateFleet($deliverydate, $moq, $fleet,$TotalDlvOrder,$QtyOrder2,$QtyOrder3,$QtyOrder4, $num){
        
      //
    }

}
