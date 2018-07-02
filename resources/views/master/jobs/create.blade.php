@extends('master.jobs.base') 

@section('content_header')
    <h1>
        Add New Jobs
        <small>Tambah Jobs</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Create Jobs</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('jobs.store') }}">
  <div class="row">

  <div class="col-xs-12">
    <div class="box box-danger">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
      <br>
        <div class="form-group">
          <label class="col-xs-2 control-label">Job code</label>
          <div class="col-xs-3">
            <input type="text"  id="job_code" name="job_code"   placeholder="job_code" class="form-control" required/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Description</label>
          <div class="col-xs-3">
            <input type="text"  id="job_name" name="job_name"   placeholder="job_name" class="form-control" required/> 
          </div>   
        </div>
        <!--<div class="form-group">
          <label class="col-xs-2 control-label">Job Brand</label>
          <div class="col-xs-3">
            <input type="text"  id="job_brand" name="job_brand"   placeholder="job_brand" class="form-control" required/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Job Type</label>
          <div class="col-xs-3">
            <input type="text"  id="job_type" name="job_type"   placeholder="job_type" class="form-control" required/> 
          </div>   
        </div> -->
       <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
              <label>
                <input checked="checked" type="checkbox" name="active" id="active" value="Y" required>Active
              </label>
              </div>
            </div>
          </div>
      <br>
    </div>
    <!-- Box Footer -->
      <div class="box-footer">
          <a href="{{ route('jobs.index') }}" class="btn btn-danger btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right">Submit</button>    
      </div>
      <!-- End Of Box Footer --> 
  </div>
  </div> 
</form>
@stop

 