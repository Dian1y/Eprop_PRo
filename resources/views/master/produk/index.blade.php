@extends('master.produk.base') 

@section('content_header') 
	     <h1>
        List Of Produk
        <small>Daftar Produk</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Produk</a></li>
        <li class="active">Master Produk</li>
      </ol>  
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
     	<div class="box-header">
		     <div class="col-sm-4">
		       <a class="btn btn-primary" href="{{ route('produk.create') }}">Add Produk</a>
			 </div>
		 </div>
     	<div class="col-xs-10">
             <div class="box-body">
            	<table id="produk" name = "produk" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Inventory Item Id</th>
                      <th>Brand id</th>
                      <th>Produk Name</th>
                      <th>Active</th>                      
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                      @if (isset($MsProduk))
                        @foreach($MsProduk as $value)
                        <?php $i++ ?>
                        <tr>
                        	<td> {{ $i }} </td>
                          <td> {{ ($value->inventory_item_id) }} </td>
                          <td> {{ ($value->brand_id) }} </td>
                          <td> {{ ($value->produk_name) }} </td>
                          <td> {{ ($value->active) }} </td>                          
                          <td><a href="{{ 
                          route('produk.edit',$value->id) }}
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
 