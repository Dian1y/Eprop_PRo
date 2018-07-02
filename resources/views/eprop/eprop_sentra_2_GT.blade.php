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
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('PostFirstPage') }}">
	<div class="row">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
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
            <label class="col-sm-2 control-label">MT Account</label>
            <div class="col-md-3">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="account_mt[]" id="account_mt" type="text" required="required"> 
                @foreach($account_mt as $value)
                    <option value="{{$value->id}}">
                      {{$value->account_name}}
                    </option>
                @endforeach 
                </select> 
                <input type="checkbox" id="allaccount" >All
                <label class="col-sm-2 control-label">Store</label>
            <div class="col-md-3">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="account_mt[]" id="account_mt" type="text" required="required"> 
                @foreach($account_mt as $value)
                    <option value="{{$value->id}}">
                      {{$value->account_name}}
                    </option>
                @endforeach 
                </select> 
                <input type="checkbox" id="allaccount" >All
          </div>
        </div>
        <div class="form-group"> 
            <label class="col-sm-2 control-label">Backgound</label>
            <div class="col-md-3">
              <textarea class="form-control" rows="2" placeholder="Enter ..." disabled required="required"></textarea>
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
