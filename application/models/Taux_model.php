<?php

class Taux_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_taux_echange($id_taux_echange)
    {
        return $this->db->get_where('taux_echange',array('id_taux_echange'=>$id_taux_echange))->row_array();
    }


    function get_all_taux_echange()
    {
        $this->db->order_by('id_taux_echange', 'desc');
        return $this->db->get('taux_echange')->result_array();
    }

    function get_last_taux_echange()
    {
        return $query = $this->db->query("select *from taux_echange ORDER BY id_taux_echange DESC LIMIT 1")->row_array();
    }
        
   
    function add_taux_echanges($params)
    {
        $this->db->insert('taux_echange',$params);
        return $this->db->insert_id();
    }
    
 
    function update_taux_echanges($id_taux_echange,$params)
    {
        $this->db->where('id_taux_echange',$id_taux_echange);
        return $this->db->update('taux_echange',$params);
    }


    function update_status_taux_echanges($params)
    {
        return $this->db->update('taux_echange',$params);
    }
    


    function delete_taux_echange($id_taux_echange)
    {
        $this->db->where("id_taux_echange", $id_taux_echange);
        return $this->db->delete("taux_echange");
    }
}