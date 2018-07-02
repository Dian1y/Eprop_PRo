@extends('master.activity.base') 

@section('content_header')
    <h1>
        Edit  Activity
        <small>Edit  Activity</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Edit  Activity</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('activity.update',['id' => $EditAct->id]) }}">
  <div class="row">

  <div class="col-xs-12">
    <div class="box box-danger">
      <input type="hidden" name="_method" value="PATCH">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
      <br>
        <div class="form-group">
          <label class="col-xs-2 control-label">Group Id</label>
          <div class="col-xs-3">
            <input type="text" value="{{ $EditAct->group_id }}" id="group_id" name="group_id" class="form-control" required/> 
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Kategory Id</label>
          <div class="col-xs-3">
            <input type="text" value="{{ $EditAct->kategory_id }}" id="kategory_id" name="kategory_id" class="form-control" required/> 
          </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Activity Name</label>
          <div class="col-xs-3">
            <input type="text" value="{{ $EditAct->activity_name }}" id="activity_name" name="activity_name" class="form-control" required/> 
          </div>
        </div>

      <br>
    </div>
    <!-- Box Footer -->
      <div class="box-footer">
          <a href="{{ route('activity.index') }}" class="btn btn-danger btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right">Submit</button>    
      </div>
      <!-- End Of Box Footer --> 
  </div>
  </div> 
</form>
@stop

 