<?php

namespace App\Http\Controllers;

use App\MtlFleet;
//use Request;
use Validator; 
use Session;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use DB;

class FleetController extends Controller
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
        $Fleet = MtlFleet::paginate(10);
        return view('master/fleet/index', ['Fleet' => $Fleet]);    
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('master.fleet.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'fleet_code' => 'required|min:3|max:10|unique:mtl_fleet',
            'fleet_descr' => 'required|unique:mtl_fleet',
            'max_carton' => 'required|numeric',
            'tonase' => 'required|numeric',
            'kubikase' => 'required|numeric'
            );

        $validator = $this->validate($request, $rules);

            $fleet = new MtlFleet;
            $fleet->fleet_code = $request->input('fleet_code');
            $fleet->fleet_descr = $request->input('fleet_descr');
            $fleet->max_carton = $request->input('max_carton');
            $fleet->tonase = $request->input('tonase');
            $fleet->kubikase = $request->input('kubikase');
            $fleet->created_by = Auth::user()->name;
            $fleet->updated_by = Auth::user()->name;
            $fleet->save();

        
         $Success = 'Type Kendaran Baru Berhasil Di Simpan!';
         Session::flash('success', $Success); 
        
        $Fleet = MtlFleet::all();
        return view('master/fleet/index', ['Fleet' => $Fleet]);  
         
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Fleet = MtlFleet::find($id);
        if ($Fleet == null || count($Fleet) == 0) {
            return redirect()->intended('/master/masterfleet');
        }

        return view('master/fleet/edit', ['Fleet' => $Fleet]);   
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
        $Fleet = MtlFleet::findOrFail($id);
        $input = [
            'fleet_descr' => $request['fleet_descr'],
            'tonase' => $request['tonase'],
            'kubikase' => $request['kubikase']
        ];
        $this->validate($request, [
            'fleet_descr' => 'required',
            'max_carton' => 'required|numeric',
            'tonase' => 'required|numeric',
            'kubikase' => 'required|numeric'
        ]);
        MtlFleet::where('id', $id)
            ->update($input);
        
        $Success = 'Type Kendaran Berhasil Di Update!';
        Session::flash('success', $Success); 
            
        $Fleet = MtlFleet::paginate(15);
        return view('master/fleet/index', ['Fleet' => $Fleet]);  
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $fleet_id = $request->input('id');
        MtlCustomerFleet::find($fleet_id)->delete();
        $Fleet = DB::table('custFleet_view')->where('id',$fleet_id)->get();
        Session::put('fleet', $Fleet);
        $Success = 'Type Kendaran Baru Berhasil Di Hapus!';
        Session::flash('success', $Success); 
        return view('master/customer_fleet/show', ['Fleet' => $Fleet]);  
    }

}
