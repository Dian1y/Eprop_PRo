<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlCustomerFleet; 
use App\MtlCustomerShip;
use Validator; 
use Session; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Yajra\Datatables\Datatables;
use DB;
use App\MtlFleet;
use View;

class CustomerFleet extends Controller
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
    public function index()
    {
    	 
        $custview = MtlCustomerShip::all(); 
       // return view ( 'master/customer_fleet/index' );
        return view('master/customer_fleet/index', ['CustomerView' => $custview]);  
    }  

   public function CustShipData()
    {

        return Datatables::of(MtlCustomerShip::query())->make(true);
    }
   
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        //return view('master.fleet.create');cust_ship_id
    }

    /**cust_bill_id', 
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
        $rules = array(
            'fleet_id' => 'required',
            'cust_ship_id' => 'required|exists:mtl_customer_ship_to',
            'est_delivery_day' => 'required|numeric'
            );

        $validator = $this->validate($request, $rules);

        $id = $request->input('cust_ship_id');

        $fleet = new MtlCustomerFleet;
        $fleet->cust_ship_id = $request->input('cust_ship_id');
        $fleet->fleet_id = $request->input('fleet_id');
        $fleet->est_delivery_day = $request->input('est_delivery_day');
        $fleet->default_flag = 0;
        $fleet->created_by = Auth::user()->name;
        $fleet->updated_by = Auth::user()->name;
        $fleet->save();


        $Success = 'Type Kendaran Baru Berhasil Di Simpan!';
        Session::flash('success', $Success); 
        
        $Fleet = DB::table('custFleet_view')->where('cust_ship_id',$id)->get();
        Session::put('fleet', $Fleet);
        return view('master/customer_fleet/show', ['Fleet' => $Fleet]);  
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    { 
        $custview = DB::table('customer_view')->where('cust_ship_id', $id)->get();
        $Fleet = DB::table('custFleet_view')->where('cust_ship_id',$id)->get();
        $FlList = DB::table('mtl_fleet')->get();
        Session::put('custview',$custview);
       // dd(Session::get('custview'));
        Session::put('fleet', $Fleet);
        Session::put('FlList', $FlList);
        return view::make('master/customer_fleet/show'); 
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    { 
        $fleet_id = $request->input('id');
        MtlCustomerFleet::find($fleet_id)->delete();
        $Fleet = DB::table('custFleet_view')->where('id',$fleet_id)->get();
        Session::put('fleet', $Fleet);
        $Success = 'Type Kendaran Baru Berhasil Di Hapus!';
        Session::flash('success', $Success); 
        return view('master/customer_fleet/show', ['Fleet' => $Fleet]);  
    }
    /**
     * Search department from database base on some specific constraints
     *
     * @param  \Illuminate\Http\Request  $request
     *  @return \Illuminate\Http\Response
     */
    public function search(Request $request) {
        $constraints = [
            'name' => $request['name']
            ];

       $CustomerView = $this->doSearchingQuery($constraints);
       return view('master/customer_fleet/index', ['CustomerView' => $CustomerView, 'searchingVals' => $constraints]);
    }

    private function doSearchingQuery($constraints) {
        $query = MtlCustomerShip::query();
        $fields = array_keys($constraints);
        $index = 0;
        foreach ($constraints as $constraint) {
            if ($constraint != null) {
                $query = $query->where( $fields[$index], 'like', '%'.$constraint.'%');
            }

            $index++;
        }
        return $query->paginate(5);
    }

}
