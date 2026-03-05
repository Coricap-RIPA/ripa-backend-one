<?php

class Article_wish_client_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /*
     * Get article_wish_clients by id_article_wish_client
     */
    function get_article_wish_client($id_article_wish_client)
    {
        return $this->db->get_where('article_wish_client',array('id_article_wish_client'=>$id_article_wish_client))->row_array();
    }


    function get_article_wish_client_by_tel($id_article_wish_client_tel)
    {
        return $this->db->get_where('article_wish_client',array('id_foreign_client_tel'=>$id_article_wish_client_tel))->result_array();
    }
    
        
    /*
     * Get all article_wish_client
     */
    function get_all_article_wish_clients()
    {
        $this->db->order_by('id_article_wish_client', 'desc');
        return $this->db->get('article_wish_client')->result_array();
    }
        
    /*
     * function to add new article_wish_clients
     */
    function add_article_wish_client($params)
    {
        $this->db->insert('article_wish_client',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update article_wish_clients
     */
    function update_article_wish_client($id_article_wish_client,$params)
    {
        $this->db->where('id_article_wish_client',$id_article_wish_client);
        return $this->db->update('article_wish_client',$params);
    }


    function update_article_wish_client_by_tel_id_article($id_foreign_client_tel,$id_foreign_article,$params)
    {
        $this->db->where('id_foreign_client_tel',$id_foreign_client_tel);
        $this->db->where('id_foreign_article',$id_foreign_article);
        return $this->db->update('article_wish_client',$params);
    }
    
    /*
     * function to delete article_wish_clients
     */
    function delete_article_wish_client($token)
    {
        $this->db->where("id_article_wish_client", $token);
        return $this->db->delete("article_wish_client");
    }


    function delete_article_wish_client_by_tel($token)
    {
        $this->db->where("id_foreign_client_tel", $token);
        return $this->db->delete("article_wish_client");
    }

    function delete_article_wish_client_by_tel_id_article($tel_user, $id_article)
    {
        $this->db->where("id_foreign_client_tel", $tel_user);
        $this->db->where("id_foreign_article", $id_article);
        return $this->db->delete("article_wish_client");
    }
}