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
    
    #########################( PURCHASE ORDER )#########################
    
    # LAST ORDER NUMBER
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
    
    # GET BRANCH NAME
    function get_branch_name($branch_name)
    {
        $query_branch = "
            select 
                branch_id,
                branch_no,
                branch_name,
                owner
            from
                tbl_branch
            where
                branch_name like '%{$branch_name}%'
        ";
        $query = $this->db->query($query_branch);
        return $query->result();
    } 
    
    # SAVE NEW HEADER
    function add_new_header($data){
        echo $this->db->insert("tbl_purchase_order_header", $data);
    }
    
    # GET ITEM DESCRIPTION
    function get_item_description($item_description,$order_no)
    {
        $result_buyer_price = $this->get_buyer_price($order_no);
        if ($result_buyer_price->num_rows() > 0){
           $row = $result_buyer_price->row();
        }
        else{
           $row = "0"; 
        }         
        $query_item_description = "
            select 
                a.item_id,
                a.group_code,
                b.group_name,
                a.description,
                a.packaging,
                a.unit_price,
                a.{$row->buyer} as buyer_price,
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
                a.description like '%{$item_description}%'
        ";
        $query = $this->db->query($query_item_description);
        return $query->result();
    } 
    
    # GET BUYER PRICE
    function get_buyer_price($order_no)
    {
        $query_item_description = "
            select 
                buyer
            from 
                tbl_purchase_order_header
            where
                purchase_order_no = $order_no
        ";
        return $query = $this->db->query($query_item_description);
    } 
    
    # SAVE NEW HEADER
    function add_new_detail($data){
        echo $this->db->insert("tbl_purchase_order_details", $data);
    }
}