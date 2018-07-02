<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlSubRegion;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;


class MtlSubRegionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $SubReg = MtlSubRegion::all();
        return view('master.subregion.index',['SubReg' => $SubReg]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         $lsubreg = DB::table('mtl_region')->get();
        return view('master.subregion.create', compact('lsubreg'));
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
            'id_region' => 'required|unique:mtl_subregion',
            'subregion' => 'required|unique:mtl_subregion',
            'active' => 'required|:mtl_subregion'
         );

        $validator = $this->validate($request, $rules);

        $GrpAct = new MtlSubRegion; 
        $GrpAct->id_region = $request->input('id_region') ; 
        $GrpAct->subregion = $request->input('subregion') ;
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

        $Success = 'Data Sub Region berhasil di input!';
        Session::flash('success', $Success); 
        
        $KatAct = MtlSubRegion::all();
        return view('master.subregion.index',['KatAct' => $KatAct]);
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
        //
        $edit = MtlSubRegion::find($id);
        return view('master.subregion.edit',['EditKat' => $edit]);
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
         $GrpAct = MtlSubRegion::findOrFail($id);
        $input = [
            'id_region' => $request['id_region'],
            'subregion' => $request['subregion']
        ];
        $this->validate($request, [
            'id_region' => 'required',
            'subregion' => 'required'
        ]);
        MtlSubRegion::where('id', $id)
            ->update($input);
        
        $Success = 'Sub Region berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $KatAct = MtlSubRegion::all();
        return view('master.subregion.index', ['GrpAct' => $KatAct]); 
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
