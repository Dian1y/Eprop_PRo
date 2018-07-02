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
      @if ($submit == 'SUBMIT') 
        <h3> Proposal sudah berhasil di simpan!!</h3>
        <h3> No. Proposal : {{ $EpropNum }}</h3>
      @else
        <h3> Proposal disimpan Sebagai DRAFT!!</h3>
        <h3> No. Proposal : {{ $EpropNum }}</h3>      
      @endif
     </div> 
     <div class="box-footer">
        <a href="{{ route('sentra.Cancelled') }}" class="btn bg-orange btn-sm margin">Close</a>
        @if ($submit == 'SUBMIT')
          <a href="{{ url('/home') }}" class="btn btn-danger btn-md">Lihat Detail Eproposal</a>
        @endif
     </div>     
	</div>	
</form>	
@stop
