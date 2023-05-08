<?php 
    error_reporting(E_ALL & ~E_NOTICE);
    $session = session();
    $success_msg= $session->getFlashdata('success_msg');
?>
<script src="<?php echo base_url('/assets/theme/bootstrap5/docs/js/dropzone.js') ?>"></script>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/mystyle.css') ?>" rel="stylesheet"/>
<style>
#image{width:175px;height:175px;}
#image:hover{opacity:0.8;cursor:pointer;}
.list-group-item.active{ background-color: #f89e24; color:#ffffff; }
.wrong { outline-color: red; -webkit-animation: shake .5s linear;}
#enviar{align-items}
@media (max-width: 560px) {#image{width:100px; height:100px; }}
@-webkit-keyframes shake {
    8%, 41% {
        -webkit-transform: translateX(-10px);
    }
    25%, 58% {
        -webkit-transform: translateX(10px);
    }
    75% {
        -webkit-transform: translateX(-5px);
    }
    92% {
        -webkit-transform: translateX(5px);
    }
    0%, 100% {
        -webkit-transform: translateX(0);
    }
}
</style>
<div class="row">
    <div class="col-md-4 col-xl-3">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Minha conta</h5>
            </div>
            <div class="list-group list-group-flush" role="tablist">
                <a class="list-group-item list-group-item-action active" data-bs-toggle="list" href="#conta" role="tab">Informações</a>
                <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#senha" role="tab">Senha</a>
                <a class="list-group-item list-group-item-action" data-bs-toggle="list" href="#img" role="tab">Foto de perfil</a>
            </div>
        </div>
    </div>

    <div class="col-md-8 col-xl-9">
        <div class="tab-content">
            <div class="tab-pane fade show active " id="conta" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Informações</h5>
                        <?php if($success_msg){ ?>
                        <div class="alert alert-success alert-dismissible" role="alert">
                            <div class="alert-message"><?php echo $success_msg; ?> </div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php } ?>
                        <form method="post" action="<?php echo base_url('index.php/update_user');?>">
                            <div class="row">
                                <div class="col-md-2 mb-2">                                    
                                    <label>ID</label>
                                    <input type="text" class="form-control mt-1" name="id" value="<?php echo $user['id'];?>" readonly>
                                </div>
                                <div class="col-md-5 mb-2">
                                    <label>Nome</label>
                                    <input type="text" class="form-control mt-1" name="name" placeholder="Nome Completo" value="<?php echo $user['name'];?>" required>
                                </div>
                                <div class="col-md-5 mb-3">
                                    <label>E-mail</label>
                                    <input type="email" class="form-control mt-1" name="email" placeholder="E-mail" value="<?php echo $user['email'];?>" required>
                                </div>
                            </div>
                            <button type="submit" class="btn bg-orange text-white mt-3">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="senha" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Mudar Senha</h5>
                            <input type="hidden" name="id" id="id" value="<?php echo $user['id'];?>">
                            <div class="alert alert-success alert-dismissible" role="alert" style="display:none" id="update_pass">
                                <div class="alert-message">Senha atualizada com sucesso!</div>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>                         
                            <div class="mb-3">
                                <label>Senha Atual</label>
                                <input type="password" class="form-control mt-1" id="old_pass" placeholder="Insira aqui sua nova senha" required>
                                <p id="error1" class="text-danger d-none">Senha atual incorreta. Tente novamente! </p>                         
                            </div>
                            <div class="mb-3">
                                <label>Nova senha</label>
                                <input type="password" class="form-control mt-1" id="new_pass" placeholder="Insira aqui sua nova senha" required>
                            </div>                            
                            <div class="mb-3">
                                <label>Confime a nova senha</label>
                                <input type="password" class="form-control mt-1" id="confirm_pass" placeholder="Insira aqui sua nova senha" required>
                                <p id="error2" class="text-danger d-none">Senhas não correspondem </p>
                            </div>
                            <button onclick="password();" class="btn bg-orange text-white">Salvar</button>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade show" id="img" role="tabpanel">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title mb-3">Foto de perfil</h5>
                        <div class="alert alert-success alert-dismissible" role="alert" style="display:none" id="update_pass">
                            <div class="alert-message">Senha atualizada com sucesso!</div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>      
                        <div style="text-align: center;">
                        <?php if(($user['img_url']) && file_exists($user['img_url'])){ ?>
                            <img src="<?php echo base_url($user['img_url']) ?>" class="img-fluid rounded-circle" alt="Foto de Perfil" id="image">
                        <?php }else{ ?>
                            <img src="<?php echo base_url('writable/uploads/perfil/user_default.png') ?>" class="img-fluid rounded-circle" alt="Foto de Perfil" height="150px" width="150px">
                        <?php } ?>
                        </div>
                        <form id="dropzone-form" role="form" enctype="multipart/form-data" method="post" action="<?php echo base_url('index.php/img_user') ?>">
                            <label>Modificar foto</label>
                            <input type="file" name="file" id="user-img-file" class="d-none">
                            <div id="dropzone" class="dropzone mt-2"></div>
                            <button type="submit" id="enviar" class="btn bg-orange text-white mt-3">Salvar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function password(){
    let id = $('#id').val();
    let old_pass = $('#old_pass').val();
    let new_pass = $('#new_pass').val();
    let confirm_pass = $('#confirm_pass').val();
    let grupo = {'id_user':id, 'old_pass':old_pass, 'new_pass': new_pass, 'confirm_pass':confirm_pass };
    let values = [];
    values.push(grupo);
    $.ajax({
        url:"<?php echo base_url('index.php/user_pass'); ?>",
        method:"POST",
        data:{password:values},
        success:function(data){
            if(data == 'old_pass'){
                $('#error1').removeClass('d-none');
                $('#error2').addClass('d-none');
                $('#old_pass').toggleClass('wrong');
            }else if(data == 'new_pass'){
                $('#error1').hide();
                $('#error2').removeClass('d-none');
                $('#new_pass').toggleClass('wrong');
                $('#confirm_pass').toggleClass('wrong');
            }else{
                $('#old_pass').val('');
                $('#new_pass').val('');
                $('#confirm_pass').val('');
                $('#error1').addClass('d-none');
                $('#error2').addClass('d-none');
                $('#update_pass').show();                   
            }
        }
    });
}

Dropzone.autoDiscover = false;

var myDropzone = new Dropzone("#dropzone", {
    url:"<?php echo base_url('index.php/img_user'); ?>",
    method: "POST",
    paramName: "file",
    autoProcessQueue : false,
    acceptedFiles: "image/*",
    maxFiles: 1,
    maxFilesize: 30, // MB
    uploadMultiple: false,
    createImageThumbnails: true,
    thumbnailWidth: 120,
    thumbnailHeight: 120,
    addRemoveLinks: false,
    timeout: 180000,
    dictRemoveFileConfirmation: "Tem certeza que deseja remover?",
    dictFileTooBig: "Arquivo muito grande ({{filesize}}mb). Máximo de {{maxFilesize}}mb",
    dictInvalidFileType: "Tipo de arquivo inválido",
    dictCancelUpload: "Cancelar",
    dictRemoveFile: "Remover",
    dictMaxFilesExceeded: "Você só pode fazer o u]pload de {{maxFiles}} arquivo.",
    dictDefaultMessage: "Solte seus arquivos aqui para enviar",
});

myDropzone.on("addedfile", function(e) {
});

myDropzone.on("removedfile", function(file) {
});

myDropzone.on("error", function(file, response) {

});

myDropzone.on("success", function(file, response) {
    window.location.reload();
});

var enviar = document.getElementById("enviar");
enviar.addEventListener("click", function(e) {
    e.preventDefault();
    e.stopPropagation();

    if (myDropzone.files != "") {
        myDropzone.processQueue();     
    } else {  
        document.getElementById("dropzone-form").submit();
    }
});

</script>