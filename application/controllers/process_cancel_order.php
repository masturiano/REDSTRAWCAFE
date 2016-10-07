<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Process_cancel_order extends CI_Controller {
    
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
    
    #########################( CANCEL ORDER )#########################
    
    public function display_cancel(){  
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODULE NAME
        $data['module_name']  = "Cancel Order";   

        # MODEL
        //$this->load->model('get_process_add_stock'); 
         
        //$data['result_add_stock'] = $this->get_process_add_stock->get_add_stock(); 
        
        # FORM CANCEL ORDER
        $data['text_order_no'] = array(
            'name' => 'text_order_no',
            'id' => 'text_order_no',
            'maxlength' => '20',
            'size' => '20',                              
            'style' => 'width:100%',
            'class' => 'form-control'
        ); 
        
        $data['button'] = array(
            'name' => 'button_cancel',
            'id' => 'button_cancel',
            'value' => 'true',
            'type' => 'reset',
            'content' => 'Cancel',
            'class' => 'form-control',
        );
        
        # VIEW
        $this->load->view('view_cancel_order',$data); 
    }  
    
    function check_order_no(){
        # MODEL
        $this->load->model('get_process_cancel_order'); 
        
        $result_order_no_header = $this->get_process_cancel_order->check_order_no($this->input->post('post_order_no'));   
        
        if ($result_order_no_header->num_rows() > 0){
           echo "1";
        }
        else{
           echo "0";
        } 
    }
    
    function cancel_order_no(){ 
        # MODEL
        $this->load->model('get_process_cancel_order');   
        
        # GET CURRENT DATE
        $result_current_date_time = $this->get_process_cancel_order->get_server_current_date_time();   
        if ($result_current_date_time->num_rows() > 0){
           $row = $result_current_date_time->row();
        }
        else{
           $row = "0"; 
        } 
        
        # UPDATE TABLE ITEMS QUANTITY PER ITEM
        $result_item_qty_order_no = $this->get_process_cancel_order->get_item_quantity_order_no($this->input->post('post_order_no'));
        foreach($result_item_qty_order_no as $row_item_qty_order_no){
            //$row_item_qty_order_no->item_id;    
            //$row_item_qty_order_no->input_no_of_items;   
            $result_item_qty = $this->get_process_cancel_order->get_item_quantity($row_item_qty_order_no->item_id); 
            if ($result_item_qty->num_rows() > 0)
            {
                $row_final_qty = $result_item_qty->row(); 
                $final_qty = $row_item_qty_order_no->input_no_of_items + $row_final_qty->no_of_items;  
                $edit_item_qty = array(
                    "no_of_items" => $final_qty,
                    "date_update" => $row->current_date_time
                );
                if($this->get_process_cancel_order->edit_item_quantity($edit_item_qty,$row_item_qty_order_no->item_id)){
                    if($this->get_process_cancel_order->cancelDetailsOrderNo($this->input->post('post_order_no'),$row_item_qty_order_no->item_id)){
                        $this->get_process_cancel_order->deleteDetailsOrderNo($this->input->post('post_order_no'),$row_item_qty_order_no->item_id);
                        #nag loop so nagloop din ang pag delete
                    } 
                }
            }
        }
        if($this->get_process_cancel_order->cancelHeaderOrderNo($this->input->post('post_order_no'))){
            $this->get_process_cancel_order->deleteHeaderOrderNo($this->input->post('post_order_no'));
        }
    }
}