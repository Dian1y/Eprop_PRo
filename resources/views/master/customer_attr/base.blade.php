
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
    $('#person_area').DataTable()
    $('#cust_table').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false,
      'responsive'  : true
    });
  });

  $("#default_fleet").on('change', function() {
      var data =$("#default_fleet").val();
      console.log(data);      
      $.get('/master/customer_attr/getMOQ/' + data, function(data){
          console.log(data);
          $('#minqtyorder').prop('value', data); 
    })
  });

  $("#allperson").click(function(){
            if($("#allperson").is(':checked') ){
                $("#person_id").find('option').prop("selected",true);
                $("#person_id").trigger("change");
            }else{
                $("#person_id").val('').trigger("change"); 

             }
        });


  $("#area_id").on('change', function() {
      var data =$("#area_id").val();
      console.log(data);
      $('#person_id').empty();      
      $.get('/master/customer_attr/GetPArea/' + data, function(data){
        $.each(data, function(index,areaObj){
          $('#person_id').append('<option value="'+areaObj.person_id+'">'+areaObj.name+'</option>');
       }) 
    })
  });

</script> 

    
@endpush
