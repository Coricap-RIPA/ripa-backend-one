<?php

    class Categorie_sous_categorie_model extends CI_Model{

        function add($params){
            $this->db->insert("categorie_sous_categorie", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_categorie_sous_categorie", $id);
            return $this->db->update("categorie_sous_categorie", $params);
        }

        function get_all_categorie_sous_categories(){
            $this->db->order_by('id_categorie_sous_categorie', 'desc');
            return $this->db->get('categorie_sous_categorie')->result_array();
        }

        function get_all_categorie_from_categorie_sous_categories($id_categorie){
            $this->db->where("id_categorie", $id_categorie);
            return $this->db->get('categorie_sous_categorie')->result_array();
        }


        function get_all_sous_categorie_from_categorie_sous_categories($id_sous_categorie){
            $this->db->where("id_sous_categorie", $id_sous_categorie);
            return $this->db->get('categorie_sous_categorie')->result_array();
        }
       

        function get_categorie_sous_categories($id){
            return $this->db->get_where('categorie_sous_categorie',array('id_categorie_sous_categorie'=>$id))->row_array();
        }

        function delete_categorie_sous_categories($token){
            $this->db->where("id_categorie_sous_categorie", $token);
            return $this->db->delete("categorie_sous_categorie");
        }

    }

?>