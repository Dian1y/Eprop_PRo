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
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->       
      </div> 
    </div>
    <!-- body -->
    <div class="col-xs-12">
      <div class="box box-danger">
        <div class="form-group">
           <label class="col-sm-2 control-label">Originator</label>
           <div class="col-md-3">
             <input type="text" name="person_name" class="form-control" readonly="readonly" value ="{{ $person_name}}">
           </div>
           
        </div>  
        <div class="form-group">
           <label class="col-sm-2 control-label">Reco Number</label>
           <div class="col-lg-8">
             <input type="text" name="reco" id="reco" class="form-control" placeholder="Reco Number" required="required">
           </div> 
        </div>        
        <div class="form-group">
           <label class="col-sm-2 control-label">Description / Title</label>
           <div class="col-lg-8">
             <input type="text" name="title" id="title" class="form-control" placeholder="Input Title/Description Proposal" required="required">
           </div> 
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Budget Year</label>
          <div class="col-md-3">
            {{ Form::selectYear('year', $yearNow, $year5, $yearNow) }}
          </div>
          <label class="col-sm-2 control-label">Promo Start And End Date</label>
          <div class="col-md-3 input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="text" class="form-control pull-right" id="eprop_date" name="eprop_date" required="required" >
          </div>
      </div> 
      <div class="form-group">
        <label class="col-sm-2 control-label">PT.</label>
        <div class="col-md-3">
            <select class="form-control select2" style="width: 75%;" name="company_id" id="company_id" type="text" required="required">
                @foreach($company as $value)
                  <option value="{{$value->access_id}}">
                    {{$value->access_name}}
                </option>
                @endforeach
            </select>
        </div>
        <label class="col-sm-2 control-label">Divisi/Departemen</label>
        <div class="col-md-3">
            <select class="form-control select2" style="width: 75%;" name="divisi_id" id="divisi_id" type="text" required="required">
              @if (Session::has('user_division'))
                @foreach((Session::get('user_division')) as $value)
                  <option value="{{$value->access_id}}">
                    {{$value->access_name}}
                </option>
                @endforeach
              @endif
            </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Market Type</label>
        <div class="col-md-3">
            <select class="form-control select2" style="width: 75%;" name="market_type_id" id="market_type_id" type="text" required="required">  
              @if (Session::has('user_market_type'))
                @foreach((Session::get('user_market_type')) as $value)
                  <option value="{{$value->access_id}}">
                    {{$value->access_name}}
                </option>
                @endforeach
              @endif
            </select>
        </div>
        <label class="col-sm-2 control-label">Activity</label>
        <div class="col-md-5">
            <select class="form-control select2" multiple="multiple"  style="width: 75%;" name="activity_id[]" id="activity_id" type="text" required="required" >
               
                @foreach($user_activity as $value)
                  <option value="{{$value->activity_id}}">
                    {{$value->activity_name }}
                  </option>
                @endforeach
            </select>   
            <input type="checkbox" id="allactivity" > All 
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Brand</label>
        <div class="col-md-3">
            <select class="form-control select2" multiple="multiple" style="width: 75%;" name="brand_id[]" id="brand_id" type="text" required="required">
              @foreach($brand as $value)
                  <option value="{{$value->access_id}}">
                    {{$value->access_name}}
                  </option>
                @endforeach
              </select> 
              <input type="checkbox" id="allbrand" > All
        </div>
        <label class="col-sm-2 control-label">Variant</label>
        <div class="col-md-5">
            <select class="form-control select2" multiple="multiple" data-placeholder='Required -- Varian Produk --' style="width: 75%;" name="varian_id[]" id="varian_id" type="text">
              
              </select> 
              <input type="checkbox" id="allvarian" >All
        </div>
      </div>      
      <div class="form-group">
        <label class="col-sm-2 control-label">Branch/Area</label>
        <div class="col-md-3">
            <select class="form-control select2" multiple="multiple" style="width: 75%;" name="branch_id[]" id="branch_id" type="text" required="required">
              @if (Session::has('person_info'))
              @foreach(Session::get('person_info') as $value)
                  <option value="{{$value->area_id}}">
                    {{$value->area}}
                  </option>
              @endforeach
              @endif
              </select> 
              <input type="checkbox" id="allarea" >All
        </div>
        <label class="col-sm-2 control-label">Executor</label>
        <div class="col-md-5">
            <select class="form-control select2" multiple="multiple"  style="width: 75%;" name="executor_id[]" id="executor_id" type="text">
              
              </select> 
              <input type="checkbox" id="allexecutor" >All
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
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->
      </div>  
    </div>    
    <!-- End -->  
  </div> 
</form>	
@stop
