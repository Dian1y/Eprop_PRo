@extends('layouts.base') 

@section('content_header') 
	     <h1>
        List Of Fleet
        <small>Daftar Kendaraan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Fleet</a></li>
        <li class="active">Master Fleet</li>
      </ol>  
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
     	<div class="box-header">
		     <div class="col-sm-4">
		       <a class="btn btn-primary" href="{{ route('masterfleet.create') }}">Add Fleet</a>
			 </div>
		 </div>
     	<div class="col-xs-10">
             <div class="box-body">
            	<table id="tblfleet" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Kode Fleet</th>
                      <th>Description</th>
                      <th>Max Karton</th>
                      <th>Kubikase</th>
                      <th>Tonase</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                      @foreach($Fleet as $value)
                      <?php $i++ ?>
                      <tr>
                      	<td> {{ $i }} </td>
                        <td> {{ ($value->fleet_code) }} </td>
                        <td> {{ $value->fleet_descr }} </td>
                        <td> {{ number_format($value->max_carton, 2, ',', '.') }} </td>
                        <td> {{ number_format($value->tonase, 2, ',', '.') }} </td>
                        <td> {{ number_format($value->kubikase, 2, ',', '.') }} </td>
                        <td><a href="{{ route('masterfleet.edit',$value->id) }}" class="btn btn-success btn-sm">Edit</a></td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>  
              </div>  
            </div> 
     <!--end box danger -->
     </div>
   </div>
</div>
@stop
 