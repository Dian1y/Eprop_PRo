
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
                $("#parent_pos_id").select2();
                $("#subordinate_pos_id").select2();
                $("#division_id").select2();                
                $("#brand_id").select2();                
            }
        );
      
      $("#parent_pos_id").on('change', function() {
          var data =$("#parent_pos_id").val();
          console.log(data);      
          $('#ParentHolder').prop('value', '');
          $.get('/master/wf_hirarki/getHolder/' + data, function(data){
               console.log(data); 
               var holder = '**' + data + ' Holder(s)';
              $('#ParentHolder').prop('value', holder ); 
        })
      });

     
</script>

@endpush
