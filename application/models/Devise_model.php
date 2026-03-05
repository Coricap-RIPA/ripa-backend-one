<?php

    class Devise_model extends CI_Model{

        function add($params){
            $this->db->insert("devise", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_devise", $id);
            return $this->db->update("devise", $params);
        }

        function get_all_devises(){
            $this->db->where('is_deleted', 0);
            $this->db->order_by('id_devise', 'desc');
            return $this->db->get('devise')->result_array();
        }

        function get_devise_by_token($token){
            return $this->db->get_where('devise',array('token_devise'=>$token))->row_array();
        }

        function get_devise($id){
            return $this->db->get_where('devise',array('id_devise'=>$id))->row_array();
        }

        function get_devise_by_abreviation($abreviation){
            return $this->db->get_where('devise',array('abreviation'=>$abreviation))->row_array();
        }

        function delete_devise($token){
            $this->db->where("token_devise", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("devise");
        }

    }

?>