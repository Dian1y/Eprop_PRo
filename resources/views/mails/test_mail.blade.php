@extends('master.persons.base') 

@section('content_header')
       <h1>
        List of Persons
        <small>Daftar Nama Pengguna CMO Apps</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Person</a></li>
        <li class="active">index</li>
      </ol>
@stop

@section('content')
<section class="content">
      <div class="row">
         
        <div class="col-xs-12">
          <div> 
            <a class="btn btn-warning" href="{{ route('testSendMail') }}">Test Mail</a> 
          </div>
         </div>
      </div>
</section>
@stop