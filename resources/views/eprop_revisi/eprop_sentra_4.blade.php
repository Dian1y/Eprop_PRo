@extends('eprop.base') 

@section('content_header')
    <h1>
        Input Proposal
        <small>Budget Break Down</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Proposal</a></li>
        <li class="active">sentral</li>
      </ol> 
@stop

@section('content')
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('PostFourthPage') }}">
  <div class="row">
     <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <!-- header -->
    <div class="col-xs-12">
      <div class="box">
         <button type="submit" class="btn bg-orange btn-sm margin">Cancel</button>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right"> Submit </button>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </button>
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->       
      </div> 
    </div>
    <!-- Body -->
      <div class="col-xs-12">
          <div class="col-xs-8">
            <label>Upload file Attachment</label>
            <div> 
            <input type="file" name="import_file" />   
            </div>
          </div>
          <font size="2" face="Courier New" >
              <h4>Budget Info</h4>
              <table id="budgetinfo" name = "budgetinfo" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Company</th>
                      <th>Kategory Activity Name</th> 
                      <th>Brand</th>
                      <th>Account Name</th>
                      <th>Current Budget (Min 10%)</th>
                      <th>Budget to Approve</th>
                      <th>Outstanding</th>
                    </tr>
                  </thead>
                  <tbody>
 
                      <?php $i = 0 ?>
                       @foreach($budgetApprove as $value)
                        <?php $i++ ?>  
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value['company_name']) }} </td>
                          <td> {{ ($value['kategory_name']) }} </td>
                          <td> {{ ($value['brand_name']) }} </td>
                          <td> {{ ($value['account_name']) }} </td>
                          <td> {{ number_format($value['current_budget'], 2,',', '.') }} </td>
                          <td> {{ number_format($value['budget_tobe_Apprv'], 2, ',', '.') }} </td>
                          <td> {{ number_format($value['outstanding'], 2, ',', '.') }} </td> 
                        </tr>
                        @endforeach
                    </tbody>
              </table>  
          </font>  
            <div class="form-group">
                <label class="col-sm-3 control-label">Total Budget</label>
                <div class="col-md-2">
                    <Input type="text" name="total_budget" id="total_budget"  value= "{{ number_format($Total_amount, 0, ',', '.') }}" style="font-size: 13px;text-align: right;font-weight: bold;"   required="required" readonly="readonly"> 
                </div>
            </div>             
            <div class="form-group">
                <label class="col-sm-3 control-label">Target</label>
                <div class="col-md-2">
                  <select class="form-control select2"   style="width: 75%;" name="target" id="target" type="text"> 
                    @foreach($targetEprop as $value)
                        <option value="{{$value->value_name}}">
                              {{$value->value_name}}
                        </option>
                    @endforeach 
                    </select> 
                </div>
            </div> 
            <div class="form-group">
                <label class="col-sm-3 control-label">Jumlah Target</label>
                <div class="col-md-4">
                  <Input type="number" name="jmltarget" id="jmltarget" style="font-size: 13px;text-align: right;"   required="required"> 
                </div>
            </div>   
            <h5>SALES TARGET</h5>
            <h6>==============</h6>
            <h5>TARGET SALES</h5>   
            <div class="form-group">
                <label class="col-sm-3 control-label">Averages Sales Min For The Last 3 Month</label>
                <div class="col-md-2">
                  <Input type="number" name="avg_sales" id="avg_sales"   style="font-size: 13px;text-align: right;" step="1000"  required="required" > 
                </div>
            </div>   
            <div class="form-group">
                <label class="col-sm-3 control-label">Target Sales (Value)</label>
                <div class="col-md-2">
                  <Input type="number" name="sales_value" id="sales_value"  style="font-size: 13px;text-align: right;"   required="required" > 
                </div>
            </div>   
            <h5>COST ANALYSIS</h5>  
            <div class="form-group">
                <label class="col-sm-3 control-label">Target value vs target sales</label>  <!-- sales/average -->
                <div class="col-md-2">
                  <Input type="text" name="sales_compare" id="sales_compare" style="font-size: 13px;text-align: right;"  required="required"
                  readonly="readonly"> 
                </div>
                %
            </div>   
            <div class="form-group">
                <label class="col-sm-3 control-label">Cost Ratio</label>  <!-- Budget/Target Sales -->
                <div class="col-md-2">
                  <Input type="text" name="cost_ratio" id="cost_ratio" style="font-size: 13px;text-align: right;"   required="required" readonly="readonly">  
                </div>
                %
            </div>   
      </div> 

    <!-- footer -->
    <div class="col-xs-12">
      <div class="box">
         <button type="submit" class="btn bg-orange btn-sm margin">Cancel</button>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right"> Submit >></button>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </button>
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->       
      </div> 
    </div>
  </div>  
</form> 
@stop
