<?php

class Couple_article_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get couple_articles by id_couple_article
     */
    function get_couple_article($id_couple_article)
    {
        return $this->db->get_where('couple_article',array('id_couple_article'=>$id_couple_article))->row_array();
    }

    
        
    /*
     * Get all couple_article
     */
    function get_all_couple_articles()
    {
        $this->db->order_by('id_couple_article', 'desc');
        return $this->db->get('couple_article')->result_array();
    }
        
    /*
     * function to add new couple_articles
     */
    function add_couple_article($params)
    {
        $this->db->insert('couple_article',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update couple_articles
     */
    function update_couple_article($id_couple_article,$params)
    {
        $this->db->where('id_couple_article',$id_couple_article);
        return $this->db->update('couple_article',$params);
    }
    
    /*
     * function to delete couple_articles
     */
    function delete_couple_article($token)
    {
        $this->db->where("id_couple_article", $token);
        return $this->db->delete("couple_article");
    }


    public function get_couple_article_by_id_commande($id_commande)
    {
        $query = $this->db->query("SELECT * FROM couple_article WHERE id_foreign_commande=$id_commande")->result_array();
        return $query;
    }

    public function get_couple_article_by_id_article($id_article)
    {
        $query = $this->db->query("SELECT * FROM couple_article WHERE id_foreign_article=$id_article")->result_array();
        return $query;
    }

    public function get_couple_article_by_id_fournisseur($id_fournisseur)
    {
        $query = $this->db->query("SELECT * FROM couple_article WHERE id_foreign_fournisseur_commande=$id_fournisseur")->result_array();
        return $query;
    }


    public function get_couple_article_by_id_fournisseur_group_by_id_commande($id_fournisseur)
    {
        $query = $this->db->query("SELECT * FROM couple_article WHERE id_foreign_fournisseur_commande=$id_fournisseur GROUP BY id_foreign_commande")->result_array();
        return $query;
    }


    public function get_couple_article_by_id_commande_group_by_id_fournisseur($id_commande)
    {
        $query = $this->db->query("SELECT * FROM couple_article WHERE id_foreign_commande=$id_commande GROUP BY id_foreign_fournisseur_commande")->result_array();
        return $query;
    }

    public function get_couple_article_by_id_fournisseur_id_commande_id_article($id_fournisseur,$id_commande,$id_article)
    {
        $query = $this->db->query("SELECT * FROM couple_article WHERE id_foreign_fournisseur_commande=$id_fournisseur AND id_foreign_commande=$id_commande AND id_foreign_article=$id_article")->result_array();
        return $query;
    }
    
}