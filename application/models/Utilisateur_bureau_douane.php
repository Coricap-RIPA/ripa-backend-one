<?php

class Utilisateur_bureau_douane extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get utilisateur_lmc_bureau_douanes by id_utilisateur_lmc_bureau_douane
     */
    function get_utilisateur_lmc_bureau_douane($id_utilisateur_lmc_bureau_douane)
    {
        return $this->db->get_where('utilisateur_lmc_bureau_douane',array('id_utilisateur_lmc_bureau_douane'=>$id_utilisateur_lmc_bureau_douane, 'is_deleted' => 0))->row_array();
    }

    
        
    /*
     * Get all utilisateur_lmc_bureau_douane
     */
    function get_all_utilisateur_lmc_bureau_douane()
    {
        $this->db->order_by('id_utilisateur_lmc_bureau_douane', 'desc');
        return $this->db->get('utilisateur_lmc_bureau_douane')->result_array();
    }
        
    /*
     * function to add new utilisateur_lmc_bureau_douanes
     */
    function add($params)
    {
        $this->db->insert('utilisateur_lmc_bureau_douane',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update utilisateur_lmc_bureau_douanes
     */
    function update($id_utilisateur_lmc_bureau_douane,$params)
    {
        $this->db->where('id_utilisateur_lmc_bureau_douane',$id_utilisateur_lmc_bureau_douane);
        return $this->db->update('utilisateur_lmc_bureau_douane',$params);
    }
  

    function get_utilisateur_lmc_bureau_douane_by_id_utilisateur($id_utilisateur){
        return $this->db->get_where("utilisateur_lmc_bureau_douane", array("id_utilisateur" => $id_utilisateur))->result_array();
    }

    function get_utilisateur_lmc_bureau_douane_by_id_bureau_douane($id_bureau_douane){
        return $this->db->get_where("utilisateur_lmc_bureau_douane", array("id_bureau_douane" => $id_bureau_douane))->result_array();
    }

    function delete_utilisateur_lmc_bureau_douane_by_id_utilisateur($id_utilisateur){
        $this->db->where("id_utilisateur", $id_utilisateur);
        return $this->db->delete("utilisateur_lmc_bureau_douane");
    }
    
}