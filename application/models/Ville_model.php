<?php

    class Ville_model extends CI_Model{

        function add($params){
            $this->db->insert("ville", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_ville", $id);
            return $this->db->update("ville", $params);
        }

        function get_all_villes(){
            $this->db->where('is_deleted', 0);
            $this->db->order_by('id_ville', 'desc');
            return $this->db->get('ville')->result_array();
        }

        function get_ville_by_token($token){
            return $this->db->get_where('ville',array('token_ville'=>$token, 'is_deleted' => 0))->row_array();
        }

        function get_ville($id){
            return $this->db->get_where('ville',array('id_ville'=>$id, 'is_deleted' => 0))->row_array();
        }

        function delete_ville($token){
            $this->db->where("token_ville", $token);
            $this->db->set('is_deleted', 1);
            return $this->db->update("ville");
        }

    }

?>