<?php

    class Marchand_model extends CI_Model{

        function add($params){
            $this->db->insert("marchand_fiche", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_marchand_fiche", $id);
            return $this->db->update("marchand_fiche", $params);
        }

        function get_all_marchand_fiches(){
            $this->db->order_by('id_marchand_fiche', 'desc');
            return $this->db->get('marchand_fiche')->result_array();
        }


        function get_marchand_fiche($id){
            return $this->db->get_where('marchand_fiche',array('id_marchand_fiche'=>$id))->row_array();
        }

        function get_marchand_fiche_by_array($array_marchand_fiche){
            return $this->db->get_where('marchand_fiche',$array_marchand_fiche)->row_array();
        }

        function delete_marchand_fiche($token){
            $this->db->where("id_marchand_fiche", $token);
            return $this->db->delete("marchand_fiche");
        }

    }

?>