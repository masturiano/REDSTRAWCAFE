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
            // CLOSE THE MODAL REFRESH THE PAGE
            $('#save_close').click( function (e) {
                document.location.reload();
            }); 
            // END AVOID CLOSING THE MODAL
            $.ajax({
                url: "<?php echo base_url('process_purchase_order/get_order_no');?>",
                type: "POST",
                data: "post_buyer="+value,
                success: function(data){  
                    order_no_display = '';
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
                    $("#text_delivery_charge").val('');                                         
                    $("#text_previous_bal").val('');                                         
                    $("#disp_branch_no").html(''); 
                    $("#disp_owner").html('');  
                         
                    $('#create_header').modal('show'); 
                    $('#saving_header').click( function (e) {
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
                        if($('#text_delivery_charge').val().length == 0){
                            bootbox.alert("Please input Delivery Charge!", function() {  
                                $('#text_delivery_charge').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#text_delivery_charge').css("border","gray solid 1px");    
                        }
                        if($('#text_previous_bal').val().length == 0){
                            bootbox.alert("Please input Previous Balance!", function() {  
                                $('#text_previous_bal').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#text_previous_bal').css("border","gray solid 1px");    
                        }
                         
                        $.ajax({
                            url: "<?php echo base_url('process_purchase_order/saving_header');?>",
                            type: "POST",
                            data: $('#purchase_header_form').serialize(),
                            success: function(){
                                bootbox.alert("Header successfully saved!", function() {  
                                    $('#create_header').modal('hide'); 
                                    $('#saving_header').attr('disabled','disabled');
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
            // CLOSE THE MODAL REFRESH THE PAGE
            $('#save_details_close').click( function (e) {
                document.location.reload();
            });
            // END AVOID CLOSING THE MODAL
            $('#text_order_no_detail').val(order_no_display);
            $('#disp_order_no_detail').html(order_no_display);
                    
            $('#create_details').modal('show');
            $('#btn_view_details').click( function (e) {
                e.stopImmediatePropagation();
                viewDetails(order_no_display);
            });
            $('#saving_details').click( function (e) {
                e.stopImmediatePropagation();
                if($('#text_item_description').val().length == 0){
                    bootbox.alert("Please search Item Description!", function() {  
                        $('#text_item_description').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_item_description').css("border","gray solid 1px");    
                }
                if($('#text_buyer_price').val().length == 0){
                    bootbox.alert("Please search Item Description!", function() {  
                        $('#text_buyer_price').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_buyer_price').css("border","gray solid 1px");    
                }
                if($('#text_group_name').val().length == 0){
                    bootbox.alert("Please search Item Description!", function() {  
                        $('#text_group_name').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_group_name').css("border","gray solid 1px");    
                }
                if($('#text_packaging').val().length == 0){
                    bootbox.alert("Please search Item Description!", function() {  
                        $('#text_packaging').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_packaging').css("border","gray solid 1px");    
                } 
                if($('#text_no_of_items').val().length == 0){
                    bootbox.alert("Please search Item Description!", function() {  
                        $('#text_no_of_items').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_no_of_items').css("border","gray solid 1px");    
                } 
                if($('#text_input_no_of_items').val().length == 0){
                    bootbox.alert("Please input no. of items!", function() {  
                        $('#text_input_no_of_items').css("border","red solid 1px");  
                    }); 
                    return false;
                } 
                else{
                    $('#text_input_no_of_items').css("border","gray solid 1px");    
                } 
                if(parseInt($('#text_input_no_of_items').val()) > parseInt($('#text_no_of_items').val())){
                    bootbox.alert("Available Items must be greater than no. of items!", function() {  
                        $('#text_input_no_of_items').css("border","red solid 1px");  
                    });                                 
                    return false;
                } 
                else{
                    $('#text_input_no_of_items').css("border","gray solid 1px");    
                } 
                $.ajax({
                    url: "<?php echo base_url('process_purchase_order/get_order_no_details_item');?>",
                    type: "POST",
                    data: $('#purchase_detail_form').serialize(),
                    success: function(data){ 
                        if(data == 1)
                        {
                            bootbox.alert("Item already added! Delete the item first on view details!", function() {  
                                $('#text_item_description').css("border","red solid 1px");  
                            });
                        }
                        else
                        {              
                            $('#saving_details').attr('disabled','disabled');
                           $.ajax({
                                url: "<?php echo base_url('process_purchase_order/saving_details');?>",
                                type: "POST",
                                data: $('#purchase_detail_form').serialize(),
                                success: function(){ 
                                    bootbox.alert("Details successfully saved!", function() {  
                                        $('#saving_details').removeAttr('disabled'); 
                                        clearTextfield();
                                    });    
                                }         
                            });     
                        }
                    }         
                });  
            });
        }
        
        function viewDetails(order_no_display){
            // START AVOID CLOSING THE MODAL
            $('#view_details').modal({
                backdrop: 'static',
                keyboard: false
            }); 
            // END AVOID CLOSING THE MODAL 
            $.ajax({
                url: "<?php echo base_url('process_purchase_order/view_details');?>",
                type: "POST",
                data: "post_purchase_order_no="+order_no_display,
                success: function(data){  
                    //console.log(data);   
                    $('#view_details .modal-body').html(data);
                    $('#view_details').modal('show'); 
                }       
            });   
        }  
        
        // CLEAR TEXTFIELD AUTO COMPLETE
        function clearTextfield(){
            $("#text_branch_name").val('');
            $("#text_branch_id").val('');  
            $("#disp_branch_no").html('');                                             
            $("#text_branch_no").val(''); 
            $("#disp_owner").html('');
            $("#text_owner").val(''); 
            $("#text_delivery_charge").val(''); 
            $("#text_previous_bal").val(''); 
            // DETAILS
            $("#text_item_description").val(''); 
            $("#disp_buyer_price").html('');
            $("#text_buyer_price").val('');
            $("#disp_group_name").html('');
            $("#text_group_name").val('');
            $("#disp_packaging").html('');
            $("#text_packaging").val('');
            $("#disp_no_of_items").html('');
            $("#text_no_of_items").val('');
            $("#text_input_no_of_items").val('');
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
            $("#text_item_description").autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "<?php echo base_url('process_purchase_order/search_item_description/');?>",
                        dataType: "json",
                        data: {
                            term : request.term,
                            order_no : $('#text_order_no_detail').val()
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                minLength: 1,
                select: function(event, ui) {
                    var content = ui.item.id;
                    $("#text_item_id").val(content);  
                    var content2 = ui.item.label; 
                    $("#text_description").val(content2);                                              
                    var content3 = ui.item.label2;
                    $("#text_unit_price").val(content3); 
                    var content4 = ui.item.label3; 
                    $("#text_buyer_price").val(content4);
                    $("#disp_buyer_price").html(content4);
                    var content5 = ui.item.label4; 
                    $("#text_group_code").val(content5);
                    var content6 = ui.item.label5; 
                    $("#text_group_name").val(content6);
                    $("#disp_group_name").html(content6);
                    var content7 = ui.item.label6; 
                    $("#text_packaging").val(content7);
                    $("#disp_packaging").html(content7);
                    var content8 = ui.item.label7; 
                    $("#text_no_of_items").val(content8);
                    $("#disp_no_of_items").html(content8);
                }
            });   
        });
        
        // ALLOW NUMERIC ONLY ON TEXTFIELD
        $(function(){
            $("#text_input_no_of_items,#text_delivery_charge,#text_previous_bal").keydown(function (e) {
                // Allow: backspace, delete, tab, escape, enter and .
                if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                     // Allow: Ctrl+A, Command+A
                    (e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
                     // Allow: home, end, left, right, down, up
                    (e.keyCode >= 35 && e.keyCode <= 40)) {
                         // let it happen, don't do anything
                         return;
                }
                // Ensure that it is a number and stop the keypress
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                    e.preventDefault();
                }
            });
        });
    </script>   
    
    <style type="text/css">
        body .modal-header {
            /* new custom width */
            width: 948px;
        }
        body .modalbody{
            /* new custom width */
            width: 950px;
        }
        body .modal-dialog{
            /* new custom width */
            width: 950px;
        }
        .close {display: none;}
    </style>
                                                        
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
                        <tr>
                            <td class="table_label"><b>Delivery Charge</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_owner"></div>
                                <?php echo form_input($text_delivery_charge,''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Previous Balance</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_owner"></div>
                                <?php echo form_input($text_previous_bal,''); ?>
                            </td>
                        </tr>
                    </table>
                </form>
              </div>
              <div class="modal-footer">
                <button type="button" id="save_close" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="saving_header" class="btn btn-primary">Save</button>
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
                    <?php echo form_input($text_item_id,''); ?>
                    <?php echo form_input($text_unit_price,''); ?>
                    <?php echo form_input($text_group_code,''); ?>
                    <table id="table_purchase_detail" border="0" class="table table-condensed table-striped">
                        <tr>
                            <td class="table_label"><b>Order No.</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_order_no_detail"></div>
                                <?php echo form_input($text_order_no_detail,''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Item Description</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <?php echo form_input($text_item_description,'Click Me','onclick="clearTextfield();"'); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Price</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_buyer_price"></div>
                                <?php echo form_input($text_buyer_price,''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Group Name</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_group_name"></div>
                                <?php echo form_input($text_group_name,''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Packaging</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_packaging"></div>
                                <?php echo form_input($text_packaging,''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Available Items</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <div id="disp_no_of_items"></div>
                                <?php echo form_input($text_no_of_items,''); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Input No. of Items</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <?php echo form_input($text_input_no_of_items,''); ?>
                            </td>
                        </tr>
                    </table>
                </form>
              <div class="modal-footer">
                <button type="button" id="btn_view_details" class="btn btn-default" data-dismiss="modal">View Details</button>   
                <button type="button" id="save_details_close" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="saving_details" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </div>   
    </div> 
    
    <div class="modal fade" id="view_details" tabindex="-1" role="dialog" aria-labelledby="create_details" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">View Details</h4>
              </div>
              <div class="modal-body" style="overflow-y: auto; height: 500px;">
                <div id="div_disp_data" style="display:none;">
                    &nbsp;
                </div>   
            </div>
          </div>
        </div>   
    </div> 
    
    <div class="modal fade" id="deleteOrderNoItemDetail" tabindex="-1" role="dialog" aria-labelledby="deleteUser" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            <form id="purchase_detail_form" method="POST">
                <?php echo form_input($text_order_no_details_item,''); ?>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" id="delete_close" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="button" id="deleting" class="btn btn-primary">Yes</button>
          </div>
        </div>
      </div>
    </div>

    </body>
</html>