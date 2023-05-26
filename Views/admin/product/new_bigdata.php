<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = \Config\Services::session();
$error_msg = $session->getFlashdata('error_msg');
?>
<!DOCTYPE html>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/mystyle.css') ?>" rel="stylesheet" />

<div class="row">
    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="card-header border-0" style="background-color: #fc9700;">
                <div class="row align-items-center col-12 ">
                    <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8" style="background-color: #fc9700;">
                        <h3 class="mb-0 text-white">Adicionar Base Produtos</h3>
                    </div>
                    <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-1">
                        <a href="import_export"><button class="btn btn bg-white float-end m-1">Importar/Exportar Base Produtos</button></a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <h6 class="card-subtitle text-muted mt-2">Informações do Base Produto</h6>
                <hr class="mt-2" />
                <form id="dropzone-form" role="form" enctype="multipart/form-data" action="javascript:void(0)">
                    <?php if ($error_msg) { ?>
                        <div class="mt-3 alert alert-danger alert-dismissible" role="alert">
                            <div class="alert-message"> <?php echo $error_msg; ?></div>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php } ?>
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="name">Nome do Base Produto</label>
                                <input type="text" id="name" name="name" class="mt-2 form-control form-control-alternative" placeholder="Nome do Base Produto" required>
                                <div class="alert" id="nameError" style="color: #f2545b;display: none;" role="alert">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="shelves">Preço</label>
                                <input type="text" id="price" name="price" class="mt-2 money form-control form-control-alternative" placeholder="Preço do Base Produto">
                                <div class="alert" id="priceError" style="color: #f2545b;display: none;" role="alert">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="brand">Marca</label>
                                <input type="text" id="brand" name="brand" class="mt-2 form-control form-control-alternative" placeholder="Marca do Base Produto" required>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="brand">Fabricante</label>
                                <input type="text" id="producer" name="producer" class="mt-2 form-control form-control-alternative" placeholder="Fabricante do Base Produto">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="grammage">Gramatura </label>
                                <input type="text" id="grammage" name="grammage" class="mt-2 form-control form-control-alternative" placeholder="Gramatura do Base Produto" required>
                                <div class="alert" id="grammageError" style="color: #f2545b;display: none;" role="alert">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="feature">Característica</label>
                                <input type="text" id="feature" name="feature" class="mt-2 form-control form-control-alternative" placeholder="Sabor (para comestíveis), Aroma (para Base Produtos de limpeza), Cor (para roupas e objetos)">
                                <div class="alert" id="featureError" style="color: #f2545b;display: none;" role="alert">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="ean">Ean do Base Produto</label>
                                <input type="text" name="ean" id="ean" class="mt-2 form-control form-control-alternative" placeholder="Ean do Base Produto" required>
                                <div class="alert" id="eanError" style="color: #f2545b;display: none;" role="alert">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2" id="checkbox">
                            <div class="form-group">
                                <label class="form-check mt-2">
                                    <input type="checkbox" id="rand_ean" onclick="gerarEan(this)" class="form-check-input">
                                    <span class="mt-4 form-check-label">Gerar EAN</span>
                                    <div id="info" title="Código único de identificação do Base Produto." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
                                </label>
                                <input id="button_url" class="btn bg-orange text-white mt-2" type="button" value="Importar Produto" onclick="import_from_base()" />
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="category">Categoria do Base Produto</label>
                                <select class="mt-2 form-control form-control-alternative" id="category" name="category" onchange="habilitarAdicionarCategoria(this.value)">
                                    <option value="">Selecione uma categoria</option>
                                    <?php foreach ($categories as $category) { ?>
                                        <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                                    <?php } ?>
                                    <option value="add">Adicionar nova categoria</option>
                                </select>
                                <div class="alert" id="categoryError" style="color: #f2545b;display: none;" role="alert">
                                </div>
                            </div>
                        </div>
                        <input type="hidden" value='' id="url" name="images_url">
                        <input type="hidden" value='' id="url2" name="images_url2">
                        <div class="col-lg-6">
                            <label class="mt-2"></label>
                            <div class="col-lg-6">
                                <input id="button_url" class="btn bg-orange text-white mt-2" type="button" value="Inserir nova categoria" onclick="habilitarAdicionarCategoria('add')" />
                            </div>
                        </div>
                        <div class="col-lg-6" id="new_category" style="display:none;">
                            <div class="form-group">
                                <label class="mt-2 form-control-label" for="category">Criar nova categoria</label>
                                <input type="text" id="categoria" name="new_category" class="mt-2 form-control form-control-alternative" placeholder="Digita a nova categoria">
                            </div>
                        </div>
                        <div class="col-lg-6" id="salvar_categoria" onclick="salvarCategoria()" style="display:none;">
                            <input class="btn bg-orange text-white" type="text" data-bs-dismiss="modal" value="Cadastrar categoria" readonly />
                        </div>

                        <div class="card-group">
                            <h6 class="card-subtitle text-muted mt-4">Informações para gerar o share de gôndola <div id="info" title="Percentual de participação de cada Base Produto na gôndola." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
                            </h6>
                        </div>

                        <hr class="mt-2" />
                        <div class="col-lg-6">
                            <label class="mt-2 form-control-label">Largura</label>
                            <div id="info" title="As medidas de altura e largura do Base Produto são utilizadas para gerar o share." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
                            <div class="input-group mt-2">
                                <input type="number" name="width" id="width" min="1" class="form-control" placeholder="Largura do Base Produto em centímetros">
                                <button class="btn bg-orange text-white" type="button" style="pointer-events: none;">cm</button>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <label class="mt-2 form-control-label">Altura</label>
                            <div id="info" title="As medidas de altura e largura do Base Produto são utilizadas para gerar o share." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
                            <div class="input-group mt-2">
                                <input type="number" name="height" id="height" min="1" class="form-control" placeholder="Altura do Base Produto em centímetros">
                                <button class="btn bg-orange text-white" type="button" style="pointer-events: none;">cm</button>
                            </div>
                        </div>

                        <h6 class="card-subtitle text-muted mt-4">Imagens do Base Produto</h6>
                        <hr class="mt-2" />
                        <input type="hidden" value="<?php echo (!empty($product['id'])) ? $product['id'] : ""; ?>" name="id">
                        <div class="row col-lg-12 mt-3">

                            <div class="col-lg-6 mt-1">
                                <label>Imagem</label>
                                <div id="info" title=" Imagem que irá aparecer na gondola." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
                                <div data-bs-toggle="modal" data-bs-target="#sizedModalLg" id="imgProd" style="min-height: 150px;border: 1px solid #000;display: flex;align-items: center;justify-content: center;text-align: center;cursor: pointer;" class="mt-2 mb-5">
                                    <img src="" height="170px" class="imgProd" />

                                    <p>Selecione a imagem</p>
                                </div>
                            </div>
                            <div class="col-lg-6 mt-1">
                                <label>Imagem 360</label>
                                <div id="info" title="Imagem que irá aparecer no popup que será aberto quando o usuário clicar em um Base Produto da gondola." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
                                <div data-bs-toggle="modal" data-bs-target="#sizedModalLg2" id="img360" style="min-height: 150px;border: 1px solid #000;display: flex;align-items: center;justify-content: center;text-align: center;cursor: pointer;" class="mt-2 mb-5">
                                    <img class="img360" src="" height="170px" />

                                    <p>Selecione a imagem</p>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 text-center mt-3">
                            <input id="submit-dropzone" class="botao bg-orange text-white" type="submit" onclick="enviaForm()" name="submitDropzone" value="Cadastrar Base Produto" />
                        </div>
                    </div>
                </form>

                <div class="modal fade" id="Aviso" tabindex="-1" role="dialog" aria-hidden="true" style=" padding-top: 10%; padding-bottom: 20%;" >
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Aviso</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body m-3">
                                <div class="text-center">
                                    <h4 id="certeza1" class="mb-4">Gostaria de obter Base Produtos da lista de Base Produtos?</h4>
                                    <button id="btn-success1" class="product_ean" onclick="get_Base_Produtos()" data-bs-dismiss="modal" data-id="" style="
                                        background-color: rgb(0, 204, 153);
                                        border-color: rgb(0, 204, 153);
                                        display: inline-block;
                                        font-weight: 400;
                                        line-height: 1.5;
                                        color: #3e4676;
                                        text-align: center;
                                        vertical-align: middle;
                                        cursor: pointer;
                                        -webkit-user-select: none;
                                        -moz-user-select: none;
                                        user-select: none;
                                        padding: 0.25rem 0.7rem;
                                        font-size: .9375rem;
                                        border-radius: 0.2rem;
                                        transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;">
                                        SIM
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button data-bs-toggle="modal" data-bs-target="#Aviso" id="open_modal" style="display: none"></button>
                <div class="modal fade" id="sizedModalLg" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <ul class="nav nav-tabs" id="ex1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="ex1-tab-1" data-mdb-toggle="tab" href="#ex1-tabs-1" role="tab" aria-controls="ex1-tabs-1" aria-selected="true">Galeria</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="ex1-tab-2" data-mdb-toggle="tab" href="#ex1-tabs-2" role="tab" aria-controls="ex1-tabs-2" aria-selected="false">Upload</a>
                                    </li>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="tab-content" id="ex1-content">
                                    <div class="tab-pane fade show active" id="ex1-tabs-1" role="tabpanel" aria-labelledby="ex1-tab-1">
                                        <p class="mb-0">Selecione a imagem para obter a URL:</p>
                                        <hr />
                                        <div id="loading2" style="display: block;text-align: center;">
                                            <img src="<?php echo base_url('/assets/img/loader1.gif') ?>" alt="" srcset="">
                                        </div>
                                        <script>
                                            function ajax2() {
                                                $.ajax({
                                                        url: "<?php echo base_url('index.php/loading_img') ?>",
                                                        type: 'get',
                                                    })
                                                    .done(function(msg) {
                                                        img = JSON.parse(msg);
                                                        $('#galeria_popup2').empty();
                                                        $('#galeria_popup2').remove();
                                                        gallery2(img);
                                                    })
                                                    .fail(function(jqXHR, textStatus, msg) {
                                                        console.log(msg)
                                                    });
                                            }

                                            function gallery2(img) {

                                                var newdiv = document.createElement('div');
                                                newdiv.setAttribute(
                                                    'style',
                                                    'overflow-y:auto; max-height:550px;display: flex;flex-wrap: wrap;justify-content: space-around;',
                                                )
                                                newdiv.setAttribute(
                                                    'class',
                                                    'teste',
                                                )
                                                newdiv.setAttribute(
                                                    'id',
                                                    'galeria_popup2',
                                                )
                                                var tabela = document.querySelector("#galeria_popup2")
                                                if (tabela) {
                                                    tabela.remove()
                                                }
                                                var loaded = '';
                                                Object.keys(img).forEach(function(item) {
                                                    loaded += `<div class="popup_image" id="" onclick="image2(this)">
                                                    <img class="mb-10 lazy ex2-images" src="<?php echo base_url(); ?>/` + img[item].src + `" height="150px">
                                                    </div> `;
                                                });
                                                newdiv.innerHTML += loaded;
                                                newdiv.innerHTML += '</div>';
                                                document.getElementById('ex1-tabs-1').appendChild(newdiv);
                                                document.getElementById("loading2").style.display = "none";

                                            }
                                        </script>
                                    </div>
                                    <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
                                        <form action="<?php echo base_url('index.php/dragDropUpload'); ?>" id="dropzone2" class="dropzone"></form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="sizedModalLg2" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <ul class="nav nav-tabs" id="ex2" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link active" id="ex2-tab-1" data-mdb-toggle="tab" href="#ex2-tabs-1" role="tab" aria-controls="ex2-tabs-1" aria-selected="true">Galeria</a>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <a class="nav-link" id="ex2-tab-2" data-mdb-toggle="tab" href="#ex2-tabs-2" role="tab" aria-controls="ex2-tabs-2" aria-selected="false">Upload</a>
                                    </li>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="tab-content" id="ex2-content">
                                    <div class="tab-pane fade show active" id="ex2-tabs-1" role="tabpanel" aria-labelledby="ex2-tab-1">
                                        <p class="mb-0">Selecione a imagem para obter a URL:</p>
                                        <hr />
                                        <div id="loading" style="display: block;text-align: center;">
                                            <img src="<?php echo base_url('/assets/img/loader1.gif') ?>" alt="" srcset="">
                                        </div>
                                        <script>
                                            function ajax() {
                                                $.ajax({
                                                        url: "<?php echo base_url('index.php/loading_img') ?>",
                                                        type: 'get',
                                                    })
                                                    .done(function(msg) {
                                                        img = JSON.parse(msg);
                                                        $('#galeria_popup').empty();
                                                        $('#galeria_popup').remove();
                                                        gallery(img);

                                                    })
                                                    .fail(function(jqXHR, textStatus, msg) {
                                                        console.log(msg)

                                                    });
                                            }

                                            function gallery(img) {

                                                var newdiv = document.createElement('div');
                                                newdiv.setAttribute(
                                                    'style',
                                                    'overflow-y:auto; max-height:550px;display: flex;flex-wrap: wrap;justify-content: space-around;',
                                                )
                                                newdiv.setAttribute(
                                                    'class',
                                                    'teste',
                                                )
                                                newdiv.setAttribute(
                                                    'id',
                                                    'galeria_popup',
                                                )
                                                var tabela = document.querySelector("#result")
                                                if (tabela) {
                                                    tabela.remove()
                                                }
                                                var loaded = '';
                                                Object.keys(img).forEach(function(item) {
                                                    loaded += `<div class="popup_image2" onclick="image(this)">
                            <img class="mb-10 lazy ex2-images" src="<?php echo base_url(); ?>/` + img[item].src + `" height="150px">
                            </div> `;
                                                });
                                                newdiv.innerHTML += loaded;
                                                newdiv.innerHTML += '</div>';
                                                document.getElementById('ex2-tabs-1').appendChild(newdiv);
                                                document.getElementById("loading").style.display = "none";

                                            }
                                        </script>
                                    </div>
                                    <div class="tab-pane fade" id="ex2-tabs-2" role="tabpanel" aria-labelledby="ex2-tab-2">
                                        <form action="<?php echo base_url('index.php/dragDropUpload'); ?>" id="dropzone" class="dropzone"></form>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input class="btn bg-orange text-white" type="text" data-bs-dismiss="modal" value="Enviar" readonly />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.6.0/mdb.min.js"></script>
<script src="<?php echo base_url('/assets/theme/bootstrap5/docs/js/dropzone.js') ?>"></script>
<script src="<?php echo base_url('/assets/js/pages/admin/products/index.js') ?>" base_url='<?php echo base_url('index.php'); ?>'></script>

<!-- Dropzone -->

<script>
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("#dropzone", {
        url: "<?php echo base_url('index.php/dragDropUpload'); ?>",
        method: "POST",
        paramName: "file",
        autoProcessQueue: true,
        acceptedFiles: "image/*",
        maxFiles: 100,
        maxFilesize: 8, // MB
        parallelUploads: 100, // use it with uploadMultiple
        createImageThumbnails: true,
        thumbnailWidth: 120,
        thumbnailHeight: 120,
        addRemoveLinks: false,
        timeout: 100000,
        dictRemoveFileConfirmation: "Tem certeza?", // ask before removing file
        // Language Strings
        dictFileTooBig: "Arquivo muito grande ({{filesize}}mb). Máximo de {{maxFilesize}}mb",
        dictInvalidFileType: "Tipo de arquivo inválido",
        dictCancelUpload: "Cancelar",
        dictRemoveFile: "Remover",
        dictMaxFilesExceeded: "Somente {{maxFiles}} arquivos são aceitos",
        dictDefaultMessage: "Solte seus arquivos aqui para enviar",
    });

    myDropzone.on("addedfile", function(file) {
        console.log('file');
    });

    myDropzone.on("removedfile", function(file) {});

    // Add mmore data to send along with the file as POST data. (optional)
    myDropzone.on("sending", function(file, xhr, formData) {});

    myDropzone.on("error", function(file, response) {
        console.log(response);
    });

    // on success
    myDropzone.on("successmultiple", function(file, response) {
        // get response from successful ajax request
        console.log(response);
        // submit the form after images upload
        // (if u want yo submit rest of the inputs in the form)
        window.location.href = "<?php echo base_url('index.php/update_product'); ?>";
    });


    /**
     *  Add existing images to the dropzone
     *  @var images
     *
     */



    // button trigger for processingQueue
    var submitDropzone = document.getElementById("submit-dropzone");
    submitDropzone.addEventListener("click", function(e) {
        // Make sure that the form isn't actually being sent.
        e.preventDefault();
        e.stopPropagation();

        if (myDropzone.files != "") {
            // console.log(myDropzone.files);
            myDropzone.processQueue();
        } else {
            // if no file submit the form    
            document.getElementById("dropzone-form").submit();
        }

    });


    myDropzone.on("complete", function() {
        console.log('complete')

        document.getElementById("loading2").style.display = "block";
        var uploadTab = document.getElementById("ex2-tab-2")
        var upload = document.getElementById("ex2-tabs-2")
        var gallery = document.getElementById("ex2-tabs-1")
        var galleryTab = document.getElementById("ex2-tab-1")
        uploadTab.classList.remove('active');
        galleryTab.classList.add('active');
        upload.classList.remove('active', 'show');
        gallery.classList.add('active', 'show');

        ajax();
        ajax2();

    });
</script>

<!-- Dropzone -->

<!-- Dropzone 2 -->

<script>
    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("#dropzone2", {
        url: "<?php echo base_url('index.php/dragDropUpload'); ?>",
        method: "POST",
        paramName: "file",
        autoProcessQueue: true,
        acceptedFiles: "image/*",
        maxFiles: 100,
        maxFilesize: 8, // MB
        parallelUploads: 100, // use it with uploadMultiple
        createImageThumbnails: true,
        thumbnailWidth: 120,
        thumbnailHeight: 120,
        addRemoveLinks: false,
        timeout: 100000,
        dictRemoveFileConfirmation: "Tem certeza?", // ask before removing file
        // Language Strings
        dictFileTooBig: "Arquivo muito grande ({{filesize}}mb). Máximo de {{maxFilesize}}mb",
        dictInvalidFileType: "Tipo de arquivo inválido",
        dictCancelUpload: "Cancelar",
        dictRemoveFile: "Remover",
        dictMaxFilesExceeded: "Somente {{maxFiles}} arquivos são aceitos",
        dictDefaultMessage: "Solte seus arquivos aqui para enviar",
    });

    myDropzone.on("addedfile", function(file) {
        console.log('file22');
    });

    myDropzone.on("removedfile", function(file) {});

    // Add mmore data to send along with the file as POST data. (optional)
    myDropzone.on("sending", function(file, xhr, formData) {
        formData.append("dropzone", "1");
        nameValue = document.querySelector("input[name=name]").value;
        formData.append("name", nameValue);
        priceValue = document.querySelector("input[name=price]").value;
        formData.append("price", priceValue);
        priceValue = document.querySelector("input[name=grammage]").value;
        formData.append("grammage", priceValue);
        idValue = document.querySelector("input[name=producer]").value;
        formData.append("producer", idValue);
        urlValue = document.querySelector("input[name=images_url]").value;
        formData.append("images_url", urlValue);
        featureValue = document.querySelector("input[name=feature]").value;
        formData.append("feature", featureValue);
        eanValue = document.querySelector("input[name=ean]").value;
        formData.append("ean", eanValue);
        categoryValue = document.querySelector("select[name=category]").value;
        formData.append("category", categoryValue);
        brandValue = document.querySelector("input[name=brand]").value;
        formData.append("brand", brandValue);
        idValue = document.querySelector("input[name=id]").value;
        formData.append("id", idValue);
    });

    //$("input[name=ean]").change( function() {
    //    let ean = $("input[name=ean]").val();
    //    // $("input[name=ean]").val('');
    //    if (ean != null) {
    //        $.ajax({
    //            url: "<?php //echo base_url('index.php/check_base_produtos'); ?>//",
    //            method: "POST",
    //            data: {ean_product: ean},
    //            success: function (data) {
    //                if (JSON.parse(data).success == 1) {
    //                    $('.product_ean').attr('data-id', ean);
    //                    $("#open_modal").click();
    //                }
    //            }
    //        })
    //    }
    //});

    function import_from_base() {
        console.log('aaa')
        let ean = $("input[name=ean]").val();
        // $("input[name=ean]").val('');
        if (ean != null) {
            $.ajax({
                url: "<?php echo base_url('index.php/check_base_produtos'); ?>",
                method: "POST",
                data: {ean_product: ean},
                success: function (data) {
                    if (JSON.parse(data).success == 1) {
                        $('.product_ean').attr('data-id', ean);
                        $("#open_modal").click();
                    }
                }
            })
        }
    }

    function get_Base_Produtos() {
        let ean = $('.product_ean').attr('data-id');
        $.ajax({
            url: "<?php echo base_url('index.php/get_base_produtos'); ?>",
            method: "POST",
            data: {ean_product: ean},
            success: function (data) {
                console.log(JSON.parse(data))
                let dataArray = JSON.parse(data);
                console.log(dataArray);
                $('#name').val(dataArray['product'][0]['name']);
                $('#price').val(dataArray['product'][0]['price']);
                $('#brand').val(dataArray['product'][0]['hoje']);
                $('#producer').val(dataArray['product'][0]['producer']);
                $('#category').val(dataArray['product'][0]['category']);
                $('#grammage').val(dataArray['product'][0]['grammage']);
                $('#feature').val(dataArray['product'][0]['feature']);
                $('#ean').val(dataArray['product'][0]['ean']);
                $('#width').val(dataArray['product'][0]['width']);
                $('#height').val(dataArray['product'][0]['height']);
                $('.imgProd').attr('src', dataArray['product_images']);
                $('.img360').attr('src', dataArray['product'][0]['url']);
            }
        })
    }

    myDropzone.on("error", function(file, response) {
        console.log(response);
    });

    // on success
    myDropzone.on("successmultiple", function(file, response) {
        // get response from successful ajax request
        console.log(response);
        // submit the form after images upload
        // (if u want yo submit rest of the inputs in the form)
        window.location.href = "<?php echo base_url('index.php/update_product'); ?>";
    });


    /**
     *  Add existing images to the dropzone
     *  @var images
     *
     */



    // button trigger for processingQueue
    var submitDropzone = document.getElementById("submit-dropzone");
    submitDropzone.addEventListener("click", function(e) {
        // Make sure that the form isn't actually being sent.
        e.preventDefault();
        e.stopPropagation();

        if (myDropzone.files != "") {
            // console.log(myDropzone.files);
            myDropzone.processQueue();
        } else {
            // if no file submit the form    
            document.getElementById("dropzone-form").submit();
        }

    });

    myDropzone.on("complete", function() {

        console.log('complete2')

        document.getElementById("loading2").style.display = "block";
        var uploadTab = document.getElementById("ex1-tab-2")
        var upload = document.getElementById("ex1-tabs-2")
        var gallery = document.getElementById("ex1-tabs-1")
        var galleryTab = document.getElementById("ex1-tab-1")
        uploadTab.classList.remove('active');
        galleryTab.classList.add('active');
        upload.classList.remove('active', 'show');
        gallery.classList.add('active', 'show');
        ajax2();
        ajax();
    });
</script>
<script>
    function enviaForm() {
        var logError = '';
        eanValue = document.getElementById("ean").value;
        nameValue = document.getElementById("name").value;
        priceValue = document.getElementById("price").value;
        grammageValue = document.getElementById("grammage").value;
        producerValue = document.getElementById("producer").value;
        imgProdValue = document.getElementById("url2").value;
        img360Value = document.getElementById("url").value;
        featureValue = document.getElementById("feature").value;
        categoryValue = document.getElementById("category").value;
        brandValue = document.getElementById("brand").value;

        if (eanValue == '') {
            error = "Preencha o campo ean"
            alert(error);
        } else {

            var form = document.getElementById('dropzone-form');
            var formData = new FormData(form);
            if (nameValue == '') {
                var error = document.getElementById("nameError")
                error.style.display = "block"
                error.innerHTML = 'Preencha o campo "Nome"'
                logError = 'erro';
            }
            if (priceValue == '') {
                var error = document.getElementById("priceError")
                error.style.display = "block"
                error.innerHTML = 'Preencha o campo "Preço"'
                logError = 'erro';
            }
            if (grammageValue == '') {
                var error = document.getElementById("grammageError")
                error.style.display = "block"
                error.innerHTML = 'Preencha o campo "Gramatura"'
                logError = 'erro';
            }
            if (categoryValue == '') {
                var error = document.getElementById("categoryError")
                error.style.display = "block"
                error.innerHTML = 'Preencha o campo "Categoria"'
                logError = 'erro';
            }
            if (featureValue == '') {
                var error = document.getElementById("featureError")
                error.style.display = "block"
                error.innerHTML = 'Preencha o campo "Caracteristica"'
                logError = 'erro';
            }
            formData.append("name", nameValue);
            formData.append("price", priceValue);
            formData.append("grammage", grammageValue);
            formData.append("producer", producerValue);
            formData.append("images_url2", imgProdValue);
            formData.append("images_url", img360Value);
            formData.append("feature", featureValue);
            formData.append("ean", eanValue);
            formData.append("category", categoryValue);
            formData.append("brand", brandValue);
            console.log(logError);
            if (logError == '') {
                $.ajax({
                        url: "<?php echo base_url('index.php/add_bigdata') ?>",
                        type: 'post',
                        data: formData,
                        contentType: false,
                        processData: false
                    })
                    .done(function(msg) {
                        location.href = "<?php echo base_url('index.php/bigdata_products?order=DESC&orderBy=ID') ?>";
                    })
                    .fail(function(jqXHR, textStatus, msg) {
                        console.log(msg)
                    });
            } else {
                console.log(logError);
            }
            d
        }

    }
    ajax();
    ajax2();
</script>
<!--  FIM Dropzone 2 -->