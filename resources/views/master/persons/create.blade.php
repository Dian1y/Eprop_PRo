@extends('master.persons.base') 

@section('content_header')
	  <h1>
        Add New Persons
        <small>Tambah Personnell </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Person</a></li>
        <li class="active">Create</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('persons.store') }}">
  <div class="row">

	<div class="col-xs-12">
	  <div class="box box-danger">
	    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
	    <br>
        <div class="form-group">
          <label class="col-xs-2 control-label">Nama</label>
          <div class="col-xs-6">
            <input type="text"  id="name" name="name"   placeholder="Nama Person" class="form-control" required/> 
          </div>   
        </div>      
        <div class="form-group">
          <label class="col-xs-2 control-label">Email</label>
          <div class="col-xs-6">
            <input type="text"  id="email" name="email"  class="form-control" required /> 
          </div>   
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-10">
            <div class="checkbox">
            <label>
              <input type="checkbox" name="active" id="active" checked="checked"> Status Aktif
            </label>
            </div>
          </div>
        </div>
        </div> 
        <div class="form-group">
          <label class="col-xs-2 control-label">Personal Type</label>
            <div class="col-xs-6">
              <select class="form-control select2" style="width: 75%;" name="person_type" id="person_type" type="text">
                    <option value="{{$person_type->value_name}}" selected="selected"> {{$person_type->value_name}} </option>
                  @foreach($person_type as $value)
                    <option value="{{$value->value_name}}">
                      {{$value->value_name}}
                    </option>
                  @endforeach
              </select>
              </div>
        </div> 
        <div class="form-group">
          <label class="col-xs-2 control-label">Group Akses</label>
            <div class="col-xs-6">
              <select class="form-control select2" style="width: 75%;" name="group_akses" id="group_akses" type="text">
                    <option value="{{$group_akses->id}}" selected="selected"> {{$group_akses->group_akses_descr}} </option>
                  @foreach($group_akses as $value)
                    <option value="{{$value->id}}">
                      {{$value->group_akses_descr}}
                    </option>
                  @endforeach
              </select>
              </div>
        </div>  
        <div class="form-group">
          <label class="col-xs-2 control-label">Position</label>
            <div class="col-xs-6">
              <select class="form-control select2" style="width: 75%;" name="position_id" id="position_id" type="text" data-placeholder='-- Pilih Position --'>
                  <option></option>
                  @foreach($lposition as $value)
                    <option value="{{$value->id}}">
                      {{$value->position}}
                    </option>
                  @endforeach
              </select>
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Job</label>
            <div class="col-xs-6"> 
              <select class="form-control select2" style="width: 75%;" name="job_id" id="job_id" type="text" data-placeholder='-- Pilih Job --'>                  
                  <option></option>
                  @foreach($ljob as $value)
                    <option value="{{$value->id}}">
                      {{$value->job_description}}
                    </option>
                  @endforeach
              </select>
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Region</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="region_id[]" id="region_id" data-placeholder='-- Pilih Region --' type="text">
                  <option></option>
                  @foreach($lregion as $value)
                    <option value="{{$value->id}}">
                      {{$value->region}}
                    </option>
                  @endforeach
              </select> 
              <input type="checkbox" id="allregion" >Select All Region
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Sub Region</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" data-placeholder='-- Pilih Sub Region --' style="width: 75%;" name="subregion_id[]" id="subregion_id" type="text">
              </select> 
              <input type="checkbox" id="allsubregion" >Select All Subregion
            </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Coverage Area</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" data-placeholder='-- Coverage Area --' style="width: 75%;" name="area_id[]" id="area_id" type="text">
              </select>
              <input type="checkbox" id="allarea" >Select All Coverage Area
              </div>
        </div>
	  	<br>
	  </div>
	  <!-- Box Footer -->
      <div class="box-footer">
      		<a href="{{ url('persons') }}" class="btn btn-warning btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right"  href="{{ url('persons') }}">Submit</button>    
      </div>
      <!-- End Of Box Footer --> 
	</div>
  </div> 
</form>
@stop

 