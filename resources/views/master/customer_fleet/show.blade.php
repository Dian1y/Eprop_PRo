@extends('layouts.base') 

@section('content_header')
	  <h1>
        List Of Customer Fleet
        <small>Daftar Kendaraan Customer</small>
    </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Fleet</a></li>
        <li class="active">Customer Fleet</li>
      </ol>  
@stop 

@section('content') 
@if(Session::has('custview'))
	@php $custview = Session::get('custview') @endphp
@endif
@foreach($custview as $value)
	@php $cust_ship_id = $value->cust_ship_id @endphp
	@php $customer_number = $value->customer_number @endphp
	@php $customer_name = $value->customer_name @endphp
	@php $customer_ship_name = $value->customer_ship_name @endphp
	@php $customer_ship = $value->customer_ship @endphp
@endforeach  
{{ $cust_ship_id }}
@if(Session::has('FlList'))
	@php $fllist = Session::get('FlList') @endphp
@endif 
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('custFleet_store') }}">
  <div class="row">
	<div class="col-xs-12">
	  <div class="box box-danger">
        <input type="hidden" name="_token" value="{{ csrf_token() }}">
        <input type="hidden" name="cust_ship_id" id="cust_ship_id" value="{{ $cust_ship_id }}">
	    <br>
        <div class="form-group">
          <label class="col-xs-2 control-label">Kode Customer</label>
          <div class="col-xs-3">
            <input type="text"  id="customer_number" name="customer_number"   value="{{ $customer_number }}" class="form-control" required disabled="disabled" /> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Nama Customer</label>
          <div class="col-xs-3">
            <input type="text"  id="customer_name" name="customer_name"   value="{{ $customer_name }}" class="form-control" required disabled="disabled"/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Cabang Customer)</label>
          <div class="col-xs-3">
            <input type="text"  id="customer_ship_name" name="customer_ship_name"   value="{{ $customer_ship_name }}" class="form-control" required disabled="disabled"/> 
          </div>   
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Alamat Kirim</label>
          <div class="col-xs-8">
            <input type="text"  id="customer_ship" name="customer_ship"   value="{{ $customer_ship }}" class="form-control" required disabled="disabled"/> 
          </div>   
        </div>
        </div>
    </div>
        <div class="col-xs-12">
        <div class="box box-danger">
			     <div class="form-group">
	            <label class="col-xs-2 control-label">Type Kendaraan</label>
	            <div class="col-xs-2">
	            <select class="form-control select2" name="fleet_id" id="fleet_id" type="text">
	                <option disabled selected> -- select an option -- </option>
	                @foreach($fllist as $value)
	                  <option value="{{$value->id}}">
	                    {{$value->fleet_descr}}
	                  </option>
	                @endforeach
	            </select>
              </div>
              <label class="col-xs-2 control-label">Est. Delivery Day</label>
              <div class="col-xs-2">
                  <input type="text"  id="est_delivery_day" name="est_delivery_day"   value="1" class="form-control" required /> 
              </div> 
              
              <div>
                <input type="checkbox" id="default">Default
              </div> 
           </div>
           <div>
               <div class="col-xs-2">
                  <button type="submit" class="btn btn-info pull-right">Add New Fleet</button>
              </div>
           </div>
	    </div>
      </div>	  
    </div>  
    </form>

		  <!-- Box Table Fleet -->
	  <div class="col-xs-12">
	    <div class="box box-danger">
	    <table id="tbldetails" class="table table-bordered table-striped">
	      <thead>
	      <tr>
	        <th>No.</th>
            <th>Kode Fleet</th>
            <th>Description</th>
            <th>Max Karton</th>
            <th>Tonase</th>
            <th>Kubikase</th>
            <th>ID</th>
            <th>Action</th>
	       </tr>
	       </thead>
	       <tbody>
	        <?php $i = 0 ?>
	        @if(Session::has('fleet'))
	        @php $fleet = Session::get('fleet') @endphp	
            @foreach($fleet as $value)
            <?php $i++ ?>
              <tr>
                <td> {{ $i }} </td>
                <td> {{ ($value->fleet_code) }} </td>
                <td> {{ $value->fleet_descr }} </td>
                <td> {{ number_format($value->max_carton, 2, ',', '.') }} </td>
                <td> {{ number_format($value->tonase, 2, ',', '.') }} </td>
                <td> {{ number_format($value->kubikase, 2, ',', '.') }} </td>
                 <td> {{ $value->id }} </td>
                <td> 
                    <form method="post" action="route('delete_route,  {{$value->id}}')">
                    <input name="_method" type="hidden" value="DELETE">
                    <input type="hidden" name="id" id="id" value="{{ $value->id }}">
                    <div class="form-group">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                           <div class="form-group">
                           <button type="submit" class="btn btn-danger">Delete</button>
                           </div>
                    </div>
                        </form>
                </td>
              </tr>
            @endforeach
            @endif
	        </tbody>
	    </table>
	    </div>
	   </div>
		<!-- End of Box Table Fleet -->
	  <!-- Box Footer -->
	  <div class="col-xs-12">
      <div class="box-footer">
      		<a href="{{ url('master/customer_fleet') }}" class="btn btn-warning btn-sm">Back</a>
      </div>
    </div>
      <!-- End Of Box Footer --> 
	

 

@stop
