<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = \Config\Services::session();
?>
<!DOCTYPE html>
<style>
    a {
        text-decoration: none;
    }

    .text-info {
        color: #fc9700 !important;
    }

    .physicianList ul {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .physicianList ul li {
        display: inline-block;
        width: 205px;
        float: left;
        margin-right: 15px;
        margin-left: 15px;
        margin-bottom: 15px;
    }

    .physicianBox {
        cursor: pointer;
        box-shadow: 0px 0px 20px 1px #00000036;
        border-radius: 5px;
        border: 1px solid #ddd;
        color: #333;
        margin-bottom: 20px;
        position: relative;
        background-color: #f5f5f5;

    }

    .physicianBox .physicianPic {
        padding: 15px 0;
        background-color: #fff;
        text-align: center;
    }

    .physicianBox .physicianPic img {
        border: 6px solid #f8f8f8;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        transition: all 0.3s ease 0s;
        margin: 0 auto;
        height: 120px;
        width: 120px;
    }

    .physicianInfo {
        padding: 10px;
        text-align: center;
        border-top: 1px solid #eee;
    }

    .physicianInfo h6 {

        font-size: 16px;
        margin: 0;
    }

    .physicianBox strong {
        color: #444;
    }

    .physicianBox p {
        font-size: 13px;
        margin: 0;
        line-height: 22px;
    }

    .physicianBio {
        position: absolute;
        right: 0;
        bottom: 3px;
    }

    .physicianModal {
        text-align: center;
    }

    .physicianModal .physicianPic img {
        border: 6px solid #f8f8f8;
        -webkit-border-radius: 50%;
        border-radius: 50%;
        transition: all 0.3s ease 0s;
        margin: 0 auto;
        height: 180px;
        width: 180px;
    }

    .physicianModal .info p,
    .physicianModal .info .text-info {
        margin: 0;
    }

    .showBioBtn {
        background-color: #337ab7;
        padding: 5px 10px;
        color: #fff;
        font-size: 12px;
    }

    /*Pagination CSS*/
    #page_navigation {
        clear: both;
        margin: 20px 0;
        display: flex;
        justify-content: end;
    }

    #page_navigation a {
        padding: 3px 6px;
        border: 1px solid #f89e24;
        border-radius: 5px;
        margin: 2px;
        color: black;
        text-decoration: none
    }

    .active_page {
        background: #f89e24;
        color: white !important;
    }

    .next_link,
    .previous_link {
        color: white !important;
        background-color: #f89e24;
    }

    #bodyContent {
        overflow-x: hidden;
    }

    #bodyContent::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px #ffffff;
        border-radius: 10px;
        background-color: #ffffff;
    }

    #bodyContent::-webkit-scrollbar {
        width: 5px;
        background-color: #ffffff;
    }

    #bodyContent::-webkit-scrollbar-thumb {
        border-radius: 10px;
        -webkit-box-shadow: inset 0 0 6px rgba(204, 0, 0, 0.3);
        background-color: #f89e24;
    }
</style>
<div class="row">
    <div class="col-md-12 container mt-3">
        <div class="card">
            <div class="card-header border-0" style="background-color: #fc9700;">
                <div class="row align-items-center col-12 ">
                    <div class="col-8" style="background-color: #fc9700;">
                        <h3 class="mb-0 text-white">Formulario de pesquisa</h3>
                    </div>
                    <div class="col-4 text-right p-0 mt-1"></div>
                </div>
            </div>
            <div class="card-body">
                <h6 class="mt-2 card-subtitle text-muted ">Informações do Estudo</h6>
                <hr class="mt-2">
                <div class="physicianList">
                    <input type='hidden' id='current_page' />
                    <input type='hidden' id='show_per_page' />
                    <ul id="pagingBox">
                        <?php for ($i = 0; $i < count($result_answers); $i++) { ?>

                            <li>
                                <div class="physicianBox">
                                    <div class="physicianInfo" onclick="openModal(<?php echo $result_answers[$i]['id'] ?>)">
                                        <div class="info">
                                            <div class="card text-center">
                                                <div class="card-header">
                                                    Pesquisa - Bebidas
                                                </div>
                                                <div class="card-body">
                                                    <h4 style="color: #fc9700; font-size: 20px;" class="card-title">Informações</h4>
                                                    <p class="card-text"><?php echo $result_answers[$i]['name'] ?></p>
                                                    <p class="card-text"><?php echo $result_answers[$i]['email'] ?></p>
                                                </div>
                                            </div>
                                            <div class="card-footer text-muted" style="background-color: #f89e24;border-radius: 5px;">
                                                <p class="card-text" style="color: #ffffff; font-size: 15px;"><?php echo $result_answers[$i]['data'] ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
                <div id='page_navigation'></div>
            </div>
            <button type="button" id="clickOpenModal" class="btn btn-primary" data-toggle="modal" data-target="#ModalRespostas" hidden></button>
            <div class="modal fade" id="ModalRespostas" tabindex="-1" role="dialog" aria-labelledby="ModalRespostasTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header" style="background-color: #ff9100;">
                            <h3 class="modal-title" id="ModalRespostasTitle" style="color: white;">Relatório</h3>
                            <div type="button" class="close" data-dismiss="modal" aria-label="Close" style="color: red;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                                </svg>
                            </div>
                        </div>
                        <div class="modal-body" id="bodyContent" style="position: relative;flex: 1 1 auto;max-height: calc(90vh - 40px);padding: 1rem;overflow-y: auto;">
                            <div id="body_answers">

                            </div>
                            <div class="modal-footer">
                                <button type="button" style="background-color: #f89e24;border-color: #f89e24;" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            var show_per_page = 12;
            var number_of_items = $('#pagingBox').children().length;
            var number_of_pages = Math.ceil(number_of_items / show_per_page);
            $('#current_page').val(0);
            $('#show_per_page').val(show_per_page);
            var navigation_html = '<a class="previous_link" href="javascript:previous();">Prev</a>';
            var current_link = 0;
            while (number_of_pages > current_link) {
                navigation_html += '<a class="page_link" href="javascript:go_to_page(' + current_link + ')" longdesc="' + current_link + '">' + (current_link + 1) + '</a>';
                current_link++;
            }
            navigation_html += '<a class="next_link" href="javascript:next();">Next</a>';

            $('#page_navigation').html(navigation_html);
            $('#page_navigation .page_link:first').addClass('active_page');
            $('#pagingBox').children().css('display', 'none');
            $('#pagingBox').children().slice(0, show_per_page).css('display', 'block');

        });


        function previous() {
            new_page = parseInt($('#current_page').val()) - 1;
            if ($('.active_page').prev('.page_link').length == true) {
                go_to_page(new_page);
            }
        }

        function next() {
            new_page = parseInt($('#current_page').val()) + 1;
            if ($('.active_page').next('.page_link').length == true) {
                go_to_page(new_page);
            }

        }

        function go_to_page(page_num) {
            var show_per_page = parseInt($('#show_per_page').val());
            start_from = page_num * show_per_page;
            end_on = start_from + show_per_page;
            $('#pagingBox').children().css('display', 'none').slice(start_from, end_on).css('display', 'block');
            $('.page_link[longdesc=' + page_num + ']').addClass('active_page').siblings('.active_page').removeClass('active_page');
            $('#current_page').val(page_num);
        }

        function openModal(id_user) {
            $('#body_answers').html(" ");
            $.ajax({
                "url": "<?php echo base_url('/index.php/get_products_form_by_ean') ?>",
                "method": "POST",
                "headers": {
                    "Content-Type": "application/json"
                },
                "data": JSON.stringify({
                    "userId": id_user
                }),
            }).done(function(response) {
                var products = JSON.parse(response);

                for (let i = 0; i < products.length; i++) {
                    $(document).ready(function() {
                        $('#body_answers')
                            .append(`<div class="col-md-12">
                                <div class="card text-center"" style=" width: 100%;">
                                    <div class="card-body" style="box-shadow: 0px 0px 10px 0px #0000000d;border-radius: 10px;">
                                        <h5 class="card-title">Pergunta ${i + 1}</h5>
                                        <h6 class="card-subtitle mb-2 text-muted">Texto da pergunta que sera escrita?</h6>
                                        <div style="display: flex;justify-content: center;">
                                            <div class="card text-center" style=" width: 50%;">
                                                <div class="card-body" style="box-shadow: 0px 0px 10px 0px #0000001a;border-radius: 5px;">
                                                    <h5 class="card-title">${products[i].name}</h5>
                                                    <img src="<?php echo base_url() ?>/${products[i].image}" onerror="this.src='data:image/png;base64,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'" alt="">
                                                    <p class="card-text">R$${products[i].price}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>`);
                    });
                }
                document.getElementById('clickOpenModal').click();

            });


        }
    </script>
