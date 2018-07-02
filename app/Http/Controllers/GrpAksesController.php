<?php

namespace App\Http\Controllers;
use Response; 
use Illuminate\Http\Request;
use App\MtlGroupAkses;
use App\MtlDetilAkses;
use App\MtlGroupView;
use Illuminate\Support\Facades\Auth;
use Session;
use DB;



class GrpAksesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $GrpAks = MtlGroupView::all();
        //dd($GrpAct);
        return view('master.group_akses.index',['GrpAks' => $GrpAks]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $lcompany = DB::table('mtl_company')->get(); 
        $ldivisi = DB::table('mtl_division')->get(); 
        $lkategory = DB::table('mtl_kategory_activity')->get(); 
        $lmarkettype = DB::table('mtl_type_market')->get(); 
        $lbrand = DB::table('mtl_brand_produk')->get(); 
        $laccount = DB::table('mtl_mt_channel')->get(); 
        return view('master.group_akses.create',compact('lcompany','ldivisi','lkategory','lmarkettype','lbrand','laccount'));

        //return view('master.persons.create', compact('lposition', 'lregion', 'lsubregion', 'ljob'));
        
        
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
             'group_akses_code' => 'required|unique:mtl_group_akses',
             'group_akses_descr' => 'required|:mtl_group_akses',
             'active' => 'required|:mtl_group_akses'
            );

       
        $GrpAks = new MtlGroupAkses;
        $GrpAks->group_akses_code = $request->input('group_akses_code');
        $GrpAks->group_akses_descr = $request->input('group_akses_descr');
          if ($request->input('active') == 'on') 
            { 
                $GrpAks->active = 'N';
            }
            else
            { 
                $GrpAks->active = 'Y';
            }    
        $GrpAks->created_by = Auth::user()->id;
        $GrpAks->updated_by = Auth::user()->id;
        $GrpAks->save();

        $group_id = db::table('mtl_group_akses')->select('id')->where('group_akses_descr',$request->input('group_akses_descr'))->get(); 
            foreach ($group_id as $key => $value) {
                        $idgroup = $value->id;
                    }  
//company
          if ($request->input('allcompany') == 'on') {
              
                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'COMPANY'; 
                $detakses->akses_name = '';
               // $detakses->akses_key = 'COMPANY';
                $detakses->full_akses_flag = 'Y';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save();                
                 
          }  else {
 
                $company_id = db::table('mtl_company')->whereIn('id', $request->input('company_code'))->get();
                foreach ($company_id as $key => $value) {
                    # code...
                    $detakses = new MtlDetilAkses;
                    $detakses->group_id = $idgroup;
                    $detakses->akses_key = 'COMPANY';
                    $detakses->akses_id =  $value->id;
                    $detakses->akses_name =  $value->company_name;
                    $detakses->akses_code =  $value->company_code;
                    $detakses->full_akses_flag = 'N';
                    $detakses->created_by = Auth::user()->id;
                    $detakses->updated_by = Auth::user()->id;
                    $detakses->save();  
                }    
          }      
//divisi

        if ($request->input('alldivisi') == 'on') {
              
                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'DIVISI'; 
                $detakses->akses_name = '';
               // $detakses->akses_key = 'COMPANY';
                $detakses->full_akses_flag = 'Y';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save();                
                 
          }  else {
 
                $divisi_id = db::table('mtl_division')->whereIn('id', $request->input('division_code'))->get();
                foreach ($divisi_id as $key => $value) {
                    # code...
                    $detakses = new MtlDetilAkses;
                    $detakses->group_id = $idgroup;
                    $detakses->akses_key = 'DIVISI';
                    $detakses->akses_id =  $value->id;
                    $detakses->akses_name =  $value->division_name;
                    $detakses->akses_code =  $value->division_code;
                    $detakses->full_akses_flag = 'N';
                    $detakses->created_by = Auth::user()->id;
                    $detakses->updated_by = Auth::user()->id;
                    $detakses->save();  
                }    
          }  

//kategory activity
if ($request->input('allkategory') == 'on') {
              
                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'KATEGORY'; 
                $detakses->akses_name = '';
               // $detakses->akses_key = 'COMPANY';
                $detakses->full_akses_flag = 'Y';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save();                
                 
          }  else {
 
                $divisi_id = db::table('mtl_kategory_activity')->whereIn('id', $request->input('id'))->get();
                foreach ($divisi_id as $key => $value) {
                    # code...
                    $detakses = new MtlDetilAkses;
                    $detakses->group_id = $idgroup;
                    $detakses->akses_key = 'KATEGORY';
                    $detakses->akses_id =  $value->id;
                    $detakses->akses_name =  $value->kategory_name;
                    $detakses->akses_code =  $value->id;
                    $detakses->full_akses_flag = 'N';
                    $detakses->created_by = Auth::user()->id;
                    $detakses->updated_by = Auth::user()->id;
                    $detakses->save();  
                }    
          }    

//Market type
          if ($request->input('allmarket') == 'on') {
              
                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'MARKET TYPE'; 
                $detakses->akses_name = '';
               // $detakses->akses_key = 'COMPANY';
                $detakses->full_akses_flag = 'Y';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save();                
                 
          }  else {
 
                $divisi_id = db::table('mtl_type_market')->whereIn('id', $request->input('type_market_code'))->get();
                foreach ($divisi_id as $key => $value) {
                    # code...
                    $detakses = new MtlDetilAkses;
                    $detakses->group_id = $idgroup;
                    $detakses->akses_key = 'MARKET TYPE';
                    $detakses->akses_id =  $value->id;
                    $detakses->akses_name =  $value->description;
                    $detakses->akses_code =  $value->id;
                    $detakses->full_akses_flag = 'N';
                    $detakses->created_by = Auth::user()->id;
                    $detakses->updated_by = Auth::user()->id;
                    $detakses->save();  
                }    
          }        


//Brand
          if ($request->input('allbrand') == 'on') {
              
                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'BRAND PRODUK'; 
                $detakses->akses_name = '';
               // $detakses->akses_key = 'COMPANY';
                $detakses->full_akses_flag = 'Y';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save();                
                 
          }  else {
 
                $divisi_id = db::table('mtl_brand_produk')->whereIn('id', $request->input('brand_code'))->get();
                foreach ($divisi_id as $key => $value) {
                    # code...
                    $detakses = new MtlDetilAkses;
                    $detakses->group_id = $idgroup;
                    $detakses->akses_key = 'BRAND PRODUK';
                    $detakses->akses_id =  $value->id;
                    $detakses->akses_name =  $value->brand_name;
                    $detakses->akses_code =  $value->id;
                    $detakses->full_akses_flag = 'N';
                    $detakses->created_by = Auth::user()->id;
                    $detakses->updated_by = Auth::user()->id;
                    $detakses->save();  
                }    
          }    

//Account 
          if ($request->input('allaccount') == 'on') {
              
                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'MT ACCOUNT'; 
                $detakses->akses_name = '';
               // $detakses->akses_key = 'COMPANY';
                $detakses->full_akses_flag = 'Y';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save();                
                 
          }  else {
 
                $divisi_id = db::table('mtl_mt_channel')->whereIn('id', $request->input('account_code'))->get();
                foreach ($divisi_id as $key => $value) {
                    # code...
                    $detakses = new MtlDetilAkses;
                    $detakses->group_id = $idgroup;
                    $detakses->akses_key = 'MT ACCOUNT';
                    $detakses->akses_id =  $value->id;
                    $detakses->akses_name =  $value->account_name;
                    $detakses->akses_code =  $value->id;
                    $detakses->full_akses_flag = 'N';
                    $detakses->created_by = Auth::user()->id;
                    $detakses->updated_by = Auth::user()->id;
                    $detakses->save();  
                }    
          }        



  //flag executor            
         if ($request->input('flag_executor') == 'on') {
                $executor = 'Y';
                $executor_id = 1;
           } else {
                $executor = 'N';
                $executor_id = 0;
           }

                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'EXECUTOR';
                $detakses->akses_name =  $executor; 
                $detakses->akses_code =  $executor_id;               
                $detakses->full_akses_flag = 'N';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save(); 

 if ($request->input('flag_originator') == 'on') {
                $originator = 'Y';
                $originator_id =1;
           } else {
                $originator = 'N';
                $originator_id =0;
           }

                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'ORIGINATOR';
                $detakses->akses_name =  $originator;
                $detakses->akses_code =  $originator_id;              
                $detakses->full_akses_flag = 'N';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save();            
        
        if ($request->input('flag_approver') == 'on') {
                $approver = 'Y';
                $approver_id =1;
           } else {
                $approver = 'N';
                $approver_id =0;
           }

                $detakses = new MtlDetilAkses;
                $detakses->group_id = $idgroup;
                $detakses->akses_key = 'APPROVER';
                $detakses->akses_name =  $approver; 
                $detakses->akses_code =  $approver_id;               
                $detakses->full_akses_flag = 'N';
                $detakses->created_by = Auth::user()->id;
                $detakses->updated_by = Auth::user()->id;
                $detakses->save();            

        $Success = 'Data Group Akses berhasil di simpan!';
        Session::flash('success', $Success); 
        
        $GrpAks = MtlGroupView::all();
        return view('master/group_akses/index', ['GrpAks' => $GrpAks]); 
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
       
       // $group = MtlGroupView::where('group_id',$id)->first(); 
        $lcompany = DB::table('mtl_company')->get(); 
       // $lregion = DB::select('SELECT * from get_person_region(?)', [$id]);
        $ldivisi = DB::table('mtl_division')->get(); 
        $lkategory = DB::table('mtl_kategory_activity')->get(); 
        $lmarkettype = DB::table('mtl_type_market')->get(); 
        $lbrand = DB::table('mtl_brand_produk')->get(); 
         $group = MtlGroupView::where('group_id',$id)->first();
        return view('master.group_akses.edit',['group' => $group],compact('lcompany','ldivisi', 'lkategory', 'lmarkettype', 'lbrand'));

       // $edit = MtlGroupView::find($id);
       // return view('master.group_akses.edit',['EditGrp' => $edit]);
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
         $group = MtlGroupAkses::findOrFail($id);
        $input = [
            'group_akses_code' => $request['group_akses_code'],
            'group_akses_descr' => $request['group_akses_descr'],
            'active' => $request['active']
        ];
        //dd(group_akses_code);
        $this->validate($request, [
            'group_akses_code' => 'required',
            'group_akses_descr' => 'required',
            'active' => 'required'
        ]);
        //dd(group_akses_code);
        MtlGroupAkses::where('id', $id)
            ->update($input);
        
        $Success = 'Group Akses berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $group = MtlGroupAkses::all();
        return view('master.group_akses.index', ['group' => $group]);
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

    public function getbrand($company_code)
    { 

        $lbrand = DB::table('mtl_brand_produk')
                      ->where("company_id",$company_code)
                      ->select("brand_name","id")
                      ->get();
 
       // dd($lbrand);
        return Response::json($lbrand);
    }
}
