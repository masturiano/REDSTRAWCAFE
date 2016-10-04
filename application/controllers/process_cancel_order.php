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
        $this->load->model('get_process_purchase_order'); 
        
        # GET CURRENT DATE
        $result_order_no_details_item = $this->get_process_purchase_order->check_order_no($this->input->post('text_order_no_detail'),$this->input->post('text_item_id'));   
        
        if ($result_order_no_details_item->num_rows() > 0){
           echo "1";
        }
        else{
           echo "0";
        } 
    }
}