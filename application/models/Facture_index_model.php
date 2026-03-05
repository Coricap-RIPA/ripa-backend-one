<?php

    class Facture_index_model extends CI_Model{

        function add($params){
            $this->db->insert("facture_index", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_facture_index", $id);
            return $this->db->update("facture_index", $params);
        }

        function update_by_id_foreign_entreprise($id, $params){
            $this->db->where("id_foreign_entreprise", $id);
            return $this->db->update("facture_index", $params);
        }


        function get_facture_index_by_token($token){
            return $this->db->get_where('facture_index',array('id_facture_index'=>$token))->row_array();
        }

        
        function get_last_facture_index_by_id_foreign_entreprise($id_foreign_entreprise)
        {
            $query = $this->db->query("SELECT * FROM facture_index WHERE id_foreign_entreprise = $id_foreign_entreprise ORDER BY id_facture_index DESC LIMIT 1")->row_array();
            return $query;
        }

        function delete_facture_index($token){
            $this->db->where("id_facture_index", $token);
            return $this->db->delete("facture_index");
        }

        function delete_by_id_foreign_entreprise($token){
            $this->db->where("id_foreign_entreprise", $token);
            return $this->db->delete("facture_index");
        }
    }

?>