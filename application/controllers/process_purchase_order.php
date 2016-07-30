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
        $data['text_branch_name'] = array(
            'name'        => 'text_branch_name',
            'id'          => 'text_branch_name',
            'maxlength'   => '50',
            'size'        => '50',                              
            'style'       => 'width:100%',
            'class' => 'form-control'
        );  
        
        $data['text_order_no'] = array(
            'name'        => 'text_order_no',
            'id'          => 'text_order_no',
            'maxlength'   => '10',
            'size'        => '10',                              
            'style'       => 'width:100%',
            'class' => 'form-control',
            'disabled'   => 'disabled'
        );
        
        $data['text_branch_no'] = array(
            'name'        => 'text_branch_no',
            'id'          => 'text_branch_no',
            'maxlength'   => '50',
            'size'        => '50',                              
            'style'       => 'width:100%',
            'class' => 'form-control',
            'disabled'   => 'disabled'
        );
        
        $data['text_owner'] = array(
            'name'        => 'text_owner',
            'id'          => 'text_owner',
            'maxlength'   => '50',
            'size'        => '50',                              
            'style'       => 'width:100%',
            'class' => 'form-control',
            'disabled'   => 'disabled'
        );
        
        # VIEW
        $this->load->view('view_purchase_order',$data); 
    }  
    
    public function get_order_no(){  
        # MODEL
        $this->load->model('get_process_purchase_order'); 
        
        # GET TRANSMITTAL NUMBER
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
                        "id"=>$val->TIN,
                        "label"=>$val->TIN." - ".$val->BIR_REG_NAME,
                        "label2"=>$val->BIR_REG_NAME,
                        "value" => strip_tags($val->TIN));    
                }
        echo json_encode($arrResult);    
    }
}