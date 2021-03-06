@extends('master.group_akses.base') 

@section('content_header')
    <h1>
        Add New Group Akses
        <small>Tambah Group Akses</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Master</a></li>
        <li class="active">Create Group Akses</li>
      </ol>  
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('group_akses.store') }}">
  <div class="row">

  <div class="col-xs-12">
    <div class="box box-danger">
      <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
      <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}"> </div>
      <br>
        <div class="form-group">
          <label class="col-xs-2 control-label">Group Akses Code</label>
          <div class="col-xs-3">
            <input type="text"  id="group_akses_code" name="group_akses_code"   placeholder="group_akses_code" class="form-control" required/> 
          </div>   
        </div>

         <div class="form-group">
          <label class="col-xs-2 control-label">Group Akses Name</label>
          <div class="col-xs-3">
            <input type="text"  id="group_akses_desc" name="group_akses_descr"   placeholder="group_akses_descr" class="form-control" required/> 
          </div>   
        </div>
         <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
              <label>
                <input checked="checked" type="checkbox" name="flag_originator" id="flag_originator" >Originator
              </label>
              </div>
            </div>
          </div>
         <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
              <label>
                <input checked="checked" type="checkbox" name="flag_approver" id="flag_approver" >Approver
              </label>
              </div>
            </div>
          </div>  
           <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
              <label>
                <input checked="checked" type="checkbox" name="flag_executor" id="flag_executor" >Executor
              </label>
              </div>
            </div>
          </div>
          <div class="form-group">
          <label class="col-xs-2 control-label">Company</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="company_code[]" id="company_code" data-placeholder='-- Pilih Company --' type="text">
                  <option></option>
                  @foreach($lcompany as $value)
                    <option value="{{$value->id}}">
                      {{$value->company_name}}
                    </option>
                  @endforeach
              </select> 
              <input type="checkbox" name="allcompany" id="allcompany" >Select All Company
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Division</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="division_code[]" id="division_code" data-placeholder='-- Pilih Division --' type="text">
                  <option></option>
                  @foreach($ldivisi as $value)
                    <option value="{{$value->id}}">
                      {{$value->division_name}}
                    </option>
                  @endforeach
              </select> 
              <input type="checkbox" id="alldivisi" >Select All Division
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Kategory Activity</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="id[]" id="id"
              data-placeholder='-- Pilih Kategory --' type="text">
                  <option></option>
                  @foreach($lkategory as $value)
                    <option value="{{$value->id}}">
                      {{$value->kategory_name}}
                    </option>
                  @endforeach
              </select> 
              <input type="checkbox" id="allkategory" >Select All Kategory
              </div>
        </div>
        <div class="form-group">
          <label class="col-xs-2 control-label">Market Type</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="type_market_code[]" id="type_market_code" data-placeholder='-- Pilih Market Type --' type="text">
                  <option></option>
                  @foreach($lmarkettype as $value)
                    <option value="{{$value->id}}">
                      {{$value->description}}
                    </option>
                  @endforeach
              </select> 
              <input type="checkbox" id="allmarket" >Select All Market Type
              </div>
        </div>
         <div class="form-group">
          <label class="col-xs-2 control-label">Brand</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="brand_code[]" id="brand_code" data-placeholder='-- Pilih Brand --' type="text">
                  <option></option>
                  @foreach($lbrand as $value)
                    <option value="{{$value->id}}">
                      {{$value->brand_name}}
                    </option>
                  @endforeach
              </select> 
              <input type="checkbox" id="allbrand" >Select All Brand
              </div>
        </div>
          <div class="form-group">
          <label class="col-xs-2 control-label">Account</label>
            <div class="col-xs-8">
              <select class="form-control select2" multiple="multiple" style="width: 75%;" name="account_code[]" id="account_code" data-placeholder='-- Pilih Account --' type="text">
                  <option></option>
                  @foreach($laccount as $value)
                    <option value="{{$value->id}}">
                      {{$value->account_name}}
                    </option>
                  @endforeach
              </select> 
              <input type="checkbox" id="allaccount" >Select All Account
              </div>
        </div>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
              <div class="checkbox">
              <label>
                <input checked="checked" type="checkbox" name="active" id="active" value="Y" >Active
              </label>
              </div>
            </div>
          </div>

          <!-- Box Footer -->
      <div class="box-footer">
          <a href="{{ route('group_akses.index') }}" class="btn btn-danger btn-sm">Cancel</a>
            <button type="submit" class="btn btn-info pull-right">Submit</button>    
      </div>
      <!-- End Of Box Footer --> 
  </div>
  </div> 
</form>
@stop

 