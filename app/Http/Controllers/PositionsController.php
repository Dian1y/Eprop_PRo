<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlPositions;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;

class PositionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $GrpAct = MtlPositions::all();
        return view('master.positions.index',['GrpAct' => $GrpAct]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('master.positions.create');
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
            'position_name' => 'required|unique:mtl_positions',
            'active' => 'required'
            );

        $validator = $this->validate($request, $rules);

        $GrpAct = new MtlPositions; 
        $GrpAct->position_name = $request->input('position_name') ;
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

        $Success = 'Data Posisi berhasil di input!';
        Session::flash('success', $Success); 
        
        $GrpAct = MtlPositions::all();
        return view('master.positions.index',['GrpAct' => $GrpAct]);
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
        $edit = MtlPositions::find($id);
        return view('master.positions.edit',['EditJob' => $edit]);
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
        $GrpAct = MtlPositions::findOrFail($id);
        $input = [
            'position_name' => $request['position_name']
        ]; 
        $this->validate($request, [
            'position_name' => 'required'
        ]);
        
        MtlPositions::where('id', $id)
            ->update($input);
        
        $Success = 'Position berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $GrpAct = MtlPositions::all();
        return view('master.positions.index', ['GrpAct' => $GrpAct]);
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
