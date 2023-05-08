<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = session();
$id_user = $session->get('id');
$user = $usuario;
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
        width: 100%
    }
</style>
<div class="row">
    <div class="col-12 col-md-12 col-sm-12 col-lg-12 col-xl-12 d-flex">
        <div class="card flex-fill p-3">

            <div class="row align-items-center col-12">
                <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                    <h3 class="mb-3 mt-3">Painel Financeiro</h3>
                </div>
            </div>
            <div id="table">
                <table id="datatables-dashboard-projects" class="table table-striped my-0">
                    <thead>
                        <tr>
                            <th class="col">Data da transação</th>
                            <th scope="col">Metodo de Pagamento</th>
                            <th class="col">Status</th>
                            <th class="col">Valor</th>
                            <th class="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (is_array($paymentset)) {
                            foreach ($paymentset as $payment) { ?>
                                <tr id="scenario-<?php echo $payment['client_id']; ?>">
                                    <td class="d-none d-md-table-cell">
                                        <?php echo $payment["created_in"];?>
                                    </td>
                                    <td>
                                        <?php echo $payment["payment_method"]; ?>
                                        <div class="d-none" id="id_scenario"><input name="id_scenario" type="hidden" value="<?php echo $payment["client_id"] ?>">
                                            <div>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php echo $payment['status'] ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <?php  echo "R$ " . number_format($payment['total_value'], 2, ",", "."); ?>
                                    </td>
                                    <td class="d-none d-md-table-cell">
                                        <div class="d-inline-block dropdown show">
                                            <?php if($payment['payment_method'] == 'Boleto'){?>
                                                <a class="btn btn-sm btn-icon-only" href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-end" id="drops">
                                                    <a class="dropdown-item" href=<?php echo  '"'. $payment['boleto']. '"'; ?> target="_blank">Download Boleto</a>
                                                </div>
                                            <?php }?>
                                        </div>
                                    </td>
                                </tr>
                            <?php }
                        } ?>
                    </tbody>
                </table>
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
    $(function() {
        $('#grid').hide();
        $('#datatables-dashboard-projects').DataTable({});
    });
</script>