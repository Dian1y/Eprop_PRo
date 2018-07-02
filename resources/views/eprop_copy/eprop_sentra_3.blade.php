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
<form class="form-horizontal form-group-sm" method="POST" action="{{ route('CPostThridPage') }}">
	<div class="row">
	   <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
    <!-- header -->
    <div class="col-xs-12">
      <div class="box">
         <a href="{{ route('copy.sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Cancel</a>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right">Next Page >></button>
         <a href="{{ route('copy.SentraPrevPage2') }}" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </a>
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->       
      </div> 
    </div>
    <!-- Body -->   
      <div class="col-xs-12">
        <div class="box-body">
          <font size="2" face="Courier New" >
            <!--<div class="form">
              <label class="col-sm-2 control-label">Total Budget</label>
              <input type="number" style="font-size:15px;font-weight: bold;" min="0" step="0.01" data-number-to-fixed="2" data-number-stepfactor="100" class="currency" id="budgetallocation" name="budgetallocation"> 
              <button class="btn btn-success" type="button" id="bagirata" onclick="BagiRata()">Bagi Sama Rata</button>
            </div>
              <h4>Budget Break Down</h4> -->              
              <table id="budgetbreak" name = "budgetbreak" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Divisi</th>
                      <th>Activity Name</th>                                            
                      <th>Region</th>                                            
                      <th>Branch</th>
                      <th>Brand</th>
                      <th>Produk</th>
                      <th>Account Name</th>
                      <th>Executor</th>
                      <th>Budget Allocation</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                       @foreach($budget_breakdown as $value)
                        <?php $i++ ?>
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value->division_name) }} </td>
                          <td> {{ ($value->activity_name) }} </td>
                          <td> {{ ($value->region) }} </td>
                          <td> {{ ($value->area) }} </td>
                          <td> {{ ($value->brand_name) }} </td>
                          <td> {{ ($value->produk_name) }} </td>
                          <td> {{ ($value->account_name) }} </td>
                          <td> {{ ($value->person_name) }} </td>
                          <td><input type="number" style="font-size:13px;font-weight: bold;text-align: right;" id="budgetallocation" name="budgetallocation[]" value="{{ ($value->budget_amount) }}" required="required"></td>
                          <td><input type="hidden" style="font-size:13px;font-weight: bold;text-align: right;" id="row_number" name="row_number[]" value = "{{ ($value->row_number) }} "></td>
                        </tr>
                        @endforeach
                    </tbody>
              </table>  
          </font>
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
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                       @foreach($budget_info as $value)
                        <?php $i++ ?>
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value->company_name) }} </td>
                          <td> {{ ($value->kategory_name) }} </td>
                          <td> {{ ($value->brand_name) }} </td>
                          <td> {{ ($value->account_name) }} </td>
                          <td> {{ number_format($value->current_budget, 2, ',', '.') }} </td>
                        </tr>
                        @endforeach
                    </tbody>
              </table>  
          </font>
        </div>    
      </div> 

    <!-- footer -->
    <div class="col-xs-12">
      <div class="box">
         <a href="{{ route('copy.sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Cancel</a>
         <button type="submit" class="btn bg-maroon btn-sm margin pull-right">Next Page >></button>
         <a href="{{ route('copy.SentraPrevPage2') }}" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </a>
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->       
      </div> 
    </div>
	</div>	
</form>	
@stop
