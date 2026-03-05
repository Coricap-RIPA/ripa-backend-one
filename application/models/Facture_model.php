<?php

    class Facture_model extends CI_Model{

        function add($params){
            $this->db->insert("facture", $params);
            return $this->db->insert_id();
        }

        function update_by_trafic($params, $id_trafic){
            $this->db->where("id_trafic", $id_trafic);
            return $this->db->update("facture", $params);
        }


        function update($params, $id_facture){
            $this->db->where("id_facture", $id_facture);
            return $this->db->update("facture", $params);
        }

        function get_facture($id){
            return $this->db->get_where("facture", array("id_facture" => $id))->row_array();
        }

        function get_facture_and_entreprise($id_facture, $id_entreprise){
            $query = $this->db->query("SELECT * FROM facture, facture WHERE facture.id_facture = $id_facture AND facture.id_trafic = facture.id_trafic AND (facture.id_entreprise_paye = $id_entreprise OR facture.id_importateur_exportateur = $id_entreprise OR facture.id_transitaire = $id_entreprise)")->row_array();
            return $query;
        }

        function get_facture_by_idtrafic($idtrafic){
            return $this->db->get_where("facture", array("id_trafic" => $idtrafic))->row_array();
        }

        function get_facture_by_token($token){
            return $this->db->get_where("facture", array("token_facture" => $token))->row_array();
        }

        function get_all_factures(){
            $query = $this->db->query("SELECT * FROM facture, facture WHERE facture.id_trafic = facture.id_trafic AND facture.is_deleted = 0 ORDER BY facture.id_facture DESC")->result_array();
            return $query;
        }

        function get_all_factures_by_entreprise($id_entreprise){
            $query = $this->db->query("SELECT * FROM facture, facture WHERE facture.id_trafic = facture.id_trafic AND facture.is_deleted = 0 AND (facture.id_entreprise_paye = $id_entreprise OR facture.id_importateur_exportateur = $id_entreprise OR facture.id_transitaire = $id_entreprise) ORDER BY facture.id_facture DESC")->result_array();
            return $query;
        }

        function get_all_factures_by_bureau_douane($id_bureau_douane){
            $query = $this->db->query("SELECT * FROM facture, facture WHERE facture.id_trafic = facture.id_trafic AND facture.is_deleted = 0 AND  facture.id_bureau_douane = $id_bureau_douane ORDER BY facture.id_facture DESC")->result_array();
            return $query;
        }

        function get_all_factures_annee(){
            $annee = date("Y");
            $query = $this->db->query("SELECT * FROM facture, facture WHERE facture.id_trafic = facture.id_trafic AND facture.is_deleted = 0 AND EXTRACT(YEAR FROM facture.date_heure) = '$annee' ORDER BY facture.id_facture DESC")->result_array();
            return $query;
        }

        function get_all_factures_annee_entreprise($id_entreprise){
            $annee = date("Y");
            $query = $this->db->query("SELECT * FROM facture, facture WHERE facture.id_trafic = facture.id_trafic AND facture.is_deleted = 0 AND (facture.id_entreprise_paye = $id_entreprise OR facture.id_transitaire = $id_entreprise OR facture.id_importateur_exportateur = $id_entreprise) AND EXTRACT(YEAR FROM facture.date_heure) = '$annee' ORDER BY facture.id_facture DESC")->result_array();
            return $query;
        }

        

        function get_all_factures_annee_by_bureau_douane($id_bureau_douane){
            $annee = date("Y");
            $query = $this->db->query("SELECT * FROM facture, facture WHERE facture.id_trafic = facture.id_trafic AND facture.is_deleted = 0 AND facture.id_bureau_douane = $id_bureau_douane AND EXTRACT(YEAR FROM facture.date_heure) = '$annee' ORDER BY facture.id_facture DESC")->result_array();
            return $query;
        }

        function get_factures_paye(){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

        function get_factures_paye_provision_annee(){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee' AND paiement.type_paiement = 'provision'")->result_array();
            return $query;
        }

        function get_factures_paye_entreprise($id_entreprise){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement, facture WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND facture.id_trafic = facture.id_trafic AND (facture.id_entreprise_paye = $id_entreprise OR facture.id_transitaire = $id_entreprise OR facture.id_importateur_exportateur = $id_entreprise) AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

        function get_factures_paye_by_bureau_douane($id_bureau_douane){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement, facture WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND facture.id_trafic = facture.id_trafic AND  facture.id_bureau_douane = $id_bureau_douane AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

        function get_factures_paye_provision_by_entreprise_annee($id_entreprise){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement, facture WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND paiement.type_paiement = 'provision' AND facture.id_trafic = facture.id_trafic AND (facture.id_entreprise_paye = $id_entreprise OR facture.id_transitaire = $id_entreprise OR facture.id_importateur_exportateur = $id_entreprise) AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

        function get_factures_paye_provision_by_bureau_douane($id_bureau_douane){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement, facture WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND paiement.type_paiement = 'provision' AND facture.id_trafic = facture.id_trafic AND  facture.id_bureau_douane = $id_bureau_douane AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

        function get_paiement_en_attente(){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement WHERE facture.id_facture = paiement.id_facture AND (paiement.status = 0 OR paiement.status = 2) AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

        function get_paiement_en_attente_entreprise($id_entreprise){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement, facture WHERE facture.id_facture = paiement.id_facture AND (paiement.status = 0 OR paiement.status = 2) AND facture.id_trafic = facture.id_trafic AND (facture.id_entreprise_paye = $id_entreprise OR facture.id_transitaire = $id_entreprise OR facture.id_importateur_exportateur = $id_entreprise) AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }

        function get_paiement_en_attente_by_bureau_douane($id_bureau_douane){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM facture, paiement, facture WHERE facture.id_facture = paiement.id_facture AND (paiement.status = 0 OR paiement.status = 2) AND facture.id_trafic = facture.id_trafic AND  facture.id_bureau_douane = $id_bureau_douane AND EXTRACT(YEAR FROM paiement.date_paiement) = '$annee'")->result_array();
            return $query;
        }
    }

?>