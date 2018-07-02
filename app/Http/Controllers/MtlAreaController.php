<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlArea;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;


class MtlAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
         $GrpAct= MtlArea::all();
        return view('master.area.index',['GrpArea' => $GrpAct]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
         $lregion = DB::table('mtl_region')->get();
         $lsubregion = DB::table('mtl_subregion')->get();
         return view('master.area.create', compact('lregion'), compact('lsubregion'));
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
            'id_region' => 'required|:mtl_area',
            'id_subregion' => 'required|:mtl_area',
            'area' => 'required|:mtl_area',
            'active' => 'required|:mtl_area'
         );

        $validator = $this->validate($request, $rules);

        $GrpAct = new MtlArea; 
        $GrpAct->id_region = $request->input('id_region') ; 
        $GrpAct->id_subregion = $request->input('id_subregion') ;
        $GrpAct->area = $request->input('area') ;
        if ($request->input('active') == 'on') 
            { 
                $GrpAct->active = 'Y';
            }
            else
            { 
                $GrpAct->active = 'N';
            }    
        $GrpAct->created_by = Auth::user()->id; 
        $GrpAct->updated_by = Auth::user()->id;
        $GrpAct->save();

        $Success = 'Data Area berhasil di input!';
        Session::flash('success', $Success); 
        
        $KatAct = MtlArea::all();
        return view('master.area.index',['KatAct' => $KatAct]);
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
         $lregion = DB::table('mtl_region')->get();
         $lsubregion = DB::table('mtl_subregion')->get();
        $edit = MtlArea::find($id);
        return view('master.area.edit',['EditArea' => $edit],compact('lregion','lsubregion'));
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
        $GrpArea = MtlArea::findOrFail($id);
        $input = [
            'id_region' => $request['id_region'],
             'id_subregion' => $request['id_subregion']
        ];
        $this->validate($request, [
            'id_region' => 'required',
            'id_subregion' => 'required'
        ]);
        MtlArea::where('id', $id)
            ->update($input);
        
        $Success = 'Area berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $KatAct = MtlArea::all();
        return view('master.Area.index', ['GrpArea' => $KatAct]); 
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
