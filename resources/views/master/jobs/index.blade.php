@extends('master.jobs.base') 

@section('content_header') 
	     <h1>
        List Of Jobs </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Kategory Jobs</a></li>
        <li class="active">Master Jobs</li>
      </ol>  
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
     	<div class="box-header">
		     <div class="col-sm-4">
		       <a class="btn btn-primary" href="{{ route('jobs.create') }}">Add Job</a>
			 </div>
		 </div>
     	<div class="col-xs-10">
             <div class="box-body">
            	<table id="tbjob" name = "tbjob" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Job Code</th>
                      <th>Job Name</th>
                      <!--<th>Job Brand</th>
                      <th>Job Type</th> -->
                      <th>Active</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                      @if (isset($Job))
                        @foreach($Job as $value)
                        <?php $i++ ?>
                        <tr>
                        	<td> {{ $i }} </td>
                          <td> {{ ($value->job_code) }} </td>
                          <td> {{ ($value->job_description) }} </td>
                          <!--<td> {{ ($value->job_brand) }} </td>
                          <td> {{ ($value->job_type) }} </td> -->
                          <td> {{ ($value->active) }} </td>
                          <td><a href="{{ 
                          route('jobs.edit',$value->id) }}
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
 