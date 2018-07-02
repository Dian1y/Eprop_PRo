@extends('master.customer_fleet.base') 

@section('content_header')
	     <h1>
        List Of Customer Fleet
        <small>Daftar Kendaraan Customer</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Fleet</a></li>
        <li class="active">Customer Fleet</li>
      </ol>  
@stop

@section('content')
<div id="example2_wrapper" class="fluid-container">
  <div class="row">
     <div class="col-xs-12">
       <div class="box box-danger">
       	<div class="col-xs-12">
               <div class="box-body">
              	<table class="table table-bordered table-striped" id="cust_table" name="cust_table">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Kode Customer</th>
                        <th>Nama Customer</th>
                        <th>Cabang Customer</th>
                        <th>Alamat Kirim</th>
                        <th>Action      </th>
                      </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0 ?>
                        @foreach($CustomerView as $value)
                        <?php $i++ ?>
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value->customer_number) }} </td>
                          <td> {{ ($value->customer_name) }} </td>
                          <td> {{ $value->customer_ship_name }} </td>
                          <td> {{ $value->customer_ship }} </td>
                          <td><a href="{{ route('customer_fleet.show',$value->cust_ship_id) }}" class="btn btn-info btn-sm"><i class="fa fa-align-left" title="Align Justify"></i>   Show Fleet</a>
                          </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
              </div> 
       <!--end box danger -->
       </div>
     </div>
  </div>
</div>
@stop
  