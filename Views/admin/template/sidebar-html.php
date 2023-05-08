<?php
$user = $usuario;
$session = session();
$role = $session->get('role');
$permissions = $session->get('permissions');

$controller = $session->get('controller');

//verifica se o usuario está logado e caso contrario, retorna para a página de login//
if(empty($user)){
	header("Location: ".base_url());
	die();
}
//==================================================================================//

$name = $user['name'];
$email = $user['email'];
$img = $user['img_url'];
$views = 0;
?>
<style>
	* {
		font-family: 'Roboto', sans-serif
	}

	body {
		background-color: #e5e5e5;
	}

	.wrapper:before {
		background: #2a2a2a;
	}

	.sidebar-brand {
		background: #2a2a2a;
	}

	.sidebar-brand,
	.sidebar-brand:hover {
		background: #2a2a2a;
	}

	.navname {
		color: #f89e24;
		font-weight: 700;
		font-size: 20px;
	}

	.bg-orange {
		background: #f89e24;
	}

	.bg-orange:hover {
		opacity: 0.9;
	}

	.bg-blue {
		background: #52aeff;
	}

	.bg-blue:hover {
		opacity: 0.9;
	}

	.bg-success:hover {
		opacity: 0.9;
	}

	.text-orange {
		color: #f89e24;
	}

	.menu {
		font-weight: 700;
		color: #282525;
	}

	.menu:hover {
		color: #fafafa;
	}

	.bootstrap-datetimepicker-widget table td.active,
	.bootstrap-datetimepicker-widget table td.active:hover {
		background-color: #f89e24e0;
		color: #ffffff;
	}

	.bootstrap-datetimepicker-widget table td.today:before {
		border-color: rgba(0, 0, 0, .2) transparent #f89e24;
	}

	.splash .splash-icon {
		background: #d17701;
	}

	.btn-white {
		background-color: #fff;
		font-weight: 500;
	}

	.page-item.active .page-link {
		background-color: #f89e24;
		border-color: #f89e24;
	}

	.form-check-input:checked {
		background-color: #f89e24;
		border-color: #f89e24
	}

	.form-control-alternative {
		height: 2.5rem;
	}

	.list-group-item.active {
		background-color: #ececec;
		color: #616161;
		border-color: rgba(0, 0, 0, .125)
	}

	.bootstrap-datetimepicker-widget table td span.active {
		background-color: #f89e24
	}

	#notifications {
		overflow-y: scroll;
		max-height: 410px;
	}

	@media (max-width: 990px) {
		.sidebar-toggle {
			margin-top: 1rem;
		}

		.input-group {
			margin-top: 2rem;
		}
	}
</style>
<style>
	.noselect {
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
		-webkit-tap-highlight-color: transparent;
	}

	#delete_css {
		margin: 0 5px;
		width: 145px;
		height: 30px;
		cursor: pointer;
		display: flex;
		align-items: center;
		background: red;
		border: none;
		border-radius: 5px;
		box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.15);
		background: #ff0000af;
	}

	#delete_css,
	button span {
		transition: 200ms;
	}

	#delete_css .text {
		transform: translateX(35px);
		color: white;
		font-weight: bold;
		margin: 0px 0px -4px -8px;
	}

	#delete_css .icon {
		position: absolute;
		border-left: 1px solid #ff0000af;
		transform: translateX(110px);
		height: 15px;
		width: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	#delete_css svg {
		width: 15px !important;
		height: 30px;
		margin-right: 15px;
		fill: #eee;
	}

	#delete_css:hover {
		background: #ff0000;
	}

	#delete_css:hover .text {
		color: transparent;
	}

	#delete_css:hover .icon {
		width: 150px;
		border-left: none;
		transform: translateX(0);
	}

	#delete_css:focus {
		outline: none;
	}

	#cancel_css {
		margin: 0 5px;
		width: 145px;
		height: 30px;
		cursor: pointer;
		display: flex;
		align-items: center;
		background: blue;
		border: none;
		border-radius: 5px;
		box-shadow: 1px 1px 3px rgba(0, 0, 0, 0.15);
		background: #0051ffad;
	}

	#cancel_css,
	button span {
		transition: 200ms;
	}

	#cancel_css .text {
		transform: translateX(35px);
		color: white;
		font-weight: bold;
		margin: 0px 0px -4px -5px;
	}

	#cancel_css .icon {
		position: absolute;
		border-left: 1px solid #0051ffad;
		transform: translateX(110px);
		height: 15px;
		width: 40px;
		display: flex;
		align-items: center;
		justify-content: center;
	}

	#cancel_css svg {
		width: 15px !important;
		height: 30px;
		margin-right: 15px;
		fill: #eee;
	}

	#cancel_css:hover {
		background: #0051ff;
	}

	#cancel_css:hover .text {
		color: transparent;
	}

	#cancel_css:hover .icon {
		width: 150px;
		border-left: none;
		transform: translateX(0);
	}

	#cancel_css:focus {
		outline: none;
	}
</style>
<script>
	nova_view = "<?php echo $views; ?>"
    function release_field() {
        window.location.href = "<?php echo base_url('/index.php/new_planogram/0'); ?>";
    }
</script>
<div class="wrapper" id="wrapper">
    <div class="modal fade" id="Adicionar" tabindex="-1" role="dialog" aria-hidden="true" style=" padding-top: 10%; padding-bottom: 20%;" >
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Deseja criar um planograma para monitorar a quebra da gôndola?</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="text-center">
                        <h4 id="certeza1" class="mb-4">Se você escolher a opção sim, terá acesso a campos personalizados como estoque de produtos, localização dos produtos em corredores e gôndulas, também poderá integrar seu estoque com seu checkout</h4>
                        <a type="button" class="btn btn-success" id="btn-success1" onclick="release_field()" data-id="">SIM</a>
                        <a href="<?php echo base_url('/index.php/new_planogram/1'); ?>" type="button" class="btn btn-danger">NÃO</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
	<nav id="sidebar" class="sidebar">
		<a href="<?php echo base_url('index.php/dashboard') ?>" class="sidebar-brand">
			<img src="<?php echo base_url('assets/img/brand/logo_branco.png') ?>" class="img-fluid rounded-circle" alt="DKMA" style="width: 90%;height: auto;">
		</a>
		<div class="sidebar-content">
			<div class="sidebar-user">
				<a href="<?php echo base_url('index.php/myaccount'); ?>">
					<?php if (($img) && file_exists($img)) { ?><img src="<?php echo base_url($img); ?>" class="img-fluid rounded-circle mt-4" alt="DKMA" style="width:120px;height:120px;">
					<?php } else { ?><img src="<?php echo base_url('writable/uploads/perfil/user_default.png'); ?>" class="img-fluid rounded-circle mt-4" alt="DKMA" style="width:120px;height:120px;"><?php } ?>
				</a>
				<div class="navname mt-3"><?php echo $name ?></div>
				<small><?php echo $email ?></small><br>
				<?php if ($role == 'admin') { ?>
					<small><?php echo $role ?></small>
				<?php } ?>
			</div>

			<ul class="sidebar-nav mt-4">
				<li class="sidebar-item text-center" style="background-color: #f89e24; height:1.9rem; padding:2.5px;cursor:pointer;">
					<span class="align-middle menu">MENU</span>
				</li>

				<li class="sidebar-item mt-2">
					<?php if ($role == 'free') { ?><a class="sidebar-link" href="<?php echo base_url('index.php/free/dashboard') ?>"><span class="align-middle text-uppercase">Dashboard</span></a>
					<?php } else { ?><a class="sidebar-link" href="<?php echo base_url('index.php/dashboard') ?>"><span class="align-middle text-uppercase">Dashboard</span></a><?php } ?>
				</li>
				<li class="sidebar-item">
					<a class="sidebar-link" href="<?php echo base_url('index.php/myaccount') ?>"><span class="align-middle text-uppercase">Minha conta</span></a>
				</li>
				<li class="sidebar-item">
					<a class="sidebar-link" href="<?php echo base_url('index.php/painel_financeiro') ?>"><span class="align-middle text-uppercase">Painel Financeiro</span></a>
				</li>
				<li class="sidebar-item">
					<a data-bs-target="#produtos" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<span class="align-middle text-uppercase">Produtos</span>
					</a>
					<ul id="produtos" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item"><a class="sidebar-link" href="<?php echo base_url('index.php/all_products') ?>">- Todos Produtos</a></li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?php echo base_url('index.php/new_product') ?>">- Adicionar Produto</a></li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?php echo base_url('index.php/import_export') ?>">- Importar/Exportar</a></li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?php echo base_url('index.php/bigdata_products') ?>">- Base de produtos</a></li>
					</ul>
				</li>
				<li class="sidebar-item">
					<a data-bs-target="#cenarios" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<span class="align-middle text-uppercase">Planogramas</span>
					</a>
					<ul id="cenarios" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item"><a class="sidebar-link" href="<?php echo base_url('index.php/all_planograms') ?>">- Ver todos</a></li>
						<li class="sidebar-item"><a data-bs-toggle="modal" data-bs-target="#Adicionar" class="sidebar-link"">- Criar novo</a></li>
					</ul>
				</li>

				<li class="sidebar-item">
					<a data-bs-target="#estudos" data-bs-toggle="collapse" class="sidebar-link collapsed">
						<span class="align-middle text-uppercase">Estudos</span>
					</a>
					<ul id="estudos" class="sidebar-dropdown list-unstyled collapse " data-bs-parent="#sidebar">
						<li class="sidebar-item"><a class="sidebar-link" href="<?php echo base_url('index.php/all_company') ?>">- Todos Estudos</a></li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?php echo base_url('index.php/new_company') ?>">- Adicionar Estudo</a></li>
						<li class="sidebar-item">
							<a class="sidebar-link" href="<?php echo base_url('index.php/all_scenarios') ?>" style="display: flex;gap: 5px;">
								<span class="align-middle">- Cenários</span>
								<div id="info" title="Planogramas liberados para campo." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
							</a>
						</li>
						<li class="sidebar-item"><a class="sidebar-link" href="<?php echo base_url('index.php/form/results') ?>">- Ver Resultados</a></li>
						<li class="sidebar-item">
							<a class="sidebar-link" href="<?php echo base_url('index.php/all_orders') ?>">
								<span class="align-middle">- Relatórios</span>
							</a>
						</li>
					</ul>
				</li>

				<li class="sidebar-item">
					<a class="sidebar-link" href="<?php echo base_url('index.php/gallery') ?>">
						<span class="align-middle text-uppercase">Galeria de imagens</span>
					</a>
				</li>

				<li class="sidebar-item" style="display:none">
					<a class="sidebar-link collapsed" href="<?php echo base_url('index.php/all_categories') ?>">
						<span class="align-middle text-uppercase">Categorias</span>
					</a>
				</li>

				<?php if (1) { ?>
					<li class="sidebar-item">
						<a class="sidebar-link collapsed" href="<?php echo base_url('index.php/users') ?>">
							<span class="align-middle text-uppercase">Usuários</span>
						</a>
					</li>
				<?php } ?>
				<?php if (in_array('IMPORTAR_ENTREVISTADOS', $permissions)) { ?>
					<li class="sidebar-item">
						<a class="sidebar-link collapsed" href="<?php echo base_url('index.php/interviewed_import') ?>">
							<span class="align-middle text-uppercase">Importar Entrevistados</span>
						</a>
					</li>
				<?php } ?>
				<?php if (in_array('MENU_VERSOES', $permissions)) { ?>
					<li class="sidebar-item">
						<a class="sidebar-link collapsed" href="<?php echo base_url('index.php/versions') ?>">
							<span class="align-middle text-uppercase">Versões</span>
						</a>
					</li>
				<?php } ?>
				<?php if (in_array('MENU_LOGS', $permissions)) { ?>
					<li class="sidebar-item">
						<a class="sidebar-link collapsed" href="<?php echo base_url('index.php/logs') ?>">
							<span class="align-middle text-uppercase">Logs</span>
						</a>
					</li>
				<?php } ?>
				<li class="sidebar-item" style="background-color: #f89e24;">
					<a class="sidebar-link collapsed" href="#!" style="color: #fff;background-color: #f89e24;">
						<span class="align-middle text-uppercase">Treinamentos</span>
					</a>
				</li>
				<li class="sidebar-item">
					<a class="sidebar-link collapsed" href="#!">
						<span class="align-middle text-uppercase">Integração</span>
					</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="modal fade bd-example-modal-sm" id="modal_notify" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-body" style="text-align: center;">
					<h5 id='mensagem_notify_delete'>Você deseja excluir todas as notificações?</h5>
				</div>
				<div class="modal-footer">
					<!--<button type="button" id="cancelar_deletar" style="margin: 1px 40px 1px 1px;" class="close btn btn-outline-secondary" data-dismiss="modal">Cancelar</button>
					<a type="button" onclick='deletar_notificacoes()' id="delete_notify" style="margin: 1px 16px 1px 1px;" class="btn btn-outline-danger"><span>Excluir Tudo</span></a>-->
					<div style="display: inline-flex;">
						<button id="cancel_css" data-toggle="modal" onclick="deletar_notificacoes()" class="noselect delete_notify" style="margin-right: 60px;">
						<span class="text" style="margin: 0px 0px 0px -9px !important;">Confirmar</span>
							<span class="icon">
								<svg width="24px" height="24px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M9.55 19.91C9.10487 19.9068 8.67832 19.7312 8.36 19.42L2.16 13.21C2.08924 13.1411 2.03301 13.0586 1.99461 12.9676C1.95621 12.8766 1.93643 12.7788 1.93643 12.68C1.93643 12.5812 1.95621 12.4834 1.99461 12.3924C2.03301 12.3014 2.08924 12.2189 2.16 12.15L4.57 9.73C4.89374 9.41061 5.33023 9.23153 5.785 9.23153C6.23978 9.23153 6.67626 9.41061 7 9.73L9.78 12.52C9.81356 12.5524 9.85837 12.5705 9.905 12.5705C9.95163 12.5705 9.99645 12.5524 10.03 12.52L17.38 5.17C17.7002 4.86318 18.1265 4.69189 18.57 4.69189C19.0135 4.69189 19.4398 4.86318 19.76 5.17L21.21 6.62C21.5233 6.93482 21.6991 7.36088 21.6991 7.805C21.6991 8.24913 21.5233 8.67518 21.21 8.99L10.74 19.42C10.4217 19.7312 9.99513 19.9068 9.55 19.91ZM3.75 12.68L9.42 18.35C9.45356 18.3824 9.49837 18.4005 9.545 18.4005C9.59163 18.4005 9.63645 18.3824 9.67 18.35L20.1 7.93C20.1324 7.89645 20.1505 7.85163 20.1505 7.805C20.1505 7.75837 20.1324 7.71356 20.1 7.68L18.65 6.23C18.6148 6.19696 18.5683 6.17857 18.52 6.17857C18.4717 6.17857 18.4252 6.19696 18.39 6.23L11 13.58C10.6803 13.8839 10.2561 14.0533 9.815 14.0533C9.37392 14.0533 8.9497 13.8839 8.63 13.58L5.89 10.79C5.87519 10.7694 5.85567 10.7525 5.83306 10.7409C5.81046 10.7293 5.78541 10.7233 5.76 10.7233C5.73459 10.7233 5.70955 10.7293 5.68694 10.7409C5.66433 10.7525 5.64482 10.7694 5.63 10.79L3.75 12.68Z" fill="black"></path>
								</svg>

							</span>
						</button>
						<button id="delete_css" data-toggle="modal" data-dismiss="modal" class="noselect cancelar_deletar">
							<span class='text' style="margin: 0px 0px 0px 0px !important;">Cancelar</span>
							<span class="icon">
								<svg width="200px" height="200px" viewBox="0 0 200 200" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg">
									<title />
									<path d="M114,100l49-49a9.9,9.9,0,0,0-14-14L100,86,51,37A9.9,9.9,0,0,0,37,51l49,49L37,149a9.9,9.9,0,0,0,14,14l49-49,49,49a9.9,9.9,0,0,0,14-14Z" />
								</svg>
							</span>
						</button>
					</div>
				</div>
				<script>
					

					
				</script>
			</div>
		</div>
	</div>
	
	<div class="main" style="background-color: #e5e5e5;">
		<nav class=" navbar navbar-expand navbar-theme">
			<div class="hide_hamburg">
				<a class="sidebar-toggle d-flex me-2">
					<i class="hamburger align-self-center"></i>
				</a>
			</div>
			<form class="d-none">
				<div class="col-md-6 mb-3" style="margin-top:1.1rem;margin-left:0.4rem;">
					<div class="input-group md-form form-sm form-1 pl-0">
						<span><i class="mt-2 fas fa-search text-white" aria-hidden="true"></i></span>
						<input class="form-control form-control-lite" type="text" placeholder="Busca...">
					</div>
				</div>
			</form>
			<div class="navbar-collapse collapse">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item dropdown ms-lg-2">
						<a class="nav-link dropdown-toggle position-relative dropdown" href="#" onclick="modal()" id="alertsDropdown">
							<i class="align-middle fas fa-bell"></i>
						</a>
						<div class="dropdown-menu dropdown-menu-lg dropdown-menu-end dropdown py-0" id="dropdown" aria-labelledby="alertsDropdown">
							<div class="dropdown-menu-header">Notificações </div>
							<div id="semNotify"></div>
							<?php if (is_array($notifications)) { ?>
								<div class="list-group" id="notifications">
									<?php foreach ($notifications as $notification) { ?>
										<?php if (($notification['view'] == '') or ($notification['view'] == '1') || 1 == 1) { ?>
												<div class="row g-0 align-items-center notificacao">
													<div class="col-2 p-3">
														<?php if ((mb_strpos($notification['view'], $user['id']) !== false)) { ?>
															<i class="ms-1 text-warning fas fa-fw fa-envelope-open"></i>
														<?php } else { ?>
															<i class="ms-1 text-danger fas fa-fw fa-envelope"></i>
														<?php } ?>
													</div>
													<div class="col-10 p-3">
														<div class="text-dark"><?php echo $notification["content"] ?></div>
														<div class="text-muted small mt-1"> <?php echo date('d/m/Y H:i:s', strtotime($notification["data"])); ?> </div>
													</div>
												</div>
										<?php } ?>
									<?php } ?>
								</div>
								<div id="btns_notify"></div>
							<?php } else { ?>
								<div class="list-group">
									<a href="#" class="list-group-item">
										<div class="row g-0 align-items-center">
											<div class="col-2 p-3">
												<i class="ms-1 text-warning fas fa-fw fa-envelope-open"></i>
											</div>
											<div class="col-10 p-3">
												<div class="text-dark">Você não possui notificações.</div>
											</div>
										</div>
									</a>
								</div>
							<?php } ?>
						</div>
					</li>
					<li class="nav-item dropdown ms-lg-2">
						<a class="nav-link dropdown-toggle position-relative" href="<?php echo base_url('index.php/logout') ?>">
							<i class="align-middle fas fa-sign-out-alt"></i>
						</a>
					</li>
				</ul>
			</div>
		</nav>
