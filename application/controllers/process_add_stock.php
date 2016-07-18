<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Process_add_stock extends CI_Controller {
    
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
    
    #########################( ADD STOCK )#########################
    
    public function display_stock(){  
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODULE NAME
        $data['module_name']  = "Add Stock";   

        # MODEL
        $this->load->model('get_process_add_stock'); 
         
        $data['result_add_stock'] = $this->get_process_add_stock->get_add_stock(); 
        
        # VIEW
        $this->load->view('view_add_stock',$data); 
    }  
    
    public function add_stock(){  
        # MODEL
        $this->load->model('get_process_add_stock'); 
        $result_stock_details = $this->get_process_add_stock->get_add_stock_details($this->input->post('post_id'));
        if ($result_stock_details->num_rows() > 0){
           $row = $result_stock_details->row();
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
                // WITHOUT DOT
                $("#txt_no_item").keydown(function (e) {
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
        
        <form id="add_stock_form" method="POST">
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
                    <td class="table_label"><b>Description</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->description; ?>
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Packaging</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->packaging; ?>
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Unit Price</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->unit_price; ?>
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>No. of Item</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <?php echo $row->no_of_items; ?>
                    </td>
                </tr> 
                <tr>
                    <td class="table_label"><b>Additional No. of Item</b></td>
                    <td class="table_colon"><b>:</b></td>
                    <td class="table_data">
                        <input type="text" class="form-control" name="txt_no_item" id="txt_no_item" style="height:30px; width: 400px;">  
                    </td>
                </tr>
            </table>    
        </form>
        <?php
    }   
    
    public function adding_stock(){  
        # MODEL
        $this->load->model('get_process_add_stock');

        # GET CURRENT DATE
        $result_current_date_time = $this->get_process_add_stock->get_server_current_date_time();
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        # GET STOCK COUNT BASE ON SELECTED ITEM
        $result_item_details = $this->get_process_add_stock->get_add_stock_details($this->input->post('post_id'));
         
        if ($result_item_details->num_rows() > 0){
           $row_stock_count = $result_item_details->row();
        }
        else{
           $row_stock_count = "0"; 
        }
        
        # ADD ITEM COUNT AND ADDITIONAL STOCK
        $new_stock_count = (int)$row_stock_count->no_of_items + (int)$this->input->post('txt_no_item');
        
        $edit_item = array(
            "no_of_items" => $new_stock_count,
            "date_update" => $row->current_date_time
        );
        $this->get_process_add_stock->edit_item_id($edit_item,$this->input->post('txt_item_id'));
        echo "Item stock has been edited";
    }
}