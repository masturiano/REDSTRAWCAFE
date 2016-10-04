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
        
        function cancelOrderNo(){
            if($('#text_order_no').val().length == 0){
                bootbox.alert("Please input order no.!", function() {
                    $('#text_order_no').css("border","red solid 1px");
                });    
                return false;
            }
            else{
                $('#text_order_no').css("border","lightgray solid 1px");  
                cancellation($('#text_order_no').val());  
            }
        }
        
        function cancellation(text_order_no){
            // START AVOID CLOSING THE MODAL
            $('#modalCancelOrderNo').modal({
                backdrop: 'static',
                keyboard: false
            }); 
            $('#cancelling').click( function (e) {
                e.stopImmediatePropagation();
                $.ajax({
                    url: "<?php echo base_url('process_cancel_order/check_order_no');?>",
                    type: "POST",
                    data: "post_order_no="+text_order_no,
                    success: function(){
                        bootbox.alert("Selected item successfully deleted!", function() {
                        });
                        $('#deleteOrderNoItemDetail').modal('hide');
                        $('#btn_delete').attr('disabled','disabled');
                        data_order_no = "<?=$this->input->post('post_purchase_order_no');?>";
                        order_no = data_order_no.length; 
                        order_no_add_zero = $("#text_order_no").val('00000000000000000000'+data_order_no);
                        order_no_display = order_no_add_zero.val().slice(order_no);
                        viewDetails(order_no_display);
                    }         
                });   
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
                                    createDetails(order_no_display);
                                });  
                            }         
                        });   
                    });
                }       
            });   
        }   
        
        // ALLOW NUMERIC ONLY ON TEXTFIELD
        $(function(){
            $("#text_order_no").keydown(function (e) {
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
            <table border="0" align="center" width = "35%">
                <tr>
                    <td width="19%"> Order No. </td>
                    <td width="1%"> : </td>
                    <td width="59%"> <?php echo form_input($text_order_no,''); ?></td>
                    <td width="1%">  </td>
                    <td width="20%"> <?php echo form_button($button,'Click Me','onclick="cancelOrderNo();"')?> </td>
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
    </div> 
    
    <div class="modal fade" id="modalCancelOrderNo" tabindex="-1" role="dialog" aria-labelledby="cancelOrderNo" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-body">
            Are you sure you want to cancel order no. 
          </div>
          <div class="modal-footer">
            <button type="button" id="cancel_close" class="btn btn-default" data-dismiss="modal">No</button>
            <button type="button" id="cancelling" class="btn btn-primary">Yes</button>
          </div>
        </div>
      </div>
    </div>

    </body>
</html>