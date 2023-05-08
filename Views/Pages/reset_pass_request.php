<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $session = \Config\Services::session();
?>
<!DOCTYPE html>
<style>
#img{
    width:100%;
    height:100%;
    position: absolute;
}

#form{
    top: 0; bottom: 0;
    left: 0; right: 0;
    margin: auto;
}
#skew{
    transform: skewX(-10deg) translateX(-15%);
}
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
        					<div class="text-center mb-4 mt-3">
        						<img src="<?php echo base_url('assets/img/brand/logo.png') ?>" alt="DKMA" class="img-fluid rounded-circle" style="width: 80%;" />
        					</div>
        					<?php 
								$success_msg= $session->getFlashdata('success_msg');
								$error_msg= $session->getFlashdata('error_msg');
			
								if($error_msg){ ?>
								<div class="mt-3 alert alert-danger alert-dismissible" role="alert">
									<div class="alert-message">
										<?php echo $error_msg; ?>
									</div>
									<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
								</div>
                            <?php 
								}
								if($success_msg){ ?>
									<div class="mt-3 alert alert-success alert-dismissible" role="alert">
										<div class="alert-message">
											<?php echo $success_msg; ?>
										</div>
										<button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
									</div>
							<?php 
								} 
							?>
        					<form role="form" method="post" action="<?php echo base_url('index.php/password_reset/key'); ?>">
        						<div class="mb-3">
									<div class="mb-3">
										<h3 class="m-0">Insira seu email para a recuperação de senha.</h3>
										<small>Enviaremos um link para redefinição de senha no email inserido.</small>
									</div>
        							<input class="form-control form-control-lg" type="email" name="email" placeholder="E-mail" autofocus />
        						</div>
        						<div class="text-center mt-3">
        							<input type="submit" value="Enviar" name="enviar" class="btn btn-lg btn-dark">
        						</div>
        					</form>
                    	</div>
    				</div>
    			</div>
        	</div>
    	 </div>
    </div>
</div>
