@extends('eprop.base') 

@section('content_header')
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Proposal</a></li>
        <li class="active">sentral</li>
      </ol> 
@stop

@section('content')
<form class="form-horizontal form-group-sm"">
	<div class="row">
	   <div class="box box-danger">
      <h3> Proposal sudah berhasil di simpan!!</h3>
      <h3> No. Proposal : {{ $EpropNum }}</h3>
     </div> 
     <div class="box-footer">
        <a href="{{ url('/home') }}" class="btn btn-warning btn-md">Close</a>
        <a href="{{ url('/home') }}" class="btn btn-danger btn-md">Lihat Detail Eproposal</a>
     </div>     
	</div>	
</form>	
@stop
