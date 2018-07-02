<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlRegion;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;

class RegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Region = DB::table('mtl_region')->get();   
        return view('master/region/index', ['Region' => $Region]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.region.create');
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
            'region' => 'required|unique:mtl_region',
            'active' => 'required'
            );

        $validator = $this->validate($request, $rules);

        $Region = new MtlRegion; 
        $Region->region = $request->input('region') ;
        if ($request->input('active') == 'on') 
            { 
               $Region->active = 'N';
            }
            else
            { 
                $Region->active = 'Y';
            }    
        $Region->created_by = Auth::user()->id; 
        $Region->updated_by = Auth::user()->id;
        $Region->save();

        $Success = 'Data Region berhasil di input!';
        Session::flash('success', $Success); 
        
        $Region = DB::table('mtl_region')->get();   
        return view('master/region/index', ['Region' => $Region]);
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
        $EditReg = MtlRegion::find($id);
        return view('master.region.edit',['EditReg' => $EditReg]);
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
        $Region = MtlRegion::findOrFail($id);

        if ($request->input('active') == 'on') 
            { 
               $active = 'N';
            }
            else
            { 
                $active = 'Y';
            }  

        $input = [
            'region' => $request['region'],
            'active' => $active
        ]; 
        $this->validate($request, [
            'region' => 'required'
        ]);
        
        MtlRegion::where('id', $id)
            ->update($input);
        
        $Success = 'Region berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $Region = MtlRegion::all();
        return view('master.region.index', ['Region' => $Region]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
