<?php

    class Role_model extends CI_Model{

        function add($param){
            $this->db->insert("role", $param);
            return $this->db->insert_id();
        }

        function update($param, $id_role){
            $this->db->where("id_role", $id_role);
            $this->db->update("role", $param);
        }

        function get_all_roles(){
            return $this->db->get_where("role", array("is_deleted" => 0))->result_array();
        }

        function get_role_by_token($token){
            return $this->db->get_where("role", array("token_role" => $token, "is_deleted" => 0))->row_array();
        }

        function get_role($id_role){
            return $this->db->get_where("role", array("id_role" => $id_role))->row_array();
        }

        function delete($token){
            $this->db->where("token_role", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("role");
        }

        function get_roles_by_elligilite_type_entreprise($type){
            $query = $this->db->query("SELECT * FROM role WHERE (elligible_type_entreprise = $type OR elligible_type_entreprise = 0) AND is_deleted = 0")->result_array();
            return $query;
        }

        function get_roles_for_insertion(){
            $query = $this->db->query("SELECT * FROM role WHERE id_role > 1 AND is_deleted = 0")->result_array();
            return $query;
        }

    }

?>