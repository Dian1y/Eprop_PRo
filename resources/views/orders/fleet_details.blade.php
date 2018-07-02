@extends('layouts.base')

@section('content_header')
	  <h1>
        Detail Order by Fleet
        <small>Confirm Monthly Orders</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Orders</a></li>
        <li class="active">Upload Orders</li>
      </ol>  
@stop

@section('content')
  <div class="row">
  	<div class="col-md-12">
	  	<div class="box box-info">
	  		<!-- /.box-header -->
	  		<form class="form-horizontal form-group-sm">
	  		<div class="col-xs-10">
                <div class="box-body">  
                  <table id="tbldetails" class="table table-bordered table-striped">
                    <thead>
                    <tr>
                      <th>Tgl Kirim</th>
                      <th>Fleet Ke</th>
                      <th>Jenis Fleet</th>
                      <th>kode Item</th>
                      <th>Nama Item</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Diskon</th>
                      <th>Total</th>
                      <th>Netto + PPN 10%</th>
                    </tr>
                    </thead>
                    <tbody>
                      @if(Session::has('DetailFleet'))
                      @foreach(Session::get('DetailFleet') as $value)
                      <tr>
                            <td> {{ date('d/m/Y', strtotime($value['delivery_date'])) }} </td>
                            <td> {{ $value['Fleetke'] }} </td>
                            <td> {{ $value['Fleet'] }} </td>
                            <td> {{ $value['kode_item'] }} </td>
                            <td> {{ $value['kode_descr'] }} </td>                            
                            <td> {{ $value['delivery_qty'] }} </td>                            
                            <td> {{ number_format($value['price_crt'], 2, ',', '.') }} </td>
                            <td> {{ $value['disc_percent'] }} % </td>
                            <td> {{ number_format($value['netto'], 2, ',', '.') }} </td>
                            <td> {{ number_format($value['extended_price'], 2, ',', '.') }} </td>  
                      </tr>
                      @endforeach
                      @endif
                    </tbody>
                  </table>
                </div>
              </div> 
        </form>
        </div>
    </div>
    <div class="box-footer"> 
       <!-- href="{{ url('/fleet_back') }}" class="btn btn-warning btn-sm">Back</a> -->
    </div>
  </div>
@endsection