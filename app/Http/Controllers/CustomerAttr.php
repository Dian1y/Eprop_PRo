<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlCustomerShip;
use App\MtlCustomerPerson;
use App\MtlCustomerAttributeV;
use App\MtlCustomerPIC;
use App\MtlArea;
use App\MtlFleet;
use Redirect;
use Validator; 
use Session; 
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use DB;
use App\MtlCustomerAttribute;
use View; 
use Response; 


class CustomerAttr extends Controller
{
    public function index()
    {
         $custview = DB::table('customer_view')->get();  
        // dd($custview); 
        return view('master/customer_attr/index', ['CustomerView' => $custview]);  
    }

    public function create($id)
    {
        //$custAttr = DB::table('customer_view')->where('cust_ship_id', "=", $id)->get(); 
        $CustomerAttr = MtlCustomerShip::where('cust_ship_id', $id)->first();
        $lfleet= DB::table('customer_fleet_v')->where('cust_ship_id', "=", $id)->select('fleet_id','fleet_code','fleet_descr', 'default_flag')->get();
        
        $custFleet_view =DB::table('customer_fleet_v')->where('cust_ship_id', "=", $id)->where('default_flag', '=', 'Y')->select('default_flag', 'max_carton')->get();
        if (empty($default_fleet)) {
            $default_fleet = 'N';   
            $minOrder = Null;         
        } else {
            $default_fleet = 'Y';
            $minOrder = $custFleet_view->max_carton;
        }

        $larea = MtlArea::all(); 
        return view('master/customer_attr/create', compact('CustomerAttr','lfleet','larea', 'default_fleet','minOrder'));
    }

    public function show($id)
    {

        $CustomerAttr = MtlCustomerAttributeV::where('cust_ship_id', $id)->first(); 
        $PICArea = DB::table('customer_pic_view')->where('cust_ship_id', $id)->get();

        return view('master/customer_attr/show', compact('CustomerAttr','PICArea'));
    }

    public function edit($id)
    {

        $CustomerAttr = MtlCustomerAttributeV::where('cust_ship_id', $id)->first();
        $cust_attrID = $CustomerAttr->cust_attr_id;
        $lfleet= DB::table('customer_fleet_v')->where('cust_ship_id', "=", $id)->select('fleet_id','fleet_code','fleet_descr', 'default_flag')->get();
        
        $larea = MtlArea::all(); 
        $PICArea = DB::table('customer_pic_view')->where('cust_ship_id',$id)->get();
        return view('master/customer_attr/edit', compact('CustomerAttr','lfleet','larea', 'PICArea','cust_attrID'));
    }

    public function storePIC(Request $request) {

         $attrID = db::table('mtl_customer_attribute')->select('id')->where('cust_ship_id',$request->input('cust_ship_id'))->get(); 
                foreach ($attrID as $key => $value) {
                            $cust_attrID = $value->id;
                        }  

        $persons =$request->input('person_id'); 

        $input = [
            'minqtyorder' => $request['minqtyorder'],
            'area_id' => $request['area_id'],
            'default_fleet' => $request['default_fleet']
        ]; 

        MtlCustomerAttribute::where('id', $cust_attrID)
            ->update($input);

        $Piclists =""; 
        foreach ($persons as $value){
            if ($Piclists == "") {
                $Piclists = $value;    
            } else {
                $Piclists = $Piclists . ',' . $value;    
            }
                
        }  

       //  dd($Piclists);
        $persons = DB::table('person_area_view')->whereIn('person_id', $request->input('person_id'))->where('area_id','=',$request->input('area_id'))->get();

        foreach ($persons as $value){
            $save_persons = [
                        'cust_ship_id' =>  $request->input('cust_ship_id'),
                        'cust_attr_id' => $cust_attrID,
                        'person_id' => $value->person_id,
                        'position_id' => $value->position_id,
                        'area_id' => $request->input('area_id'),
                        'job_id' => $value->job_id,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id
                    ];
                   
                    
                    DB::table('mtl_customer_person')->insert(array($save_persons));
                   // $save_area=array_push($samtl_persons_areave_area, $save_area);
                }  

        $id = $request->input('cust_ship_id');        
        $CustomerAttr = MtlCustomerAttributeV::where('cust_ship_id', $id)->first();
        $cust_attrID = $CustomerAttr->cust_attr_id;
        $lfleet= DB::table('customer_fleet_v')->where('cust_ship_id', "=", $id)->select('fleet_id','fleet_code','fleet_descr', 'default_flag')->get();
        
        $larea = MtlArea::all(); 
        $PICArea = DB::table('customer_pic_view')->where('cust_ship_id',$id)->get();
        return back();   

    }

    public function store(Request $request)
    {        


    	//Mtl Attribute
        $rules = array(
            'area_id' => 'required',
            'person_id' => 'required',
            'minqtyorder' => 'required|numeric',
            'default_fleet' => 'required',
            'cust_ship_id' => 'required|exists:mtl_customer_ship_to|unique:mtl_customer_attribute'
            );

        if (!$request->default_fleet) {
            $otherErrors = 'Default Fleet Harus Diisi jika Kosong Input di Customer Fleet!';
            Session::flash('otherErrors', $otherErrors); 
            return back();

        }

        $validator = $this->validate($request, $rules);

        $id = $request->input('cust_ship_id');
        //Insert int MtlCustomerAttribute

        $cust_attr = new MtlCustomerAttribute;
        $cust_attr->cust_ship_id = $request->input('cust_ship_id');
        $cust_attr->area_id = $request->input('area_id');
        $cust_attr->minqtyorder = $request->input('minqtyorder'); 
        $cust_attr->default_fleet = $request->input('default_fleet'); 
        $cust_attr->created_by = Auth::user()->id;
        $cust_attr->updated_by = Auth::user()->id;
        $cust_attr->save();

        
        $attrID = db::table('mtl_customer_attribute')->select('id')->where('cust_ship_id',$request->input('cust_ship_id'))->get(); 
                foreach ($attrID as $key => $value) {
                            $cust_attrID = $value->id;
                        }  

        $persons =$request->input('person_id'); 

        $Piclists =""; 
        foreach ($persons as $value){
            if ($Piclists == "") {
                $Piclists = $value;    
            } else {
                $Piclists = $Piclists . ',' . $value;    
            }
                
        }  

       //  dd($Piclists);
        $persons = DB::table('person_area_view')->whereIn('person_id', $request->input('person_id'))->where('area_id','=',$request->input('area_id'))->get();

        foreach ($persons as $value){
            $save_persons = [
                        'cust_ship_id' =>  $request->input('cust_ship_id'),
                        'cust_attr_id' => $cust_attrID,
                        'person_id' => $value->person_id,
                        'position_id' => $value->position_id,
                        'area_id' => $request->input('area_id'),
                        'job_id' => $value->job_id,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id
                    ];
                   
                    
                    DB::table('mtl_customer_person')->insert(array($save_persons));
                   // $save_area=array_push($samtl_persons_areave_area, $save_area);
                }  



        $Success = 'Customer attribute berhasil di simpan!';
        Session::flash('success', $Success); 
        
        $custview = MtlCustomerShip::all();  
        $custAttr = DB::table('mtl_customer_attribute')->where('cust_ship_id')->get();
        return view('master/customer_attr/index', ['CustomerView' => $custview]);  
    }

      public function Update(Request $request, $id)
    {        

        //Mtl Attribute
        $rules = array(
            'area_id' => 'required',
            'person_id' => 'required',
            'minqtyorder' => 'required|numeric',
            'default_fleet' => 'required',
            //'cust_ship_id' => 'required|exists:mtl_customer_ship_to'
            );

        if (!$request->default_fleet) {
            $otherErrors = 'Default Fleet Harus Diisi jika Kosong Input di Customer Fleet!';
            Session::flash('otherErrors', $otherErrors); 
            return back();

        }

        $validator = $this->validate($request, $rules);

        $id = $request->input('cust_ship_id');
        //Insert int MtlCustomerAttribute


        $update_attr = [//'cust_ship_id' => $request->input('cust_ship_id'),
                         '$area_id' => $request->input('area_id'),
                         '$minqtyorder' => $request->input('minqtyorder'), 
                         '$default_fleet' => $request->input('default_fleet'), 
                         '$updated_by' => Auth::user()->id
                        ];

        MtlCustomerAttribute::find($id)->update($update_attr);

        $persons =$request->input('person_id'); 
        $Piclists =""; 
        foreach ($persons as $value){
            if ($Piclists == "") {
                $Piclists = $value;    
            } else {
                $Piclists = $Piclists . ',' . $value;    
            }
                
        }  

       //  dd($Piclists);
        $persons = DB::table('person_area_view')->whereIn('person_id', $request->input('person_id'))->where('area_id','=',$request->input('area_id'))->get();

        $custPerson = MtlCustomerPerson::where('cust_attr_id', '=', $id); 
        $custPerson->delete();
        
        foreach ($persons as $value){
            $save_persons = [
                        'cust_ship_id' =>  $request->input('cust_ship_id'),
                        'cust_attr_id' => $cust_attrID,
                        'person_id' => $value->person_id,
                        'position_id' => $value->position_id,
                        'area_id' => $request->input('area_id'),
                        'job_id' => $value->job_id,
                        'created_by' => Auth::user()->id,
                        'updated_by' => Auth::user()->id
                    ];
                                       
                    DB::table('mtl_customer_person')->insert(array($save_persons));
                   // $save_area=array_push($samtl_persons_areave_area, $save_area);
                }  



        $Success = 'Customer attribute berhasil di simpan!';
        Session::flash('success', $Success); 
        
        $custview = MtlCustomerShip::all();  
        $custAttr = DB::table('mtl_customer_attribute')->where('cust_ship_id')->get();
        return view('master/customer_attr/index', ['CustomerView' => $custview]);  
    }

    public function DeletePIC(Request $request,$id,$cust_ship_id)
    { 
 
        MtlCustomerPerson::where('id',$id)->where('cust_ship_id',$cust_ship_id)->delete();
        $Success = 'Person In Charge berhasil Di Hapus!';
        Session::flash('success', $Success);  

        /*$cust_attrID = $CustomerAttr['cust_attr_id'];
        $lfleet= DB::table('customer_fleet_v')->where('cust_ship_id', "=", $id)->select('fleet_id','fleet_code','fleet_descr', 'default_flag')->get();
        $larea = MtlArea::all(); 
        $PICArea = DB::table('customer_pic_view')->where('cust_ship_id',$id)->get();
        return view('master/customer_attr/edit', compact('CustomerAttr','lfleet','larea', 'PICArea'));*/

        return back();
    }

    public function destroy(Request $request,$id)
    {
        dd($request->all());
        MtlCustomerPerson::where('person_id',$id)->where('cust_ship_id',$request->input('cust_ship_id'))->delete();
        $Success = 'Person In Charge berhasil Di Hapus!';
        Session::flash('success', $Success); 
        $CustomerAttr = MtlCustomerAttributeV::where('cust_ship_id', $id)->first();
        $cust_attrID = $CustomerAttr->cust_attr_id;
        $lfleet= DB::table('customer_fleet_v')->where('cust_ship_id', "=", $id)->select('fleet_id','fleet_code','fleet_descr', 'default_flag')->get();
        $larea = MtlArea::all(); 
        $PICArea = DB::table('customer_pic_view')->where('cust_ship_id',$id)->get();
        return view('master/customer_attr/edit', compact('CustomerAttr','lfleet','larea', 'PICArea'));
    }

    public function getPersonArea($areaID)
    { 
         
        $personArea = DB::table('person_area_view')
                      ->where("area_id",$areaID)
                      ->select("person_id","name")
                      ->get();
 
        return Response::json($personArea);       

    }

    public function getFleetMOQ($fleet_id)
    { 
         
        $MtlFleet = MtlFleet::where('id',$fleet_id)->first();
        $MOQ = $MtlFleet->max_carton;
 
        return Response::json($MOQ);       

    }

    // 
    //controller GetPIC
    //return Datatables::of($personArea)
    //        ->addColumn('action', function ($DelAttr) {
    //            return '<a href="#delete-'.$user->id.'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-delete"></i> Edit</a>';
    //        })
    //        ->editColumn('id', 'ID: {{$id}}')
    //        ->make(true);

}
