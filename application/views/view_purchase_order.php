<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    
    <title><? echo $title; ?></title>
    
    <?php 
    include('view_includes.php');
    ?>
        
    <!-- Javascript -->
    <script type="text/javascript">     
        // CHECK SESSION
        function checkSession(){
            $.ajax({
                url: "<?php echo base_url('systema/check_session');?>",
                type: "POST",
                success: function(Data){
                        eval(Data);
                    }                
               });  
            setTimeout("checkSession()",10000); 
        } 
        
        function buyerSelect(){
            if($('#dropdown_buyer').val() == 'select'){
                bootbox.alert("Please select buyer!", function() {
                    $('#dropdown_buyer').css("border","red solid 1px");
                });    
                return false;
            }
            else{
                $('#dropdown_buyer').css("border","lightgray solid 1px");  
                createHeader($('#dropdown_buyer').val());  
            }
        }
        
        function createHeader(value){
            // START AVOID CLOSING THE MODAL
            $('#create_header').modal({
                backdrop: 'static',
                keyboard: false
            }); 
            // END AVOID CLOSING THE MODAL
            $.ajax({
                url: "<?php echo base_url('process_purchase_order/get_order_no');?>",
                type: "POST",
                data: "post_buyer="+value,
                success: function(data){  
                    order_no = data.length; 
                    order_no_add_zero = $("#text_order_no").val('00000000000000000000'+data);
                    order_no_display = order_no_add_zero.val().slice(order_no);
                    
                    $('#text_buyer').val(value);
                    $('#text_order_no').val(order_no_display);
                    $('#disp_order_no').html(order_no_display);
                    $("#text_branch_id").val('');  
                    $("#text_branch_name").val('');                                              
                    $("#text_branch_no").val(''); 
                    $("#text_owner").val('');                                         
                    $("#disp_branch_no").html(''); 
                    $("#disp_owner").html('');  
                         
                    $('#create_header').modal('show'); 
                    $('#saving').click( function (e) {
                        e.stopImmediatePropagation();
                        if($('#text_branch_name').val().length == 0){
                            bootbox.alert("Please search Branch Name!", function() {  
                                $('#text_branch_name').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#text_branch_name').css("border","gray solid 1px");    
                        }
                        if($('#text_branch_no').val().length == 0){
                            bootbox.alert("Please search Branch Name!", function() {  
                                $('#text_branch_name').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#text_branch_name').css("border","gray solid 1px");    
                        }
                        if($('#text_owner').val().length == 0){
                            bootbox.alert("Please search Branch Name!", function() {  
                                $('#text_branch_name').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#text_branch_name').css("border","gray solid 1px");    
                        }
                         
                        $.ajax({
                            url: "<?php echo base_url('process_purchase_order/saving_header');?>",
                            type: "POST",
                            data: $('#purchase_header_form').serialize(),
                            success: function(){
                                bootbox.alert("Header successfully saved!", function() {  
                                    $('#create_header').modal('hide'); 
                                    createDetails(order_no_display);
                                });  
                            }         
                        });   
                    });
                }       
            });   
        }   
        
        function createDetails(order_no_display){
            // START AVOID CLOSING THE MODAL
            $('#create_details').modal({
                backdrop: 'static',
                keyboard: false
            });
            // END AVOID CLOSING THE MODAL
            $('#text_order_no_detail').val(order_no_display);
            $('#disp_order_no_detail').html(order_no_display);
                    
            $('#create_details').modal('show');
            $('#saving').click( function (e) {
                e.stopImmediatePropagation();
                if($('#text_branch_name').val().length == 0){
                    bootbox.alert("Please search Branch Name!", function() {  
                        $('#text_branch_name').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_branch_name').css("border","gray solid 1px");    
                }
                if($('#text_branch_no').val().length == 0){
                    bootbox.alert("Please search Branch Name!", function() {  
                        $('#text_branch_name').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_branch_name').css("border","gray solid 1px");    
                }
                if($('#text_owner').val().length == 0){
                    bootbox.alert("Please search Branch Name!", function() {  
                        $('#text_branch_name').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_branch_name').css("border","gray solid 1px");    
                }
                 
                $.ajax({
                    url: "<?php echo base_url('process_purchase_order/saving_header');?>",
                    type: "POST",
                    data: $('#purchase_header_form').serialize(),
                    success: function(){ 
                        createDetails(value_display); 
                    }         
                });   
            });
            //$('#editUser').modal('hide');  
            //bootbox.alert("Item successfully edited!", function() {
            //    $('#btn_edit').attr('disabled','disabled');
            //    $('#btn_delete').attr('disabled','disabled'); 
            //    document.location.reload();
            //});
        }
        
        // CLEAR TEXTFIELD AUTO COMPLETE
        function clearTextfield(){
            $("#text_branch_name").val('');
            $("#text_branch_id").val('');                                               
            $("#text_branch_no").val(''); 
            $("#text_owner").val('');       
        }    
        
        // TEXTFIELD AUTO COMPLETE
        $(function(){
            $("#text_branch_name").autocomplete({
                source: "<?php echo base_url('process_purchase_order/search_branch_name');?>",
                minLength: 1,
                select: function(event, ui) {
                    var content = ui.item.id;
                    $("#text_branch_id").val(content);  
                    var content2 = ui.item.label; 
                    $("#text_branch_name").val(content2);                                              
                    var content3 = ui.item.label2;
                    $("#text_branch_no").val(content3); 
                    $("#disp_branch_no").html(content3); 
                    var content4 = ui.item.label3; 
                    $("#text_owner").val(content4);
                    $("#disp_owner").html(content4);
                }
            });   
        });
    </script>   
                                                        
    </head>

    <body onload=" checkSession(); startTime();">
  
    <div class="header_bg">
    </div>
    <div class="header_logo">
    </div>
    
    <?php 
    include('view_menu.php');
    ?>
    
    <div class="header_bg_down">
    </div>
    
    <?php 
    if($this->session->userdata('userName') == 'masturiano'){
        $view_password = "data-visible-in-selection=\"true\"";    
    }
    else{
        $view_password = "data-visible-in-selection=\"false\"";    
    }
    ?>
    
    <div id="container">  
        <div align="center">
            <table border="0" align="center" width = "30%">
                <tr>
                    <td width="10%"> Buyer </td>
                    <td width="1%"> : </td>
                    <td> <?php echo form_dropdown('dropdown_buyer', $price_option, 'dropdown_buyer', 'class="form-control" id="dropdown_buyer"'); ?></td>
                    <td width="1%">  </td>
                    <td> <?php echo form_button($button,'Click Me','onclick="buyerSelect();"')?> </td>
                </tr>
            </table>
        </div>
    </div>    
    
    <br/><br/>
    <div class="footer_bg">
        <div align="center">
        <?php 
        include('view_copy_right.php');
        ?>
        </div> 
        
        <div class="modal fade" id="create_header" tabindex="-1" role="dialog" aria-labelledby="create_header" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Create Header</h4>
              </div>
              <div class="modal-body">
                <form id="purchase_header_form" method="POST">
                    <?php echo form_input($text_buyer,''); ?>
                    <?php echo form_input($text_branch_id,''); ?>
                    <table id="table_purchase_header" border="0" class="table table-condensed table-striped">
                        <tr>
                            <td class="table_label"><b>Branch Name</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <?php echo form_input($text_branch_name,'Click Me','onclick="clearTextfield();"'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Order No.</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_order_no"></div>
                                <?php echo form_input($text_order_no,''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Branch No.</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_branch_no"></div>
                                <?php echo form_input($text_branch_no,''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Owner</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_owner"></div>
                                <?php echo form_input($text_owner,''); ?>
                            </td>
                        </tr>
                    </table>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" id="save_close" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="saving" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </div> 
        
        <div class="modal fade" id="create_details" tabindex="-1" role="dialog" aria-labelledby="create_details" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Create Details</h4>
              </div>
              <div class="modal-body">
                <form id="purchase_detail_form" method="POST">
                    <table id="table_purchase_detail" border="0" class="table table-condensed table-striped">
                        <tr>
                            <td class="table_label"><b>Order No.</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_order_no_detail"></div>
                                <?php echo form_input($text_order_no_detail,''); ?>
                            </td>
                        </tr>
                    </table>
                </form>
              <div class="modal-footer">
                <button type="button" id="save_close" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="saving" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </div>   
    </div> 

    </body>
</html>