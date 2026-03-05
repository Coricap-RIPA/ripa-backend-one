
<div class="row" style=" background-color: #270345; height: 55px;">

    <div class="col s2 <?php if(isset($dashboard_link_active)) echo $dashboard_link_active; ?>">
        <a href="<?php echo site_url('Dashboard/index/'); ?>">
            <img src="<?php echo base_url('assets/dore_assets/img/logoappwhite.png') ?>" style="width: 150px; height: 45px; margin-top: 5px; border-radius: 10px;"/> <b style="position: relative; top: 8px; color:#fff; font-size: 18px;"></b>
        </a>
    </div>

    <?php if(  check_privilege('transaction', $this->session->user['id_role'], 'voir')   ){ ?>
        <div class="col s2 <?php if(isset($transaction_recu_link_active)) echo $transaction_recu_link_active; ?> text-center ">
            <a href="<?php echo site_url('Transaction/recu/'); ?>"> <b class="white-text" style="font-size: 12px; position: relative; top: 15px;"> <i class="fa fa-indent"></i> PAIEMENTS RECUS</b> </a>
        </div>
    <?php }?>

    <?php if( check_privilege('transaction', $this->session->user['id_role'], 'voir') ){ ?>
        <div class="col s2 <?php if(isset($transaction_sorti_link_active)) echo $transaction_sorti_link_active; ?> text-center ">
            <a href="<?php echo site_url('Transaction/sorti/'); ?>"><b class="white-text" style="font-size: 12px; position: relative; top: 15px;"> <i class="fa fa-outdent"></i> PAIEMENTS EFFECTUES</b> </a>
        </div>
    <?php }?>

    <?php if(  check_privilege('marchand_fiche', $this->session->user['id_role'], 'voir')  ){ ?>
        <div class="col s4 <?php if(isset($marchand_fiche_link_active)) echo $marchand_fiche_link_active; ?> text-center ">
            <a href="<?php echo site_url('Marchand_fiche/index/'); ?>"><b class="white-text" style="font-size: 12px; position: relative; top: 12px;"> <i class="fa fa-file-text"></i> FORMULAIRES MARCHANDS </b> </a>
        </div>
    <?php }?>


    
    
    <?php if( 
       
        check_privilege('compte', $this->session->user['id_role'], 'voir') OR 
        check_privilege('role', $this->session->user['id_role'], 'voir') OR 
        check_privilege('activite_user', $this->session->user['id_role'], 'voir') OR 
        check_privilege('compte_money', $this->session->user['id_role'], 'voir') OR 
        check_privilege('taux', $this->session->user['id_role'], 'voir') OR 
        check_privilege('entreprise', $this->session->user['id_role'], 'voir') OR 
        check_privilege('facture_index', $this->session->user['id_role'], 'voir') OR 
        check_privilege('reference_index', $this->session->user['id_role'], 'voir') OR 
        check_privilege('commission', $this->session->user['id_role'], 'voir') ) { ?>

        <div class="col s2 <?php if(isset($settings_link_active)) echo $settings_link_active; ?>  text-center">
            <h5 class="dropdown-trigger text-white" data-target='dropdown1'  style="font-size: 12px; position: relative; top: 5px;"> <b> <i class="fa fa-cogs"></i> CONFIGURATIONS </b> </h5>
            <ul id='dropdown1' class='dropdown-content' style="width: 300px !important;">

                <?php if (check_privilege('compte_money', $this->session->user['id_role'], 'voir') ) { ?>
                    <li>
                        <a href="<?php echo site_url('Compte_money/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-cog"></i>COMPTE MOBILE MONEY </b> <a>
                    </li>
                <?php } ?>
                <?php if (check_privilege('taux', $this->session->user['id_role'], 'voir') ) { ?>
                    <li>
                        <a href="<?php echo site_url('Taux_echange/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-exchange"></i> TAUX</b> <a>
                    </li>
                <?php } ?>
                <?php if (check_privilege('commission', $this->session->user['id_role'], 'voir') ) { ?>
                    <li>
                        <a href="<?php echo site_url('Commission/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-percent"></i> COMMISSION</b> <a>
                    </li>
                <?php } ?>
                <?php if (check_privilege('entreprise', $this->session->user['id_role'], 'voir') ) { ?>
                    <li>
                        <a href="<?php echo site_url('Entreprise/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-building"></i> MARCHANDS </b> <a>
                    </li>
                <?php } ?>
                <?php if (check_privilege('facture_index', $this->session->user['id_role'], 'voir') ) { ?>
                    <li>
                        <a href="<?php echo site_url('Facture_index/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-file-text"></i> FACTURE INDEX </b> <a>
                    </li>
                <?php } ?>
                <?php if (check_privilege('reference_index', $this->session->user['id_role'], 'voir') ) { ?>
                    <li>
                        <a href="<?php echo site_url('Reference_index/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-file-text"></i> REFERENCE INDEX </b> <a>
                    </li>
                <?php } ?>
                <?php if (check_privilege('compte', $this->session->user['id_role'], 'voir') ) { ?>
                    <li>
                        <a href="<?php echo site_url('Utilisateur/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-users"></i> UTILISATEURS </b> <a>
                    </li>
                <?php } ?>
                <?php if (check_privilege('role', $this->session->user['id_role'], 'voir') ) { ?>
                    <li>
                        <a href="<?php echo site_url('Role/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-users"></i> ROLES </b> <a>
                    </li>
                <?php } ?> 
                <?php if (check_privilege('activite_user', $this->session->user['id_role'], 'voir')) { ?>
                    <li>
                        <a href="<?php echo site_url('Action_utilisateur/index/')  ?>"><b style="font-size:12px;"> <i class="fa fa-file-text"></i> JOURNALS SYSTEMES </b> <a>
                    </li>
                <?php } ?>

                <?php if (true) { ?>
                    <li>
                        <a href="<?php echo site_url('Starter/disconnect/'); ?>"><b class="center-text" style="font-size:12px;"> <i class="fa fa-sign-out"></i> DECONNEXION </b> </a>
                    </li>
                <?php } ?>

            </ul>
        </div>
    <?php }?>

</div>
