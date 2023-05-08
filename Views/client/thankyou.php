<!DOCTYPE html>
<style>
    body {background:url(<?php echo base_url('assets/img/theme/bg.jpg') ?>); background-size:100%; background-position:center;}
    .container-fluid {max-width: 1000px;}
</style>

<script type="text/javascript" src="<?php echo base_url("assets/js/jquery-3.4.1.min.js")?>"></script>
<div class="container-fluid mt-7 pb-5">
    <div class="card shadow">
        <div class="card-body text-center">
            <h3 class="text-muted mb-4" style="margin-bottom: 0px; text-align: center; font-size: 50px; color: #333 !important;">Por favor, clique em continuar.</h6>
            <a class="btn btn-success" style="font-size: 50px;" href="<?php echo $link; ?>?id_company=<?php echo $id_company ?>&uuid=<?php echo session()->get("user_uuid") ?>&id=<?php echo session()->get("id") ?>">Continuar</a>
        </div>
    </div>
</div>
    