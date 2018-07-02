@extends('layouts.base') 

@section('content_header')
	  <h1>
        List of Users
        <small>Daftar Users</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Setting</a></li>
        <li class="active">Users</li>
      </ol>  
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
     	<div class="box-header">
		     <div class="col-sm-4">
		       <a class="btn btn-primary" href="{{ route('masterfleet.create') }}">Add Users</a>
			 </div>
		 </div>
     	<div class="col-xs-10">
             <div class="box-body">
            	<table id="tblfleet" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Nama User</th>
                      <th>Email</th>
                      <th>posisi</th>
                      <th>Role</th> 
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                      @foreach($Users as $value)
                      <?php $i++ ?>
                      <tr>
                      	<td> {{ $i }} </td>
                        <td> {{ ($value->name) }} </td>
                        <td> {{ $value->email }} </td>
                        <td> {{ $value->role }} </td>
                        <td><a href="{{ route('masterfleet.edit',$value->id) }}" class="btn btn-success btn-sm">Detail</a>
                        <a href="{{ route('masterfleet.edit',$value->id) }}" class="btn btn-success btn-sm">Add Role/Akses</a>
                        <a href="{{ route('masterfleet.edit',$value->id) }}" class="btn btn-success btn-sm">Edit Passwd</a></td>
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