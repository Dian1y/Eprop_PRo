@extends('layouts.base') 

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
<div id="example2_wrapper" class="fluid-container">
  <div class="row">
     <div class="col-xs-12"> 
       <div class="box box-danger">
        <div class="col-xs-12">
              <div class="box-body">
               <form method="POST" action="{{ route('person.search') }}">
                   {{ csrf_field() }}
                   @component('layouts.search', ['title' => 'Search'])
                    @component('layouts.two-cols-search-row', ['items' => ['Name'], 
                    'oldVals' => [isset($searchingVals) ? $searchingVals['name'] : '']])
                    @endcomponent
                  @endcomponent
                </form>
              </div> 
               <div>
                  <a class="btn btn-warning" href="{{ route('persons.create') }}">Add Person</a>
               </div> 

                <table id="cust_table" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Position</th>
                        <th>Region</th>                        
                        <th>Sub Region</th>                        
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
                          <td> {{ $value->region }} </td>
                          <td> {{ $value->subregion }} </td>
                          <td> {{ $value->status }} </td>
                          <td><a href="{{ route('persons.edit',$value->id) }}" class="btn btn-success btn-sm">Edit</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                  {!! $Persons->render() !!}
              </div> 
       <!--end box danger -->
       </div>
     </div>
  </div>
</div>
@stop
  
@push('scripts')
<script>
  $(function () {
    $('#cust_table').DataTable()
    $('#cust_table').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
@endpush