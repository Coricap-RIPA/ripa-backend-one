<?php

    class Pays_model extends CI_Model{

        function add($params){
            $this->db->insert("pays", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_pays", $id);
            return $this->db->update("pays", $params);
        }

        function get_all_pays(){
            $this->db->where('is_deleted', 0);
            $this->db->order_by('id_pays', 'desc');
            return $this->db->get('pays')->result_array();
        }

        function get_pays_by_token($token){
            return $this->db->get_where('pays',array('token_pays'=>$token, 'is_deleted' => 0))->row_array();
        }

        function get_pays($id){
            return $this->db->get_where('pays',array('id_pays'=>$id, 'is_deleted' => 0))->row_array();
        }

        function delete_pays($token){
            $this->db->where("token_pays", $token);
            $this->db->set('is_deleted', 1);
            return $this->db->update("pays");
        }

    }

?>