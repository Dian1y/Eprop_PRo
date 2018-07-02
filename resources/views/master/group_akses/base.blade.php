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
                $("#company_code").select2();
                $("#division_code").select2();
                $("#id").select2(); 
                $("#type_market_code").select2(); 
                $("#brand_code").select2(); 
                $("#account_code").select2(); 
            }
        );
        //Select All Checkbox
          $("#allcompany").click(function(){
            if($("#allcompany").is(':checked') ){
                $("#company_code").find('option').prop("selected",true);
                $("#company_code").trigger("change");
            }else{
                $("#company_code").val('').trigger("change"); 
                $("#brand_code").select2('destroy');
                $("#brand_code").html("<option></option>");
                $("#brand_code").select2(); 
             }
        });

        $("#alldivisi").click(function(){
            if($("#alldivisi").is(':checked') ){
                $("#division_code").find('option').prop("selected",true);
                $("#division_code").trigger("change");
            }else{
                $("#division_code").val('').trigger("change"); 
                 }
        });

        $("#allkategory").click(function(){
            if($("#allkategory").is(':checked') ){
                $("#id").find('option').prop("selected",true);
                $("#id").trigger("change");
            }else{
                $("#id").val('').trigger("change"); 
            }
        });

          $("#allmarket").click(function(){
            if($("#allmarket").is(':checked') ){
                $("#type_market_code").find('option').prop("selected",true);
                $("#type_market_code").trigger("change");
            }else{
                $("#type_market_code").val('').trigger("change"); 
             }
        });

           $("#allbrand").click(function(){
            if($("#allbrand").is(':checked') ){
                $("#brand_code").find('option').prop("selected",true);
                $("#brand_code").trigger("change");
            }else{
                $("#brand_code").val('').trigger("change"); 
             }
        });  


          $("#allaccount").click(function(){
            if($("#allaccount").is(':checked') ){
                $("#account_code").find('option').prop("selected",true);
                $("#account_code").trigger("change");
            }else{
                $("#account_code").val('').trigger("change"); 
             }
        });  


        //****/

        $("#company_code").on('change', function() {
          var data = $("#company_code").val();
         // console.log(data);

          //$('#division_code').empty();
         // $('#id').empty();
         // $('#type_market_code').empty();
          $('#brand_code').empty();
          $.each($("#company_code").val(), function( index, value ) {
            
            $.get('/group_akses/getbrand/' + value, function(data){
                //success data
               // console.log(data);
                console.log('/group_akses/getbrand/' + value);
              $.each(data, function(index,subregObj){
                //console.log(value);
                   $("#brand_code").append('<option value="'+subregObj.id+'">'+subregObj.brand_name+'</option>');
              })
            })//
          })
        });

         
</script>

@endpush
