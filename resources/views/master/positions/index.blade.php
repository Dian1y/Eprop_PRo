@extends('master.positions.base') 

@section('content_header')
       <h1>
        List of Position
        <small>Daftar Nama Positions</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Positions</a></li>
        <li class="active">index</li>
      </ol>
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
      <div class="box-header">
         <div class="col-sm-4">
           <a class="btn btn-primary" href="{{ route('positions.create') }}">Add Position</a>
       </div>
     </div>
      <div class="col-xs-10">
             <div class="box-body">
              <table id="Posisi" name = "tbjob" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Position Nama</th>
                      <th>Active</th>
                      <th>Action </th
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                      @if (isset($GrpAct))
                        @foreach($GrpAct as $value)
                        <?php $i++ ?>
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value->position_name)  }} </td> 
                          <td> {{ ($value->active) }} </td>
                          <td><a href="{{ 
                          route('positions.edit',$value->id) }}
                          " class="btn btn-success btn-sm">Edit</a></td>
                        </tr>
                        @endforeach
                      @endif
                  </tbody>
                </table>  
              </div>  
            </div> 
     <!--end box danger -->
     </div>
   </div>
</div>
@stop
  