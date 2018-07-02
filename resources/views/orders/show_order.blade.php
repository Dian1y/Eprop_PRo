@extends('layouts.base')

@section('content_header')
	  <h1>
        Detail Orders
        <small>Confirm Monthly Orders</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Orders</a></li>
        <li class="active">Show Detail Orders</li>
      </ol>  
@stop

@section('content')  

 <div class="row">
    <div class="col-md-12">
        <div class="box box-danger">
          <form class="form-horizontal form-group-sm"  }}">
            <!-- Headers -->
            <div class="box-body">
                <div class="form-group">
                    <label class="col-xs-2 control-label">Distributor</label>
                    <div class="col-xs-3">
                        @foreach($cust_order as $value)
                            <input type="text"  name="distributor" id="distributor" value = "{{ $value->customer_name }}" class="form-control" readonly="true" required/> 
                        @endforeach
                    </div>   
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Ship Name</label>
                    <div class="col-xs-3"> 
                        @foreach($cust_order as $value)
                            <input type="text"  name="customer_ship_name" id="customer_ship_name" value = "{{ $value->customer_ship_name }}" class="form-control" readonly="true" required/> 
                        @endforeach
                    </div>   
                </div>
                <div class="form-group">
                    <label class="col-xs-2 control-label">Cabang</label>       
                    <div class="col-xs-3">
                          @foreach($cust_order as $value)
                               <input type="text" name="cabang" id="cabang"  value="{{ $value->subregion }}" class="form-control" readonly="true" required/> 
                          @endforeach
                    </div>         
                </div> 
                <div class="form-group">
                    <label class="col-xs-2 control-label">Minimun Qty Order (Crt)</label>
                    <div class="col-xs-3">
                      @foreach($cust_order as $value)
                          <input type="number" name="moq" value =  "{{ $value->MOQ }}" class="form-control" readonly="true" required/>     
                      @endforeach
                    </div>
                </div>  
                <div class="form-group">
                  <label class="col-xs-2 control-label">Kirim Ke</label>
                  <div class="col-xs-10"> 
                          @foreach($cust_order as $value)
                               <input type="text" name="kirimke" id="kirimke" value="{{ $value->alamat_cust }}" class="form-control service" readonly="true" required/> 
                          @endforeach 
                  </div>  
                </div>
                <div class="form-group">
                  <label class="col-xs-2 control-label">Periode CMO</label>
                  <div class="col-xs-3">
                          @foreach($trx_rkp as $value)
                               <input type="text" name="periode" id="periode" value="{{ date('F Y', strtotime($value->periode_cmo)) }}" class="form-control service" readonly="true" required/> 
                               @break
                          @endforeach
                  </div>  
                </div>
            </div>
            <!-- End of Headers -->
            <!-- Table Header --> 
            <div class="col-xs-4">
              <div class="box-body">
                <table id="tbldeliver" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Tgl Kirim</th>
                      <th>Qty Carton</th>
                      <th>Value</th>
                      <th>Fleet</th>
                    </tr>
                  </thead>
                  <tbody>
                      @foreach($trx_rkp as $value) 
                      <tr>
                        <td> {{ date('d/m/Y', strtotime($value->datedlv)) }} </td>
                        <td> {{ $value->total_crt }} </td>
                        <td> {{ number_format($value->value_order, 2, ',', '.') }} </td>
                        <td><a href="{{ route('fleet_details',$value->datedlv) }}" class="btn btn-success btn-sm">Detail</a></td>
                      </tr>
                      @endforeach
                  </tbody>
                </table>  
              </div>  
            </div> 
            <!-- End Of Table Header -->
            <!-- Table Details  -->
            <div class="col-xs-8">
              <div class="box-body">
                <table id="tbldetails" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Tgl Kirim</th>
                      <th>Kode Item</th>
                      <th>Nama Item</th>
                      <th>Qty</th>
                      <th>Harga</th>
                      <th>Diskon</th>
                      <th>Total</th>
                      <th>Netto + PPN 10%</th>      
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($trx_detail->sortBy('delivery_date')  as $value)
                    <tr>
                        <td> {{ date('d/m/Y', strtotime($value->delivery_date)) }} </td>
                        <td> {{ $value->kode_item }} </td>
                        <td> {{ $value->kode_descr }} </td>                            
                        <td> {{ $value->delivery_qty }} </td>                            
                        <td> {{ number_format($value->price_crt, 2, ',', '.') }} </td>
                        <td> {{ $value->disc_percent }} % </td>
                        <td> {{ number_format($value->netto, 2, ',', '.') }} </td>
                        <td> {{ number_format($value->extended_price, 2, ',', '.') }} </td> 
                    </tr> 
                    @endforeach
                  </tbody>
                </table>  
              </div>  
            </div>      
            <!-- End of Table Details  -->
            <!-- End Of Table -->
            <!-- Box Footer -->
            <div class="box-footer">
               <a href="{{ url('/home') }}" class="btn btn-warning btn-sm">OK</a> 
            </div>
            <!-- End Of Box Footer --> 
          </form>
        </div>    
    </div> 
 </div>
  
@stop
  