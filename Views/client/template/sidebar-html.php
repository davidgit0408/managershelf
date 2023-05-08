<?php 
    $session = \Config\Services::session();
    $role = $session->get('role');
    $controller = $session->get('controller');
?>
<nav class="navbar navbar-vertical fixed-left navbar-expand-md navbar-light bg-orange" id="sidenav-main">
    <div class="container-fluid">
      <!-- Toggler -->
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#!sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <!-- Brand -->
      <a class="text-white navbar-brand pt-0" href="#!">
        <img src="<?php echo base_url('assets/img/brand/logo.png') ?>" class="navbar-brand-img" alt="...">
      </a>
      <!-- User -->
      <ul class="nav align-items-center d-md-none">
        <li class="nav-item dropdown">
          <a class="text-white nav-link nav-link-icon" href="#!" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right" aria-labelledby="navbar-default_dropdown_1">
            <a class="text-white dropdown-item" href="#!">Action</a>
            <a class="text-white dropdown-item" href="#!">Another action</a>
            <div class="dropdown-divider"></div>
            <a class="text-white dropdown-item" href="#!">Something else here</a>
          </div>
        </li>
        <li class="nav-item dropdown">
          <a class="text-white nav-link" href="#!" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="<?php echo base_url('assets/img/theme/team-1-800x800.jpg') ?>">
              </span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
            <div class=" dropdown-header noti-title">
              <h6 class="text-overflow m-0">Welcome!</h6>
            </div>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-single-02"></i>
              <span>My profile</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-settings-gear-65"></i>
              <span>Settings</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-calendar-grid-58"></i>
              <span>Activity</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-support-16"></i>
              <span>Support</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-user-run"></i>
              <span>Logout</span>
            </a>
          </div>
        </li>
      </ul>
      <!-- Collapse -->
      <div class="collapse navbar-collapse" id="sidenav-collapse-main">
        <!-- Collapse header -->
        <div class="navbar-collapse-header d-md-none">
          <div class="row">
            <div class="col-6 collapse-brand">
              <a href="#!">
                <img src="assets/img/brand/blue.png">
              </a>
            </div>
            <div class="col-6 collapse-close">
              <button type="button" class="navbar-toggler" data-toggle="collapse" data-target="#!sidenav-collapse-main" aria-controls="sidenav-main" aria-expanded="false" aria-label="Toggle sidenav">
                <span></span>
                <span></span>
              </button>
            </div>
          </div>
        </div>
        <!-- Form -->
        <form class="mt-4 mb-3 d-md-none">
          <div class="input-group input-group-rounded input-group-merge">
            <input type="search" class="form-control form-control-rounded form-control-prepended" placeholder="Search" aria-label="Search">
            <div class="input-group-prepend">
              <div class="input-group-text">
                <span class="fa fa-search"></span>
              </div>
            </div>
          </div>
        </form>
        <!-- Navigation -->
        <ul class="navbar-nav">
          <li class="nav-item" class="active">
          <a class="text-white  nav-link active " href="<?php echo base_url('index.php/'.$controller.'/dashboard_'.$controller) ?>"> 
            <i class="ni ni-tv-2 text-blue"></i> Dashboard
          </a>
          </li>
          <li class="nav-item">
            <a class="text-white nav-link" href="#!">
              <i class="fas fa-chalkboard-teacher text-blue"></i> Cenários
            </a>
            <ul class="nav">
                <li class="nav-item">
                    <a class="text-white nav-link " href="<?php echo base_url('index.php/'.$controller.'/all_scenarios') ?>">Todos Cenários</a>
                </li>
                <li class="nav-item">
                    <a class="text-white nav-link " href="<?php echo base_url('index.php/'.$controller.'/new_scenario') ?>">Adicionar Cenário</a>
                </li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="text-white nav-link " href="#!">
              <i class="fas fa-dollar-sign text-blue"></i> Produtos
            </a>
            <ul class="nav">
                <li class="nav-item">
                    <a class="text-white nav-link " href="<?php echo base_url('index.php/'.$controller.'/all_products') ?>">Todos Produtos</a>
                </li>
                <li class="nav-item">
                    <a class="text-white nav-link " href="<?php echo base_url('index.php//index.php/'.$controller.'/new_product') ?>">Adicionar Produto</a>
                </li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="text-white nav-link " href="<?php echo base_url('index.php/'.$controller.'/all_orders') ?>">
              <i class="far fa-chart-bar text-blue"></i> Vendas
            </a>
          </li>
          <?php if($role == 'admin'){ ?>
          <li class="nav-item">
            <a class="text-white nav-link" href="#!">
              <i class="fa fa-user-circle text-blue"></i> Usuários
            </a>
            <ul class="nav">
                <li class="nav-item">
                    <a class="text-white nav-link " href="<?php echo base_url('index.php/'.$controller.'/all_users') ?>">Todos Usuários</a>
                </li>
                <li class="nav-item">
                    <a class="text-white nav-link " href="<?php echo base_url('index.php//index.php/'.$controller.'/new_user') ?>">Adicionar Usuário</a>
                </li>
            </ul>
          </li>
          <?php } ?>
        </ul>
        <ul class="navbar-nav mb-3" style="bottom: 0;position: absolute;">
            <li class="nav-item">
            <a class="text-white nav-link" href="<?php echo base_url('index.php//index.php/admin/logout') ?>">
              <i class="fa fa-sign-out-alt" aria-hidden="true"></i> Logout
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <div style="margin-left:200px">