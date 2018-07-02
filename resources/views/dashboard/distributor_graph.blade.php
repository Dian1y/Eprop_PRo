@extends('layouts.base') 

@section('content_header')
    <div class="row">
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>0</h3>

              <p>New Orders</p>
            </div>
            <div class="icon">
              <i class="ion ion-bag"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>0<sup style="font-size: 20px"></sup></h3>

              <p>Incoming Delivery</p>
            </div>
            <div class="icon">
              <i class="fa fa-truck"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>0</h3>

              <p>Invoice Due</p>
            </div>
            <div class="icon">
              <i class="fa fa-money"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>0</h3>

              <p>Principal Information</p>
            </div>
            <div class="icon">
              <i class="fa fa-info-circle"></i>
            </div>
            <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
          </div>
        </div>
        <!-- ./col -->
        <div class = "col-sm-8" >
          <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Need Your Actions</h3>
            </div>  
            <div class="box-body">
              <div class="table-responsive">
               <table id="tblAction" name = "tblAction" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No. </th>
                      <th>Keterangan</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 0 ?>
                    @foreach($wf_approval as $value)
                    <?php $i++ ?>                
                    <tr>
                      <td>{{$i}}</td>
                      <td> {{$value->subject}} </td>
                       <td><a href="{{ route('show_detail_order',[$value->header_id]) }}" class="btn btn-success btn-sm">Show Details</a>
                           <a href="{{ route('cmo.approve',[$value->id, $value->wf_key_id, 'Y']) }}" class="btn btn-success btn-sm">Approve</a>
                           <a href="{{ route('cmo.reject',[$value->id, $value->wf_key_id, 'Y']) }} " class="btn btn-warning btn-sm">Rejected</a>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
               </table>            
              </div>
            </div>
          </div>
        </div>
    </div>
@stop