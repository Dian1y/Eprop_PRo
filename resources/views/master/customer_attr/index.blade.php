@extends('master.customer_attr.base') 

@section('content_header')
	     <h1>
        List Of Customer Attribute
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Customer Attribute</a></li>
        <li class="active">Index</li>
      </ol>  
@stop

@section('content')
<div id="example2_wrapper" class="fluid-container">
  <div class="row">
     <div class="col-xs-12">
       <div class="box box-danger">
       	<div class="col-xs-12">
               <div class="box-body"> 
              	<table  class="table table-bordered table-striped" id="cust_table">
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
                            <td>
                            @if ($value->ada_attr == 0) 
                               <a href="{{ route('create_attr',$value->cust_ship_id) }}" class="btn btn-info btn-sm"><i class="fa fa-align-left" title="Align Justify"></i>   Add Attribute</a>
                            @else
                                <a href="{{ route('customer_attr.show',$value->cust_ship_id) }}" class="btn btn-info btn-sm"><i class="fa fa-align-left" title="Align Justify"></i>   Show Attribute </a>
                                <a href="{{ route('customer_attr.edit',$value->cust_ship_id) }}" class="btn btn-danger btn-sm"><i class="fa fa-align-left" title="Align Justify"></i>  Edit Attribute</a>
                            @endif
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
  