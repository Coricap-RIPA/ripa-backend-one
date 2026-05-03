<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Import employés portail B2B — spécification colonnes et validation des lignes.
 * Ne pas journaliser de données sensibles (PCI / vie privée).
 */

if (!function_exists('business_employe_import_column_guide')) {
    /**
     * Guide des colonnes pour l’interface et la doc utilisateur.
     *
     * @return array[] list of [ 'key', 'label', 'required', 'example', 'hint' ]
     */
    function business_employe_import_column_guide() {
        return array(
            array(
                'key' => 'nom',
                'label' => 'Nom',
                'required' => true,
                'example' => 'Kabila',
                'hint' => 'Nom de famille, 2 caractères minimum.',
            ),
            array(
                'key' => 'prenom',
                'label' => 'Prénom',
                'required' => true,
                'example' => 'Jean',
                'hint' => '2 caractères minimum.',
            ),
            array(
                'key' => 'email',
                'label' => 'Email',
                'required' => false,
                'example' => 'jean.kabila@entreprise.cd',
                'hint' => 'Adresse professionnelle si connue.',
            ),
            array(
                'key' => 'telephone',
                'label' => 'Téléphone',
                'required' => false,
                'example' => '+243 850 000 000',
                'hint' => 'Format libre, 50 caractères max.',
            ),
            array(
                'key' => 'poste',
                'label' => 'Poste / fonction',
                'required' => false,
                'example' => 'Responsable comptabilité',
                'hint' => 'Intitulé de poste générique.',
            ),
            array(
                'key' => 'matricule',
                'label' => 'Matricule / réf. interne',
                'required' => false,
                'example' => 'EMP-2024-0142',
                'hint' => 'Code interne RH (badge, numéro dossier).',
            ),
            array(
                'key' => 'departement',
                'label' => 'Département / service',
                'required' => false,
                'example' => 'Finance',
                'hint' => 'Direction, service ou unité.',
            ),
            array(
                'key' => 'date_entree',
                'label' => 'Date d’entrée',
                'required' => false,
                'example' => '2024-01-15 ou 15/01/2024',
                'hint' => 'AAAA-MM-JJ, JJ/MM/AAAA ou date Excel.',
            ),
            array(
                'key' => 'ville',
                'label' => 'Ville',
                'required' => false,
                'example' => 'Kinshasa',
                'hint' => '',
            ),
            array(
                'key' => 'pays',
                'label' => 'Pays',
                'required' => false,
                'example' => 'CD',
                'hint' => 'Code ISO alpha-2 recommandé (ex. CD, FR).',
            ),
            array(
                'key' => 'actif',
                'label' => 'Actif',
                'required' => false,
                'example' => '1 ou oui',
                'hint' => '1 / oui / yes = actif ; 0 / non = inactif. Vide = actif.',
            ),
        );
    }
}

if (!function_exists('business_employe_import_normalize_header_key')) {
    function business_employe_import_normalize_header_key($header) {
        $h = trim(mb_strtolower((string) $header, 'UTF-8'));
        static $acc = array(
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'à' => 'a', 'â' => 'a', 'ä' => 'a',
            'î' => 'i', 'ï' => 'i',
            'ô' => 'o', 'ö' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u',
            'ç' => 'c',
        );
        $h = strtr($h, $acc);
        $h = preg_replace('/[^a-z0-9]+/', '_', $h);
        return trim($h, '_');
    }
}

if (!function_exists('business_employe_import_header_aliases')) {
    /** @return array<string,string> slug d’en-tête → champ base */
    function business_employe_import_header_aliases() {
        return array(
            'nom' => 'nom',
            'nom_de_famille' => 'nom',
            'lastname' => 'nom',
            'family_name' => 'nom',
            'prenom' => 'prenom',
            'firstname' => 'prenom',
            'email' => 'email',
            'courriel' => 'email',
            'mail' => 'email',
            'e_mail' => 'email',
            'telephone' => 'telephone',
            'tel' => 'telephone',
            'mobile' => 'telephone',
            'phone' => 'telephone',
            'poste' => 'poste',
            'fonction' => 'poste',
            'titre' => 'poste',
            'job_title' => 'poste',
            'matricule' => 'matricule',
            'ref_interne' => 'matricule',
            'reference' => 'matricule',
            'badge' => 'matricule',
            'code_employe' => 'matricule',
            'departement' => 'departement',
            'department' => 'departement',
            'service' => 'departement',
            'direction' => 'departement',
            'unite' => 'departement',
            'date_entree' => 'date_entree',
            'date_embauche' => 'date_entree',
            'embauche' => 'date_entree',
            'date_d_entree' => 'date_entree',
            'ville' => 'ville',
            'city' => 'ville',
            'pays' => 'pays',
            'country' => 'pays',
            'actif' => 'actif',
        );
    }
}

if (!function_exists('business_employe_import_map_header_row')) {
    /**
     * @param array $header_cells valeurs première ligne
     * @return array<string,int> champ => index colonne
     */
    function business_employe_import_map_header_row(array $header_cells) {
        $aliases = business_employe_import_header_aliases();
        $allowed = array_flip(array(
            'nom', 'prenom', 'email', 'telephone', 'poste', 'matricule',
            'departement', 'date_entree', 'ville', 'pays', 'actif',
        ));
        $map = array();
        foreach ($header_cells as $col_idx => $raw) {
            if ($raw === null || $raw === '') {
                continue;
            }
            $slug = business_employe_import_normalize_header_key($raw);
            if ($slug === '') {
                continue;
            }
            $field = isset($aliases[$slug]) ? $aliases[$slug] : null;
            if ($field === null && isset($allowed[$slug])) {
                $field = $slug;
            }
            if ($field !== null && !isset($map[$field])) {
                $map[$field] = (int) $col_idx;
            }
        }
        return $map;
    }
}

if (!function_exists('business_employe_import_parse_actif')) {
    function business_employe_import_parse_actif($val) {
        if ($val === null || $val === '') {
            return 1;
        }
        if (is_numeric($val)) {
            return ((int) $val) !== 0 ? 1 : 0;
        }
        $s = mb_strtolower(trim((string) $val), 'UTF-8');
        if (in_array($s, array('oui', 'yes', 'y', 'o', 'true', 'vrai', 'actif', '1'), true)) {
            return 1;
        }
        if (in_array($s, array('non', 'no', 'n', 'false', 'faux', 'inactif', '0'), true)) {
            return 0;
        }
        return 1;
    }
}

if (!function_exists('business_employe_import_validate_db_row')) {
    /**
     * @param array $row champs alignés sur la table (hors id_marchand)
     * @return array{ok:bool,errors:string[],row:array}
     */
    function business_employe_import_validate_db_row(array $row) {
        $errors = array();
        $nom = isset($row['nom']) ? trim((string) $row['nom']) : '';
        $prenom = isset($row['prenom']) ? trim((string) $row['prenom']) : '';
        if (mb_strlen($nom) < 2) {
            $errors[] = 'Nom trop court ou manquant.';
        }
        if (mb_strlen($prenom) < 2) {
            $errors[] = 'Prénom trop court ou manquant.';
        }
        $email = isset($row['email']) ? trim((string) $row['email']) : '';
        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email invalide.';
        }
        if ($email !== '' && strlen($email) > 255) {
            $errors[] = 'Email trop long.';
        }
        $tel = isset($row['telephone']) ? trim((string) $row['telephone']) : '';
        if (strlen($tel) > 50) {
            $errors[] = 'Téléphone trop long.';
        }
        foreach (array('poste' => 128, 'matricule' => 64, 'departement' => 128, 'ville' => 120, 'pays' => 3) as $f => $max) {
            $v = isset($row[$f]) ? trim((string) $row[$f]) : '';
            if (strlen($v) > $max) {
                $errors[] = 'Champ « ' . $f . ' » trop long.';
            }
        }
        $de = isset($row['date_entree']) ? $row['date_entree'] : null;
        if ($de !== null && $de !== '') {
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', (string) $de)) {
                $errors[] = 'Date d’entrée invalide.';
            }
        }
        $actif = 1;
        if (array_key_exists('actif', $row)) {
            $actif = ((int) $row['actif'] === 1) ? 1 : 0;
        }
        $out = array(
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => ($email === '') ? null : $email,
            'telephone' => ($tel === '') ? null : $tel,
            'poste' => (isset($row['poste']) && trim((string) $row['poste']) !== '') ? trim((string) $row['poste']) : null,
            'matricule' => (isset($row['matricule']) && trim((string) $row['matricule']) !== '') ? trim((string) $row['matricule']) : null,
            'departement' => (isset($row['departement']) && trim((string) $row['departement']) !== '') ? trim((string) $row['departement']) : null,
            'date_entree' => ($de !== null && $de !== '') ? (string) $de : null,
            'ville' => (isset($row['ville']) && trim((string) $row['ville']) !== '') ? trim((string) $row['ville']) : null,
            'pays' => (isset($row['pays']) && trim((string) $row['pays']) !== '') ? strtoupper(substr(trim((string) $row['pays']), 0, 3)) : null,
            'actif' => $actif,
            'id_utilisateur_business' => null,
            'id_service' => null,
            'id_ref_poste_fonction' => null,
            'id_utilisateur_application' => null,
        );
        return array(
            'ok' => empty($errors),
            'errors' => $errors,
            'row' => $out,
        );
    }
}
