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
<form class="form-horizontal form-group-sm" name="inputEprop" method="POST" action="{{ route('PostFirstPage') }}">
  <div class="row">
    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <!-- header -->
    <div class="col-md-12">
      <div class="box">
         <a href="{{ route('sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Cancel</a>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right">Next Page >></button>
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->       
      </div> 
    </div>
    <!-- body -->
    <div class="col-md-12">
      <div class="box box-danger">
        <div class="form-group">
           <label class="col-sm-2 control-label">Reco Number</label>
           <div class="col-lg-8">
             @if (Session::has('FirstPageSentra'))
                @foreach (Session::get('FirstPageSentra') as $pilihan)
                  <input type="text" name="reco" id="reco" class="form-control"  value = '{{ $pilihan['reco_no'] }}' required="required">
                @endforeach
             @else
                <input type="text" name="reco" id="reco" class="form-control" placeholder="Reco Number" required="required">
             @endif
           </div> 
        </div>        
        <div class="form-group">
           <label class="col-sm-2 control-label">Description / Title</label>
           <div class="col-lg-8">
             @if (Session::has('FirstPageSentra'))
                @foreach (Session::get('FirstPageSentra') as $pilihan)
                  <input type="text" name="reco" id="reco" class="form-control"  value = "{{ $pilihan['description'] }}" required="required">
                @endforeach
             @else            
                <input type="text" name="title" id="title" class="form-control" placeholder="Input Title/Description Proposal" required="required">
             @endif 
           </div> 
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">Budget Year</label>
          <div class="col-md-3">
             @if (Session::has('FirstPageSentra'))
                @foreach (Session::get('FirstPageSentra') as $pilihan)
                  {{ Form::selectYear('year', $yearNow, $year5, $pilihan['budget_year']) }}
                @endforeach
             @else                        
                {{ Form::selectYear('year', $yearNow, $year5, $yearNow) }}
             @endif
          </div>
          <label class="col-sm-2 control-label">Promo Start And End Date</label>
          <div class="col-md-3 input-group">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
             @if (Session::has('FirstPageSentra'))
                @foreach (Session::get('FirstPageSentra') as $pilihan)
                 <input type="text" class="form-control pull-right" id="eprop_date" name="eprop_date" value = "{{ $pilihan['rangeDate'] }}" required="required" >
                @endforeach
             @else            
                <input type="text" class="form-control pull-right" id="eprop_date" name="eprop_date" required="required" >
             @endif             
          </div>
      </div> 
      <div class="form-group">
        <label class="col-sm-2 control-label">Market Type</label>
        <div class="col-md-3">
            <select class="form-control select2"   style="width: 75%;" name="market_type_id" id="market_type_id" type="text" required="required">  
              @if (Session::has('user_market_type'))
                @foreach((Session::get('user_market_type')) as $value)
                  @if (Session::has('FirstPageSentra'))
                    @foreach (Session::get('FirstPageSentra') as $pilihan)
                    @if ($pilihan['market_type_id'] == $value->access_id)
                        <option value="{{$value->access_id}}" selected="selected">
                            {{$value->access_name}}
                        </option>
                    @endif
                    @endforeach
                  @endif
                <option value="{{$value->access_id}}">
                    {{$value->access_name}}
                </option>
              @endforeach

            @endif                                
              <!--<input type="checkbox" id="markettype" > All-->
            </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label">Divisi/Departemen</label>
        <div class="col-md-4">
            <select class="form-control select2"  style="width: 75%;" name="divisi_id" id="divisi_id" type="text" required="required">
              @if (Session::has('user_division'))
                @foreach((Session::get('user_division')) as $value)
                  @if (Session::has('FirstPageSentra'))
                    @foreach (Session::get('FirstPageSentra') as $pilihan)
                    @if ($pilihan['division_id'] == $value->access_id)
                        <option value="{{$value->access_id}}" selected="selected">
                            {{$value->access_name}}
                        </option>
                    @endif
                    @endforeach      
                  @endif     
                  <option value="{{$value->access_id}}">
                    {{$value->access_name}}
                </option>
                @endforeach
            @endif                      
            </select>
        </div>
        <label class="col-sm-1 control-label">Brand</label>
        <div class="col-md-4">
            <select class="form-control select2" multiple="multiple" style="width: 75%;" name="brand_id[]" id="brand_id" type="text" required="required">
              @foreach($brand as $value)              
                @if (Session::has('det_prop_brand'))
                @foreach (Session::get('det_prop_brand') as $pilihan)
                  @if ($pilihan['brand_id'] == $value->access_id)
                    <option value="{{$value->access_id}}" selected="selected">
                      {{$value->access_name}}
                    </option>
                  @endif
                @endforeach
                @endif
                <option value="{{$value->access_id}}">
                  {{$value->access_name}}
                </option>
              @endforeach                           
            </select> 
              <input type="checkbox" id="allbrand" > All
        </div>
      </div>
      <div class="form-group">
         <label class="col-sm-2 control-label">Activity</label>
        <div class="col-md-4">
            <select class="form-control select2" multiple="multiple"  style="width: 75%;" name="activity_id[]" id="activity_id" type="text" required="required" >              
                @foreach($user_activity as $value)                
                    @if (Session::has('det_prop_activity'))                
                     @foreach (Session::get('det_prop_activity') as $pilihan)
                     @if ($pilihan['activity_id'] == $value->activity_id)
                        <option value="{{$value->activity_id}}" selected="selected">
                            {{$value->activity_name}}
                        </option>
                     @endif
                     @endforeach
                    @endif 
                  <option value="{{$value->activity_id}}">
                    {{$value->activity_name }}
                  </option>
                @endforeach                               
            </select>   
            <input type="checkbox" id="allactivity" > All 
        </div>
        <label class="col-sm-1 control-label">Varian</label>
        <div class="col-md-4">
              <select class="form-control select2" multiple="multiple"   style="width: 75%;" name="varian_id[]" id="varian_id" type="text">
              @if (Session::has('det_prop_varian'))                
                @foreach($ListVarian as $value)
                    @foreach (Session::get('det_prop_varian') as $pilihan)
                        @if ($pilihan['varian_id'] == $value->id)
                            <option value="{{$value->id}}" selected="selected">
                              {{$value->produk_name }}
                            </option>
                        @endif 
                    @endforeach
                    <option value="{{$value->id}}">
                      {{$value->produk_name }}
                    </option>
                @endforeach
              @endif
              </select> 
              <input type="checkbox" id="allvarian" > All
        </div>
      </div>      
      <div class="form-group">
        <label class="col-sm-2 control-label">Branch/Area</label>
        <div class="col-md-4">
            <select class="form-control select2" multiple="multiple" style="width: 75%;" name="branch_id[]" id="branch_id" type="text" required="required">
              @if (Session::has('person_info'))
              @foreach(Session::get('person_info') as $value)
                  @if (Session::has('det_prop_branch'))              
                    @foreach (Session::get('det_prop_branch') as $pilihan)
                    @if ($pilihan['branch_id'] == $value->area_id)
                        <option value="{{$value->area_id}}" selected="selected">
                            {{$value->area}}
                        </option>
                    @endif
                    @endforeach
                  @endif
                  <option value="{{$value->area_id}}">
                    {{$value->area}}
                  </option>
              @endforeach
              @endif                                  
              </select> 
              <input type="checkbox" id="allarea" > All
        </div> 
        <label class="col-sm-1 control-label">Executor</label>
        <div class="col-md-4">
            <select class="form-control select2" multiple="multiple"  style="width: 75%;" name="executor_id[]" id="executor_id" type="text" required="required">
              @if (Session::has('det_prop_executor'))              
                @foreach($executors as $value)
                  @foreach (Session::get('det_prop_executor') as $pilihan)
                    @if ($pilihan['executor_id'] == $value->person_id)
                      <?php dd(Session::get('det_prop_executor')) ?>
                      <option value="{{$value->person_id}}" selected="selected">
                        {{$value->name}}
                      </option>
                      @endif
                  @endforeach
                  <option value="{{$value->person_id}}">
                    {{$value->name}}
                  </option>
                @endforeach
              @endif                 
              </select> 
              <input type="checkbox" id="allexecutor" > All
        </div>
      </div>
      <div class="form-group">
          <label class="col-sm-2 control-label">Target</label>
          <div class="col-md-4">
              <select class="form-control select2"   style="width: 75%;" name="target" id="target" type="text"> 
                    @foreach($targetEprop as $value)
                        <option value="{{$value->target_name}}">
                              {{$value->target_name}}
                        </option>
                    @endforeach 
              </select> 
          </div>
      </div> 

      <div class="form-group"> 
        <label class="col-sm-2 control-label">Total Budget</label>
        <div class="col-md-5">
          @if (Session::has('FirstPageSentra'))
            @foreach (Session::get('FirstPageSentra') as $pilihan)
                <Input type="number" name="total_buget" id="total_buget" style="font-size: 15px;"  value = "{{ $pilihan['total_budget'] }}" required="required">
            @endforeach
          @else
              <Input type="number" name="total_buget" id="total_buget" style="font-size: 15px;"   required="required">              
          @endif
        </div>          
      </div>

      <div class="form-group">
                <label class="col-sm-2 control-label">Jumlah Target</label>
                <div class="col-md-4">
                  <Input type="number" name="jmltarget" id="jmltarget" style="font-size: 13px;text-align: right;"   required="required"> 
                </div>
            </div>   
            <!--<h5 align="left">SALES TARGET</h5>
            <h6 align="left">==============</h6>
            <h5 align="left">TARGET SALES</h5> -->
            <!--div class="form-group"--> 
              <p style="color:blue; font-weight: bold;">TARGET SALES</p>
              <p style="color:blue; font-weight: bold;">==============</h6> 
            <!--/div-->
            <!--div class="form-group">
              <label class="col-sm-2 control-label">==============</label>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label">TARGET SALES</label>
            </div-->           
            <div class="form-group"> 
                <label class="col-md-2 control-label"></label>
                <input type="checkbox"  name="chktarget" id="chktarget" checked="checked"> Input Target Sales
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">Averages Sales Min For The Last 3 Month</label>
                <div class="col-md-4">
                  <Input type="number" name="avg_sales" id="avg_sales"   style="font-size: 13px;text-align: right;" disabled="disabled"> 
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label"> Target Sales (Value)</label>               
                <div class="col-md-4">
                  <Input type="number" name="sales_value" id="sales_value"  style="font-size: 13px;text-align: right;"   disabled="disabled" > 
                </div>

            </div>   
            <p style="color:blue; font-weight: bold;">COST ANALYSIS</p>
            <p style="color:blue; font-weight: bold;">==============</h6> 
            <div class="form-group">
                <label class="col-sm-2 control-label">Target value vs target sales</label>  <!-- sales/average -->
                <div class="col-md-4">
                  <Input type="text" name="sales_compare" id="sales_compare" style="font-size: 13px;text-align: right;"  
                  readonly="readonly" disabled="disabled">  %
                </div>
            </div>   
            <div class="form-group">
                <label class="col-sm-2 control-label">Cost Ratio</label>  <!-- Budget/Target Sales -->
                <div class="col-md-4">
                  <Input type="text" name="cost_ratio" id="cost_ratio" style="font-size: 13px;text-align: right;"   readonly="readonly" disabled="disabled">  %
                </div>                
            </div> 
      <!--box danger --> 
      </div>  
    </div>
    <!-- footer -->
    <div class="col-md-12">
      <div class="box">
         <a href="{{ route('sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Cancel</a>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right">Next Page >></button>
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->
      </div>  
    </div>    
    <!-- End -->  
  </div> 
</form> 
@stop
