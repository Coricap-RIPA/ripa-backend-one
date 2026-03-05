<?php

    class Sous_categorie_model extends CI_Model{

        function add($params){
            $this->db->insert("sous_categorie", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_sous_categorie", $id);
            return $this->db->update("sous_categorie", $params);
        }

        function get_all_sous_categories(){
            $this->db->order_by('id_sous_categorie', 'desc');
            return $this->db->get('sous_categorie')->result_array();
        }

       

        function get_sous_categorie($id){
            return $this->db->get_where('sous_categorie',array('id_sous_categorie'=>$id))->row_array();
        }

        function delete_sous_categorie($token){
            $this->db->where("id_sous_categorie", $token);
            return $this->db->delete("sous_categorie");
        }

    }

?>