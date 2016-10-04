<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_process_cancel_order extends CI_Model {
    
    #########################( ENCODE )#########################
    
    # GET SERVER CURRENT DATE
    function get_server_current_date_time()
    {
        $query_current_date = "
            select NOW() as current_date_time
        ";
        return $query = $this->db->query($query_current_date);
    }
    
    #########################( PURCHASE ORDER )#########################
    
    # CHECK ORDER NO DETAILS
    function check_order_no($order_no){
        $query_select = "
            select 
                * 
            from 
                tbl_purchase_order_header
            where 
                purchase_order_no = {$order_no}
        ";
        return $query_execute = $this->db->query($query_select);
    }
}