<?php
    echo  $header;
    echo  $footer;
?>


<body class="sidebar-noneoverflow">
    <!-- BEGIN LOADER -->
    <div id="load_screen">
        <div class="loader">
            <div class="loader-content">
                <div class="spinner-grow align-self-center"></div>
            </div>
        </div>
    </div>
    <!--  END LOADER -->

    <div class="main_container" style="padding: 0.5%;">

        <div id="row_content" class="row">

            <div class="col s12">
                <fieldset>
                    <legend> CONFIGURATIONS / ROLES - PERMISSIONS </legend>
                </fieldset>
            </div>

            <div class="col s12 col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 layout-spacing">
                <div class="widget widget-chart-one">

                    <div class="row">

                        <div class="col-lg-3">
                            <a href="<?php echo site_url('Role'); ?>" class="btn btn-info btn-lg btn-block"> <i class="icon-long-arrow-left"></i> Retour aux roles</a>
                        </div>
                        <br><br><br>

                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header b-b">
                                    <h4>Les permissions du rôle <b><?php echo $role['designation'] ?></b></h4>
                                </div>
                                <div class="card-body b-b">

                                    <form action="<?php echo site_url("Role/save_permissions/" . $role['id_role']) ?>" method="post">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>Fonctionnalités</th>
                                                    <th>Voir</th>
                                                    <th>Ajouter</th>
                                                    <th>Editer</th>
                                                    <th>Supprimer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                foreach ($group_fonctionnalites as $groupe) {
                                                    foreach ($groupe['fonctionnalites'] as $fonctionnalite) {
                                                ?>
                                                        <input type="hidden" name="fonctionnalites[]" value="<?php echo $fonctionnalite['id_fonctionnalite'] ?>">
                                                        <?php
                                                        if (!empty($fonctionnalite['permission'])) {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $fonctionnalite['designation'] ?></td>
                                                                <td>
                                                                    <?php
                                                                    if ($fonctionnalite['permission']['peux_voir'] == 1) {
                                                                    ?>
                                                                        <input id="" name="can_view-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" checked />
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <input id="" name="can_view-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" />
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($fonctionnalite['permission']['peux_ajouter'] == 1) {
                                                                    ?>
                                                                        <input id="" name="can_add-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" checked />
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <input id="" name="can_add-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" />
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($fonctionnalite['permission']['peux_editer'] == 1) {
                                                                    ?>
                                                                        <input id="" name="can_edit-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" checked />
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <input id="" name="can_edit-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" />
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <?php
                                                                    if ($fonctionnalite['permission']['peux_supprimer'] == 1) {
                                                                    ?>
                                                                        <input id="" name="can_delete-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" checked />
                                                                    <?php
                                                                    } else {
                                                                    ?>
                                                                        <input id="" name="can_delete-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" />
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                </td>
                                                            </tr>
                                                        <?php
                                                        } else {
                                                        ?>
                                                            <tr>
                                                                <td><?php echo $fonctionnalite['designation'] ?></td>
                                                                <td>
                                                                    <input id="" name="can_view-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" />
                                                                </td>
                                                                <td>
                                                                    <input id="" name="can_add-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" />
                                                                </td>
                                                                <td>
                                                                    <input id="" name="can_edit-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" />
                                                                </td>
                                                                <td>
                                                                    <input id="" name="can_delete-<?php echo $fonctionnalite['id_fonctionnalite'] ?>" type="checkbox" />
                                                                </td>
                                                            </tr>
                                                <?php
                                                        }
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <td colspan="4"></td>
                                                    <td>
                                                        <button type="submit" class="btn btn-sm btn-primary">Enregistrer</button>
                                                    </td>
                                                </tr>
                                            </tfoot>

                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

</body>

</html>
<?php
