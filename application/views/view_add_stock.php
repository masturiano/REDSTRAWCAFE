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
        
        // BOOT GRID
        // Refer to http://jquery-bootgrid.com/Documentation for methods, events and settings
        // load gird on page\e load...
        
        //selection:true,
        //multiSelect: true,
        //rowSelect : true,   
        $(function()
        {
            function init()
            {
                $("#div_disp_data").show();
                $("#grid-data").bootgrid({
                    formatters: {
                        "link": function(column, row)
                        {
                            return "<?php echo base_url('process_add_stock/display_stock');?>" + column.id + ": " + row.id + "</a>";
                        }
                    },
                    rowCount: [10, 50, 75, -1]
                }).on("selected.rs.jquery.bootgrid", function (e, rows) {
                    var value = $("#grid-data").bootgrid("getSelectedRows");
                    if(value.length == 0){
                        $('#btn_add').attr('disabled','disabled'); 
                    }
                    else if(value.length == 1){
                        $('#btn_add').removeAttr('disabled');
                    }
                    else{    
                        $('#btn_add').attr('disabled','disabled'); 
                    }                                                    
                }).on("deselected.rs.jquery.bootgrid", function (e, rows){
                    var value = $("#grid-data").bootgrid("getSelectedRows");
                    if(value.length == 0){
                        $('#btn_add').attr('disabled','disabled'); 
                    }
                    else if(value.length == 1){
                        $('#btn_add').removeAttr('disabled');
                    }
                    else{    
                        $('#btn_add').attr('disabled','disabled'); 
                    }       
                })  
            }
            
            init();    
            
            $("#btn_add").on("click", function ()
            {
                var value = $("#grid-data").bootgrid("getSelectedRows");
                // SELECT TABLE ROW         
                $.ajax({           
                    url: "<?php echo base_url('process_add_stock/display_stock');?>",
                    type: "POST",
                    data: "post_id="+value,
                    success: function(){
                                             
                        $("#div_disp_data").show();  
                        $('#btn_encode').removeAttr('disabled'); 
                        $('#div_encode_sawt').modal('hide');   
                        add_stock(value)     
                    }         
                });  
            });     
        });    
        
        // ADD STOCK
        function add_stock(value){ 
            $.ajax({
                url: "<?php echo base_url('process_add_stock/add_stock');?>",
                type: "POST",
                data: "post_id="+value,
                success: function(data){  
                    $('#editUser .modal-body').html(data);
                    $('#editUser').modal('show');
                    $('#saving').click( function (e) {
                        e.stopImmediatePropagation();
                        
                        if($('#cmb_group_code').val() == 0){
                            bootbox.alert("Please select group code!", function() {  
                                $('#cmb_group_code').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#cmb_group_code').css("border","gray solid 1px");    
                        } 
                       
                        if($('#txt_description').val().length == 0){
                            bootbox.alert("Please input Description!", function() {  
                                $('#txt_description').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_description').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_packaging').val().length == 0){
                            bootbox.alert("Please input Packaging!", function() {  
                                $('#txt_packaging').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_packaging').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_unit_price').val().length == 0){
                            bootbox.alert("Please input Unit Price!", function() {  
                                $('#txt_unit_price').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_unit_price').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_rel_price').val().length == 0){
                            bootbox.alert("Please input Relative Price!", function() {  
                                $('#txt_rel_price').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_rel_price').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_fran_price').val().length == 0){
                            bootbox.alert("Please input Franchise Price!", function() {  
                                $('#txt_fran_price').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_fran_price').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_no_item').val().length == 0){
                            bootbox.alert("Please input No. of Item!", function() {  
                                $('#txt_no_item').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_no_item').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_lower_limit').val().length == 0){
                            bootbox.alert("Please input Lower Limit!", function() {  
                                $('#txt_lower_limit').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_lower_limit').css("border","gray solid 1px");    
                        }
                        
                        if(Math.round($('#txt_unit_price').val() * 100) > Math.round($('#txt_rel_price').val() * 100)){
                            bootbox.alert("Unit Price must be lower than Relative Price!", function() {  
                                $('#txt_rel_price').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_rel_price').css("border","gray solid 1px");    
                        }
                        
                        if(Math.round($('#txt_unit_price').val() * 100) > Math.round($('#txt_fran_price').val() * 100)){
                            bootbox.alert("Unit Price must be lower than Franchise Price!", function() {  
                                $('#txt_fran_price').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_fran_price').css("border","gray solid 1px");    
                        }
                         
                        $.ajax({
                            url: "<?php echo base_url('systema/editing_item');?>",
                            type: "POST",
                            data: $('#edit_form').serialize()+"&post_id="+value,
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
        <div id="div_disp_data" style="display:none;">
            &nbsp;
            <button type="button" id="btn_add" class="btn btn-primary" disabled="disabled">Add</button>
            
            <table id="grid-data" class="table table-condensed table-hover table-striped"
            data-selection="true" 
            data-multi-select="false" 
            data-row-select="true" 
            data-keep-selection="true">
                <thead>
                    <tr class="clickable-row"> 
                        <!-- data-column-id="sender" data-column-id="received" -->
                        <th data-column-id="id" data-type="numeric" data-identifier="true" data-order="asc" >Id</th>
                        <th data-column-id="group_name">Group Name</th>
                        <th data-column-id="description">Description</th>
                        <th data-column-id="packaging">Packaging</th>
                        <th data-column-id="unit_price">Unit Price</th>
                        <th data-column-id="no_of_items">No. of item</th>
                        <th data-column-id="lower_limit">Lower limit</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($result_add_stock as $row){
                    ?>
                    <tr>
                        <td id="<?php echo $row->item_id; ?>"><?php echo $row->item_id; ?></td>
                        <td id="<?php echo $row->item_id; ?>"><?php echo $row->group_name; ?></td>
                        <td id="<?php echo $row->item_id; ?>"><?php echo $row->description; ?></td> 
                        <td id="<?php echo $row->item_id; ?>"><?php echo $row->packaging; ?></td> 
                        <td id="<?php echo $row->item_id; ?>"><?php echo $row->unit_price; ?></td> 
                        <td id="<?php echo $row->item_id; ?>"><?php echo $row->no_of_items; ?></td> 
                        <td id="<?php echo $row->item_id; ?>"><?php echo $row->lower_limit; ?></td> 
                    </tr>
                    <?php
                        }
                    ?>
                </tbody>
            </table> 
        </div>    
        
        <div class="modal fade" id="addUser" tabindex="-1" role="dialog" aria-labelledby="addUser" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add Item</h4>
              </div>
              <div class="modal-body">
              </div>
              <div class="modal-footer">
                <button type="button" id="save_close" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="adding" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </div>
        
        <div class="modal fade" id="deleteUser" tabindex="-1" role="dialog" aria-labelledby="deleteUser" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-body">
              </div>
              <div class="modal-footer">
                <button type="button" id="delete_close" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="deleting" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>
    
        <div class="modal fade" id="editUser" tabindex="-1" role="dialog" aria-labelledby="editUser" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Add Stock</h4>
              </div>
              <div class="modal-body">
              </div>
              <div class="modal-footer">
                <button type="button" id="save_close" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="saving" class="btn btn-primary">Save changes</button>
              </div>
            </div>
          </div>
        </div>
    
    </div>    
    
    <br/><br/>
    <div class="footer_bg">
        <div align="center">
        &copy; 2015 Puregold Price Club Inc. IT-HO Mydel-Ar A. Asturiano All Rights Reserved
        </div>    
    </div> 

    </body>
</html>