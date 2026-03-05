<?php

    class Nature_produit_model_lmc extends CI_Model{
        function add($params){
            $this->db->insert("nature_produit_lmc", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("nature_produit_lmc", $id);
            return $this->db->update("nature_produit_lmc", $params);
        }

        function get_all_natures(){
            $this->db->where('is_deleted', 0);
            $this->db->order_by('id_nature_produit_lmc', 'desc');
            return $this->db->get('nature_produit_lmc')->result_array();
        }

        function get_nature_by_token($token){
            return $this->db->get_where('nature_produit_lmc',array('token_nature_produit_lmc'=>$token, 'is_deleted' => 0))->row_array();
        }

        function get_nature($id){
            return $this->db->get_where('nature_produit_lmc',array('id_nature_produit_lmc'=>$id, 'is_deleted' => 0))->row_array();
        }

        function get_nature_by_code($code){
            return $this->db->get_where('nature_produit_lmc',array('code'=>$code, 'is_deleted' => 0))->row_array();
        }

        function delete_nature($token){
            $this->db->where("token_nature_produit_lmc", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("nature_produit_lmc");
        }
    }

?>