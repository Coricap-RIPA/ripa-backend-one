<?php

    class Commission_model extends CI_Model{

        function add($params){
            $this->db->insert("commission", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_commission", $id);
            return $this->db->update("commission", $params);
        }

        function get_all_commissions(){
            $this->db->order_by('id_commission', 'asc');
            return $this->db->get('commission')->result_array();
        }

        
        function get_commission($id){
            return $this->db->get_where('commission',array('id_commission'=>$id))->row_array();
        }

        function get_fromatter_commissions(){
            $query = $this->db->query("SELECT * FROM `commission` WHERE `id_commission`<>3 AND `id_commission` <> 9 AND `id_commission` <>10")->result_array();
            return $query;
        }

        function delete_commission($token){
            $this->db->where("id_commission", $token);
            return $this->db->delete("commission");
        }

        function get_last_commission()
        {
            $query = $this->db->query("SELECT * FROM commission ORDER BY id_commission DESC LIMIT 1")->row_array();
            return $query;
        }

        function get_last_commission_by_id_foreign_type_commission($id_foreign_type_commission)
        {
            $query = $this->db->query("SELECT * FROM commission WHERE id_foreign_type_commission=$id_foreign_type_commission ORDER BY id_commission DESC LIMIT 1")->row_array();
            return $query;
        }

    }

?>