@extends('master.group_activity.base') 

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
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('group_activity.store') }}">
  <div class="row">

	<div class="col-xs-12">
	  <div class="box box-danger">
	    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
	    <br>
        <div class="form-group">
          <label class="col-xs-2 control-label">Group Name</label>
          <div class="col-xs-3">
            <input type="text"  id="group_name" name="group_name"   placeholder="Group Name" class="form-control" required/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Position</label>
            <div class="col-xs-6">
              <select class="form-control select2" style="width: 75%;" name="position_id" id="position_id" type="text" data-placeholder='-- Pilih Position --'>
                  <option></option>
                  @foreach($lposition as $value)
                    <option value="{{$value->id}}">
                      {{$value->position_name}}
                    </option>
                  @endforeach
              </select>
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

 