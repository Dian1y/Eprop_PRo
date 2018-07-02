@extends('master.customer_attr.base') 

@section('content_header')
	    <h1>
        List Of Customer
        <small>Customer Attribute</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Customer</a></li>
        <li class="active">Customer Attributes</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('custAttr_store') }}">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <input type="hidden" name="cust_ship_id" id="cust_ship_id" value="{{ $CustomerAttr->cust_ship_id }}">
  <div class="box box-danger">
    <div class="box-body">
      <div class="row">
        <div>
          <!-- customer Info -->
          <div class="form-group">
            <label class="col-xs-2 control-label">Kode Customer</label>
            <div class="col-xs-3">
              <input type="text"  id="customer_number" name="customer_number"   value="{{ $CustomerAttr->customer_number }}" class="form-control" required disabled="disabled" /> 
            </div>   
          </div>
          <div class="form-group">
            <label class="col-xs-2 control-label">Nama Customer</label>
            <div class="col-xs-3">
              <input type="text"  id="customer_name" name="customer_name"   value="{{ $CustomerAttr->customer_name }}" class="form-control" required disabled="disabled"/> 
            </div>   
          </div>
          <div class="form-group">
            <label class="col-xs-2 control-label">Cabang Customer)</label>
            <div class="col-xs-3">
              <input type="text"  id="customer_ship_name" name="customer_ship_name"   value="{{ $CustomerAttr->customer_ship_name }}" class="form-control" required disabled="disabled"/> 
            </div>   
          </div>
          <div class="form-group">
            <label class="col-xs-2 control-label">Alamat Kirim</label>
            <div class="col-xs-8">
              <input type="text"  id="customer_ship" name="customer_ship"   value="{{ $CustomerAttr->customer_ship }}" class="form-control" required disabled="disabled"/> 
            </div>   
          </div>
      </div>
      <div>     
        <div class="col-md-6">
           <div class="form-group">
              <label class="col-md-4 control-label">Default Fleet</label> 
              <div class="col-md-6">
              <select class="form-control select2" style="width: 75%;" name="default_fleet" id="default_fleet" type="text" data-placeholder='-- Piih Default Fleet --' tag="Jika Fleet kosong, Input terlebih dahulu di customer fleet">
                  <option></option>
                  @if(isset($lfleet))
                    @foreach($lfleet as $value)  
                      @if ($default_fleet != 'Y')                 
                      <option value="{{$value->fleet_id}}" > {{$value->fleet_descr}} </option>
                      @else
                      <option value="{{$value->fleet_id}}" selected="selected" > {{$value->fleet_descr}} </option>
                      @endif
                    @endforeach
                  @endif
              </select> 
              </div>
           </div>
        </div>
        <div class="col-md-5">
        <div class="form-group">
            <label class="col-md-2 control-label">Minimun Order</label>
            <div class="col-md-3">
              @if ($minOrder !=  0)
                <input type="text"  id="minqtyorder" name="minqtyorder"   value = "{{ $minOrder }}" class="form-control" required  />
              @else
                <input type="text"  id="minqtyorder" name="minqtyorder"   sclass="form-control" required  />  
              @endif
            </div>   
            <label> Carton</label>
        </div>
        </div>
      </div>
        <!-- row -->
    </div>
        <!-- box body -->
  </div>
  <!-- box danger I -->
  <div class="box box-danger">
    <div class="row">
        <div class="col-md-12">
          <div class="box-header with-border">
            <h3 class="box-title">Person In Charge</h3>
          </div>
        </div>
    </div>
      <div class="row">
        <div class="box-body"> 
          <div class="col-md-6">
            <div class="form-group">
              <label class="col-xs-1 control-label">Area</label>
                <select class="form-control select2" style="width: 75%;" name="area_id" id="area_id" type="text" data-placeholder='-- Pilih Area --'>
                  <option></option>
                  @foreach($larea as $value)
                    <option value="{{$value->id}}"> {{$value->area}} </option>
                  @endforeach
                </select>
            </div>                       
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label>Person </label>
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="person_id[]" id="person_id" type="text" data-placeholder='-- Pilih Person In Charge --'>
              </select>
              <input type="checkbox" id="allperson">Select All 
            </div>
          </div>   
    </div>
  </div>
  <div class="box-footer">
      <div>
        <a href="{{ url('master/customer_attr') }}" class="btn btn-warning ">Cancel</a>
        <button type="submit" class="btn btn-info pull-right"  href="{{ url('master/customer_attr') }}">Submit</button>    
      </div>
  </div>
</form> 
@stop