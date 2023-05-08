<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = session();
$id_user = $session->get('id');
?>
<!DOCTYPE html>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/planograms.css') ?>" rel="stylesheet" />

<style>

</style>
<div class="row">
    <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 d-flex">
        <div class="card flex-fill p-3">
            <div class="row align-items-center col-12 mb-3">
                <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                    <h3 class="mb-0">Planogramas</h3>
                </div>
                <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-3">
<!--                    <a href="new_planogram" class="btn bg-orange text-white float-end" style="white-space:nowrap;">Adicionar planograma</a>-->
                    <a data-bs-toggle="modal" data-bs-target="#Adicionar" class="btn bg-orange text-white float-end" style="white-space:nowrap;">Adicionar planograma</a>
                    <a onclick="toggle_view()" id="btn_toggle" class="btn btn-success text-white float-end" style="white-space:nowrap; margin-right:10px ; width:auto"> Ver por grade</a>
                </div>
            </div>
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
            <div id="table">
                <table id="datatables-dashboard-projects" class="table table-striped my-0 ">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <?php if ($id_user == 1) { ?><th class="d-none d-md-table-cell">Criador</th><?php } ?>
                            <th class="col">Categoria</th>
                            <th class="col">Localização</th>
                            <th class="col">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($planograms)) {
                            foreach ($planograms as $planogram) { ?>
                                <tr id="scenario-<?php echo $planogram["id"] ?>">
                                    <td><?php echo $planogram["name"]; ?>
                                        <div class="d-none" id="id_scenario"><input name="id_scenario" type="hidden" value="<?php echo $planogram["id"] ?>">
                                            <div>
                                    </td>
                                    <?php if ($id_user == 1) {  ?><td class="d-none d-md-table-cell"><?php echo $planogram["id_user"]; ?></td><?php } ?>
                                    <td>
                                        <?php echo $planogram["category"]; ?>
                                    </td>
                                    <td>
                                        <?php echo $planogram["location"]; ?>
                                    </td>
                                   
                                    <td>
                                        <div class="d-inline-block dropdown show">
                                            <a class="btn btn-sm btn-icon-only" href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" id="drops">
                                                <a class="dropdown-item" href="<?php echo base_url('/index.php/share?planogram=' . $planogram['id']); ?>">Visualizar Share</a>
                                                <a class="dropdown-item" href="<?php echo base_url('index.php/edit_planogram/' . $planogram['id']) ?>">Editar Planograma</a>
                                                <a class="dropdown-item" href="<?php echo base_url('index.php/duplica_planogram/' . $planogram['id']) ?>">Duplicar Planograma</a>
                                                <a class="dropdown-item" onclick="remove_scenario(this)" href="#" data-id="<?php echo $planogram['id'] ?>" data-name="<?php echo $planogram['name'] ?>" data-bs-toggle="modal" data-bs-target="#delete">Excluir Planograma</a>
                                                <?php if ($planogram['release_flag']) {?>
                                                    <a class="dropdown-item" href="#" onclick="open_modal(this)" data-id="<?php echo $planogram["id"] ?>" data-name="<?php echo $planogram["name"] ?>" data-toggle="modal" data-target="#libera_planogram">Liberar planograma para campo</a>
                                                <?php }?>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!-- Modal confirmação ao deletar cenário -->
                                <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Deletar cenário</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body m-3">
                                                <div class="text-center">
                                                    <h4 id="certeza" class="mb-4"></h4>
                                                    <button type="button" class="btn btn-success" onclick="deleta(this)" data-id="">SIM</button>
                                                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">NÃO</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal liberar planograma -->
                                <div class="modal fade" id="libera_planogram" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content inicial">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="name_plan"></h5>
                                                <button type="button" class="btn-close" id="close_modal" data-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body m-3">
                                                <div class="form-group">
                                                    <div class="alert alert-danger alert-dismissible" id="error_msg" style="display:none" role="alert">
                                                        <div class="alert-message">Não é possível liberar para campo um planograma vazio!</div>
                                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>
                                                    <label class="form-control-label" for="name">Vincule um estudo ao planograma antes de liberar para campo:</label><br>
                                                    <select id="company" class="mt-1 form-control" required>
                                                        <option disabled selected>Selecione...</option>
                                                        <?php foreach ($available_company as $company) {
                                                            if ($company['name'] != ' ') {  ?>
                                                                <option name="company" value="<?php echo $company['id'] ?>"><?php echo $company['name'] ?></option>
                                                        <?php }
                                                        } ?>
                                                    </select>
                                                </div>
                                                <input id="id_planogram" type="hidden">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" onclick="libera_planograma()" class="text-white btn bg-orange">Liberar</button>
                                            </div>
                                        </div>
                                        <div class="modal-content sucesso" style="display:none;">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Planograma liberado com sucesso!</h5>
                                            </div>
                                            <div class="modal-body m-3">
                                                <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                                                    <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                                    <span class="swal2-success-line-tip"></span>
                                                    <span class="swal2-success-line-long"></span>
                                                    <div class="swal2-success-ring"></div>
                                                    <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                                    <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                                </div>
                                            </div>
                                            <div class="form-group text-center col-12 mb-3">
                                                <a href="<?php echo base_url('index.php/all_scenarios') ?>"><button type="button" class="btn btn-light">Ver cenários em campo</button></a>
                                                <a onclick="login_client(this)"><button type="button" class="text-white btn bg-orange">Iniciar Pesquisa</button></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!--Modal Cliente-->
                                <button class="btn d-none" id="open_login" data-toggle="modal" data-target="#loga_client"></button>
                                <div class="modal fade" id="loga_client" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Logar como cliente</h5>
                                                <a href="<?php echo base_url('index.php/all_planograms') ?>"><button type="button" class="btn-close"></button></a>
                                            </div>
                                            <form role="form" method="post" action="<?php echo base_url('index.php/client_scenario') ?>">
                                                <div class="modalcontain">
                                                    <div class="modal-body m-3">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="form-control-label" for="name">Quantidade Máxima de compras</label>
                                                                    <input type="text" name="qtd_max" class="mt-1 form-control" placeholder="Insira a quantidade máxima de compras" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <div class="form-group">
                                                                    <label class="form-control-label" for="name">Eye tracking</label><br>
                                                                    <select name="eye_tracking" class="mt-1 form-control" required>
                                                                        <option disabled>Selecione </option>
                                                                        <option name="eye_tracking" value="true">Sim</option>
                                                                        <option name="eye_tracking" value="false">Não</option>
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <input name="id_cenario" id="id_scenario" type="hidden">
                                                    <input name="id_company" id="id_company" type="hidden">

                                                    <div class="modal-footer">
                                                        <button type="submit" class="text-white btn bg-orange">Logar</button>
                                                    </div>

                                            </form>
                                        </div>
                                    </div>
                                </div>
                        <?php }
                        } ?>
                    </tbody>
                </table>
            </div>
            <div class="container-fluid" id="grid">
                <?php if (is_array($planograms)) {
                    $count = 0;
                    while (1) { ?>
                        <?php if ($count % 5 == 0) { ?>
                            <div class="row">
                                <?php for ($i = 0; $i < count($planograms); $i++) {
                                    if ($planograms[$count]) { ?>
                                        <div class="card text-white bg-warning mb-3 col-md-2 dropdown" style="max-width: 100%; padding:0px ; margin:1.65%;cursor: pointer;">
                                            <div class="card-body dropbtn" data-bs-toggle="dropdown" data-bs-display="static" style="padding:0px">
                                                <img class="card-img-top" style="min-height:100px; text-align:center;  object-fit: cover;" src="<?php echo base_url('writable/uploads/scenarios/scenario' . $planograms[$count]['id'] . '.png'); ?>" alt="Preview not available">
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end" id="drops">
                                                <a class="dropdown-item" href="<?php echo base_url('/index.php/share?planogram=' . $planogram['id']); ?>">Visualizar Share</a>
                                                <a class="dropdown-item" href="<?php echo base_url('index.php/edit_planogram/' . $planograms[$count]['id']) ?>">Editar Planograma</a>
                                                <a class="dropdown-item" href="<?php echo base_url('index.php/duplica_planogram/' . $planograms[$count]['id']) ?>">Duplicar Planograma</a>
                                                <a class="dropdown-item" onclick="remove_scenario1(this)" href="#" data-id="<?php echo $planograms[$count]['id'] ?>" data-name="<?php echo $planograms[$count]['name'] ?>" data-bs-toggle="modal" data-bs-target="#delete1">Excluir Planograma</a>
                                                <a class="dropdown-item" href="#" onclick="open_modal1(this)" data-id="<?php echo $planograms[$count]["id"] ?>" data-name="<?php echo $planograms[$count]["name"] ?>" data-toggle="modal" data-target="#libera_planogram1">Liberar planograma para campo</a>
                                            </div>

                                            <div class="card-footer" style="text-align: center"><small class="text-muted"><?php echo $planograms[$count]['name']; ?> </small> </div>
                                            <div class="modal fade" id="delete1" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Deletar cenário</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body m-3">
                                                            <div class="text-center">
                                                                <h4 id="certeza1" class="mb-4">Tem certeza que deseja deletar Cerveja - Cenário 1 ?</h4>
                                                                <button type="button" class="btn btn-success" id="btn-success1" onclick="deleta(this)" data-id="">SIM</button>
                                                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">NÃO</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal liberar planograma -->
                                            <div class="modal fade" id="libera_planogram1" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content inicial1">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="name_plan1"></h5>
                                                            <button type="button" class="btn-close" id="close_modal" data-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body m-3">
                                                            <div class="form-group">
                                                                <div class="alert alert-danger alert-dismissible" id="error_msg1" style="display:none" role="alert">
                                                                    <div class="alert-message">Não é possível liberar para campo um planograma vazio!</div>
                                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                                                                </div>
                                                                <label class="form-control-label" style="color:black" for="name">Vincule um estudo ao planograma antes de liberar para campo:</label><br>
                                                                <select id="company1" class="mt-1 form-control" required>
                                                                    <option disabled selected>Selecione...</option>
                                                                    <?php foreach ($available_company as $company) {
                                                                        if ($company['name'] != ' ') {  ?>
                                                                            <option name="company" value="<?php echo $company['id'] ?>"><?php echo $company['name'] ?></option>
                                                                    <?php }
                                                                    } ?>
                                                                </select>
                                                            </div>
                                                            <input id="id_planogram1" type="hidden">
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" onclick="libera_planograma1()" class="text-white btn bg-orange">Liberar</button>
                                                        </div>
                                                    </div>
                                                    <div class="modal-content sucesso1" style="display:none;">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Planograma liberado com sucesso!</h5>
                                                        </div>
                                                        <div class="modal-body m-3">
                                                            <div class="swal2-icon swal2-success swal2-animate-success-icon" style="display: flex;">
                                                                <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                                                                <span class="swal2-success-line-tip"></span>
                                                                <span class="swal2-success-line-long"></span>
                                                                <div class="swal2-success-ring"></div>
                                                                <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                                                                <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group text-center col-12 mb-3">
                                                            <a href="<?php echo base_url('index.php/admin/all_scenarios') ?>"><button type="button" class="btn btn-light">Ver cenários em campo</button></a>
                                                            <a onclick="login_client(this)"><button type="button" class="text-white btn bg-orange">Iniciar Pesquisa</button></a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                <?php $count++;
                                    }
                                } ?>
                                <?php if ($count == count($planograms)) break; ?>
                            </div>
                        <?php } ?>
                <?php }
                } ?>
            </div>
        </div>
    </div>
</div>

<script>
    var isGrid = false;

    function release_field() {
        window.location.href = "<?php echo base_url('/index.php/new_planogram/0'); ?>";
    }
    function remove_scenario(scenario) {
        let id = $(scenario).data('id');
        $('.btn-success').attr('data-id', id);
        let name = $(scenario).data('name');
        $('#certeza').text('Tem certeza que deseja deletar  ' + name + ' ?');
    }

    function remove_scenario1(scenario) {
        let id = $(scenario).data('id');
        $('#btn-success1').attr('data-id', id);
        let name = $(scenario).data('name');
        $('#certeza1').text('Tem certeza que deseja deletar  ' + name + ' ?');
    }

    function deleta(scenario) {
        let id = $(scenario).data('id');
        window.location.href = "<?php echo base_url('index.php/delete_planogram') ?>" + "/" + id;
    }


    $(function() {
        $('#grid').hide();
        $('#datatables-dashboard-projects').DataTable({});
    });

    function open_modal(planogram) {
        $('#error_msg').css('display', 'none');
        let id = $(planogram).data('id');
        let name = $(planogram).data('name');
        $('#name_plan').text('Liberar ' + name + ' para campo');
        $('#id_planogram').val(id);
    };

    function open_modal1(planogram) {
        $('#error_msg1').css('display', 'none');
        let id = $(planogram).data('id');
        let name = $(planogram).data('name');
        $('#name_plan1').text('Liberar ' + name + ' para campo');
        $('#id_planogram1').val(id);
    };


    function libera_planograma() {
        let id_planogram = $('#id_planogram').val();
        let id_company = $('#company option:selected').val();
        $('#id_company').val(id_company);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/libera_planograma'); ?>",
            data: {
                id_planogram: id_planogram,
                id_company: id_company
            },
            success: function(data) {
                console.log('DADA LIBERA PLANOGRAMA')
                console.log(data);
                if (data == 'empty') {
                    $('#error_msg').css('display', '');
                } else {
                    $('.inicial').css('display', 'none');
                    $('.sucesso').css('display', '');
                    $('#id_scenario').val(data);
                }
            }
        });
    };

    function libera_planograma1() {
        let id_planogram = $('#id_planogram1').val();
        let id_company = $('#company1 option:selected').val();
        $('#id_company').val(id_company);

        $.ajax({
            type: "POST",
            url: "<?php echo base_url('index.php/libera_planograma'); ?>",
            data: {
                id_planogram: id_planogram,
                id_company: id_company
            },
            success: function(data) {
                if (data == 'empty') {
                    $('#error_msg1').css('display', '');
                } else {
                    $('.inicial1').css('display', 'none');
                    $('.sucesso1').css('display', '');
                    $('#id_scenario').val(data);
                }
            }
        });
    };


    function toggle_view() {
        if (isGrid) {
            $('#grid').hide();
            $('#table').show();
            $('#btn_toggle').text("Visualizar em grade")
            isGrid = false;
        } else {
            isGrid = true;
            $('#grid').show();
            $('#table').hide();
            $('#btn_toggle').text("Visualizar em tabela")
        }
    }

    function login_client() {
        $("#close_modal").trigger("click");
        $("#open_login").trigger("click");

    };
</script>
