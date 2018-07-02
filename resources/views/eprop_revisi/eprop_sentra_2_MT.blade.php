@extends('eprop.base') 

@section('content_header')
	  <h1>
        Input Proposal
        <small>Sentralisasi</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Proposal</a></li>
        <li class="active">sentral</li>
      </ol> 
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('PostSecondMTPage') }}">
	<div class="row">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <input type="hidden" name="eproptype"  id="eproptype" value="MT">
    <!-- header -->
    <div class="col-xs-12">
      <div class="box">
         <button type="submit" class="btn bg-orange btn-sm margin">Cancel</button>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right">Next Page >></button>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </button>
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->       
      </div> 
    </div>
    <!-- body -->
    <div class="col-xs-12">
      <div class="box box-danger">
        <div class="form-group"> 
            <label class="col-sm-2 control-label">Account</label>
            <div class="col-md-4">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="account_mt[]" id="account_mt" type="text" required="required"> 
                @foreach($account_mt as $value)
                    <option value="{{$value->id}}">
                      {{$value->account_name}}
                    </option>
                @endforeach 
                </select> 
                <input type="checkbox" id="allaccount" >All
            </div>
            <label class="col-sm-1 control-label">Distributor</label>
            <div class="col-md-4">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="cust_ship_id[]" id="cust_ship_id" type="text" > 
                @foreach($customer_info as $value)
                    <option value="{{$value->cust_ship_id}}">
                      {{$value->customer_ship_name}}
                    </option>
                @endforeach 
                </select> 
                <input type="checkbox" id="alldisti" >All
            </div>            
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Store</label>
            <div class="col-md-4">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="store_mt[]" id="store_mt" type="text"> 
                @foreach($store as $value)
                    <option value="{{$value->id}}">
                      {{$value->store_name}} - {{$value->kota}}
                    </option>
                @endforeach 
                </select> 
                <input type="checkbox" id="allstore" >All
            </div>
        </div>

        <div class="form-group"> 
            <label class="col-sm-2 control-label">Total Budget</label>
            <div class="col-md-5">
              <Input type="number" name="total_buget" id="total_buget" style="font-size: 15px;"   required="required">
            </div>          
        </div>


        <div class="form-group"> 
            <label class="col-sm-2 control-label">Backgound</label>
            <div class="col-md-5">
              <textarea class="form-control" rows="2" name="background" id="background"  required="required"></textarea>
            </div>          
        </div>
        
        <div class="form-group"> 
            <label class="col-sm-2 control-label">Objective</label>
            <div class="col-md-5">
              <textarea class="form-control" rows="2"   name="objective" id="objective" required="required"></textarea>
            </div>          
        </div>
        <div class="form-group"> 
            <label class="col-sm-2 control-label">Mechanism</label>
            <div class="col-md-5">
              <textarea class="form-control" rows="2"  name="mechanism" id="mechanism"  required="required"></textarea>
            </div>          
        </div>
        <div class="form-group"> 
            <label class="col-sm-2 control-label">Estimate Cost</label>
            <div class="col-md-5">
              <textarea class="form-control" rows="2"  name="estimate_cost" id="estimate_cost"  required="required"></textarea>
            </div>          
        </div>
        <div class="form-group"> 
            <label class="col-sm-2 control-label">KPI</label>
            <div class="col-md-5">
              <textarea class="form-control" rows="2"  name="kpi" id="kpi"  required="required"></textarea>
            </div>          
        </div>
      <!--box danger --> 
      </div>  
    </div>
    <!-- footer -->
    <div class="col-xs-12">
      <div class="box">
         <button type="submit" class="btn bg-orange btn-sm margin">Cancel</button>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right">Next Page >></button>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page</button>
      </div>  
    </div>    
    <!-- End -->  
  </div> 
</form>	
@stop
