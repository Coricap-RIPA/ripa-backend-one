<?php

    class Publicite_model extends CI_Model{

        function add($params){
            $this->db->insert("publicite", $params);
            return $this->db->insert_id();
        }
        
        function update($id, $params){
            $this->db->where("id_publicite", $id);
            return $this->db->update("publicite", $params);
        }

        function get_all_publicites(){
            $this->db->order_by('id_publicite', 'desc');
            return $this->db->get('publicite')->result_array();
        }

        function get_publicite_by_type($token){
            $this->db->order_by('id_publicite', 'desc');
            return $this->db->get_where('publicite',array('id_foreign_type_publicite'=>$token))->result_array();
        }

        function get_publicite($id){
            return $this->db->get_where('publicite',array('id_publicite'=>$id))->row_array();
        }

        function delete_publicite($token){
            $this->db->where("token_publicite", $token);
            return $this->db->delete("publicite");
        }

        function get_publicite_by_id_section($id_foreign_section){
            $this->db->order_by('id_publicite', 'desc');
            $this->db->limit(1);
            return $this->db->get_where('publicite',array('id_foreign_section',$id_foreign_section))->row_array();
        }

        function get_two_last_publicite_by_id_section($id_foreign_section){
            $this->db->order_by('id_publicite', 'desc');
            $this->db->limit(2);
            return $this->db->get_where('publicite',array('id_foreign_section',$id_foreign_section))->result_array();
        }


        function get_two_last_publicites(){
            $this->db->order_by('id_publicite', 'desc');
            $this->db->limit(2);
            return $this->db->get('publicite')->result_array();
        }

    }

?>