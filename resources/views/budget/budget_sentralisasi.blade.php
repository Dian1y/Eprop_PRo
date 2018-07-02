@extends('budget.base') 

@section('content_header')
    <h1>
        Yearly Budget
        <small>Master Budget Tahunan</small>
    </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Budget</a></li>
        <li class="active">Master Budget Sentralisasi</li>
      </ol>  
@stop

  
@section('content')
 
  @if(Session::has('ErrorExcel'))
    @foreach(Session::get('ErrorExcel') as $value) 
         <div class="alert alert-danger"> {{ $value['Err'] }} </div>
    @endforeach
  @endif

  <div class="row">
  	<div class="col-md-12">
  		<div class="box box-danger">
  		<form class="form-horizontal form-group-md" method="POST" action="{{ route('budget_str.save_budget') }}">
  			<input type="hidden" name="_token" value="{{ csrf_token() }}">
  			<meta name="csrf-token" content="{{ csrf_token() }}">
  			<div class="form-group">
  				<label class="col-xs-2 control-label">Tahun</label>
	            <div class="col-xs-1">
	            	@if(Session::has('budget_master'))
	                @foreach(Session::get('budget_master') as $value)
	                	<input type="text"  name="year" id="year" value = "{{ $value['budget_year'] }}"  style="font-size: 12px;" class="form-control" readonly="true" required/>
	                @endforeach
	                @endif
	            </div>
  			</div>
  			<div class="form-group">
	            <label class="col-xs-2 control-label">PT</label>
	            <div class="col-xs-2"> 
	                   <input type="text"  name="company_name" id="company_name" value = "{{ $company_name }}" style="font-size: 12px;" class="form-control" readonly="true" required/> 
	            </div>   
	        </div>
	        <div class="form-group">
	            <label class="col-xs-2 control-label">Total Budget</label>       
	            <div class="col-xs-2">
	            	@if(Session::has('budget_master'))
	                @foreach(Session::get('budget_master') as $value)
	                	<input type="text" name="total_budget" id="total_budget"  value="{{ number_format($value['total_budget'], 2, ',', '.') }}" class="form-control" style="font-size: 12px;" readonly="true" required/> 
	                @endforeach
	                @endif
	            </div>         
	        </div> 	   
	        <!-- Detail budget -->     
	        <div class="col-md-12">
	        	<div class="box-body">
	        		<table id="master_budget" class="table table-bordered table-striped">
	        			<thead>
	        				<tr>
	        					<th>No.</th>
	        					<th>Brand Code</th>
	        					<th>Brand Name</th>
	        					<th>Activity</th>
	        					<th>Region</th>
	        					<th>MT Account</th>
	        					<th>Budget Amount</th>
	        				</tr>
	        			</thead>
	        			<tbody>
	        				<?php $i = 0 ?>
	        				@if (Session::has('budget_detail'))
	        				@foreach(Session::get('budget_detail') as $value)
	        				<?php $i = $i +1 ?>
	        				<tr>
	        					<td>{{ $i }}</td>
	        					<td>{{ $value['brand_code'] }}</td>
	        					<td>{{ $value['brand_name'] }}</td>
	        					<td>{{ $value['activity'] }}</td>
	        					<td>{{ $value['region_name'] }}</td>
	        					<td>{{ $value['account_name'] }}</td>
	        					<td> {{ number_format($value['budget_amount'], 2, ',', '.') }} </td>
	        				</tr>
	        				@endforeach
	        				@endif
	        			</tbody>
	        		</table>
	        	</div>
	        </div>
	        <!-- Footer Submit or Cancel -->
	        <div class="box-footer">
	        	<a href="{{ url('/home') }}" class="btn btn-warning btn-sm">Cancel</a>
	        	@if(Session::has('ErrorExcel'))
	        		<button type="submit" class="btn btn-info pull-right" disabled="disabled">Submit</button>
	        	@else
	        		<button type="submit" class="btn btn-info pull-right" >Submit</button>
	        	@endif	
	        </div>
  		</form>	
  		</div>	
  	</div>
  </div>
@stop