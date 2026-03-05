<?php

class Status_commande_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get status_commandes by id_status_commande
     */
    function get_status_commande($id_status_commande)
    {
        return $this->db->get_where('status_commande',array('id_status_commande'=>$id_status_commande))->row_array();
    }

 
        

    function get_all_status_commande()
    {
        $this->db->order_by('id_status_commande', 'desc');
        return $this->db->get('status_commande')->result_array();
    }
        
   

    function add_status_commandes($params)
    {
        $this->db->insert('status_commande',$params);
        return $this->db->insert_id();
    }
    
   
    function update_status_commandes($id_status_commande,$params)
    {
        $this->db->where('id_status_commande',$id_status_commande);
        return $this->db->update('status_commande',$params);
    }
    

   
    function delete_status_commande($token)
    {
        $this->db->where("token_status_commande", $token);
        return $this->db->delete("status_commande");
    }


}