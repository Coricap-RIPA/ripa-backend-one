<?php

class Type_mobile_money_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_type_mobile_money($id_type_mobile_money)
    {
        return $this->db->get_where('type_mobile_money',array('id_type_mobile_money'=>$id_type_mobile_money))->row_array();
    }


    function get_all_type_mobile_money()
    {
        $this->db->order_by('id_type_mobile_money', 'desc');
        return $this->db->get('type_mobile_money')->result_array();
    }
        
   
    function add_type_mobile_moneys($params)
    {
        $this->db->insert('type_mobile_money',$params);
        return $this->db->insert_id();
    }
    
 
    function update_type_mobile_moneys($id_type_mobile_money,$params)
    {
        $this->db->where('id_type_mobile_money',$id_type_mobile_money);
        return $this->db->update('type_mobile_money',$params);
    }


    function update_status_type_mobile_moneys($params)
    {
        return $this->db->update('type_mobile_money',$params);
    }
    


    function delete_type_mobile_money($id_type_mobile_money)
    {
        $this->db->where("id_type_mobile_money", $id_type_mobile_money);
        return $this->db->delete("type_mobile_money");
    }
}