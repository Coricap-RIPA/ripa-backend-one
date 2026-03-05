<?php

    class Mode_transport_model extends CI_Model{

        function add($params){
            $this->db->insert("mode_transport", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_mode_transport", $id);
            return $this->db->update("mode_transport", $params);
        }

        function get_all_mode_transports(){
            $this->db->where('is_deleted', 0);
            $this->db->order_by('id_mode_transport', 'desc');
            return $this->db->get('mode_transport')->result_array();
        }

        function get_mode_by_token($token){
            return $this->db->get_where('mode_transport',array('token_mode_transport'=>$token, 'is_deleted' => 0))->row_array();
        }

        function get_mode($id){
            return $this->db->get_where('mode_transport',array('id_mode_transport'=>$id, 'is_deleted' => 0))->row_array();
        }

        function delete_mode_transport($token){
            $this->db->where("token_mode_transport", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("mode_transport");
        }

    }

?>