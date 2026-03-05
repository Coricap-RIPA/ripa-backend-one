<?php

class Article_commande_client_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get article_commande_clients by id_article_commande_client
     */
    function get_article_commande_client($id_article_commande_client)
    {
        return $this->db->get_where('article_commande_client',array('id_article_commande_client'=>$id_article_commande_client))->row_array();
    }


    function get_article_commande_client_by_tel($id_article_commande_client_tel)
    {
        return $this->db->get_where('article_commande_client',array('id_foreign_client_tel'=>$id_article_commande_client_tel))->result_array();
    }
    
        
    /*
     * Get all article_commande_client
     */
    function get_all_article_commande_clients()
    {
        $this->db->order_by('id_article_commande_client', 'desc');
        return $this->db->get('article_commande_client')->result_array();
    }
        
    /*
     * function to add new article_commande_clients
     */
    function add_article_commande_client($params)
    {
        $this->db->insert('article_commande_client',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update article_commande_clients
     */
    function update_article_commande_client($id_article_commande_client,$params)
    {
        $this->db->where('id_article_commande_client',$id_article_commande_client);
        return $this->db->update('article_commande_client',$params);
    }


    function update_article_commande_client_by_tel_id_article($id_foreign_client_tel,$id_foreign_article,$params)
    {
        $this->db->where('id_foreign_client_tel',$id_foreign_client_tel);
        $this->db->where('id_foreign_article',$id_foreign_article);
        return $this->db->update('article_commande_client',$params);
    }
    
    /*
     * function to delete article_commande_clients
     */
    function delete_article_commande_client($token)
    {
        $this->db->where("id_article_commande_client", $token);
        return $this->db->delete("article_commande_client");
    }


    function delete_article_commande_client_by_tel($token)
    {
        $this->db->where("id_foreign_client_tel", $token);
        return $this->db->delete("article_commande_client");
    }

    function delete_article_commande_client_by_tel_id_article($tel_user, $id_article)
    {
        $this->db->where("id_foreign_client_tel", $tel_user);
        $this->db->where("id_foreign_article", $id_article);
        return $this->db->delete("article_commande_client");
    }
}