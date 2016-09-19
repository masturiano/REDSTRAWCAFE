<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Process_Purchase_Order_Pdf extends CI_Controller {
    function __construct()
    {
        parent::__construct();
        $this->load->library('pdf'); // Load library
        $this->pdf->fontpath = 'font/'; // Specify font folder
        $this->load->model('get_process_purchase_order');
    }
    
    public function index($order_no)
    {
        $arr = $this->get_process_purchase_order->get_purchase_order_no($order_no);
        
        # GET HEADER ORDER NO
        $header_order_no = $this->get_process_purchase_order->get_header_purchase_order_no_pdf($order_no);
        if ($header_order_no->num_rows() > 0)
        {
            $header_order_no_row = $header_order_no->row();
        }
        
        # GET HEADER BRANCH
        $header_branch = $this->get_process_purchase_order->get_header_branch_purchase_order_no_pdf($order_no);
        if ($header_branch->num_rows() > 0)
        {
            $header_branch_row = $header_branch->row();
        }
        
        # GET DETAILS
        $details = $this->get_process_purchase_order->get_item_details($order_no);
        
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        
        # HEADER
        $this->pdf->SetFont('Arial','B',12);
        $this->pdf->Cell(0,4,'ORDER FORM',0,1,'R');
        $this->pdf->Cell(0,4,'RedStraw Cafe',0,1,'L');
        $this->pdf->Cell(0,4,"The frappe that you can't resist...",0,1,'L');
        
        $this->pdf->SetFont('Arial','BI',10);
        $this->pdf->Cell(148,4,'DATE # : ',0,0,'R');
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->Cell(0,4,date('F d, Y',strtotime($header_order_no_row->date_enter)),0,1,'R');
        $this->pdf->SetFont('Arial','BI',10);
        $this->pdf->Cell(148,4,'ORDER # : ',0,0,'R');
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->Cell(0,4,$header_order_no_row->purchase_order_no,0,1,'R');
        $this->pdf->SetFont('Arial','BI',10);
        $this->pdf->Cell(148,4,'CUSTOMER ID : ',0,0,'R');
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->Cell(0,4,$header_order_no_row->branch_no,0,1,'R');
        $this->pdf->Cell(0,4,'',0,1,'C');    
        
        $this->pdf->SetFont('Arial','BU',8);
        $this->pdf->Cell(0,4,'BILL TO',0,1,'L');
        $this->pdf->SetFont('Arial','BI',8);
        $this->pdf->Cell(13,4,'Owner :',0,0,'L');
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->Cell(0,4,$header_branch_row->owner,0,1,'L');
        $this->pdf->SetFont('Arial','BI',8);
        $this->pdf->Cell(25,4,'Company Name :',0,0,'L');
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->Cell(0,4,$header_branch_row->branch_name,0,1,'L');
        $this->pdf->SetFont('Arial','BI',8);
        $this->pdf->Cell(15,4,'Address :',0,0,'L');
        $this->pdf->SetFont('Arial','',8);           
        $this->pdf->Cell(0,4,$header_branch_row->address,0,1,'L');
        $this->pdf->SetFont('Arial','BI',8);
        $this->pdf->Cell(15,4,'Mobile # :',0,0,'L');
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->Cell(0,4,$header_branch_row->mobile_no,0,1,'L');
        $this->pdf->SetFont('Arial','BI',8);
        $this->pdf->Cell(10,4,'Tel # :',0,0,'L');
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->Cell(0,4,$header_branch_row->tel_no,0,1,'L');
        $this->pdf->Cell(0,4,'',0,1,'L');
        
        # BODY 
        // TABLE HEADER
        $this->pdf->SetFont('Arial','B',8); // SET FONT
        $this->pdf->Cell(70,8,'DESCRIPTION','BTLR',0,'L');
        $this->pdf->Cell(30,8,'NO. OF ITEMS','BTLR',0,'L');
        $this->pdf->Cell(30,8,'UNIT PRICE','BTLR',0,'L');
        $this->pdf->Cell(30,8,'AMOUNT','BTLR',1,'L');
            
        $this->pdf->SetFont('Arial','',8); // SET FONT
        // TABLE DETAILS
        
        foreach($details as $details_val) {
            $this->pdf->Cell(70,6,$details_val->description,'BTLR',0,'L');
            $this->pdf->Cell(30,6,$details_val->input_no_of_items,'BTLR',0,'L');
            $this->pdf->Cell(30,6,$details_val->unit_price,'BTLR',0,'L');
            $this->pdf->Cell(30,6,$details_val->added_price,'BTLR',1,'L');
            //$this->pdf->Cell(30,6,date('Y-m-d',strtotime($details_val->RECEIPT_DATE)),'BTLR',0,'C');
            //$this->pdf->Cell(30,6,$details_val->ACCOUNT_NUMBER,'BTLR',0,'L');
            //$this->pdf->Cell(50,6,substr($val->ACCOUNT_NAME,0,30),'BTLR',0,'L');
            //$this->pdf->Cell(15,6,$details_val->COMP_SHORT,'BTLR',0,'L');
            //$this->pdf->Cell(30,6,$details_val->AMOUNT,'BTLR',0,'R');
            //$this->pdf->Cell(20,6,$details_val->TIN,'BTLR',0,'L');
            //$this->pdf->Cell(50,6,substr($details_val->BIR_REG_NAME,0,30),'BTLR',0,'L');
            //$this->pdf->Cell(20,6,$details_val->ATC_CODE,'BTLR',0,'L');
            //$this->pdf->Cell(30,6,$details_val->TAX_BASE,'BTLR',0,'R');
            //$this->pdf->Cell(30,6,date('Y-m-d',strtotime($details_val->GL_DATE_SAWT)),'BTLR',1,'C');
        } 
        
        # FOOTER
        $curr_date = $this->get_process_purchase_order->get_server_current_date_time();
        
        if ($curr_date->num_rows() > 0){
           $current_date = $curr_date->row();
        }
        else{
           $current_date = "0"; 
        }
        
        /*
        $this->pdf->Cell(0,5,'',0,1,'L');
        $this->pdf->SetFont('Arial','',10);
        $this->pdf->Cell(30,5,'Transmittal Number:',0,0,'C');
        $this->pdf->Cell(53,5,$val->TRANSMITTAL_NUMBER,0,1,'C');
        $this->pdf->Cell(25,5,'Transmittal Date:',0,0,'C');
        $this->pdf->Cell(52,5,date('F d, Y h:i:s',strtotime($val->TRANSMITTED_DATE)),0,1,'C');
        $this->pdf->Cell(18,5,'Date printed:',0,0,'C');
        $this->pdf->Cell(52,5,date('F d, Y h:i:s',strtotime($current_date->current_date_time)),0,1,'C');
        $this->pdf->Cell(15,5,'Printed by:',0,0,'C');
        $this->pdf->Cell(100,5,"  ".$this->session->userdata('fullName'),0,1,'L');
        $this->pdf->Cell(0,5,'',0,1,'C');     
        */
        
        # FPDF FUNCTION
        $this->pdf->Output('SAWT_Transmittal.pdf','D');    
        
    }  
}   
?>  