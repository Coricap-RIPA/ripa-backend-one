<?php

class Compte_money_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_compte_money_entreprise($id_compte_money_entreprise)
    {
        return $this->db->get_where('compte_money_entreprise',array('id_compte_money_entreprise'=>$id_compte_money_entreprise))->row_array();
    }

    function get_compte_money_by_entreprise($id_entreprise_cliente)
    {
        return $this->db->get_where('compte_money_entreprise',array('id_entreprise_cliente'=>$id_entreprise_cliente))->row_array();
    }

    function get_compte_money_by_num_mobile_money($num_mobile_money)
    {
        return $this->db->get_where('compte_money_entreprise',array('num_mobile_money'=>$num_mobile_money))->row_array();
    }

    function get_active_compte_money_by_entreprise($id_entreprise_cliente)
    {
        return $this->db->get_where('compte_money_entreprise',array('id_entreprise_cliente'=>$id_entreprise_cliente,'is_active'=>1))->result_array();
    }


    function get_all_compte_money_entreprise()
    {
        $this->db->order_by('id_compte_money_entreprise', 'desc');
        return $this->db->get('compte_money_entreprise')->result_array();
    }
        
   
    function add_compte_money_entreprises($params)
    {
        $this->db->insert('compte_money_entreprise',$params);
        return $this->db->insert_id();
    }
    
 
    function update_compte_money_entreprises($id_compte_money_entreprise,$params)
    {
        $this->db->where('id_compte_money_entreprise',$id_compte_money_entreprise);
        return $this->db->update('compte_money_entreprise',$params);
    }


    function update_status_compte_money_entreprises($params)
    {
        return $this->db->update('compte_money_entreprise',$params);
    }
    


    function delete_compte_money_entreprise($id_compte_money_entreprise)
    {
        $this->db->where("id_compte_money_entreprise", $id_compte_money_entreprise);
        return $this->db->delete("compte_money_entreprise");
    }
}