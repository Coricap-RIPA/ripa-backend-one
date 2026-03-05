<?php

    class Action_utilisateur_model extends CI_Model{

        function add($data){
            return $this->db->insert("action_utilisateur", $data);
        }

    }

?>