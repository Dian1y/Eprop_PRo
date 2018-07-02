<?php

namespace App\Http\Controllers;

use App\MtlFleet;
use Validator; 
use Session;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;

class ReportsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function FindCMO()
    {
        $lcustomer = DB::table('customer_view')->distinct()->select('customer_number', 'customer_name')->orderBy('customer_name')->get();
        $lcustShip = DB::table('customer_view')->distinct()->select('cust_ship_id', 'customer_ship_name')->orderBy('customer_ship_name')->get();
        $lregion = DB::table('mtl_region')->orderBy('region')->get();
        $lsubregion = DB::table('mtl_subregion')->orderBy('subregion')->get();
        $lcmo = DB::table('trx_cmo_headers')->orderBy('cmo_number')->get();
        return view('reports/order_rpt', compact('lcustomer','lcustShip','lregion', 'lsubregion', 'lcmo' ));    
    }
}
