<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlGroupActivity;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;


class GrpActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $GrpAct = MtlGroupActivity::all();
        //dd($GrpAct);
        return view('master.group_activity.index',['GrpAct' => $GrpAct]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $lposition = DB::table('mtl_positions')->get();
        return view('master.group_activity.create', compact('lposition'));
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
            'group_name' => 'required|unique:mtl_group_activity'
            );

        $validator = $this->validate($request, $rules); 

        $grpAct = new MtlGroupActivity;
        $grpAct->group_name = $request->input('group_name');
        $grpAct->created_by = Auth::user()->id;
        $grpAct->updated_by = Auth::user()->id;
        $grpAct->save();

        $Success = 'Data Group Activity berhasil di Input!';
        Session::flash('success', $Success); 

        $GrpAct = MtlGroupActivity::all(); 
        return view('master.group_activity.index',['GrpAct' => $GrpAct]);

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
        $edit = MtlGroupActivity::find($id);
        return view('master.group_activity.edit',['EditGrp' => $edit]);
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
        $GrpAct = MtlGroupActivity::findOrFail($id);
        $input = [
            'group_name' => $request['group_name']
        ];
        $this->validate($request, [
            'group_name' => 'required' 
        ]);
        MtlGroupActivity::where('id', $id)
            ->update($input);
        
        $Success = 'Group Aktivity berhasil di Edit';
        Session::flash('success', $Success); 
            
        $GrpAct = MtlGroupActivity::all(); 
        return view('master.group_activity.index',['GrpAct' => $GrpAct]);
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
