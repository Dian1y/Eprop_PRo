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
         <a href="{{ route('sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Cancel</a>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right">Next Page >></button>
         <a href="{{ route('SentraPrevPage1') }}" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </a>
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
                    @if (Session::has('det_prop_account'))
                    @foreach (Session::get('det_prop_account') as $pilihan)
                      @if ($pilihan['account_id'] == $value->id)
                        <option value="{{$value->id}}" selected="selected">
                          {{$value->account_name}}
                        </option>
                      @endif
                    @endforeach
                    @endif  
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
                    @if (Session::has('det_prop_disti'))
                    @foreach (Session::get('det_prop_disti') as $pilihan)
                      @if ($pilihan['distributor_id'] == $value->cust_ship_id)
                        <option value="{{$value->cust_ship_id}}" selected="selected">
                          {{$value->customer_ship_name}}
                        </option>
                      @endif
                    @endforeach
                    @endif                  
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
                    @if (Session::has('det_prop_store'))
                    @foreach (Session::get('det_prop_store') as $pilihan)
                      @if ($pilihan['store_id'] == $value->id)
                        <option value="{{$value->id}}" selected="selected">
                          {{$value->store_name}} - {{$value->kota}}
                        </option>
                      @endif
                    @endforeach
                    @endif                    
                    <option value="{{$value->id}}">
                      {{$value->store_name}} - {{$value->kota}}
                    </option>
                @endforeach 
                </select> 
                <input type="checkbox" id="allstore" >All
            </div>
        </div>

 <!--       <div class="form-group"> 
            <label class="col-sm-2 control-label">Total Budget</label>
            <div class="col-md-5">
              @if (Session::has('SecondPageSentra'))
                @foreach (Session::get('SecondPageSentra') as $pilihan)
                  <Input type="number" name="total_buget" id="total_buget" style="font-size: 15px;"  value = "{{ $pilihan['total_budget'] }}" required="required">
                @endforeach
              @else
                <Input type="number" name="total_buget" id="total_buget" style="font-size: 15px;"   required="required">              
              @endif
            </div>          
        </div> -->


        <div class="form-group"> 
            <label class="col-sm-2 control-label">Backgound</label>
            <div class="col-md-5">
              @if (Session::has('SecondPageSentra'))
                @foreach (Session::get('SecondPageSentra') as $pilihan)              
                  <textarea class="form-control" rows="2" name="background" id="background"  required="required">{{ $pilihan['background'] }}</textarea>
                @endforeach
              @else
                <textarea class="form-control" rows="2" name="background" id="background"  required="required"></textarea>
              @endif
            </div>          
        </div>
        
        <div class="form-group"> 
            <label class="col-sm-2 control-label">Objective</label>
            <div class="col-md-5">
              @if (Session::has('SecondPageSentra'))
                @foreach (Session::get('SecondPageSentra') as $pilihan)               
                  <textarea class="form-control" rows="2"   name="objective" id="objective"  required="required">{{$pilihan['objective']}}</textarea>
                @endforeach
              @else
                <textarea class="form-control" rows="2"   name="objective" id="objective" required="required"></textarea>
              @endif
            </div>          
        </div>
        <div class="form-group"> 
            <label class="col-sm-2 control-label">Mechanism</label>
            <div class="col-md-5">
              @if (Session::has('SecondPageSentra'))
                @foreach (Session::get('SecondPageSentra') as $pilihan)  
                  <textarea class="form-control" rows="2"  name="mechanism" id="mechanism"   required="required">{{$pilihan['mechanism']}}</textarea>
                @endforeach   
              @else                         
                  <textarea class="form-control" rows="2"  name="mechanism" id="mechanism"  required="required"></textarea>
              @endif
            </div>          
        </div>
        <div class="form-group"> 
            <label class="col-sm-2 control-label">Estimate Cost</label>
            <div class="col-md-5">
              @if (Session::has('SecondPageSentra'))
                @foreach (Session::get('SecondPageSentra') as $pilihan)   
                  <textarea class="form-control" rows="2"  name="estimate_cost"  id="estimate_cost"   required="required">{{$pilihan['estimate_cost']}}</textarea>
                @endforeach
              @else           
              <textarea class="form-control" rows="2"  name="estimate_cost" id="estimate_cost"  required="required"></textarea>
              @endif 
            </div>          
        </div>
        <div class="form-group"> 
            <label class="col-sm-2 control-label">KPI</label>
            <div class="col-md-5">
              @if (Session::has('SecondPageSentra'))
                @foreach (Session::get('SecondPageSentra') as $pilihan) 
                  <textarea class="form-control" rows="2"  name="kpi" id="kpi" required="required">{{ $pilihan['kpi'] }}</textarea> 
                @endforeach
              @else            
                <textarea class="form-control" rows="2"  name="kpi" id="kpi"  required="required"></textarea>
              @endif  
            </div>          
        </div>
      <!--box danger --> 
      </div>  
    </div>
    <!-- footer -->
    <div class="col-xs-12">
      <div class="box">
         <a href="{{ route('sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Cancel</a>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right">Next Page >></button>
         <a href="{{ route('SentraPrevPage1') }}" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </a>
      </div>  
    </div>    
    <!-- End -->  
  </div> 
</form>	
@stop
