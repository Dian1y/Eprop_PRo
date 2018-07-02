@extends('layouts.base') 

@section('content_header')
	  <h1>
        Confirm Monthly Order 
        <small>Report </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Reports</a></li>
        <li class="active">CMO Report</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm">
  <div class="row">

	<div class="col-xs-12">
	  <div class="box box-danger">
        <div class="form-group">
          <label class="col-xs-3 control-label">Customer Name</label>
            <div class="col-xs-3">
              <select class="form-control select2"  style="width: 200px;" data-placeholder="Pilih Customer" name="customer_number" id="customer_number">
                  <option disabled selected> -- select an option -- </option>
                  @foreach($lcustomer as $value)
                    <option value="{{$value->customer_number}}">
                      {{$value->customer_name}}
                    </option>
                  @endforeach
              </select>
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Customer Ship Name</label>
            <div class="col-xs-3">
              <select class="form-control select2" style="width: 200px;" data-placeholder="Pilih Customer" name="cust_ship_id" id="cust_ship_id">
                  <option disabled selected> -- select an option -- </option>
                  @foreach($lcustShip as $value)
                    <option value="{{$value->cust_ship_id}}">
                      {{$value->customer_ship_name}}
                    </option>
                  @endforeach
              </select>
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Region</label>
            <div class="col-xs-3">
              <select class="form-control select2" style="width: 200px;" data-placeholder="Pilih Region" name="region_id" id="region_id">
                  <option disabled selected> -- select an option -- </option>
                  @foreach($lregion as $value)
                    <option value="{{$value->id}}">
                      {{$value->region}}
                    </option>
                  @endforeach
              </select>
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-3 control-label">Sub Region</label>
            <div class="col-xs-3">
              <select class="form-control select2" style="width: 200px;" name="subregion_id" id="subregion_id" type="text">
                  <option disabled selected> -- select an option -- </option>
                  @foreach($lsubregion as $value) 
                    <option value="{{$value->id}}">
                      {{$value->subregion}}
                    </option>
                  @endforeach
              </select>
              </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-3 col-sm-10">
            <div class="checkbox">
            <label>
              <input type="checkbox" name="status" id="status"> Status Aktif
            </label>
            </div>
          </div>
        </div>
        </div>
	  	<br>
	  </div>
	  <!-- Box Footer -->
      <div class="box-footer">
      		<a href="{{ url('persons') }}" class="btn btn-warning btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right"  href="{{ url('persons') }}">Find</button>    
      </div>
      <!-- End Of Box Footer --> 
	</div>
  </div> 
</form>
@stop

 