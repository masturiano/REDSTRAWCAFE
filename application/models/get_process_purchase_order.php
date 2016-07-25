<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_process_purchase_order extends CI_Model {
    
    #########################( ENCODE )#########################
    
    # GET SERVER CURRENT DATE
    function get_server_current_date_time()
    {
        $query_current_date = "
            select NOW() as current_date_time
        ";
        return $query = $this->db->query($query_current_date);
    }
    
    #########################( LAST ORDER NUMBER )#########################
    function get_order_number(){
        
        $query_add_order_no = "
            UPDATE
                tbl_order_number
            SET
                last_order_no=last_order_no+1;
        ";
        
        $query_get_new_order_no = "
            SELECT 
                last_order_no
            FROM 
                tbl_order_number;
        ";
        if ($this->db->query($query_add_order_no)) {
            return $this->db->query($query_get_new_order_no);               
        } 
        else{
            return false;
        }
    }
}