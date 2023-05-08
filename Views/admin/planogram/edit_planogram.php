<?php
error_reporting(E_ALL & ~E_NOTICE);
$data = $data[0];

$session = session();
?>

<!DOCTYPE html>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/edit_planogram.css') ?>" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/multiselect/bootstrap-multiselect.css") ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/resize/component.css") ?>" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/css/planogram-custom.css") ?>" />

<div class="row m-0">
    <div class="container-fluid" style="width: 100%;">
        <div class="card shadow">
            <div class="card-header border-0" style="background-color: #fc9700;">
                <div class="row align-items-center col-12 ">
                    <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8" style="background-color: #fc9700;">
                        <h3 class="mb-0 text-white"><?php echo $data['name'] ?></h3>
                    </div>
                    <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-1" id="buttons_edit">
                        <a href="<?php echo base_url('/index.php/share?planogram=' . $data['id']); ?>"><button id="share_shelf" class="btn btn-light float-end m-1" tooltip="Gerar share de gôndola"><i class="fas fa-percentage"></i></button></a>
                        <button id="export" class="btn btn-github float-end m-1" tooltip="Exportar planograma"><i class="fas fa-download"></i></button>
                        <a target="_blank" href="<?php echo base_url() ?>/index.php/scenario?id_company=<?php echo $session->get('id_company') ?>&id_scenario=<?php echo $data['id'] ?>&user_uuid=<?php echo $session->get('email') ?>&qtd_max=0&eye_tracking=false" class="btn bg-white float-end m-1" tooltip="Visualizar Como Cliente"><i id="loga" class="fas fa-user-alt"></i></a>
                        <a id="hide_buttons" class="sidebar-toggle btn bg-white float-end m-1" tooltip="Visualizar Planograma"><i id="visualiza" class="far fa-eye"></i></a>
                    </div>
                </div>
            </div>
            <div id="loading" class="active">
                <img src="https://media.giphy.com/media/3oEjI6SIIHBdRxXI40/source.gif" />
            </div>
            <div id="print">
                <div id="contcont" class="gondola pt-5" style="display: flex;overflow: visible;overflow-x: scroll;">
                    <?php for ($c = 1; $c <= $columns_qtd; $c++) {
                        $shelves = isset($columns[$c]) ? $columns[$c] : array();  ?>

                        <div class="column column-<?php echo $c ?>" data-column="<?php echo $c ?>">
                            <?php for ($i = 1; $i <= $shelves_qtd; $i++) {
                                $positions = isset($shelves[$i]) ? $shelves[$i] : array();
                                $y = 1; ?>
                                <div class="prateleira" data-shelf="<?php echo $i ?>">
                                    <div style="border-bottom:2px solid #ccc;height: calc(<?php echo $shelf_height[$i] ?>px + 35px);" id="plus" class="card-group">
                                        <!--exibição da prateleira-->
                                        <div class="products">
                                            <!--botões laterais-->
                                            <div class="buttons">
                                                <a class="btn bg-blue text-white mb-2 moveshelf" tooltip-left tooltip="Mover Prateleira">
                                                    <i class="fas fa-arrows-alt"></i>
                                                </a>
                                                <a data-toggle="modal" data-target="#add-modal-<?php echo $c ?>-<?php echo $i ?>" class="btn bg-orange text-white mb-2" tooltip-left tooltip="Adicionar Produto">
                                                    <i class="fa fa-plus "></i>
                                                </a>
                                                <a class="btn bg-success text-white mb-2" onclick="ep.shelf.copyshelf(<?php echo $i ?>, <?php echo $c ?>)" tooltip-left tooltip="Duplicar Prateleira">
                                                    <i class="far fa-copy "></i>
                                                </a>
                                                <a onclick="ep.shelf.remove_shelf(<?php echo $i ?>)" class="btn bg-danger text-white mb-2 removeshelf" tooltip-left tooltip="Remover Prateleira">
                                                    <i class="fas fa-times"></i>
                                                </a>
                                            </div>
                                            <div class="prat">
                                                <ol class="moverProdutos" id="result<?php echo $c ?>-<?php echo $i ?>" data-shelf="<?php echo $i ?>" data-column="<?php echo $c ?>">
                                                    <?php
                                                    if (is_array($positions)) {
                                                        $data_position = 0;
                                                        foreach ($positions as $position) {
                                                            if (empty($position['width'])) $width = 'auto';
                                                            else $width = $position['width'];
                                                            if (empty($position['height'])) $height = 'auto';
                                                            else $height = $position['height'];
                                                            $data_position = $data_position + 1;

                                                            if (isset($position['product_image'])) {
                                                    ?>
                                                                <li class='produto_sortido' id="<?php echo 'produto=';
                                                                                                echo $position['id']; ?>, <?php echo $position['id_scenario']; ?>, <?php echo $position['shelf'] ?>" style="margin: 10px" data-id='<?php echo $position['id']; ?>' data-id_product='<?php echo $position['id_product'] ?>' data-id_scenario='<?php echo $position['id_scenario']; ?>' data-shelf='<?php echo $position['shelf'] ?>' data-column="<?php echo $c ?>" data-id_position='<?php echo $data_position ?>' data-views='<?php echo $position['views'] ?>' data-alert_count='<?php echo $position['alert_count'] ?>' data-qty='<?php echo $position['qty'] ?>'>
                                                                    <div class='images' style='overflow-y:hidden;'>
                                                                        <?php for ($v = 1; $v <= $position['views']; $v++) { ?>
                                                                            <img height="<?php echo $height ?>" width="<?php echo $width ?>" class="img_scenario" src='<?php echo $position['product_image'] ?>' />
                                                                        <?php } ?>
                                                                        <i onclick="ep.position.turnon_edit_position(<?php echo $position['id'] . ',' . $position['id_product'] . ',' . $position['id_scenario'] . ',' . $position['shelf']. ',' . $position['column'] . ',' . $position['views'] . ',' . $position['qty'] . ',' . $position['alert_count']; ?>,'<?php echo $width; ?>','<?php echo $height; ?>','<?php echo $position['product_image']; ?>','<?php echo $position['position']; ?>')" id='editar' class='edit' data-height="<?php echo $height; ?>" data-width="<?php echo $width; ?>" data-img='<?php echo $position['product_image']; ?>' tooltip-left tooltip="Editar Produto"><img src='<?php echo base_url('writable/uploads/icones/edit.png') ?>' width="15px" width="15px" /></i>
                                                                        <i id='move' class="move" tooltip-left tooltip="Mover Produto"><img src='<?php echo base_url('writable/uploads/icones/mover.png') ?>' style="filter: contrast(2);" height="22px" width="19px" /></i>
                                                                        <i onclick="ep.position.remove_position(<?php echo $position['id']; ?>, <?php echo $position['id_scenario']; ?>, <?php echo $position['shelf'] ?>)" id='remove' class="close" tooltip="Remover Produto"><img src='<?php echo base_url('writable/uploads/icones/delete.png') ?>' height="17px" width="12px" /></i>
                                                                    </div>
                                                                </li>
                                                    <?php
                                                            }
                                                            $y = strval(floatval($position['position']) + 1);
                                                        }
                                                    }
                                                    ?>
                                                </ol>
                                            </div>
                                        </div>

                                        <!-- modal add product-->
                                        <div id="add-modal-<?php echo $c ?>-<?php echo $i ?>" data-backdrop="static" class="modal fade" role="dialog">
                                            <div class="modal-dialog">
                                                <!-- Modal content-->
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h4 class="modal-title">Adicionar Produto ao Planograma</h4>
                                                        <i type="button" class="fas fa-times fa-lg" data-dismiss="modal" id="close_add_<?php echo $i; ?>"></i>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="row">
                                                            <div class="col-lg-12" id="products">
                                                                <label class="mt-2 mb-2 form-control-label" for="name">Faça uma busca pelo nome do produto</label>
                                                            </div>
                                                            <div class="col-12 col-md-5 col-sm-5 col-lg-5 col-xl-5">
                                                                <select id="categorias_add<?php echo $c ?>-<?php echo $i ?>" class="form-control form-control-alternative" placeholder="Selecione uma categoria" required>
                                                                    <option value='' class="disabled">Selecione uma categoria....</option>
                                                                    <?php foreach ($categories as $category) { ?>
                                                                        <option <?php echo "value='" . $category['id'] . "'" ?>><?php echo $category['name']; ?></option>
                                                                    <?php } ?>
                                                                </select>
                                                            </div>
                                                            <div class="col-12 col-md-5 col-sm-5 col-lg-5 col-xl-5">
                                                                <div class="form-group">
                                                                    <input onkeypress="if(event.keyCode === 13) return searchProducts(document.querySelector('.column.column-<?php echo $c ?> input#buscarProduto<?php echo $i ?>').value, <?php echo $i ?>, <?php echo $y ?>, <?php echo $c ?>)" id="buscarProduto<?php echo $i ?>" type="text" name="search" class="form-control form-control-alternative" placeholder="Digite o nome do produto" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 col-md-2 col-sm-2 col-lg-2 col-xl-2">
                                                                <a onclick="searchProducts(document.querySelector('.column.column-<?php echo $c ?> input#buscarProduto<?php echo $i ?>').value, <?php echo $i ?>, <?php echo $y ?>, <?php echo $c ?>);" class="btn bg-orange text-white">Buscar</a>
                                                            </div>
                                                            <div class="col-lg-12 row m-0 mt-3" style="max-height: 300px;overflow-y: scroll;" id="searchProducts<?php echo $c ?>-<?php echo $i ?>"></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer text-center"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <script>
                                    document.addEventListener("DOMContentLoaded", function() {
                                        $('#categorias_add<?php echo $c ?>-<?php echo $i ?>').multiselect();
                                    });
                                </script>
                            <?php } ?>
                            <div class="column-controls">
                                <a class="btn bg-transparent text-white movecolumn" tooltip="Mover Coluna">
                                    <i class="fas fa-arrows-alt"></i>
                                </a>
                                <a class="btn bg-transparent text-white" onclick="ep.shelf.add_shelf(<?php echo $c ?>, <?php echo $i ?>, <?php echo $positions_qtd ?>)" tooltip="Adicionar Prateleira">
                                    <i class="fa fa-plus "></i>
                                </a>
                                <a class="btn bg-transparent text-white" onclick="ep.column.copycolumn(<?php echo $c ?>)" tooltip="Duplicar Coluna">
                                    <i class="far fa-copy"></i>
                                </a>
                                <a class="btn bg-transparent text-white" onclick="ep.column.remove_column(<?php echo $c ?>)" tooltip="Remover Coluna">
                                    <i class="fas fa-times"></i>
                                </a>
                            </div>
                        </div>
                    <?php } ?>
                    <div id="add_column" class="d-flex align-items-center" style="background-image: linear-gradient(to right, #ccc, transparent);">
                        <a class="btn bg-transparent mx-5" onclick="ep.column.add_column(<?php echo $c ?>)" tooltip="Adicionar Coluna">
                            <i class="fa fa-plus h1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- modal editar produto-->
        <div id='edit_product' class='modal fade' role='dialog' tabindex="-1" aria-hidden="true">
            <div class='modal-dialog' style='box-shadow: 0 3px 7px rgba(0, 0, 0, 0.3);'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h4 class='modal-title'>Editar Produto</h4>
                        <i type="button" data-dismiss="modal" class="exit_edit fas fa-times fa-lg"></i>
                    </div>
                    <div class='modal-body'>
                        <div class='row'>
                            <div class='form-group'>
                                <div class='row col-lg-12'>
                                    <div class='col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12'>
                                        <label class='form-control-label' for='shelves'>Produto</label>
                                        <select class='form-control form-control-alternative mb-2' id="produto" name='id_product' readonly disabled>
                                            <?php foreach ($products as $product1) { ?>
                                                <option value='<?php echo $product1['id'] ?>'><?php echo $product1['name'] ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                    <div class='col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4'>
                                        <label class='form-control-label' for='name' style='white-space:nowrap;'>Frentes exibidas</label>
                                        <input type='number' min='1' name='views' class='form-control form-control-alternative' placeholder='Quantas frentes serão exibidas?' value=''>
                                    </div>
                                    <?php if (!$release_flag) {?>
                                    <div class='col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4'>
                                        <label class='form-control-label' for='name' style='white-space:nowrap;'>Total de produtos</label>
                                        <input type='number' min='1' name='qty' class='form-control form-control-alternative' placeholder='Qual o total de produtos?' value=''>
                                    </div>
                                    <div class='col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4'>
                                        <label class='form-control-label' for='name' style='white-space:nowrap;'>Produtos de advertência</label>
                                        <input type='number' min='1' name='alert' class='form-control form-control-alternative' placeholder='Produtos de advertência?' value=''>
                                    </div>
                                    <?php }?>
                                    <div class='col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 d-flex align-items-end'>
                                        <button id='save_product' class='btn btn-outline-warning' style='white-space:nowrap;'>Salvar Frentes</button>
                                        <button onclick="ep.position.open_resizable(this)" tooltip="Redimensionar imagem" id="btn_img" class='btn bg-orange text-white'><i class="fas fa-expand-arrows-alt text-white"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12 mt-3" id="products">
                                    <label class="form-control-label mb-2" for="name">Faça uma busca pelo nome do produto</label>
                                </div>
                                <div class="col-12 col-md-5 col-sm-5 col-lg-5 col-xl-5 mb-3">
                                    <select id="categorias_edit_modal" class="form-select" placeholder="Selecione uma categoria" required>
                                        <option value='' class="disabled" selected>Selecione uma categoria....</option>
                                        <?php foreach ($categories as $category) { ?>
                                            <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="col-12 col-md-5 col-sm-5 col-lg-5 col-xl-5">
                                    <div class="form-group">
                                        <input onkeypress="if(event.keyCode === 13) return searchProducts_edit()" type="text" name="search" class="form-control form-control-alternative buscarProduto" placeholder="Digite o nome do produto" required>
                                    </div>
                                </div>
                                <div class='col-12 col-md-2 col-sm-2 col-lg-2 col-xl-2 '>
                                    <div class='form-group '>
                                        <button onclick="searchProducts_edit()" id='search_product' class='btn btn-outline-warning' style='white-space:nowrap;'>Buscar</button>
                                    </div>
                                </div>
                                <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12" style="max-height: 300px;overflow-y: scroll;" id="searchProducts"></div>
                            </div>
                            <div id="old_product" style="display:none;"></div>
                            <div id="new_product" style="display:none;">
                                <input type="hidden" data-id_product="" data-views="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- modal redimensionar imagem do produto-->
        <button id="btn_resize" type="button" style="display:none;" class="btn btn-primary" data-toggle="modal" data-target="#redimensiona_img"></button>
        <div id="redimensiona_img" data-backdrop="static" tabindex="-1" role="dialog" class="modal fade" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog " role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Redimensionar imagem</h4>
                        <button tooltip="Fechar" id="close" class="btn mb-1 btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                    </div>
                    <div class="modal-body" id="modal_resize">
                        <img class="resize-image" alt="image for resizing">
                        
                    </div>
                    <div class="modal-footer">
                        <div class="row col-12">
                            <div class="col-lg-6 mt-2">
                                Altura - <span id="height"></span> e Largura - <span id="width"></span>
                            </div>
                            <div class="col-lg-4 text-end p-1">
                                <button id="resetSize" class="btn btn-danger">Voltar ao tamanho original</button>
                            </div>
                            <div class="col-lg-2 text-end p-1">
                                <button onclick="ep.position.submit_img(this)" class="btn btn-success">Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-sm-12 col-md-12 col-12">
                <div class="card shadow">
                    <div class="card-header" style="background-color: #fc9700;">
                        <h3 class="mt-1 text-white">Editar Planograma</h3>
                    </div>
                    <div class="card-body">
                        <form id="dropzone-form" role="form" method="post" action="<?php echo base_url('/index.php/update_planogram') ?>">
                            <h6 class="heading-small text-muted">Informações do Planograma</h6>
                            <hr class="mt-0">
                            <div class="pl-lg-4">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <label class="form-control-label" for="name">Nome do Planograma</label>
                                            <input type="text" name="name" class="form-control form-control-alternative" placeholder="Nome do Planograma" value="<?php echo $data['name'] ?>" required>
                                        </div>
                                    </div>
                                    <input type="hidden" value="<?php echo $data['id'] ?>" name="id">
                                    <input type="hidden" value="<?php echo $data['shelves'] ?>" name="shelves_old">
                                    <input type="hidden" name="url_print">
                                    <div class="col-lg-12 text-center mt-3">
                                        <button id="btnSavePlanogram" class="btn bg-orange text-white">Salvar</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Layout para exportar gondula -->
        <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 card shadow" id="print_gondula" style="display:none">
            <div class="header_data">
                <h4 class="text-center"><?php echo $formatter->format($hoje) . ' | ' . date('G:i'); ?></h4>
            </div>
            <div class="card-group mt-3">
                <div class="dkma">
                    <img src="<?php echo base_url('assets/img/brand/logo.png') ?>" class="img-fluid rounded-circle" alt="DKMA" style="width:180px;height:auto;">
                </div>
                <div class="logo_marca">
                    <img class="logo" src="<?php echo base_url($usuario['img_url']); ?>" alt="logo">
                    <h3 class="logo_text"><?php echo $data['name']; ?></h3>
                    <?php foreach (array() as $company) { // foreach ($companies as $company) {
                        if ($estudos[0] == $company['id']) { ?>
                            <h5 class="logo_text"><?php echo $company['name'] ?></h5>
                    <?php }
                    } ?>
                </div>
            </div>
            <div class="gondola2" style="display: flex;">
                <?php for ($c = 1; $c <= $columns_qtd; $c++) {
                    $shelves = isset($columns[$c]) ? $columns[$c] : array(); ?>

                    <div class="column column-<?php echo $c ?>" data-column="<?php echo $c ?>">
                        <?php for ($i = 1; $i <= $shelves_qtd; $i++) {
                            $positions = isset($shelves[$i]) ? $shelves[$i] : array();
                            $y = 1; ?>
                            <ol class="card-group p-0" style="height:auto;">
                                <?php if (is_array($positions)) {
                                    $data_position = 0;
                                    foreach ($positions as $position) {
                                        if (empty($position['width'])) $width = 'auto';
                                        else $width = $position['width'];
                                        if (empty($position['height'])) $height = 'auto';
                                        else $height = $position['height'];
                                        $data_position = $data_position + 1;
                                        if (isset($position['product_image'])) {
                                ?>
                                            <li class='produtos'>
                                                <div class='images_print'>
                                                    <?php for ($x = 1; $x <= $position['views']; $x++) { ?>
                                                        <img height="<?php echo $height ?>" width="<?php echo $width ?>" class="img_scenario" src='<?php echo base_url($position['product_image']) ?>' />
                                                    <?php } ?>
                                                    <div class="price"><?php echo $position['product_price'] ?></div>
                                                </div>
                                            </li>
                                <?php
                                        }
                                        $y = strval(floatval($position['position']) + 1);
                                    }
                                } ?>
                            </ol>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <div class="footer_export">
                <h5>DKMA MANAGER SHELF LTDA</h5>
                <h5>Av. Nova Cantareira, 2014 - Cj 121/122/123</h5>
                <h5>00 000 000/0001-00</h5>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo base_url('assets/js/html2canvas.js') ?>"></script>
<script src="<?php echo base_url("assets/js/multiselect/bootstrap-multiselect.js") ?>"></script>
<script src="<?php echo base_url("assets/js/multiselect/bootstrap.bundle-4.5.2.min.js") ?>"></script>
<script src="<?php echo base_url('assets/js/pages/admin/planograms/edit_planogram/index.js') ?>" type='module' async></script>
<script>
    var ep;
    let cont = 0;
    let release_flag = <?php echo $release_flag; ?>;
    console.log(release_flag);
    window.onload = function() {
        var configObj = {
            base_url: '<?php echo base_url() ?>',
            id_scenario: <?php echo $data['id'] ?>,
            cenario_name: '<?php echo $data['name'] ?>',
        }
        ep = EditPlanogram(configObj);
        console.log(ep);
        
        $("#btnSavePlanogram").click(async function(e) {
            e.preventDefault();
            window.scrollTo(0, 0);
            $('body').toggleClass('disablescroll');

            await setTimeout(function() {
                var print = document.querySelector("#print");
                html2canvas(print).then(canvas => {
                    url = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                    const base64Canvas = canvas.toDataURL("image/jpeg").split(';base64,')[1];
                    console.log("IMAGEM base 64");
                    console.log(base64Canvas);
                    console.log("URL");
                    console.log(url);
                    $("input[name=url_print]").val(url);
                    $("#dropzone-form").submit();
                });
            }, 2000)
            $('body').toggleClass('disablescroll');

        })

        $("#loading").toggleClass("active");

        $('#categorias_edit').multiselect();

        //Funções de clique
        $(function() {
            $('.sidebar-toggle').click(function(i) {
                if ($('i#visualiza').hasClass('far fa-eye')) {
                    $('#hide_buttons').attr('title', 'Editar Planograma');
                    $('i#visualiza').removeClass('far fa-eye');
                    $('i#visualiza').addClass('fas fa-pencil-alt');
                } else {
                    $('#hide_buttons').attr('title', 'Visualizar Planograma');
                    $('i#visualiza').removeClass('fas fa-pencil-alt');
                    $('i#visualiza').addClass('far fa-eye');
                }

                $('.main').toggleClass('toggled');
                $('.img_scenario').toggleClass('toggled');
                $('.card-group ol').toggleClass('toggled');
                $('#cf').toggleClass('scenario');

                $('.prat').toggleClass('toggled');
                $('.buttons').toggleClass('hide');
                $('i#editar').toggleClass('hide');
                $('i#remove').toggleClass('hide');
                $('i#move').toggleClass('hide');
            });
        });

        $("#submit-dropzone").click(function() {
            $('input#submit-dropzone').val('Exportando...');
        });

        $('.exit').click(function(event) {
            event.preventDefault();
            $('body').removeClass('modal-open');
        });

        $('#save_product').click(function() {
            var id = $('#old_product input').attr('data-id');
            var id_scenario = $('#old_product input').attr('data-id_scenario');
            var shelf = $('#old_product input').attr('data-shelf');
            var views = $('#edit_product input[name="views"]').val();
            var qty = $('#edit_product input[name="qty"]').val();
            var alert_count = $('#edit_product input[name="alert_count"]').val();
            var id_product = $('#edit_product select[name="id_product"]').val();
            if (views == '' || views == ' ') views = 1;
            if (views == 0) ep.position.remove_position(id, id_scenario, shelf);
            if (qty == '' || qty == ' ' || qty < 1) qty = 1;
            if (alert_count == '' || alert_count == ' ' || alert_count < 1) alert_count = 1;
            $.ajax({
                url: '<?php echo base_url('/index.php/edit_position'); ?>',
                method: 'POST',
                data: {
                    id: id,
                    id_scenario: id_scenario,
                    shelf: shelf,
                    id_product: id_product,
                    views: views,
                    qty: qty
                },
                success: function(data) {
                    window.location.reload();
                }
            })
        });
    };

    //Função para exportar cenário em png
    $('#export').click(function() {
        window.scrollTo(0, 0);
        $('body').toggleClass('disablescroll');
        setTimeout(function() {
            $('#print_gondula').css('display', '');
            if ($('#sidebar').hasClass('toggled')) {
                $("#hide_buttons").trigger("click");
            }
            let gondola = document.querySelector("#print_gondula");
            html2canvas(gondola).then(canvas => {
                let cenario = "<?php echo $data['name'] ?>";
                cenario = cenario.replace(/[ÀÁÂÃÄÅ]/g, "A");
                cenario = cenario.replace(/[àáâãäå]/g, "a");
                cenario = cenario.replace(/[ÈÉÊË]/g, "E");
                cenario = cenario.replace(/[ç]/gi, 'c');
                cenario = cenario.replace(/[^a-z0-9]/gi, '_');

                var a = document.createElement('a');
                document.getElementById("wrapper").appendChild(a);
                a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                a.download = cenario + '.png';
                a.click();
            });
            $('#print_gondula').css('display', 'none');
            $('body').toggleClass('disablescroll');
        }, 500);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('/index.php/export_scenario'); ?>",
            success: function(data) {}
        });

    });

    function searchProducts(string, shelf, position, column) {
        let category = $('#categorias_add' + column + '-' + shelf).val();

        $('#searchProducts' + column + '-' + shelf).empty();
        //Se o usuário não digitar nada ou inserir um espaço em branco finaliza a função.
        if (string == '' && category == '') return 0;
        console.warn(category);

        $.ajax({
            url: "<?php echo base_url('/index.php/search_product'); ?>",
            method: "POST",
            data: {
                string: string,
                category: category
            },
            success: function(data) {
                if (data == 0) {
                    let html = `<div class="row col-12 m-0 mb-3"><p>Nenhum produto encontrado</p></div>`;
                    $('#searchProducts' + column + '-' + shelf).append(html);
                } else {
                    let dataArray = data; 
                    if (!release_flag) {
                        $.each(dataArray, function (index) {
                            if (this.image.indexOf("app.managershelf.com.br") === -1) {
                                this.image = "<?php echo base_url() ?>/" + this.image
                            }
                            let html = `<div class="row col-12 m-0 p-0 mb-3 search_product_result" data-id_product="` + this.id + `">
                        <div class="col-auto">
                            <img src="` + this.image + `" alt="` + this.name + `" style="max-width: 175px;">
                        </div>
                        <div class="col p-0">
                            <div class="form-group">
                                <div class="row m-0" style="width: 100%;">
                                    <div class="col-12">
                                        <h2 class="d-flex align-items-center m-0">` + this.name + ` - <span class="text-sm m-2">R$ ` + this.price + `</span></h2>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end flex-column">
                                        <label class="mt-2 form-control-label" for="name">Quantas frentes deste produto serão exibidas?</label>
                                        <input data-id_product="` + this.id + `" type="number" min="1" name="views" class="form-control form-control-alternative" value="1" required>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end flex-column">
                                        <label class="mt-2 form-control-label" for="name">Qual será o total de produtos?</label>
                                        <input data-id_product="` + this.id + `" type="number" min="1" name="qty" class="form-control form-control-alternative" value="1" required>
                                    </div>
                                    <div class="col-12 mt-3 d-flex justify-content-end">
                                        <button onclick="ep.position.add_position(` + this.id + `, ` + shelf + `, ` + (parseInt(position) + 1) + `, ` + column + `)" class="btn bg-orange text-white">Adicionar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                            $('#searchProducts' + column + '-' + shelf).append(html);
                        });
                    } else {
                        $.each(dataArray, function (index) {
                            if (this.image.indexOf("app.managershelf.com.br") === -1) {
                                this.image = "<?php echo base_url() ?>/" + this.image
                            }
                            let html = `<div class="row col-12 m-0 p-0 mb-3 search_product_result" data-id_product="` + this.id + `">
                        <div class="col-auto">
                            <img src="` + this.image + `" alt="` + this.name + `" style="max-width: 175px;">
                        </div>
                        <div class="col p-0">
                            <div class="form-group">
                                <div class="row m-0" style="width: 100%;">
                                    <div class="col-12">
                                        <h2 class="d-flex align-items-center m-0">` + this.name + ` - <span class="text-sm m-2">R$ ` + this.price + `</span></h2>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end flex-column">
                                        <label class="mt-2 form-control-label" for="name">Quantas frentes deste produto serão exibidas?</label>
                                        <input data-id_product="` + this.id + `" type="number" min="1" name="views" class="form-control form-control-alternative" value="1" required>
                                    </div>
                                    <div class="col-12 mt-3 d-flex justify-content-end">
                                        <button onclick="ep.position.add_position(` + this.id + `, ` + shelf + `, ` + (parseInt(position) + 1) + `, ` + column + `)" class="btn bg-orange text-white">Adicionar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                            $('#searchProducts' + column + '-' + shelf).append(html);
                        });
                    }
                }
            }
        })
    }

    function searchProducts_edit() {
        let shelf = $('#edit_product').attr('data-shelf')
        let id = $('#edit_product').attr('data-id')
        let position = $('#edit_product').attr('data-position')
        let string = $('#edit_product').find(".buscarProduto").val()
        let column = $('#edit_product').data('column');
        let categoria = $('#edit_product').find('#categorias_edit_modal').val();

        console.log(column, shelf, $('#edit_product'), this)
        $('#edit_product #searchProducts').empty();

        //Se o usuário não digitar nada ou inserir um espaço em branco finaliza a função.
        if (string == '' && categoria == '') return 0;
        // console.warn(string);

        $.ajax({
            url: "<?php echo base_url('/index.php/search_product'); ?>",
            method: "POST",
            data: {
                string: string,
                category: categoria
            },
            success: function(data) {
                if (data == 0) {
                    let html = `<div class="row col-12 m-0 mb-3"><p>Nenhum produto encontrado</p></div>`;
                    $('#edit_product #searchProducts').append(html);
                } else {
                    let dataArray = data; // JSON.parse(data);
                    if (!release_flag) {
                        $.each(dataArray, function (index) {
                            if (this.image.indexOf("app.managershelf.com.br") === -1) {
                                this.image = "<?php echo base_url() ?>/" + this.image
                            }
                            let html = `<div class="row col-12 m-0 p-0 mb-3 search_product_result" data-id_product="` + this.id + `">
                        <div class="col-auto">
                            <img src="` + this.image + `" alt="Teste" style="max-width: 175px;">
                        </div>
                        <div class="col p-0">
                            <div class="form-group">
                                <div class="row m-0" style="width: 100%;">
                                    <div class="col-12">
                                        <h2 class="d-flex align-items-center m-0">` + this.name + ` - <span class="text-sm m-2">R$ ` + this.price + `</span></h2>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end flex-column">
                                        <label class="mt-2 form-control-label" for="name">Quantas frentes deste produto serão exibidas?</label>
                                        <input data-id_product="` + this.id + `" type="number" min="1" name="views" class="form-control form-control-alternative" value="1" required>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end flex-column">
                                        <label class="mt-2 form-control-label" for="name">Qual será o total de produtos?</label>
                                        <input data-id_product="` + this.id + `" type="number" min="1" name="qty" class="form-control form-control-alternative" value="1" required>
                                    </div>
                                    <div class="col-12 mt-3 d-flex justify-content-end">
                                        <button onclick="ep.position.remove_position(` + id + `, <?php echo $data['id'] ?>, ` + shelf + `); ep.position.add_position(` + this.id + `, ` + shelf + `, ` + position + `, ` + column + `)" class="btn bg-orange text-white">Adicionar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                            $('#edit_product #searchProducts').append(html);
                        });
                    } else {
                        $.each(dataArray, function (index) {
                            if (this.image.indexOf("app.managershelf.com.br") === -1) {
                                this.image = "<?php echo base_url() ?>/" + this.image
                            }
                            let html = `<div class="row col-12 m-0 p-0 mb-3 search_product_result" data-id_product="` + this.id + `">
                        <div class="col-auto">
                            <img src="` + this.image + `" alt="Teste" style="max-width: 175px;">
                        </div>
                        <div class="col p-0">
                            <div class="form-group">
                                <div class="row m-0" style="width: 100%;">
                                    <div class="col-12">
                                        <h2 class="d-flex align-items-center m-0">` + this.name + ` - <span class="text-sm m-2">R$ ` + this.price + `</span></h2>
                                    </div>
                                    <div class="col-6 d-flex justify-content-end flex-column">
                                        <label class="mt-2 form-control-label" for="name">Quantas frentes deste produto serão exibidas?</label>
                                        <input data-id_product="` + this.id + `" type="number" min="1" name="views" class="form-control form-control-alternative" value="1" required>
                                    </div>
                                    <div class="col-12 mt-3 d-flex justify-content-end">
                                        <button onclick="ep.position.remove_position(` + id + `, <?php echo $data['id'] ?>, ` + shelf + `); ep.position.add_position(` + this.id + `, ` + shelf + `, ` + position + `, ` + column + `)" class="btn bg-orange text-white">Adicionar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>`;
                            $('#edit_product #searchProducts').append(html);
                        });
                    }
                }
            }
        })
    }
</script>