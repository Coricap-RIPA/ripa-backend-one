<?php

    class Trafic_model extends CI_Model{


        function add($params){
            $this->db->insert("trafic", $params);
            return $this->db->insert_id();
        }

        function update($params, $id_trafic){
            $this->db->where("id_trafic", $id_trafic);
            return $this->db->update("trafic", $params);
        }

        function get_all_trafics(){
            $this->db->where("is_deleted", 0);
            $this->db->order_by("id_trafic", "desc");
            return $this->db->get("trafic")->result_array();
        }

        function get_all_trafics_by_entreprise($id_entreprise){
            $query = $this->db->query("SELECT * FROM trafic WHERE (id_importateur_exportateur = $id_entreprise OR id_transitaire = $id_entreprise) AND is_deleted = 0 ORDER BY id_trafic DESC")->result_array();
            return $query;
        }

        function get_all_trafics_by_bureau_douane($id_bureau_douane){
            $query = $this->db->query("SELECT * FROM trafic WHERE id_bureau_douane = $id_bureau_douane  AND is_deleted = 0 ORDER BY id_trafic DESC")->result_array();
            return $query;
        }

        function get_trafic_by_token($token){
            return $this->db->get_where("trafic", array("token_trafic" => $token, "is_deleted" => 0))->row_array();
        }

        function generate_code($params){
            $this->db->insert("code_qr", $params);
            return $this->db->insert_id();
        }

        function verifier_code($code){
            return $this->db->get_where("code_qr", array("code" => $code))->row_array();
        }

        function get_all_code_qr(){
            return $this->db->get("code_qr")->result_array();
        }

        function get_code_qr($id){
            return $this->db->get_where("code_qr", array("code_qr" => $id))->row_array();
        }

        function get_pascal(){
            $query = $this->db->query("SELECT * FROM code_qr WHERE id >= 1 AND id <= 250")->result_array();
            return $query;
        }

        function get_tim(){
            $query = $this->db->query("SELECT * FROM code_qr WHERE id >= 251 AND id <= 500")->result_array();
            return $query;
        }

        function get_clerc(){
            $query = $this->db->query("SELECT * FROM code_qr WHERE id >= 501 AND id <= 750")->result_array();
            return $query;
        }

        function get_mathieu(){
            $query = $this->db->query("SELECT * FROM code_qr WHERE id >= 751 AND id <= 1000")->result_array();
            return $query;
        }

        function get_trafic($id){
            return $this->db->get_where("trafic", array("id_trafic" => $id, "is_deleted" => 0))->row_array();
        }

        function get_count_all_trafics_annee(){
            $annee = date('Y');
            $query = $this->db->query("SELECT COUNT(*) AS nombre FROM trafic WHERE EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0")->row_array();
            return $query['nombre'];
        }

        function get_count_all_trafics_annee_by_entreprise($id_entreprise){
            $annee = date('Y');
            $query = $this->db->query("SELECT COUNT(*) AS nombre FROM trafic WHERE (id_importateur_exportateur = $id_entreprise OR id_transitaire = $id_entreprise) AND EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0")->row_array();
            return $query['nombre'];
        }

        function get_count_all_trafics_annee_by_bureau_douane($id_bureau_douane){
            $annee = date('Y');
            $query = $this->db->query("SELECT COUNT(*) AS nombre FROM trafic WHERE id_bureau_douane = $id_bureau_douane AND EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0")->row_array();
            return $query['nombre'];
        }

        function get_all_count_trafic_by_type_operation($type){
            $annee = date('Y');
            $query = $this->db->query("SELECT COUNT(*) AS nombre FROM trafic WHERE EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0 AND type_operation = $type")->row_array();
            return $query['nombre'];
        }

        function get_all_count_trafic_by_type_operation_entreprise($type, $id_entreprise){
            $annee = date('Y');
            $query = $this->db->query("SELECT COUNT(*) AS nombre FROM trafic WHERE (id_importateur_exportateur = $id_entreprise OR id_transitaire = $id_entreprise) AND EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0 AND type_operation = $type")->row_array();
            return $query['nombre'];
        }

        function get_all_count_trafic_by_type_operation_by_bureau_douane($type, $id_bureau_douane){
            $annee = date('Y');
            $query = $this->db->query("SELECT COUNT(*) AS nombre FROM trafic WHERE id_bureau_douane = $id_bureau_douane AND EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0 AND type_operation = $type")->row_array();
            return $query['nombre'];
        }

        function get_trafic_paye(){
            $query = $this->db->query("SELECT * FROM trafic, facture, paiement WHERE trafic.id_trafic= facture.id_trafic AND facture.id_facture= paiement.id_facture AND paiement.status = 1")->result_array();
            return $query;
        }
        
        function get_trafic_paye_by_entreprise( $id_entreprise){
            $query = $this->db->query("SELECT * FROM trafic, facture, paiement WHERE trafic.id_trafic= facture.id_trafic AND facture.id_facture= paiement.id_facture AND paiement.status = 1 AND (trafic.id_importateur_exportateur =$id_entreprise OR trafic.id_transitaire = $id_entreprise) ")->result_array();
            return $query;
        }

        function get_trafic_en_attente_paiement(){
            $query = $this->db->query("SELECT * FROM facture, paiement, trafic WHERE facture.id_facture = paiement.id_facture AND (paiement.status = 0 OR paiement.status = 2) AND facture.id_trafic = trafic.id_trafic")->result_array();
            return $query;
        }

        function get_trafic_en_attente_paiement_by_entreprise( $id_entreprise){
            $query = $this->db->query("SELECT * FROM facture, paiement, trafic WHERE facture.id_facture = paiement.id_facture AND (paiement.status = 0 OR paiement.status = 2) AND facture.id_trafic = trafic.id_trafic AND (trafic.id_importateur_exportateur =$id_entreprise OR trafic.id_transitaire = $id_entreprise) ")->result_array();
            return $query;
        }

        function get_trafic_en_attente_paiement_by_bureau_douane ( $id_bureau_douane){
            $query = $this->db->query("SELECT * FROM facture, paiement, trafic WHERE facture.id_facture = paiement.id_facture AND (paiement.status = 0 OR paiement.status = 2) AND facture.id_trafic = trafic.id_trafic AND trafic.id_bureau_douane =$id_bureau_douane  ")->result_array();
            return $query;
        }

        function get_trafic_facture(){
            $query = $this->db->query("SELECT * FROM trafic, facture WHERE trafic.id_trafic= facture.id_trafic")->result_array();
            return $query;
        }

        function get_trafic_facture_by_entreprise($id_entreprise){
            $query = $this->db->query("SELECT * FROM trafic, facture WHERE trafic.id_trafic= facture.id_trafic (trafic.id_importateur_exportateur =$id_entreprise OR trafic.id_transitaire = $id_entreprise) " )->result_array();
            return $query;
        }

        function get_all_trafic_by_type_operation($type){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM trafic WHERE EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0 AND type_operation = $type")->result_array();
            return $query;
        }
        function get_all_trafic_by_type_operation_by_bureau_douane($type, $id_bureau_douane){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM trafic WHERE EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0 AND type_operation = $type AND id_bureau_douane = $id_bureau_douane ")->result_array();
            return $query;
        }

        function get_all_trafic_by_type_operation_entreprise($type, $id_entreprise){
            $annee = date('Y');
            $query = $this->db->query("SELECT * FROM trafic WHERE (id_importateur_exportateur = $id_entreprise OR id_transitaire = $id_entreprise) AND EXTRACT(YEAR FROM date_heure) = '$annee' AND is_deleted = 0 AND type_operation = $type")->result_array();
            return $query;
        }

        function delete_trafic($token)
        {
            $this->db->where("token_trafic", $token);
            $this->db->set("is_deleted", 1);
            return $this->db->update("trafic");
        }
        
    }

?>