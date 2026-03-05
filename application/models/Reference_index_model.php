<?php

    class Reference_index_model extends CI_Model{

        function add($params){
            $this->db->insert("reference_index", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_reference_index", $id);
            return $this->db->update("reference_index", $params);
        }

        function update_by_id_foreign_entreprise($id, $params){
            $this->db->where("id_foreign_entreprise", $id);
            return $this->db->update("reference_index", $params);
        }



        function get_reference_index_by_token($token){
            return $this->db->get_where('reference_index',array('id_reference_index'=>$token))->row_array();
        }

        function get_last_reference_index_by_id_foreign_entreprise($id_foreign_entreprise)
        {
            $query = $this->db->query("SELECT * FROM reference_index WHERE id_foreign_entreprise = $id_foreign_entreprise ORDER BY id_reference_index DESC LIMIT 1")->row_array();
            return $query;
        }

        function delete_reference_index($token){
            $this->db->where("id_reference_index", $token);
            return $this->db->delete("reference_index");
        }

        function delete_by_id_foreign_entreprise($token){
            $this->db->where("id_foreign_entreprise", $token);
            return $this->db->delete("reference_index");
        }
    }

?>