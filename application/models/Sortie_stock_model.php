<?php

class Sortie_stock_model extends CI_Model
{
    
    function __construct()
    {
        parent::__construct();
    }
    

    function get_sortie_stock($id_sortie_stock)
    {
        return $this->db->get_where('sortie_stock',array('id_sortie_stock'=>$id_sortie_stock))->row_array();
    }


    function get_sortie_stock_by_date($start_date,$end_date)
    {
        $query = $this->db->query("SELECT * FROM sortie_stock INNER JOIN article_sortie_vente ON sortie_stock.id_sortie_stock = article_sortie_vente.id_sortie_stock WHERE sortie_stock.date_enregistrement_sortie_stock BETWEEN '$start_date' AND '$end_date'")->result_array();
        return $query;
    }

    
        
    function get_all_sortie_stock()
    {
        $this->db->order_by('id_sortie_stock', 'desc');
        return $this->db->get('sortie_stock')->result_array();
    }
        
   
    function add_sortie_stock($params)
    {
        $this->db->insert('sortie_stock',$params);
        return $this->db->insert_id();
    }
    
  
    function update_sortie_stock($id_sortie_stock,$params)
    {
        $this->db->where('id_sortie_stock',$id_sortie_stock);
        return $this->db->update('sortie_stock',$params);
    }
    

    function delete_sortie_stock($id_sortie_stock)
    {
        $this->db->where("id_sortie_stock", $id_sortie_stock);
        return $this->db->delete("sortie_stock");
    }


    function get_sortie_stock_by_query($sql)
    {
        $query = $this->db->query("SELECT * FROM sortie_stock WHERE ".$sql)->result_array();
        return $query;
    }

    function get_sortie_stock_by_date_id_article($start_date,$end_date,$id_article)
    {
        $query = $this->db->query("SELECT * FROM sortie_stock INNER JOIN article_sortie_vente ON sortie_stock.id_sortie_stock = article_sortie_vente.id_sortie_stock WHERE article_sortie_vente.id_article=$id_article AND sortie_stock.date_enregistrement_sortie_stock BETWEEN '$start_date' AND '$end_date' ")->result_array();
        return $query;
    }
}