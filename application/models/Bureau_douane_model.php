<?php

    class Bureau_douane_model extends CI_Model{
        function add($params){
            $this->db->insert("bureau_douane", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_bureau_douane", $id);
            return $this->db->update("bureau_douane", $params);
        }

        function get_all_bureaux(){
            //$this->db->where('is_deleted', 0);
            $this->db->order_by('id_bureau_douane', 'desc');
            return $this->db->get('bureau_douane')->result_array();
        }

        function get_bureau_by_token($token){
            return $this->db->get_where('bureau_douane',array('token_bureau_douane'=>$token, 'is_deleted' => 0))->row_array();
        }

        function get_bureau($id){
            return $this->db->get_where('bureau_douane',array('id_bureau_douane'=>$id, 'is_deleted' => 0))->row_array();
        }

        function delete_bureau($token){
            $this->db->where("token_bureau_douane", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("bureau_douane");
        }
    }

?>