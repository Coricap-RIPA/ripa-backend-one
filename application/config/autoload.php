<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------
| AUTO-LOADER
| -------------------------------------------------------------------
| This file specifies which systems should be loaded by default.
|
| In order to keep the framework as light-weight as possible only the
| absolute minimal resources are loaded by default. For example,
| the database is not connected to automatically since no assumption
| is made regarding whether you intend to use it.  This file lets
| you globally define which systems you would like loaded with every
| request.
|
| -------------------------------------------------------------------
| Instructions
| -------------------------------------------------------------------
|
| These are the things you can load automatically:
|
| 1. Packages
| 2. Libraries
| 3. Drivers
| 4. Helper files
| 5. Custom config files
| 6. Language files
| 7. Models
|
*/

/*
| -------------------------------------------------------------------
|  Auto-load Packages
| -------------------------------------------------------------------
| Prototype:
|
|  $autoload['packages'] = array(APPPATH.'third_party', '/usr/local/shared');
|
*/
$autoload['packages'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Libraries
| -------------------------------------------------------------------
| These are the classes located in system/libraries/ or your
| application/libraries/ directory, with the addition of the
| 'database' library, which is somewhat of a special case.
|
| Prototype:
|
|	$autoload['libraries'] = array('database', 'email', 'session');
|
| You can also supply an alternative library name to be assigned
| in the controller:
|
|	$autoload['libraries'] = array('user_agent' => 'ua');
*/

$autoload['libraries'] = array('database', 'pagination', 'session', 'form_validation','grocery_CRUD','user_agent','Fpdfroundedrecalpha','JWT_Library','Response_format');

/*
| -------------------------------------------------------------------
|  Auto-load Drivers
| -------------------------------------------------------------------
| These classes are located in system/libraries/ or in your
| application/libraries/ directory, but are also placed inside their
| own subdirectory and they extend the CI_Driver_Library class. They
| offer multiple interchangeable driver options.
|
| Prototype:
|
|	$autoload['drivers'] = array('cache');
|
| You can also supply an alternative property name to be assigned in
| the controller:
|
|	$autoload['drivers'] = array('cache' => 'cch');
|
*/
$autoload['drivers'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Helper Files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['helper'] = array('url', 'file');
*/
$autoload['helper'] = array('security','form','url','custom','cookie','string');

/*
| -------------------------------------------------------------------
|  Auto-load Config files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['config'] = array('config1', 'config2');
|
| NOTE: This item is intended for use ONLY if you have created custom
| config files.  Otherwise, leave it blank.
|
*/
$autoload['config'] = array('pagination');

/*
| -------------------------------------------------------------------
|  Auto-load Language files
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['language'] = array('lang1', 'lang2');
|
| NOTE: Do not include the "_lang" part of your file.  For example
| "codeigniter_lang.php" would be referenced as array('codeigniter');
|
*/
$autoload['language'] = array();

/*
| -------------------------------------------------------------------
|  Auto-load Models
| -------------------------------------------------------------------
| Prototype:
|
|	$autoload['model'] = array('first_model', 'second_model');
|
| You can also supply an alternative model name to be assigned
| in the controller:
|
|	$autoload['model'] = array('first_model' => 'first');
*/
$autoload['model'] = array(
	'Action_utilisateur_model', 'Role_model', 'Utilisateur_model', 'Group_fonctionnalite_model', 'Fonctionnalite_model', 'Role_permission_model', 'Sexe_model', 'Etat_civil_model', 'Devise_model', 'Commande_model', 'Article_commande_model', 'Client_model', 'Publicite_model', 'Sous_categorie_model', 'Categorie_sous_categorie_model', 'Fournisseur_model', 'Entreprise_model', 'Article_commande_client_model', 'Bureau_douane_model', 'Mode_transport_model', 'Vehicule_dedouane_model', 'Provision_model', 'Paiement_model', 'Entreprise_model', 'Taux_model', 'Commission_model', 'Facture_model', 'ModelGetTableRow', 'Article_model', 'Status_commande_model', 'Type_mobile_money_model', 'Compte_money_model', 'Article_model', 'Article_sortie_vente_model', 'Sortie_stock_model', 'Entree_stock_model', 'Facture_model', 'Facture_index_model', 'Reference_index_model', 'Transaction_model', 'Marchand_model', 'User_model', 'Kyc_model', 'Notification_model',
	// Portail marchand B2B (models/business/) — noms distincts pour ne pas écraser Marchand_model / Transaction_model legacy
	'business/business_marchand_model',
	'business/utilisateur_business_model' => 'ub_model',
	'business/service_model',
	'business/employe_model',
	'business/business_transaction_model',
);
