<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
//use Illuminate\Support\Facades\Redirect;
use App\Product;
use Session;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
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
    protected $val_dlv_qty1;
    protected $val_dlv_qty2;
    protected $val_dlv_qty3;
    protected $val_dlv_qty4;
    protected $prices;
    protected $detailOrder;
    protected $rekapOrder;
    protected $FleetErr;
    protected $trx_dtl;
    protected $trx_rkp;

    public function importExport() 
    { 
        Session::forget('trx_detail');
        Session::forget('cust_order');
        Session::forget('DetailFleet');
        Session::forget('FleetError');
        print_r('masih ada session nya');
        if(Session::has('FleetError')) {
            print_r('masih ada session nya');
        }
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
        //Clear Sessions
        Session::forget('trx_detail');
        Session::forget('cust_order');
        Session::forget('DetailFleet');
        Session::forget('ErrorExcel');
        // End Clear Session
        $trx_dtl=[];
        $trx_rkp=[];
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
            $val_dlv_qty1=0;
            $val_dlv_qty2=0;
            $val_dlv_qty3=0;
            $val_dlv_qty4=0;
            if(!empty($data) && $data->count()){
                //get group harga 
                $cust_order = DB::table('customer_info')->where('cust_ship_id','=', $request['disti'])
                              ->get();
                foreach($cust_order as $key =>$custdata){
                    $group_harga =$custdata->group_harga;
                    $customer_number = $custdata->customer_number;
                } 

                $item_prices = DB::table('mtl_harga')->where('group_harga', $group_harga)->get();  
                $this->prices = $item_prices;

                //get data using foreach
                foreach ($data as $key => $value){
                    $this->$x = $x;
                    $kodeitem = '';
                    $delivery_qty1=0;
                    $delivery_qty2=0;
                    $delivery_qty3=0;
                    $delivery_qty4=0;
                    //Validation 
                    if($x == 3) { //check Periode
                        $input = array('period'   =>  $value['6']);
                        $rules = array('period' => 'required|date');
                        $messages = array('required' => 'Periode CMO harus Di isi',
                                          'date'  => 'Format type Periode di Excel harus Format Date!');
                        $validation = Validator::make($input, $rules, $messages); 
                        if ($validation->fails()){ //validation fail
                            $MsgErr[] = ($messages);  
                        } else {  
                            $uda_ada = DB::table('trx_cmo_headers')->where('periode_cmo',$value['6'])->where('customer_number',$customer_number)->get();

                            foreach ($uda_ada as $key => $adanih) {
                                $ada = $adanih->cmo_number;
                            } 

                            if(empty($ada)){
                                $period = $value['6']->format('Y-m-d');
                            } else {
                                $period = $value['6']->format('Y-m-d');
                                $messages = [ 'Err'  => 'Periode CMO ' . $value['6']->format('Y-m-d') . ' Sudah pernah di input. No. CMO ' . $ada];
                                $MsgErr[] = ($messages); 
                            }
                        }  
                    } //cek periode
                    if($x == 7){ //Delivery Date
                        if(!empty($value['15'])){ //delivery date 1
                            $input = ['delivery_date_1'   =>  $value['15']];
                            $rules = ['delivery_date_1' => 'date'];
                            $messages = [ 'Err'  => 'Format Tanggal Pengiriman ke-1 (Kolom 15/Kolom R di Excel) harus Format Date!'];
                            $validation = Validator::make($input, $rules, $messages);  
                            if ($validation->fails()){ //success
                                 $MsgErr[] = ($messages);   
                            } else {
                                $delivery_date_1 = $value['15']->format('Y-m-d');
                            } //success                                 
                        } //delivery date 1
                        if(!empty($value['16'])){ //delivery date 2
                            $input = ['delivery_date_2'   =>  $value['16']];
                            $rules = ['delivery_date_2' => 'date'];
                            $messages = [ 'Err'  => 'Format Tanggal Pengiriman ke-2 (Kolom 16/Kolom S di Excel) harus Format Date!'];
                            $validation = Validator::make($input, $rules, $messages);  
                            if ($validation->fails()){ //success
                                $MsgErr[] = ($messages);
                            } else {
                                $delivery_date_2 = $value['16']->format('Y-m-d');
                            } //success                                 
                        } //delivery date 2
                        if(!empty($value['17'])){ //delivery date 3
                            $input = ['delivery_date_3'   =>  $value['17']];
                            $rules = ['delivery_date_3' => 'date'];
                            $messages = [ 'Err'  => 'Format Tanggal Pengiriman ke-3 (Kolom 17/Kolom T di Excel) harus Format Date!'];
                            $validation = Validator::make($input, $rules, $messages);  
                            if ($validation->fails()){ //success
                                    $MsgErr[] = ($messages);
                            } else {
                                $delivery_date_3 = $value['17']->format('Y-m-d');
                            } //success                                 
                        } //delivery date 3
                        if(!empty($value['18'])){ //delivery date 4
                            $input = ['delivery_date_4'   =>  $value['18']];
                            $rules = ['delivery_date_4' => 'date'];
                            $messages = [ 'Err'  => 'Format Tanggal Pengiriman ke-4 (Kolom 18/Kolom U di Excel) harus Format Date!'];
                            $validation = Validator::make($input, $rules, $messages);  
                            if ($validation->fails()){ //success
                                $MsgErr[] = ($messages);
                            } else {
                                $delivery_date_4 = $value['18']->format('Y-m-d');    
                            } //success                                 
                        } //delivery date 4
                    } //Delivery Date                    
                    if($x > 7){ //details 
                        if(!empty($value['1'])) { //Kodeitem
                            $input = array('kodeitem'   =>  $value['1']);
                            $rules = array('kodeitem' => 'exists:mtl_items');
                            $messages = array('Err' => 'Kode Item '  . $value['1'] . ' Tidak ada di Master Produk!');
                            $validation = Validator::make($input, $rules, $messages);  
                            if ($validation->fails()){ //success
                                $MsgErr[] = ($messages);                                
                                //print_r($MsgErr);
                            } else {
                                $kodeitem = $value['1']; 
                            } //success  

                        } // kodeitem
                        if(!empty($delivery_date_1) and !empty($kodeitem)) { //delivery Qty 1
                            $input = ['delivery_qty1'   =>  $value['15']];
                               if (!empty($kodeitem)) { //kodeitem Ada
                                    $rules = ['delivery_qty1' => 'required',
                                              'delivery_qty1' => 'numeric'];        
                               } //kodeitem ada
                               else { //kodeitem Tdk Ada
                                    $rules = ['delivery_qty1' => 'numeric'];    
                               } //kodeitem Tdk Ada
                               $messages = array('Err' => 'Quantity Item '  . $value['1'] . ' Invalid!');
                               $validation = Validator::make($input, $rules, $messages);  
                                if ($validation->fails()){ //success
                                    //$errors = $validation->messages();
                                    $MsgErr[] = ($messages); 
                                } else {    
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
                               $messages = array('Err' => 'Quantity Item '  . $value['1'] . ' Invalid!');
                               $validation = Validator::make($input, $rules, $messages);  
                               if ($validation->fails()){ //success
                                    //$errors = $validation->messages();
                                    $MsgErr[] = ($messages); 
                                } else {   
                                    $delivery_qty2 = $value['16'];
                                    $ttl_dlv_qty2 = $ttl_dlv_qty2 + $delivery_qty2;
                                } //success  

                        } //delivery Qty 2
                        if(!empty($delivery_date_3) and !empty($kodeitem)) { //delivery Qty 3
                            $input = ['delivery_qty3'   =>  $value['17']];
                               if (!empty($kodeitem)) { //kodeitem Ada
                                    $rules = ['delivery_qty3' => 'required',
                                              'delivery_qty3' => 'numeric'];        
                               } //kodeitem ada
                               else { //kodeitem Tdk Ada
                                    $rules = ['delivery_qty3' => 'numeric'];    
                               } //kodeitem Tdk Ada
                               $validation = Validator::make($input, $rules, $messages);  
                               $messages = array('Err' => 'Quantity Item '  . $value['1'] . ' Invalid!');
                               if ($validation->fails()){ //success
                                    //$errors = $validation->messages(); 
                                    $MsgErr[] = ($messages);
                                } else {
                                    $delivery_qty3 = $value['17'];
                                    $ttl_dlv_qty3 = $ttl_dlv_qty3 + $delivery_qty3;
                                } //success  
                        } //delivery Qty 3
                        if(!empty($delivery_date_4) and !empty($kodeitem)) { //delivery Qty 4
                            $input = ['delivery_qty4'   =>  $value['18']];
                               if (!empty($kodeitem)) { //kodeitem Ada
                                    $rules = ['delivery_qty4' => 'required',
                                              'delivery_qty4' => 'numeric'];        
                               } //kodeitem ada
                               else { //kodeitem Tdk Ada
                                    $rules = ['delivery_qty4' => 'numeric'];    
                               } //kodeitem Tdk Ada
                               $validation = Validator::make($input, $rules, $messages);
                               $messages = array('Err' => 'Quantity Item '  . $value['1'] . ' Invalid!');  
                               if ($validation->fails()){ //success
                                    //$errors = $validation->messages();
                                    $MsgErr[] = ($messages); 
                                } else {    
                                    $delivery_qty4 = $value['18'];
                                    $ttl_dlv_qty4 = $ttl_dlv_qty4 + $delivery_qty4;
                                } //success  

                        } //delivery Qty 4
                    } //Details
                    //End of Validation 
                    //Insert Into Array Trx Details 
                    if(!empty($kodeitem)){
                        if (!empty($delivery_date_1)) { //Insert1
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
                                    'average' => $value['12'],
                                    'buffer' => $value['11'],
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

                                    $val_dlv_qty1 = $val_dlv_qty1 + ($this->getExtAmount($value['1'],$value['15']));                               
                        } //End Insert1
                        if (!empty($delivery_date_2)) { //Insert2
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
                                    $val_dlv_qty2 = $val_dlv_qty2 + ($this->getExtAmount($value['1'],$value['16']));
                        }  //End Insert2
                        if (!empty($delivery_date_3)) { //Insert3
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
                                    $val_dlv_qty3 = $val_dlv_qty3 + ($this->getExtAmount($value['1'],$value['17']));        
                        } //End Insert3
                        if (!empty($delivery_date_4)) { //Insert4
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
                                    $val_dlv_qty4 = $val_dlv_qty4 + ($this->getExtAmount($value['1'],$value['18']));
                        }  //End Insert4
                    } 
                    //End of Insert Into Array Trx Details
                    $this->$x = $this->$x  + 1;
                    $x=$x+1;                     
                //end for each $data
                //var_dump($trx_dtl);
                }                                   
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
                $trx_rkp[] = [ 'periode_cmo' => $period,
                            'datedlv' => $delivery_date_1,
                            'num'  => 1,
                            'total_crt' => $ttl_dlv_qty1,
                            'value_order' => $val_dlv_qty1,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_2)){
                $trx_rkp[] = ['periode_cmo' => $period,
                            'datedlv' => $delivery_date_2,
                            'num'  => 2,
                            'total_crt' => $ttl_dlv_qty2,
                            'value_order' => $val_dlv_qty2,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_3)){
                $trx_rkp[] = ['periode_cmo' => $period,
                            'datedlv' => $delivery_date_3,
                            'num'  => 3,
                            'total_crt' => $ttl_dlv_qty3,
                            'value_order' => $val_dlv_qty3,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        if(!empty($delivery_date_4)){
                $trx_rkp[] = ['periode_cmo' => $period,
                            'datedlv' => $delivery_date_4,
                            'num'  => 4,
                            'total_crt' => $ttl_dlv_qty4,
                            'value_order' => $val_dlv_qty4,
                            'moq'=> $moq,
                            'fleet' => $fleet,
                            'minFleetCrt' =>$minFleetCrt,
                            'minFleetVal' =>$minFleetVal,
                            'minFleetType' =>$minFleetType
                ];
        }
        
       // dd($trx_rkp);

        $this->rekapOrder = $trx_rkp; 
        $trx_dtl = collect($trx_dtl)->filter(function ($trx_dtl) {
            return $trx_dtl['kode_item'] !== null;
        });


        $this->detailOrder =collect($trx_dtl)->sortBy('delivery_date'); 
        
        //dd($trx_dtl);

        //dd(collect($trx_dtl)->sortBy('delivery_date'));

        $getFleet= $this->getfleet();

        //dd(collect($MsgErr)->toArray());
        Session::put('trx_rkp',$trx_rkp);
        Session::put('trx_detail',$trx_dtl);
        Session::put('cust_order', $cust_order);
        Session::put('DetailFleet', $getFleet);
        dd($getFleet);
        if(Session::has('FleetError')){
            $FleetError = Session::get('FleetError');
        }
        //Merge Error
        $AllErrors = [];
        if (!empty($FleetError) and !empty($MsgErr)) {
            $AllErrors = array_merge($MsgErr,$FleetError);
        } elseif (!empty($MsgErr) and empty($FleetError)) {
            $AllErrors = $MsgErr;
        } elseif (empty($MsgErr) and !empty($FleetError)) {
            $AllErrors = $FleetError;
        }

        if (!empty($AllErrors)) {
                Session::put('ErrorExcel', $AllErrors);}
        return view('orders.upload_order'); 

    } //End of importExcel



    public function itemPrice($item_code){
        $get_prices = $this->prices;
        $get_prices = collect($get_prices);
        $filtered = $get_prices->where('kode_item', $item_code);
        $filtered = $filtered->all();
        $itemprice=0;
        foreach($filtered as $item) {
            $itemprice = $item->price_crt;
            //print_r($itemprice);
        } 

        return $itemprice;
    }
   

   public function itemdisc($item_code){
        //dd($this->prices);
        $get_prices = $this->prices;
        $get_prices = collect($get_prices);
        $filtered = $get_prices->where('kode_item', $item_code);
        $filtered = $filtered->all();
        $getdisc=0;
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
        $getdisc =0;
        $getprice=0;
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
        $getdisc =0;
        $getprice=0;
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
        $getdisc =0;
        $getprice=0;
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
        $getdisc =0;
        $getprice=0;
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
        $summary = collect($summary)->sortBy('datedlv'); 
        $details = collect($details); 
        $no = 0;   
        $sisaMin = 0;
        $jmlkirimMin =0;
        $ListFleet =[];
        $mergeErr=[];
        //dd($summary);
        foreach ($summary as $value){ 
                $msgFleet=[];
                $ErrFleet=[];
                $no = $no+1;
                $datedlv  = $value['datedlv'];
                $ttlcarton = $value['total_crt'];
                $sisa = ($ttlcarton % $value['moq']);
                $jmlkirim = (($value['total_crt']-$sisa)/$value['moq']);
                if($sisa <> 0 and $no == $SumCount) {
                    $sisaMin = $sisa;                    
                    $jmlkirimMin = (($sisa)/$value['minFleetCrt']); 
                }   
                $DefineFleet = $this->DefineFleet($value['num'], $datedlv, $details,$value['minFleetCrt'],$value['minFleetVal'],$value['minFleetType'],$value['fleet'], $ttlcarton,$value['moq'],$jmlkirim,$sisaMin, $jmlkirimMin);

                dd($DefineFleet);

                //var_dump($DefineFleet);
                if(isset($DefineFleet)) {
                    if(empty($ListFleet)) {
                            $ListFleet = $DefineFleet;
                    } else {
                            $ListFleet = array_merge($ListFleet, $DefineFleet); }
                }                 

                if (!empty($this->fleetErr)) {   
                    $ErrFleet = $this->fleetErr;
                }

        }  
        //dd($mergeErr);
        

        return $ListFleet;

    }

    public function DefineFleet($dlvNum, $tglkirim, $trxDetails, $minFleetCrt, $minFleetVal, $minFleetType,$fleet, $TotalCrt, $moq,$jmlkirim, $sisaMin, $jmlkirimMin) {
        
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
        $trxDetails = $trxDetails->reverse(); 
        $FleetItem=[];
        $fleetKe = 1;
        foreach ($trxDetails as $value) {
            $price_crt = $value['price_crt'];
            $disc_percent = $value['disc_percent'];
            if ($FleetQty== $moq and $fleetKe <= $jmlkirim ){
                $fleetKe = $fleetKe + 1;
                $FleetQty = 0; 
            }
            //split the Order Qty
            
            if(($FleetQty + $value['delivery_qty']) > $moq ){          //dlvqty = 4800      
                $dlvQty = (($moq-$FleetQty));                          
                $FleetQty = $FleetQty + $dlvQty;                        
                $currentQty = $currentQty + $dlvQty;                    
                $disc_value = ($price_crt * $dlvQty) * ($disc_percent/100);
                $subtotal = ($price_crt * $dlvQty);
                $netto = ($price_crt * $dlvQty) - $disc_value;
                $ppn = $netto * (10/100);
                $extended_price = $netto + $ppn;
                //hitung sisa
                $addFleet = 'True';
                $sisadlvQty = $value['delivery_qty'];     
                //print_r($sisadlvQty);   
                /*$sisaSubtotal = ($price_crt * $sisadlvQty);
                $sisaDiscVal = ($price_crt * $sisadlvQty) * ($disc_percent/100);
                $sisaNetto = ($price_crt * $sisadlvQty) - $sisaDiscVal;
                $sisaPpn = $sisaNetto * (10/100);
                $sisaExtended_price =  $sisaNetto + $sisaPpn;*/  
            } else {
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
            
            if ($fleetKe <= $jmlkirim and $jmlkirim<>0){ 
                if ($sisadlvQty=0) {                                                //kl ga ada sisa
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

                } else {  
                    $sisadlvQty = $value['delivery_qty'];
                    while ($sisadlvQty > $moq) {
                        print_r($fleetKe);
                        $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                                        'delivery_num' =>$dlvNum,
                                        'Fleetke' =>$fleetKe,
                                        'Fleet' => $fleet,
                                        'kode_item' => $value['kode_item'],
                                        'kode_descr' => $value['kode_descr'],
                                        'delivery_qty' => $dlvQty,
                                        'price_crt' => $price_crt,
                                        'subtotal' => $subtotal,
                                        'disc_percent' => $disc_percent,
                                        'disc_value' => $disc_value,
                                        'netto' => $netto,
                                        'ppn' => $ppn,
                                        'extended_price' => $extended_price];
                        //check kalo sisanya masih banyak                                         
                        $sisadlvQty = $sisadlvQty  - $dlvQty;                        
                        if($sisadlvQty >= $moq){  //sisa delivery = 4800 - 2200
                            $dlvQty = $moq;                           
                            $FleetQty = 0;                                   
                            $currentQty = $currentQty + $dlvQty;
                            $subtotal=($price_crt * $dlvQty);
                            $disc_value = ($price_crt * $dlvQty) * ($disc_percent/100);
                            $netto = ($price_crt * $dlvQty) - $disc_value;
                            $ppn = $netto * (10/100);
                            $extended_price = $netto + $ppn; 
                        }    
                        $fleetKe = $fleetKe + 1 ; 
                    }        
                }

                if ($sisadlvQty <>0 and ($fleetKe + 1) <= $jmlkirim) {
                    $dlvQty = $sisadlvQty;
                    $sisadlvQty =0;
                    $fleetKe = $fleetKe +1;
                    $FleetQty = $sisadlvQty;
                    $currentQty = $currentQty + $dlvQty; 
                    $disc_value = ($price_crt * $dlvQty) * ($disc_percent/100);
                    $netto = ($price_crt * $dlvQty) - $disc_value;
                    $ppn = $netto * (10/100);
                    $subtotal=($price_crt * $dlvQty);
                    $extended_price = $netto + $ppn;   
                    $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                                        'delivery_num' =>$dlvNum,
                                        'Fleetke' =>$fleetKe,
                                        'Fleet' => $fleet,
                                        'kode_item' => $value['kode_item'],
                                        'kode_descr' => $value['kode_descr'],
                                        'delivery_qty' => $dlvQty,
                                        'price_crt' => $price_crt,
                                        'subtotal' => $subtotal,
                                        'disc_percent' => $disc_percent,
                                        'disc_value' => $disc_value,
                                        'netto' => $netto,
                                        'ppn' => $ppn,
                                        'extended_price' => $extended_price]; 
                }                            
            }
            //kalau ada sisa
            /*if($sisadlvQty > 0 and $fleetKe <= $jmlkirim){               
                //$SisafleetKe = $SisafleetKe-1;
                $sisakirim =0;
                $x=0;
                while ($sisadlvQty<>0) {    
                                $x=$x+1;                      
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
            }*/
            //qty lebih kecil dari moq
            if ($sisadlvQty > 0 and $fleetKe > $jmlkirim) { 
                $FleetQty= $FleetQty + $sisadlvQty;
                $SisaMOQ[] = ['delivery_date' => $value['delivery_date'],
                            'delivery_num' =>$dlvNum,
                            'Fleetke' =>$fleetKe + 1,
                            'Fleet' => $minFleetType,
                            'kode_item' => $value['kode_item'],
                            'kode_descr' => $value['kode_descr'],
                            'delivery_qty' => $FleetQty,  
                            'price_crt' => $price_crt,
                            'subtotal' => ($FleetQty * $price_crt),
                            'disc_percent' => $disc_percent,
                            'disc_value' => ($FleetQty * $price_crt) * ($disc_percent/100),
                            'netto' => (($FleetQty * $price_crt) - (($FleetQty * $price_crt) * ($disc_percent/100))),  
                            'ppn' => (($FleetQty * $price_crt) - (($FleetQty * $price_crt) * ($disc_percent/100))) * (10/100),
                            'extended_price' => (($FleetQty * $price_crt) - (($FleetQty * $price_crt) * ($disc_percent/100))) + (($FleetQty * $price_crt) - (($FleetQty * $price_crt) * ($disc_percent/100))) * (10/100)];
               // dd($SisaMOQ);                            
            }
            if ($sisadlvQty == 0 and $fleetKe > $jmlkirim) { 
                $SisaMOQ[] = ['delivery_date' => $value['delivery_date'],
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
                //dd($SisaMOQ);                            
            }
            //Lebih kecil dari MOQ
            if ($sisadlvQty == 0 and  $jmlkirim==0) { 
                $subtotal = $dlvQty * $price_crt;
                $disc_value = ($dlvQty * $price_crt) * ($disc_percent/100);
                $netto = $subtotal - $disc_value;
                $ppn = $netto * (10/100);
                $Extended_price =$netto+$ppn;
                $SisaMOQ[] = ['delivery_date' => $value['delivery_date'],
                            'delivery_num' =>$dlvNum,
                            'Fleetke' =>$fleetKe,
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
                            'extended_price' => $Extended_price];
                //dd($SisaMOQ);                            
            }

            if (empty($FleetDetails)) {
                $FleetDetails = $FleetItem; 
            } else {
                $FleetDetails = array_merge($FleetDetails, $FleetItem); 
            }

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
        //var_dump($SisaMOQ);
        $DefineSisa = $this->DefineCDD ($dlvNum,$tglkirim,$SisaMOQ,$minFleetCrt,$minFleetVal,$minFleetType,$sisaMin, $jmlkirimMin,$fleetKe);    
        $FleetDetails = array_merge($FleetItem, $DefineSisa);  
        //$this->FleetDetails = $FleetDetails;
      }

        //dd($FleetDetails);
        return $FleetDetails;
    }


    //Define sisa 
    public function DefineCDD($dlvNum, $tglkirim, $SisaMOQ, $minFleetCrt, $minFleetVal, $minFleetType, $sisaMin, $jmlkirimMin, $fleet1){
        $SisaMOQ = collect($SisaMOQ)->sortBy('extended_price')->reverse()->toArray();  
        $ValueSisa =0;
        $JmlCrt = 0;
        $SisafleetKe =0;
        $fleetKe=$fleet1;
        $moq = $minFleetCrt;
        $fleet = $minFleetType; 
        $ValueOrder =0;
        $FleetItem=[];
        $lastFleet=0;
        //$this->fleetErr =[];
        foreach ($SisaMOQ as  $value) {
            $JmlCrt=$JmlCrt + $value['delivery_qty'];
            $ValueSisa = $ValueSisa + $value['extended_price']; 
        }
        $sisaMin =$JmlCrt% $minFleetCrt;
        $ValueSisa = $minFleetVal-$ValueSisa ; 
        if($sisaMin < $SisaMOQ ) {
            if ($ValueSisa < $minFleetVal){
            $msg = 'Sisa Pengiriman Tanggal ' . $tglkirim . ' dengan CDD Kurang, Total Pengiriman ' . $sisaMin . ' karton, MOQ CDD ' . $minFleetCrt . ' karton. Tambahkan Order ' . ($minFleetCrt - $sisaMin) . ' Carton. Atau Tambahkan Value Order Rp.' . number_format($ValueSisa, 2, ',', '.');
            $fleetErr[] = ['Err' => $msg];  
            //print_r($fleetErr);
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
            if ($CDDQty <= $moq and $fleetKe <= ($jmlkirimMin + $fleet1)){     
                        if($sisadlvQty > 0 and $addFleet = 'True'){
                            $SisafleetKe = $fleetKe +1;
                            $addFleet = 'False';
                            $CDDQty = 0;  
                            $ValueOrder = 0;   
                        } 
                        $lastFleet = $fleetKe;
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
             
            if ($sisadlvQty > 0 and $SisafleetKe <= (int)($jmlkirimMin + $fleet1)) {
                $SisafleetKe = $SisafleetKe-1;
                $sisakirim =0;
                $x=0; 
                if (empty($currentQty)){
                    $currentQty =0;
                }  
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
                        if ($SisafleetKe <= (int)$jmlkirimMin){                     
                            $fleetKe = $SisafleetKe;
                            $CDDQty=$CDDQty+$sisadlvQty;
                            $currentQty = $currentQty + $sisakirim; 
                            $subtotal = $sisadlvQty * $price_crt;
                            $disc_value = ($sisadlvQty * $price_crt) * ($disc_percent/100);
                            $netto = $subtotal - $disc_value;
                            $ppn = $netto * (10/100);
                            $extended_price = $netto + $ppn;
                            $ValueOrder =$ValueOrder + $extended_price;
                            $lastFleet = $fleetKe;
                            $FleetItem[] = ['delivery_date' => $value['delivery_date'],
                                            'delivery_num' =>$dlvNum,
                                            'Fleetke' =>$fleetKe,
                                            'Fleet' => $fleet,
                                            'kode_item' => $value['kode_item'],
                                            'kode_descr' => $value['kode_descr'],
                                            'delivery_qty' => $sisadlvQty,
                                            'price_crt' => $price_crt,
                                            'subtotal' => $subtotal,
                                            'disc_percent' => $disc_percent,
                                            'disc_value' => $disc_value ,
                                            'netto' => $netto,
                                            'ppn' => $ppn,
                                            'extended_price' => $extended_price]; 
                        }
                        if ($SisafleetKe > (int)$jmlkirimMin){                     
                            $fleetKe = $SisafleetKe;
                            $CDDQty=$CDDQty+$sisadlvQty;
                            $currentQty = $currentQty + $sisakirim; 
                            $subtotal = $sisakirim * $price_crt;
                            $disc_value = ($sisakirim * $price_crt) * ($disc_percent/100);
                            $netto = $subtotal - $disc_value;
                            $ppn = $netto * (10/100);
                            $extended_price = $netto + $ppn;
                            $ValueOrder =$ValueOrder + $extended_price;
                            $lastFleet = $fleetKe;
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
                $lastFleet = $fleetKe;
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
                $lastFleet = $SisafleetKe;       
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
        
        /*if($CDDQty < $minFleetCrt) {
             $ErrorFleet = 'Pengiriman ke ' . $fleetKe . ' dengan CDD Kurang. Tambahkan Order ' . ($minFleetCrt-$CDDQty) . ' Carton. Atau Tambahkan Value Order Rp.' . sprintf('%01.2f', ($minFleetVal - $ValueSisa));
             $fleetErr[] = ['errField' => 'Fleet'  , 'errMsg' => $ErrorFleet];  

        }*/
        
        $SisaCrt =0;
        $hitungSisa = collect($FleetItem)->where('Fleetke', $lastFleet);
        foreach ($hitungSisa as $value) {
            $ValueSisa = $ValueSisa  + (int)$value['extended_price'];
            $SisaCrt = $SisaCrt + (int)$value['delivery_qty'];
        }  
        if($SisaCrt < $minFleetCrt ) {
            if ($ValueSisa < $minFleetVal){
                //print_r('masuk sini Value ...');
            $msg = 'Sisa Pengiriman Tanggal ' . $tglkirim . ' dengan CDD Kurang, total pengiriman ' . $SisaCrt . ' Karton, MOQ CDD ' . $minFleetCrt . ' karton. Tambahkan Order ' . ($minFleetCrt - $SisaCrt) . ' Carton. Atau Tambahkan Value Order Rp.' . number_format(($minFleetVal-$ValueSisa), 2, ',', '.');
            $fleetErr[] = ['Err' => $msg];  
            //print_r($ErrorFleet);
            }

        }

        if(Session::has('FleetError')){
            $getErr = Session::get('FleetError');
            Session::forget('FleetError');
            if (!empty($fleetErr)) {
                $getErr = array_merge($getErr, $fleetErr);
            }
            Session::put('FleetError',$getErr);
        } else {
            if (!empty($fleetErr)) {
                $getErr =  $fleetErr;
                Session::put('FleetError',$getErr);
            } else {
                $getErr=[];
            }
        }       
        //dd($messageErr);
        return $FleetItem;

    }


    public function validateFleet($deliverydate, $moq, $fleet,$TotalDlvOrder,$QtyOrder2,$QtyOrder3,$QtyOrder4, $num){
        
      //
    }

}
