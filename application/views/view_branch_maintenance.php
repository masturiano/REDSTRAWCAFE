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
                            return "<?php echo base_url('systema/branch_maintenance');?>" + column.id + ": " + row.id + "</a>";
                        }
                    },
                    rowCount: [10, 50, 75, -1]
                }).on("selected.rs.jquery.bootgrid", function (e, rows) {
                    var value = $("#grid-data").bootgrid("getSelectedRows");
                    if(value.length == 0){
                        $('#btn_edit').attr('disabled','disabled'); 
                        $('#btn_delete').attr('disabled','disabled'); 
                    }
                    else if(value.length == 1){
                        $('#btn_edit').removeAttr('disabled');
                        $('#btn_delete').removeAttr('disabled'); 
                    }
                    else{    
                        $('#btn_edit').attr('disabled','disabled'); 
                        $('#btn_delete').attr('disabled','disabled'); 
                    }                                                    
                }).on("deselected.rs.jquery.bootgrid", function (e, rows){
                    var value = $("#grid-data").bootgrid("getSelectedRows");
                    if(value.length == 0){
                        $('#btn_edit').attr('disabled','disabled'); 
                        $('#btn_delete').attr('disabled','disabled'); 
                    }
                    else if(value.length == 1){
                        $('#btn_edit').removeAttr('disabled');
                        $('#btn_delete').removeAttr('disabled'); 
                    }
                    else{    
                        $('#btn_edit').attr('disabled','disabled'); 
                        $('#btn_delete').attr('disabled','disabled'); 
                    }       
                })  
            }
            
            init();    
            
            $("#btn_add").on("click", function ()
            {
                var value = $("#grid-data").bootgrid("getSelectedRows");
                // SELECT TABLE ROW         
                $.ajax({           
                    url: "<?php echo base_url('systema/branch_maintenance');?>",
                    type: "POST",
                    data: "post_id="+value,
                    success: function(){
                                             
                        $("#div_disp_data").show();  
                        $('#btn_encode').removeAttr('disabled'); 
                        $('#div_encode_sawt').modal('hide');   
                        add_branch(value)     
                    }         
                });  
            }); 
            
            $("#btn_edit").on("click", function ()
            {
                var value = $("#grid-data").bootgrid("getSelectedRows");
                // SELECT TABLE ROW         
                $.ajax({           
                    url: "<?php echo base_url('systema/branch_maintenance');?>",
                    type: "POST",
                    data: "post_id="+value,
                    success: function(){
                                             
                        $("#div_disp_data").show();  
                        $('#btn_encode').removeAttr('disabled'); 
                        $('#div_encode_sawt').modal('hide');   
                        edit_branch(value)     
                    }         
                });  
            });
            
            $("#btn_delete").on("click", function ()
            {
                var value = $("#grid-data").bootgrid("getSelectedRows");
                // SELECT TABLE ROW         
                $.ajax({           
                    url: "<?php echo base_url('systema/branch_maintenance');?>",
                    type: "POST",
                    data: "post_id="+value,
                    success: function(){
                                             
                        $("#div_disp_data").show();  
                        $('#btn_encode').removeAttr('disabled'); 
                        $('#div_encode_sawt').modal('hide');   
                        delete_user(value)     
                    }         
                });  
            });
            
        });    
        
        // ADD NEW USER
        function add_branch(value){ 
            $.ajax({
                url: "<?php echo base_url('systema/add_branch');?>",
                type: "POST",
                success: function(data){   
                   
                    $('#addUser .modal-body').html(data);
                    $('#addUser').modal('show');
                    $('#adding').click( function (e) {  
                        e.stopImmediatePropagation();
                        
                        if($('#txt_branch_no').val().length == 0){
                            bootbox.alert("Please input Branch Number!", function() {  
                                $('#txt_branch_no').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_branch_no').css("border","gray solid 1px");    
                        } 
                       
                        if($('#txt_branch_name').val().length == 0){
                            bootbox.alert("Please input Branch Name!", function() {  
                                $('#txt_branch_name').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_branch_name').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_address').val().length == 0){
                            bootbox.alert("Please input Address!", function() {  
                                $('#txt_address').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_address').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_owner').val().length == 0){
                            bootbox.alert("Please input Owner!", function() {  
                                $('#txt_owner').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_owner').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_mobile_no').val().length == 0){
                            bootbox.alert("Please input Mobile Number!", function() {  
                                $('#txt_mobile_no').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_mobile_no').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_tel_no').val().length == 0){
                            bootbox.alert("Please input Tel Number!", function() {  
                                $('#txt_tel_no').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_tel_no').css("border","gray solid 1px");    
                        }
                        
                        $.ajax({
                            url: "<?php echo base_url('systema/check_branch_no');?>",
                            type: "POST",
                            data: $('#add_form').serialize(),
                            success: function(data){ 
                                if(data == 1){
                                    bootbox.alert("Branch number already exist!", function() {
                                        $('#txt_branch_no').css("border","red solid 1px");  
                                    });  
                                    return false;  
                                }
                                else{  
                                    $.ajax({
                                        url: "<?php echo base_url('systema/adding_branch');?>",
                                        type: "POST",
                                        data: $('#add_form').serialize(),
                                        success: function(){ 
                                            $('#addUser').modal('hide');  
                                            bootbox.alert("Branch successfully added!", function() {
                                                $('#btn_edit').attr('disabled','disabled');
                                                $('#btn_delete').attr('disabled','disabled'); 
                                                document.location.reload();
                                            });
                                        }         
                                    }); 
                                }
                            }         
                        });
                    });
                }         
            });   
        };       
    
        // EDIT BRANCH ID
        function edit_branch(value){
            $.ajax({
                url: "<?php echo base_url('systema/edit_branch');?>",
                type: "POST",
                data: "post_id="+value,
                success: function(data){   
                   
                    $('#editUser .modal-body').html(data);
                    $('#editUser').modal('show');
                    $('#saving').click( function (e) {
                        e.stopImmediatePropagation();
                        
                        if($('#txt_branch_no').val().length == 0){
                            bootbox.alert("Please input Branch Name!", function() {  
                                $('#txt_branch_no').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_branch_no').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_branch_name').val().length == 0){
                            bootbox.alert("Please input Branch Name!", function() {  
                                $('#txt_branch_name').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_branch_name').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_address').val().length == 0){
                            bootbox.alert("Please input Address!", function() {  
                                $('#txt_address').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_address').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_owner').val().length == 0){
                            bootbox.alert("Please input Owner!", function() {  
                                $('#txt_owner').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_owner').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_mobile_no').val().length == 0){
                            bootbox.alert("Please input Mobile Number!", function() {  
                                $('#txt_mobile_no').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_mobile_no').css("border","gray solid 1px");    
                        }
                        
                        if($('#txt_tel_no').val().length == 0){
                            bootbox.alert("Please input Tel Number!", function() {  
                                $('#txt_tel_no').css("border","red solid 1px");  
                            }); 
                            return false;
                        } 
                        else{
                            $('#txt_tel_no').css("border","gray solid 1px");    
                        }
                        
                        $.ajax({
                            url: "<?php echo base_url('systema/check_branch_no');?>",
                            type: "POST",
                            data: $('#add_form').serialize(),
                            success: function(data){ 
                                if(data == 1){
                                    bootbox.alert("Branch number already exist!", function() {
                                        $('#txt_branch_no').css("border","red solid 1px");  
                                    });  
                                    return false;  
                                }
                                else{   
                                    $.ajax({
                                        url: "<?php echo base_url('systema/editing_branch');?>",
                                        type: "POST",
                                        data: $('#edit_form').serialize()+"&post_id="+value,
                                        success: function(){
                                            $('#editUser').modal('hide');  
                                            bootbox.alert("Branch successfully edited!", function() {
                                                $('#btn_edit').attr('disabled','disabled');
                                                $('#btn_delete').attr('disabled','disabled'); 
                                                document.location.reload();
                                            });
                                        }         
                                    }); 
                                }
                            }         
                        });  
                    });
                }         
            });  
        } 
        
        // DELETE USER ID
        function delete_user(value){             
            $.ajax({
                cache: false,
                url: "<?php echo base_url('systema/delete_branch');?>",
                type: "POST",
                data: "post_id="+value,
                success: function(data){
                                            
                    $('#deleteUser').modal('show');
                    $('#deleteUser .modal-body').html(data); 
                    $('#deleting').click( function (e) {
                        e.stopImmediatePropagation(); 
                        $.ajax({
                            url: "<?php echo base_url('systema/deleting_branch');?>",
                            type: "POST",
                            data: "post_id="+value,
                            success: function(){
                                bootbox.alert("Branch successfully deleted!", function() {
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
            <button type="button" id="btn_add" class="btn btn-primary">Add</button>
            <button type="button" id="btn_edit" class="btn btn-primary" disabled="disabled">Edit</button>
            <button type="button" id="btn_delete" class="btn btn-danger" disabled="disabled">Delete</button>
                            
            <table id="grid-data" class="table table-condensed table-hover table-striped"
            data-selection="true" 
            data-multi-select="false" 
            data-row-select="true" 
            data-keep-selection="true">
                <thead>
                    <tr class="clickable-row"> 
                        <!-- data-column-id="sender" data-column-id="received" -->
                        <th data-column-id="id" data-type="numeric" data-identifier="true" data-order="asc" >Id</th>
                        <th data-column-id="branch_no">Branch No.</th>
                        <th data-column-id="branch_name">Branch Name</th>
                        <th data-column-id="address">Address</th>
                        <th data-column-id="owner">Owner</th>
                        <th data-column-id="mobile_no">Mobile No.</th>
                        <th data-column-id="tel_no">Tel No.</th>
                        <th data-column-id="date_enter">Date / Time Enter</th>
                        <th data-column-id="date_update">Date / Time Update</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($result_branch_maintenance as $row){
                    ?>
                    <tr>
                        <td id="<?php echo $row->branch_id; ?>"><?php echo $row->branch_id; ?></td>
                        <td id="<?php echo $row->branch_id; ?>"><?php echo $row->branch_no; ?></td>
                        <td id="<?php echo $row->branch_id; ?>"><?php echo $row->branch_name; ?></td> 
                        <td id="<?php echo $row->branch_id; ?>"><?php echo $row->address; ?></td> 
                        <td id="<?php echo $row->branch_id; ?>"><?php echo $row->owner; ?></td> 
                        <td id="<?php echo $row->branch_id; ?>"><?php echo $row->mobile_no; ?></td> 
                        <td id="<?php echo $row->branch_id; ?>"><?php echo $row->tel_no; ?></td> 
                        <td id="<?php echo $row->branch_id; ?>"><?php echo date('Y-m-d H:i:s',strtotime($row->date_enter)); ?></td>
                        <?php 
                        $date_update = date('Y-m-d H:i:s',strtotime($row->date_update));
                        $date_update = strpos($date_update,"1970");
                        if($date_update !== FALSE){
                        ?>
                            <td id="<?php echo $row->branch_id; ?>"></td>
                        <?php
                        }
                        else{
                        ?>
                            <td id="<?php echo $row->branch_id; ?>"><?php echo date('Y-m-d H:i:s',strtotime($row->date_update)); ?></td>
                        <?php
                        }
                        ?>
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
                <h4 class="modal-title" id="myModalLabel">Edit Item</h4>
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