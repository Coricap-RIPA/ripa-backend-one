<?php

    class Provision_model extends CI_Model{

        function add($params){
            $this->db->insert("provision", $params);
            return $this->db->insert_id();
        }

        function update($params, $id_provision){
            $this->db->where("id_provision", $id_provision);
            return $this->db->update("provision", $params);
        }

        function get_provisions_and_paiements($id_entreprise){
            $query = $this->db->query("SELECT * FROM provision, paiement WHERE provision.id_entreprise = ".$this->db->escape($id_entreprise)." AND provision.id_provision = paiement.id_provision");
            return $query->result_array();
        }

        function get_montant_entree_provision($id_entreprise){
            $query = $this->db->query("SELECT SUM(provision.montant_provision) AS montant FROM provision, paiement WHERE provision.id_entreprise = ".$this->db->escape($id_entreprise)." AND provision.id_provision = paiement.id_provision AND paiement.status = 1")->row_array();
            return $query['montant'];
        }

        function get_historique($id_entreprise){
            $query = $this->db->query("SELECT * FROM provision, paiement WHERE provision.id_entreprise = $id_entreprise AND provision.id_provision = paiement.id_provision ORDER BY paiement.date_paiement DESC")->result_array();
            return $query;
        }

        function get_provision($id_provision){
            return $this->db->get_where("provision", array("id_provision" => $id_provision))->row_array();
        }

        function get_provision_and_entreprise($id_provision, $id_entreprise){
            return $this->db->get_where("provision", array("id_provision" => $id_provision, 'id_entreprise' => $id_entreprise))->row_array();
        }

        function get_provisions_paye(){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM provision, paiement WHERE provision.id_provision = paiement.id_provision AND paiement.status = 1 AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

        function get_provisions_paye_entreprise($id_entreprise){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM provision, paiement WHERE provision.id_provision = paiement.id_provision AND paiement.status = 1 AND provision.id_entreprise = $id_entreprise AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

    }

?>