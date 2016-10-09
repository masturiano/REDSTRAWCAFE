<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_report extends CI_Model {
    
    # GET SERVER CURRENT DATE
    function get_server_current_date_time()
    {
        $query_current_date = "
            select GETDATE() as current_date_time
        ";
        return $query = $this->db->query($query_current_date);
    }
    
    #########################( AVAILABLE STOCKS REPORT )#########################
        
    /**
    * Excel report of all items with item group
    * 
    * @param integer $group_code  
    */
    function get_available_stocks($group_code)
    {   
        # FILTER PER ITEM GROUP
        if($group_code == 0)
        {
            $filter_item_group = "";    
        }
        else
        {
            $filter_item_group = "where a.group_code = {$group_code}";    
        }
        
        
        $query_select = "
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
            {$filter_item_group}
            order by
                group_name asc,
                description asc  
        ";
        $query_execute = $this->db->query($query_select);
        return $query_execute->result();
    }
    
    /**
    * Get the total number of items
    * 
    * @param integer $group_code
    */
    function get_total_no_of_items($group_code)
    {
        # FILTER PER ITEM GROUP
        if($group_code == 0)
        {
            $filter_item_group = "";    
        }
        else
        {
            $filter_item_group = "where a.group_code = {$group_code}";    
        }
        
        $query_select = "
            select 
                sum(a.no_of_items) as no_of_items
            from 
                tbl_items a
            inner join
                tbl_item_group b
                on a.group_code = b.group_code
            {$filter_item_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    #########################( SUMMARIZED REPORT )#########################
    
    /**
    * Excel report of summarized report
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    */
    function get_summarized_report($txt_date_from,$txt_date_to)
    {         
        $query_select = "
            select 
                DATE_FORMAT(date_enter,'%Y-%m-%d') as date_enter,
                sum(input_no_of_items) as total_input_no_of_items,
                sum((unit_price * input_no_of_items)) as total_unit_price,
                sum((buyer_price * input_no_of_items)) as total_buyer_price,
                sum(added_price) as total_added_price,
                (sum((buyer_price * input_no_of_items)) - sum((unit_price * input_no_of_items))) as total_net_sales
            from 
                tbl_purchase_order_details
            where
                DATE_FORMAT(date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
            group by
                DATE_FORMAT(date_enter,'%Y-%m-%d')
        ";
        $query_execute = $this->db->query($query_select);
        return $query_execute->result();
    }
    
    /**
    * Get the total purchased
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to
    */
    function get_total_purchased_summarized_report($txt_date_from,$txt_date_to)
    {   
        $query_select = "
            select 
                sum(input_no_of_items) as total_input_no_of_items
            from 
                tbl_purchase_order_details
            where
                DATE_FORMAT(date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total unit price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to
    */
    function get_total_unit_price_summarized_report($txt_date_from,$txt_date_to)
    {   
        $query_select = "
            select 
                sum((unit_price * input_no_of_items)) as total_unit_price
            from 
                tbl_purchase_order_details
            where
                DATE_FORMAT(date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total buyer price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to
    */
    function get_total_buyer_price_summarized_report($txt_date_from,$txt_date_to)
    {   
        $query_select = "
            select 
                sum((buyer_price * input_no_of_items)) as total_buyer_price
            from 
                tbl_purchase_order_details
            where
                DATE_FORMAT(date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total added price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to
    */
    function get_total_added_price_summarized_report($txt_date_from,$txt_date_to)
    {   
        $query_select = "
            select 
                sum(added_price) as total_added_price
            from 
                tbl_purchase_order_details
            where
                DATE_FORMAT(date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total net sales
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to
    */
    function get_total_net_sales_summarized_report($txt_date_from,$txt_date_to)
    {   
        $query_select = "
            select 
                (sum((buyer_price * input_no_of_items)) - sum((unit_price * input_no_of_items))) as total_net_sales
            from 
                tbl_purchase_order_details
            where
                DATE_FORMAT(date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
        ";
        return $query_execute = $this->db->query($query_select);
    }
    
    #########################( DETAILED REPORT )#########################
    
    /**
    * Excel report of detailed report
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_detailed_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {         
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                a.purchase_order_no,
                b.branch_id,
                b.branch_no,
                d.branch_name,
                b.date_enter,
                c.description,
                a.unit_price,
                a.buyer_price,
                a.input_no_of_items,
                a.added_price,
                (a.unit_price * a.input_no_of_items) as total_unit_price,
                (a.added_price - (a.unit_price * a.input_no_of_items)) as net_sales
            from tbl_purchase_order_details a
            inner join tbl_purchase_order_header b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
            order by
                a.date_enter,
                d.branch_name,
                (a.added_price - (a.unit_price * a.input_no_of_items))
        ";
        $query_execute = $this->db->query($query_select);
        return $query_execute->result();
    }
    
    /**
    * Get the total unit price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_unit_price_detailed_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.unit_price) as total_unit_price
            from tbl_purchase_order_details a
            inner join tbl_purchase_order_header b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total buyer price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_buyer_price_detailed_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.buyer_price) as total_buyer_price
            from tbl_purchase_order_details a
            inner join tbl_purchase_order_header b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total number of items
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_no_of_items_detailed_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.input_no_of_items) as total_input_no_of_items
            from tbl_purchase_order_details a
            inner join tbl_purchase_order_header b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total added buyer price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_added_buyer_price_detailed_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.added_price) as total_added_price
            from tbl_purchase_order_details a
            inner join tbl_purchase_order_header b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total total unit price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_total_unit_price_detailed_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.added_price - (a.unit_price * a.input_no_of_items)) as total_total_unit_price
            from tbl_purchase_order_details a
            inner join tbl_purchase_order_header b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the net sales
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_net_sales_detailed_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.added_price - (a.unit_price * a.input_no_of_items)) as total_net_sales
            from tbl_purchase_order_details a
            inner join tbl_purchase_order_header b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    #########################( CANCELLED ORDER REPORT )#########################
    
    /**
    * Excel report of detailed report
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_cancelled_order_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {         
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                a.purchase_order_no,
                b.branch_id,
                b.branch_no,
                d.branch_name,
                b.date_enter,
                c.description,
                a.unit_price,
                a.buyer_price,
                a.input_no_of_items,
                a.added_price,
                (a.unit_price * a.input_no_of_items) as total_unit_price,
                (a.added_price - (a.unit_price * a.input_no_of_items)) as net_sales
            from tbl_purchase_order_details_cancel a
            inner join tbl_purchase_order_header_cancel b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
            order by
                a.date_enter,
                d.branch_name,
                (a.added_price - (a.unit_price * a.input_no_of_items))
        ";
        $query_execute = $this->db->query($query_select);
        return $query_execute->result();
    }
    
    /**
    * Get the total unit price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_unit_price_cancelled_order_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.unit_price) as total_unit_price
            from tbl_purchase_order_details_cancel a
            inner join tbl_purchase_order_header_cancel b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total buyer price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_buyer_price_cancelled_order_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.buyer_price) as total_buyer_price
            from tbl_purchase_order_details_cancel a
            inner join tbl_purchase_order_header_cancel b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total number of items
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_no_of_items_cancelled_order_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.input_no_of_items) as total_input_no_of_items
            from tbl_purchase_order_details_cancel a
            inner join tbl_purchase_order_header_cancel b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total added buyer price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_added_buyer_price_cancelled_order_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.added_price) as total_added_price
            from tbl_purchase_order_details_cancel a
            inner join tbl_purchase_order_header_cancel b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the total total unit price
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_total_unit_price_cancelled_order_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.added_price - (a.unit_price * a.input_no_of_items)) as total_total_unit_price
            from tbl_purchase_order_details_cancel a
            inner join tbl_purchase_order_header_cancel b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    
    /**
    * Get the net sales
    * 
    * @param string $txt_date_from
    * @param string $txt_date_to 
    * @param integer $cmb_branch_group
    */
    function get_total_net_sales_cancelled_order_report($txt_date_from,$txt_date_to,$cmb_branch_group)
    {   
        # FILTER PER BRANCH GROUP
        if($cmb_branch_group == 0)
        {
            $filter_branch_group = "";    
        }
        else
        {
            $filter_branch_group = "and b.branch_id = {$cmb_branch_group}";    
        }
        $query_select = "
            select
                sum(a.added_price - (a.unit_price * a.input_no_of_items)) as total_net_sales
            from tbl_purchase_order_details_cancel a
            inner join tbl_purchase_order_header_cancel b
                on b.purchase_order_no = a.purchase_order_no
            left join tbl_items c 
                on c.item_id = a.item_id
            left join tbl_branch d
                on d.branch_id = b.branch_id
            where 
                DATE_FORMAT(a.date_enter,'%Y-%m-%d') >= '{$txt_date_from}'
                and DATE_FORMAT(a.date_enter,'%Y-%m-%d') <= '{$txt_date_to}'
                {$filter_branch_group}
        ";
        return $query_execute = $this->db->query($query_select);
    } 
    #########################( UNIVERSAL )#########################
    
    /**
    * Get all item for combo box
    * 
    */
    function get_item_group_name()
    {
        $query_select = "
            select 
                group_code,
                group_name
            from 
                tbl_item_group
            order by
                group_name   
        ";
        $query_execute = $this->db->query($query_select);
        return $query_execute->result();
    } 
    
    /**
    * Get all branch for combo box
    * 
    */
    function get_branch_name()
    {
        $query_branch = "
            select 
                branch_id,
                branch_no,
                branch_name,
                owner
            from
                tbl_branch
            order by 
                branch_name
        ";
        $query = $this->db->query($query_branch);
        return $query->result();
    } 
}
