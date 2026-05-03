<?php defined('BASEPATH') OR exit('No direct script access allowed');
$CI =& get_instance();
$r1 = $CI->uri->segment(1);
$r2 = $CI->uri->segment(2);
$active = isset($nav_active) ? $nav_active : '';
if ($active === '') {
    if ($r2 === 'services') {
        $active = 'services';
    } elseif ($r2 === 'employes') {
        $active = 'employes';
    } elseif ($r2 === 'kyb') {
        $active = 'kyb';
    } elseif ($r2 === 'transactions') {
        $active = 'transactions';
    } elseif ($r1 === 'business' && (empty($r2) || $r2 === 'tableau-de-bord' || $r2 === 'dashboard')) {
        $active = 'home';
    }
}
?>
<header class="ripa-portal-nav" role="navigation">
    <div class="nav-inner">
        <a class="brand-block" href="<?php echo site_url('business'); ?>">
            <img src="<?php echo base_url('assets/dore_assets/img/logoappwhite.png'); ?>" alt="RIPA" />
            <div class="brand-text">
                <strong>Portail marchand</strong>
                <span>Espace professionnel</span>
            </div>
        </a>
        <div class="nav-links">
            <a class="<?php echo $active === 'home' ? 'is-active' : ''; ?>" href="<?php echo site_url('business'); ?>">Tableau de bord</a>
            <a class="<?php echo $active === 'services' ? 'is-active' : ''; ?>" href="<?php echo site_url('business/services'); ?>">Services</a>
            <a class="<?php echo $active === 'employes' ? 'is-active' : ''; ?>" href="<?php echo site_url('business/employes'); ?>">Employés</a>
            <a class="<?php echo $active === 'kyb' ? 'is-active' : ''; ?>" href="<?php echo site_url('business/kyb'); ?>">Dossier KYB</a>
            <a class="<?php echo $active === 'transactions' ? 'is-active' : ''; ?>" href="<?php echo site_url('business/transactions'); ?>">Transactions</a>
            <a class="btn-logout" href="<?php echo site_url('business/deconnexion'); ?>">Déconnexion</a>
        </div>
    </div>
</header>
