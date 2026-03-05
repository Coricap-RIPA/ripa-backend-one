<?php

    class Nature_produit_model extends CI_Model{
        function add($params){
            $this->db->insert("nature_produit", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_nature_produit", $id);
            return $this->db->update("nature_produit", $params);
        }

        function get_all_natures(){
            $this->db->where('is_deleted', 0);
            $this->db->order_by('id_nature_produit', 'desc');
            return $this->db->get('nature_produit')->result_array();
        }

        function get_nature_by_token($token){
            return $this->db->get_where('nature_produit',array('token_nature_produit'=>$token, 'is_deleted' => 0))->row_array();
        }

        function get_nature($id){
            return $this->db->get_where('nature_produit',array('id_nature_produit'=>$id, 'is_deleted' => 0))->row_array();
        }

        function get_nature_by_name($designation){
            return $this->db->get_where('nature_produit',array('designation'=>$designation, 'is_deleted' => 0))->row_array();
        }

        function delete_nature($token){
            $this->db->where("token_nature_produit", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("nature_produit");
        }
        
    }

?>