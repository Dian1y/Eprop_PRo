@extends('master.area.base') 

@section('content_header')
       <h1>
        List of Area
        <small>Daftar Area</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Area</a></li>
        <li class="active">index</li>
      </ol>
@stop

@section('content')
<section class="content">
      <div class="row">
         
        <div class="col-xs-12">
          <div>
            <a class="btn btn-warning" href="{{ route('area.create') }}">Add Area</a>
          </div>
          <br>
          <!-- /.box -->
          <div class="box box-danger">
            <div class="col-xs-12">
            <!-- /.box-header -->
            <div class="box-body">
              <table id="listofarea" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Area</th>
                    <th>SubRegion</th>
                    <th>Region</th>
                    <th>Action      </th>
                  </tr>
                </thead>
                <tbody>
                        <?php $i = 0 ?>
                        @foreach($Area as $value)
                        <?php $i++ ?>
                        <tr>
                          <td> {{ $i }} </td>
                          <td> {{ ($value->area) }} </td>
                          <td> {{ ($value->subregion) }} </td>
                          <td> {{ ($value->region) }} </td>
                          <td><a href="{{ route('area.edit',$value->id) }}" class="btn btn-success btn-sm">Edit</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                <tfoot>
                <tr>
                    <th>No.</th>
                    <th>Area</th>
                    <th>SubRegion</th>
                    <th>Region</th>
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
  