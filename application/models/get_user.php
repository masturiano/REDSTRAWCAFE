<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Get_user extends CI_Model {

    # GET USER
	function validate_user($arr)
	{
        $user_name = $arr['txt_username'];
        $pass_word = base64_encode($arr['txt_password']);
        //$pass_word = $arr['txt_password'];
        
        $query_validate_user = "
            SELECT 
                * 
            FROM 
                tbl_users
            WHERE
                user_name = '{$user_name}'
                and user_pass = '{$pass_word}'    
        ";
        $query = $this->db->query($query_validate_user); // # EXECUTE QUERY
        return $query->num_rows(); # COUNT NUMBER OF ROWS 
        //return $query->result_array(); # FETCH ASSOC
        //return $query->result(); # FETCH ARRAY
	}
    
    # GET USER DETAILS
    function user_details($arr)
    {
        $user_name = $arr['txt_username'];
        $pass_word = base64_encode($arr['txt_password']);
        //$pass_word = $arr['txt_password'];
        
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
                user_name = '{$user_name}'
                AND user_pass = '{$pass_word}'  
                AND user_stat = 'A' 
        ";
        return $query = $this->db->query($query_user_detail); // # EXECUTE QUERY
        //return $query->db->get()result_array();
    }
}
