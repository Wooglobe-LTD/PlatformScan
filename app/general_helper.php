<?php


function auth (){
	
	$ci = &get_instance();
	
	$sess = $ci->sess->userdata('isAdminLogin');
	
	if(!$sess){
		
		redirect('auth');
		
	}
	
}


function auth_ajax (){
	
	$ci = &get_instance();
	
	$sess = $ci->sess->userdata('isAdminLogin');
	
	if(!$sess){
		$response = array();
		
		$response['code'] = 204;
		$response['message'] = 'Invalid user session!';
		$response['error'] = 'Session expire.';
		$response['url'] = base_url().'auth';
		
		return $response;
		
	}else{
		return false;
	}
	
}

function json($response){

    $ci = &get_instance();

	$response = $ci->output
            ->set_content_type('application/json')
            ->set_output(json_encode($response));
		return $response;
	
}

function auth_verify (){
	
	$ci = &get_instance();
	
	$sess = $ci->sess->userdata('isAdminLogin');
	
	if($sess){
		
		redirect('dashboard');
		
	}
	
}

function settings(){
	
	$ci = &get_instance();
	
	$result = $ci->db->query('
		SELECT *
		FROM settings
		WHERE id = 1
	');
	
	return $result->row();
}

function slug($text,$table,$colum)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return false;
    }

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT COUNT(id) as tot
		FROM '.$table.'
		WHERE '.$colum.' = "'.$text.'"
	');
    $result = $result->row();

    if($result->tot > 0){

        $num = ($result->tot+1);

        $text = $text.'-'.$num;

    }

    return $text;
}

function role_permitted($only_super_admin = true,$menu = false,$action = false){

    $ci = &get_instance();

    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');

    if($only_super_admin === true){

        if(!$isLogin){

            redirect('auth');

        }else{

            return true;

        }

    }else{
        if($isLogin && $roleType == 1){
            return true;
        }else{
            if($menu === false && $action === false){

                redirect('dashboard');

            }else if($menu !== false && $action !== false){

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.admin_role_id = '.$adminRoleId.'
                      AND m.controller_uri = "'.$menu.'"
                      AND ma.action_uri = "'.$action.'"
                ');

                if($result->num_row() > 0){

                    return true;

                }else{

                    redirect('dashboard');

                }


            }else if($menu !== false && $action === false){

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.admin_role_id = '.$adminRoleId.'
                      AND m.controller_uri = "'.$menu.'"
                      AND arp.menu_action_id = 0
                ');

                if($result->num_row() > 0){

                    return true;

                }else{

                    redirect('dashboard');

                }


            }
        }

    }

}

function role_permitted_html($only_super_admin = true,$menu = false,$action = false){

    $ci = &get_instance();

    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');
    if($only_super_admin === true){

        if(!$isLogin){

            return false;

        }else{

            return true;

        }

    }else{
        if($isLogin && $roleType == 1){
            return true;
        }else{
            if($menu === false && $action === false){

                return false;

            }else if($menu !== false && $action !== false){

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.admin_role_id = '.$adminRoleId.'
                      AND m.controller_uri = "'.$menu.'"
                      AND ma.action_uri = "'.$action.'"
                ');

                if($result->num_rows() > 0){

                    return true;

                }else{

                    return false;

                }


            }else if($menu !== false && $action === false){

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.admin_role_id = '.$adminRoleId.'
                      AND m.controller_uri = "'.$menu.'"
                      AND arp.menu_action_id = 0
                ');
                //echo $ci->db->last_quey();exit;
                if($result->num_rows() > 0){

                    return true;

                }else{

                    return false;

                }


            }
        }

    }

}

function role_permitted_ajax($only_super_admin = true,$menu = false,$action = false){

    $ci = &get_instance();

    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');
    $response = array();

    $response['code'] = 204;
    $response['message'] = 'Invalid user session!';
    $response['error'] = 'Session expire.';
    $response['url'] = base_url().'auth';


    if($only_super_admin === true){

        if(!$isLogin){

            $response['url'] = base_url().'auth';
            return $response;

        }else{

            return false;

        }

    }else{
        if($isLogin && $roleType == 1){
            return false;
        }else{
            if($menu === false && $action === false){

                $response['url'] = base_url().'dashboard';
                return $response;

            }else if($menu !== false && $action !== false){

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.status = 1
                      AND arp.deleted = 0
                      AND arp.admin_role_id = '.$adminRoleId.'
                      AND m.controller_uri = "'.$menu.'"
                      AND ma.action_uri = "'.$action.'"
                ');

                if($result->num_rows() > 0){

                    return false;

                }else{

                    $response['url'] = base_url().'dashboard';
                    return $response;

                }


            }else if($menu !== false && $action === false){

                $result = $ci->db->query('
                    SELECT arp.id
                      FROM admin_role_permissions arp
                      INNER JOIN menus m
                      ON arp.menu_id = m.id
                      AND m.status = 1
                      AND m.deleted = 0
                      INNER JOIN menu_actions ma
                      ON arp.menu_action_id = ma.id
                      AND ma.status = 1
                      AND ma.deleted = 0
                      WHERE arp.admin_role_id = '.$adminRoleId.'
                      AND m.controller_uri = "'.$menu.'"
                      AND arp.menu_action_id = 0
                ');

                if($result->num_row() > 0){

                    return false;

                }else{

                    $response['url'] = base_url().'dashboard';
                    return $response;

                }


            }
        }

    }

}

function getActionsByMenuId($menu_id){

    $ci = &get_instance();

    $result = $ci->db->query('
		SELECT ma.*
		FROM menu_actions ma
		WHERE ma.menu_id = '.$menu_id.'
		AND ma.status = 1
		AND ma.deleted =0
	');

    return $result;
}

function get_user_menu(){
    $ci = &get_instance();
    $isLogin = $ci->sess->userdata('isAdminLogin');
    $roleType = $ci->sess->userdata('adminTypeId');
    $adminRoleId = $ci->sess->userdata('adminRoleId');
    if($isLogin){
        if($roleType == 1){
            $menus = $ci->db->query('
                    SELECT m.*
                    FROM menus m
                    WHERE m.status = 1
                    AND m.deleted = 0
                    GROUP BY m.id
                    ORDER BY m.sort_no ASC 
            ');
        }else{
            $menus = $ci->db->query('
                    SELECT arp.*,m.*
                    FROM admin_role_permissions arp
                    INNER JOIN menus m 
                    ON arp.menu_id = m.id
                    AND m.status = 1
                    AND m.deleted = 0
                    WHERE arp.admin_role_id = '.$adminRoleId.'
                    AND arp.menu_action_id = 0
                    GROUP BY arp.menu_id
                    ORDER BY m.sort_no ASC
            ');
        }
        return $menus;
    }else{
        return false;
    }
}



?>