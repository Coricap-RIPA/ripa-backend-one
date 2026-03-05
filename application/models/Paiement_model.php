<?php

    class Paiement_model extends CI_Model{

        function add($params){
            $this->db->insert("paiement", $params);
            return $this->db->insert_id();
        }

        function update($params, $id_paiement){
            $this->db->where("id_paiement", $id_paiement);
            return $this->db->update("paiement", $params);
        }

        function get_paiement_by_provision_entreprise($identreprise){
            $query = $this->db->query("SELECT SUM(facture.montant_facture) AS montant FROM facture, paiement WHERE facture.id_entreprise_paye = ".$this->db->escape($identreprise)." AND paiement.type_paiement = 'provision'")->row_array();
            return $query['montant'];
        }

        function get_historique_paiement_par_provision($identreprise){
            $query = $this->db->query("SELECT * FROM facture, paiement WHERE facture.id_entreprise_paye = $identreprise AND facture.id_facture = paiement.id_facture AND paiement.status = 1 AND paiement.type_paiement = 'provision' ORDER BY paiement.date_paiement DESC")->result_array();
            return $query;
        }

        function get_paiement_by_token($token){
            return $this->db->get_where("paiement", array("token_paiement" => $token))->row_array();
        }

        function get_paiement($id_paiement){
            return $this->db->where("paiement", array("id_paiement" => $id_paiement))->row_array();
        }

        function get_all_paiements_en_attente(){
            $query = $this->db->query("SELECT * FROM paiement WHERE status = 0 OR status = 2 ORDER BY date_paiement DESC")->result_array();
            return $query;
        }

        function get_all_paiements_en_attente_entreprise($id_entreprise){
            $query = $this->db->query("SELECT * FROM paiement, facture, trafic WHERE ( paiement.status = 0 OR paiement.status = 2 ) AND paiement.id_facture = facture.id_facture AND facture.id_trafic = trafic.id_trafic AND (  trafic.id_importateur_exportateur = $id_entreprise OR trafic.id_transitaire = $id_entreprise ) ORDER BY date_paiement DESC")->result_array();
            return $query;
        }



        function get_all_paiements_en_attente_bureau_douane($id_bureau_douane){
            $query = $this->db->query( "SELECT * FROM paiement, facture, trafic WHERE ( paiement.status = 0 OR paiement.status = 2 ) AND paiement.id_facture = facture.id_facture AND facture.id_trafic = trafic.id_trafic AND  trafic.id_bureau_douane = $id_bureau_douane  ORDER BY date_paiement DESC" )->result_array();
            return $query;
        }

        

        function get_all_paiements_en_attente_de_paiement(){
            $query = $this->db->query("SELECT * FROM facture WHERE id_facture NOT IN (SELECT id_facture FROM paiement)")->result_array();
            return $query;
        }

        function get_all_paiements_valide(){
            $this->db->where("status", 1);
            $this->db->order_by("date_paiement", "DESC");
            return $this->db->get("paiement")->result_array();
        }

        function get_all_paiements_valide_entreprise($id_entreprise){
            $query = $this->db->query("SELECT * FROM paiement, facture, trafic WHERE paiement.status = 1 AND paiement.id_facture = facture.id_facture AND facture.id_trafic = trafic.id_trafic AND (facture.id_entreprise_paye = $id_entreprise OR trafic.id_importateur_exportateur = $id_entreprise OR trafic.id_transitaire = $id_entreprise) ORDER BY date_paiement DESC")->result_array();
            return $query;
        }

        function get_all_paiements_valide_by_bureau_douane($id_bureau_douane){
            $query = $this->db->query("SELECT * FROM paiement, facture, trafic WHERE paiement.status = 1 AND paiement.id_facture = facture.id_facture AND facture.id_trafic = trafic.id_trafic AND  trafic.id_bureau_douane = $id_bureau_douane ORDER BY date_paiement DESC")->result_array();
            return $query;
        }

        function valider_paiement($id_paiement){
            $this->db->where("id_paiement", $id_paiement);
            $this->db->set("status", 1);
            return $this->db->update("paiement");
        }

        function get_paiement_by_idtrafic($idfacture){
            return $this->db->get_where("paiement", array("id_facture" => $idfacture))->row_array();
        }

        function rejeter_paiement($id_paiement){
            $this->db->where("id_paiement", $id_paiement);
            $this->db->set("status", 2);
            return $this->db->update("paiement");
        }

        function rejet_paiement_explication($params){
            $this->db->insert("rejet_paiement", $params);
            return $this->db->insert_id();
        }

        function uptade_status_paiement($id_paiement,$status_params){
            $this->db->where("id_paiement", $id_paiement);
            $this->db->set("status", $status_params);
            return $this->db->update("paiement");
        }

        function get_explication_rejet($id_paiement){
            $this->db->where("id_paiement", $id_paiement);
            $this->db->order_by("id_rejet_paiement", "desc");
            return $this->db->get("rejet_paiement")->result_array();
        }

        function get_paiement_by_month_and_annee(){
            $annee = date("Y");
            $query = $this->db->query("SELECT SUM(facture.montant_facture) AS nombre, MONTH(paiement.date_paiement) as mois FROM facture, paiement WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND EXTRACT(YEAR FROM paiement.date_paiement) = $annee GROUP BY MONTH(paiement.date_paiement)")->result_array();
            return $query;
        }

        function get_paiement_by_month_and_annee_entreprise($id_entreprise){
            $annee = date("Y");
            $query = $this->db->query("SELECT SUM(facture.montant_facture) AS nombre, MONTH(paiement.date_paiement) as mois FROM facture, paiement, trafic WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND facture.id_trafic = trafic.id_trafic AND (trafic.id_importateur_exportateur = $id_entreprise OR trafic.id_transitaire = $id_entreprise OR facture.id_entreprise_paye = $id_entreprise) AND EXTRACT(YEAR FROM paiement.date_paiement) = $annee GROUP BY MONTH(paiement.date_paiement)")->result_array();
            return $query;
        }

        function get_paiement_by_month_and_annee_by_bureau_douane($id_bureau_douane){
            $annee = date("Y");
            $query = $this->db->query("SELECT SUM(facture.montant_facture) AS nombre, MONTH(paiement.date_paiement) as mois FROM facture, paiement, trafic WHERE facture.id_facture = paiement.id_facture AND paiement.status = 1 AND facture.id_trafic = trafic.id_trafic AND trafic.id_bureau_douane = $id_bureau_douane AND EXTRACT(YEAR FROM paiement.date_paiement) = $annee GROUP BY MONTH(paiement.date_paiement)")->result_array();
            return $query;
        }

        function get_all_paiement(){
            $query = $this->db->query("SELECT * FROM paiement")->result_array();
            return $query;
        }


    }

?>