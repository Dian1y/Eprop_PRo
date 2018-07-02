<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlKategoryActivity;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;

class MtlKategoryActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
          $KatAct = MtlKategoryActivity::all();
        return view('master.kategory_activity.index',['KatAct' => $KatAct]);

       //  $custview = MtlCustomerShip::paginate(6);  
       //  $custAttr = DB::table('mtl_customer_attribute')->where('cust_ship_id')->get();
       //  return view('master/customer_attr/index', ['CustomerView' => $custview]);  
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $lgroupid = DB::table('mtl_group_activity')->get();
        return view('master.kategory_activity.create', compact('lgroupid'));
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
         //
       $rules = array(
            'group_id' => 'required|unique:mtl_kategory_activity',
            'kategory_name' => 'required|:mtl_kategory_activity',
            'active' => 'required|:mtl_kategory_activity'
         );

        $validator = $this->validate($request, $rules);

        $GrpAct = new MtlKategoryActivity; 
        $GrpAct->group_id = $request->input('group_id') ;
      // if (is_null($GrpAct)) {        
      //  } else {
      //      $group_id =  $request->input('group_id');   
      //      if (is_null($group_id)) {
    //            $otherErrors = 'Group id Tidak boleh Kosong!';
    //            Session::flash('otherErrors', $otherErrors); 
    //            return back(); 
    //        }
   //     } 
        $GrpAct->kategory_name = $request->input('kategory_name') ;
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

       // $GrpAct = db::table('mtl_group_activity')->select('id')->where('group_code',$request->input('group_code'))->get(); 
      //      foreach ($GrpAct as $key => $value) {
       //                 $idgroup = $value->id;
       //             }  

        $Success = 'Data Kategory Activity berhasil di input!';
        Session::flash('success', $Success); 
        
        $KatAct = MtlKategoryActivity::all();
        return view('master.kategory_activity.index',['KatAct' => $KatAct]);
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
        $lgroupid = DB::table('mtl_group_activity')->get();
        $edit = MtlKategoryActivity::find($id);
        return view('master.kategory_activity.edit',['EditKat' => $edit],compact('lgroupid'));
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
         $GrpAct = MtlKategoryActivity::findOrFail($id);
        $input = [
            'group_id' => $request['group_id'],
            'kategory_name' => $request['kategory_name']
        ];
        
        $this->validate($request, [
            'group_id' => 'required',
            'kategory_name' => 'required'
        ]);

        MtlKategoryActivity::where('id', $id)
            ->update($input);
        
        $Success = 'Kategory Activity berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $KatAct = MtlKategoryActivity::all();
        return view('master.Kategory_activity.index', ['GrpAct' => $KatAct]); 
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
