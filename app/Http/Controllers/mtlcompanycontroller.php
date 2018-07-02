<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlCompany;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;

class mtlcompanycontroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $Cmpny = MtlCompany::all();
        return view('master.company.index',['Cmpny' => $Cmpny]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        
        return view('master.company.create');
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
            'company_name' => 'required|unique:mtl_company'
            );

        $validator = $this->validate($request, $rules);

        $GrpAct = new MtlCompany; 
        $GrpAct->company_code = $request->input('company_code') ; 
         $GrpAct->company_name = $request->input('company_name') ; 
        $GrpAct->created_by = Auth::user()->id; 
        $GrpAct->updated_by = Auth::user()->id;
        $GrpAct->save();

        $Success = 'Data company berhasil di input!';
        Session::flash('success', $Success); 
        
        $GrpAct = MtlCompany::all();
        return view('master.company.index',['GrpAct' => $GrpAct]);

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
        $edit = MtlCompany::find($id);
        return view('master.company.edit',['EditGrp' => $edit]);
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
        $GrpAct = MtlCompany::findOrFail($id);
        $input = [
            'company_name' => $request['company_name']
        ];
        $this->validate($request, [
            'company_name' => 'required'
        ]);
        MtlCompany::where('id', $id)
            ->update($input);
        
        $Success = 'Company berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $GrpAct = MtlCompany::all();
        return view('master.company.index', ['GrpAct' => $GrpAct]); 
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
