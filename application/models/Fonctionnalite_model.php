<?php

    class Fonctionnalite_model extends CI_Model{

        function get_fonctionnalites(){
            return $this->db->get("fonctionnalite")->result_array();
        }

        function get_all_fonctionnalites_by_idgroup($idgroup){
            return $this->db->get_where("fonctionnalite", array("id_group_fonctionnalite", $idgroup))->result_array();
        }

    }

?>