<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $session = \Config\Services::session();
	$success_msg= $session->getFlashdata('success_msg');
	$error_msg= $session->getFlashdata('error_msg');
	$id_company = $_GET['id_company'];
	$company =	$company[0];
?>
<!DOCTYPE html>
<style>
* {font-family: 'Roboto';}
#img{width:100%; height:100%; position: absolute;}
#form{ top: 0; bottom: 0; left: 0; right: 0; margin: auto}
#skew{transform: skewX(-10deg) translateX(-15%);}
.form-control{ height:2.5rem;}
.bg-orange{ background-color: #ff8138}
.bg-orange:hover{opacity:0.9;}
.btn-warning:{ background-color: #f79425;}
@media (max-width: 990px) {body { background-image:url(<?php echo base_url('assets/img/theme/bg.jpg') ?>) } #skew{display:none;} form {padding:20px;} #form img {width:100px;} }

</style>

<main class="main h-100 w-100" style="margin-left:0px">
	<div class="h-100" style="overflow:hidden;">
		<div class="row h-100">
            <div class="col-md-7 col-lg-7 col-xl-8 bg-image hover-zoom" id="skew">
               	<img id="img" src="<?php echo base_url('assets/img/theme/bg.jpg') ?>" />
            </div>
        	<div class="col-10 col-sm-7 col-md-7 col-lg-5 col-xl-3" id="form">
        	    <div class="card-body">
        	        <div class="card ">
        				<div class="m-sm-4" >
        					<div class="text-center mb-5">
        						<img src="<?php echo base_url('assets/img/brand/logo.png') ?>" alt="DKMA" class="img-fluid rounded-circle" width="132" />
        					</div>
							<?php if($company['status'] == 'Finalizado'){ //se estudo for indisponivel?>
								<div class="mt-3 alert alert-danger alert-dismissible" role="alert">
									<div class="alert-message">Estudo indicado indisponível!</div>
									<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        						</div>
							<?php }else if(!$id_company){ //se o id do estudo não for inserido na url ?>
								<div class="mt-3 alert alert-danger alert-dismissible" role="alert">
									<div class="alert-message">Insira o ID do estudo na URL para poder logar</div>
									<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							<?php }else{ 
							if($error_msg){ //se email ou senha forem inseridos errado ?>
								<div class="mt-3 alert alert-danger alert-dismissible" role="alert">
									<div class="alert-message">
										<?php echo $error_msg; ?>
									</div>
									<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
							<?php } ?>
							<div class="mt-3 alert alert-danger alert-dismissible d-none" role="alert">
								<div class="alert-message">Por favor, selecione um cenário antes de logar com facebook</div>
								<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							<form role="form" class="mt-3" method="post" action="<?php echo base_url('index.php/client/login'); ?>">
								<div class="mb-3">
									<input class="form-control form-control-lg" type="email" name="email" placeholder="E-mail" autofocus />
								</div>
								<div class="mb-3">
									<input class="form-control form-control-lg" type="password" name="password" placeholder="Senha" />
								</div>
								<div class="form-group">
									<select class="form-control" id="scenario"  name="id_scenario" required>
										<option value="" selected disabled>Selecione um cenário</option>
										<?php foreach($scenarios as $scenario){ ?>
											<option  value="<?php echo $scenario['id'] ?>"><?php echo $scenario['name'] ?></option>
										<?php } ?>
									</select>
								</div>
								<input type="hidden" name="id_company" value="<?php echo $_GET['id_company'] ?>">
								<div class="col-lg-12 text-center mt-3">
									<input type="submit" value="Login" name="login" class="text-white btn btn-warning ">
									<button onclick="open_page(this)" type="button" class="btn btn-facebook"><i class="align-middle fab fa-facebook"></i> Entrar com Facebook</button>
								</div>
							</form>
                            <?php } ?>
                    	</div>
    				</div>
    			</div>
        	</div>
    	 </div>
    </div>
</div>

<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
<script>
function open_page(id){	
	let scenario = $('#scenario').val();
	if(scenario){
		window.location.href="<?php echo base_url('index.php/User_Authentication/authenticate_user?id_company='.$id_company.'&id_scenario=') ?>"+scenario;
	}else{
		$('.alert').removeClass('d-none');
	}		
}
</script>