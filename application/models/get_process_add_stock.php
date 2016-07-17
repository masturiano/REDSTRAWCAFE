<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_process_add_stock extends CI_Model {
    
    #########################( ENCODE )#########################
    
    # GET SERVER CURRENT DATE
    function get_server_current_date_time()
    {
        $query_current_date = "
            select GETDATE() as current_date_time
        ";
        return $query = $this->db->query($query_current_date);
    }
    
    # GET ADD STOCK
    function get_add_stock()
    {
        $query_get_item_details = "
            select 
                a.item_id,
                a.group_code,
                b.group_name,
                a.description,
                a.packaging,
                a.unit_price,
                a.rel_price,
                a.fran_price,
                a.no_of_items,
                a.lower_limit,
                a.date_enter,
                a.date_update
            from 
                tbl_items a
            inner join
                tbl_item_group b
                on a.group_code = b.group_code
        ";
        $query = $this->db->query($query_get_item_details);
        return $query->result();
    }
    
    # GET ITEM DETAILS
    function get_add_stock_details($item_id)
    {
        $query_item_detail = "
            select 
                a.item_id,
                a.group_code,
                b.group_name,
                a.description,
                a.packaging,
                a.unit_price,
                a.rel_price,
                a.fran_price,
                a.no_of_items,
                a.lower_limit,
                a.date_enter,
                a.date_update
            from 
                tbl_items a
            inner join
                tbl_item_group b
                on a.group_code = b.group_code
            where
                a.item_id = {$item_id} 
        ";
        return $query = $this->db->query($query_item_detail);
    }
}
