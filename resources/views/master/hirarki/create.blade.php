@extends('master.hirarki.base') 

@section('content_header')
	    <h1>
        Hirarki Workflow Approval
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Customer</a></li>
        <li class="active">Hirarki Workflow</li>
      </ol>  
@stop

@section('content')
  @if(Session::has('ArrHirarki'))
    @php $ArrHirarki = Session('ArrHirarki'); @endphp
  @endif  
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('wf_hirarki.store') }}">
  <input type="hidden" name="_token" value="{{ csrf_token() }}">
  <meta name="csrf-token" content="{{ csrf_token() }}"> 
  <div class="box box-danger">
  <div class="box-body">
    <div class="row">
    <!--Hirarki Name -->
      <div class="form-group">
        <label class="col-xs-2 control-label">Hirarki Name</label>
        <div class="col-xs-6">
          @if(Session::has('ArrHirarki')) 
            @foreach($ArrHirarki as $key => $value)
              <input type="text"  id="hirarki_name" name="hirarki_name"  value= "{{ $value['hirarki_name'] }}"  class="form-control" required /> 
            @endforeach
          @else
            <input type="text"  id="hirarki_name" name="hirarki_name"  class="form-control" required /> 
          @endif
        </div>   
      </div>
      <div class="form-group">
        <label class="col-xs-2 control-label">Hirarki Description</label>
        <div class="col-xs-6">
          @if(Session::has('ArrHirarki')) 
            @foreach($ArrHirarki as $key => $value)
              <input type="text"  id="hirarki_descr" name="hirarki_descr" value = "{{ $value['hirarki_descr'] }}" class="form-control" required/> 
            @endforeach
          @else
            <input type="text"  id="hirarki_descr" name="hirarki_descr" class="form-control" required/> 
          @endif
        </div>   
      </div>
      <div class="form-group">
          <label class="col-xs-2 control-label">Hirarki Type</label> 
          <div class="col-xs-3">
            <select class="form-control select2" style="width: 75%;" name="hirarky_type" id="hirarky_type" type="text" data-placeholder='-- Piih Hirarki Type --'>
              @if(Session::has('ArrHirarki'))
                @foreach($ArrHirarki as $key => $value)
                  <option value="{{ $value['hirarki_type']}}"> {{ $value['hirarki_typename'] }} </option>
                @endforeach
              @else
                <option></option>
              @endif
              @foreach ($hirarkiType as $value)
                  <option value="{{ $value->id }}"> {{ $value->value_name}} </option>
              @endforeach  
            </select> 
          </div>
      </div>
      <div class="form-group">
          <label class="col-xs-2 control-label">Division</label> 
          <div class="col-xs-3">
              <select class="form-control select2" style="width: 75%;" name="division_id" id="division_id" type="text" data-placeholder='-- Piih Division--'>
                  <option></option>  
              </select> 
          </div>
      </div>
      <div class="form-group">
          <label class="col-xs-2 control-label">Brand Product</label> 
          <div class="col-xs-3">
              <select class="form-control select2" style="width: 75%;" name="brand_id" id="brand_id" type="text" data-placeholder='-- Piih Division--'>
                  <option></option>     
              </select> 
          </div>
      </div>
      <!-- position hirarki --> 
        <div class="col-md-9 box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title">Position</h3>
              </div> 
              <div class="box-body">
                <div class="form-group">
                    <label class="col-xs-2 control-label">Name</label> 
                    <div class="col-xs-3">
                        <select class="form-control select2" style="width: 75%;" name="parent_pos_id" id="parent_pos_id" type="text" data-placeholder='-- Piih Position --'>
                            @if(Session::has('ArrHirarki'))
                              @foreach ($ArrHirarki as $key => $value)
                                <option value = "{{ $value['parent_pos_id']}}"> {{ $value['parentpos_name']}} </option>
                              @endforeach
                            @else
                              <option></option>
                            @endif
                            @foreach ($lposition as $value)
                                <option value="{{ $value->id}}"> {{ $value->position_name}} </option>
                            @endforeach      
                        </select> 
                    </div>
                </div>         
                <div class="form-group">
                  <label class="col-xs-2 control-label">Holder</label>
                  <div class="col-xs-3">
                    @if (Session::has('ArrHirarki'))
                      @foreach($ArrHirarki as $key => $value)
                        <input type="text"  id="ParentHolder" name="ParentHolder"  value = "{{ $value['ParentHolder'] }}" class="form-control" />
                      @endforeach
                    @else  
                      <input type="text"  id="ParentHolder" name="ParentHolder"  class="form-control" /> 
                    @endif
                  </div>  
                  <div>
                    <button type="submit" name="up" value="1" class="btn btn-success"><i class="fa fa-fw fa-hand-o-up"></i>Up</button> 
                  </div> 
                </div>
              </div>
            <div class="col-md-8 box box-danger">
              <div class="box-header with-border">
                <h3 class="box-title">Subordinate</h3>
              </div>  
              <div class="box-body">
                <div class="form-group">
                    <label class="col-xs-2 control-label">Name</label> 
                    <div class="col-xs-3">
                        <select class="form-control select2" style="width: 75%;" name="subordinate_pos_id" id="subordinate_pos_id" type="text" data-placeholder='-- Piih Position --'>
                            @if(Session::has('ArrHirarki'))
                              @foreach($ArrHirarki as $key => $value)
                                <option value = "{{ $value['subordinate_pos_id']}}"> {{ $value['subordinate_pos_id']}} </option>
                              @endforeach
                            @else
                              <option></option>
                            @endif
                            @foreach ($lposition as $value)
                              <option value="{{ $value->id }}"> {{ $value->position_name}} </option>
                            @endforeach     
                        </select> 
                    </div>
                </div>         
                <div class="form-group">
                  <label class="col-xs-2 control-label">Holder</label>
                  <div class="col-xs-3">
                    @if (Session::has('ArrHirarki'))
                      @foreach($ArrHirarki as $key => $value)
                        <input type="text"  id="ParentHolder" name="subOrdHolder"  value = "{{ $value['subOrdHolder'] }}" class="form-control"  />
                      @endforeach
                    @else
                      <input type="text"  id="subOrdHolder" name="subOrdHolder" class="form-control"   /> 
                    @endif
                  </div>   
                  <div>
                    <button type="submit" name="down" value="1" class="btn btn-success"><i class="fa fa-fw fa-hand-o-down"></i>Down</button> 
                  </div>
                </div>
              </div>
            </div>
            <div class="box-footer">
              <button type="submit" name="draft" value="1" class="btn btn-info">Save Position</button> 
            </div>
        </div>
      <!-- position hirarki -->
    <!-- -->
    </div>
  </div>
  </div>
  <div class="box-footer">
      <a href="{{ url('home') }}" class="btn btn-warning">Cancel</a> 
      <button type="submit" class="btn btn-primary pull-right" name="save" value="1"  }}">Submit</button>
  </div>
</form> 
@stop