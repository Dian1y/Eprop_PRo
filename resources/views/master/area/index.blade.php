@extends('master.area.base') 

@section('content_header') 
	     <h1>
        List Of Area
        <small>Daftar Area</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Area</a></li>
        <li class="active">Master Area</li>
      </ol>  
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
     	<div class="box-header">
		     <div class="col-sm-4">
		       <a class="btn btn-primary" href="{{ route('area.create') }}">Add Area</a>
			 </div>
		 </div>
     	<div class="col-xs-10">
             <div class="box-body">
            	<table id="grparea" name = "grparea" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Id Region</th>
                       <th>id Subregion</th>
                       <th>Area</th>
                        <th>Active</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                      @if (isset($GrpArea))
                        @foreach($GrpArea as $value)
                        <?php $i++ ?>
                        <tr>
                        	<td> {{ $i }} </td>
                          <td> {{ ($value->id_region) }} </td>
                           <td> {{ ($value->id_subregion) }} </td>
                           <td> {{ ($value->area) }} </td>
                           <td> {{ ($value->active) }} </td>
                          <td><a href="{{ 
                          route('area.edit',$value->id) }}
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
 