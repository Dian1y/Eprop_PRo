<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlBrandProduk;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;

class BrandProdController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $BrandPro = MtlBrandProduk::all();
        return view('master.brand_produk.index',['BrandPro' => $BrandPro]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
//        $lposition = DB::table('mtl_positions')->get();
//        return view('master.brand_produk.create', compact('lposition'));
        return view('master.brand_produk.create');

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
            'company_id' => 'required',
            'brand_code' => 'required|unique:mtl_brand_produk',
            'brand_name' => 'required|unique:mtl_brand_produk',
            'product_type' => 'required',
            'active' => 'required'
            );

        $validator = $this->validate($request, $rules);

        $BrandPro = new MtlBrandProduk; 
        $BrandPro->company_id = $request->input('company_id') ;
        $BrandPro->brand_code = $request->input('brand_code') ; 
        $BrandPro->brand_name = $request->input('brand_name') ;
        $BrandPro->product_type = $request->input('product_type') ;
        $BrandPro->created_by = Auth::user()->id; 
        $BrandPro->updated_by = Auth::user()->id;
        $BrandPro->active = $request->input('active') ;  

    //    $play = $request->input('active');
    //    if ($play === null ) {
    //        $programmer->play = 'of';
    //    } else {
    //        $programmer->play = 'on';
    //    }


        $BrandPro->save();

        $Success = 'Data Brand Produk berhasil di input!';
        Session::flash('success', $Success); 
        
        $BrandPro = MtlBrandProduk::all();
        return view('master.brand_produk.index',['BrandPro' => $BrandPro]);

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
        $edit = MtlBrandProduk::find($id);
        return view('master.brand_produk.edit',['EditBrand' => $edit]);
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
        $BrandPro = MtlBrandProduk::findOrFail($id);
        $input = [
            'company_id' => $request['company_id'],
            'brand_code' => $request['brand_code'],
            'brand_name' => $request['brand_name'],
            'product_type' => $request['product_type'],
            'active' => $request['active']
        ];
        $this->validate($request, [
            'company_id' => 'required',
            'brand_code' => 'required',
            'brand_name' => 'required',
            'product_type' => 'required',
            'active' => 'required'
        ]);
        MtlBrandProduk::where('id', $id)
            ->update($input);
        
        $Success = 'Brand Produk berhasil di Edit!';
        Session::flash('success', $Success); 
            
        $Fleet = MtlBrandProduk::all();
        return view('master.brand_produk.index', ['BrandPro' => $BrandPro]); 
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
