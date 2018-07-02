<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlRegion;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;


class MtlRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $KatAct = MtlRegion::all();
        return view('master.region.index',['KatAct' => $KatAct]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
         //
         $rules = array(
            'region' => 'required|:mtl_region'
         );

        $validator = $this->validate($request, $rules);

        $GrpAct = new MtlRegion; 
        $GrpAct->region = $request->input('region') ; 
        // $GrpAct->active = $request->input('active') ;
         if ($request->input('active') == 'on') 
            { 
                $GrpAct->active = 'N';
            }
            else
            { 
                $GrpAct->active = 'Y';
            }    
    
        $GrpAct->created_by = Auth::user()->id; 
        $GrpAct->updated_by = Auth::user()->id;
        $GrpAct->save();

        $Success = 'Data Region berhasil di input!';
        Session::flash('success', $Success); 
        
        $KatAct = MtlRegion::all();
        return view('master.region.index',['KatAct' => $KatAct]);
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
         $edit = MtlRegion::find($id);
        return view('master.region.edit',['EditKat' => $edit]);//
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
        $GrpAct = MtlRegion::findOrFail($id);
        $input = [
            'region' => $request['region']
        ];
        $this->validate($request, [
            'region' => 'required'
        ]);
        MtlRegion::where('id', $id)
            ->update($input);
        
        $Success = 'Region berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $KatAct = MtlRegion::all();
        return view('master.region.index', ['GrpAct' => $KatAct]); 
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
