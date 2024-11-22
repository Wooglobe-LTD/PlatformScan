<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Video_Compilation_Model extends APP_Model {

    public function __construct() {
        parent::__construct();

    }



    public function getCompilationLeadsByGroupId($groupId,$id = null){
        $query = "
            SELECT * 
            FROM compilation_leads
            WHERE lead_group_id = '$groupId'
        ";

        if(!empty($id) && $id > 0){
            $query .= " AND id = $id ";
        }

        $result = $this->db->query($query);
        return $result->result();
    }
    public function getCompilationDealById($id,$fields='*')
    {
        if(is_array($fields)){

            $fields = implode(',',$fields);

        }

        $query = '
			SELECT '.$fields.'
		    FROM compilation_leads cl
            LEFT JOIN admin ad
            ON ad.id = cl.assign_to
		    WHERE cl.id = '.$id.'
		';

        $result = $this->db->query($query);

        if($result->num_rows() > 0){

            return $result->row();

        }else{

            return false;

        }

    }
    public function getCSompilationLeads($sortColumn = 'cl.created_at',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
        $action_table_join = '';
        $join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id == '11'){
            $staff_check = 'AND cl.assign_to = '.$adminid;
        }



        $query = '
	        SELECT cl.*
				FROM compilation_leads cl
				LEFT JOIN admin ad
	            ON ad.id = cl.assign_to
				WHERE cl.deleted = 0
				AND cl.status = "Unassigned"
				'.$staff_check.'
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;


        $result = $this->db->query($query);

        return $result;

    }

    public function getCSompilationLeadsCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id == '11'){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT cl.*
				FROM compilation_leads cl
				LEFT JOIN admin ad
	            ON ad.id = cl.assign_to
				WHERE cl.deleted = 0
				AND cl.status = "Unassigned"
				'.$staff_check.'';


        return $this->db->query($query)->num_rows();

    }

    public function getAssignedLeads($sortColumn = 'cl.created_at',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
        $action_table_join = '';
        $join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id == '11'){
            $staff_check = 'AND cl.assign_to = '.$adminid;
        }



        $query = '
	        SELECT cl.*,ad.name
				FROM compilation_leads cl
				LEFT JOIN admin ad
	            ON ad.id = cl.assign_to
				WHERE cl.deleted = 0
				AND cl.status = "Assigned"
				'.$staff_check.'
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;


        $result = $this->db->query($query);

        return $result;

    }

    public function getAssignedLeadsCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id == '11'){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT cl.*
				FROM compilation_leads cl
				LEFT JOIN admin ad
	            ON ad.id = cl.assign_to
				WHERE cl.deleted = 0
				AND cl.status = "Assigned"
				'.$staff_check.'';


        return $this->db->query($query)->num_rows();

    }

    public function getLeadInfo($groupId,$leadId)
    {

        if(empty($leadId)){
            $leadId = 0;
        }

        $query = '
	        SELECT cl.*
				FROM compilation_leads_info cl
				WHERE cl.deleted = 0
				AND cl.lead_group_id = "'.$groupId.'"
				AND cl.lead_id = '.$leadId.'';


        $result = $this->db->query($query);

        return $result;

    }

    public function getLeadInfoByLeadId($leadId)
    {

        if(empty($leadId)){
            $leadId = 0;
        }

        $query = '
	        SELECT cl.*
				FROM compilation_leads_info cl
				WHERE cl.deleted = 0
				AND cl.lead_id = '.$leadId.'';


        $result = $this->db->query($query);

        return $result;

    }

    public function getProgressedLeads($sortColumn = 'cl.created_at',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
        $action_table_join = '';
        $join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id == '11'){
            $staff_check = 'AND cl.assign_to = '.$adminid;
        }



        $query = '
	        SELECT cl.*,ad.name,(SELECT COUNT(inf.id) FROM compilation_leads_info inf WHERE inf.lead_id = cl.id AND inf.status = "Acquired") info_count
				FROM compilation_leads cl
				LEFT JOIN admin ad
	            ON ad.id = cl.assign_to
				WHERE cl.deleted = 0
				AND cl.status = "In-Progress"
				'.$staff_check.'
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;


        $result = $this->db->query($query);

        return $result;

    }

    public function getProgressedLeadsCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id == '11'){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT cl.*
				FROM compilation_leads cl
				LEFT JOIN admin ad
	            ON ad.id = cl.assign_to
				WHERE cl.deleted = 0
				AND cl.status = "In-Progress"
				'.$staff_check.'';


        return $this->db->query($query)->num_rows();

    }

    public function getCompletedLeads($sortColumn = 'cl.created_at',$sort = 'DESC', $limit = 10, $offset = 0, $additional_info = [])
    {
        $action_table_join = '';
        $join_cond = '';
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id == '11'){
            $staff_check = 'AND cl.assign_to = '.$adminid;
        }



        $query = '
	        SELECT cl.*,ad.name, (SELECT COUNT(inf.id) FROM compilation_leads_info inf WHERE inf.lead_id = cl.id AND inf.status = "Acquired") info_count
				FROM compilation_leads cl
				LEFT JOIN admin ad
	            ON ad.id = cl.assign_to
				WHERE cl.deleted = 0
				AND cl.status = "Completed"
				'.$staff_check.'
	        ORDER BY '.$sortColumn.' '.$sort.' LIMIT '.$limit.' OFFSET '.$offset;


        $result = $this->db->query($query);

        return $result;

    }

    public function getCompletedLeadsCount()
    {
        $adminid = $this->sess->userdata('adminId');

        $check_query = 'select admin_role_id from admin where id='.$adminid;
        $check_res = $this->db->query($check_query);
        $check_role_id =$check_res->result();
        $role_id =$check_role_id[0]->admin_role_id;
        $staff_check = '';
        if ($role_id == '11'){
            $staff_check = 'AND vl.staff_id = '.$adminid;
        }
        $query = '
	        SELECT cl.*
				FROM compilation_leads cl
				LEFT JOIN admin ad
	            ON ad.id = cl.assign_to
				WHERE cl.deleted = 0
				AND cl.status = "Completed"
				'.$staff_check.'';


        return $this->db->query($query)->num_rows();

    }

}
