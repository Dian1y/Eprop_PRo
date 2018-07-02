<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Product;

Class FileController extends Controller {

	public function importExportExcelORCSV() {
		return view('file_import_export');
	}

	public function importFileIntoDB(Request $request){
        if($request->hasFile('sample_file')){
            $path = $request->file('sample_file')->getRealPath();  
            $data = Excel::load($path, function($reader) {})->get();
            dd($data);

            if($data->count()){
                foreach ($data as $key => $value) {
                    $arr[] = ['name' => $value->name, 'details' => $value->details];
                }
                
                if(!empty($arr)){
                    dd($arr);
                    \DB::table('products')->insert($arr);             
                    return back()->compact($arr);   
                }
            }
        }

    } 


	Public function downloadExcelFile($type) {
		$products = Product::get()->toArray();

		return \Excel::create('expertphp_demo', function($excel) use($products) {
			$excel->sheet('sheet name', function($sheet) use ($products) {
				$sheet->fromArray($products);
			});

		})->download($stype);
	}

}