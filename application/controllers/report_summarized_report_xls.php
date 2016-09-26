<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');    

class report_summarized_report_xls extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
        
        # LIBRARY
        $this->load->library('form_validation');
        $this->load->library('session');
        
        # INCLUDE FILE
        ini_set('include_path','C:\wamp\php\PEAR');
        require_once 'Spreadsheet/Excel/Writer.php';
    }
    
    function print_excel($txt_date_from,$txt_date_to){	

        $workbook = new Spreadsheet_Excel_Writer(); 
        
        $workbook->setCustomColor(12,226,236,253);
        
        $headerFormat = $workbook->addFormat(array('Size' => 11,
                                      'Color' => 'black',
                                      'bold'=> 1,
                                      'border' => 1,
                                      'Align' => 'merge'));
        $headerFormat->setFontFamily('Calibri'); 
        
        $headerFormat2 = $workbook->addFormat(array('Size' => 11,
                                          'Color' => 'black',
                                          'bold'=> 1,
                                          'border' => 1,
                                          'Align' => 'center'));
        $headerFormat2->setFontFamily('Calibri'); 
        $headerFormat2->setNumFormat('#,##0.00');
    
        $workbook->setCustomColor(13,155,205,255);
        $TotalBorder    = $workbook->addFormat(array('Align' => 'right','bold'=> 1,'border'=>1,'fgColor' => 'white'));
        $TotalBorder->setFontFamily('Calibri'); 
        $TotalBorder->setTop(5); 
        $detailrBorder   = $workbook->addFormat(array('border' =>1,'Align' => 'right'));
        $detailrBorder->setFontFamily('Calibri'); 
        $detailrBorderAlignRight2   = $workbook->addFormat(array('Align' => 'left'));
        $detailrBorderAlignRight2->setFontFamily('Calibri');
        $workbook->setCustomColor(12,183,219,255);
        
        #DETAIL ALIGN LEFT COLOR WHITE 
        $detail_left_color_white   = $workbook->addFormat(array('Size' => 10,
                                              'fgColor' => 'white',
                                              'Pattern' => 1,
                                              'border' =>1,
                                              'Align' => 'left'));
        $detail_left_color_white->setFontFamily('Calibri'); 
        #DETAIL ALIGN LEFT COLOR BLUE 
        $detail_left_color_blue   = $workbook->addFormat(array('Size' => 10,
                                              'border' =>1,
                                              'Pattern' => 1,
                                              'Align' => 'left'));
        $detail_left_color_blue->setFgColor(12); 
        $detail_left_color_blue->setFontFamily('Calibri');
        
        #DETAIL ALIGN CENTER COLOR WHITE 
        $detail_center_color_white   = $workbook->addFormat(array('Size' => 10,
                                              'fgColor' => 'white',
                                              'Pattern' => 1,
                                              'border' =>1,
                                              'Align' => 'center'));
        $detail_center_color_white->setFontFamily('Calibri'); 
        #DETAIL ALIGN CENTER COLOR BLUE 
        $detail_center_color_blue   = $workbook->addFormat(array('Size' => 10,
                                              'border' =>1,
                                              'Pattern' => 1,
                                              'Align' => 'center'));
        $detail_center_color_blue->setFgColor(12); 
        $detail_center_color_blue->setFontFamily('Calibri');
        
        #DETAIL ALIGN RIGHT COLOR WHITE 
        $detail_right_color_white   = $workbook->addFormat(array('Size' => 10,
                                              'fgColor' => 'white',
                                              'Pattern' => 1,
                                              'border' =>1,
                                              'Align' => 'right'));
        $detail_right_color_white->setFontFamily('Calibri'); 
        #DETAIL ALIGN RIGHT COLOR BLUE 
        $detail_right_color_blue   = $workbook->addFormat(array('Size' => 10,
                                              'border' =>1,
                                              'Pattern' => 1,
                                              'Align' => 'right'));
        $detail_right_color_blue->setFgColor(12); 
        $detail_right_color_blue->setFontFamily('Calibri');
        
        #DETAIL ALIGN RIGHT COLOR WHITE WITH NUMBER FORMAT 
        $detail_right_color_white_number   = $workbook->addFormat(array('Size' => 10,
                                              'fgColor' => 'white',
                                              'Pattern' => 1,
                                              'border' =>1,
                                              'Align' => 'right'));
        $detail_right_color_white_number->setFontFamily('Calibri'); 
        $detail_right_color_white_number->setNumFormat('#,##0.00');
        #DETAIL ALIGN RIGHT COLOR BLUE WITH NUMBER FORMAT
        $detail_right_color_blue_number   = $workbook->addFormat(array('Size' => 10,
                                              'border' =>1,
                                              'Pattern' => 1,
                                              'Align' => 'right'));
        $detail_right_color_blue_number->setFgColor(12); 
        $detail_right_color_blue_number->setFontFamily('Calibri');
        $detail_right_color_blue_number->setNumFormat('#,##0.00');

        #DETAIL ALIGN RIGHT COLOR WHITE RED FONT
        $detail_right_color_white_red_font   = $workbook->addFormat(array('Size' => 10,
                                              'Color' => 'red',
                                              'fgColor' => 'white',
                                              'Pattern' => 1,
                                              'border' =>1,
                                              'Align' => 'right'));
        $detail_right_color_white_red_font->setFontFamily('Calibri'); 
        #DETAIL ALIGN RIGHT COLOR BLUE RED FONT
        $detail_right_color_blue_red_font   = $workbook->addFormat(array('Size' => 10,
                                              'Color' => 'red',
                                              'border' =>1,
                                              'Pattern' => 1,
                                              'Align' => 'right'));
        $detail_right_color_blue_red_font->setFgColor(12); 
        $detail_right_color_blue_red_font->setFontFamily('Calibri');
        
        # FILENAME
        $filename = "summarized_report.xls";
        
        # SEND FILENAME
        $workbook->send($filename);
        
        # ADD WORK SHEET OR WORK SHEET LABEL
        $worksheet = $workbook->addWorksheet('summarized_report');
        
        # SET LANDSCAPE
        $worksheet->setLandscape();
        
        # FREEZE PANE
        //$worksheet->freezePanes(array(3,0));
        
        # MAIN HEADER
        $worksheet->write(0,0,"SUMMARIZED REPORT DATE ENTER FROM ".$txt_date_from." TO ".$txt_date_to,$headerFormat);
        for($i=1;$i<5;$i++) {
            $worksheet->write(0, $i, "",$headerFormat);    
        }
        
        # COLUMN WIDTH
        $worksheet->setColumn(0,0,20);
        $worksheet->setColumn(1,1,20);
        $worksheet->setColumn(2,2,20);
        $worksheet->setColumn(3,3,20);
        $worksheet->setColumn(4,4,20);
        
        # HEADER
        $worksheet->write(2,0,"DATE ENTER",$headerFormat);
        $worksheet->write(2,1,"TOTAL PURCHASED",$headerFormat);
        $worksheet->write(2,2,"TOTAL UNIT PRICE",$headerFormat);
        $worksheet->write(2,3,"TOTAL SELLING PRICE",$headerFormat);
        $worksheet->write(2,4,"TOTAL NET SALES",$headerFormat);
        
        # DATABASE
        $this->load->model('get_report'); 
        
        $data = $this->get_report->get_summarized_report($txt_date_from,$txt_date_to);
        
        # ASSIGN COUNTER ROW
        $ctr = 3; 
        
        #ASSIGN ALTERNATE COLOR
        $col = 0;     
        
        # LOOP DATA
        foreach($data as $row){  
             
            $row_left = ($col==0) ? $detail_left_color_blue:$detail_left_color_white;
            $row_center = ($col==0) ? $detail_center_color_blue:$detail_center_color_white;
            $row_right = ($col==0) ? $detail_right_color_blue:$detail_right_color_white;
            $row_right_number = ($col==0) ? $detail_right_color_blue_number:$detail_right_color_white_number;
            $row_right_red_font = ($col==0) ? $detail_right_color_blue_red_font:$detail_right_color_white_red_font;
            
            $col = ($col==0) ? 1:0;   
            
            $worksheet->write($ctr,0,date('Y-m-d',strtotime($row->date_enter)),$row_center);
            $worksheet->write($ctr,1,$row->total_input_no_of_items,$row_right);
            $worksheet->write($ctr,2,$row->total_unit_price,$row_right_number);
            //$worksheet->write($ctr,3,$row->total_buyer_price,$row_right_number);
            $worksheet->write($ctr,3,$row->total_added_price,$row_right_number);
            $worksheet->write($ctr,4,$row->total_net_sales,$row_right_number);
            
            /*
            $worksheet->write($ctr,1,date('Y-m-d',strtotime($row->RECEIPT_DATE)),$row_center);
            $worksheet->write($ctr,2,$row->ACCOUNT_NUMBER,$row_left);
            $worksheet->write($ctr,3,$row->ACCOUNT_NAME,$row_left);     
            $result_company = $this->get_report->get_company($row->ORG_ID); 
            if ($result_company->num_rows() > 0){
               $row_company = $result_company->row();
            }   
            $worksheet->write($ctr,4,$row_company->comp_short,$row_left);
            $worksheet->write($ctr,5,$row->AMOUNT,$row_right_number);
            $worksheet->write($ctr,6," ".$row->TIN,$row_left);
            $worksheet->write($ctr,7,$row->BIR_REG_NAME,$row_left);
            $worksheet->write($ctr,8,$row->ATC_CODE,$row_left);
            $worksheet->write($ctr,9,$row->TAX_BASE,$row_right_number);
            $worksheet->write($ctr,10,date('Y-m-d',strtotime($row->GL_DATE_SAWT)),$row_center);
            $worksheet->write($ctr,11,$row->USER_NAME,$row_left);
            */
             
            $ctr++;
        }  
        
        $data_total_purchased = $this->get_report->get_total_purchased_summarized_report($txt_date_from,$txt_date_to);
        if ($data_total_purchased->num_rows() > 0){
           $row_total_purchased = $data_total_purchased->row();
        }  
        $data_total_unit_price = $this->get_report->get_total_unit_price_summarized_report($txt_date_from,$txt_date_to);
        if ($data_total_unit_price->num_rows() > 0){
           $row_total_unit_price = $data_total_unit_price->row();
        } 
        $data_total_buyer_price = $this->get_report->get_total_buyer_price_summarized_report($txt_date_from,$txt_date_to);
        if ($data_total_buyer_price->num_rows() > 0){
           $row_total_buyer_price = $data_total_buyer_price->row();
        }                              
        $data_total_added_price = $this->get_report->get_total_added_price_summarized_report($txt_date_from,$txt_date_to);
        if ($data_total_added_price->num_rows() > 0){
           $row_total_added_price = $data_total_added_price->row();
        } 
        $data_total_net_sales = $this->get_report->get_total_net_sales_summarized_report($txt_date_from,$txt_date_to);
        if ($data_total_net_sales->num_rows() > 0){
           $row_total_net_sales = $data_total_net_sales->row();
        } 
         
        $worksheet->write($ctr,0,"TOTAL",$headerFormat);             
        $worksheet->write($ctr,1,$row_total_purchased->total_input_no_of_items,$headerFormat);                      
        $worksheet->write($ctr,2,$row_total_unit_price->total_unit_price,$headerFormat2);                      
        $worksheet->write($ctr,3,$row_total_added_price->total_added_price,$headerFormat2);                      
        $worksheet->write($ctr,4,$row_total_net_sales->total_net_sales,$headerFormat2);                      

        # CLOSE WORK BOOK     
        $workbook->close();
    }
}
?>