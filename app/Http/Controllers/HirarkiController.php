<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Redirect;
use Validator;
use Response;
use Session; 
use DB;


class HirarkiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
        Session::forget('HirarkiStruc');  
        Session::forget('ArrHirarki');
        
        $hirarkiType = DB::table('fnd_values')->where('group_value' , '=', 'Hirarki_type')->get();
        //dd($hirarkiType);
        $lposition = DB::table('mtl_positions')->get(); 
        return view('master.hirarki.create',compact('hirarkiType', 'lposition'));
    }

    public function saveDraft(Request $request) 
    {
        //validation

        $HirarkiPos = [
            'hirarki_name' => $request->hirarki_name, 
            'hirarki_descr' => $request->hirarki_descr, 
            'hirarki_type' => $request->hirarki_type,
            'hirarki_division' => $request->hirarki_division,
            'hirarki_brand' => $request->hirarki_brand,
            'parent_pos_id' => $request->parent_pos_id,
            'subordinate_pos_id' => $request->subordinate_pos_id
        ];

        Session::put('HirarkiStruc',$HirarkiPos); 
        return $request;      
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $draft = $request->get('draft',false);
        $up = $request->get('up',false);
        $down = $request->get('down',false);
        if($draft) {
            //validation
            $input = array('parent_pos_id'   =>  $request->input('parent_pos_id'));
            $rules = array('parent_pos_id' => 'required');
            $messages = array('required' => 'parent Pos ID Harus diisi');
            $validation = Validator::make($input, $rules, $messages); 
            if ($validation->fails()){ //validation fail
                $otherErrors = 'Parent Position Tidak Boleh Kosong!';
                Session::flash('otherErrors', $otherErrors);  
            } else {
               $this->saveDraft($request); 
            }

        } elseif ($up) {
            $this->up($request); 
        } elseif ($down) {
            $this->down($request); 
        }
        
        $hirarki_typename = DB::table('fnd_values')->where('id',$request->hirarkiType)->first();
        $parentpos_name = DB::table('mtl_positions')->where('id',$request->parent_pos_id)->first();
        $subord_name = DB::table('mtl_positions')->where('id',$request->subordinate_pos_id)->first();
                
        $parent_name = $parentpos_name->position_name;
        if (!empty($subord_name)){
                $sub_name = $subord_name->position_name;
        } else {
                $sub_name = null;
        }

        Session::forget('ArrHirarki');
        $ArrHirarki[] = [
            'hirarki_name' => $request->hirarki_name, 
            'hirarki_descr' => $request->hirarki_descr, 
            'hirarki_type' => $request->hirarki_type,
            'hirarki_division' => $request->hirarki_division,
            'hirarki_brand' => $request->hirarki_brand,
            'parent_pos_id' => $request->parent_pos_id,
            'subordinate_pos_id' => $request->subordinate_pos_id,
            'hirarki_typename' => $hirarki_typename,
            'parentpos_name' => $parent_name,
            'subord_name' => $sub_name,
            'ParentHolder' => $request->ParentHolder,
            'subOrdHolder' => $request->subOrdHolder,
        ];

        Session::put('ArrHirarki', $ArrHirarki);
        $hirarkiType = DB::table('fnd_values')->where('group_value' , '=', 'Hirarki_type')->get();
        //dd($hirarkiType);
        $lposition = DB::table('mtl_positions')->get(); 
        return view('master.hirarki.create',compact('hirarkiType', 'lposition'));
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


    public function getHolder($posID)
    {
        $getHolder = DB::table('CountHolder_view')->where('id','=',$posID)->get()->first();
        $holder= $getHolder->holder;

        return Response::json($holder);  
    }

    public function up(Request $request)
    {    
         Session::forget('ArrHirarki');
         if (Session::has('HirarkiStruc')) {
            $structureH = Session('HirarkiStruc');    
            if ($request->subordinate_pos_id == null) {
                $otherErrors = 'Posisi ini tidak Mempunyai Subordinate ';
                Session::flash('otherErrors',$otherErrors); 

                $hirarki_typename = DB::table('fnd_values')->where('id',$request->hirarkiType)->first();
                $parentpos_name = DB::table('mtl_positions')->where('id',$request->parent_pos_id)->first();
                $subord_name = DB::table('mtl_positions')->where('id',$request->subordinate_pos_id)->first();
                $parent_name = $parentpos_name->position_name;
                if (!empty($subord_name)){
                        $sub_name = $subord_name->position_name;
                } else {
                        $sub_name = null;
                }

                $ArrHirarki[] = [
                    'hirarki_name' => $request->hirarki_name, 
                    'hirarki_descr' => $request->hirarki_descr, 
                    'hirarki_type' => $request->hirarki_type,
                    'hirarki_division' => $request->hirarki_division,
                    'hirarki_brand' => $request->hirarki_brand,
                    'parent_pos_id' => $request->parent_pos_id,
                    'subordinate_pos_id' => $request->subordinate_pos_id,
                    'hirarki_typename' => $hirarki_typename,
                    'parentpos_name' => $parent_name,
                    'subord_name' => $sub_name,
                    'ParentHolder' => $request->ParentHolder,
                    'subOrdHolder' => $request->subOrdHolder,
                ];

            } else {
                $hirarki_typename = DB::table('fnd_values')->where('id',$request->hirarkiType)->first();
                $parentpos_name = DB::table('mtl_positions')->where('id',$request->parent_pos_id)->first();
                $subord_name = DB::table('mtl_positions')->where('id',$request->subordinate_pos_id)->first();

                if (!empty($subord_name)){
                        $sub_name = $subord_name->position_name;
                } else {
                        $sub_name = null;
                }

                $ArrHirarki[] = [
                    'hirarki_name' => $request->hirarki_name, 
                    'hirarki_descr' => $request->hirarki_descr, 
                    'hirarki_type' => $request->hirarki_type,
                    'hirarki_division' => $request->hirarki_division,
                    'hirarki_brand' => $request->hirarki_brand,
                    'parent_pos_id' => null,
                    'subordinate_pos_id' => $request->parent_pos_id,
                    'hirarki_typename' => $hirarki_typename,
                    'parentpos_name' => null,
                    'subord_name' => $parent_name,
                    'ParentHolder' => $request->ParentHolder,
                    'subOrdHolder' => $request->subOrdHolder,
                ];
            }

         } else {
            return back();
         }

        Session::put('ArrHirarki', $ArrHirarki); 
        $hirarkiType = DB::table('fnd_values')->where('group_value' , '=', 'Hirarki_type')->get();
        //dd($hirarkiType);
        $lposition = DB::table('mtl_positions')->get(); 
        return view('master.hirarki.create',compact('hirarkiType', 'lposition'));
    }
    

    public function down(Request $request)
    {    
         Session::forget('ArrHirarki');
         if (Session::has('HirarkiStruc')) {
            $structureH = Session('HirarkiStruc');    
            if ($request->parent_pos_id  = null ) {
                $otherErrors = 'Silahkan Isi Posisi';
                Session::flash('otherErrors',$otherErrors); 

                $hirarki_typename = DB::table('fnd_values')->where('id',$request->hirarkiType)->first();
                $subord_name = DB::table('mtl_positions')->where('id',$request->subordinate_pos_id)->first();
                
                if (!empty($subord_name)){
                        $sub_name = $subord_name->position_name;
                } else {
                        $sub_name = null;
                }

                $ArrHirarki[] = [
                    'hirarki_name' => $request->hirarki_name, 
                    'hirarki_descr' => $request->hirarki_descr, 
                    'hirarki_type' => $request->hirarki_type,
                    'hirarki_division' => $request->hirarki_division,
                    'hirarki_brand' => $request->hirarki_brand,
                    'parent_pos_id' => null,
                    'subordinate_pos_id' => $request->parent_pos_id,
                    'hirarki_typename' => $hirarki_typename,
                    'parentpos_name' => null,
                    'subord_name' => $parent_name,
                    'ParentHolder' => $request->ParentHolder,
                    'subOrdHolder' => $request->subOrdHolder,
                ];

            } else {
                $hirarki_typename = DB::table('fnd_values')->where('id',$request->hirarkiType)->first();
                $parentpos_name = DB::table('mtl_positions')->where('id','=', $request->parent_pos_id)->get()->first();
                $subord_name = DB::table('mtl_positions')->where('id',$request->subordinate_pos_id)->first();
                //$parent_name = $parentpos_name->position_name;
                if (!empty($subord_name)){
                        $sub_name = $subord_name->position_name;
                } else {
                        $sub_name = null;
                }

                $ArrHirarki[] = [
                    'hirarki_name' => $request->hirarki_name, 
                    'hirarki_descr' => $request->hirarki_descr, 
                    'hirarki_type' => $request->hirarki_type,
                    'hirarki_division' => $request->hirarki_division,
                    'hirarki_brand' => $request->hirarki_brand,
                    'parent_pos_id' => null,
                    'subordinate_pos_id' => $request->parent_pos_id,
                    'hirarki_typename' => $hirarki_typename,
                    'parentpos_name' => null,
                    'subord_name' => null, //$parent_name,
                    'ParentHolder' => $request->ParentHolder,
                    'subOrdHolder' => $request->subOrdHolder,
                ];

                dd($ArrHirarki);
            }

         } else {
            return back();
         }

        Session::put('ArrHirarki', $ArrHirarki); 
        $hirarkiType = DB::table('fnd_values')->where('group_value' , '=', 'Hirarki_type')->get();
        //dd($hirarkiType);
        $lposition = DB::table('mtl_positions')->get(); 
        return view('master.hirarki.create',compact('hirarkiType', 'lposition'));
    }

}
