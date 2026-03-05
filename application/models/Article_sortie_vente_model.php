<?php

class Article_sortie_vente_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_article_sortie_vente($id_article_sortie_vente)
    {
        return $this->db->get_where('article_sortie_vente',array('id_article_sortie_vente'=>$id_article_sortie_vente))->row_array();
    }


    function get_article_sortie_vente_by_date($start_date,$end_date)
    {
        $query = $this->db->query("SELECT * FROM article_sortie_vente WHERE date_enregistrement_article_sortie_vente BETWEEN '$start_date' AND '$end_date'")->result_array();
        return $query;
    }


    function get_article_sortie_vente_by_stock_sortie_id($id)
    {
        return $this->db->get_where('article_sortie_vente',array('id_sortie_stock'=>$id))->result_array();
    }
        
    function get_all_article_sortie_vente()
    {
        $this->db->order_by('id_article_sortie_vente', 'desc');
        return $this->db->get('article_sortie_vente')->result_array();
    }
        
   
    function add_article_sortie_vente($params)
    {
        $this->db->insert('article_sortie_vente',$params);
        return $this->db->insert_id();
    }
    
  
    function update_article_sortie_vente($id_article_sortie_vente,$params)
    {
        $this->db->where('id_article_sortie_vente',$id_article_sortie_vente);
        return $this->db->update('article_sortie_vente',$params);
    }
    

    function delete_article_sortie_vente($id_article_sortie_vente)
    {
        $this->db->where("id_article_sortie_vente", $id_article_sortie_vente);
        return $this->db->delete("article_sortie_vente");
    }


    function delete_article_sortie_vente_by_id_sortie_stock($id_sortie_stock)
    {
        $this->db->where("id_sortie_stock", $id_sortie_stock);
        return $this->db->delete("article_sortie_vente");
    }


}