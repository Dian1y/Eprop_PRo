
@extends('layouts.layout_template')

@section('content_header')
     @yield('content_header')
@endsection

@section('content')
    @yield('content')
@endsection

@push('scripts')

<script>

  function enable_text(status)
      {     

        console.log(status);
        document.getElementById('avg_sales').disabled=!status;
        document.inputEprop.sales_value.disabled=!status;
        document.inputEprop.sales_compare.disabled=!status;
        document.inputEprop.cost_ratio.disabled=!status;
      }

  $(function () {
    //Initialize Select2 Elements
    $('.select2').select2()
    $('#grparea').DataTable()
    $('#budgetbreak').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'serverSide'  : true,
      'autoWidth'   : false
    });

    //Date range picker
    $('#eprop_date').daterangepicker()
  
    $(document).ready(function () {

        $("#company_id").select2();
        $("#divisi_id").select2();
        $("#activity_id").select2();                
        $("#market_type_id").select2();     
        $("#company_id").trigger("change");    
        $("#divisi_id").trigger("change");


        $('#sales_value').keyup(function() {
            
            var $this = $( this );
            var input = $this.val();

            var targetSales = $(this).val();
            var avgSales = $('#avg_sales').val();
            var totalAmount = $('#total_budget').val(); 
            totalAmount =  totalAmount.replace('.','');
            totalAmount =  totalAmount.replace('.','');
            totalAmount =  totalAmount.replace('.','');
            totalAmount =  totalAmount.replace('.','');

            console.log(totalAmount);
            var SalesCompare = (((targetSales/avgSales)*100)) ;
            var CostRatio = (((totalAmount/targetSales)*100));

            SalesCompare = SalesCompare.toFixed(2);
            CostRatio = CostRatio.toFixed(3);

            $('#sales_compare').val(SalesCompare) ;
            $('#cost_ratio').val(CostRatio);            
        })

    });

          $("#chktarget").click(function(){
            if ($(this).is(":checked")) {
              console.log($(this).is(":checked"));
              $("#avg_sales").removeAttr("required");
              $("#sales_value").removeAttr("required");
              $("#sales_compare").removeAttr("required");
              $("#cost_ratio").removeAttr("required");
              $("#avg_sales").attr("disabled", "disabled"); 
              $("#sales_value").attr("disabled", "disabled"); 
              $("#sales_compare").attr("disabled", "disabled"); 
              $("#cost_ratio").attr("disabled", "disabled"); 
            } else {
              console.log($(this).is(":checked"));
              $("#avg_sales").attr("required","required");              
              $("#sales_value").attr("required","required");              
              $("#sales_compare").attr("required","required");              
              $("#cost_ratio").attr("required","required");              
              $("#sales_value" ).removeAttr("disabled"); 
              $("#sales_compare" ).removeAttr("disabled"); 
              $("#cost_ratio" ).removeAttr("disabled"); 
              $("#avg_sales" ).removeAttr("disabled"); 
            } 
          });



         $("#allactivity").click(function(){
            if($("#allactivity").is(':checked') ){
                $("#activity_id").find('option').prop("selected",true);
                $("#activity_id").trigger("change");
            }else{
                $("#activity_id").val('').trigger("change"); 

             }
        });

        $("#allactivity").click(function(){
            if($("#allactivity").is(':checked') ){
                $("#activity_id").find('option').prop("selected",true);
                $("#activity_id").trigger("change");
            }else{
                $("#activity_id").val('').trigger("change"); 

             }
        });

        $("#allbrand").click(function(){
            if($("#allbrand").is(':checked') ){
                $("#brand_id").find('option').prop("selected",true);
                $("#brand_id").trigger("change");s
            }else{
                $("#brand_id").val('').trigger("change"); 

             }
        });

        $("#allvarian").click(function(){
            if($("#allvarian").is(':checked') ){
                $("#varian_id").find('option').prop("selected",true);
                $("#varian_id").trigger("change");s
            }else{
                $("#varian_id").val('').trigger("change"); 

             }
        });
        $("#alldisti").click(function(){
            if($("#alldisti").is(':checked') ){
                $("#cust_ship_id").find('option').prop("selected",true);
                $("#cust_ship_id").trigger("change");s
            }else{
                $("#cust_ship_id").val('').trigger("change"); 

             }
        }); 

        $("#markettype").click(function(){
            if($("#markettype").is(':checked') ){
                $("#market_type_id").find('option').prop("selected",true);
                $("#market_type_id").trigger("change");
            }else{
                $("#market_type_id").val('').trigger("change"); 

             }
        });

        $("#alldivisi").click(function(){
            if($("#alldivisi").is(':checked') ){
                $("#divisi_id").find('option').prop("selected",true);
                $("#divisi_id").trigger("change");s
            }else{
                $("#divisi_id").val('').trigger("change"); 

             }
        });

        $("#allarea").click(function(){
            if($("#allarea").is(':checked') ){
                $("#branch_id").find('option').prop("selected",true);
                $("#branch_id").trigger("change");
            }else{
                $("#branch_id").val('').trigger("change"); 

             }
        });

        $("#allexecutor").click(function(){
            if($("#allexecutor").is(':checked') ){
                $("#executor_id").find('option').prop("selected",true);
                $("#executor_id").trigger("change");
            }else{
                $("#executor_id").val('').trigger("change"); 

             }
        });

        $("#allaccount").click(function(){
            if($("#allaccount").is(':checked') ){
                $("#account_mt").find('option').prop("selected",true);
                $("#account_mt").trigger("change");
            }else{
                $("#account_mt").val('').trigger("change"); 

             }
        });

        $("#allstore").click(function(){
            if($("#allstore").is(':checked') ){
                $("#store_mt").find('option').prop("selected",true);
                $("#store_mt").trigger("change");
            }else{
                $("#store_mt").val('').trigger("change"); 

             }
        });

        $("#market_type_id").on('change', function() {
          var market  = $("#market_type_id").val();
          console.log(market);
          if (market == 'NKA') {
              console.log('NKA');
              $('#executor_id').empty();                      
              $.get('/eprop/getExecutor/1/' + market, function(data){
                          //success data
                  console.log(data);
                  console.log('/eprop/getExecutor/' + data + '/' + market);
                  $.each(data, function(index,areaObj){
                      $('#executor_id').append('<option value="'+areaObj.person_id +'">'+areaObj.name+'</option>');
                  })
                })
              
          }
        });

        $("#divisi_id").on('change', function() {
          var divisi_id  = $("#divisi_id").val();
          console.log(divisi_id);
            console.log('NKA');
            $('#target').empty();                      
            $.get('/eprop/target/' + divisi_id, function(data){
                          //success data
                  console.log(data);
                  console.log('/eprop/target/' + divisi_id);
                  $.each(data, function(index,areaObj){
                      $('#target').append('<option value="'+areaObj.target_name +'">'+areaObj.target_name+'</option>');
                  })
                })
        });

        $("#branch_id").on('change', function() {
            var data =$("#branch_id").val();
            var market = $("#market_type_id").val();

            if (market == 'NKA') {
                $('#executor_id').empty(); 
            } else {  
                            console.log(data);
                         
                                $('#executor_id').empty();
                            $.each($("#branch_id").val(), function( index, value ) {
                                    
                            $.get('/eprop/getExecutor/' + value + '/' + market, function(data){
                                        //success data
                                console.log(data);
                                console.log('/eprop/getExecutor/' + data + '/' + market);
                                $.each(data, function(index,areaObj){
                                    $('#executor_id').append('<option value="'+areaObj.person_id +'">'+areaObj.name+'</option>');
                                })
                              })
                            })
                        }                        
          }); 

        $("#brand_id").on('change', function() {
            var data =$("#brand_id").val();
            console.log(data);
         
            $('#varian_id').empty();
            console.log('value');
            $.each($("#brand_id").val(), function( index, value ) {
                    
            $.get('/eprop/getVarian/' + value, function(data){
                        //success data
                console.log(data);
                console.log('/eprop/getVarian/' + data);
                $.each(data, function(index,areaObj){
                    console.log(value);
                    $('#varian_id').append('<option value="'+areaObj.id +'">'+areaObj.produk_name+'</option>');
                })
              })
            })
          }); 

                //com
               /* $('select[name="company_id"]').on('change', function() {        
                      var company_id = $(this).val();
                      console.log(company_id);
                      if(company_id) {
                      console.log(company_id);  
                      $.ajax({
                        url: '/eprop/getBrand/'+company_id,
                        type: "GET",
                        dataType: "json",
                        success:function(data) {
                        console.log('success');
                        console.log(data);
                        $('select[id="brand_id"]').empty();
                        $.each(data, function(key, value) {
                          console.log(value);
                        $('select[id="brand_id"]').append('<option value="'+ value +'">'+ key +'</option>');
                    });
                  }
                    });
                  }else{
                    console.log('no row return');
                    $('select[id="company_id"]').empty();
                  }
                });*/        


  });

</script>

@endpush
