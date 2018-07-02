@extends('master.activity.base') 

@section('content_header') 
	     <h1>
        List Of  Activity
        <small>Daftar  Activity</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"> Activity</a></li>
        <li class="active">Master  Activity</li>
      </ol>  
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
     	<div class="box-header">
		     <div class="col-sm-4">
		       <a class="btn btn-primary" href="{{ route('activity.create') }}">Add  Activity</a>
			 </div>
		 </div>
     	<div class="col-xs-10">
             <div class="box-body">
            	<table id="activity" name = "activity" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Group Id</th>
                      <th>Kategory Id</th>
                      <th>Activity Name</th>
                      <th>Activ</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                       @foreach($Act as $value)
                        <?php $i++ ?>
                        <tr>
                        	<td> {{ $i }} </td>
                          <td> {{ ($value->group_id) }} </td>
                          <td> {{ ($value->kategory_id) }} </td>
                          <td> {{ ($value->activity_name) }} </td>
                          <td> {{ ($value->active) }} </td>
                          <td><a href="{{ 
                          route('activity.edit',$value->id) }}
                          " class="btn btn-success btn-sm">Edit</a></td>
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
 