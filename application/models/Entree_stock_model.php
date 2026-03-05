<?php

class Entree_stock_model extends CI_Model
{


    function __construct()
    {
        parent::__construct();
    }
    

    function get_entre_stock($id_entre_stock)
    {
        return $this->db->get_where('entre_stock',array('id_entre_stock'=>$id_entre_stock))->row_array();
    }


    function get_entre_stock_by_date($start_date,$end_date)
    {
        $query = $this->db->query("SELECT * FROM entre_stock WHERE date_enregistrement_entre_stock BETWEEN '$start_date' AND '$end_date'")->result_array();
        return $query;
    }
        
    function get_all_entre_stock()
    {
        $this->db->order_by('id_entre_stock', 'desc');
        return $this->db->get('entre_stock')->result_array();
    }
        
   
    function add_entre_stock($params)
    {
        $this->db->insert('entre_stock',$params);
        return $this->db->insert_id();
    }
    
  
    function update_entre_stock($id_entre_stock,$params)
    {
        $this->db->where('id_entre_stock',$id_entre_stock);
        return $this->db->update('entre_stock',$params);
    }
    
    function get_last_entree_stock_by_id_article($id_article)
    {
        $query = $this->db->query("SELECT * FROM entre_stock WHERE id_article = $id_article ORDER BY id_entre_stock DESC LIMIT 1")->row_array();
        return $query;
    }

    function delete_entre_stock($id_entre_stock)
    {
        $this->db->where("id_entre_stock", $id_entre_stock);
        return $this->db->delete("type_entre_stock");
    }


    function get_entre_stock_by_query($sql)
    {
        $query = $this->db->query("SELECT * FROM entre_stock WHERE ".$sql)->result_array();
        return $query;
    }


    function get_entre_stock_by_date_id_article($start_date,$end_date,$id_article)
    {
        $query = $this->db->query("SELECT * FROM entre_stock WHERE date_enregistrement_entre_stock BETWEEN '$start_date' AND '$end_date' AND id_article=$id_article")->result_array();
        return $query;
    }

}