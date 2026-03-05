<?php

    class Fournisseur_model extends CI_Model{

        function add($params){
            $this->db->insert("fournisseur", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_fournisseur", $id);
            return $this->db->update("fournisseur", $params);
        }

        function get_all_fournisseurs(){
            $this->db->order_by('id_fournisseur', 'asc');
            return $this->db->get('fournisseur')->result_array();
        }

        function get_fournisseurs_for_home(){
            $this->db->order_by('id_fournisseur', 'asc');
            return $this->db->get('fournisseur')->result_array();
        }

        
        function get_fournisseur($id){
            return $this->db->get_where('fournisseur',array('id_fournisseur'=>$id))->row_array();
        }

        function delete_fournisseur($token){
            $this->db->where("id_fournisseur", $token);
            return $this->db->delete("fournisseur");
        }

    }

?>