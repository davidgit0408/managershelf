<?php
    $id_company = service('request')->getGet('id_company');
    if(isset($company)) $company_name = $company[0]['name'];
    else $company_name = '';
    $session = \Config\Services::session();
?>
<style>
.splash .splash-icon{background:#d17701;}
.wrapper:before{background-color:#2a2a2a; }
</style>
<main class="row">
    <div class="wrapper mb-5" id="wrapper">
        <div class="container h-100  mt-5">         
            <div class="col-12 mt-5">   
                <div class="card">
                    <div class="text-center mb-4 mt-3">
                        <img src="<?php echo base_url('assets/img/brand/logo.png') ?>" alt="DKMA" class="img-fluid rounded-circle" width="112" />
                    </div>

                <div class="card-body p-5">
                    <?php if($session->get('id')){ ?>
                        <div class="row align-items-center col-12 ">
                            <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8" >
                                <h5>Bem vindo(a), <?php echo $user[0]['name'] ?>.</h5>
                            </div>                                        
                            <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right  mt-1" >
                                <button onclick="desloga_fb();" type="button" style="float:right" class="btn btn-facebook"><i class="align-middle fab fa-facebook"></i> Desconectar </button>
                            </div>
                        </div>
                        <p><?php echo $company_name ?></p>
                        <?if($scenarios && $id_company != '' && $id_company != 0 ){ ?>
                            <form role="form" class="mt-3" method="post" action="<?php echo base_url('index.php/client/login_client'); ?>">
                                <div class="col-12 mt-4">
                                    <div class="form-group col-6" style="margin: 0 auto;">
                                        <label>Por favor selecione um cenário para logar</label>
                                        <select class="form-control" id="scenario"  name="id_scenario" required>
                                            <option value="" selected disabled>Selecione um cenário</option>
                                            <?php foreach($scenarios as $scenario){ ?>
                                                <option  value="<?php echo $scenario['id'] ?>"><?php echo $scenario['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="hidden" name="id_company" value="<?php echo $id_company; ?>">
                                        <div class="text-center mt-1">
                                            <button type="submit" class="btn btn-github  mt-2" >Logar </button>   
                                        </div>         
                                    </div>
                                </div>  
                            </form>
                        <?php }else{ ?>
                            <div class="mt-3 alert alert-danger alert-dismissible" role="alert">
                                <div class="alert-message">Nenhum cenário disponível para este estudo!</div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php } ?>
                    <?php }else{ ?>
                        <div class="mt-3 alert alert-danger alert-dismissible" role="alert">
                            <div class="alert-message">Você precisa logar para escolher um cenário!</div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <div class="text-center mt-1">
                            <a href="<?php echo base_url('index.php/client?id_company=0')?>"><button type="submit" class="btn btn-github mt-2" >Logar </button> </a>    
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

<script>
function desloga_fb(id){	
	window.location.href="<?php echo base_url('index.php/User_Authentication/logout') ?>";
}
</script>