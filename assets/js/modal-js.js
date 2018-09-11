
                $(document).ready(function() {
                $ (".js-example-basic-multiple").select2({
                   placeholder: "Select Additional services"
                 
                });
                $(".js-example-basic-single").select2({
                    placeholder:"Select Advert Type"

                });
                });
               
               
                
                    $("#ads_select").change(function(){
                        var ads_id = $(this).val()
                        var program_id = $("#program_id").val()
                        $(".info-box").load("end_user_program_parse.php",{"get_ads_description":1,"ads_type_id":ads_id,"program_id":program_id},function(data){	
                            $("#total_price").html( $("#total_price1").html())	
                        });
                       
                    });
                
                    $(".info-button").click(function(e){
                        $(".info-box").toggle()
                    });

                
                    $("input[type=checkbox]").change(function(){
                       if($("input[type=checkbox]").is(":checked") == true){
                        var ads_id = $("ads_id").val()
                        $("#sel2-div").css("display","inline")
                        // $("#sel2").load("end_user_program_parse.php",{"additional_serve":1},function(data){

                        // });

                    } else{
                        $('#sel2').val(null).trigger('change');
                        $("#sel2-div").css("display","none")

                    }
                })

                $("#sel2").on("change", function (e) {
                        //send the value to php
                     if($("#sel2").val().length == 0){
                        $("#total_price").html( $("#total_price1").html())
                     }else{
                        $.post("end_user_program_parse.php",{"additional_serve_price":1,"additional_services":$("#sel2").val()},function(data){
                            if($("#total_price1").length == 0){
                              $("#total_price").html(data)
                            }else{
                              console.log("testing the fk ")
                                data = parseFloat(data)
                                total_price = parseFloat($("#total_price1").html())
                                grand_total = data + total_price;
                                
                              $("#total_price").html( grand_total)
                            }
                           
                        }) 
                     }
                       
                    
                });
                