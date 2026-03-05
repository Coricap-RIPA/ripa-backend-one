<?php

    class Group_fonctionnalite_model extends CI_Model{

        function get_all_group_fonctionnalites(){
            return $this->db->get("group_fonctionnalite")->result_array();
        }

    }

?>