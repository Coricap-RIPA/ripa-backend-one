<?php

class Importateur_exprotateur_transitaire_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get importateur_exprotateur_transitaires by id_importateur_exprotateur_transitaire
     */
    function get_importateur_exprotateur_transitaires($id_importateur_exprotateur_transitaire)
    {
        return $this->db->get_where('importateur_exprotateur_transitaire',array('id_imp_exp_trans'=>$id_importateur_exprotateur_transitaire, 'is_deleted' => 0))->row_array();
    }

    function get_importateur_exprotateur_transitaire_by_token($token){
        return $this->db->get_where('importateur_exprotateur_transitaire',array('token_importateur_exprotateur_transitaire'=>$token, 'is_deleted' => 0))->row_array();
    }
        
    /*
     * Get all importateur_exprotateur_transitaire
     */
    function get_all_importateur_exprotateur_transitaire()
    {
        $this->db->order_by('id_imp_exp_trans', 'desc');
        return $this->db->get('importateur_exprotateur_transitaire')->result_array();
    }
        
    /*
     * function to add new importateur_exprotateur_transitaires
     */
    function add($params)
    {
        $this->db->insert('importateur_exprotateur_transitaire',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update importateur_exprotateur_transitaires
     */
    function update($id_importateur_exprotateur_transitaire,$params)
    {
        $this->db->where('id_imp_exp_trans',$id_importateur_exprotateur_transitaire);
        return $this->db->update('importateur_exprotateur_transitaire',$params);
    }
    
    /*
     * function to delete importateur_exprotateur_transitaires
     */
    function delete($token)
    {
        $this->db->where("token_importateur_exprotateur_transitaire", $token);
        $this->db->set("is_deleted", 1);
        return $this->db->update("importateur_exprotateur_transitaire");
    }

    function get_importateur_exportateur($id_transitaire){
        return $this->db->get_where("importateur_exprotateur_transitaire", array("id_transitaire" => $id_transitaire))->result_array();
    }

    function get_importateur_exportateur_by_idtransitaire_idimportexport($id_transitaire,$id_import_export){
        return $this->db->get_where("importateur_exprotateur_transitaire", array("id_transitaire" => $id_transitaire,'id_imp_exp'=>$id_import_export))->row_array();
    }

    function delete_importateur_exportateur_by_idtransitaire($id_transitaire){
        $this->db->where("id_transitaire", $id_transitaire);
        return $this->db->delete("importateur_exprotateur_transitaire");
    }
    
}