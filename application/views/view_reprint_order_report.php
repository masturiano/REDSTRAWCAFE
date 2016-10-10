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
        
        function reprintOrderNo(){
            if($('#text_order_no').val().length == 0){
                bootbox.alert("Please input order no.!", function() {
                    $('#text_order_no').css("border","red solid 1px");
                });    
                return false;
            }
            else{
                $('#text_order_no').css("border","lightgray solid 1px"); 
                if($('#text_order_no').val().length < 20){
                    bootbox.alert("Order number must be 20 digits!", function() {
                        $('#text_order_no').css("border","red solid 1px");
                    });    
                    return false;
                }
                else{
                    $('#text_order_no').css("border","lightgray solid 1px");  
                    reprinting($('#text_order_no').val());  
                }  
            }
        }
        
        function reprinting(text_order_no){
            $.ajax({
                url: "<?php echo base_url('report/check_order_no');?>",
                type: "POST",
                data: "post_order_no="+text_order_no,
                success: function(data){
                    if(data == 0){
                        bootbox.alert("Order number not exist!", function() {
                            $('#text_order_no').css("border","red solid 1px");
                        });    
                        return false;    
                    }
                    if(data == 1){
                        // PRINT TRANSMITTAL
                        $.ajax({
                            url: "<?php echo base_url('report/generate_purchase_order_pdf');?>",
                            type: "POST",
                            data: "post_order_no="+text_order_no,
                            success: function(data){
                                eval(data);
                                bootbox.alert("Print order form success!", function() {  
                                    //$('#div_transmit_confirm').modal('hide'); 
                                    document.location.reload();
                                });       
                            }         
                        });   
                    }    
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
            width: 548px;
        }
        body .modalbody{
            /* new custom width */
            width: 550px;
        }
        body .modal-dialog{
            /* new custom width */
            width: 550px;
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
                    <td width="20%"> <?php echo form_button($button,'Click Me','onclick="reprintOrderNo();"')?> </td>
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