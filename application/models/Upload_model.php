<?php

    class Upload_model extends CI_Model{

        function add($data){
            return $this->db->insert("trafic",$data);
        }

    }

?>