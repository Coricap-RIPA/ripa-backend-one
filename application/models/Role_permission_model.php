<?php

    class Role_permission_model extends CI_Model{

        function add($params){
            return $this->db->insert("role_permission", $params);
        }

        function update($params, $idrole, $idfonctionnalite){
            $this->db->where("id_role", $idrole);
            $this->db->where("id_fonctionnalite", $idfonctionnalite);
            return $this->db->update("role_permission", $params);
        }

        function get_permission_by_idfonctionnalite_and_idrole($idrole, $idfonctionnalite){
            return $this->db->get_where("role_permission", array("id_role" => $idrole, "id_fonctionnalite" => $idfonctionnalite))->row_array();
        }

    }

?>