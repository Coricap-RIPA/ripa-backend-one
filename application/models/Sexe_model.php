<?php

class Sexe_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get sexes by id_sexe
     */
    function get_sexes($id_sexe)
    {
        return $this->db->get_where('sexe',array('id_sexe'=>$id_sexe))->row_array();
    }

    function get_sexe_by_token($token){
        return $this->db->get_where('sexe',array('token_sexe'=>$token))->row_array();
    }
        
    /*
     * Get all sexe
     */
    function get_all_sexe()
    {
        $this->db->where("is_deleted", 0);
        $this->db->order_by('id_sexe', 'desc');
        return $this->db->get('sexe')->result_array();
    }
        
    /*
     * function to add new sexes
     */
    function add_sexes($params)
    {
        $this->db->insert('sexe',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update sexes
     */
    function update_sexes($id_sexe,$params)
    {
        $this->db->where('id_sexe',$id_sexe);
        return $this->db->update('sexe',$params);
    }
    
    /*
     * function to delete sexes
     */
    function delete_sexe($token)
    {
        $this->db->where("token_sexe", $token);
        $this->db->set("is_deleted", 1);
        return $this->db->update("sexe");
    }
}