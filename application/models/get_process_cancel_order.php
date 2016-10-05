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
    
    #########################( CANCEL ORDER )#########################
    
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
    
    # GET INPUTED NO OF ITEMS PER PURCHASE ORDER PER ITEM ID
    function get_item_quantity_order_no($order_no)
    {
        $query_select = "
            select 
                item_id,
                input_no_of_items  
            from 
                tbl_purchase_order_details
            where 
                purchase_order_no = {$order_no}
        ";
        $query_execute = $this->db->query($query_select);
        return $query_execute->result();
    }
    
    # GET ITEM QUANTITY
    function get_item_quantity($item_id)
    {
        $item_qty = "       
            select 
                no_of_items 
            from 
                tbl_items where item_id = {$item_id}
        ";
        return $query = $this->db->query($item_qty);
    }
    
    # EDIT ITEM QUANTITY
    function edit_item_quantity($data,$item_id){
        return $this->db->update("tbl_items", $data, "item_id = {$item_id}");
    }
    
    # CANCEL HEADER ORDER NO
    function cancelHeaderOrderNo($order_no){
        $query_select = "       
            insert into tbl_purchase_order_header_cancel(
                buyer,
                purchase_order_no,
                branch_id,
                branch_no,
                branch_name,
                owner,
                amount,
                date_enter,
                date_update,
                delivery_charge,
                previous_bal
            )
            select
                buyer,
                purchase_order_no,
                branch_id,
                branch_no,
                branch_name,
                owner,
                amount,
                date_enter,
                date_update,
                delivery_charge,
                previous_bal
            from 
                tbl_purchase_order_header
            where 
                purchase_order_no = {$order_no};
        ";
        $query = $this->db->query($query_select);
    }
}