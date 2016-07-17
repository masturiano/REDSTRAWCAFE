<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_system extends CI_Model {
    
    # GET SERVER CURRENT DATE
    function get_server_current_date_time()
    {
        $query_current_date = "
            select NOW() as current_date_time
        ";
        // select CURDATE() as current_date_time
        return $query = $this->db->query($query_current_date);
    }
    
    # GET USER MAINTENANCE DETAILS
    function get_user_maintenance()
    {
        $query_get_user_details = "
            SELECT 
                a.user_id,
                a.group_code,
                a.full_name,
                a.user_name,
                a.user_pass,
                a.user_stat,
                a.date_enter,
                a.date_update,
                a.ip_address,
                a.log,
                b.group_name,
                c.user_stat_description 
            FROM 
                tbl_users a
            LEFT OUTER JOIN
                tbl_user_group b ON a.group_code = b.group_code
            LEFT OUTER JOIN
                tbl_user_stat c ON a.user_stat = c.user_stat 
        ";
        $query = $this->db->query($query_get_user_details);
        return $query->result();
    }
    
    # GET USER DETAILS
    function get_user_details($user_id)
    {
        $query_user_detail = "
            SELECT 
                a.user_id,
                a.group_code,
                a.full_name,
                a.user_name,
                a.user_stat,
                a.date_enter,
                a.date_update,
                a.ip_address,
                a.log,
                b.group_name 
            FROM 
                tbl_users a
            LEFT OUTER JOIN
                tbl_user_group b ON a.group_code = b.group_code
            WHERE
                a.user_id = {$user_id} 
        ";
        return $query = $this->db->query($query_user_detail);
    }
    
    # GET USER GROUP
    function get_user_group()
    {
        $query_get_user_department = "
            select 
                group_code,group_name 
            from 
                tbl_user_group
        ";
        $query = $this->db->query($query_get_user_department);
        return $query->result();
    }
    
    # GET USER LEVEL
    function get_user_level()
    {
        $query_get_user_level = "
            select 
                level_code,level_desc,level_stat
            from
                tbl_user_level
        ";
        $query = $this->db->query($query_get_user_level);
        return $query->result();
    }
    
    # GET STORE DETAILS
    function get_store_details()
    {
        $query_store_details = "
            select 
                a.strnum as store_num,a.stshrt as store_short,cast(a.strnum as nvarchar)+' - '+a.strnam as store_code_name
            from 
                mmpgtlib_tblstr a
            where
                (a.STRNAM NOT LIKE 'X%')
                and (a.STCOMP in (101,102,103,104,105,801,802,803,804,805,806,807,808,809,700))
                and (a.STRNUM < 902)
            order by 
                a.strnum
        ";
        $query = $this->db->query($query_store_details);
        return $query->result();
    }
    
    # GET USER STATUS
    function get_user_status()
    {
        $query_get_user_status = "
            select 
                user_stat,user_stat_description
            from 
                tbl_user_stat
        ";
        $query = $this->db->query($query_get_user_status);
        return $query->result();
    }  
    
    # CHECK USER IF EXISTING
    function check_username($post_username)
    {
        $query = "
            select 
                user_id,
                group_code,
                full_name,
                user_name,
                user_stat,
                date_enter,
                date_update,
                ip_address,
                log 
            from 
                tbl_users 
            where 
                user_name = '{$post_username}'
        ";
        return $query = $this->db->query($query);
    }  
    
    # ADD NEW USER
    function add_new_user($data){
        echo $this->db->insert("tbl_users", $data);
    }
    
    # DELETE USER ID
    function delete_user_id($post_id)
    {
        $query_delete_user_id = "
            delete 
            from 
                tbl_users
            where 
                user_id = {$post_id}
        ";
        $this->db->query($query_delete_user_id);
    }
           
    # EDIT USER ID    
    function edit_user_id($data,$user_id){
        $this->db->update("tbl_users", $data, "user_id = {$user_id}");
    }
    
    # EDIT USER ID GET STORE DETAILS SELECTED
    function get_store_details_selected($user_id)
    {
        $query_get_user_level = "
            select 
                b.strnum as store_num,b.stshrt as store_short,cast(b.strnum as nvarchar)+' - '+b.strnam as store_code_name 
            from 
                tbl_users a
            inner join 
                mmpgtlib_tblstr b on b.strnum = a.store_code
            where 
                user_id = {$user_id}
        ";
        return $query = $this->db->query($query_get_user_level);
    }
    
    # EDIT USER ID GET USER GROUP NAME SELECTED
    function get_user_group_selected($user_id)
    {
        $query_get_user_group = "
            select 
                a.group_code,b.group_name 
            from 
                tbl_users a
            left join
                tbl_user_group b
                on a.group_code = b.group_code
            where
                a.user_id = {$user_id}
        ";
        return $query = $this->db->query($query_get_user_group);
    }
    
    # EDIT USER ID GET USER LEVEL SELECTED
    function get_user_level_selected($user_id)
    {
        $query_get_user_level = "
            select 
                b.level_code,b.level_desc,b.level_stat
            from 
                tbl_users a
            left join tbl_user_level b on b.level_code = a.user_level
            where
                a.user_id = {$user_id}
        ";
        return $query = $this->db->query($query_get_user_level);
    }  
    
    # EDIT USER GET USER STATUS
    function get_user_status_selected($user_id)
    {
        $query_get_user_status = "
            select 
                a.user_stat,
                b.user_stat_description
            from 
                tbl_users a
            left join 
                tbl_user_stat b
                on a.user_stat = b.user_stat
            where
                a.user_id = {$user_id}
        ";
        return $query = $this->db->query($query_get_user_status);
    }  
    
    # EDIT USER PASSWORD   
    function edit_user_password($data,$user_id){
        $this->db->update("tbl_users", $data, "user_name = '{$user_id}'");
    }
    
    # COPY ORACLE DATA
    function get_oracle_data($date_from,$date_to)
    {
        $query_get_oracle_data = "
            IF OBJECT_ID('tbl_oracle_temp', 'U') IS NOT NULL
            BEGIN
            DROP TABLE tbl_oracle_temp
            END
            -- INSERT INTO TEMP TABLE
            BEGIN
                -- CURRENT DATE
                DECLARE @Current_Date varchar(MAX)
                select @Current_Date = CONVERT(char(10), GetDate(),126)
                --Select @Current_Date as 'Current_Date' -- to view the date
                -- PREVIOUS 2 DAYS
                DECLARE @Previous_Two_Days varchar(MAX)
                select @Previous_Two_Days = CONVERT(char(10), DATEADD(DAY,-2,GetDate()),126)
                --Select @Prev_Two_Days as 'Prev_Two_Days' -- to view the date
                DECLARE @TSQL varchar(8000)
                select @TSQL = ' 
                select 
                    CASH_RECEIPT_ID,RECEIPT_NUMBER,RECEIPT_DATE,ACCOUNT_NUMBER,ACCOUNT_NAME,
                    RECEIPT_METHOD_ID,NAME,ORG_ID,AMOUNT,USER_ID,USER_NAME,GL_DATE,GL_DATE_SAWT,ORA_CREATION_DATE,SAWT_EXTRACT_DATE
                into tbl_oracle_temp
                from openquery([192.168.200.136],''
                    SELECT acr.CASH_RECEIPT_ID,
                        acr.RECEIPT_NUMBER,
                        acr.RECEIPT_DATE,
                        a.ACCOUNT_NUMBER,
                        a.ACCOUNT_NAME,
                        acr.RECEIPT_METHOD_ID,
                        b.NAME,
                        acr.ORG_ID,
                        acr.AMOUNT,
                        fnd_user.USER_ID,
                        fnd_user.USER_NAME,
                        arp.GL_DATE,
                        arp.GL_DATE as GL_DATE_SAWT,
                        acr.CREATION_DATE as ORA_CREATION_DATE,
                        '''''+@Current_Date_Time+''''' as SAWT_EXTRACT_DATE
                    FROM fnd_user,
                        ar_cash_receipts_all acr,
                        hz_cust_accounts a,
                        AR_RECEIPT_METHODS b,
                        ar_payment_schedules_all arp
                    WHERE acr.PAY_FROM_CUSTOMER = a.CUST_ACCOUNT_ID
                        AND acr.RECEIPT_METHOD_ID   = b.RECEIPT_METHOD_ID
                        AND acr.CREATED_BY          = fnd_user.USER_ID
                        AND acr.CASH_RECEIPT_ID     = arp.CASH_RECEIPT_ID
                        AND (b.NAME LIKE ''''%230%''''
                        AND acr.ORG_ID IN (85,87,113,133,153))
                        AND acr.CREATION_DATE between ''''{$date_from}'''' and ''''{$date_to}''''    
                    ORDER BY acr.ORG_ID,
                        a.ACCOUNT_NUMBER,
                        acr.CASH_RECEIPT_ID
                '')' 
                EXEC (@TSQL)
            END
            BEGIN
                insert into 
                    tbl_export(
                    CASH_RECEIPT_ID,RECEIPT_NUMBER,RECEIPT_DATE,ACCOUNT_NUMBER,ACCOUNT_NAME,
                    RECEIPT_METHOD_ID,NAME,ORG_ID,AMOUNT,USER_ID,USER_NAME,GL_DATE,GL_DATE_SAWT,ORA_CREATION_DATE,
                    SAWT_EXTRACT_DATE
                    )
                select 
                    CASH_RECEIPT_ID,RECEIPT_NUMBER,RECEIPT_DATE,ACCOUNT_NUMBER,ACCOUNT_NAME,
                    RECEIPT_METHOD_ID,NAME,ORG_ID,AMOUNT,USER_ID,USER_NAME,GL_DATE,GL_DATE_SAWT,ORA_CREATION_DATE,
                    SAWT_EXTRACT_DATE
                from tbl_oracle_temp
                where 
                    CASH_RECEIPT_ID not in (
                            select CASH_RECEIPT_ID from tbl_export
                    )
                    and CASH_RECEIPT_ID not in (
                            select CASH_RECEIPT_ID from tbl_export_cancelled
                    )
            END    
        ";
        $query = $this->db->query($query_get_oracle_data);
    } 
    
    #GET ITEM MAINTENANCE DETAILS
    function get_item_maintenance()
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
    
    # GET ITEM GROUP
    function get_item_group()
    {
        $query_get_item_group = "
            select 
                group_code,
                group_name 
            from 
                tbl_item_group
        ";
        $query = $this->db->query($query_get_item_group);
        return $query->result();
    }
    
    # EDIT ITEM ID GET ITEM GROUP NAME SELECTED
    function get_item_group_selected($item_id)
    {
        $query_get_item_group = "
            select 
                a.group_code,
                b.group_name 
            from 
                tbl_items a
            inner join
                tbl_item_group b
                on a.group_code = b.group_code
            where
                a.item_id = {$item_id}
        ";
        return $query = $this->db->query($query_get_item_group);
    }
    
    # ADD NEW ITEM
    function add_new_item($data){
        echo $this->db->insert("tbl_items", $data);
    }
    
    # EDIT ITEM ID    
    function edit_item_id($data,$item_id){
        $this->db->update("tbl_items", $data, "item_id = {$item_id}");
    }
    
    # DELETE ITEM ID
    function delete_item_id($post_id)
    {
        $query_delete_item_id = "
            delete 
            from 
                tbl_items
            where 
                item_id = {$post_id}
        ";
        $this->db->query($query_delete_item_id);
    }
    
    # GET ITEM DETAILS
    function get_item_details($item_id)
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
    
    #GET BRANCH MAINTENANCE DETAILS
    function get_branch_maintenance()
    {
        $query_get_branch_details = "
            select 
                branch_id,
                branch_no,
                branch_name,
                address,
                owner,
                mobile_no,
                tel_no,
                date_enter,
                date_update
            from 
                tbl_branch
            order by 
                branch_name
        ";
        $query = $this->db->query($query_get_branch_details);
        return $query->result();
    }
    
    # CHECK BRANCH NUMBER IF EXISTING
    function check_branch_no($post_branch_no)
    {
        $query = "
            select 
                branch_id,
                branch_no,
                branch_name,
                address,
                owner,
                mobile_no,
                tel_no,
                date_enter,
                date_update
            from 
                tbl_branch
            where 
                branch_no = '{$post_branch_no}'
        ";
        return $query = $this->db->query($query);
    }  
    
    # ADD NEW BRANCH
    function add_new_branch($data){
        echo $this->db->insert("tbl_branch", $data);
    }
    
    # EDIT BRANCH ID    
    function edit_branch_id($data,$branch_id){
        $this->db->update("tbl_branch", $data, "branch_id = {$branch_id}");
    }
    
    # DELETE BRANCH ID
    function delete_branch_id($post_id)
    {
        $query_delete_item_id = "
            delete 
            from 
                tbl_branch
            where 
                branch_id = {$post_id}
        ";
        $this->db->query($query_delete_item_id);
    }
    
    # GET BRANCH DETAILS
    function get_branch_details($branch_id)
    {
        $query_branch_detail = "
            select 
                branch_id,
                branch_no,
                branch_name,
                address,
                owner,
                mobile_no,
                tel_no,
                date_enter,
                date_update
            from 
                tbl_branch
            where
                branch_id = {$branch_id} 
            order by 
                branch_name
        ";
        return $query = $this->db->query($query_branch_detail);
    }
    
}
