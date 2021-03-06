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
    
    # CHECK ORDER NO DETAILS
    function check_order_no_details_item($order_no,$item_no){
        $query_order_no_details_item = "
            select 
                * 
            from 
                tbl_purchase_order_details
            where 
                purchase_order_no = {$order_no}
                and item_id = {$item_no}
        ";
        return $query = $this->db->query($query_order_no_details_item);
    }
    
    
    # SAVE NEW HEADER
    function add_new_detail($data){
        return $this->db->insert("tbl_purchase_order_details", $data);
    }
    
    # GET TOTAL DETAILS
    function get_total_detail($purchase_order_no)
    {
        $total_buyer_price = "
            select
                sum(added_price) as total_added_price
            from
                tbl_purchase_order_details
            where 
                purchase_order_no = {$purchase_order_no}
        ";
        return $query = $this->db->query($total_buyer_price);
    }
    
    # EDIT ITEM ID    
    function edit_header_amount($data,$purchase_order_no){
        $this->db->update("tbl_purchase_order_header", $data, "purchase_order_no = {$purchase_order_no}");
    }
    
    # GET ITEM DESCRIPTION
    function get_item_details($order_no)
    {         
        if($order_no != '')
        {
            $filter_order_no = "where a.purchase_order_no = {$order_no}";    
        }
        else
        {
            $filter_order_no = "where a.purchase_order_no = ''";
        }
        $query_item_description = "
            select 
                a.item_id,
                b.description,
                b.packaging,
                c.group_code,
                c.group_name,
                a.unit_price,
                a.buyer_price,
                a.added_price,
                a.input_no_of_items
            from
                tbl_purchase_order_details a
            left join
                tbl_items b on a.item_id = b.item_id
            left JOIN
                tbl_item_group c on b.group_code = c.group_code
            {$filter_order_no}
            order by 
                b.description
        ";
        $query = $this->db->query($query_item_description);
        return $query->result();
    } 
    
    # DELETE ORDER NO ITEM DETAILS
    function delete_order_no_details_item($order_no,$item_no)
    {
        $query = "
            delete 
            from 
                tbl_purchase_order_details
            where 
                purchase_order_no = {$order_no}
                and item_id = {$item_no}
        ";
        return $this->db->query($query);
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
    
    # GET INPUTED NO OF ITEMS PER PURCHASE ORDER PER ITEM ID
    function get_item_quantity_order_no($purchase_order_no,$item_id)
    {
        $input_item_quantity = "
            select 
                input_no_of_items 
            from 
                tbl_purchase_order_details
            where 
                purchase_order_no = {$purchase_order_no}
                and item_id = {$item_id}
        ";
        return $query = $this->db->query($input_item_quantity);
    }
    
    function get_purchase_order_no($order_no)
    {
        $input_item_quantity = "
            select 
                input_no_of_items 
            from 
                tbl_purchase_order_details
            where 
                purchase_order_no = {$order_no}
        ";
        return $query = $this->db->query($input_item_quantity);
    }
    
    # GET HEADER ORDER NO PER ORDER NUMBER FOR PURCHASE ORDER PRINTOUT
    function get_header_purchase_order_no_pdf($order_no)
    {
        $query_select = "
            select 
                * 
            from  
                tbl_purchase_order_header
            where 
                purchase_order_no like '%{$order_no}'
        ";
        return $query_execute = $this->db->query($query_select);
    }
    
    # GET HEADER BRANCH PER ORDER NUMBER FOR PURCHASE ORDER PRINTOUT
    function get_header_branch_purchase_order_no_pdf($order_no)
    {
        $query_select = "
            select 
                b.* 
            from 
                tbl_purchase_order_header a
            inner join 
                tbl_branch b on a.branch_id = b.branch_id
            where 
                a.purchase_order_no = {$order_no}
        ";
        return $query_execute = $this->db->query($query_select);
    }
    
    # GET FOOTER TOTAL INPUT NUMBER OF ITEMS PER ORDER NUMBER FOR PURCHASE ORDER PRINTOUT
    function get_footer_total_input_no_of_items_order_no_pdf($order_no)
    {
        $query_select = "
            select 
                sum(input_no_of_items) as input_no_of_items 
            from 
                tbl_purchase_order_details
            where 
                purchase_order_no = {$order_no}
        ";
        return $query_execute = $this->db->query($query_select);
    }
    
    # GET FOOTER TOTAL BUYER PRICE PER ORDER NUMBER FOR PURCHASE ORDER PRINTOUT
    function get_footer_total_buyer_price_order_no_pdf($order_no)
    {
        $query_select = "
            select 
                sum(buyer_price) as buyer_price 
            from 
                tbl_purchase_order_details
            where 
                purchase_order_no = {$order_no}
        ";
        return $query_execute = $this->db->query($query_select);
    }
    
    # GET FOOTER TOTAL ADDED PRICE PER ORDER NUMBER FOR PURCHASE ORDER PRINTOUT
    function get_footer_total_added_price_order_no_pdf($order_no)
    {
        $query_select = "
            select 
                sum(added_price) as added_price 
            from 
                tbl_purchase_order_details
            where 
                purchase_order_no = {$order_no}
        ";
        return $query_execute = $this->db->query($query_select);
    }
}