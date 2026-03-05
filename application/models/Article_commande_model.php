<?php

class Article_commande_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get article_commandes by id_article_commande
     */
    function get_article_commande($id_article_commande)
    {
        return $this->db->get_where('article_commande',array('id_article_commande'=>$id_article_commande))->row_array();
    }

    
        
    /*
     * Get all article_commande
     */
    function get_all_article_commandes()
    {
        $this->db->order_by('id_article_commande', 'desc');
        return $this->db->get('article_commande')->result_array();
    }
        
    /*
     * function to add new article_commandes
     */
    function add_article_commande($params)
    {
        $this->db->insert('article_commande',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update article_commandes
     */
    function update_article_commande($id_article_commande,$params)
    {
        $this->db->where('id_article_commande',$id_article_commande);
        return $this->db->update('article_commande',$params);
    }
    
    /*
     * function to delete article_commandes
     */
    function delete_article_commande($token)
    {
        $this->db->where("id_article_commande", $token);
        return $this->db->delete("article_commande");
    }


    public function get_article_commande_by_id_commande($id_commande)
    {
        $query = $this->db->query("SELECT * FROM article_commande WHERE id_foreign_commande=$id_commande")->result_array();
        return $query;
    }

    public function get_article_commande_by_id_article($id_article)
    {
        $query = $this->db->query("SELECT * FROM article_commande WHERE id_foreign_article=$id_article")->result_array();
        return $query;
    }

    public function get_article_commande_by_id_fournisseur($id_fournisseur)
    {
        $query = $this->db->query("SELECT * FROM article_commande WHERE id_foreign_fournisseur_commande=$id_fournisseur")->result_array();
        return $query;
    }


    public function get_article_commande_by_id_fournisseur_group_by_id_commande($id_fournisseur)
    {
        $query = $this->db->query("SELECT * FROM article_commande WHERE id_foreign_fournisseur_commande=$id_fournisseur GROUP BY id_foreign_commande")->result_array();
        return $query;
    }


    public function get_article_commande_by_id_commande_group_by_id_fournisseur($id_commande)
    {
        $query = $this->db->query("SELECT * FROM article_commande WHERE id_foreign_commande=$id_commande GROUP BY id_foreign_fournisseur_commande")->result_array();
        return $query;
    }

    public function get_article_commande_by_id_fournisseur_id_commande_id_article($id_fournisseur,$id_commande,$id_article)
    {
        $query = $this->db->query("SELECT * FROM article_commande WHERE id_foreign_fournisseur_commande=$id_fournisseur AND id_foreign_commande=$id_commande AND id_foreign_article=$id_article")->result_array();
        return $query;
    }
    
}