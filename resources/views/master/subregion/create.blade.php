@extends('master.subregion.base') 

@section('content_header')
	  <h1>
        Add New Sub Region
        <small>Tambah Sub Region</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Create Sub Region</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('subregion.store') }}">
  <div class="row">

	<div class="col-xs-12">
	  <div class="box box-danger">
	    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
	    <br>
      <div class="form-group">
        <label class="col-xs-2 control-label">Id Region</label>
          <div class="col-xs-6">
            <select class="form-control select2" style="width: 75%;" name="id_region" id="id_region" type="text">
                <option value = ""> </option>
                @foreach($lsubreg as $value)
                  <option value="{{$value->id}}">
                    {{$value->region}}
                  </option>
                @endforeach
            </select>
            </div>
          </div> 
          <!-- <div class="form-group">
            <label class="col-xs-2 control-label">Id Region</label>
            <div class="col-xs-3">
              <input type="text"  id="id_region" name="id_region"   placeholder="id_region" class="form-control" required/> 
            </div>  
          </div> --> 
         <div class="form-group">
            <label class="col-xs-2 control-label">Sub Region</label>
            <div class="col-xs-3">
              <input type="text"  id="subregion" name="subregion"   placeholder="subregion" class="form-control" required/> 
            </div>  
          </div>  
             <div class="form-group">
              <div class="col-sm-offset-2 col-sm-10">
                <div class="checkbox">
                <label>
                  <!--<input type="checkbox" name="active" id="active" value="Y" required/> Active-->
                  <input checked="checked" type="checkbox" name="active" id="active" value="Y" required/> Active
                </label>
                </div>
              </div>
            </div>
           <br>
		  <!-- Box Footer -->
      <div class="box-footer">
      		<a href="{{ route('subregion.index') }}" class="btn btn-danger btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right">Submit</button>    
      </div>
      <!-- End Of Box Footer --> 
      </div> 
     </div>
	</div>
  </div> 
</form>
@stop

 