<?php

    class Vehicule_dedouane_model extends CI_Model{

        function add($params){
            $this->db->insert("vehicule_dedouane", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_vehicule_dedouane", $id);
            return $this->db->update("vehicule_dedouane", $params);
        }

        function get_all_vehicule_dedouanes(){
            $this->db->order_by('id_vehicule_dedouane', 'desc');
            return $this->db->get('vehicule_dedouane')->result_array();
        }

        function get_mode_by_id($id){
            return $this->db->get_where('vehicule_dedouane',array('id_vehicule_dedouane'=>$id))->row_array();
        }

        
        function delete_vehicule_dedouane($id){
            $this->db->where("id_vehicule_dedouane", $id);
            return $this->db->delete("vehicule_dedouane");
        }


    }

?>