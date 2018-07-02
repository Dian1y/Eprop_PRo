@extends('layouts.base') 

@section('content_header')
	  <h1>
        Add New Group Activity
        <small>Tambah Group Activity</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Group Activity</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('group_activity.update',['id' => $EditGrp->id]) }}">
  <div class="row">

	<div class="col-xs-12">
	  <div class="box box-danger">
      <input type="hidden" name="_method" value="PATCH">
	    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
	    <br>
        <div class="form-group">
          <label class="col-xs-2 control-label">Group Name</label>
          <div class="col-xs-3">
            <input type="text"  value="{{ $EditGrp->group_name }}" id="group_name" name="group_name"   class="form-control" required/> 
          </div>   
        </div>
	  	<br>
	  </div>
	  <!-- Box Footer -->
      <div class="box-footer">
      		<a href="{{ route('group_activity.index') }}" class="btn btn-danger  btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right">Submit</button>    
      </div>
      <!-- End Of Box Footer --> 
	</div>
  </div> 
</form>
@stop

 