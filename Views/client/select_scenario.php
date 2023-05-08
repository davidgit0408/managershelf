<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $session = \Config\Services::session();
?>
<!DOCTYPE html>
<style>
body{
    background:url(<?php echo base_url('assets/img/theme/bg.jpg') ?>);
    background-size:100%;
    background-position:center;
}
</style>
<div class="container mt-9" style="height: 100%;">
    <div class="row" style="height: 100%;">
        <div class="col-md-5 offset-md-3" style="margin: auto;">
            <div class="login-panel panel panel-success bg-gradient-blue" style="border-radius: 20px;">
                <div class="panel-heading text-center" style="border:0.2px solid gray;padding: 10px;border-radius: 5px 5px 0 0;padding-top: 20px;">
                    <h3><img src="<?php echo base_url('assets/img/brand/logo.png') ?>" width="100px" /></h3>
                </div>
                <?php 
                  $success_msg= $session->getFlashdata('success_msg');
                  $error_msg= $session->getFlashdata('error_msg');

                  if($success_msg){
                    ?>
                    <div class="alert alert-success" style="margin:0;border-radius: 0px;">
                      <?php echo $success_msg; ?>
                    </div>
                  <?php
                  }
                  if($error_msg){
                    ?>
                    <div class="alert alert-danger mb-1" style="margin:0!important;border-radius: 0px;">
                      <?php echo $error_msg; ?>
                    </div>
                    <?php
                  }
                  ?>

                <div class="panel-body" style="border:0.2px solid gray;padding: 20px;border-radius: 1px;padding-bottom: 30px;">
                    <form role="form" method="post" action="<?php echo base_url('index.php/client/login'); ?>">
                        <fieldset>
                            <div class="form-group"  >
                                <input class="form-control" placeholder="E-mail" name="email" type="email" autofocus required>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="Senha" name="password" type="password" required>
                            </div>
                            <div class="form-group">
                                <select class="form-control" name="id_scenario" required>
                                    <option value="" selected disabled>Selecione um cenário</option>
                                    <?php foreach($scenarios as $scenario){ ?>
                                        <option value="<?php echo $scenario['id'] ?>"><?php echo $scenario['name'] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                            <input type="hidden" name="id_company" value="<?php echo $_GET['id_company'] ?>">
                            <input class="btn btn-lg btn-block bg-gradient-orange col-md-6 offset-md-3 text-white" type="submit" value="Login" name="login">
                            <a class="btn btn-cd btn-block bg-gradient-orange col-md-6 offset-md-3 text-white" value="Cadastro" name="register" href="<?php echo base_url() ?>/client/cadastro?id_company=22">Cadastro</a>

                        </fieldset>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
