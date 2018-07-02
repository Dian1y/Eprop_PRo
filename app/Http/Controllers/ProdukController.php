<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\MtlProduk;
use Illuminate\Support\Facades\Auth;
use Validator; 
use Session;
use DB;

class ProdukController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $MsProduk = MtlProduk::all();
        return view('master.produk.index',['MsProduk' => $MsProduk]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('master.produk.create');
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
            'inventory_item_id' => 'required',
            'brand_id' => 'required',
            'produk_name' => 'required|unique:mtl_produk',
            'active' => 'required'
            );

        $validator = $this->validate($request, $rules);

        $MsProduk = new MtlProduk; 
        $MsProduk->inventory_item_id = $request->input('inventory_item_id') ;
        $MsProduk->brand_id = $request->input('brand_id') ; 
        $MsProduk->produk_name = $request->input('produk_name') ;
        $MsProduk->created_by = Auth::user()->id; 
        $MsProduk->updated_by = Auth::user()->id;
        $MsProduk->active = $request->input('active') ;  

    //    $play = $request->input('active');
    //    if ($play === null ) {
    //        $programmer->play = 'of';
    //    } else {
    //        $programmer->play = 'on';
    //    }


        $MsProduk->save();

        $Success = 'Data Produk berhasil di input!';
        Session::flash('success', $Success); 
        
        $MsProduk = MtlProduk::all();
        return view('master.produk.index',['MsProduk' => $MsProduk]);

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
        $edit = MtlProduk::find($id);
        return view('master.produk.edit',['EditBrand' => $edit]);        
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
}
