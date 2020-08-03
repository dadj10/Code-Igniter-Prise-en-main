<!--PAGE CONTENT -->
        <div id="content">

            <div class="inner">
                <div class="row">
                    <div class="col-lg-12">


                        <h2> <?= $heading ?> </h2>



                    </div>
                </div>

                <hr />

				
				
				
				

                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <p class="alert alert-success animated fadein"><span class="icon-check"></span> Succès utilisateur ajouté.</p>
                                <?= $this->session->flashdata('success_flash'); ?>
                                <?= $this->session->flashdata('info_flash'); ?>
                                <?= $this->session->flashdata('warning_flash'); ?>
                                <?= $this->session->flashdata('danger_flash'); ?>

                                <p class="<?= (isset($info))? $class : "hidden" ?>" class="">
                                    <span class="<?= (isset($info))? $faclass : "hidden" ?>"></span>
                                    <span class="text-bold"><?= (isset($alert))? $alert : "" ?></span> <?= (isset($info))? $info : "" ?>
                                </p>
                            </div>   						
    						
    						
    						
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>NOM</th>
                                                <th>E-MAIL</th>
                                                <th>LOGIN</th>
                                                <th>PRIVILEGE</th>
                                                <th>ETAT</th>
                                                <th>DATE MODIFICATION</th>
                                                <th class="hidden-print">ACTIONS</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $cpt=1; foreach ($dataUsers as $key): ?>
                                                
                                            <tr class="odd gradeX">
                                                <td><?= $cpt; ?></td>
                                                <td class="text-uppercase"><?= $key->NOM_UT; ?></td>
                                                <td><?= $key->EMAIL_UT; ?></td>
                                                <td><?= $key->LOGIN_UT; ?></td>
                                                <td><?= $key->DESIGNATION_PRIV; ?></td>
                                                <td>
                                                    <?php if ($key->ETAT_UT ==1): ?>
                                                        <span class="text-success"><i class="fa fa-check"></i> ACTIF</span>
                                                    <?php else: ?>
                                                        <span class="text-danger"><i class="fa fa-times"></i> INACTIF</span>                                
                                                    <?php endif ?>
                                                </td>
                                                <td><?= $key->DATE_MODIF_UT; ?></td>
                                                <td class="hidden-print">
                                                    <a href="#update" class="btn btn-primary" title="Modifier" data-toggle="modal" data-target="#update" onclick="get(<?= $key->ID_UT; ?>)"><i class="icon-pencil"></i></a>
                                                    <a href="#delete" class="btn btn-danger" title="Modifier" onclick="load(<?= $key->ID_UT; ?>)"><i class="icon-trash"></i></a>
                                                </td>
                                            </tr>

                                            <?php $cpt++; endforeach ?>
                                        </tbody>
                                    </table>
                                </div>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>




        </div>
       <!--END PAGE CONTENT -->


       <div class="modal fade" id="update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel">Ajouter utilisateur</h4>
                    </div>
                    <form action="<?= href_url('utilisateur/create') ?>" method="post">                        
                        <div class="modal-body">
                           Notre formulaire d'ajout ser ici
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>