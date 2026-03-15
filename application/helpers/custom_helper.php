<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    /**
     * Chiffrement bidirectionnel RIPA (données personnelles en base).
     * Clé : config "ripa_encryption_key" (32 octets recommandés pour AES-256).
     * @param string $plaintext
     * @return string Base64 du chiffré (ou chaîne vide si erreur)
     */
    function encrypt_ripa($plaintext) {
        if ($plaintext === null || $plaintext === '') {
            return '';
        }
        $CI = &get_instance();
        $key = $CI->config->item('ripa_encryption_key');
        if (empty($key)) {
            log_message('error', 'ripa_encryption_key non configurée');
            return '';
        }
        $key = hash('sha256', $key, true);
        $iv = openssl_random_pseudo_bytes(16);
        $cipher = openssl_encrypt($plaintext, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        if ($cipher === false) {
            return '';
        }
        return base64_encode($iv . $cipher);
    }

    /**
     * Déchiffrement (inverse de encrypt_ripa).
     * @param string $ciphertext Base64 du chiffré
     * @return string Texte clair ou chaîne vide
     */
    function decrypt_ripa($ciphertext) {
        if ($ciphertext === null || $ciphertext === '') {
            return '';
        }
        $CI = &get_instance();
        $key = $CI->config->item('ripa_encryption_key');
        if (empty($key)) {
            return '';
        }
        $key = hash('sha256', $key, true);
        $raw = base64_decode($ciphertext, true);
        if ($raw === false || strlen($raw) < 16) {
            return '';
        }
        $iv = substr($raw, 0, 16);
        $cipher = substr($raw, 16);
        $plain = openssl_decrypt($cipher, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        return ($plain !== false) ? $plain : '';
    }

    /**
     * Normalise le téléphone pour stockage et hash (indicatif pays avec +).
     * @param string $tel
     * @return string ex: +243971234567
     */
    function normalise_phone_ripa($tel) {
        $tel = preg_replace('/[^0-9+]/', '', trim($tel));
        if ($tel !== '' && $tel[0] !== '+') {
            $tel = '+' . $tel;
        }
        return $tel;
    }

    /**
     * Nettoie un tableau ou une chaîne avant écriture en log (log_utilisateur_application).
     * Retire ou masque : pan, cvv, token, token_vault, mot de passe, pin, otp.
     * PCI DSS : ne jamais logger PAN, CVV, token complet, mot de passe.
     * @param array|string $data
     * @return array|string Données safe pour les logs
     */
    function sanitize_for_log($data) {
        $sensitive_keys = array('pan', 'cvv', 'pin', 'mot_passe', 'password', 'token', 'token_vault', 'otp', 'otp_code', 'mot_passe_pin');
        if (is_array($data)) {
            $out = array();
            foreach ($data as $k => $v) {
                $k_lower = is_string($k) ? strtolower($k) : $k;
                $is_sensitive = false;
                foreach ($sensitive_keys as $sk) {
                    if (strpos((string) $k_lower, $sk) !== false) {
                        $is_sensitive = true;
                        break;
                    }
                }
                if ($is_sensitive) {
                    $out[$k] = '[REDACTED]';
                } elseif (is_array($v) || is_object($v)) {
                    $out[$k] = sanitize_for_log((array) $v);
                } else {
                    $out[$k] = $v;
                }
            }
            return $out;
        }
        if (is_string($data)) {
            return $data;
        }
        return $data;
    }

    function action_utilisateur($data, $model){
        $model->add($data);
    }

    function generate_token(){
        $string = date("Y-m-d H:i:s") .''. $_SESSION['user']['id_utilisateur'];
        return sha1($string);
    }

    function generate_token1($id){
        $string = date("Y-m-d H:i:s") .''. $id;
        return sha1($string);
    }

    function check_privilege($fonctionnalite, $idrole, $operation){
        $CI = &get_instance();
        $fonct = $CI->db->get_where("fonctionnalite", array("short_code" => $fonctionnalite))->row_array();
        if(!empty($fonct)){
            $permission = $CI->db->get_where("role_permission", array("id_role" => $idrole, "id_fonctionnalite" => $fonct['id_fonctionnalite']))->row_array();
            if(!empty($permission)){
                switch($operation){
                    case "voir" :
                        if($permission['peux_voir'] == 1){
                            return true;
                        } else {
                            return false;
                        }
                    break;
                    case "ajouter" :
                        if($permission['peux_ajouter'] == 1){
                            return true;
                        } else {
                            return false;
                        }
                    break;
                    case "editer" :
                        if($permission['peux_editer'] == 1){
                            return true;
                        } else {
                            return false;
                        }
                    break;
                    case "supprimer" :
                        if($permission['peux_supprimer'] == 1){
                            return true;
                        } else {
                            return false;
                        }
                    break;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }


    function ellipsis($string, $max_length) {
	    return (strlen($string) > $max_length) ? substr($string,0,strrpos(substr($string, 0, $max_length), ' '))."…" : $string;
	}

    function echoTab ($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
    }



    function echoDie ($data){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        die;
    }

    function in_multiarray($elem, $array,$field)
    {
        $top = sizeof($array) - 1;
        $bottom = 0;
        while($bottom <= $top)
        {
            if($array[$bottom][$field] == $elem)
                return true;
            else 
                if(is_array($array[$bottom][$field]))
                    if(in_multiarray( $elem, ( $array[$bottom][$field] ),$field ) )
                        return true;

            $bottom++;
        }        
        return false;
    }

    function isMobile() {
        return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $_SERVER["HTTP_USER_AGENT"]);
    }

    function format_date_fr($date_en_in)
    {
        $tab_date = explode('-',$date_en_in);
        return $tab_date[2].'-'.$tab_date[1].'-'.$tab_date[0];
    }

    function getCurrentDate(){
        return date('Y-m-d');
    }

    function getFrDate($date_en){
        return date_format(date_create($date_en),'d-m-Y');
    }


    function getTypeOperator($number){

        $number  = trim($number);
        $number  = "".$number;
        $prefix  = $number[0]."".$number[1];

        if($prefix == "97" OR $prefix == "99"){
            return "Airtel";
        }elseif( $prefix == "81" OR $prefix == "82" ){
            return "Vodacom";
        }elseif( $prefix == "84" OR $prefix == "85" ){
            return "Orange";
        }elseif( $prefix == "90" OR $prefix == "91" ){
            return "Africel";
        }
        else{
            return "Bankcard";
        }

    }

    function formatOperatorMobileMoneyName($nameOperator) {

        if( strtolower(trim($nameOperator)) == "airtel" ){
            return "Airtel Money";
        }elseif( strtolower(trim($nameOperator)) == "vodacom" ){
            return "M-pesa";
        }elseif( strtolower(trim($nameOperator)) == "orange" ){
            return "Orange Money";
        }elseif( strtolower(trim($nameOperator))  == "africel" ){
            return "Afri Money";
        }
        else{
            return "Carte Bancaire";
        }
    }

    function formatNumberPhone($numberPhoneIn) {
        $numberPhone = "".$numberPhoneIn;
        $numberPhone = str_replace("+243","",$numberPhone);
        $numberPhone = str_replace("243","",$numberPhone);
        $numberPhone = str_replace(" ","",$numberPhone);
        $numberPhone = str_replace("  ","",$numberPhone);
        $numberPhone = trim($numberPhone);
        if($numberPhone[0] === '0'){
            $numberPhone = str_replace("0","",$numberPhone);;
        }
        return $numberPhone;
    }

?>