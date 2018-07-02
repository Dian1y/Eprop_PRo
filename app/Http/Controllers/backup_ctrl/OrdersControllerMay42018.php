<?php

namespace App\Http\Controllers\backup_ctrl;

use Illuminate\Http\Request;
use DB;
use Session;
use Auth;
use Excel; 
use App\TrxRekap;
use App\TrxDetails;
use App\WFApprover;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Input;
use App\Events\WorkflowEmail\UserApproverEvent;
//use app/TrxFleets;


class OrdersControllerMay42018 extends Controller
{

	protected $redirectTo = '/OrdersController';

     /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload_orders(){
    	return view('orders.upload_order');
    }


      public function show_detail_order($header_id) { 


        $trx_rkp = DB::table('cmo_rekap_delivery')->where('header_id','=',$header_id)->get(); 
        foreach ($trx_rkp as $key => $value) {
          $cust_ship_id = $value->cust_ship_id; 
        }
        
        $trx_detail= DB::table('trx_cmo_details')->where('header_id','=',$header_id)->get();
        $getFleet= DB::table('trx_cmo_fleets')->where('cmo_header_id','=',$header_id)->get();
        $cust_order = DB::table('customer_info')->where('cust_ship_id','=', $cust_ship_id)->get();         
 
        return view('orders.show_order',compact('trx_rkp','trx_detail','getFleet','cust_order'));
    }

    public function show_fleets($dlvdate){
        print_r('In Here ..');
        return view('orders.fleet_details');
    }

    public function save_orders(Request $request){


        $cust_info = Session::get('cust_order');
        $trx_rekap = Session::get('trx_rkp');
        $trxDetails = Session::get('trx_detail');
        $periode = Session::get('periode_cmo');
        $DetailFleet = Session::get('DetailFleet');

        foreach($cust_info as $key => $value) {
            $cust_ship_id =  $value->cust_ship_id;
            $cust_bill_id =  $value->cust_ship_id;
            $customer_number =  $value->customer_number;
            $subregion =  $value->subregion;
            //$rsdh_id  = $value->rsdh_id;
            //$asdh_id  = $value->asdh_id;
        } 

        $no_cmo = $this->getOrderNumber($customer_number);

        $delivery_date_1 ="";
        $delivery_date_2 ="";
        $delivery_date_3 ="";
        $delivery_date_4 ="";

        

        foreach($trx_rekap as $value) {
            if (!empty($value['periode_cmo'])) {
                $periode_cmo = $value['periode_cmo'];
            } else {
              dd($key);
            }
            if ($value['num']== '1') {
                $delivery_date_1 = $value['datedlv'];
            }

            if ($value['num'] == '2') {
                $delivery_date_2 = $value['datedlv'];
            }

            if ($value['num'] == '3') {
                $delivery_date_3 = $value['datedlv'];
            }

            if ($value['num'] == '4') {
                $delivery_date_4 = $value['datedlv'];
            }
        }

        $save_rekap = [];
        $cmo_number = $this->getOrderNumber($customer_number);
        $header_id = DB::select("select nextval('trx_cmo_headers_id_seq') as header_id");


        foreach($header_id as  $value) {
            $id = $value->header_id;
        }

        $save_rekap = [
              'header_id' => $id,
              'cust_ship_id' => $cust_ship_id,
              'cust_bill_id' => $cust_bill_id,
              'customer_number' => $customer_number,
              'cmo_number' => $cmo_number,
              'periode_cmo' => $periode_cmo,
              'subregion' => $subregion,
              'cmo_status' => 'NOT APPROVE',
              'rsdh_id' => null,
              'asdh_id'=> null,
              'delivery_date_1' => $delivery_date_1,
              'delivery_date_2' => $delivery_date_2,
              'delivery_date_3' => $delivery_date_3,
              'delivery_date_4' => $delivery_date_4,
              'created_by' => Auth::user()->name,
              'updated_by' => Auth::user()->name
        ];

        DB::begintransaction(); 

        DB::table('trx_cmo_headers')->insert(array($save_rekap));

        $save_detail[]='';

        //Save Order Detail
        $i=1;
        foreach ($trxDetails as $value) {

            $save_detail = [
             'header_id' => $id,
             'kode_item' => $value['kode_item'],
             'kode_descr' => $value['kode_descr'],
             'uom_trx' => $value['uom_trx'],
             'stock_awal_cycle' => $value['stock_awal_cycle'],
             'sp' => $value['sp'],
             'total_stock' => $value['total_stock'],
             'est_sales' => $value['est_sales'],
             'est_stock_akhir' => $value['est_stock_akhir'],
             'est_sales_nextmonth' => $value['est_sales_nextmonth'],
             'buffer' => $value['buffer'],
             'average' => $value['average'],
             'doi' => $value['doi'],
             'cmo' => $value['cmo'],
             'cmo_status' => 'NOT APPROVE',
             'delivery_date' => $value['delivery_date'],
             'delivery_num' => $value['delivery_num'],
             'delivery_qty' => $value['delivery_qty'],
             'price_crt' => doubleval($value['price_crt']),
             'disc_percent' => doubleval($value['disc_percent']),
             'disc_value' => $value['disc_value'],
             'netto' => $value['netto'],
             'ppn' => $value['ppn'],
             'extended_price' => $value['extended_price'],
             'created_by' => Auth::user()->id,
             'updated_by' => Auth::user()->id];
             
             DB::table('trx_cmo_details')->insert(array($save_detail));

             $i=$i+1;
             $save_detail[] ="";
        }
             
        //Save Fleet Detail
        $i=1;
        foreach ($DetailFleet as $value) {

            $fleet_detail = [ 
              'cmo_header_id' => $id,
              'dlv_date' => $value['delivery_date'] ,
              'fleet_no' => $value['Fleetke'],
              'fleet_type' => $value['Fleet'],
              'kode_item' => $value['kode_descr'],
              'dlv_qty'  => $value['delivery_qty'],
              'dlv_price' => doubleval($value['price_crt']),
              'dlv_disc' => doubleval($value['disc_value']),
              'dlv_netto' => doubleval($value['netto']),
              'dlv_extended' => doubleval($value['extended_price']),
              'created_by' => Auth::user()->id,
              'updated_by' => Auth::user()->id];
             
             DB::table('trx_cmo_fleets')->insert(array($fleet_detail));

             $i=$i+1;
             $fleet_detail[] ="";
        }
              
        DB::commit();

        $Success = "CMO No. " . $cmo_number . ' Berhasil di Simpan!';


        //Export CMO Excel 

        $custName = $request->input('distributor');
        $custShipName = $request->input('customer_ship_name');
        $custAddr = $request->input('kirimke');
        $custBranch = $request->input('cabang');
        $Periode = $request->input('periode');
        $cmo_data = DB::table('cmo_transaction_view')->where('cmo_number', '=', $no_cmo)->get();
        $cmo_data = collect($cmo_data);
        
        
        Excel::create($id,function($excel) use ($cmo_data, $no_cmo, $custShipName,$custAddr,$custBranch,$Periode,$delivery_date_1,$delivery_date_2,$delivery_date_3,$delivery_date_4) {
            $excel->sheet('Confirm Monthly Order', function($sheet) use ($cmo_data, $no_cmo, $custShipName,$custAddr,$custBranch,$Periode,$delivery_date_1,$delivery_date_2,$delivery_date_3,$delivery_date_4)
            {       

                    
                    $sheet->row(1,array( '', 'Customer Name',$custShipName));
                    $sheet->row(2,array( '', 'Cabang',$custBranch));
                    $sheet->row(3,array( '', 'Alamat Kirim ',$custAddr));
                    $sheet->row(4,array( '', 'Periode',$Periode));
                    $sheet->row(5,array( '', 'Nomor CMO',$no_cmo));
                    $sheet->cells('B1:C5', function($cells) {
                        $cells->setFontWeight('bold');
                    });
                    $sheet->cell('A7', function($cell) {$cell->setValue('No.');   });
                    $sheet->cell('B7', function($cell) {$cell->setValue('Kode Item');   });
                    $sheet->cell('C7', function($cell) {$cell->setValue('Description');   });
                    $sheet->cell('D7', function($cell) {$cell->setValue('UOM');   });
                    $sheet->setCellValue('E7', $delivery_date_1);
                    $sheet->setCellValue('F7', $delivery_date_2);
                    $sheet->setCellValue('G7', $delivery_date_3);
                    $sheet->setCellValue('H7', $delivery_date_4); 
                    $sheet->cell('I7', function($cell) {$cell->setValue('Total');   });
                    $sheet->cell('J7', function($cell) {$cell->setValue('Harga (Carton)');   });
                    $sheet->cell('K7', function($cell) {$cell->setValue('Disc %');   });
                    $sheet->cell('L7', function($cell) {$cell->setValue('Nett');   });
                    $sheet->cell('M7', function($cell) {$cell->setValue('PPN');   });
                    $sheet->cell('N7', function($cell) {$cell->setValue('Total');   });
                    $sheet->cells('A7:N7', function($cells) {
                        $cells->setFontWeight('bold');
                        $cells->setBackground('#DB9795');
                    });
                    if (!empty($cmo_data)) {
                            $no = 0;
                        foreach ($cmo_data as $key => $value) {
                            $i= $key+8;
                            $no = $no+1;
                            $sheet->cell('A'.$i, $no); 
                            $sheet->cell('B'.$i, $value->kode_item); 
                            $sheet->cell('C'.$i, $value->kode_descr); 
                            $sheet->cell('D'.$i, $value->uom_trx); 
                            $sheet->cell('E'.$i, $value->delivery_qty_1); 
                            $sheet->cell('F'.$i, $value->delivery_qty_2); 
                            $sheet->cell('G'.$i, $value->delivery_qty_3); 
                            $sheet->cell('H'.$i, $value->delivery_qty_4); 
                            $sheet->cell('I'.$i, $value->totalqty); 
                            $sheet->cell('J'.$i, $value->price_crt); 
                            $sheet->cell('K'.$i, $value->disc_percent); 
                            $sheet->cell('L'.$i, $value->netto); 
                            $sheet->cell('M'.$i, $value->ppn); 
                            $sheet->cell('N'.$i, $value->extended_price); 
                        }
                    }

                    $sheet->setColumnFormat(array(
                        'E8:N'.$i => '#,###.00'
                    ));

            });
        })->store('xls'); 
        
         
        //** update attach_filename path to trx_cmo_headers

         

        //*** Send Email With Attachment
        //** Get User Info
        $userInfo = Session::get('user_info');
        foreach ($userInfo as $key => $value) {
            $person_id = $value->person_id;
            $position = $value->position_id;
        }

        $subject = 'Order Number ' . $cmo_number . ' ' . ' - ' . $custShipName ; 
        $message = $cmo_number;
        $attach_file = storage_path('exports') . "/" . $id . ".xls";
        //dd($attach_file);
        //echo($subject);
        //Get hirarki id
        $hirarki_id = DB::table('mtl_hirarki')
                      ->where('hirarki_name', '=', 'CMO APPROVAL')
                      ->get();

        foreach ($hirarki_id as $key => $value) {
            $idhirarki = $value->id;
        }


        //Set workflow First
        //person_id, hirarki_id, position_id, cust_ship_id, subject
        $wf_check = DB::select('Select * From insert_approvers(?,?,?,?,?,?,?)',[$person_id, $idhirarki,$position,$cust_ship_id,$subject, $message, $attach_file]);

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
        
        
        //dd($Email);
        //***

        Session::forget('trx_detail');
        Session::forget('cust_order');
        Session::forget('DetailFleet');
        Session::forget('ErrorExcel');

        $Success = $Success . ' Menunggu Approve dari ' . $wfApprover->recipient_name;

        Session::flash('success', $Success); 

        return redirect('upload_excel');

    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return IlluminateHttpResponse
     */
    public function details()
    {
      dd(Session::get('DetailFleet'));
      
    	if(Session::has('DetailFleet')){
    		$details = Session::get('DetailFleet'); 
    	} else {
    		$details=[];
    	}
    	
        // return to the edit viewss
        return view('orders/fleet_details',compact('details'));
    }

    public function getOrderNumber($custNum) {

        $cmoNum = 'PO/' . $custNum . '/' . date('Y') . '/';


        $MaxNum = DB::table('cmo_number')->where('customer_number', '=', $custNum)->get();

        $num="";

        foreach ($MaxNum as $value) {
            $num = $value->cmo_num;
        }

        //dd($num);

        if(empty($num)) {
            $cmoNum = $cmoNum . '001'; 
        } else {
            $explnum = explode('/', $num);
            $number = end($explnum); 
            $number = intval($number) + 1;
            $cmoNum = $cmoNum . sprintf("%03d", $number);
        } 

        return $cmoNum;

    }
}
