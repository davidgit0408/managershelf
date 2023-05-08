<?php 
$img = $img[0]; 
?>

<style>
    #canvas {
        background-image: url(<?php echo base_url($img['src']) ?>);
        background-size: 100%;
        width: 100%;
        height: 100%;
    }

    .dataTables_empty {
        text-align: center;
    }

    .hidden_div {
        display: none;
    }

    table {
        width: 100%;
        margin-bottom: 100px;
    }

    td {
        padding: 0px;
    }

    .popover-item {
        position: relative;
    }

    .popover-content {
        display: none;
        position: absolute;
        left: 140%;
        display: none;
        font-size: 1.2rem;
        background-color: rgba(250, 250, 250, 0.85);
        padding: 10px 20px;
        border-radius: 16px;
        font-family: Tahoma, Verdana, Segoe, sans-serif;
        font-weight: normal;
        z-index: 111;
        width: 300px;
    }

    .popover-content:hover {
        display: none !important;
    }

    .popover-item:hover .popover-content {
        display: block;
    }

    .popover-content h1 {
        font-size: 20px;
    }
</style>

<div class="row">
    <div class="container col-md-11">
        <div class="print ">
            <div class="nav_layout" style="display:none">
                <a class="mt-2 mb-2" style=" margin-right: 30px;">
                    <img src="<?php echo base_url('assets/img/brand/logo.png') ?>" class="img-fluid rounded-circle" alt="DKMA" style="width:180px;height:auto;">
                </a>
                <h4 class="info">Heatmap - <?php echo $cenario[0]['name'] ?></h4>
            </div>
            <div class="planograma" id="div_canvas" display="none">
                <canvas id="canvas" width="<?php echo $img['width'] ?>" height="<?php echo $img['height'] ?>"> </canvas>
                <div id="popup"></div>
            </div>
            <div class="card hidden_div" id="div_table">
                <div class="card-header row m-0 align-items-center" style="background-color: #fc9700;">
                    <div class="col-6">
                        <h3 class="mt-1 text-white">Tempo de fixação do produto</h3>
                    </div>
                    <div class="col-6 d-flex justify-content-end">
                        <button id="excel-tempo-de-fixacao" class="btn btn-success m-2" ><i class="far fa-file-excel"></i>  Exportar Planilha</button>  
                    </div>
                </div>
                <div class="card-body">
                    <div id="tempo-de-fixacao"></div>
                    <div class="row align-items-center col-12 mb-3" style="display:none">
                        <div class="col-12 col-md-8 col-sm-8 col-lg-8 col-xl-8 col-xl-8">
                            <h3 class="mb-0">Produtos</h3>
                        </div>
                        <div class="col-12 col-md-4 col-sm-4 col-lg-4 col-xl-4 col-xl-4 text-right p-0 mt-3">
                            <!-- <button id="excel" onclick="exporta(this)" class="btn btn-success m-2" ><i class="far fa-file-excel"></i>  Exportar Planilha</button>     -->
                            <form id="export" action="<?php echo base_url('index.php/admin/export_fixation_time') ?>" method="POST">
                                <input type="hidden" name="data" id="data" value="">
                                <a onclick="exporta()" class="btn btn-success text-white float-end" style="white-space:nowrap;"><i class="far fa-file-excel"></i> Exportar Planilha</a>
                            </form>
                        </div>
                    </div>

                    <table style="display:none" id="datatables-dashboard-projects" class="table table-striped my-0 ">
                        <thead>
                            <tr>
                                <th scope="col">Nome Do Produto</th>
                                <th scope="col">Prateleira</th>
                                <th scope="col">Posição</th>
                                <th scope="col">Tempo De Fixação</th>
                            </tr>
                        </thead>
                        <tbody id="time_table">
                        </tbody>
                    </table>
                </div>

            </div>

        </div>
    </div>
</div>


<script src="<?php echo base_url('assets/js/studio/simpleheat.js') ?>"></script>
<script src="<?php echo base_url('assets/js/html2canvas.js') ?>"></script>
<script type="text/javascript" src="<?php echo base_url("assets/js/simditor/simditor.js") ?>"></script>
<script src="../assets/js/pages/admin/report/tempoDeFixacao.js"></script>
<script>
    $('.card-group .dropdown-toggle').click(function() {
        $('.arrow').css("display", "none");
        $('.alert_options').css("display", "none");
    });

    $('.card-group #download').click(function() {
        window.scrollTo(0, 0);
        $('body').toggleClass('disablescroll');
        setTimeout(function() {
            $('.nav_layout').css('display', '');
            $(".print").toggleClass('print_layout');
            let gondola = document.querySelector(".print");
            html2canvas(gondola).then(canvas => {
                let cenario = "<?php echo 'heatmap_' . $cenario[0]['name']; ?>";
                cenario = cenario.replace(/[ÀÁÂÃÄÅ]/g, "A");
                cenario = cenario.replace(/[àáâãäå]/g, "a");
                cenario = cenario.replace(/[ÈÉÊË]/g, "E");
                cenario = cenario.replace(/[ç]/gi, 'c');
                cenario = cenario.replace(/[^a-z0-9]/gi, '_');

                var a = document.createElement('a');
                document.getElementById("wrapper").appendChild(a);
                a.href = canvas.toDataURL("image/jpeg").replace("image/jpeg", "image/octet-stream");
                a.download = cenario + '.png';
                a.click();
            });
            $(".print").toggleClass('print_layout');
            $('.nav_layout').css('display', 'none');
            $('body').toggleClass('disablescroll');
        }, 500);
    });

    ///////////////////////////////////////////
    /////Creating eye tracking result view/////
    ///////////////////////////////////////////
    let table_mode = true;
    let results = <?php echo $results ?>;
    let positions = <?php echo $results  ?>;


    let htmlTempoFixacao = new TempoDeFixacao().montaGondola(positions);
    console.log(htmlTempoFixacao);
    $("#tempo-de-fixacao").append(htmlTempoFixacao);

    $("#excel-tempo-de-fixacao").on("click", () => {
        new TempoDeFixacao().generateCSV(positions);
    });

    console.error(positions.length)
    console.error(positions)
    let data = [];
    let timeTemp = 0;
    let tableData = [];
    let productInfo = [];
    let shelves = [];
    let index = 0;
    let html = "";
    let ctx;
    let shelfProduct = [];
    let shelf = 1;
    let width = 0;
    shelves.push(0);
    shelfProduct.push(2);
    let height = 0;

    ///////////////creating table///////////////////
    for (let index = 0; index < positions.length; index++) {
        var rowData = [];
        rowData.push(positions[index].name);
        rowData.push(positions[index].shelf);
        rowData.push(positions[index].position);
        rowData.push(positions[index].time);
        html += "<tr>"
        html += "<td>"
        html += positions[index].name
        html += "</td>"
        html += "<td>"
        html += positions[index].shelf
        html += "</td>"
        html += "<td>"
        html += positions[index].position
        html += "</td>"
        html += "<td>"
        html += positions[index].time
        html += "</td>"
        html += "</tr>"
        index++;
        tableData.push(rowData);
    }

    ////////////creating heatmap data///////////////////
    for (let i = 0; i <= results.length - 1; i++) {
        data.splice(0, 0, [(results[i]['docX']), (results[i]['docY'] - 320) * 2, 3]);
    }

    window.requestAnimationFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame || window.msRequestAnimationFrame;

    function get(id) {
        return document.getElementById(id);
    }

    var heat = simpleheat('canvas').data(data).max(18),
        frame;

    function draw() {
        ctx = heat.draw();
        ctx = ctx._ctx;
        frame = null;
    }

    /////////////////draw heatmap///////////////////
    draw();

    var radius = get('radius'),
        blur = get('blur'),
        changeType = 'oninput' in radius ? 'oninput' : 'onchange';

    radius[changeType] = blur[changeType] = function(e) {
        heat.radius(+radius.value, +blur.value);
        frame = frame || window.requestAnimationFrame(draw);
    };
    ////////////////////////////////////export excel/////////////////////////////////
    function exporta() {
        console.log(data);
        console.log(tableData);
        data = JSON.stringify(tableData);
        $('#data').val(data);
        console.log($('#data').val());
        $('#export').submit();
    }
    $('#time_table').html(html);
    // $(function() {
    //     $('#datatables-dashboard-projects').DataTable({});
    // });

    var canvas = document.getElementById('canvas');

    /////////////toggle view mode//////////////
    $('#toggle_view').click(() => {
        toggle_mode(table_mode);
        table_mode ? table_mode = false : table_mode = true;
    });

    const toggle_mode = (table_mode) => {
        if (table_mode) {
            $('#toggle_view').text("Visualizar Heatmap")
            $('#div_canvas').hide();
            $('#div_table').fadeIn();
        } else {
            $('#toggle_view').text("Visualizar Tempo de Fixação")
            $('#div_table').hide();
            $('#div_canvas').fadeIn();
        }
    }
</script>
