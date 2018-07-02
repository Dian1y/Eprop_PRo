@extends('master.group_akses.base') 

@section('content_header') 
       <h1>
        List Of Group Akses </small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Company</a></li>
        <li class="active">Master Group Akses</li>
      </ol>  
@stop

@section('content')
<div class="row">
   <div class="col-xs-12">
     <div class="box box-danger">
      <div class="box-header">
         <div class="col-sm-4">
           <a class="btn btn-primary" href="{{ route('group_akses.create') }}">Add Group Akses</a>
       </div>
     </div>
      <div class="col-xs-10">
             <div class="box-body">
              <table id="example1" name = "example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>Group Akses Code</th>
                      <th>Group Akses Name</th>
                      <th>Akses Name</th>
                      <th>Akses Key</th>
                      <th>Active</th>
                    </tr>
                  </thead>
                  <tbody>
                      <?php $i = 0 ?>
                      @if (isset($GrpAks))
                        @foreach($GrpAks as $value)
                        <?php $i++ ?>
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value->group_akses_code) }} </td>
                          <td> {{ ($value->desription) }} </td>
                          <td> {{ ($value->akses_name) }} </td>
                          <td> {{ ($value->akses_key) }} </td>
                          <td> {{ ($value->active) }} </td>
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
 