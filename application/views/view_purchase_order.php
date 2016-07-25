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
                    alert(data);
                    return false;
                    $('#create_header .modal-body').html(data);
                    $('#create_header').modal('show');
                    $('#saving').click( function (e) {
                        e.stopImmediatePropagation();
                        
                        if($('#txt_no_item').val().length == 0){
                            bootbox.alert("Please input No. of Item!", function() {  
                                $('#txt_no_item').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_no_item').css("border","gray solid 1px");    
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
        
        
        
        // BOOT GRID
        // Refer to http://jquery-bootgrid.com/Documentation for methods, events and settings
        // load gird on page\e load... 
        
        // ADD STOCK
        function add_stock(value){ 
            $.ajax({
                url: "<?php echo base_url('process_add_stock/add_stock');?>",
                type: "POST",
                data: "post_id="+value,
                success: function(data){  
                    $('#create_header .modal-body').html(data);
                    $('#create_header').modal('show');
                    $('#saving').click( function (e) {
                        e.stopImmediatePropagation();
                        
                        if($('#txt_no_item').val().length == 0){
                            bootbox.alert("Please input No. of Item!", function() {  
                                $('#txt_no_item').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_no_item').css("border","gray solid 1px");    
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
        };  
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