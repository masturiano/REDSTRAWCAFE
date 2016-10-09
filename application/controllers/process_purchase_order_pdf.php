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
        
        # GET FOOTER TOTAL INPUT NUMBER OF ITEMS
        $footer_total_input_no_of_items = $this->get_process_purchase_order->get_footer_total_input_no_of_items_order_no_pdf($order_no);
        if ($footer_total_input_no_of_items->num_rows() > 0)
        {
            $footer_total_input_no_of_items_row = $footer_total_input_no_of_items->row();
        }
        
        # GET FOOTER TOTAL BUYER PRICE
        $footer_total_buyer_price = $this->get_process_purchase_order->get_footer_total_buyer_price_order_no_pdf($order_no);
        if ($footer_total_buyer_price->num_rows() > 0)
        {
            $footer_total_buyer_price_row = $footer_total_buyer_price->row();
        }
        
        # GET FOOTER TOTAL ADDED PRICE
        $footer_total_added_price = $this->get_process_purchase_order->get_footer_total_added_price_order_no_pdf($order_no);
        if ($footer_total_added_price->num_rows() > 0)
        {
            $footer_total_added_price_row = $footer_total_added_price->row();
        }
        
        # GET DETAILS
        $details = $this->get_process_purchase_order->get_item_details($order_no);
        
        $this->pdf->AliasNbPages();
        $this->pdf->AddPage();
        
        # HEADER
        $this->pdf->SetFont('Arial','B',12);
        $this->pdf->Cell(0,4,'ORDER FORM',0,1,'R');
        $image_redstraw_logo = "includes/images/redstraw_logo.jpg";
        $this->pdf->Cell(20,35,$this->pdf->Image($image_redstraw_logo,20,20,25),0,1,'R');
        $this->pdf->Cell(0,4,'RedStraw Cafe',0,1,'L');
        $this->pdf->Cell(0,4,"The frappe that you can't resist...",0,1,'L');
        $this->pdf->Cell(0,4,'',0,1,'L');                          
        
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->Cell(100,4,'Unit 2 G/F Padilla Bldg. 2136 CM Recto Ave.',0,1,'L');
        $this->pdf->Cell(100,4,'Zone 040 Brgy. 390 Quiapo Manila',0,1,'L');
        $this->pdf->SetFont('Arial','BI',8);
        $this->pdf->Cell(15,4,'Mobile # :',0,0,'L');
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->Cell(0,4,'09061677482',0,1,'L');
        $this->pdf->SetFont('Arial','BI',8);
        $this->pdf->Cell(10,4,'Tel # :',0,0,'L');
        $this->pdf->SetFont('Arial','',8);
        $this->pdf->Cell(0,4,'2421353',0,1,'L');
        $this->pdf->Cell(0,4,'',0,1,'L');
        
        $this->pdf->SetFont('Arial','BI',10);
        $this->pdf->Cell(148,4,'DATE : ',0,0,'R');
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
        $this->pdf->Cell(104,8,'DESCRIPTION','BTLR',0,'L');
        $this->pdf->Cell(30,8,'NO. OF ITEMS','BTLR',0,'L');
        $this->pdf->Cell(30,8,'UNIT PRICE','BTLR',0,'L');
        $this->pdf->Cell(30,8,'AMOUNT','BTLR',1,'L');
            
        // TABLE DETAILS
        $this->pdf->SetFont('Arial','B',8); // SET FONT
        $this->pdf->Cell(104,6,'INGREDIENTS','BTLR',0,'L');
        $this->pdf->Cell(30,6,' ','BTLR',0,'R');
        $this->pdf->Cell(30,6,' ','BTLR',0,'R');
        $this->pdf->Cell(30,6,' ','BTLR',1,'R'); 
        $this->pdf->SetFont('Arial','',8); // SET FONT
        foreach($details as $details_val) {
            if($details_val->group_code == 1){
                $this->pdf->Cell(104,6,$details_val->description,'BTLR',0,'L');
                $this->pdf->Cell(30,6,$details_val->input_no_of_items,'BTLR',0,'R');
                $this->pdf->Cell(30,6,number_format($details_val->buyer_price,2),'BTLR',0,'R');
                $this->pdf->Cell(30,6,number_format($details_val->added_price,2),'BTLR',1,'R');    
            }     
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
        $this->pdf->SetFont('Arial','B',8); // SET FONT
        $this->pdf->Cell(104,6,'OTHERS','BTLR',0,'L');
        $this->pdf->Cell(30,6,' ','BTLR',0,'R');
        $this->pdf->Cell(30,6,' ','BTLR',0,'R');
        $this->pdf->Cell(30,6,' ','BTLR',1,'R'); 
        $this->pdf->SetFont('Arial','',8); // SET FONT
        foreach($details as $details_val_others) {
            if($details_val_others->group_code == 2 || $details_val_others->group_code == 3){
                $this->pdf->Cell(104,6,$details_val_others->description,'BTLR',0,'L');
                $this->pdf->Cell(30,6,$details_val_others->input_no_of_items,'BTLR',0,'R');
                $this->pdf->Cell(30,6,number_format($details_val_others->buyer_price,2),'BTLR',0,'R');
                $this->pdf->Cell(30,6,number_format($details_val_others->added_price,2),'BTLR',1,'R');    
            }   
        } 
        
        // TABLE FOOTER
        $this->pdf->SetFont('Arial','B',8); // SET FONT
        $this->pdf->Cell(134,6,'','',0,'L');
        $this->pdf->Cell(30,6,'TOTAL AMOUNT :','BTLR',0,'L');
        //$this->pdf->Cell(30,8,$footer_total_input_no_of_items_row->input_no_of_items,'',0,'L');
        //$this->pdf->Cell(30,8,number_format($footer_total_buyer_price_row->buyer_price,2),'',0,'L');
        $this->pdf->Cell(30,6,number_format($footer_total_added_price_row->added_price,2),'BTLR',1,'R');
        $this->pdf->Cell(134,6,'','',0,'L');
        $this->pdf->Cell(30,6,'','BTLR',0,'L');
        $this->pdf->Cell(30,6,'','BTLR',1,'L');
        $this->pdf->Cell(134,6,'','',0,'L');
        $this->pdf->Cell(30,6,'PREVIOUS BAL :','BTLR',0,'L');
        $this->pdf->Cell(30,6,number_format($header_order_no_row->previous_bal,2),'BTLR',1,'R');
        $this->pdf->Cell(134,6,'','',0,'L');
        $this->pdf->Cell(30,6,'DELIVERY CHARGE :','BTLR',0,'L');
        $this->pdf->Cell(30,6,number_format($header_order_no_row->delivery_charge,2),'BTLR',1,'R');
        $this->pdf->Cell(134,6,'','',0,'L');
        $this->pdf->Cell(30,6,'TOTAL BALANCE :','BTLR',0,'L');
        $total_balance = (int)$footer_total_added_price_row->added_price + (int)$header_order_no_row->previous_bal +  (int)$header_order_no_row->delivery_charge;
        $this->pdf->Cell(30,6,number_format($total_balance,2),'BTLR',1,'R');
        
        $this->pdf->Cell(1,6,'','',1,'L');
        $this->pdf->SetFont('Arial','BI',8);
        $this->pdf->Cell(190,4,'If you have any question about this order form please contact',0,1,'C');
        $this->pdf->Cell(190,4,'242-1353 / Email : redstrawcafe@gmail.com',0,1,'C');
        
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
        $this->pdf->Output('redstraw_cafe_'.$order_no.'.pdf','D');    
        
    }  
}   
?>  