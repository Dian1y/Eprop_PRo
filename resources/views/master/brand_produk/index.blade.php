@extends('master.brand_produk.base') 

@section('content_header') 
	     <h1>
        List Of Brand Produk
        <small>Daftar Brand Produk</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Brand Produk</a></li>
        <li class="active">Master Brand Produk</li>
      </ol>  
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
     	<div class="box-header">
		     <div class="col-sm-4">
		       <a class="btn btn-primary" href="{{ route('brand_produk.create') }}">Add Brand Produk</a>
			 </div>
		 </div>
     	<div class="col-xs-10">
             <div class="box-body">
            	<table id="brandprod" name = "brandprod" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Company Id</th>
                      <th>Brand Code</th>
                      <th>Brand Name</th>
                      <th>Product Type</th>
                      <th>Active</th>                      
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                      @if (isset($BrandPro))
                        @foreach($BrandPro as $value)
                        <?php $i++ ?>
                        <tr>
                        	<td> {{ $i }} </td>
                          <td> {{ ($value->company_id) }} </td>
                          <td> {{ ($value->brand_code) }} </td>
                          <td> {{ ($value->brand_name) }} </td>
                          <td> {{ ($value->product_type) }} </td>
                          <td> {{ ($value->active) }} </td>                          
                          <td><a href="{{ 
                          route('brand_produk.edit',$value->id) }}
                          " class="btn btn-success btn-sm">Edit</a></td>
                        </tr>
                        @endforeach
                      @endif
                  </tbody>
                </table>  
              </div>  
            </div> 
     <!--end box danger -->
     </div>
   </div>
</div>
@stop
 