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
    <div class="modal fade" id="delete1" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">editar cenário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body m-3">
                    <div class="text-center">
                        <h4 id="editar1" class="mb-4">Tem certeza de que deseja editar Beer-Scenario 1?</h4>
                        <button type="button" class="btn btn-success" id="btn-success_edit" onclick="editar(this)" data-id="">SIM</button>
                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal" aria-label="Close">NÃO</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 d-flex">
        <div class="card flex-fill p-3">
            <div class="row align-items-center col-12 mb-3">
                <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                    <h3 class="mb-0">Setores</h3>
                </div>
                <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-3">
                    <!--                    <a href="new_planogram" class="btn bg-orange text-white float-end" style="white-space:nowrap;">Adicionar planograma</a>-->
                    <a href="<?= base_url('index.php/export_csv_alert_priducts'); ?>" class="col-5 btn btn-github text-white float-end m-1" >Exportar</a>
                </div>
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
                                            <a onclick="edit_scenario1(this)" href="#" data-id="<?php echo $planograms[$count]['id'] ?>" data-name="<?php echo $planograms[$count]['name'] ?>" data-bs-toggle="modal" data-bs-target="#delete1">
                                                <img class="card-img-top" style="min-height:100px; text-align:center;  object-fit: cover;" src="<?php echo base_url('writable/uploads/scenarios/scenario' . $planograms[$count]['id'] . '.png'); ?>" alt="Preview not available">
                                            </a>
<!--                                            <div class="dropdown-menu dropdown-menu-end" id="drops">-->
<!--                                                <a class="dropdown-item" href="--><?php //echo base_url('/index.php/share?planogram=' . $planogram['id']); ?><!--">Visualizar Share</a>-->
<!--                                                <a class="dropdown-item" href="--><?php //echo base_url('index.php/edit_planogram/' . $planograms[$count]['id']) ?><!--">Editar Planograma</a>-->
<!--                                                <a class="dropdown-item" href="--><?php //echo base_url('index.php/duplica_planogram/' . $planograms[$count]['id']) ?><!--">Duplicar Planograma</a>-->
<!--                                                <a class="dropdown-item" onclick="remove_scenario1(this)" href="#" data-id="--><?php //echo $planograms[$count]['id'] ?><!--" data-name="--><?php //echo $planograms[$count]['name'] ?><!--" data-bs-toggle="modal" data-bs-target="#delete1">Excluir Planograma</a>-->
<!--                                                <a class="dropdown-item" href="#" onclick="open_modal1(this)" data-id="--><?php //echo $planograms[$count]["id"] ?><!--" data-name="--><?php //echo $planograms[$count]["name"] ?><!--" data-toggle="modal" data-target="#libera_planogram1">Liberar planograma para campo</a>-->
<!--                                            </div>-->

                                            <div class="card-footer" style="text-align: center"><small class="text-muted"><?php echo $planograms[$count]['name']; ?> </small> </div>

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

    function edit_scenario1(scenario) {
        let id = $(scenario).data('id');
        $('#btn-success_edit').attr('data-id', id);
        let name = $(scenario).data('name');
        $('#editar1').text('Tem certeza de que deseja editar ' + name + ' ?');
    }

    function editar(scenario) {
        let id = $(scenario).data('id');
        window.location.href = "<?php echo base_url('index.php/edit_alert_planogram') ?>" + "?planogram=" + id;
    }


    $(function() {
        $('#table').hide();
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
