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
            <a class="btn btn-warning" href="{{ route('persons.create') }}">Add Person</a> 
          </div>
          <br>
          <!-- /.box -->
          <div class="box box-danger">
            <div class="col-xs-12">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Position</th>
                    <th>Jobs</th>                    
                    <th>Region</th>                        
                    <th>Sub Region</th>    
                    <th>Area/Coverage</th>  
                    <th>Status</th>
                    <th>Action      </th>
                  </tr>
                </thead>
                <tbody>
                        <?php $i = 0 ?>
                        @foreach($Persons as $value)
                        <?php $i++ ?>
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value->name) }} </td>
                          <td> {{ ($value->position) }} </td>
                          <td> {{ ($value->job) }} </td>
                          <td> {{ $value->region }} </td>
                          <td> {{ $value->subregion }} </td>
                          <td> {{ $value->area }} </td>
                          <td> {{ $value->status }} </td>
                          <td><a href="{{ route('persons.edit',$value->person_id) }}" class="btn btn-success btn-sm">Edit</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Nama</th>
                    <th>Position</th>
                    <th>Region</th>                        
                    <th>Sub Region</th>                        
                    <th>Status</th>
                    <th>Action      </th>
                  </tr>
                </tfoot>
              </table>
            </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
</section>
@stop
  