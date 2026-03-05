<?php

    class Unite_facturation_model extends CI_Model{
        function add($params){
            $this->db->insert("unite_facturation", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_unite_facturation", $id);
            return $this->db->update("unite_facturation", $params);
        }

        function get_all_unites(){
            $this->db->where('is_deleted', 0);
            $this->db->order_by('id_unite_facturation', 'desc');
            return $this->db->get('unite_facturation')->result_array();
        }

        function get_unite_by_token($token){
            return $this->db->get_where('unite_facturation',array('token_unite_facturation'=>$token, 'is_deleted' => 0))->row_array();
        }

        function get_unite($id){
            return $this->db->get_where('unite_facturation',array('id_unite_facturation'=>$id, 'is_deleted' => 0))->row_array();
        }

        function delete_unite($token){
            $this->db->where("token_unite_facturation", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("unite_facturation");
        }
    }

?>