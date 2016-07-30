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
            $.ajax({
                url: "<?php echo base_url('process_purchase_order/get_order_no');?>",
                type: "POST",
                data: "post_buyer="+value,
                success: function(data){  
                    value = data.length; 
                    value_add_zero = $("#text_order_no").val('00000000000000000000'+data);
                    value_display = value_add_zero.val().slice(value);
                    $('#text_order_no').val(value_display);
                    //$('#create_header .modal-body').html(data);
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
                         
                        $.ajax({
                            url: "<?php echo base_url('process_add_stock/adding_stock');?>",
                            type: "POST",
                            data: $('#add_stock_form').serialize()+"&post_id="+value,
                            success: function(){
                                $('#editUser').modal('hide');  
                                bootbox.alert("Item successfully edited!", function() {
                                    $('#btn_edit').attr('disabled','disabled');
                                    $('#btn_delete').attr('disabled','disabled'); 
                                    document.location.reload();
                                });
                            }         
                        });   
                    });
                }       
            });   
        }       
        
        // TEXTFIELD AUTO COMPLETE
        $(function(){
            $("#text_branch_name").autocomplete({
                source: "<?php echo base_url('process_purchase_order/search_branch_name');?>",
                minLength: 1,
                select: function(event, ui) {    
                    var content = ui.item.id;
                    $("#txt_tax_payer_id").val(content); 
                    var content2 = ui.item.label2; 
                    $("#txt_bir_reg_name").val(content2);
                    // CHECK IF TIN NO EXIST IN DB
                    $.ajax({
                        url: "<?php echo base_url('process/check_tin_number');?>",
                        type: "POST",
                        data: "post_tin_no="+content,
                        success: function(data){
                            if(data == 1){   
                                $('#txt_bir_reg_name').attr('readonly','readonly');  
                            } 
                            else{
                                $('#txt_bir_reg_name').removeAttr('readonly'); 
                            }   
                        }         
                    }); 
                    
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
                    <table id="table_purchase_header" border="0" class="table table-condensed table-striped">
                        <tr>
                            <td class="table_label"><b>Branch Name</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <?php echo form_input($text_branch_name); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Order No.</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <?php echo form_input($text_order_no); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Branch No.</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <?php echo form_input($text_branch_no); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="table_label"><b>Owner</b></td>
                            <td class="table_colon"><b>:</b></td>
                            <td class="table_data">
                                <?php echo form_input($text_branch_no); ?>
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
    </div> 

    </body>
</html>