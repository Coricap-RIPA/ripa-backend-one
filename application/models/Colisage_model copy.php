<?php

class Colisage_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get sexes by id_sexe
     */
    function get_colisage($id_type_colisage)
    {
        return $this->db->get_where('type_colisage',array('id_type_colisage'=>$id_type_colisage, 'is_deleted' => 0))->row_array();
    }

    function get_colisage_by_token($token){
        return $this->db->get_where('type_colisage',array('token_colisage'=>$token, 'is_deleted' => 0))->row_array();
    }
        
    /*
     * Get all sexe
     */
    function get_all_colisage()
    {
        $this->db->where("is_deleted", 0);
        $this->db->order_by('id_type_colisage', 'desc');
        return $this->db->get('type_colisage')->result_array();
    }
        
    /*
     * function to add new sexes
     */
    function add_colisage($params)
    {
        $this->db->insert('type_colisage',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update sexes
     */
    function update_colisage($id_colisage,$params)
    {
        $this->db->where('id_type_colisage',$id_colisage);
        return $this->db->update('type_colisage',$params);
    }
    
    /*
     * function to delete sexes
     */
    function delete_colisage($token)
    {
        $this->db->where("token_colisage", $token);
        $this->db->set("is_deleted", 1);
        return $this->db->update("type_colisage");
    }
}