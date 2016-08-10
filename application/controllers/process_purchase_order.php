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
        //$this->load->model('get_process_add_stock'); 
         
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
}