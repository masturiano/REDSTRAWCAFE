<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Systema extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        
        # LIBRARY
        $this->load->library('form_validation');
        $this->load->library('pagination'); 
        $this->load->library('table');
        
        # HELPER
        $this->load->helper('url');
        $this->load->helper('path');  
        $this->load->helper('form');      
    }
    
    public function check_session(){
        # CHECK SESSION                 
        if($this->session->userdata('userName') == ""){
            $this->session->sess_destroy();    
            echo "location.href='".base_url()."'";    
        }    
    }
    
    #########################( SYSTEM )#########################
    
    public function user_maintenance(){  
        # TITLE
        $data['title']  = "SAWT"; 
        
        # MODULE NAME
        $data['module_name']  = "User Maintenance System";   
        
        # MODEL
        $this->load->model('get_system'); 
         
        $data['result_user_maintenance'] = $this->get_system->get_user_maintenance(); 
        
        # VIEW
        $this->load->view('view_user_maintenance',$data);  
    }     
    
    public function add_user(){  
        # MODEL
        $this->load->model('get_system');
        
        # GET GROUP CODE
        $result_group_code = $this->get_system->get_user_group(); 
        # GET USER LEVEL
        //$result_user_level = $this->get_system->get_user_level(); 
        # GET STORE DETAILS
        //$result_store_details = $this->get_system->get_store_details(); 
        
        ?>
        
        <form data-toggle="validator" role="form" id="add_form" method="POST">
            <table id="table_add" border="0" class="table table-condensed table-striped">
                <tr>
                    <td class="table_label"><b>Full name</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_full_name" id="txt_full_name"
                        style="height:30px; width: 400px;" required>  
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>User name</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_user_name" id="txt_user_name"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>        
                <tr>
                    <td class="table_label"><b>Group Name</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <select name="cmb_group_code" id="cmb_group_code"
                        style="height:30px; width: 400px;">   
                            <option value="0">SELECT</option>  
                            <?php foreach($result_group_code as $row){ ?>
                            <option value="<?=$row->group_code;?>"><?=$row->group_name;?></option>
                            <? } ?>
                        </select>
                    </td>
                </tr> 
            </table>    
        </form>
        <?php

    } 
    
    public function check_user(){
        # MODEL
        $this->load->model('get_system');    
        
        $result_existing_user =  $this->get_system->check_username($this->input->post('txt_user_name')); 
        
        if ($result_existing_user->num_rows() > 0){
           echo "1";
        }
        else{
           echo "0"; 
        } 
    }
    
    public function adding_user(){  
        # MODEL
        $this->load->model('get_system'); 
        
        # GET CURRENT DATE
        $result_current_date_time = $this->get_system->get_server_current_date_time();   
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $new_user = array(
            "group_code" => $this->input->post('cmb_group_code'),
            "full_name" => $this->input->post('txt_full_name'), 
            "user_name" => $this->input->post('txt_user_name'),
            "user_pass" => base64_encode($this->input->post('txt_user_name')),
            "user_stat" => 'A',
            "date_enter" => $row->current_date_time,
            "date_update" => null,
            "ip_address" => '',
            "log" => ''
        );
        $this->get_system->add_new_user($new_user);
        echo "New user has been added";
    }
    
    public function delete_user(){  
        ?>
        Are you sure you want to delete user id <?php echo $this->input->post('post_id');?>? 
        <?php
    }
    
    public function deleting_user(){  
        # MODEL
        $this->load->model('get_system');
        $this->get_system->delete_user_id($_POST['post_id']); 
    }
    
    public function edit_user(){  
        # MODEL
        $this->load->model('get_system'); 
        $result_user_details = $this->get_system->get_user_details($this->input->post('post_id'));
         
        if ($result_user_details->num_rows() > 0){
           $row = $result_user_details->row();
        }
        else{
           $row = "0"; 
        }
             
        # GET GROUP CODE
        $result_group_code = $this->get_system->get_user_group(); 
        # GET GROUP DEPARTMENT SELECTED
        $result_group_code_selected = $this->get_system->get_user_group_selected($row->user_id); 
        if ($result_group_code_selected->num_rows() > 0){
           $row_group_code_selected = $result_group_code_selected->row();
        }
        else{
           $row_group_code_selected = "0"; 
        } 
            
        # GET USER STATUS
        $result_user_status = $this->get_system->get_user_status(); 
        # GET USER STATUS SELECTED
        $result_user_status_selected = $this->get_system->get_user_status_selected($row->user_id); 
        if ($result_user_status_selected->num_rows() > 0){
           $row_user_status_selected = $result_user_status_selected->row();
        }
        ?>
        <form id="edit_form" method="POST">
            <table id="table_edit" border="0" class="table table-condensed table-striped">
                <tr>
                    <td class="table_label"><b>User id</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->user_id; ?>
                        <input type="hidden" class="form-control" name="txt_user_id" id="txt_user_id" value="<?php echo $row->user_id; ?>"> 
                    </td>
                </tr>    
                <tr>
                    <td class="table_label"><b>User name</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->user_name; ?>
                        <input type="hidden" class="form-control" name="txt_user_name" id="txt_user_name" value="<?php echo $row->user_name; ?>">
                    </td>
                </tr>    
                <tr>
                    <td class="table_label"><b>Full name</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_full_name" id="txt_full_name" placeholder="<?php echo $row->full_name; ?>" value="<?php echo $row->full_name; ?>" 
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>  
                <tr>
                    <td class="table_label"><b>Group code</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <select name="cmd_group_code" id="cmd_group_code"
                        style="height:30px; width: 400px;">          
                            <option value="<?=$row_group_code_selected->group_code;?>"><?=$row_group_code_selected->group_name;?></option>
                            <?php foreach($result_group_code as $row){ ?>
                            <option value="<?=$row->group_code;?>"><?=$row->group_name;?></option>
                            <? } ?>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>User Status</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <select name="cmd_user_status" id="cmd_user_status"
                        style="height:30px; width: 400px;">    
                            <option value="<?=$row_user_status_selected->user_stat;?>"><?=$row_user_status_selected->user_stat_description;?></option>   
                            <?php foreach($result_user_status as $row){ ?>
                            <option value="<?=$row->user_stat;?>"><?=$row->user_stat_description;?></option>
                            <? } ?>
                        </select>
                    </td>
                </tr>   
            </table>    
        </form>
        <?php
    }
    
    public function editing_user(){  
        # MODEL
        $this->load->model('get_system');

        # GET CURRENT DATE
        $result_current_date_time = $this->get_system->get_server_current_date_time();
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $edit_user = array(
            "full_name" => $this->input->post('txt_full_name'), 
            //"user_name" => $this->input->post('txt_user_name'),
            //"store_code" => $this->input->post('cmd_store_code'),
            "group_code" => $this->input->post('cmd_group_code'),
            //"user_level" => $this->input->post('cmd_user_level'),
            //"oracle_user_name" => $this->input->post('txt_ora_user_name'),
            //"user_pass" => base64_encode($this->input->post('txt_user_name')),
            //"pages" => '',
            //"date_enter" => $row->current_date_time,
            "date_update" => $row->current_date_time,
            "user_stat" => $this->input->post('cmd_user_status')
            //"ip_address" => '',
            //"log" => ''
        );
        $this->get_system->edit_user_id($edit_user,$this->input->post('txt_user_id'));
        echo "User has been edited";
    }
    
    #########################( ORACLE DATA )#########################
    
    public function oracle_data(){  
        # TITLE
        $data['title']  = "SAWT"; 
        
        # MODULE NAME
        $data['module_name']  = "Oracle Data System";   
        
        # MODEL
        $this->load->model('get_system'); 
         
        $data['result_user_maintenance'] = $this->get_system->get_user_maintenance(); 
        
        # VIEW
        $this->load->view('view_oracle_data',$data);  
    } 
    
    public function copy_oracle_data(){  
        # MODEL
        $this->load->model('get_system');
        
        $this->get_system->get_oracle_data($this->input->post('txt_date_from'),$this->input->post('txt_date_to'));
        echo "Oracle data successfully copied!";
    }  
    
    #########################( CHANGE PASSWORD )#########################
    
    public function change_password(){  
        # TITLE
        $data['title']  = "SAWT"; 
        
        # MODULE NAME
        $data['module_name']  = "Change Password System";   
        
        # MODEL
        $this->load->model('get_system'); 
         
        $data['result_user_maintenance'] = $this->get_system->get_user_maintenance(); 
        
        # VIEW
        $this->load->view('view_change_password',$data);  
    }    
    
    public function editing_user_password(){  
        # MODEL
        $this->load->model('get_system');

        # GET CURRENT DATE
        $result_current_date_time = $this->get_system->get_server_current_date_time();
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $edit_user = array(
            "user_pass" => base64_encode($this->input->post('txt_password')),
            "date_update" => $row->current_date_time
        );
        $this->get_system->edit_user_password($edit_user,$this->input->post('txt_username'));
        echo "User has been edited";
    }
    
    #########################( ITEM MAINTENANCE )#########################   
    
    public function item_maintenance(){  
        # TITLE
        $data['title']  = "SAWT"; 
        
        # MODULE NAME
        $data['module_name']  = "Item Maintenance System";   
        
        # MODEL
        $this->load->model('get_system'); 
         
        $data['result_item_maintenance'] = $this->get_system->get_item_maintenance(); 
        
        # VIEW
        $this->load->view('view_item_maintenance',$data);  
    }     
    
    public function add_item(){  
        # MODEL
        $this->load->model('get_system');
        
        # GET GROUP CODE
        $result_group_code = $this->get_system->get_item_group();         
        ?> 
        <!-- Jquery UI -->
        <link href="<? echo base_url('includes/jquery/development-bundle/themes/base/jquery.ui.all.css'); ?>" rel="stylesheet"> 
        <!-- Jquery Minified Javascript -->
        <script src="<? echo base_url('includes/jquery/js/jquery-ui-1.8.23.custom.min.js'); ?>"></script>
        <!-- My Javascript -->
        <script src="<? echo base_url('includes/my_javascript.js'); ?>"></script>
        
        <script type="text/javascript">
            // ALLOW NUMERIC ONLY ON TEXTFIELD
            $(function(){
                $("#txt_unit_price,#txt_rel_price,#txt_fran_price").keydown(function (e) {
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
                // WITHOUT DOT
                $("#txt_no_item,#txt_lower_limit").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
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
        
        <form data-toggle="validator" role="form" id="add_form" method="POST">
            <table id="table_add" border="0" class="table table-condensed table-striped">
                <tr>
                    <td class="table_label"><b>Group Name</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <select name="cmb_group_code" id="cmb_group_code"
                        style="height:30px; width: 400px;">   
                            <option value="0">SELECT</option>  
                            <?php foreach($result_group_code as $row){ ?>
                            <option value="<?=$row->group_code;?>"><?=$row->group_name;?></option>
                            <? } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>Description</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_description" id="txt_description"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>        
                <tr>
                    <td class="table_label"><b>Packaging</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_packaging" id="txt_packaging"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Unit Price</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_unit_price" id="txt_unit_price"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Rel Price</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_rel_price" id="txt_rel_price"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Fran Price</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_fran_price" id="txt_fran_price"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>No. of Item</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_no_item" id="txt_no_item"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>  
                <tr>
                    <td class="table_label"><b>Lower Limit</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_lower_limit" id="txt_lower_limit"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>
            </table>    
        </form>
        <?php
    } 
    
    public function adding_item(){  
        # MODEL
        $this->load->model('get_system'); 
        
        # GET CURRENT DATE
        $result_current_date_time = $this->get_system->get_server_current_date_time();   
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $new_item = array(
            "group_code" => $this->input->post('cmb_group_code'),
            "description" => $this->input->post('txt_description'), 
            "packaging" => $this->input->post('txt_packaging'),
            "unit_price" => $this->input->post('txt_unit_price'),
            "rel_price" => $this->input->post('txt_rel_price'),
            "fran_price" => $this->input->post('txt_fran_price'),
            "no_of_items" => $this->input->post('txt_no_item'),
            "lower_limit" => $this->input->post('txt_lower_limit'),
            "date_enter" => $row->current_date_time,
            "date_update" => null
        );
        $this->get_system->add_new_item($new_item);
        echo "New item has been added";
    }
    
    public function edit_item(){  
        # MODEL
        $this->load->model('get_system'); 
        $result_item_details = $this->get_system->get_item_details($this->input->post('post_id'));
         
        if ($result_item_details->num_rows() > 0){
           $row = $result_item_details->row();
        }
        else{
           $row = "0"; 
        }
             
        # GET GROUP CODE
        $result_group_code = $this->get_system->get_item_group(); 
        # GET GROUP ITEM SELECTED
        $result_group_code_selected = $this->get_system->get_item_group_selected($row->item_id); 
        if ($result_group_code_selected->num_rows() > 0){
           $row_group_code_selected = $result_group_code_selected->row();
        }
        else{
           $row_group_code_selected = "0"; 
        } 
        ?>
        
        <!-- Jquery UI -->
        <link href="<? echo base_url('includes/jquery/development-bundle/themes/base/jquery.ui.all.css'); ?>" rel="stylesheet"> 
        <!-- Jquery Minified Javascript -->
        <script src="<? echo base_url('includes/jquery/js/jquery-ui-1.8.23.custom.min.js'); ?>"></script>
        <!-- My Javascript -->
        <script src="<? echo base_url('includes/my_javascript.js'); ?>"></script>
        
        <script type="text/javascript">
            // ALLOW NUMERIC ONLY ON TEXTFIELD
            $(function(){
                $("#txt_unit_price,#txt_rel_price,#txt_fran_price").keydown(function (e) {
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
                // WITHOUT DOT
                $("#txt_no_item,#txt_lower_limit").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
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
        
        <form id="edit_form" method="POST">
            <table id="table_edit" border="0" class="table table-condensed table-striped">
                <tr>
                    <td class="table_label"><b>Item id</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->item_id; ?>
                        <input type="hidden" class="form-control" name="txt_item_id" id="txt_item_id" value="<?php echo $row->item_id; ?>"> 
                    </td>
                </tr>  
                <tr>
                    <td class="table_label"><b>Group name</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <select name="cmb_group_code" id="cmb_group_code"
                        style="height:30px; width: 400px;">          
                            <option value="<?=$row_group_code_selected->group_code;?>"><?=$row_group_code_selected->group_name;?></option>
                            <?php foreach($result_group_code as $row_group){ ?>
                            <option value="<?=$row_group->group_code;?>"><?=$row_group->group_name;?></option>
                            <? } ?>
                        </select>
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Description</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_description" id="txt_description" placeholder="<?php echo $row->description; ?>" value="<?php echo $row->description; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Packaging</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_packaging" id="txt_packaging" placeholder="<?php echo $row->packaging; ?>" value="<?php echo $row->packaging; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Unit Price</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_unit_price" id="txt_unit_price" placeholder="<?php echo $row->unit_price; ?>" value="<?php echo $row->unit_price; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Rel Price</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_rel_price" id="txt_rel_price" placeholder="<?php echo $row->rel_price; ?>" value="<?php echo $row->rel_price; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Fran Price</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_fran_price" id="txt_fran_price" placeholder="<?php echo $row->fran_price; ?>" value="<?php echo $row->fran_price; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>No. of Item</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_no_item" id="txt_no_item" placeholder="<?php echo $row->no_of_items; ?>" value="<?php echo $row->no_of_items; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Lower Limit</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_lower_limit" id="txt_lower_limit" placeholder="<?php echo $row->lower_limit; ?>" value="<?php echo $row->lower_limit; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr> 
            </table>    
        </form>
        <?php
    }
    
    public function editing_item(){  
        # MODEL
        $this->load->model('get_system');

        # GET CURRENT DATE
        $result_current_date_time = $this->get_system->get_server_current_date_time();
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $edit_item = array(
            "group_code" => $this->input->post('cmb_group_code'),
            "description" => $this->input->post('txt_description'), 
            "packaging" => $this->input->post('txt_packaging'),
            "unit_price" => $this->input->post('txt_unit_price'),
            "rel_price" => $this->input->post('txt_rel_price'),
            "fran_price" => $this->input->post('txt_fran_price'),
            "no_of_items" => $this->input->post('txt_no_item'),
            "lower_limit" => $this->input->post('txt_lower_limit'),
            "date_update" => $row->current_date_time
        );
        $this->get_system->edit_item_id($edit_item,$this->input->post('txt_item_id'));
        echo "Item has been edited";
    }
    
    public function delete_item(){  
        ?>
        Are you sure you want to delete item id <?php echo $this->input->post('post_id');?>? 
        <?php
    }
    
    public function deleting_item(){  
        # MODEL
        $this->load->model('get_system');
        $this->get_system->delete_item_id($_POST['post_id']); 
    }
    
    #########################( BRANCH MAINTENANCE )#########################   
    
    public function branch_maintenance(){  
        # TITLE
        $data['title']  = "REDSTRAWCAFE"; 
        
        # MODULE NAME
        $data['module_name']  = "Branch Maintenance System";   
        
        # MODEL
        $this->load->model('get_system'); 
         
        $data['result_branch_maintenance'] = $this->get_system->get_branch_maintenance(); 
        
        # VIEW
        $this->load->view('view_branch_maintenance',$data);  
    }  
    
    public function add_branch(){  
        # MODEL
        $this->load->model('get_system');       
        ?> 
        <!-- Jquery UI -->
        <link href="<? echo base_url('includes/jquery/development-bundle/themes/base/jquery.ui.all.css'); ?>" rel="stylesheet"> 
        <!-- Jquery Minified Javascript -->
        <script src="<? echo base_url('includes/jquery/js/jquery-ui-1.8.23.custom.min.js'); ?>"></script>
        <!-- My Javascript -->
        <script src="<? echo base_url('includes/my_javascript.js'); ?>"></script>
        
        <script type="text/javascript">
            // ALLOW NUMERIC ONLY ON TEXTFIELD
            $(function(){
                // WITHOUT DOT
                $("#txt_mobile_no,#txt_tel_no").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
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
        
        <form data-toggle="validator" role="form" id="add_form" method="POST">
            <table id="table_add" border="0" class="table table-condensed table-striped">
                <tr>
                    <td class="table_label"><b>Branch id</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->branch_id; ?>
                        <input type="hidden" class="form-control" name="txt_branch_id" id="txt_branch_id" value="<?php echo $row->branch_id; ?>"> 
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>Branch No.</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_branch_no" id="txt_branch_no"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>        
                <tr>
                    <td class="table_label"><b>Branch Name</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_branch_name" id="txt_branch_name"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Address</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_address" id="txt_address"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Owner</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_owner" id="txt_owner"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Mobile No.</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_mobile_no" id="txt_mobile_no"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>  
                <tr>
                    <td class="table_label"><b>Tel No.</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_tel_no" id="txt_tel_no"
                        style="height:30px; width: 400px;">  
                    </td>
                </tr>
            </table>    
        </form>
        <?php
    } 
    
    public function adding_branch(){  
        # MODEL
        $this->load->model('get_system'); 
        
        # GET CURRENT DATE
        $result_current_date_time = $this->get_system->get_server_current_date_time();   
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $new_branch = array(
            "branch_no" => $this->input->post('txt_branch_no'),
            "branch_name" => $this->input->post('txt_branch_name'), 
            "address" => $this->input->post('txt_address'),
            "owner" => $this->input->post('txt_owner'),
            "mobile_no" => $this->input->post('txt_mobile_no'),
            "tel_no" => $this->input->post('txt_tel_no'),
            "date_enter" => $row->current_date_time,
            "date_update" => null
        );
        $this->get_system->add_new_branch($new_branch);
        echo "New branch has been added";
    }
    
    public function edit_branch(){  
        # MODEL
        $this->load->model('get_system'); 
        $result_branch_details = $this->get_system->get_branch_details($this->input->post('post_id'));
         
        if ($result_branch_details->num_rows() > 0){
           $row = $result_branch_details->row();
        }
        else{
           $row = "0"; 
        }
        ?>
        
        <!-- Jquery UI -->
        <link href="<? echo base_url('includes/jquery/development-bundle/themes/base/jquery.ui.all.css'); ?>" rel="stylesheet"> 
        <!-- Jquery Minified Javascript -->
        <script src="<? echo base_url('includes/jquery/js/jquery-ui-1.8.23.custom.min.js'); ?>"></script>
        <!-- My Javascript -->
        <script src="<? echo base_url('includes/my_javascript.js'); ?>"></script>
        
        <script type="text/javascript">
            // ALLOW NUMERIC ONLY ON TEXTFIELD
            $(function(){
                $("#txt_unit_price,#txt_rel_price,#txt_fran_price").keydown(function (e) {
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
                // WITHOUT DOT
                $("#txt_no_item,#txt_lower_limit").keydown(function (e) {
                    // Allow: backspace, delete, tab, escape, enter and .
                    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110]) !== -1 ||
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
        
        <form id="edit_form" method="POST">
            <table id="table_edit" border="0" class="table table-condensed table-striped">
                <tr>
                    <td class="table_label"><b>Branch id</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->branch_id; ?>
                        <input type="hidden" class="form-control" name="txt_branch_id" id="txt_branch_id" value="<?php echo $row->branch_id; ?>"> 
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Branch No.</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_branch_no" id="txt_branch_no" placeholder="<?php echo $row->branch_no; ?>" value="<?php echo $row->branch_no; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>Branch Name.</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_branch_name" id="txt_branch_name" placeholder="<?php echo $row->branch_name; ?>" value="<?php echo $row->branch_name; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>Address</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_address" id="txt_address" placeholder="<?php echo $row->address; ?>" value="<?php echo $row->address; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>Owner</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_owner" id="txt_owner" placeholder="<?php echo $row->owner; ?>" value="<?php echo $row->owner; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>Mobile No.</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_mobile_no" id="txt_mobile_no" placeholder="<?php echo $row->mobile_no; ?>" value="<?php echo $row->mobile_no; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr>
                <tr>
                    <td class="table_label"><b>Tel No.</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_tel_no" id="txt_tel_no" placeholder="<?php echo $row->tel_no; ?>" value="<?php echo $row->tel_no; ?>" style="height:30px; width: 400px;">  
                    </td>
                </tr>
            </table>    
        </form>
        <?php
    }
    
    public function editing_branch(){  
        # MODEL
        $this->load->model('get_system');

        # GET CURRENT DATE
        $result_current_date_time = $this->get_system->get_server_current_date_time();
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $edit_branch = array(
            "branch_no" => $this->input->post('txt_branch_no'),
            "branch_name" => $this->input->post('txt_branch_name'), 
            "address" => $this->input->post('txt_address'),
            "owner" => $this->input->post('txt_owner'),
            "mobile_no" => $this->input->post('txt_mobile_no'),
            "tel_no" => $this->input->post('txt_tel_no'),
            "date_update" => $row->current_date_time
        );
        $this->get_system->edit_item_id($edit_branch,$this->input->post('txt_branch_id'));
        echo "Branch has been edited";
    }
    
    public function delete_branch(){  
        ?>
        Are you sure you want to delete item id <?php echo $this->input->post('post_id');?>? 
        <?php
    }
    
    public function deleting_branch(){  
        # MODEL
        $this->load->model('get_system');
        $this->get_system->delete_item_id($_POST['post_id']); 
    }
}