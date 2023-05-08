<?php
    error_reporting(E_ALL & ~E_NOTICE);
    $version = isset($version[0]) ? $version[0] : 1;
    // print_r($companies);die();
    $session = session();
    $page_count = 1;
?>
<!DOCTYPE html>
<link rel="stylesheet" href="<?php echo base_url('assets/css/calendar/vanilla-calendar.css') ?>">
<link href="<?php echo base_url('/assets/theme/bootstrap5/docs/css/dashboard.css') ?>" rel="stylesheet" />
<style>
    /* .dropdown{ position: static; } */
    .list{ 
        padding: .5rem 1.25rem;
        display: flex; 
        align-items: center;
        justify-content: space-between;
    }
    .list:not([data-page='1']){
        display: none;
    }
</style>

<!--------------- Modal de versões --------------->
<button type="button" id="button_modal"  style="display:none;" class="btn btn-primary" data-toggle="modal" data-target="#version_modal">modal</button>
<div class="modal fade" data-backdrop="static" id="version_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Versão nova!!!</h4>
                <button title="Fechar" id="close" class="btn mb-1 btn-danger" data-dismiss="modal" aria-label="Close"><i class="fas fa-times"></i></button>
            </div>
            <!-- <div class="modal-body" >< ?php echo $version['note'] ?></div>
            <div class="modal-footer"><h5 class="modal-title">< ?php echo $version['number'] ?></h5></div> -->
        </div>
    </div>
</div>

<div class="row">
    <div class="col-xl-6 col-xxl-6"> 
	    <div class="card">
			<div class="card-header p-4">
				<h5 class="card-title mb-0 text-uppercase">Calendário</h5>
			</div>
			<div class="card-body d-flex">
				<div class="align-self-center w-100">
                    <div id="myCalendar" class="vanilla-calendar"></div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xl-6 col-xxl-6 d-flex">
		<div class="w-100">
            <div class="card flex-fill">
                <div class="card-header p-4" style="background-color:#f89e24">
                    <h5 class="card-title mb-0 text-uppercase text-white" style="font-size:13pt;">Estudos Ativos</h5>
                </div>
                <div style="height:170px; overflow: hidden">
                    <?php if(is_array($companies)){ if(count($companies) > 0){ 
                        $next_page = false;
                        for($i = 0; $i < count($companies); $i++){ 
                            $dt_end= str_replace("/", "-",$companies[$i]['dt_end']); $dt_end= date('m/d/Y', strtotime($dt_end));
                            $dt_begin= str_replace("/", "-",$companies[$i]['dt_begin']); $dt_begin= date('m/d/Y', strtotime($dt_begin)); 

                            if($next_page){
                                $page_count += 1;
                                $next_page = false;
                            }

                            $x = ($i + 1)/3;
                            if(is_int($x)){
                                $next_page = true;
                            }
                    ?>
                            <div class="list" data-page="<?php echo $page_count?>" data-bs-toggle="list" onclick="company(this)" data-id="<?php echo $companies[$i]['id']?>" data-begin="<?php echo $dt_begin?>" data-end="<?php echo $dt_end?>" role="tab">
                                <span><?php echo $companies[$i]['name'] ?></span>
                                <div class="card-actions">
                                    <div class="d-inline-block dropdown show">
                                        <a href="#" data-bs-toggle="dropdown" data-bs-display="static">
                                            <i class="align-middle" data-feather="more-vertical"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <a class="dropdown-item" onclick="edit(this)" data-id="<?php echo $companies[$i]['id']?>">Editar estudo</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php }}else{ ?>
                        <a class="list" data-bs-toggle="list"  href="#" role="tab">Você não possui estudos ativos no momento.</a>
                    <?php }} ?>
                    <nav class="d-flex justify-content-center p-2" aria-label="...">
                        <ul class="pagination">
                            <li class="page-item disabled prev-btn">
                                <a class="page-link" tabindex="-1" aria-disabled="true">Anterior</a>
                            </li>
                            <?php 
                            for($i = 1; $i <= $page_count; $i++){ 
                                if($i > 3){?>
                                <li class="page-item page-number" style="display: none">
                                    <a data-count="<?php echo $i?>" class="page-link"><?php echo $i?></a>
                                </li>
                            <?php
                                }else{?>
                                <li class="page-item page-number">
                                    <a data-count="<?php echo $i?>" class="page-link"><?php echo $i?></a>
                                </li>
                            <?php 
                                }
                            }?>
                            <li class="page-item next-btn">
                                <a class="page-link">Próximo</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
			<div class="row">
                <div class="col-sm-6">
                    <div class="card" >
						<div class="card-body p-4">
							<div class="row">
								<div class="col mt-0">
									<h5 class="card-title text-uppercase">Total de Pesquisas realizadas</h5>
								</div>
							</div>
							<h1 class="display-5 mt-0 mb-3" id="pesquisas" style="font-size:4.5rem; font-family:'helvetica'; font-weight:550;">0</h1>
							<div class="mb-0">
                            <i class="mdi mdi-arrow-bottom-right"></i>
								<span class="text-orange" id="amostra" style="font-size:1.2rem; font-family:'helvetica'; font-weight:500;">0%</span> da amostra realizada
							</div>
						</div>
					</div>
                </div>
                <div class="col-sm-6">
                    <div class="card" >
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col mt-0">
                                    <h5 class="card-title text-uppercase">Pesquisas realizadas hoje</h5>
                                </div>
                            </div>                            
                            <h1 class="display-5 mt-0 mb-3" id="hoje"  style="font-size:4.5rem; font-family:'helvetica'; font-weight:550;">0</h1>
                            <div class="mb-0">
                                <span class="text-orange" id="entrevista" style="font-size:1.2rem; font-family:'helvetica'; font-weight:500;">0</span> entrevistas faltantes
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
	</div>

	<div class="col-xl-12 col-xxl-12 mb-4">
        <div class="pt-5 pb-3 service-18" style="background: #cfcfcf;">
            <div class="container">
                <div class="row wrap-service-18">
                    <div class="col-lg-5">
                        <a id="title" href="all_planograms">
                            <div class="card border-0 mb-4">
                                <div class="row no-gutters">
                                    <div class="col-md-7 col-sm-8 col-8">
                                        <div class="col-md-5 icon-position"><div class="icon-round display-5"><img src="<?php echo base_url('assets/theme/bootstrap5/img/icons/seta.png') ?>" ></div></div>
                                        <div class="card-body ml-0 ml-md-3"><h6 class="display-5 text-uppercase">Planogramas</h6></div>
                                    </div>
                                    <div class="col-md-5 col-sm-4 col-4 icon-position" style="background-image:url(<?php echo base_url('assets/theme/bootstrap5/img/icons/planogramas.png') ?> );  background-size: 55px 55px;"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <a id="title" href="all_orders">
                            <div class="card border-0 mb-4">
                                <div class="row no-gutters">
                                    <div class="col-md-7 col-sm-8 col-8">
                                        <div class="col-md-5 icon-position"><div class="icon-round display-5"><img src="<?php echo base_url('assets/theme/bootstrap5/img/icons/seta.png') ?>" ></div></div>
                                        <div class="card-body ml-0 ml-md-3"><h6 class="display-5 text-uppercase">Relatórios</h6></div>
                                    </div>
                                    <div class="col-md-5 col-sm-4 col-4 icon-position" style="background-image:url(<?php echo base_url('assets/theme/bootstrap5/img/icons/relatorios.png') ?> ); background-size: 50px 55px; "></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-5">
                        <a id="title" href="all_products">
                            <div class="card border-0 mb-4">
                                <div class="row no-gutters">
                                    <div class="col-md-7 col-sm-8 col-8">
                                        <div class="col-md-5 icon-position"><div class="icon-round display-5"><img src="<?php echo base_url('assets/theme/bootstrap5/img/icons/seta.png') ?>" ></div></div>
                                        <div class="card-body ml-0 ml-md-3"><h6 class="display-5 text-uppercase">Produtos</h6></div>
                                    </div>
                                    <div class="col-md-5 col-sm-4 col-4 icon-position" style="background-image:url(<?php echo base_url('assets/theme/bootstrap5/img/icons/produtos.png') ?> ); background-size: 55px 55px;"></div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-5">
                    <a id="title" href="all_company">
                            <div class="card border-0 mb-4">
                                <div class="row no-gutters">
                                    <div class="col-md-7 col-sm-8 col-8">
                                        <div class="col-md-5 icon-position"><div class="icon-round display-5"><img src="<?php echo base_url('assets/theme/bootstrap5/img/icons/seta.png') ?>" ></div></div>
                                        <div class="card-body ml-0 ml-md-3"><h6 class="display-5 text-uppercase">Estudos</h6></div>
                                    </div>
                                    <div class="col-md-5 col-sm-4 col-4 icon-position" style="background-image:url(<?php echo base_url('assets/theme/bootstrap5/img/icons/study.png') ?> ); background-size: 50px 55px; "></div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-12 col-xxl-12" style="z-index:0">
        <h1 class="text-uppercase text-center" style="color: #000;">Últimas Notícias</h1>
        <hr>
        <div class="col w-100 h-100">
            <div id="owl-demo" class="owl-carousel owl-theme">
                <?php if(is_array($notices)){ foreach($notices as $notice){if($notice['notices_img']){ ?>
                    <div class="item">  
                        <div class="card" style="height:500px;"> 
                            <div class="card-body p-4">
                                <h1 class="text-uppercase mb-4"><?php echo mb_strimwidth($notice['post_title'], 0, 50, "...")?></h1>
                                <div id="imagem"><img src="<?php echo $notice['notices_img'][0]['guid'] ?>" class="mt-3 card-img-top" alt="..."/></div>
                               
                                <div class="text" style="text-align:justify;">
                                    <h5 class="mt-4 card-text"><i class="far fa-calendar-alt text-orange"></i> <?php echo date('d/m/Y', strtotime($notice['post_date'])) ?></h5>
                                    <p class="mt-4 card-text"><?php echo mb_strimwidth($notice['post_content'], 0, 190, "...")?></p>
                                </div>
                                <div class="readmore mt-3">
                                    <a style="text-decoration:none;" href="<?php echo $notice['guid']?>"><h4 class="text-center text-white" style="font-weight:400">Ler Matéria Completa</h4></a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php }}} ?>   
            </div>   
        </div> 
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="<?php echo base_url('assets/theme/owlcarousel/dist/jquery-3.5.1.js') ?>"></script>
<script defer src="<?php echo base_url('assets/theme/owlcarousel/dist/owl.carousel.js') ?>"></script>
<script defer src="<?php echo base_url('assets/theme/owlcarousel/dist/owl.carousel.min.js')?>"></script>
<script src="<?php echo base_url('assets/js/calendar/vanilla-calendar.js') ?>"></script>

<script>
$(document).ready(function(){
    const pageNumbers = Array.from(document.querySelectorAll(".page-number"))
    pageNumbers[0].classList.add("active")
    const prevBtn = document.getElementsByClassName('prev-btn')[0]
    const nextBtn = document.getElementsByClassName('next-btn')[0]

    function limitAmountNumbers(){
        let amount = pageNumbers.length
        let current = document.querySelector('.page-number.active')
        let count = current.querySelector('a').dataset.count

        if(amount > 3)Array.from(document.querySelectorAll('.page-number')).forEach(element => {
            element.style.display = 'none'
        })

        if(count == 1){
            let next1 = current.nextElementSibling
            let next2 = next1.nextElementSibling
            current.style.display = 'block'
            next1.style.display = 'block'
            next2.style.display = 'block'
        }else if(count == amount){
            let previous1 = current.previousElementSibling
            let previous2 = previous1.previousElementSibling
            current.style.display = 'block'
            previous1.style.display = 'block'
            previous2.style.display = 'block'
        }else{
            current.style.display = 'block'
            current.nextElementSibling.style.display = 'block'
            current.previousElementSibling.style.display = 'block'
        }
    }

    pageNumbers.forEach(number => {
        number.addEventListener('click', event => {
            let selected = event.target
            document.querySelector('.page-number.active').classList.remove('active')
            selected.parentElement.classList.add('active')

            Array.from(document.querySelectorAll('.list')).forEach(element => {
                if(element.style.display = 'flex')
                    element.style.display = 'none'
            })

            let page = selected.dataset.count
            Array.from(document.querySelectorAll(`[data-page='${page}']`)).forEach(element => {
                element.style.display = 'flex'
            })

            if(page == 1)
                prevBtn.classList.add('disabled')
            else
                prevBtn.classList.remove('disabled')
            if(page == pageNumbers.length)
                nextBtn.classList.add('disabled')
            else
                nextBtn.classList.remove('disabled')

            limitAmountNumbers()
        })
    })

    prevBtn.addEventListener('click', e => {
        if(!prevBtn.classList.contains('disabled')){
            let current = document.querySelector('.page-number.active')
            let prev = document.querySelector('.page-number.active').previousElementSibling
            
            current.classList.remove('active')
            prev.classList.add('active')

            Array.from(document.querySelectorAll('.list')).forEach(element => {
                if(element.style.display = 'flex')
                    element.style.display = 'none'
            })

            let page = prev.querySelector('a').dataset.count
            Array.from(document.querySelectorAll(`[data-page='${page}']`)).forEach(element => {
                element.style.display = 'flex'
            })

            if(page == 1)
                prevBtn.classList.add('disabled')
            else
                prevBtn.classList.remove('disabled')
            if(page == pageNumbers.length)
                nextBtn.classList.add('disabled')
            else
                nextBtn.classList.remove('disabled')

            limitAmountNumbers()
        }
    })

    nextBtn.addEventListener('click', e => {
        if(!nextBtn.classList.contains('disabled')){
            let current = document.querySelector('.page-number.active')
            let next = document.querySelector('.page-number.active').nextElementSibling
            
            current.classList.remove('active')
            next.classList.add('active')

            Array.from(document.querySelectorAll('.list')).forEach(element => {
                if(element.style.display = 'flex')
                    element.style.display = 'none'
            })

            let page = next.querySelector('a').dataset.count
            Array.from(document.querySelectorAll(`[data-page='${page}']`)).forEach(element => {
                element.style.display = 'flex'
            })

            if(page == 1)
                prevBtn.classList.add('disabled')
            else
                prevBtn.classList.remove('disabled')
            if(page == pageNumbers.length)
                nextBtn.classList.add('disabled')
            else
                nextBtn.classList.remove('disabled')

            limitAmountNumbers()
        }
    })

    view();
    $( ".list" ).first().trigger( "click" );
    $('#owl-demo').owlCarousel({
        nav: true,
        navText:["<div class='prev-slide'><img src='<?php echo base_url('assets/theme/bootstrap5/img/icons/prev-slide.png') ?>'/></div>","<div class='next-slide'><img src='<?php echo base_url('assets/theme/bootstrap5/img/icons/next-slide.png') ?>'/></div>"],
        responsive: {
            0: {
                items: 1
            },
            600: {
                items: 2
            },
            1200: {
                items: 2
            },
            1300: {
                items: 3
            },
            1500: {
                items: 4
            }
        }
    });
}); 

let availableDates = false;

let calendar = new VanillaCalendar({
    selector: "#myCalendar",
    month: 10,
    months: ["Janeiro", "Fevereiro", "Março", "Abril", "Maio", "Junho", "Julho", "Agosto", "Setembro", "Outubro", "Novembro", "Dezembro"],
    shortWeekday: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb'],
    onHover: (data, elem) => {
        let id_company = $( "a.active" ).data( "id" );
        let date = elem.getAttribute('data-calendar-date');

        $.ajax({
            url:"<?php echo base_url('index.php/get_orders'); ?>",
            method:"POST",
            data:{id_company:id_company, date:date},
            success:function(qtd){
                let tooltip = qtd +' pesquisas realizadas'
                elem.setAttribute('title',tooltip)
            }
        })
    }        
});

function edit(company){
    let base_url = "<?php echo base_url() ?>";
    let id = $(company).data('id');
    window.location.href= base_url+"/index.php/edit_company?id_company="+id;
    console.log(base_url);
};

function company(data){
    $( ".list" ).removeClass( "active" );
    $(data).toggleClass('active');
    let startDate = new Date($(data).data('begin'));
    let endDate = new Date($(data).data('end'));
    
    calendar.set({date: new Date($(data).data('begin'))});
    
    let dates = () => {
        let result = []
        for (let day = startDate;day <= endDate; day.setDate(day.getDate() + 1)) {
            let date = day
            result.push({date: `${String(date.getFullYear())}-${String(date.getMonth() + 1).padStart(2, 0)}-${String(date.getDate()).padStart(2, 0)}`})
        }
        return result
    }
    calendar.set({availableDates: dates(), datesFilter: true})
    calendar.reset();
    let id = $(data).data('id');
    $.ajax({
        url:"<?php echo base_url('index.php/get_company_info'); ?>",
        method:"POST",
        data:{id:id},
        success:function(data){
            let dataArray = JSON.parse(data);
            console.log(dataArray)
            $('#pesquisas').text(dataArray['pesquisas']);
            $('#amostra').text(dataArray['amostra']+ '%');
            $('#hoje').text(dataArray['hoje']);
            $('#entrevista').text(dataArray['entrevista']);
        }
    })
}

function view(){
    let view = "<?php echo $view; ?>";
    let id ="<?php echo $session->get('id') ?>";
    if(view == 0){
        $( "#button_modal").trigger( "click" );
        $('#close').click(function(){
            $.ajax({
                url:"<?php echo base_url('index.php/update_view'); ?>",
                method:"POST",
                data:{id:id},
                success:function(data){}
            })
        });
    }
}
   
</script>