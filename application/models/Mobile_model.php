<?php

    class Mobile_model extends CI_Model{

        function add($data){
            return $this->db->insert("trafic",$data);
        }

        function getUser($data){
            
        }

    }

?>