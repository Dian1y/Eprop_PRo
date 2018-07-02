@extends('eprop_search.base') 

@section('content_header')
       <h1>
        Search for Eproposal
        <small>Daftar Eproposal</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">eprop_search</a></li>
        <li class="active">index</li>
      </ol>
@stop

@section('content')
<section class="content">
  <form class="form-horizontal form-group-sm" method="POST" action="{{ route('search.eprop') }}">
      <div class="row">
         <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
        <div class="col-xs-12">
          <br>
          <!-- /.box --> 
          <div class="box box-danger">
            <div class="col-xs-12">
            <!-- Headers -->
            <div class="box-body">
                <!--Date -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Periode</label>
                  <div class="col-md-3 input-group date">
                    <div class="input-group-addon">
                      <i class="fa fa-calendar"></i>
                    </div>
                    <input type="text" class="form-control pull-right" id="eprop_date">
                  </div>
                </div>
                <!--Division -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Divisi/Departemen</label>
                  <div class="col-md-4">
                      <select class="form-control select2"  style="width: 75%;" name="divisi_id" id="divisi_id" type="text">
                      <option> </option>
                        @foreach($user_division as $value)
                            <option value="{{$value->id}}">
                              {{$value->division_name}}
                          </option>
                        @endforeach
                      </select>
                  </div> 
                </div>
                <!-- Region -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Region</label>
                  <div class="col-md-4">
                      <select class="form-control select2"   style="width: 75%;" name="region_id" id="region_id" type="text">
                        <option> </option>
                        @foreach($region as $value)
                            <option value="{{$value->region_id}}">
                              {{$value->region}}
                            </option>
                        @endforeach
                        </select>  
                  </div>       
                </div> 
                <!-- Branch -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Branch</label>
                  <div class="col-md-4">
                      <select class="form-control select2"   style="width: 75%;" name="branch_id" id="branch_id" type="text">
                        <option> </option>  
                        @foreach($person_info as $value)
                            <option value="{{$value->area_id}}">
                              {{$value->area}}
                            </option>
                        @endforeach 
                        </select>  
                  </div>
                </div>  
                <!-- Executor -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Executor</label>
                  <div class="col-md-4"> 
                      <select class="form-control select2"  style="width: 75%;" name="executor_id" id="executor_id" type="text">
                        @if ($administrator_flag == 'Y') 
                          <option> </option>
                        @endif
                        @foreach($executor as $value)
                            <option value="{{$value->person_id}}">
                              {{$value->name}}
                            </option>
                          @endforeach
                      </select> 
                  </div>  
                </div> 
                <!-- Brand -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Brand</label>
                  <div class="col-md-4">
                      <select class="form-control select2"  style="width: 75%;" name="brand_id" id="brand_id" type="text">
                        <option> </option>
                        @foreach($brand as $value)
                            <option value="{{$value->id}}">
                              {{$value->brand_name}}
                            </option>
                          @endforeach
                      </select> 
                  </div> 
                </div>
                <!-- Activity -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Activity</label>
                  <div class="col-md-4">
                      <select class="form-control select2"    style="width: 75%;" name="activity_id" id="activity_id" type="text" >    
                          <option> </option>          
                          @foreach($user_activity as $value)
                            <option value="{{$value->activity_id}}">
                              {{$value->activity_name }}
                            </option>
                          @endforeach
                      </select>    
                  </div> 
                </div>
                <!-- Distributor -->
                <div class="form-group">
                    <label class="col-sm-2 control-label">Distributor</label>
                    <div class="col-md-4">
                      <select class="form-control select2" style="width: 75%;" name="cust_ship_id" id="cust_ship_id" type="text" > 
                         <option> </option>
                        @foreach($customer_info as $value)    
                            <option value="{{$value->cust_ship_id}}">
                              {{$value->customer_ship_name}}
                            </option>
                        @endforeach  
                        </select>  
                    </div>     
                </div>
                <!-- Account  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Account</label>
                  <div class="col-md-4">
                    <select class="form-control select2"  style="width: 75%;" name="account_mt" id="account_mt" type="text"> 
                      <option> </option>
                      @foreach($account_mt as $value)
                          <option value="{{$value->id}}">
                            {{$value->account_name}}
                          </option>
                      @endforeach 
                      </select>  
                  </div> 
                </div>
                <!-- Status  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Status Proposal</label>
                  <div class="col-md-4">
                    <select class="form-control select2" style="width: 75%;" name="status" id="status" type="text"> 
                      <option> </option>
                      @foreach($status as $value)
                          <option value="{{$value->value_name}}">
                            {{$value->value_name}}
                          </option>
                      @endforeach 
                      </select>  
                  </div>  
                </div>
                <!-- Proposal Number  -->
                <div class="form-group">
                  <label class="col-sm-2 control-label">Proposal No. </label>
                  <div class="col-md-4">
                    <select class="form-control select2"  style="width: 75%;" name="eprop_no" id="eprop_no" type="text"> 
                      <option> </option>
                      @foreach($eprop_no as $value)
                          <option value="{{$value->eprop_no}}">
                            {{$value->eprop_no}}
                          </option>
                      @endforeach 
                      </select>  
                  </div>  
                </div>
                <button type="submit" class="bg-olive btn-sm margin">Search</button>
            </div>
            <!-- End of Headers -->
            <!-- /.box-index -->
            <div class="box-body">
              <table id="eprop_lists" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>No. Eproposal</th>
                    <th>No. Reco</th>
                    <th>No. Reference</th>
                    <th>Title</th>                    
                    <th>Budget Year </th>
                    <th>Market Type </th>
                    <th>Division</th>
                    <th>Originator</th>
                    <th>Originator Position</th>
                    <th>Approval Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                        <?php $i = 0 ?>
                        @if(isset($eprop_summary))
                        @foreach($eprop_summary as $value)
                        <?php $i++ ?>
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value->eprop_no) }} </td>
                          <td> {{ ($value->reco_no) }} </td>
                          <td> {{ ($value->eprop_reference_num) }} </td>
                          <td> {{ ($value->description) }} </td>
                          <td> {{ ($value->budget_year) }} </td>
                          <td> {{ ($value->type_market) }} </td>
                          <td> {{ ($value->division_name) }} </td>
                          <td> {{ ($value->originator_name) }} </td>
                          <td> {{ ($value->position_name) }} </td>
                          <td> {{ ($value->apprv_status) }} </td>
                          @if ($administrator_flag == 'N' and $originator == 'Y') 
                            <td><a href="{{ route('copy.eprop',$value->header_id)}}" class="btn btn-success btn-sm">Copy</a>
                            <a href="#" class="btn btn-warning btn-sm">Revisi</a>
                            <a href="#" class="btn btn-danger btn-sm">Batal</a>
                            <a href="#" class="btn btn-info btn-sm">print</a></td>
                          @else 
                            <td>
                            <a href="#" class="btn btn-info btn-sm">print</a></td>
                          @endif
                        </tr>
                        @endforeach
                        @endif
                    </tbody>
              </table>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

  </form>    
  <div class="box-footer">
      <div>
        <a href="{{ url('/home') }}" class="btn btn-warning ">Close</a>   
      </div>
  </div>
</section>
@stop
  