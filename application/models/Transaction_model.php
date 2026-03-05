<?php

    class Transaction_model extends CI_Model{

        function add($params){
            $this->db->insert("transaction", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_transaction", $id);
            return $this->db->update("transaction", $params);
        }

        function update_by_ripa_transaction_ref($ripa_transaction_ref, $params){
            $this->db->where("ripa_transaction_ref", $ripa_transaction_ref);
            return $this->db->update("transaction", $params);
        }


        function update_by_network_transaction_ref($network_transaction_ref, $params){
            $this->db->where("network_transaction_ref", $network_transaction_ref);
            return $this->db->update("transaction", $params);
        }

        function get_all_transactions(){
            $this->db->order_by('id_transaction', 'desc');
            return $this->db->get('transaction')->result_array();
        }

        function get_transaction($id_transaction){
            return $this->db->get_where('transaction',array('id_transaction'=>$id_transaction))->row_array();
        }

        function get_transaction_by_ripa_transaction_ref($ripa_transaction_ref){
            return $this->db->get_where('transaction',array('ripa_transaction_ref'=>$ripa_transaction_ref))->row_array();
        }

        function get_transaction_by_ripa_transaction_ref_id_foreign_statut_transaction($ripa_transaction_ref,$id_foreign_statut_transaction){
            return $this->db->get_where('transaction',array('ripa_transaction_ref'=>$ripa_transaction_ref,'id_foreign_statut_transaction'=>$id_foreign_statut_transaction))->row_array();
        }


        function get_transaction_by_num_ref($numref){
            return $this->db->get_where('transaction',array('trans_id'=>$numref))->row_array();
        }

        function get_all_transaction_by_date($start,$end){
            $query = $this->db->query("SELECT * FROM transaction WHERE date_transaction BETWEEN '".$start."' AND '".$end."' ORDER BY id_transaction DESC")->result_array();
            return $query;
        }

        function get_all_transaction_query_string($sql_query){
            $query = $this->db->query($sql_query)->result_array();
            return $query;
        }

        function delete_transaction($token){
            $this->db->where("id_transaction", $token);
            return $this->db->delete("transaction");
        }


        function get_transaction_by_from_num_date($from_num,$start,$end){
            $query = $this->db->query("SELECT * FROM transaction WHERE from_num='".$from_num."' AND date_transaction BETWEEN '".$start."' AND '".$end."' ORDER BY id_transaction DESC")->result_array();
            return $query;
        }

        function get_transaction_by_from_num_date_id_foreign_statut_transaction($from_num,$start,$end,$id_foreign_statut_transaction){
            $query = $this->db->query("SELECT * FROM transaction WHERE from_num='".$from_num."' AND date_transaction BETWEEN '".$start."' AND '".$end."' AND id_foreign_statut_transaction=$id_foreign_statut_transaction ORDER BY id_transaction DESC")->result_array();
            return $query;
        }


        function get_all_transaction_by_sql_limit_offset($sql,$limit,$offset)
        {
            $query  = $this->db->query("SELECT * FROM `transaction` WHERE ".$sql." ORDER BY id_transaction DESC LIMIT $limit OFFSET $offset")->result_array();
            return $query;
        }


        function get_all_transaction_by_sql($sql)
        {
            $query  = $this->db->query("SELECT * FROM `transaction` WHERE ".$sql." ORDER BY id_transaction DESC")->result_array();
            return $query;
        }


        function get_total_all_transaction_by_sql($sql)
        {
            $query  = $this->db->query("SELECT * FROM `transaction` WHERE ".$sql." ORDER BY id_transaction DESC")->result_array();
            return count($query);
        }


        function get_sum_montant_all_transaction_by_sql_id_devise($sql,$id_devise)
        {
            $query  = $this->db->query("SELECT SUM(montant) FROM `transaction` WHERE ".$sql." AND id_devise=$id_devise")->row_array();
            return $query['SUM(montant)'];
        }


        function get_sum_montant_all_ripa_commission_transaction_by_sql_id_devise($sql,$id_devise)
        {
            $query  = $this->db->query("SELECT SUM(dee_pay_commission) FROM `transaction` WHERE ".$sql." AND id_devise=$id_devise")->row_array();
            return $query['SUM(dee_pay_commission)'];
        }

        function get_sum_montant_all_network_commission_transaction_by_sql_id_devise($sql,$id_devise)
        {
            $query  = $this->db->query("SELECT SUM(network_commission) FROM `transaction` WHERE ".$sql." AND id_devise=$id_devise")->row_array();
            return $query['SUM(network_commission)'];
        }


        function get_last_transaction_by_from_num($from_num){
            $query = $this->db->query("SELECT * FROM transaction WHERE from_num='$from_num' ORDER BY id_transaction DESC LIMIT 1")->row_array();
            return $query;
        }


    }

?>