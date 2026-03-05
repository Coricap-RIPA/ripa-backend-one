<?php

    class Utilisateur_model extends CI_Model{

        function check_connexion($email){
            return $this->db->get_where("utilisateur", array("email" => $email))->row_array();
        }

        function add_utilisateur($params){
            $this->db->insert("utilisateur", $params);
            return $this->db->insert_id();
        }

        function update_utilisateur($params, $id_utilisateur){
            $this->db->where("id_utilisateur", $id_utilisateur);
            return $this->db->update("utilisateur", $params);
        }

        function get_count_entreprise_users(){
            $query = $this->db->query("SELECT * FROM `utilisateur` WHERE `id_entreprise` != NULL OR id_entreprise != 0 AND is_deleted = 0")->result_array();
            return (count($query));
        }

        function get_count_general_users(){
            $query = $this->db->query("SELECT * FROM `utilisateur` WHERE `id_entreprise` = NULL OR id_entreprise = 0 AND is_deleted = 0")->result_array();
            return (count($query));
        }

        function get_all_utilisateurs(){
            $query = $this->db->query("SELECT * FROM `utilisateur`")->result_array();
            return ($query);
        }

        function get_count_user_by_identreprise($identreprise){
            $this->db->from("utilisateur");
            $this->db->where("id_entreprise", $identreprise);
            $this->db->where("is_deleted", 0);
            return $this->db->count_all_results();
        }

        function get_all_utilisateur_by_identreprise($identreprise){
           $query = $this->db->query("SELECT * FROM utilisateur WHERE id_entreprise = $identreprise AND is_deleted = 0 ORDER BY id_utilisateur DESC")->result_array();
           return $query;
        }

        function get_all_utilisateur_generale(){
            $query = $this->db->query("SELECT * FROM utilisateur WHERE (id_entreprise IS NULL OR id_entreprise = 0) AND is_deleted = 0 ORDER BY id_utilisateur DESC")->result_array();
           return $query;
        }

        function get_utilisateur_by_token($token){
            return $this->db->get_where("utilisateur", array("token_utilisateur" => $token, "is_deleted" => 0))->row_array();
        }

        function get_utilisateur_by_email_notif($token){
            return $this->db->get_where("utilisateur", array("id_foreign_send_email_notif" => $token))->result_array();
        }

        function changer_mot_de_passe($password, $id_utilisateur){
            $this->db->where("id_utilisateur", $id_utilisateur);
            $this->db->set("password", $password);
            return $this->db->update("utilisateur");
        }

        function check_email($email, $id_utilisateur){
            $this->db->where("email", $email);
            $this->db->where("id_utilisateur <>", $id_utilisateur);
            return $this->db->get("utilisateur")->row_array();
        }

        function delete_utilisateur($id_utilisateur){
            $this->db->where("id_utilisateur", $id_utilisateur);
            $this->db->set("is_deleted", 1);
            return $this->db->update("utilisateur");
        }
    }

?>