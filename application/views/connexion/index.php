<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="fr"> <!--<![endif]-->

    <!-- BEGIN HEAD -->
<head>
    <meta charset="UTF-8">
    <title>BCORE Admin Dashboard Template | Login Page</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="description">
    <meta content="" name="author">
     <!--[if IE]>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <![endif]-->
    <!-- GLOBAL STYLES -->
    <!-- PAGE LEVEL STYLES -->
    <link rel="stylesheet" href="<?= plugins_url('bootstrap/css/bootstrap.css') ?>">
    <link rel="stylesheet" href="<?= css_url('login') ?>">
    <link rel="stylesheet" href="<?= plugins_url('magic/magic.css') ?>">
    <!-- END PAGE LEVEL STYLES -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>
    <!-- END HEAD -->

    <!-- BEGIN BODY -->
<body>
        <!-- PAGE CONTENT --> 
    <div class="container">
        <div class="text-center">
            <img src="<?= img_url('logo.png') ?>" id="logoimg" alt="Logo">
        </div>

        <?= $this->session->flashdata('success_flash'); ?>
        <?= $this->session->flashdata('info_flash'); ?>
        <?= $this->session->flashdata('warning_flash'); ?>
        <?= $this->session->flashdata('danger_flash'); ?>

        <!-- alert -->
        <?php if ($this->session->flashdata('flashdata')): ?>
            <?= $this->session->flashdata('flashdata'); ?>
        <?php endif ?>
        <!-- <p class="alert alert-danger animated fadeIn text-justify well-sm">Veuillez renseiger correctement tous les champs.</p> -->
        <!-- alert-End -->
        
        <div class="tab-content">
            <div id="login" class="tab-pane active">
                <form id="form_login" method="POST" action="<?= href_url('connexion') ?>" class="form-signin">
                    <p class="text-muted text-center btn-block btn btn-primary btn-rect">
                        Enter your username and password
                    </p>
                    <input type="text" name="LOGIN" placeholder="Username" class="form-control">
                    <input type="password" name="PASSWORD_UT" placeholder="Password" class="form-control">
                    <br>
                    <br>
                    <button class="btn text-muted text-center btn-danger" type="submit">Sign in</button>
                </form>
            </div>
        </div>
        <div class="text-center">
            <ul class="list-inline">
                <li><a class="text-muted" href="#login" data-toggle="tab">Login</a></li>
                <li><a class="text-muted" href="#forgot" data-toggle="tab">Forgot Password</a></li>
                <li><a class="text-muted" href="#signup" data-toggle="tab">Signup</a></li>
            </ul>
        </div>
    </div>
        <!--END PAGE CONTENT -->     
        <!-- PAGE LEVEL SCRIPTS -->
    <script src="<?= plugins_url('jquery-2.0.3.min.js') ?>"></script>
    <script src="<?= plugins_url('bootstrap/js/bootstrap.js') ?>"></script>
    <script src="<?= js_url('login') ?>"></script>

    <!-- VALIDATION-ENGINE -->
    <script src="<?= plugins_url('bootstrap/js/bootstrap.js') ?>"></script>
    <script src="<?= asset_url('custom/jquery.validate.js') ?>"></script>
    <!--END PAGE LEVEL SCRIPTS -->
    <script type="text/javascript">
        jQuery.validator.addMethod("passwordvalid", function (value, element) {
            return  /[\S]{8,}/.test(value) &&
                    /[A-Z]{1,}/.test(value) &&
                    /[a-z]{1,}/.test(value) &&
                    /[\d]{1,}/.test(value) &&
                    /[$@()%ù&#*-/^\\&£¨?<>\[\]!:+]{1,}/.test(value);
        }, "Mot de passe invalide ");
        var jq = $('#form_login').validate({rules: {LOGIN:{required: true,},PASSWORD_UT: {required: true, passwordvalid: true}}});
    </script>
    <script type="text/javascript">
        $(document).ready(function(){

            $("#btnconx").click(function(e){
                e.preventDefault();

                $("#btncreate").attr("disabled", true);
                $('#content_panel').html('<div class="panel-body p-md p-xl"><h2 class="panel-title text-center">Traitement de la demande en cours, veuillez patienter...</h2><div class="progress progress-striped light active mt-md"><div class="progress-bar progress-bar-primary" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div></div></div>');

                $('#createmodal').modal('show');

                var fd = new FormData();
                var attribu = "";
                var fileName = "";
               
                var infoDoss = new Object();

                    infoDoss.ID_CAT = $("#ID_CAT").val();
                    infoDoss.CIVILITE_FND = $("#CIVILITE_FND").val();
                    infoDoss.NOM_FND = $("#NOM_FND").val();
                    infoDoss.PRENOM_FND = $("#PRENOM_FND").val();
                    infoDoss.DATE_NAISS_FND = $("#DATE_NAISS_FND").val();

                    infoDoss.CIVILITE_DRG = $("#CIVILITE_DRG").val();
                    infoDoss.NOM_DRG = $("#NOM_DRG").val();
                    infoDoss.PRENOM_DRG = $("#PRENOM_DRG").val();
                    infoDoss.DATE_NAISS_DRG = $("#DATE_NAISS_DRG").val();

                    infoDoss.RAISON_SOCIAL = $("#RAISON_SOCIAL").val();
                    infoDoss.SIGLE = $("#SIGLE").val();
                    infoDoss.ID_TYP_ENT = $("#ID_TYP_ENT").val();
                    infoDoss.RCCM_DOSS = $("#RCCM_DOSS").val();
                    infoDoss.DATE_CREATION = $("#DATE_CREATION").val();
                    infoDoss.CAPITAL_DOSS = $("#CAPITAL_DOSS").val();
                    infoDoss.SIEGE_SOCIAL_DOSS = $("#SIEGE_SOCIAL_DOSS").val();

                    infoDoss.ADRESS_POST = $("#ADRESS_POST").val();
                    infoDoss.ID_VIL = $("#ID_VIL").val();
                    infoDoss.TELEPHONE_FIXE = $("#TELEPHONE_FIXE").val();
                    infoDoss.TELEPHONE_MOBILE = $("#TELEPHONE_MOBILE").val();
                    infoDoss.SITE_WEB = $("#SITE_WEB").val();
                    infoDoss.EMAIL_DOSS = $("#EMAIL_DOSS").val();

                    infoDoss.ID_DOM = $("#ID_DOM").val();
                    infoDoss.AUTRE_DOMAINE = $("#AUTRE_DOMAINE").val();
                    infoDoss.DESCRIPTION = $("#DESCRIPTION").val();

                    infoDoss.CMPT_CONTRUBUABLE = $("#CMPT_CONTRUBUABLE").val();
                    infoDoss.ID_C_IMP = $("#ID_C_IMP").val();
                    infoDoss.ID_REG = $("#ID_REG").val();

                    infoDoss.NUM_CNPS = $("#NUM_CNPS").val();
                    infoDoss.NBR_EMP_DECL = $("#NBR_EMP_DECL").val();
                    infoDoss.NBR_EMP_PART = $("#NBR_EMP_PART").val();


                    infoDoss.CIVILITE_PERS_CT_DOSS = $("#CIVILITE_PERS_CT_DOSS").val();
                    infoDoss.NOM_PERS_CT_DOSS = $("#NOM_PERS_CT_DOSS").val();
                    infoDoss.PRENOM_PERS_CT_DOSS = $("#PRENOM_PERS_CT_DOSS").val();
                    infoDoss.RESP_PERS_CT_DOSS = $("#RESP_PERS_CT_DOSS").val();
                    infoDoss.EMAIL_PERS_CT_DOSS = $("#EMAIL_PERS_CT_DOSS").val();
                    infoDoss.MOBILE_PERS_CT_DOSS = $("#MOBILE_PERS_CT_DOSS").val();
                    infoDoss.FIXE_PERS_CT_DOSS = $("#FIXE_PERS_CT_DOSS").val();


                    infoDoss.ANNEE_CA_1 = $("#ANNEE_CA_1").val();
                    infoDoss.NBR_EMP_CA_1 = $("#NBR_EMP_CA_1").val();
                    infoDoss.MONTANT_CA_1 = $("#MONTANT_CA_1").val();

                    infoDoss.ANNEE_CA_2 = $("#ANNEE_CA_2").val();
                    infoDoss.NBR_EMP_CA_2 = $("#NBR_EMP_CA_2").val();
                    infoDoss.MONTANT_CA_2 = $("#MONTANT_CA_2").val();

                    infoDoss.ANNEE_CA_3 = $("#ANNEE_CA_3").val();
                    infoDoss.NBR_EMP_CA_3 = $("#NBR_EMP_CA_3").val();
                    infoDoss.MONTANT_CA_3 = $("#MONTANT_CA_3").val();

                var file1 = $('#file1').val().replace(/.*(\/|\\)/, '');
                var file2 = $('#file2').val().replace(/.*(\/|\\)/, '');
                var file3 = $('#file3').val().replace(/.*(\/|\\)/, '');
                var file4 = $('#file4').val().replace(/.*(\/|\\)/, '');
                var file5 = $('#file5').val().replace(/.*(\/|\\)/, '');

                $('input[type="file"]').each(function(){
                    if($(this).val()){
                        attribu = $(this).attr('name');
                        fileName = fileName + attribu + ",";
                        fd.append(attribu,$(this)[0].files[0]);
                        fd.append(attribu,$(this)[0].files[0]);
                    }
                })

                fd.append('fileName',fileName);

                    fd.append('file1',file1);
                    fd.append('file2',file2);
                    fd.append('file3',file3);
                    fd.append('file4',file4);
                    fd.append('file5',file5);

                fd.append('infoDoss', JSON.stringify(infoDoss));

                <?php if ($this->config->item('dev_env')): /* si nous somme en environnement de developpement */ ?>
                    console.log(JSON.stringify(infoDoss));
                    console.log(fd);
                    alert('Environnement de developpement activé')                
                <?php endif ?>

                    $.ajax({
                        url: '<?= current_controller('add') ?>',
                        type: 'POST',   dataType: 'json',
                        data: fd,   contentType: false, processData: false,

                        success: function(json) {
                            var statut = json.statut;
                            console.log(statut);

                            if(statut==1) {
                                console.log(json.info);
                                $('#content_panel').html("");
                                $('#content_panel').html(json.content);
                            } else {
                                console.log(json.info);
                                $("#wz_terms").prop("checked", false);
                                $('#content_panel').html("");
                                $('#content_panel').html(json.content);
                            }
                        },
                        error: function(request,status,error){
                            alert(request.responseText);
                            alert(status);
                            alert(error);
                        }
                    });
            });
        })
    </script>
</body>
    <!-- END BODY -->
</html>
