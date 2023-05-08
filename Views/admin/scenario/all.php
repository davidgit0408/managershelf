<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = session();
$id_user = $session->get('id');
?>
<!DOCTYPE html>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/planograms.css') ?>" rel="stylesheet" />

<style>
    .badge-corner:empty {
        display: inline-block;
    }

    .badge-corner {
        position: absolute;
        top: 0;
        right: 12px;
        width: 0;
        height: 0;
        border-top: 40px solid #888;
        border-top-color: rgba(0, 0, 0, 0.3);
        border-left: 66px solid transparent;
        padding: 0;
        background-color: transparent;
        border-radius: 0;
    }

    .badge-corner span {
        position: absolute;
        top: -35px;
        left: -40px;
        font-size: 8px;
        color: #fff;
    }



    .dropbtn:hover,
    .dropbtn:focus {
        background-color: #2980B9;
    }

    #drops {
        width: auto
    }
</style>
<div class="row">
    <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 d-flex">
        <div class="card flex-fill p-3">

            <div class="row align-items-center col-12 mb-3">
                <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                    <h3 class="mb-0">Cenários</h3>
                </div>
                <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-3">
                    <!-- <a href="new_planogram" class="btn bg-orange text-white float-end" style="white-space:nowrap;">Adicionar planograma</a> -->
                    <a onclick="toggle_view()" id="btn_toggle" class="btn btn-success text-white float-end" style="white-space:nowrap; width:auto">Visualizar em grade</a>
                </div>
            </div>
            <div id="table">
                <table id="datatables-dashboard-projects" class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th scope="col">Nome</th>
                            <?php if ($id_user == 1) { ?><th class="d-none d-md-table-cell">Criador</th><?php } ?>
                            <th class="d-none d-md-table-cell">Status</th>
                            <th class="col">Estudo</th>
                            <th class="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($scenarios)) {
                            foreach ($scenarios as $cenario) { ?>
                                <tr id="scenario-<?php echo $cenario["id"] ?>">
                                    <td><?php echo $cenario['name']; ?>
                                        <div class="d-none" id="id_scenario">
                                            <input name="id_scenario" type="hidden" value="<?php var_dump($cenario); ?>">
                                        <div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <p class="badge bg-danger"><?php echo $cenario['status'] ?>
                                    </td>
                                    <td><?php echo $cenario['company_name']; ?></td>
                                    <td>
                                        <div class="d-inline-block dropdown show">
                                            <a class="btn btn-sm btn-icon-only" href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end" id="drops">
                                                <a class="dropdown-item" href="<?php echo base_url('index.php/view_scenario/' . $cenario['id']) ?>">Visualizar Cenário</a>
                                                <a class="dropdown-item logar_client" href="#" onclick="loga_client(this)" data-scenario="<?php echo $cenario["id"] ?>" data-company="<?php echo $cenario["id_company"] ?>" data-bs-toggle="modal" data-bs-target="#loga_client">Logar como Cliente</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>

                                <!--Modal Cliente-->
                                <div class="modal fade" id="loga_client" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Logar como cliente</h5>
                                                <button type="button" class="btn-close" id="close_modal" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                                    <input name="id_cenario" id="id_cenario" type="hidden">
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
                <?php if (is_array($scenarios)) {
                    $count = 0;
                    while (1) { ?>
                        <?php if ($count % 5 == 0) { ?>
                            <div class="row">
                                <?php for ($i = 0; $i < count($scenarios); $i++) {
                                    if ($scenarios[$count]) { ?>
                                        <div class="card text-white bg-warning mb-3 col-md-2 dropdown" style="max-width: 100%; padding:0px ; margin:auto ">
                                            <div class="card-body dropbtn" data-bs-toggle="dropdown" data-bs-display="static" onclick="myFunction('<?php echo '#myDropdown' . $count ?>')" style="padding:0px">
                                                <img class="card-img-top" style="min-height:100px; text-align:center " src="<?php echo base_url('writable/uploads/scenarios/scenario' . $scenarios[$count]['id'] . '.png'); ?>" alt="Preview not available">
                                            </div>
                                            <div class="dropdown-menu dropdown-menu-end" id="drops">
                                                <a class="dropdown-item" href="<?php echo base_url('index.php/view_scenario?id=' . $scenarios[$count]['id']) ?>">Visualizar Cenário</a>
                                                <a class="dropdown-item logar_client" href="#" onclick="loga_client1(this)" data-scenario="<?php echo $scenarios[$count]["id"] ?>" data-company="<?php echo $scenarios[$count]["id_company"] ?>" data-bs-toggle="modal" data-bs-target="#loga_client1">Logar como Cliente</a>
                                            </div>

                                            <div class="modal fade" id="loga_client1" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Logar como cliente</h5>
                                                            <button type="button" class="btn-close" id="close_modal" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form role="form" method="post" action="<?php echo base_url('index.php/client_scenario') ?>">
                                                            <div class="modalcontain">
                                                                <div class="modal-body m-3">
                                                                    <div class="row">
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" style="color: black;" for="name">Quantidade Máxima de compras</label>
                                                                                <input type="text" name="qtd_max" class="mt-1 form-control" placeholder="Insira a quantidade máxima de compras" required>
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-lg-6">
                                                                            <div class="form-group">
                                                                                <label class="form-control-label" style="color: black;" for="name">Eye tracking</label><br>
                                                                                <select name="eye_tracking" class="mt-1 form-control" required>
                                                                                    <option disabled>Selecione </option>
                                                                                    <option name="eye_tracking" value="true">Sim</option>
                                                                                    <option name="eye_tracking" value="false">Não</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <input name="id_cenario" id="id_cenario1" type="hidden">
                                                                <input name="id_company" id="id_company1" type="hidden">
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="text-white btn bg-orange">Logar</button>
                                                                </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer" style="text-align: center"><small class="text-muted"><?php echo $scenarios[$count]['name']; ?> </small> </div>
                                        <!-- <div class="card-header" style="text-align: center">Header</div> -->
                            </div>

                    <?php $count++;
                                    }
                                } ?>
            </div>
            <?php if ($count == count($scenarios)) break; ?>
        <?php } ?>
<?php }
                } ?>
        </div>
    </div>
</div>
</div>
<script>
    var isGrid = false;

    function loga_client(id) {
        let id_scenario = $(id).data('scenario');
        let id_company = $(id).data('company');
        $('#id_cenario').val(id_scenario);
        $('#id_company').val(id_company);
    };

    function loga_client1(id) {
        let id_scenario = $(id).data('scenario');
        let id_company = $(id).data('company');
        $('#id_cenario1').val(id_scenario);
        $('#id_company1').val(id_company);
    };


    function toggle_view() {
        if (isGrid) {
            isGrid = false;
            $('#grid').hide();
            $('#table').show();
            $('#btn_toggle').text("Visualizar em grade")
        } else {
            isGrid = true;
            $('#grid').show();
            $('#table').hide();
            $('#btn_toggle').text("Visualizar em tabela")
        }
    }
    $(function() {
        $('#grid').hide();
        $('#datatables-dashboard-projects').DataTable({});
    });
</script>