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
         <a href="{{ route('sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Cancel</a>
         @if ($minus_budget == 'Y') 
            <button type="submit" class="btn bg-maroon btn-sm margin pull-right" disabled="disabled">Submit</button>
         @else
            <button type="submit" class="btn bg-maroon btn-sm margin pull-right"  id="submit_btn" name = "submit_btn" value="SUBMIT">Submit</button>
         @endif
         <a href="{{ route('SentraPrevPage3') }}" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </a>
         <button type="Submit" class="btn bg-purple btn-sm margin pull-right" id="submit_btn" name = "submit_btn" value="DRAFT">Save As Draft</button>
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
      </div> 

    <!-- footer -->
    <div class="col-xs-12">
      <div class="box">
         <a href="{{ route('sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Cancel</a>
         @if ($minus_budget == 'Y') 
            <button type="submit" class="btn bg-maroon btn-sm margin pull-right" disabled="disabled">Submit</button>
         @else
            <button type="submit" class="btn bg-maroon btn-sm margin pull-right"  id="submit_btn" name = "submit_btn" value="SUBMIT" >Submit</button>
         @endif
         <a href="{{ route('SentraPrevPage3') }}" class="btn bg-maroon btn-sm margin pull-right"><< Prev Page </a>
         <button type="Submit" class="btn bg-purple btn-sm margin pull-right" id="submit_btn" name = "submit_btn" value="DRAFT">Save As Draft</button>
         <!--<button type="submit" class="btn bg-maroon margin pull-right"><< Prev Page</button>-->       
      </div> 
    </div>
  </div>  
</form> 
@stop
