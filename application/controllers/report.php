<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Report extends CI_Controller {
    
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
    
    #########################( AVAILABLE STOCKS )#########################
    
    public function available_stocks_report(){  
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODULE NAME
        $data['module_name']  = "Available Stocks Report";   
        
        # MODEL
        $this->load->model('get_report'); 
        
        $data['get_item_group_name'] = $this->get_report->get_item_group_name();

        # VIEW
        $this->load->view('view_available_stocks_report',$data);  
    }
    
    function generate_available_stocks_report_xls()
    {   
        # TITLE
        $data['title']  = "Available Stocks Report"; 
        
        # MODEL
        $this->load->model('get_report');
                                                                                                   
        echo "window.open('".base_url()."report_available_stocks_report_xls/print_excel/".$this->input->post('cmb_item_group')."');";                  
    } 
    
    #########################( SUMMARIZED REPORT )#########################
    
    public function summarized_report(){  
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODULE NAME
        $data['module_name']  = "Summarized Report";   
        
        # MODEL
        $this->load->model('get_report'); 

        # VIEW
        $this->load->view('view_summarized_report',$data);  
    }
    
    function generate_summarized_report_xls()
    {   
        # TITLE
        $data['title']  = "Summarized Report"; 
        
        # MODEL
        $this->load->model('get_report');
                                                                                                   
        echo "window.open('".base_url()."report_summarized_report_xls/print_excel/".$this->input->post('txt_date_from')."/".$this->input->post('txt_date_to')."');";                  
    } 
    
    #########################( DETAILED REPORT )#########################
    
    public function detailed_report(){  
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODULE NAME
        $data['module_name']  = "Detailed Report";   
        
        # MODEL
        $this->load->model('get_report'); 
        
        $data['get_branch_name'] = $this->get_report->get_branch_name();

        # VIEW
        $this->load->view('view_detailed_report',$data);  
    }
    
    function generate_detailed_report_xls()
    {   
        # TITLE
        $data['title']  = "Detailed Report"; 
        
        # MODEL
        $this->load->model('get_report');
                                                                                                   
        echo "window.open('".base_url()."report_detailed_report_xls/print_excel/".$this->input->post('txt_date_from')."/".$this->input->post('txt_date_to')."/".$this->input->post('cmb_branch_group')."');";                  
    } 
    
    #########################( CANCELLED ORDER REPORT )#########################
    
    public function cancelled_order_report(){  
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODULE NAME
        $data['module_name']  = "Cancelled Order Report";   
        
        # MODEL
        $this->load->model('get_report'); 
        
        $data['get_branch_name'] = $this->get_report->get_branch_name();

        # VIEW
        $this->load->view('view_cancelled_order_report',$data);  
    }
    
    function generate_cancelled_order_report_xls()
    {   
        # TITLE
        $data['title']  = "Cancelled Order Report"; 
        
        # MODEL
        $this->load->model('get_report');
                                                                                                   
        echo "window.open('".base_url()."report_cancelled_order_report_xls/print_excel/".$this->input->post('txt_date_from')."/".$this->input->post('txt_date_to')."/".$this->input->post('cmb_branch_group')."');";                  
    } 
    
    #########################( REPRINT ORDER REPORT )#########################
    
    public function reprint_order_report(){  
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODULE NAME
        $data['module_name']  = "Reprint Order Report";   
        
        # MODEL
        $this->load->model('get_report'); 
        
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
            'name' => 'button_reprint',
            'id' => 'button_reprint',
            'value' => 'true',
            'type' => 'reset',
            'content' => 'Reprint',
            'class' => 'form-control',
        );

        # VIEW
        $this->load->view('view_reprint_order_report',$data);  
    }
    
    function check_order_no(){
        # MODEL
        $this->load->model('get_report'); 
        
        $result_order_no_header = $this->get_report->check_order_no($this->input->post('post_order_no'));   
        
        if ($result_order_no_header->num_rows() > 0){
           echo "1";
        }
        else{
           echo "0";
        } 
    }
    
    function generate_purchase_order_pdf()
    {   
        # TITLE
        $data['title']  = "REDSTRAW"; 
        
        # MODEL
        $this->load->model('get_process_purchase_order');
        
        $order_no = $this->input->post('post_order_no');
        
        echo "window.open('".base_url()."process_purchase_order_pdf/index/".str_replace(',','-',$order_no)."');";                  
    }  
    
}