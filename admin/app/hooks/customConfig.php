<?php
class customConfig{
function getConfigurations(){
    $CI =& get_instance();

    $row = $CI->db->get('configurations')->row();

    if($row) {

        foreach($row as $key=>$appConfigOption)
        {


            $CI->config->set_item($key,$appConfigOption);

        }

    }
   /* echo "wut wut";
    die;*/
}
}

