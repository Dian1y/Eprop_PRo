@extends('layouts.base') 

@section('content_header')
	  <h1>
        Add New Fleet
        <small>Tambah Jenis Kendaraan</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Fleet</a></li>
        <li class="active">Create Fleet</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ url('master/masterfleet') }}">
  <div class="row">

	<div class="col-xs-12">
	  <div class="box box-danger">
	    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
	    <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
	    <br>
        <div class="form-group">
          <label class="col-xs-2 control-label">Kode Fleet</label>
          <div class="col-xs-3">
            <input type="text"  id="fleet_code" name="fleet_code"   placeholder="Kode Fleet" class="form-control" required/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Nama Fleet</label>
          <div class="col-xs-3">
            <input type="text"  id="fleet_descr" name="fleet_descr"   placeholder="Nama Fleet" class="form-control" required/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Kapasitas(Karton)</label>
          <div class="col-xs-3">
            <input type="text"  id="max_carton" name="max_carton"   placeholder="Kapasitas Maksimum dalam karton" class="form-control" required/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Tonase (Kg)</label>
          <div class="col-xs-3">
            <input type="text"  id="tonase" name="tonase"   placeholder="Tonase (Kg)" class="form-control" required/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Kubikase (M3)</label>
          <div class="col-xs-3">
            <input type="text"  id="kubikase" name="kubikase"   placeholder="Kubikase (m3)" class="form-control" required/> 
          </div>   
        </div>
	  	<br>
	  </div>
	  <!-- Box Footer -->
      <div class="box-footer">
      		<a href="{{ url('master/masterfleet') }}" class="btn btn-warning btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right"  href="{{ url('master/masterfleet') }}">Submit</button>    
      </div>
      <!-- End Of Box Footer --> 
	</div>
  </div> 
</form>
@stop

 