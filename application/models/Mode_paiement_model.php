<?php

    class Mode_paiement_model extends CI_Model{

        function add($params){
            $this->db->insert("mode_paiement", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_mode_paiement", $id);
            return $this->db->update("mode_paiement", $params);
        }

        function get_all_mode_paiements(){
            $this->db->where('is_deleted', 0);
            $this->db->order_by('id_mode_paiement', 'desc');
            return $this->db->get('mode_paiement')->result_array();
        }

        function get_mode_by_token($token){
            return $this->db->get_where('mode_paiement',array('token_mode_paiement'=>$token, 'is_deleted' => 0))->row_array();
        }

        function get_mode($id){
            return $this->db->get_where('mode_paiement',array('id_mode_paiement'=>$id, 'is_deleted' => 0))->row_array();
        }

        function delete_mode_paiement($token){
            $this->db->where("token_mode_paiement", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("mode_paiement");
        }

    }

?>