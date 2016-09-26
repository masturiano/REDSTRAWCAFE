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
    
}