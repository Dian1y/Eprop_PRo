
@extends('layouts.layout_template')

@section('content_header')
     @yield('content_header')
@endsection

@section('content')
    @yield('content')
@endsection

@push('scripts')
<script>
  $(function () {
    $('.select2').select2()
    $('#example1').DataTable()
    $('#example').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    });
  });
</script> 

<script type="text/javascript">
  $(document).ready(
      function () {
          $("#area_id").select2();                
      }
  );

    //load datatables on area change    
    $("#area_id").on('change', function() {

      var areaid = $("#area_id").val();
      $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
      });

      $('#person_area').DataTable({
          "processing": true,
          "serverSide": true, 
          'ajax': {
              "url": "{{  url('master/customer_attr/getPArea') }}",
              "dataType": "json",
              "type": "POST",
          },
          columns: [
            { data: 'Person', name: 'Person' },
            { data: 'Email', name: 'Email' },
            { data: 'Position', name: 'Position' },
            { data: 'job', name: 'job' },
            { data: 'region', name: 'region' },
            { data: 'subregion', name: 'subregion' },
            { data: 'area', name: 'area' },
            {data: 'action', name: 'action', orderable: false, searchable: false}
          ]
      });
    });
</script>


@endpush
