<?php
$session = \Config\Services::session();
$controller = $session->get('controller');
$name = $session->get('nicename');
$email = $session->get('email');
?>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/heatmap.css') ?>" rel="stylesheet" />
<nav class="navbar navbar-expand navbar-theme bg-white" style="justify-content: space-between;">
    <div class="navbar navbar-expand navbar-theme">
        <a href="<?php echo base_url('index.php/dashboard') ?>" class="mt-2 mb-2" style=" margin-right: 30px;">
            <img src="<?php echo base_url('assets/img/brand/logo.png') ?>" class="img-fluid rounded-circle" alt="DKMA" style="width:180px;height:auto;">
        </a>
    </div>
    <div class="card-group">
        <a class="btn btn-success me-3" id="toggle_view" href="#">
            Visualizar Tempo de Fixação
        </a>
        <a class="sidebar-link dropdown-toggle" title="Alterar Radius/Blur" href="#" id="userDropdown" data-toggle="dropdown">
            <i class="fas fa-cog"></i>
        </a>
        <div id="options" class="dropdown-menu dropdown-menu-lg dropdown-menu-end py-0" aria-labelledby="userDropdown" title="Configurações">
            <div class="dropdown-menu-header">Opções </div>
            <div class="list-group">
                <div class="options">
                    <label>Tamanho </label><input type="range" id="radius" value="25" min="10" max="50" /><br />
                    <label>Intensidade </label><input type="range" id="blur" value="15" min="10" max="50" /><br />
                </div>
            </div>
        </div>

        <a class="sidebar-link" id="download" title="Download Heatmap" href="#">
            <i class="fas fa-download"></i>
        </a>
        <a class="sidebar-link" id="logout" title="Sair" href="<?php echo base_url('index.php/' . $controller . '/logout') ?>">
            <i class="fas fa-sign-out-alt"></i>
        </a>
    </div>
</nav>