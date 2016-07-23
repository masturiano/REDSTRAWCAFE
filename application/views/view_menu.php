<table id="table_user_info" border = 0>
    <tr>
        <td id="user_label">
            <b>User</b>
        </td>
        <td class="colon">
            <b>:</b>
        </td> 
        <td id="user_full_name">
            <?php 
                $session_full_name = $this->session->userdata('fullName');
                echo $session_full_name;
                //echo print_r($this->session->all_userdata()); // # CHECK THE LIST OF SESSIONS
            ?> 
        </td>
    </tr>
    <tr>
        <td id="module_label">
            <b>Position</b>
        </td>
        <td class="colon">
            <b>:</b>
        </td>
        <td id="module_name">
            <?php 
                $session_dept_short_desc = $this->session->userdata('group_name');
                echo $session_dept_short_desc;
            ?> 
        </td>
    </tr>
    <tr>
        <td id="module_label">
            <b>Module</b>
        </td>
        <td class="colon">
            <b>:</b>
        </td>
        <td id="module_name">
            <?php 
                echo $module_name;
            ?> 
        </td>
    </tr>
    <tr>
        <td id="cur_date_label">
            <b>Date</b>
        </td>
        <td class="colon">
            <b>:</b>
        </td>
        <td id="cur_date_display">
            <?php 
                $current_date = date('F d, Y');
                echo $current_date;
                //echo print_r($this->session->all_userdata()); // # CHECK THE LIST OF SESSIONS
            ?> 
        </td>
    </tr>
    <tr>
        <td id="cur_time_label">
            <b>Time</b>
        </td>
        <td class="colon">
            <b>:</b>
        </td>
        <td id="cur_time_display">
            <div id="currTime">
            </div> 
        </td>
    </tr>
</table>
        
<?php
# SESSION DISPLAY
$session_store_code = $this->session->userdata('storeCode');    
$session_group_code = $this->session->userdata('groupCode');        
$session_user_level = $this->session->userdata('userLevel');        
?>        
        
<div id="menu">
    <ul class="menu">
        <li>
            <a href="<? echo site_url('site/main'); ?>" class="homeIcon" title="Home"><span>Home</span></a>
        </li>
        <li><a href="#" class="parent"><span>Process</span></a>
            <div>  
                <ul>
                    <?php   
                    # RESTRICT DEPARTMENT DEPARTMENT
                    # 1 = MIS
                    if(
                        ($session_group_code == 1)
                        ||
                        ($session_user_level == 2)
                        ||
                        ($session_user_level == 3)
                    ){
                    ?>
                    <li><a href="<? echo site_url('process_add_stock/display_stock'); ?>" class="homeIcon" title="Home"><span>Add Stock</span></a></li>
                    <li><a href="<? echo site_url('process_purchase_order/display_order_form'); ?>" class="homeIcon" title="Home"><span>Purchase Order</span></a></li>
                    <?php
                    }
                    ?>
                    <?php   
                    # ALLOW DEPARTMENT
                    # 4 = ACCOUNTING
                    if(
                        ($session_group_code == 4)
                        && 
                        ($session_user_level == 2)
                    ){
                    ?>
                        <li><a href="<? echo site_url('process_ho/receive'); ?>" class="homeIcon" title="Home"><span>Receive</span></a></li>
                        <li><a href="<? echo site_url('process_ho/received_edit'); ?>" class="homeIcon" title="Home"><span>Received Edit</span></a></li>
                        <li><a href="<? echo site_url('process_ho/post'); ?>" class="homeIcon" title="Home"><span>BIR File Creation</span></a></li>    
                    <?php
                    }
                    ?>
                    
                </ul>  
            </div>
        </li>
        <li><a href="#" class="parent"><span>Report</span></a>
            <div>
                <ul>   
                    <?php   
                    # ALLOW DEPARTMENT
                    # 4 = ACCOUNTING
                    if(
                        ($session_group_code == 4)
                        && 
                        ($session_user_level == 2)
                    ){
                    ?>                  
                    <li><a href="<? echo site_url('report/not_transmit_all'); ?>" class="homeIcon" title="Home"><span>Not Transmit All</span></a></li>
                    <?php
                    }
                    ?> 
                    <?php   
                    # RESTRICT DEPARTMENT DEPARTMENT
                    # 1 = MIS
                    if(
                        ($session_group_code != 1)
                        && 
                        ($session_user_level == 2)
                    ){
                    ?>
                    <li><a href="<? echo site_url('report/cancelled'); ?>" class="homeIcon" title="Home"><span>Cancelled</span></a></li>   
                    <li><a href="<? echo site_url('report/not_transmit'); ?>" class="homeIcon" title="Home"><span>Not Transmit</span></a></li> 
                    <li><a href="<? echo site_url('report/unreceive'); ?>" class="homeIcon" title="Home"><span>Unreceive</span></a></li> 
                    <li><a href="<? echo site_url('report/reprint_transmittal'); ?>" class="homeIcon" title="Home"><span>Reprint Transmittal</span></a></li> 
                    <?php
                    }
                    ?>  
                    <?php   
                    # ALLOW DEPARTMENT
                    # 4 = ACCOUNTING
                    # 5 = CREDIT & COLLECTION
                    if(
                        ($session_group_code == 4) || ($session_group_code == 5)
                        && 
                        ($session_user_level == 2)
                    ){
                    ?>                  
                    <li><a href="<? echo site_url('report/received'); ?>" class="homeIcon" title="Home"><span>Received</span></a></li>   
                    <?php
                    }
                    ?>   
                    <?php   
                    # ALLOW DEPARTMENT
                    # 4 = ACCOUNTING
                    if(
                        ($session_group_code == 4)
                        && 
                        ($session_user_level == 2)
                    ){
                    ?>                  
                    <li><a href="<? echo site_url('report/post'); ?>" class="homeIcon" title="Home"><span>BIR File Creation</span></a></li> 
                    <?php
                    }
                    ?>   
                </ul>
            </div>
        </li>
        <li><a href="#" class="parent"><span>System</span></a>
            <div>
                <ul>
                    <?
                    # ALLOW DEPARTMENT
                    # 1 = MIS
                    if($session_group_code == 1){    
                    ?>
                    <li><a href="<? echo site_url('systema/user_maintenance'); ?>" class="homeIcon" title="User Maintenance"><span>User Maintenance</span></a></li>
                    <li><a href="<? echo site_url('systema/item_maintenance'); ?>" class="homeIcon" title="Item Maintenance"><span>Item Maintenance</span></a></li>
                    <li><a href="<? echo site_url('systema/branch_maintenance'); ?>" class="homeIcon" title="Branch Maintenance"><span>Branch Maintenance</span></a></li>
                    <?php
                    }
                    ?>
                    <li><a href="<? echo site_url('systema/change_password'); ?>" class="homeIcon" title="Change Password"><span>Change Password</span></a></li>
                </ul>
            </div>
        </li>
        <li><a href="<? echo site_url('site/login'); ?>"><span style="color: red;"><b>Logout</b></span></a>
    </ul>
</div>   

<div id="currTime">
</div> 
