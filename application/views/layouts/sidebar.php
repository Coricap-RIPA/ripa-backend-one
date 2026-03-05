<!--  BEGIN SIDEBAR  -->

<div class="sidebar-wrapper sidebar-theme" id="nav_sidebar" style="display: <?php echo (isMobile()) ? 'none' : ''; ?>;">     
    <nav id="sidebar">
        <div class="profile-info">
            <figure class="user-cover-image"></figure>
            <div class="user-info">
                <img src="<?php echo base_url('assets/uploads/files/').$_SESSION['user']['photo'];  ?>" alt="avatar">
                <h6 class=""><?php echo  $_SESSION['user']['nom']; ?></h6>
                <p class=""><?php echo  $_SESSION['role']['designation']; ?></p>
            </div>
        </div>
        <div class="shadow-bottom"></div>
        <ul class="list-unstyled menu-categories" id="accordionExample">
            <?php  if(check_privilege('article', $this->session->user['id_role'], 'voir')){  ?>
                <li class="menu active">
                    <a href="<?php echo site_url("Article/index"); ?>"  aria-expanded="true" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span>  Articles </span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                </li>
            <?php }?>    

            <?php  if(check_privilege('commande', $this->session->user['id_role'], 'voir')){  ?>
                <li class="menu">
                    <a href="<?php echo site_url('Commande')?>"   aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-crosshair"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="18" y2="12"></line><line x1="6" y1="12" x2="2" y2="12"></line><line x1="12" y1="6" x2="12" y2="2"></line><line x1="12" y1="22" x2="12" y2="18"></line></svg>
                            <span>Commandes </span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                </li>
            <?php }?>

            <?php  if(check_privilege('pub', $this->session->user['id_role'], 'voir')){  ?>
            <li class="menu">
                <a href="<?php echo site_url('Publicite')?>"    aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-crosshair"><circle cx="12" cy="12" r="10"></circle><line x1="22" y1="12" x2="18" y2="12"></line><line x1="6" y1="12" x2="2" y2="12"></line><line x1="12" y1="6" x2="12" y2="2"></line><line x1="12" y1="22" x2="12" y2="18"></line></svg>
                        <span>Publicité </span>
                    </div>
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                    </div>
                </a>
            </li>
            <?php }?>


           
            <?php  if(check_privilege('fournisseur', $this->session->user['id_role'], 'voir') OR check_privilege('compte', $this->session->user['id_role'], 'voir') OR check_privilege('compte', $this->session->user['id_role'], 'voir') OR check_privilege('role', $this->session->user['id_role'], 'voir') OR check_privilege('activite_user', $this->session->user['id_role'], 'voir') OR check_privilege('categorie', $this->session->user['id_role'], 'voir') OR check_privilege('sous_categorie', $this->session->user['id_role'], 'voir') OR check_privilege('client', $this->session->user['id_role'], 'voir')){  ?>
                <li class="menu">
                    <a href="#pages" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle">
                        <div class="">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>    
                            <span>CONFIGURATIONS</span>
                        </div>
                        <div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg>
                        </div>
                    </a>
                    <ul class="collapse submenu list-unstyled" id="pages" data-parent="#accordionExample">

                        <?php  if(check_privilege('client', $this->session->user['id_role'], 'voir')){  ?>
                            <li>
                                <a href="#settings_4_client" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> CLIENTS <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="settings_4_client" data-parent="#pages"> 
                                    <li>
                                        <a href="<?php echo site_url("Client"); ?>"> Voir la liste </a>
                                    </li>
                                    <?php  if(check_privilege('client', $this->session->user['id_role'], 'ajouter')){  ?>
                                        <li>
                                            <a href="<?php echo site_url("Client/index/add"); ?>"> Ajouter à la liste </a>
                                        </li>
                                    <?php }?>

                                </ul>
                            </li>
                        <?php }?>


                        <?php  if(check_privilege('fournisseur', $this->session->user['id_role'], 'voir')){  ?>
                            <li>
                                <a href="#settings_4" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> FOURNISSEURS <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="settings_4" data-parent="#pages"> 
                                    <li>
                                        <a href="<?php echo site_url("Fournisseur"); ?>"> Voir la liste </a>
                                    </li>
                                    <?php  if(check_privilege('fournisseur', $this->session->user['id_role'], 'ajouter')){  ?>
                                        <li>
                                            <a href="<?php echo site_url("Fournisseur/index/add"); ?>"> Ajouter à la liste </a>
                                        </li>
                                    <?php }?>

                                </ul>
                            </li>
                        <?php }?>

                        <?php  if(check_privilege('categorie', $this->session->user['id_role'], 'voir')){  ?>
                            <li>
                                <a href="#settings_cat" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> CATEGORIES <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="settings_cat" data-parent="#pages"> 
                                    <li>
                                        <a href="<?php echo site_url("Categorie"); ?>"> Voir la liste </a>
                                    </li>
                                    <?php  if(check_privilege('categorie', $this->session->user['id_role'], 'ajouter')){  ?>
                                        <li>
                                            <a href="<?php echo site_url("Categorie/index/add"); ?>"> Ajouter à la liste </a>
                                        </li>
                                    <?php }?>

                                </ul>
                            </li>
                        <?php }?>

                        <?php  if(check_privilege('sous_categorie', $this->session->user['id_role'], 'voir')){  ?>
                            <li>
                                <a href="#settings_sous_cat" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> SOUS CATEGORIES <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="settings_sous_cat" data-parent="#pages"> 
                                    <li>
                                        <a href="<?php echo site_url("Sous_categorie"); ?>"> Voir la liste </a>
                                    </li>
                                    <?php  if(check_privilege('sous_categorie', $this->session->user['id_role'], 'ajouter')){  ?>
                                        <li>
                                            <a href="<?php echo site_url("Sous_categorie/index/add"); ?>"> Ajouter à la liste </a>
                                        </li>
                                    <?php }?>

                                </ul>
                            </li>
                        <?php }?>

                        <?php  if(check_privilege('compte', $this->session->user['id_role'], 'voir')){  ?>
                            <li>
                                <a href="#settings_3" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> UTILISATEURS <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="settings_3" data-parent="#pages"> 
                                    <li>
                                    <a href="<?php echo site_url("Utilisateur"); ?>"> Voir la liste  </a>
                                    </li>
                                    <?php  if(check_privilege('compte', $this->session->user['id_role'], 'ajouter')){  ?>
                                        <li>
                                            <a href="<?php echo site_url("Utilisateur/index/add"); ?>"> Ajouter à la liste </a>
                                        </li>
                                    <?php }?>
                                </ul>
                            </li>
                        <?php }?>

                        <?php  if(check_privilege('role', $this->session->user['id_role'], 'voir')){  ?>
                            <li>
                                <a href="#settings_4_roles" data-toggle="collapse" aria-expanded="false" class="dropdown-toggle"> ROLES <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-chevron-right"><polyline points="9 18 15 12 9 6"></polyline></svg> </a>
                                <ul class="collapse list-unstyled sub-submenu" id="settings_4_roles" data-parent="#pages"> 
                                    <li>
                                        <a href="<?php echo site_url("Role"); ?>"> Voir la liste </a>
                                    </li>
                                    <?php  if(check_privilege('role', $this->session->user['id_role'], 'ajouter')){  ?>
                                        <li>
                                            <a href="<?php echo site_url("Role/index/add"); ?>"> Ajouter à la liste </a>
                                        </li>
                                    <?php }?>

                                </ul>
                            </li>
                        <?php }?>


                       

                        <?php  if(check_privilege('activite_user', $this->session->user['id_role'], 'voir')){  ?>                                        
                            <li>
                                <a href="<?php echo site_url("Action_utilisateur"); ?>"  aria-expanded="false" class="dropdown-toggle"> JOURNAL SYSTEME </a>
                            </li>
                        <?php }?>

                    </ul>
                </li> 
            <?php }?>


        </ul> 
    </nav>
</div>
<!--  END SIDEBAR  -->