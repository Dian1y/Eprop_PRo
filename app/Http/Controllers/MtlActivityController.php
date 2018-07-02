<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlActivity;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;

class MtlActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $Act = MtlActivity::all();
        return view('master.activity.index',['Act' => $Act]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //return view('master.activity.create');
         $lactivity = DB::table('mtl_group_activity')->get();
         $lkatactivity = DB::table('mtl_kategory_activity')->get();
         return view('master.activity.create', compact('lactivity','lkatactivity'));

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
            'group_id' => 'required|unique:mtl_activity',
            'kategory_id' => 'required|:mtl_activity',
            'activity_name' => 'required|:mtl_activity',
            'active' => 'required|:mtl_activity'
            );

        $validator = $this->validate($request, $rules);

        $Act = new MtlActivity; 
        $Act->group_id = $request->input('group_id') ; 
        $Act->kategory_id = $request->input('kategory_id') ;
        $Act->activity_name = $request->input('activity_name') ;
         if ($request->input('active') == 'on') 
            { 
                $Act->active = 'N';
            }
            else
            { 
                $Act->active = 'Y';
            }    
        $Act->created_by = Auth::user()->id; 
        $Act->updated_by = Auth::user()->id;
        $Act->save();

        $Success = 'Data  Activity berhasil di input!';
        Session::flash('success', $Success); 
        
        $Act = MtlActivity::all();
        return view('master.activity.index',['Act' => $Act]);
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
         $edit = MtlActivity::find($id);
        return view('master.activity.edit',['EditAct' => $edit]);
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
         $Act = MtlActivity::findOrFail($id);
        $input = [
            'group_id' => $request['group_id'],
            'kategory_id' => $request['kategory_id'],
            'activity_name' => $request['activity_name']
        ];
        $this->validate($request, [
            'group_id' => 'required',
            'kategory_id' => 'required',
            'activity_name' => 'required'
        ]);
        MtlActivity::where('id', $id)
            ->update($input);
        
        $Success = 'Activity berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $Act = MtlActivity::all();
        return view('master.activity.index', ['Act' => $Act]);
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
