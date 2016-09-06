<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Process_purchase_order extends CI_Controller {
    
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
        $this->load->helper('file');     
    }
    
    public function check_session(){
        # CHECK SESSION                 
        if($this->session->userdata('userName') == ""){
            $this->session->sess_destroy();    
            echo "location.href='".base_url()."'";    
        }    
    }
    
    #########################( PURCHASE ORDER )#########################
    
    public function display_order_form(){
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODULE NAME
        $data['module_name']  = "Purchase Order";   

        # MODEL
        $this->load->model('get_process_purchase_order');  
         
        # FORM BUYER
        $data['price_option'] = array(
            'select'  => 'Select',
            'rel_price'  => 'Relative',
            'fran_price'    => 'Franchise',
        );
        
        $data['button'] = array(
            'name' => 'button_buyer',
            'id' => 'button_buyer',
            'value' => 'true',
            'type' => 'reset',
            'content' => 'Create',
            'class' => 'form-control',
        );
        
        # FORM PURCHASE ORDER HEADER
        $data['text_buyer'] = array(
            'name' => 'text_buyer',
            'id' => 'text_buyer',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control'
        );
        
        $data['text_branch_id'] = array(
            'name' => 'text_branch_id',
            'id' => 'text_branch_id',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control'
        ); 
        
        $data['text_branch_name'] = array(
            'name' => 'text_branch_name',
            'id' => 'text_branch_name',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'class' => 'form-control'
        );  
        
        $data['text_order_no'] = array(
            'name' => 'text_order_no',
            'id' => 'text_order_no',
            'maxlength' => '10',
            'size' => '10',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );
        
        $data['text_branch_no'] = array(
            'name' => 'text_branch_no',
            'id' => 'text_branch_no',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );
        
        $data['text_owner'] = array(
            'name' => 'text_owner',
            'id' => 'text_owner',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );
        
        # FORM PURCHASE ORDER DETAIL
        $data['text_item_id'] = array(
            'name' => 'text_item_id',
            'id' => 'text_item_id',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );  
        
        $data['text_order_no_detail'] = array(
            'name' => 'text_order_no_detail',
            'id' => 'text_order_no_detail',
            'maxlength' => '10',
            'size' => '10',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );
        
        $data['text_item_description'] = array(
            'name' => 'text_item_description',
            'id' => 'text_item_description',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'class' => 'form-control'
        ); 
        
        $data['text_unit_price'] = array(
            'name' => 'text_unit_price',
            'id' => 'text_unit_price',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );  
        
        $data['text_buyer_price'] = array(
            'name' => 'text_buyer_price',
            'id' => 'text_buyer_price',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );  
        
        $data['text_group_code'] = array(
            'name' => 'text_group_code',
            'id' => 'text_group_code',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );  
        
        $data['text_group_name'] = array(
            'name' => 'text_group_name',
            'id' => 'text_group_name',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );    
        
        $data['text_packaging'] = array(
            'name' => 'text_packaging',
            'id' => 'text_packaging',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );  
        
        $data['text_no_of_items'] = array(
            'name' => 'text_no_of_items',
            'id' => 'text_no_of_items',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'type' => 'hidden',
            'class' => 'form-control',
        );  
        
        $data['text_input_no_of_items'] = array(
            'name' => 'text_input_no_of_items',
            'id' => 'text_input_no_of_items',
            'maxlength' => '50',
            'size' => '50',                              
            'style' => 'width:100%',
            'class' => 'form-control'
        );       
        
 
        # GET ORDER DETAILS
        //$data['result_order_detail'] = $this->get_process_purchase_order->get_item_details($this->input->post('post_purchase_order_no'));
        
        # VIEW
        $this->load->view('view_purchase_order',$data); 
    }  
    
    public function get_order_no(){  
        # MODEL
        $this->load->model('get_process_purchase_order'); 
        
        # GET ORDER NUMBER
        $result_order_number = $this->get_process_purchase_order->get_order_number();

        if ($result_order_number->num_rows() > 0){
            $row_order_no = $result_order_number->row();
            echo $row_order_no->last_order_no;
        }
        else{
           echo $row_order_no = "0"; 
        }
    } 
    
    public function search_branch_name(){ 
        # MODEL
        $this->load->model('get_process_purchase_order');

        $arrResult = array();
            $arrRegName = $this->get_process_purchase_order->get_branch_name($_GET['term']);
                foreach($arrRegName as $val){
                    $arrResult[] = array(
                        "id"=>$val->branch_id,
                        "label"=>$val->branch_name,
                        "label2"=>$val->branch_no,
                        "label3"=>$val->owner,
                    );
                }
        echo json_encode($arrResult);    
    }
    
    function saving_header(){
        # MODEL
        $this->load->model('get_process_purchase_order'); 
        
        # GET CURRENT DATE
        $result_current_date_time = $this->get_process_purchase_order->get_server_current_date_time();   
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $new_header = array(
            "buyer" => $this->input->post('text_buyer'),
            "purchase_order_no" => $this->input->post('text_order_no'),
            "branch_id" => $this->input->post('text_branch_id'), 
            "branch_no" => $this->input->post('text_branch_no'), 
            "branch_name" => $this->input->post('text_branch_name'), 
            "owner" => $this->input->post('text_owner'), 
            "amount" => 0.00, 
            "date_enter" => $row->current_date_time,
            "date_update" => null
        );
        $this->get_process_purchase_order->add_new_header($new_header);
        echo "New header has been save";
    }
    
    public function search_item_description(){ 
        # MODEL
        $this->load->model('get_process_purchase_order');

        $arrResult = array();
            $arrRegName = $this->get_process_purchase_order->get_item_description($_GET['term'],$_GET['order_no']);
                foreach($arrRegName as $val){
                    $arrResult[] = array(
                        "id"=>$val->item_id,
                        "label"=>$val->description,
                        "label2"=>$val->unit_price,
                        "label3"=>$val->buyer_price,
                        "label4"=>$val->group_code,
                        "label5"=>$val->group_name,
                        "label6"=>$val->packaging,
                        "label7"=>$val->no_of_items,
                    );
                }
        echo json_encode($arrResult);    
    }
    
    function get_order_no_details_item(){
        # MODEL
        $this->load->model('get_process_purchase_order'); 
        
        # GET CURRENT DATE
        $result_order_no_details_item = $this->get_process_purchase_order->check_order_no_details_item($this->input->post('text_order_no_detail'),$this->input->post('text_item_id'));   
        
        if ($result_order_no_details_item->num_rows() > 0){
           echo "1";
        }
        else{
           echo "0";
        } 
    }
    
    function saving_details(){
        # MODEL
        $this->load->model('get_process_purchase_order'); 
        
        # GET CURRENT DATE
        $result_current_date_time = $this->get_process_purchase_order->get_server_current_date_time();   
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        $added_price = $this->input->post('text_input_no_of_items') *  $this->input->post('text_buyer_price');
        $add_details = array(
            "purchase_order_no" => $this->input->post('text_order_no_detail'),
            "item_id" => $this->input->post('text_item_id'),
            "group_code" => $this->input->post('text_group_code'), 
            "no_of_items" => $this->input->post('text_no_of_items'), 
            "unit_price" => $this->input->post('text_unit_price'), 
            "buyer_price" => $this->input->post('text_buyer_price'),
            "input_no_of_items" => $this->input->post('text_input_no_of_items'),  
            "added_price" => $added_price,  
            "date_enter" => $row->current_date_time,
            "date_update" => null
        );
        if($this->get_process_purchase_order->add_new_detail($add_details))
        {
            $result_total_details = $this->get_process_purchase_order->get_total_detail($this->input->post('text_order_no_detail'));
            if ($result_total_details->num_rows() > 0)
            {
                $row_total_details = $result_total_details->row();
                $edit_header = array(
                    "amount" => $row_total_details->total_added_price,
                    "date_update" => $row->current_date_time
                );
               $this->get_process_purchase_order->edit_header_amount($edit_header,$this->input->post('text_order_no_detail'));
            }
            else{
                echo "error";
                $row_total_details = "0"; 
            }
        }
        echo "Details has been save";
    }
    
    function view_details(){
        # MODEL
        $this->load->model('get_process_purchase_order'); 
        
        # GET ORDER NUMBER DETAILS
        $result_order_detail = $this->get_process_purchase_order->get_item_details($this->input->post('post_purchase_order_no'));

        ?>
        <script type="text/javascript">
            $(function()
            {
                function init()
                {
                    $("#div_disp_data").show();
                    $("#grid-data").bootgrid({
                        formatters: {
                            "link": function(column, row)
                            {
                                return "<?php echo base_url('process_purchase_order/view_details');?>" + column.id + ": " + row.id + "</a>";
                            }
                        },
                        rowCount: [10, 50, 75, -1]
                    }).on("selected.rs.jquery.bootgrid", function (e, rows) {
                        var value = $("#grid-data").bootgrid("getSelectedRows");
                        if(value.length == 0){
                            $('#btn_delete').attr('disabled','disabled'); 
                        }
                        else if(value.length == 1){
                            $('#btn_delete').removeAttr('disabled'); 
                        }
                        else{    
                            $('#btn_delete').attr('disabled','disabled'); 
                        }                                                    
                    }).on("deselected.rs.jquery.bootgrid", function (e, rows){
                        var value = $("#grid-data").bootgrid("getSelectedRows");
                        if(value.length == 0){
                            $('#btn_delete').attr('disabled','disabled'); 
                        }
                        else if(value.length == 1){
                            $('#btn_delete').removeAttr('disabled'); 
                        }
                        else{    
                            $('#btn_delete').attr('disabled','disabled'); 
                        }       
                    })  
                }
                
                init();
                
                $("#btn_delete").on("click", function (e)
                {                               
                    e.stopImmediatePropagation();                
                    var value = $("#grid-data").bootgrid("getSelectedRows");
                    // SELECT TABLE ROW         
                    $.ajax({           
                        url: "<?php echo base_url('process_purchase_order/delete_order_no_details_item/');?>",
                        type: "POST",
                        success: function(data){
                            $('#deleteOrderNoItemDetail').modal('show');
                            $('#deleteOrderNoItemDetail .modal-body').html(data); 
                            $('#deleting').click( function (e) {
                                e.stopImmediatePropagation();
                                var value = $("#grid-data").bootgrid("getSelectedRows");
                                $.ajax({
                                    url: "<?php echo base_url('process_purchase_order/deleting_order_no_details_item');?>",
                                    type: "POST",
                                    data: "post_id="+value+"&post_order_no="+"<?=$this->input->post('post_purchase_order_no');?>",
                                    success: function(){
                                        alert(value);
                                        bootbox.alert("Selected item successfully deleted!", function() {
                                        });
                                        $('#deleteOrderNoItemDetail').modal('hide');
                                        $('#btn_delete').attr('disabled','disabled');
                                        data_order_no = "<?=$this->input->post('post_purchase_order_no');?>";
                                        order_no = data_order_no.length; 
                                        order_no_add_zero = $("#text_order_no").val('00000000000000000000'+data_order_no);
                                        order_no_display = order_no_add_zero.val().slice(order_no);
                                        viewDetails(order_no_display);
                                        // Remove it (later)
                                    }         
                                });   
                            });       
                        }         
                    });  
                });    
            });
        </script>
        <?php 
            echo $this->input->post('post_purchase_order_no');
            echo "</br>";
        ?>
        <table id="grid-data" class="table table-condensed table-hover table-striped"
        data-selection="true" 
        data-multi-select="false" 
        data-row-select="true" 
        data-keep-selection="true">
            <thead>
                <tr class="clickable-row"> 
                    <!-- data-column-id="sender" data-column-id="received" -->
                    <th data-column-id="id" data-type="numeric" data-identifier="true" data-order="asc" data-visible="false" >Item Id</th>
                    <th data-column-id="description">Description</th>
                    <th data-column-id="packaging">Packaging</th>
                    <th data-column-id="group_name">Group Name</th>
                    <th data-column-id="buyer_price">Unit Price</th>
                    <th data-column-id="added_price">Added Price</th>
                    <th data-column-id="input_no_of_item">No of items</th>  
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach($result_order_detail as $row){
                ?>
                <tr>
                    <td id="<?php echo $row->item_id; ?>"><?php echo $row->item_id; ?></td>
                    <td id="<?php echo $row->item_id; ?>"><?php echo $row->description; ?></td>
                    <td id="<?php echo $row->item_id; ?>"><?php echo $row->packaging; ?></td> 
                    <td id="<?php echo $row->item_id; ?>"><?php echo $row->group_name; ?></td> 
                    <td id="<?php echo $row->item_id; ?>"><?php echo number_format($row->unit_price,2,".",","); ?></td> 
                    <td id="<?php echo $row->item_id; ?>"><?php echo $row->buyer_price;; ?></td>
                    <td id="<?php echo $row->item_id; ?>"><?php echo $row->added_price; ?></td> 
                    <td id="<?php echo $row->item_id; ?>"><?php echo $row->input_no_of_items; ?></td> 
                </tr>
                <?php
                    }
                ?>
            </tbody>
        </table> 
         <div class="modal-footer">
            <button type="button" id="save_details_close" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" id="btn_delete" class="btn btn-danger" disabled="disabled">Delete</button>
         </div>
        <?php
    }
    
    function delete_order_no_details_item(){
        ?>
        Are you sure you want to delete selected item? 
        <?php    
    }
    
    public function deleting_order_no_details_item(){  
        # MODEL
        $this->load->model('get_process_purchase_order');
        $this->get_process_purchase_order->delete_order_no_details_item($_POST['post_order_no'],$_POST['post_id']); 
    }
}