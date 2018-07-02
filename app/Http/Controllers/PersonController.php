<?php

namespace App\Http\Controllers;

use Response; 
use App\MtlPerson;
use App\MtlPersonView;
use App\MtlPersonsArea;
//use Request;
use Validator; 
use Session;
use Excel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Mail;
use App\Mail\CMOMail;
use DB; 

class PersonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Persons = DB::table('person_view')->get();   
        return view('master/persons/index', ['Persons' => $Persons]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $lposition = DB::table('position_view')->get(); 
        $lregion = DB::table('mtl_region')->get(); 
        $lsubregion = DB::table('mtl_subregion')->get(); 
        $ljob = DB::table('mtl_jobs')->get();
        $group_akses = DB::table('mtl_group_akses')->get(); 
        $person_type = DB::table('person_type_view')->get(); 

        /**$email = 'dian.yullianti@enesis.com';
        try {
            Mail::to('dian.yulianti@enesis.com')->send(new CMOMail());
            echo 'Mail send successfully';
        } catch (\Exception $e) {
            echo 'Error - '.$e;
        } **/

        return view('master.persons.create', compact('lposition', 'lregion', 'lsubregion', 'ljob','group_akses','person_type' ));
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
            'name' => 'required|unique:mtl_persons', 
            'position_id' => 'required',
            'position_id' => 'required',
            'email' => 'required|unique:mtl_persons',
            'position_id' => 'required' 
            );


        dd($request->all());
        
        $regid =  $request->input('region_id');        

        if (is_null($regid)) {
             
        } else {

            $areaid =  $request->input('area_id');   
            if (is_null($areaid)) {
                $otherErrors = 'Area Tidak boleh Kosong!';
                Session::flash('otherErrors', $otherErrors); 
                return back(); 
            }
            

        }

        $validator = $this->validate($request, $rules);

            $person = new MtlPerson;
            $person->name = $request->input('name');
            $person->position_id = $request->input('position_id');
            $person->email = $request->input('email');
            $person->job_id = $request->input('job_id');
            if ($request->input('status') == 'on') 
            { 
                $person->status = 'A';
            }
            else
            { 
                $person->status = 'I';
            }    
            $person->group_akses_id = $request->input('group_akses');
            $person->person_type = $request->input('person_type');
            $person->created_by = Auth::user()->id;
            $person->updated_by = Auth::user()->id;
            $person->save();

            //save to mtl_person_area
             if (!is_null($regid)) { 
                $region_id = array(
                    'area_id' => 'required', 
                    'person_id' => 'required' 
                );

                 $validator = "";


                $persons_id = db::table('mtl_persons')->select('id')->where('name',$request->input('name'))->get(); 
                foreach ($persons_id as $key => $value) {
                            $idperson = $value->id;
                        }  

                $areas =$request->input('area_id'); 
                $arealist=""; 
                foreach ($areas as $value){
                    if ($arealist == "") {
                        $arealist = $value;    
                    } else {
                        $arealist = $arealist . ',' . $value;    
                    }
                
                }  
                
                $region_id = db::table('mtl_area')->whereIn('id',$areas)->get();
                foreach ($region_id as $value){
                    $save_area = [
                        'person_id' => $idperson,
                        'area_id' => $value->id,
                        'region_id' => $value->id_region,
                        'subregion_id' => $value->id_subregion,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id

                    ];
                   
                    DB::table('mtl_persons_area')->insert(array($save_area));
                   // $save_area=array_push($samtl_persons_areave_area, $save_area);
                } 
                
            }    
        
        $Success = 'Data Personnel Baru berhasil di simpan!';
        Session::flash('success', $Success); 
        
        $Persons = MtlPersonView::all();
        return view('master/persons/index', ['Persons' => $Persons]); 
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
        $persons = MtlPersonView::where('person_id',$id)->first(); 
        $larea = DB::select('SELECT * from get_person_area(?)', [$id]);
        $lsubregion = DB::select('SELECT * from get_person_subregion(?)', [$id]);
        $lregion = DB::select('SELECT * from get_person_region(?)', [$id]);
        $ljob = DB::table('mtl_jobs')->get(); 
        $lposition = DB::table('position_view')->get(); 
        $group_akses = DB::table('mtl_group_akses')->get(); 
        $person_type = DB::table('person_type_view')->get(); 
        foreach ($persons as $key => $value) {
            $statusAktif = $value['status'];
            if ($statusAktif == 'Aktif') {
                $status = 'Y';
            } else {
                $status = 'N';
            }
        }

        return view('master/persons/edit', compact('status','persons','lposition', 'lregion', 'lsubregion', 'ljob','larea', 'group_akses', 'person_type'));  
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

               
        $rules = array(
            'name' => 'required', 
            'position_id' => 'required',
            'position_id' => 'required',
            'email' => 'required',
            'position_id' => 'required' 
            );

        $regid =  $request->input('region_id');        

        if (is_null($regid)) {
             
        } else {

            $areaid =  $request->input('area_id');   
            if (is_null($areaid)) {
                $otherErrors = 'Area Tidak boleh Kosong!';
                Session::flash('otherErrors', $otherErrors); 
                return back(); 
            }
            

        }

        $validator = $this->validate($request, $rules);



            $person = new MtlPerson;


            if ($request->input('status') == 'on') { 
                $status = 'Y';
            }
            else { 
                $status = 'N';
            };

            $person = ['name' => $request->input('name'),
                        'position_id' => $request->input('position_id'),
                        'email' => $request->input('email'),
                        'job_id' => $request->input('job_id'),
                        'group_akses_id' => $request->input('group_akses'),
                        'person_type' => $request->input('person_type'),
                        'active' => $status,   
                        'updated_by' => Auth::user()->id];
            
            MtlPerson::find($id)->update($person);

            //save to mtl_person_area
            if (!is_null($regid)) { 
                $region_id = array(
                    'area_id' => 'required', 
                    'person_id' => 'required' 
                );

                 $validator = "";


                $persons_id = db::table('mtl_persons')->select('id')->where('name',$request->input('name'))->get(); 
                foreach ($persons_id as $key => $value) {
                            $idperson = $value->id;
                        }  

                $areas =$request->input('area_id'); 
                $arealist=""; 
                
                foreach ($areas as $value){
                    if ($arealist == "") {
                        $arealist = $value;    
                    } else {
                        $arealist = $arealist . ',' . $value;    
                    }
                
                }  
                

                $deletedRows = MtlPersonsArea::where('person_id', $id)->delete(); 

                $region_id = db::table('mtl_area')->whereIn('id',$areas)->get();
                foreach ($region_id as $value){
                    $save_area = [
                        'person_id' => $idperson,
                        'area_id' => $value->id,
                        'region_id' => $value->id_region,
                        'subregion_id' => $value->id_subregion,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id

                    ];
                   
                    DB::table('mtl_persons_area')->insert(array($save_area));
                   // $save_area=array_push($samtl_persons_areave_area, $save_area);
                } 
                
            }    
        
        $Success = 'Data Personnel Baru berhasil di simpan!';
        Session::flash('success', $Success); 
        
        $Persons = MtlPersonView::all();
        return view('master/persons/index', ['Persons' => $Persons]);  
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

    /** 
    *   Dependent select2 query
    */

    public function getsubregion($region)
    { 
        $lsubregion = DB::table('mtl_subregion')
                      ->where("id_region",$region)
                      ->select("subregion","id")
                      ->get();

        //dd($lsubregion);
        return Response::json($lsubregion);
    }
 

    public function getarea($subregion)
    {
        $larea = DB::table('mtl_area')
                      ->where("id_subregion",$subregion)
                      ->select("area","id")
                      ->get();

       return Response::json($larea);
    }
}
