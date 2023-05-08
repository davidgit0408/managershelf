<?php
error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>

<script defer src="<?php echo base_url('assets/theme/bootstrap5/docs/js/jquery.lazy.js') ?>"></script>
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/mystyle.css') ?>" rel="stylesheet" />

<style>
    .imagens {
        overflow-y: scroll;
        max-height: 650px;
        border: 1px solid #dadada;
        justify-content: center;
    }

    .noimages {
        width: 100%;
        height: 110px;
        background-color: #fafafa;
    }
    .imgProd{
        max-height: 100%;
        max-width: 140px;
        width: auto;
        height: auto;
           
    }

    .imgori {
    justify-content: center;
    display: flex;
    height: 150px;
    max-width: 200px;
    margin-bottom: 20px;
}

.imgori .child {
    display: flex;
    justify-content: flex-start;
    align-items: flex-end;
}
    img.lazy {
        height: 150px;
        display: inline-block;
    }

    #show_img {
        width: auto;
        height: 160px;
        margin: 0 auto;
    }

    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .upload {
        background-color: #fc9700;
        border: 1px solid #fff;
        color: #fff;
        border-top-left-radius: 0px !important;
        border-top-right-radius: 0px !important;
        z-index: 2;
    }

    .upload.active {
        background-color: white;
        color: #fc9700;
        font-weight: 600;
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: #fc9700;
        border-color: #fff;
        border-top-left-radius: 0px !important;
        border-top-right-radius: 0px !important;
        z-index: 2;

    }

    .nav-item {
        border-radius: 0px;
    }

    .gallery-tab {
        background-color: #fc9700;
        border: none;
        color: #fff;
        padding-left: calc(5vw/2);
    }

    .gallery-animate {
        position: relative;
        right: calc(-100%/2);
        transition: right 0.75s;
        border-bottom: solid 1px #fff;
    }

    .upload-animate {
        z-index: 5;
    }

    .tab-perso {
        padding: 10px
    }

    .hide{
        display: none!important;
    }

    button:focus {
        outline: none !important;
    }

    .view-gallery {
        right: calc(0%/2);
    }

    .empty {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100%;
        width: 100%;
        color: #bbbbbb;
    }

    @media only screen and (max-width: 500px) {
        .gallery-animate {
            border: solid 1px #fff;
        }

        .gallery-animate {
            display: none;
        }

        .gallery-animate.view-gallery {
            display: block;
        }

        .upload-animate.responsive {
            display: none;
        }
    }

    #confirm-delete {
        display: none;
    }
</style>
<script>
    function show() {
        $('.gallery-animate').addClass('view-gallery');
        $('.gallery-animate').addClass('view-gallery');
        $('.upload-animate').addClass('teste');
    }

    function hide() {
        $('.gallery-animate').removeClass('view-gallery');
        $('.upload-animate').removeClass('responsive');
    }
</script>
<div class="row">
    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="card-header" style="background-color: #fc9700;">
                <h3 class="mt-1 text-white">Biblioteca de Imagens

                    <?php
                    if (isset($teste)) {
                        var_dump($teste);
                        die;
                    }
                    ?>
                </h3>
                <ul class="nav " id="myTab" role="tablist">

                    <li class="nav-item gallery-animate" role="presentation">
                        <button class="tab-perso gallery-tab active" id="profile-tab" onclick="hide()" data-bs-toggle="tab" data-bs-target="#gallery" type="button" role="tab" aria-controls="gallery" aria-selected="false">Galeria</button>
                    </li>
                    <li class="nav-item upload-animate" role="presentation">
                        <button class="tab-perso upload" id="home-tab" onclick="show()" data-bs-toggle="tab" data-bs-target="#home" type="button" role="tab" aria-controls="home" aria-selected="true">Enviar imagens</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="pl-lg-4">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-group">
                                <div class="tab-content" id="myTabContent">

                                    <div class="tab-pane fade show active" id="gallery" role="tabpanel" aria-labelledby="profile-tab">
                                        <div class="gallery" id="here">
                                            <div class="imagens row"  id="galeria_popup">


                                            </div>

                                            <input type="hidden" id="row" value="0">
                                            <input type="hidden" id="all" value="<?php echo $allCount; ?>">

                                        </div>
                                    </div>
                                    <div class="tab-pane fade " id="home" role="tabpanel" aria-labelledby="home-tab">
                                        <div class="upload-div">
                                            <form action="<?php echo base_url('index.php/dragDropUpload'); ?>" id="dropzone" class="dropzone"></form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="modal_image" tabindex="-1" role="dialog" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Visualizar Imagem</h5>
                            <div>
                                <button class="btn mb-1 btn-light" onclick="prev()" id="prev"><i class="fas fa-angle-left"></i></button>
                                <button class="btn mb-1 btn-light" onclick="next()" id="next"><i class="fas fa-angle-right"></i></button>
                                <button class="btn mb-1 btn-danger close" data-bs-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="modal-body m-3">
                            <div class="row" id="modal_contain">
                                <img id="show_img">
                                <h4>URL:</h4>
                                <p id="url" style="word-break: break-all;"></p>
                                <input id="base_url" type="hidden" value="<?php echo base_url(); ?>">
                            </div>
                            <div class="col" id="delete">
                                <div id="confirm-delete">
                                    <p>Tem certeza? Essa ação é irreversível.</p>
                                    <button id="btn-delete-confirm" onclick="deleteImage(this)" data-src="" class="btn btn-danger w-auto">Tenho certeza.</button>
                                </div>
                                <button id="btn-delete" onclick="deleteImageConfirm(this)" class="btn btn-danger w-auto">Excluir permanentemente</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <?php $contador = 0; ?>
        <?php $max = 0; ?>

        <!-- //////////////////////////
            SCRIPT PARAA GALERIA MODAL
        /////////////////////////// -->
        <script>
            var contador = 1;
            var max = Number(<?php echo $max ?>)
            $(function() {
                $('.lazy').Lazy({
                    appendScroll: $(".imagens"),
                    scrollDirection: 'vertical',
                    effect: 'fadeIn',
                    effectTime: 500,
                    visibleOnly: true
                });
            });

            function image(img) {
                contador = $(img).attr('data_contador');
                let dataSrc = $(img).attr('data-src');

                var src = img.src;

                if (contador == 1) $('#prev').addClass('disabled');
                if (contador == max) $('#next').addClass('disabled');

                $('#btn-delete-confirm').attr('data-src', dataSrc);
                $('#show_img').attr('src', src);
                $('#url').text(src);
            }

            function next() {
                contador++;
                $('#prev').removeClass('disabled');
                if (contador == max) {
                    $('#next').addClass('disabled');
                    get_image();
                } else if (contador <= max) {
                    $('#next').removeClass('disabled');
                    get_image();
                } else {
                    $('#next').removeClass('disabled');
                }
            }

            function prev() {
                contador--;
                $('#next').removeClass('disabled');
                if (contador == 1) {
                    $('#prev').addClass('disabled');
                    get_image();
                } else if (contador > 1) {
                    $('#prev').removeClass('disabled');
                    get_image();
                } else {
                    $('#prev').addClass('disabled');
                }
            }

            function get_image() {
                let defaultImage = 'data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw=='; //defaultImage do lazy();
                let el = $('img[data_contador="' + contador + '"]');
                let src = el.attr('src');
                //Se atingir a ultima imagem carregada, o modal da galeria se limita as imagens que já estão carregadas
                if (src != defaultImage) {
                    image(el[0])
                } else {
                    //reseta o contador, para se limitar as imagens disponiveis
                    contador = 0;
                }
            }

            function deleteImageConfirm(el) {
                $("#btn-delete").hide();
                $("#confirm-delete").show();
            }

            function deleteImage(el) {

                let url = $(el).attr('data-src');

                $.ajax({
                    type: "post",
                    url: "<?php echo base_url(); ?>/index.php/deleteImage",
                    data: {
                        srcImg: url
                    },
                    success: function(response) {

                        $(".imgori").remove();
                        var dflt = $("<div />", {
                    class: 'imgori hide',
                });

                $("#galeria_popup").append(dflt);



                var dflta = $("<div />", {
                    class: 'imgProd',
                    data_contador: '0'
                });

                $(".imgori").append(dflta);

                       
                        $("img[data-src='" + url + "']").remove();
                        $("#btn-delete").show();
                        $("#confirm-delete").hide();
                        $("#modal_image .close").click()
                        loadMore()
                    }
                });
            }
        </script>
        <!-- //////////////////////////
            SCRIPT PARAA GALERIA MODAL
        /////////////////////////// -->




        <!-- //////////////////////////
            DROPZONE
        /////////////////////////// -->
        <script src="<?php echo base_url('/assets/theme/bootstrap5/docs/js/dropzone.js') ?>"></script>
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

            myDropzone.on("error", function(file, response) {
                console.log(response);
            });

            myDropzone.on("complete", function() {

                $('.gallery-animate').removeClass('view-gallery');
                $('#home-tab').removeClass('active');
                console.log('complete')
                $(".imgori").remove();
                var dflt = $("<div />", {
                    class: 'imgori hide',
                });

                $("#galeria_popup").append(dflt);

                var dflta = $("<div />", {
                    class: 'imgProd',
                    data_contador: '0'
                });

                $(".imgori").append(dflta);

                loadMore()
                tabUpload = document.getElementById("home");
                tabUpload.classList.remove("active", "show");
                tabGallery = document.getElementById('gallery')
                tabGallery.classList.add("active", "show");


            });

            Dropzone.options.dragDropUpload = { // camelized version of the `id`
                paramName: "file", // The name that will be used to transfer the file
                maxFilesize: 8, // MB
                accept: function(file, done) {
                    if (file.name == "justinbieber.jpg") {
                        done("Naha, you don't.");
                    } else {
                        done();
                    }
                },
                init: function() {
                    this.on("complete", file => {
                        console.log(file);
                    });
                }
            };
        </script>

        <script>
            function loadMore() {



                var qtd = document.querySelectorAll('.imgProd')
                var row = qtd.length


                var allcount = Number($('#all').val());
                var rowperpage = 30;


                var cont = $('.imgProd:last-child').attr("data_contador")
                if (row <= allcount) {
                    $("#row").val(row);

                    $.ajax({
                        url: '<?php echo base_url('index.php/loading_gallery') ?>',
                        type: 'post',
                        data: {
                            row: row,
                            cont: cont,
                            max: max
                        },
                        beforeSend: function() {
                            $(".load-more").text("Loading...");
                        },
                        success: function(response) {

                            var resposta = JSON.parse(response)
                            max = resposta.max
                            setTimeout(function() {
                                $(".imgori:last").after(resposta.html).show().fadeIn("slow");

                            }, 300);
                        }
                    });
                } else {
                    console.warn('Todas as imagens carregadas...')
                }
                $(".lazy").each(function() {
                    var $this = $(this);
                    $(document).ready(function() {
                        $this.attr("src"),
                            function() {
                                $this.attr("src", $this.attr("data-src"));
                            }
                    })
                });
            }

            $(document).ready(function() {
                if ($('#galeria_popup').scrollHeight < 1) {
                    loadMore()
                }
                $('#galeria_popup').bind('scroll', function() {
                    if ($(this).scrollTop() + $(this).innerHeight() >= this.scrollHeight) {
                        loadMore()
                    }
                });
                var dflt = $("<div />", {
                    class: 'imgori hide',
                });

                $("#galeria_popup").append(dflt);



                var dflta = $("<div />", {
                    class: 'imgProd',
                    data_contador: '0'
                });

                $(".imgori").append(dflta);

                loadMore()
              
                    $('.imgProd').attr("src", $('.imgProd').attr("data-preview"));
    
            });

          
        </script>