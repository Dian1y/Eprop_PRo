@extends('master.area.base') 

@section('content_header')
    <h1>
        Edit Area
        <small>Edit Area</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Edit Area</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('area.update',['id' => $EditArea->id]) }}">
  <div class="row">

  <div class="col-xs-12">
    <div class="box box-danger">
      <input type="hidden" name="_method" value="PATCH">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
      <br>
        
         <div class="form-group">
        <label class="col-xs-2 control-label">Id Region Lov</label>
          <div class="col-xs-6">
            <select class="form-control select2" style="width: 75%;" name="id_region" id="id_region">
              <!-- <select class="form-control select2" style="width: 75%;" name="group_id" id="group_id" type="text">-->
                <option value = "{{ $EditArea->id_region }}"> </option>
                @foreach($lregion as $value)
                  <option value="{{$value->id}}">{{$value->region}}
                  </option>
                @endforeach
            </select>
            </div>
          </div> 
        
         <div class="form-group">
        <label class="col-xs-2 control-label">Id Sub Region Lov</label>
          <div class="col-xs-6">
            <select class="form-control select2" style="width: 75%;" name="id_subregion" id="id_subregion">
              <!-- <select class="form-control select2" style="width: 75%;" name="group_id" id="group_id" type="text">-->
                <option value = "{{ $EditArea->id_subregion }}"> </option>
                @foreach($lsubregion as $value)
                  <option value="{{$value->id}}">{{$value->subregion}}
                  </option>
                @endforeach
            </select>
            </div>
          </div> 
         <div class="form-group">
          <label class="col-xs-2 control-label">Area</label>
          <div class="col-xs-3">
            <input type="text" value="{{ $EditArea->area }}" id="area" name="area" class="form-control" required/> 
          </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
              <label>
                <input checked="checked" type="checkbox" name="active" id="active"> Active
              </label>
              </div>
            </div>
          </div>
      <br>
    </div>
    <!-- Box Footer -->
      <div class="box-footer">
          <a href="{{ route('area.index') }}" class="btn btn-danger btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right">Submit</button>    
      </div>
      <!-- End Of Box Footer --> 
  </div>
  </div> 
</form>
@stop

 