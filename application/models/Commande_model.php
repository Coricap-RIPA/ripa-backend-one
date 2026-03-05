<?php

class Commande_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
  
    function get_commandes($id_commande)
    {
        return $this->db->get_where('commande',array('id_commande'=>$id_commande))->row_array();
    }


    function get_all_commande()
    {
        $this->db->order_by('id_commande', 'desc');
        return $this->db->get('commande')->result_array();
    }
        
    
    function add_commande($params)
    {
        $this->db->insert('commande',$params);
        return $this->db->insert_id();
    }
    
    
    function update_commande($id_commande,$params)
    {
        $this->db->where('id_commande',$id_commande);
        return $this->db->update('commande',$params);
    }


    function update_commande_by_status_read_commande($id_status_read_commande,$params)
    {
        $this->db->where('status_read_commande',$id_status_read_commande);
        return $this->db->update('commande',$params);
    }


    function update_commande_by_status_read_commande_by_id_fournisseur($id_fournisseur){
        $query = $this->db->query("UPDATE commande  INNER JOIN  article_commande  ON commande.id_commande = article_commande.id_foreign_commande  SET commande.status_read_commande = 1 WHERE article_commande.id_foreign_fournisseur_commande = $id_fournisseur ");
        return $query;
    }
    
    
    function delete_commande($token)
    {
        $this->db->where("id_commande", $token);
        return $this->db->delete("commande");
    }


    function get_commandes_not_read()
    {
        return $this->db->get_where('commande',array('status_read_commande'=>0))->row_array();
    }

    function get_commandes_not_read_by_id_fournisseur($id_fournisseur){
        $query = $this->db->query("SELECT * FROM commande INNER JOIN article_commande ON commande.id_commande=article_commande.id_foreign_commande WHERE article_commande.id_foreign_fournisseur_commande=$id_fournisseur AND commande.status_read_commande=0")->result_array();
        return $query;
    }

    function get_commande_by_periode($start_date,$end_date){
        $query = $this->db->query("SELECT * FROM commande WHERE date_commande BETWEEN '$start_date' AND '$end_date'")->result_array();
        return $query;
    }

}