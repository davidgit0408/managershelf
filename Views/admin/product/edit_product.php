<?php
error_reporting(E_ALL & ~E_NOTICE);
if (isset($_POST["submitDropzone"])) {
  print_r($_POST);
}
$product = $product[0];
$product_image360 = '';
if ($product['url'] != "") {
  if (json_decode($product['url']))
    $product_image360 = json_decode($product['url'], true)[0];
  else
    $product_image360 = $product['url'];
}

?>
<!DOCTYPE html>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/mystyle.css') ?>" rel="stylesheet">
<style>
  @media (min-width: 992px) {
    #checkbox {
      margin-top: 35px;
    }

    #salvar_categoria {
      margin-top: 40px;
    }
  }

  @media (max-width: 991px) {
    #salvar_categoria {
      margin-top: 10px;
    }
  }

  f .form-control {
    height: 2.5rem;
  }

  img.btn.popup_image {
    max-height: 200px;
    width: auto;
    max-width: 170px;
  }

  img.btn.popup_image.active {
    filter: drop-shadow(2px 4px 6px black);
  }

  .modal-header {
    padding: 1rem 1rem 0rem 1rem;
  }

  .nav-tabs {
    border-bottom: none;
  }

  .nav-link.active {
    color: #495057;
    background-color: #fff !important;
    border-color: #dee2e6;
    border-bottom: 1px solid #fff !important
  }
</style>
<div class="row">
  <div class="col-md-12 container mt-3">
    <div class="card">
      <div class="card-header" style="background-color: #fc9700;">
        <h3 class="mt-1 text-white">Editar Produto</h3>
      </div>
      <div class="card-body">
        <h6 class="card-subtitle text-muted ">Informações do Produto</h6>
        <hr />
        <form id="dropzone-form" role="form" enctype="multipart/form-data" action="javascript:void(0)">
          <div class="row">
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="name">Nome do Produto</label>
                <input type="text" id="name" name="name" class="mt-2 form-control form-control-alternative" placeholder="Nome do Produto" value="<?php echo $product['name'] ?>" required>
                <div class="alert" id="nameError" style="color: #f2545b;display: none;" role="alert">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="form-group">
                <label class="form-control-label" for="shelves">Preço</label>
                <input type="text" id="price" name="price" class="mt-2 form-control form-control-alternative" placeholder="Preço do Produto" value="<?php echo $product['price'] ?>" required>
                <div class="alert" id="priceError" style="color: #f2545b;display: none;" role="alert">
                </div>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="mt-2 form-group">
                <label class="form-control-label" for="brand">Marca </label>
                <input type="text" id="brand" name="brand" class="mt-2 form-control form-control-alternative" placeholder="Marca do Produto" value="<?php echo $product['brand'] ?>" required>
              </div>
            </div>
            <div class="col-lg-3">
              <div class="form-group">
                <label class="mt-2 form-control-label" for="brand">Fabricante</label>
                <input type="text" id="producer" name="producer" class="mt-2 form-control form-control-alternative" value="<?php echo $product['producer'] ?>" placeholder="Fabricante do Produto">
              </div>
            </div>
            <div class="col-lg-6">
              <div class="mt-2 form-group">
                <label class="form-control-label" for="shelves">Gramatura</label>
                <input type="text" id="grammage" name="grammage" class="mt-2 form-control form-control-alternative" placeholder="Gramatura do Produto" value="<?php echo $product['grammage'] ?>" required>
                <div class="alert" id="grammageError" style="color: #f2545b;display: none;" role="alert">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="mt-2 form-group">
                <label class="form-control-label" for="name">Característica</label>
                <input type="text" id="feature" name="feature" class="mt-2 form-control form-control-alternative" placeholder="Característica do Produto" placeholder="Sabor (para comestíveis), Aroma (para produtos de limpeza), Cor (para roupas e objetos)" value="<?php echo $product['feature'] ?>" required>
                <div class="alert" id="featureError" style="color: #f2545b;display: none;" role="alert">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="mt-2 form-group">
                <label class="form-control-label" for="ean">Ean do Produto</label>
                <input type="text" id="ean" name="ean" class="mt-2 form-control form-control-alternative" placeholder="Ean do Produto" value="<?php echo $product['ean'] ?>" required>
                <div class="alert" id="eanError" style="color: #f2545b;display: none;" role="alert">
                </div>
              </div>
            </div>
            <div class="col-lg-6">
              <div class="mt-2 form-group">
                <label class="form-control-label" for="category">Categoria do Produto</label>
                <select class="mt-2 form-control form-control-alternative" id="category" name="category" onchange="habilitarAdicionarCategoria(this.value)">
                  <?php foreach ($categories as $category) { ?>
                    <option value="<?php echo $category['id'] ?>"><?php echo $category['name'] ?></option>
                  <?php } ?>
                  <option value="add">Adicionar nova categoria</option>
                </select>
                <div class="alert" id="categoryError" style="color: #f2545b;display: none;" role="alert">
                </div>
              </div>
            </div>

            <!-- Modal/Galeria de imagens -->
            <input type="hidden" value='<?php echo ($product['url']); ?>' id="url" name="images_url">
            <input type="hidden" value='<?php echo ($product['image']); ?>' id="url2" name="images_url2">
            <div class="col-lg-6">
              <label class="mt-2"></label>
              <!-- Botão para abrir o modal -->
              <div class="col-lg-6">
                <input id="button_url" class="btn bg-orange text-white mt-2" type="button" value="Inserir nova categoria" onclick="habilitarAdicionarCategoria('add')" />
              </div>

            </div>

            <!-- Categorias de imagens -->
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
              <h6 class="card-subtitle text-muted mt-4">Informações para gerar o share de gôndola <div id="info" title="Percentual de participação de cada produto na gôndola." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
              </h6>
            </div>

            <hr class="mt-2" />
            <div class="col-lg-6">
              <label class="mt-2 form-control-label">Largura</label>
              <div id="info" title="As medidas de altura e largura do produto são utilizadas para gerar o share." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
              <div class="input-group mt-2">
                <input type="text" id="width" name="width" min="1" class="form-control" value="<?php echo $product['width'] ?>" placeholder="Largura do produto em centímetros">
                <button class="btn bg-orange text-white" type="button" style="pointer-events: none;">cm</button>
              </div>
            </div>

            <div class="col-lg-6">
              <label class="mt-2 form-control-label">Altura</label>
              <div id="info" title="As medidas de altura e largura do produto são utilizadas para gerar o share." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
              <div class="input-group mt-2">
                <input type="text" id="height" name="height" min="1" class="form-control" value="<?php echo $product['height'] ?>" placeholder="Altura do produto em centímetros">
                <button class="btn bg-orange text-white" type="button" style="pointer-events: none;">cm</button>
              </div>
            </div>

            <h6 class="card-subtitle text-muted mt-4">Imagens do Produto</h6>
            <hr class="mt-2" />
            <!-- Exibição das imagens do produto -->
            <input type="hidden" id="id" value="<?php echo $product['id'] ?>" name="id">
            <div class="row col-lg-12">
              <div class="col-lg-6 mt-1">
                <label>Imagem</label>
                <div id="info" title=" Imagem que irá aparecer na gondola." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
                <div data-bs-toggle="modal" data-bs-target="#sizedModalLg2" id="imgProd" style="min-height: 150px;border: 1px solid #000;display: flex;align-items: center;justify-content: center;text-align: center;cursor: pointer;" class="mt-2 mb-5">
                  <img src="<?php if (!$product['image'] == null) {
                              echo $product['image'];
                            } ?>" height="170px" class="imgProd" />
                  <?php if ($product['image'] == null) { ?>
                    <p>Selecione a imagem</p>
                  <?php } ?>
                </div>
              </div>
              <div class="col-lg-6 mt-1">
                <label>Imagem 360</label>
                <div id="info" title="Imagem que irá aparecer no popup que será aberto quando o usuário clicar em um produto da gondola." data-mdb-toggle="tooltip" data-mdb-placement="bottom"><i class="fas fa-info-circle"></i></div>
                <div data-bs-toggle="modal" data-bs-target="#sizedModalLg" id="img360" style="min-height: 150px;border: 1px solid #000;display: flex;align-items: center;justify-content: center;text-align: center;cursor: pointer;" class="mt-2 mb-5">
                  <img class="img360" src="<?php if (!$product_image360 == null) {
                                              echo $product_image360;
                                            } ?>" height="170px" />

                  <?php if ($product_image360 == null) { ?>
                    <p>Selecione a imagem</p>
                  <?php } ?>

                </div>
              </div>
            </div>
            <div class="col-lg-12 text-center">
              <input id="submit-dropzone" class="btn bg-orange text-white" type="submit" name="submitDropzone" onclick="enviaForm()" value="Atualizar Produto" />
            </div>
          </div>
        </form>

        <div class="modal fade" id="sizedModalLg2" tabindex="-1" role="dialog" aria-hidden="true">
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

                        // var primeiro = document.querySelector(".teste .popup_image");
                        // primeiro.setAttribute(
                        //   'class',
                        //   'popup_image',
                        // );
                        // primeiro.setAttribute(
                        //   'id',
                        //   'product_uploaded',
                        // );
                        // var imageUploaded = $('.aoba');
                        // imageUploaded.addClass('active');

                        // firstImg = $('#product_uploaded');
                        // var srcImg = $(firstImg).find('img')[0].src;
                        // var inputImage = document.querySelector('img.imgProd');
                        // inputImage.setAttribute('src', srcImg);
                        // $('input#url').val(JSON.stringify([srcImg]));

                      }
                    </script>

                  </div>
                  <div class="tab-pane fade" id="ex1-tabs-2" role="tabpanel" aria-labelledby="ex1-tab-2">
                    <form action="<?php echo base_url('index.php/dragDropUpload'); ?>" id="dropzone2" class="dropzone"></form>

                  </div>

                </div>

              </div>

            </div>
          </div>
        </div>
        <div class="modal fade" id="sizedModalLg" tabindex="-1" role="dialog" aria-hidden="true">
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


                        // var primeiro = document.querySelector(".teste .popup_image");
                        // primeiro.setAttribute(
                        //   'class',
                        //   'aoba popup_image',
                        // );
                        // primeiro.setAttribute(
                        //   'id',
                        //   'product_uploaded',
                        // );
                        // var imageUploaded = $('.aoba');
                        // imageUploaded.addClass('active');

                        // firstImg = $('#product_uploaded');
                        // var srcImg = $(firstImg).find('img')[0].src;
                        // var inputImage = document.querySelector('img.img360');
                        // inputImage.setAttribute('src', srcImg);
                        // $('input#url').val(JSON.stringify([srcImg]));

                      }
                    </script>
                  </div>
                  <div class="tab-pane fade" id="ex2-tabs-2" role="tabpanel" aria-labelledby="ex2-tab-2">
                    <form action="<?php echo base_url('index.php/dragDropUpload'); ?>" id="dropzone" class="dropzone"></form>
                  </div>
                </div>
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
<script src="<?php echo base_url('/assets/js/pages/admin/products/index.js') ?>" base_url='<?php echo base_url(); ?>'></script>

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
    maxFilesize: 30, // MB
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

  var images = [
    <?php if ($product['image'] != "") { ?> {
        name: "<?php echo $product['image'] ?>",
        url: "<?php echo base_url($product['image']); ?>",
        size: "12345"
      },
    <?php } ?>
  ]

  for (let i = 0; i < images.length; i++) {

    let img = images[i];
    //console.log(img.url);

    // Create the mock file:
    var mockFile = {
      name: img.name,
      size: img.size,
      url: img.url
    };
    // Call the default addedfile event handler
    myDropzone.emit("addedfile", mockFile);
    // And optionally show the thumbnail of the file:
    myDropzone.emit("thumbnail", mockFile, img.url);
    // Make sure that there is no progress bar, etc...
    myDropzone.emit("complete", mockFile);
    // If you use the maxFiles option, make sure you adjust it to the
    // correct amount:
    var existingFileCount = 1; // The number of files already uploaded
    myDropzone.options.maxFiles = myDropzone.options.maxFiles - existingFileCount;

  }

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

  $(document).ready(function() {
    $('select[name=category]').val(<?php echo $product['category'] ?>).change();
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
    maxFilesize: 30, // MB
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

  var images = [
    <?php if ($product['image'] != "") { ?> {
        name: "<?php echo $product['image'] ?>",
        url: "<?php echo base_url($product['image']); ?>",
        size: "12345"
      },
    <?php } ?>
  ]

  for (let i = 0; i < images.length; i++) {

    let img = images[i];
    //console.log(img.url);

    // Create the mock file:
    var mockFile = {
      name: img.name,
      size: img.size,
      url: img.url
    };
    // Call the default addedfile event handler
    myDropzone.emit("addedfile", mockFile);
    // And optionally show the thumbnail of the file:
    myDropzone.emit("thumbnail", mockFile, img.url);
    // Make sure that there is no progress bar, etc...
    myDropzone.emit("complete", mockFile);
    // If you use the maxFiles option, make sure you adjust it to the
    // correct amount:
    var existingFileCount = 1; // The number of files already uploaded
    myDropzone.options.maxFiles = myDropzone.options.maxFiles - existingFileCount;

  }

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

  $(document).ready(function() {
    $('select[name=category]').val(<?php echo $product['category'] ?>).change();
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

<!--  FIM Dropzone 2 -->
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
            url: "<?php echo base_url('index.php/update_product') ?>",
            type: 'post',
            data: formData,
            contentType: false,
            processData: false
          })
          .done(function(msg) {
            location.href = "<?php echo base_url('index.php/all_products?order=DESC&orderBy=ID') ?>"
          })
          .fail(function(jqXHR, textStatus, msg) {
            console.log(msg)
          });
      } else {
        console.log(logError);
      }
    }

  }
  ajax();
  ajax2();
</script>