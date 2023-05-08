<link href="/assets/pages/css/client/formulario_pesquisa.css" rel="stylesheet">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-12">
            <form id="msform" onsubmit="return false;">
                <img src="<?php echo base_url("/assets/img/brand/logo.png") ?>">
                <fieldset>
                    <h2 class="fs-title">Obrigado por chegar até aqui, estamos quase acabando!</h2>
                    <h3 class="fs-subtitle">Responda as próximas perguntas para finalizar.</h3>
                    <button type="button" id="second_next" name="next" class="next action-button" value="Proximo">Continuar</button>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Informações sobre sua experiência de compra</h2>
                    <h3 class="fs-subtitle">Preencha todos os campos para prosseguir</h3>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="21">Durante os exercícios de compra, quais marcas de produtos para colorir | tingir os cabelos, você lembra de ter visto?</label>
                            <textarea style="height: 130px;" class="form-control" id="21" name="21" placeholder="Descreva aqui..." rows="5" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="22">Durante sua compra, você viu algum produto e/ou embalagem nova que nunca tinha visto antes e passou a conhecer hoje, aqui?</label>
                            <select id="22" name="22" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Sim</option>
                                <option value="2">Não vi nenhum produto novo</option>
                                <option value="3">Vi, mas não lembro a marca</option>
                            </select>
                            <input type="text" class="form-control" id="22-descreva" name="22-descreva" placeholder="Se sim, descreva aqui"  />
                        </div>
                        <?php if($bought === 0){ ?>
                        <div class="col-md-12">
                            <label for="23">Por que você não comprou nenhum produto hoje?</label>
                            <select id="23" name="23" class="form-control" onchange="toggle23(this)" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Porque não encontrei a marca que costumo usar</option>
                                <option value="2">Porque não encontrei a cor que costumo usar</option>
                                <option value="outro">Outro motivo</option>
                            </select>
                            <input type="text" class="form-control hidden" id="23-marca" name="23-marca" placeholder="Qual marca você costuma usar?"  />
                            <input type="text" class="form-control hidden" id="23-cor" name="23-cor" placeholder="Qual cor você costuma usar?"  />
                            <input type="text" class="form-control" id="23-outros" name="23-outros" placeholder="Algum outro motivo? Descreva aqui..."  />
                        </div>
                        <?php } ?>
                    </div>
                    <button type="button" name="previous" class="previous action-button-previous">Voltar</button>
                    <button type="button" id="second_next" name="next" class="next action-button" value="Proximo">Próximo</button>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Informações sobre sua experiência de compra</h2>
                    <h3 class="fs-subtitle">Preencha seus dados corretamente para prosseguir</h3>
                    <?php if($id_scenario == "136" || $id_scenario == "140"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario140-questionario.png" alt="">
                    <?php }else if($id_scenario == "137" || $id_scenario == "141"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario141-questionario.png" alt="">
                    <?php }else if($id_scenario == "138" || $id_scenario == "142"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario142-questionario.png" alt="">
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="24">Em sua opinião, como essa gôndola de produtos para colorir|tingir o cabelo está organizada?</label>
                            <select id="24" name="24" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Por marca, por tipo de produto</option>
                                <option value="2">Por tipo de produto, por marca</option>
                            </select>
                            <input type="text" class="form-control" id="24-outros" name="24-outros" placeholder="Algum outro método? Descreva aqui..."  />
                        </div>
                        <div class="col-md-12">
                            <label for="25">O quão fácil foi você encontrar os produtos para colorir | tingir os cabelos que procurava nessa gôndola hoje?</label>
                            <select id="25" name="25" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Extremamente fácil</option>
                                <option value="2">Fácil</option>
                                <option value="3">Nem fácil, nem difícil</option>
                                <option value="4">Difícil</option>
                                <option value="5">Extremamente difícil</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="26">Por qual razão você acha isso sobre a experiência de comprar produtos para colorir|tingir os cabelos hoje?</label>
                            <textarea style="height: 130px;" class="form-control" id="26" name="26" placeholder="Descreva aqui..." rows="5" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="27">Durante a sua compra de produtos para colorir | tingir os cabelos hoje, você viu algum item que não conhecia e | ou que não pretendia comprar e decidiu comprar?</label>
                            <select id="27" name="27" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Sim</option>
                                <option value="2">Não</option>
                            </select>
                            <input type="text" class="form-control" id="27-outros" name="27-outros" placeholder="Se sim, quais?" />
                        </div>
                        <div class="col-md-12">
                            <label for="28">E o quanto você gostou dessa gôndola de produtos para colorir|tingir os cabelos hoje numa nota de 0 a 10, onde 0 significa que você não gostou nada e 10 significa que você gostou muito para realizar a sua compra?</label>
                            <select id="28" name="28" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="29">Pensando primeiro em coisas positivas dessa gôndola, o que você mais gostou? Mais alguma coisa? </label>
                            <textarea style="height: 130px;" class="form-control" id="29" name="29" placeholder="Descreva aqui..." rows="5" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label for="30">E falando agora de coisas negativas dessa gôndola, tem alguma coisa, qualquer que seja, que você não gostou? Mais alguma coisa?</label>
                            <textarea style="height: 130px;" class="form-control" id="30" name="30" placeholder="Descreva aqui..." rows="5" required></textarea>
                        </div>
                        <div class="col-md-12">
                            <div style="margin-top: 15px;">
                                <label for="31-1">
                                    Abaixo estão algumas frases e gostaríamos que você assinalasse o quanto você concorda ou discorda que a frase descreve a sua experiência de compra de produtos para colorir|tingir os cabelos hoje. 
                                    Então, o quanto você concorda ou discorda que
                                </label>
                                <h3 style="text-align: left;margin: 0;margin-top: 15px;" class="fs-subtitle">
                                    É fácil eu encontrar os produtos para colorir|tingir os cabelos que eu preciso
                                </h3>
                                <select id="31-1" name="31-1" class="form-control" required>
                                    <option selected disabled>Selecione uma opção</option>
                                    <option value="1">DISCORDO TOTALMENTE</option>
                                    <option value="2">DISCORDO</option>
                                    <option value="3">NEM CONCORDO, NEM DISCORDO</option>
                                    <option value="4">CONCORDO</option>
                                    <option value="5">CONCORDO TOTALMENTE</option>
                                </select>
                                <h3 style="text-align: left;margin: 0;margin-top: 15px;" class="fs-subtitle">
                                    Me desperta o desejo de comprar produtos para colorir|tingir os cabelos que não tenho costume de comprar
                                </h3>
                                <select id="31-2" name="31-2" class="form-control" required>
                                    <option selected disabled>Selecione uma opção</option>
                                    <option value="1">DISCORDO TOTALMENTE</option>
                                    <option value="2">DISCORDO</option>
                                    <option value="3">NEM CONCORDO, NEM DISCORDO</option>
                                    <option value="4">CONCORDO</option>
                                    <option value="5">CONCORDO TOTALMENTE</option>
                                </select>
                                <h3 style="text-align: left;margin: 0;margin-top: 15px;" class="fs-subtitle">
                                    Me ajuda a conhecer novos tipos de produtos para colorir|tingir os cabelos 
                                </h3>
                                <select id="31-3" name="31-3" class="form-control" required>
                                    <option selected disabled>Selecione uma opção</option>
                                    <option value="1">DISCORDO TOTALMENTE</option>
                                    <option value="2">DISCORDO</option>
                                    <option value="3">NEM CONCORDO, NEM DISCORDO</option>
                                    <option value="4">CONCORDO</option>
                                    <option value="5">CONCORDO TOTALMENTE</option>
                                </select>
                                <h3 style="text-align: left;margin: 0;margin-top: 15px;" class="fs-subtitle">
                                    Me ajuda a lembrar de outras marcas que gosto, mas que não compro com tanta frequência 
                                </h3>
                                <select id="31-4" name="31-4" class="form-control" required>
                                    <option selected disabled>Selecione uma opção</option>
                                    <option value="1">DISCORDO TOTALMENTE</option>
                                    <option value="2">DISCORDO</option>
                                    <option value="3">NEM CONCORDO, NEM DISCORDO</option>
                                    <option value="4">CONCORDO</option>
                                    <option value="5">CONCORDO TOTALMENTE</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button type="button" name="previous" class="previous action-button-previous">Voltar</button>
                    <button type="button" id="second_next" name="next" class="next action-button" value="Proximo">Próximo</button>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Agora vamos apresentar outra alternativa de organização de produtos para colorir|tingir os cabelos e gostaríamos que você olhasse atentamente</h2>
                    <h3 class="fs-subtitle">Preencha todos os campos para prosseguir</h3>
                    <?php if($id_scenario == "136" || $id_scenario == "140"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario141-questionario.png" alt="">
                    <?php }else if($id_scenario == "137" || $id_scenario == "141"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario142-questionario.png" alt="">
                    <?php }else if($id_scenario == "138" || $id_scenario == "142"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario140-questionario.png" alt="">
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="32">Em sua opinião, como essa gôndola de produtos para colorir|tingir os cabelos está organizada?</label>
                            <select id="32" name="32" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Por marca, por tipo de produto</option>
                                <option value="2">Por tipo de produto, por marca</option>
                            </select>
                            <input type="text" class="form-control" id="32-outros" name="32-outros" placeholder="Caso você acredite ser algum outro, descreva aqui..." />
                        </div>
                        <div class="col-md-12">
                            <label for="33">O quão fácil te parece que será encontrar os produtos para você colorir/tingir os cabelos que você quiser comprar? </label>
                            <select id="33" name="33" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Extremamente fácil</option>
                                <option value="2">Fácil</option>
                                <option value="3">Nem fácil, nem difícil</option>
                                <option value="4">Difícil</option>
                                <option value="5">Extremamente difícil</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="34">E o quanto você gostou dessa gôndola de produtos para colorir|tingir os cabelos, numa nota de 0 a 10, onde 0 significa que você não gostou nada e 10 significa que você gostou muito?</label>
                            <select id="34" name="34" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" name="previous" class="previous action-button-previous">Voltar</button>
                    <button type="button" id="second_next" name="next" class="next action-button" value="Proximo">Próximo</button>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Agora vamos apresentar outra alternativa de organização de produtos para colorir|tingir os cabelos e gostaríamos que você olhasse atentamente</h2>
                    <h3 class="fs-subtitle">Preencha todos os campos para prosseguir</h3>
                    <?php if($id_scenario == "136" || $id_scenario == "140"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario142-questionario.png" alt="">
                    <?php }else if($id_scenario == "137" || $id_scenario == "141"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario140-questionario.png" alt="">
                    <?php }else if($id_scenario == "138" || $id_scenario == "142"){ ?>
                        <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario141-questionario.png" alt="">
                    <?php } ?>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="32-duplicada">Em sua opinião, como essa gôndola de produtos para colorir|tingir os cabelos está organizada?</label>
                            <select id="32-duplicada" name="32-duplicada" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Por marca, por tipo de produto</option>
                                <option value="2">Por tipo de produto, por marca</option>
                            </select>
                            <input type="text" class="form-control" id="32-duplicada-outros" name="32-duplicada-outros" placeholder="Caso você acredite ser algum outro, descreva aqui..." />
                        </div>
                        <div class="col-md-12">
                            <label for="33-duplicada">O quão fácil te parece que será encontrar os produtos para você colorir/tingir os cabelos que você quiser comprar? </label>
                            <select id="33-duplicada" name="33-duplicada" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Extremamente fácil</option>
                                <option value="2">Fácil</option>
                                <option value="3">Nem fácil, nem difícil</option>
                                <option value="4">Difícil</option>
                                <option value="5">Extremamente difícil</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="34-duplicada">E o quanto você gostou dessa gôndola de produtos para colorir|tingir os cabelos, numa nota de 0 a 10, onde 0 significa que você não gostou nada e 10 significa que você gostou muito?</label>
                            <select id="34-duplicada" name="34-duplicada" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" name="previous" class="previous action-button-previous">Voltar</button>
                    <button type="button" id="second_next" name="next" class="next action-button" value="Proximo">Próximo</button>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Informações sobre sua experiência de compra</h2>
                    <h3 class="fs-subtitle">Preencha todos os campos para prosseguir</h3>
                    <h2 class="fs-title" style="margin-top: 20px;">Gôndola 1</h2>
                    <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario140-questionario.png" alt="">
                    <h2 class="fs-title" style="margin-top: 20px;">Gôndola 2</h2>
                    <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario141-questionario.png" alt="">
                    <h2 class="fs-title" style="margin-top: 20px;">Gôndola 3</h2>
                    <img style="max-width: 100%;" src="/assets/uploads/scenarios/scenario142-questionario.png" alt="">
                    <div class="row">
                        <div class="col-md-12">
                            <div style="margin-top: 15px;">
                                <label for="35-1">
                                    Agora que você conheceu as 3 alternativas de exposição|organização da categoria de produtos para colorir|tingir os cabelos, eu vou ler algumas frases e gostaria que você me dissesse com qual dessas exposições|gôndola cada frase mais combina. Não existem respostas certas ou erradas, é a sua opinião que interessa: 
                                </label>
                                <h3 style="text-align: left;margin: 0;margin-top: 15px;" class="fs-subtitle">
                                    É fácil eu encontrar os produtos para colorir|tingir os cabelos que eu preciso 
                                </h3>
                                <div style="margin-top: 15px;" class="checkbox-required">
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Primeira gôndola</div>
                                        <input name="35-1" class="form-control" type="checkbox" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Segunda gôndola</div>
                                        <input name="35-1" class="form-control" type="checkbox" value="2">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Terceira gôndola</div>
                                        <input name="35-1" class="form-control" type="checkbox" value="3">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Todas</div>
                                        <input name="35-1" class="form-control todas" type="checkbox" value="6">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Nenhuma</div>
                                        <input name="35-1" class="form-control excludente" type="checkbox" value="89">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <h3 style="text-align: left;margin: 0;margin-top: 15px;" class="fs-subtitle">
                                    Me desperta o desejo de comprar produtos para colorir|tingir os cabelos que não tenho costume de comprar  
                                </h3>
                                <div style="margin-top: 15px;" class="checkbox-required">
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Primeira gôndola</div>
                                        <input name="35-2" class="form-control" type="checkbox" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Segunda gôndola</div>
                                        <input name="35-2" class="form-control" type="checkbox" value="2">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Terceira gôndola</div>
                                        <input name="35-2" class="form-control" type="checkbox" value="3">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Todas</div>
                                        <input name="35-2" class="form-control todas" type="checkbox" value="6">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Nenhuma</div>
                                        <input name="35-2" class="form-control excludente" type="checkbox" value="89">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <h3 style="text-align: left;margin: 0;margin-top: 15px;" class="fs-subtitle">
                                    Me ajuda a conhecer novos tipos de produtos para colorir|tingir os cabelos 
                                </h3>
                                <div style="margin-top: 15px;" class="checkbox-required">
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Primeira gôndola</div>
                                        <input name="35-3" class="form-control" type="checkbox" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Segunda gôndola</div>
                                        <input name="35-3" class="form-control" type="checkbox" value="2">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Terceira gôndola</div>
                                        <input name="35-3" class="form-control" type="checkbox" value="3">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Todas</div>
                                        <input name="35-3" class="form-control todas" type="checkbox" value="6">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Nenhuma</div>
                                        <input name="35-3" class="form-control excludente" type="checkbox" value="89">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                                <h3 style="text-align: left;margin: 0;margin-top: 15px;" class="fs-subtitle">
                                    Me ajuda a lembrar de outras marcas que gosto, mas que não compro com tanta frequência
                                </h3>
                                <div style="margin-top: 15px;" class="checkbox-required">
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Primeira gôndola</div>
                                        <input name="35-4" class="form-control" type="checkbox" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Segunda gôndola</div>
                                        <input name="35-4" class="form-control" type="checkbox" value="2">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Terceira gôndola</div>
                                        <input name="35-4" class="form-control" type="checkbox" value="3">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Todas</div>
                                        <input name="35-4" class="form-control todas" type="checkbox" value="6">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Nenhuma</div>
                                        <input name="35-4" class="form-control excludente" type="checkbox" value="89">
                                        <span class="checkmark"></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="36">E qual delas você prefere?</label>
                            <select id="36" name="36" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Primeira gôndola</option>
                                <option value="2">Segunda gôndola</option>
                                <option value="3">Terceira gôndola</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="37">Por que você prefere essa exposição|organização?</label>
                            <textarea style="height: 130px;" class="form-control" id="37" name="37" placeholder="Descreva aqui..." rows="5" required></textarea>
                        </div>
                    </div>
                    <button type="button" name="previous" class="previous action-button-previous">Voltar</button>
                    <button type="button" id="second_next" name="next" class="next action-button" value="Proximo">Próximo</button>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title">Informações sobre sua experiência de compra</h2>
                    <h3 class="fs-subtitle">Preencha todos os campos para prosseguir</h3>
                    <img style="width: 100%;" src="/assets/uploads/questionario/tiposCabelo.png" alt="">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="38">Olhando a imagem acima, qual é o formato que mais se aproxima do formato atual do seu cabelo?</label>
                            <select id="38" name="38" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Liso</option>
                                <option value="2">Levemente Ondulado</option>
                                <option value="3">Ondulado</option>
                                <option value="4">Cacheado</option>
                                <option value="5">Muito Cacheado</option>
                                <option value="6">Levemente Crespo</option>
                                <option value="7">Crespo</option>
                                <option value="8">Muito Crespo</option>
                                <option value="9">Com dreads</option>
                                <option value="10">Trançado</option>
                                <option value="11">Careca/raspado</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="39">Você diria que seu cabelo natural é...?</label>
                            <select id="39" name="39" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Liso</option>
                                <option value="2">Ondulado</option>
                                <option value="3">Cacheado</option>
                                <option value="4">Muito Cacheado</option>
                                <option value="5">Crespo</option>
                                <option value="6">Muito Crespo</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="40">Qual o seu tipo de cabelo?</label>
                            <select id="40" name="40" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Muito olesoso</option>
                                <option value="2">Oleoso</option>
                                <option value="3">Norma</option>
                                <option value="4">Seco</option>
                                <option value="5">Muito Seco</option>
                                <option value="6">Misto (raíz oleosa e pontas secas)</option>
                                <option value="7">Normal</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <div>
                                <label for="41">Quais das características abaixo o seu cabelo possui atualmente? </label>
                                <div style="margin-top: 15px;" class="checkbox-required">
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Com brilhos</div>
                                        <input name="41" class="form-control" type="checkbox" value="1">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Fortes</div>
                                        <input name="41" class="form-control" type="checkbox" value="2">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Crescimento rápido</div>
                                        <input name="41" class="form-control" type="checkbox" value="3">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Arrepiados / com frizz</div>
                                        <input name="41" class="form-control" type="checkbox" value="4">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Pontas Duplas</div>
                                        <input name="41" class="form-control" type="checkbox" value="5">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Queda / Quebra</div>
                                        <input name="41" class="form-control" type="checkbox" value="6">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Com Volume</div>
                                        <input name="41" class="form-control" type="checkbox" value="7">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Sem Volume</div>
                                        <input name="41" class="form-control" type="checkbox" value="8">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Couro Cabeludo com Muito Suor</div>
                                        <input name="41" class="form-control" type="checkbox" value="9">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Crescimento Lento</div>
                                        <input name="41" class="form-control" type="checkbox" value="10">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Fraco / Quebradiço</div>
                                        <input name="41" class="form-control" type="checkbox" value="11">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Opaco / Sem Brilho</div>
                                        <input name="41" class="form-control" type="checkbox" value="12">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Ressecado</div>
                                        <input name="41" class="form-control" type="checkbox" value="13">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Danificado</div>
                                        <input name="41" class="form-control" type="checkbox" value="14">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Oleosidade excessiva</div>
                                        <input name="41" class="form-control" type="checkbox" value="15">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Couro Cabeludo Oleoso</div>
                                        <input name="41" class="form-control" type="checkbox" value="16">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Couro Cabeludo com Coceira</div>
                                        <input name="41" class="form-control" type="checkbox" value="17">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Couro Cabeludo Sensível</div>
                                        <input name="41" class="form-control" type="checkbox" value="18">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Caspa</div>
                                        <input name="41" class="form-control" type="checkbox" value="19">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label class="checkboxLabel">
                                        <div style="margin-top: 1px;">Nada me incomoda</div>
                                        <input name="41" class="form-control" type="checkbox" value="20">
                                        <span class="checkmark"></span>
                                    </label>
                                    <label>Outros:</label>
                                    <input type="text" class="form-control" id="41-outros" name="41-outros" placeholder="Opcional" />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="42">Qual a cor natural do seu cabelo?</label>
                            <select id="42" name="42" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Louro</option>
                                <option value="2">Mel</option>
                                <option value="3">Castanho</option>
                                <option value="4">Preto</option>
                                <option value="5">Ruivo</option>
                                <option value="outro">Outro</option>
                            </select>
                            <input type="text" class="form-control" id="42-outros" name="42-outros" placeholder="Caso seja algum outro, descreva aqui..." />
                        </div>
                        <div class="col-md-12">
                            <label for="43">Qual a cor atual do seu cabelo?</label>
                            <select id="43" name="43" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Louro</option>
                                <option value="2">Mel</option>
                                <option value="3">Castanho</option>
                                <option value="4">Preto</option>
                                <option value="5">Ruivo</option>
                                <option value="outro">Outro</option>
                            </select>
                            <input type="text" class="form-control" id="43-outros" name="43-outros" placeholder="Caso seja algum outro, descreva aqui..." />
                        </div>
                        <div class="col-md-12">
                            <label for="44">Com qual das cores de pele abaixo você mais se identifica?</label>
                            <select id="44" name="44" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Branca</option>
                                <option value="2">Preta</option>
                                <option value="3">Parda</option>
                                <option value="4">Amarela</option>
                                <option value="5">Indígena</option>
                                <option value="6">Prefiro não dizer</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label for="45">Sobre a pele do seu rosto, você diria que...</label>
                            <select id="45" name="45" class="form-control" required>
                                <option selected disabled>Selecione uma opção</option>
                                <option value="1">Oleosa</option>
                                <option value="2">Mista</option>
                                <option value="3">Muito seca/ressecada</option>
                                <option value="4">Muito oleosa</option>
                                <option value="5">Seca</option>
                                <option value="6">Mista</option>
                            </select>
                        </div>
                    </div>
                    <button type="button" name="previous" class="previous action-button-previous">Voltar</button>
                    <button type="submit" name="submit" class="submit action-button" style="width: 200px;">Próximo</button>
                </fieldset>
                <fieldset>
                    <h2 class="fs-title" id="end_page_title">Finalizando...</h2>
                    <h3 class="fs-subtitle" id="end_page_subtitle">Aguarde enquanto checamos suas respostas</h3>
                    <div class="row">
                        <div id="loading" class="col-md-12">
                            <div class="text-center" style="padding: 75px;">
                                <svg style="width: 120px; height: 120px">
                                    <svg id="check" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 100 100" xml:space="preserve">
                                        <circle id="circle" cx="50" cy="50" r="46" fill="transparent" />
                                        <polyline id="tick" points="25,55 45,70 75,33" fill="transparent" />
                                    </svg>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p>Estamos enviando suas respostas, obrigado!</p>
                        </div>
                    </div>
                </fieldset>
            </form>
            <div id="toast" style="border-radius: 25px;">
                <div id="img"><img style="width: 85px;margin: -30px 0px 0px -40px;position: absolute;" src="<?php echo base_url('/assets/theme/bootstrap5/img/brand/favicon.png') ?>"></div>
                <div id="desc" hidden style="margin-top: -4px; border-radius: 10px 20px 30px;">Preencha os campos indicados para continuar.</div>
            </div>
        </div>
    </div>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.3/jquery.easing.min.js'></script>
    <script>
        // ----- Formatadores e validadores -----

        function formatarMoeda() {
            var elemento = document.getElementById('renda_familiar');
            var valor = elemento.value;
            valor = valor + '';
            valor = parseInt(valor.replace(/[\D]+/g, ''));
            valor = valor + '';
            valor = valor.replace(/([0-9]{2})$/g, ",$1");
            if (valor.length > 6) {
                valor = valor.replace(/([0-9]{3}),([0-9]{2}$)/g, ".$1,$2");
            }
            elemento.value = ((valor.length < 3) ? valor : `R$ ${valor}`);
            if (valor == 'NaN') elemento.value = '';
        }

        function formatarCep() {
            var elemento = document.getElementById('cep');
            var valor = elemento.value;
            if (String(valor).length < 9) {

                valor = valor.replace(/\D/g, "")
                valor = valor.replace(/^(\d{5})(\d)/, "$1-$2")
                elemento.value = valor;
                if (valor == 'NaN') elemento.value = '';
            }
        }


        function mask(o, f) {
            setTimeout(function() {
                var v = mphone(o.value);
                if (v != o.value) {
                    o.value = v;
                }
            }, 1);
        }

        function mphone(v) {
            var r = v.replace(/\D/g, "");
            r = r.replace(/^0/, "");
            if (r.length > 10) {
                r = r.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
            } else if (r.length > 5) {
                r = r.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
            } else if (r.length > 2) {
                r = r.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
            } else {
                r = r.replace(/^(\d*)/, "($1");
            }
            return r;
        }


        var current_fs, next_fs, previous_fs;
        var left, opacity, scale;
        var animating;

        function nextPage(element) {
            if (animating) return false;
            animating = true;
            current_fs = element.parent();
            next_fs = element.parent().next();
            next_fs.show();
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now, mx) {
                    scale = 1 - (1 - now) * 0.2;
                    left = (now * 50) + "%";
                    opacity = 1 - now;
                    current_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'position': 'absolute'
                    });
                    next_fs.css({
                        'left': left,
                        'opacity': opacity
                    });
                    current_fs.removeClass("active_tab");
                    next_fs.addClass("active_tab");
                },
                duration: 800,
                complete: function() {
                    current_fs.hide();
                    animating = false;
                },
                easing: 'easeInOutBack'
            });
        }

        const checkInputs = () => {
            // --- Verificar inputs vazios na aba ativa antes de continuar ---
            var exist_clear = false
            $(".active_tab")
                .find('input, select, textarea')
                .each(function() {
                    if ($(this).val() === "" || $(this).val() === null) {
                        if ($(this).prop('required')) {
                            $(this).css({
                                'border-color': "red"
                            });
                            exist_clear = true
                        }
                    } else {
                        $(this).css({
                            'border-color': ""
                        });
                    }
                });

            $(".active_tab").find(".checkbox-required").each((index, element) => {
                let size = $(element).find("[type=checkbox]:checked").size();
                console.log(size);
                console.log(size === 0);
                if(size === 0){
                    console.log(element)
                    $(element).addClass("error");
                    exist_clear = true
                } else {
                    $(element).removeClass("error");
                }
            })

            if(exist_clear) launch_toast();

            return exist_clear;
        }

        $(".next").click(function() {
            if (checkInputs()) return;
            nextPage($(this))
            window.scroll({top: 0, left: 0, behavior: 'smooth'});
        });

        $(".previous").click(function() {
            if (animating) return false;
            animating = true;
            current_fs = $(this).parent();
            previous_fs = $(this).parent().prev();
            previous_fs.show();
            current_fs.animate({
                opacity: 0
            }, {
                step: function(now, mx) {
                    scale = 0.8 + (1 - now) * 0.2;
                    left = ((1 - now) * 50) + "%";
                    opacity = 1 - now;
                    current_fs.css({
                        'left': left
                    });
                    previous_fs.css({
                        'transform': 'scale(' + scale + ')',
                        'opacity': opacity
                    });
                    current_fs.removeClass("active_tab");
                    previous_fs.addClass("active_tab");
                },
                duration: 800,
                complete: function() {
                    current_fs.hide();
                    animating = false;
                },
                easing: 'easeInOutBack'
            });
        });

        let toggle = (element) => {
            $(element).slideToggle("slow");
            $(element).toggleClass("checkbox-required");
        }

        let toggle23 = (element) => {
            console.log($(element).val());
            $("#23-marca, #23-cor").removeAttr("required").hide();
            if($(element).val() == "1") $("#23-marca").attr("required", "required").show();
            else if($(element).val() == "2") $("#23-cor").attr("required", "required").show();
        }

        const excludente = (name) => {
            $(`[name=${name}]:not(.excludente):checked`).click().change();
        }

        const todas = (name) => {
            $(`[name=${name}]:not(.excludente):not(.todas):not(:checked)`).click().change();
        }

        $(".excludente").change((e) => {
            if($(e.currentTarget).is(":checked")) excludente( e.currentTarget.name );
        });

        $(".todas").change((e) => {
            if($(e.currentTarget).is(":checked")) todas( e.currentTarget.name );
        });

        $("[type='checkbox']:not(.excludente)").change((e) => {
            let name = e.currentTarget.name
            if($(e.currentTarget).is(":checked")) $(`[name=${name}].excludente:checked`).click().change();

            // let sizeNotChecked = $(`[name=${name}]:not(.excludente):not(:checked)`).size();
            // console.log(sizeNotChecked);
            // if(sizeNotChecked > 0 && $(`[name=${name}].todas`).is(":checked")) $(`[name=${name}].todas`).click().change()
        });


        $("#msform").submit(function(event) {

            if (checkInputs()) return;

            var formJqObj = $("#msform");
            var formDataObj = {};
            (function() {
                formJqObj.find(":input")
                    .not("[type='submit']")
                    .not("[type='reset']")
                    .not("[name='next']")
                    .not("[name='previous']")
                    .each(function() {
                        var thisInput = $(this);
                        if (thisInput.attr('type') === "checkbox" && thisInput.is(":checked")) {
                            if(!formDataObj[thisInput.attr("name")]){
                                formDataObj[thisInput.attr("name")] = "";
                            }
                            formDataObj[thisInput.attr("name")] += thisInput.val()+"; ";
                        } else if (thisInput.attr('type') !== "checkbox") {
                            formDataObj[thisInput.attr("name")] = thisInput.val();
                        }
                    });
            })();

            $.ajax({
                type: "POST",
                url: "/index.php/form/save_end",
                contentType: "application/json",
                data: JSON.stringify({
                    id_user: "<?php echo $_GET['uuid'] ?>",
                    id_company: "<?php echo $_GET["id_company"] ?>",
                    answers: formDataObj
                }),
                success: (s) => {
                    console.log(s);
                    window.location.href="/index.php/form/obrigado_por_concluir";
                },
                error: (e) => {
                    console.log(e);
                }
            });
        });

        function montar_JSON(){
            var formJqObj = $("#msform");
            var formDataObj = {};
            (function() {
                formJqObj.find(":input")
                    .not("[type='submit']")
                    .not("[type='reset']")
                    .not("[name='next']")
                    .not("[name='previous']")
                    .each(function() {
                        var thisInput = $(this);
                        if (thisInput.attr('type') === "checkbox" && thisInput.is(":checked")) {
                            formDataObj[thisInput.attr("name")] = thisInput.val();
                        } else {
                            formDataObj[thisInput.attr("name")] = thisInput.val();
                        }
                    });
            })();
        }

        function launch_toast() {
            var x = document.getElementById("toast")
            var msg = document.getElementById("desc")
            x.className = "show";
            setTimeout(function() {
                msg.hidden = false;
            }, 600)
            setTimeout(function() {
                msg.hidden = true;
            }, 4000);
            setTimeout(function() {
                x.className = x.className.replace("show", "");
            }, 5000);
        }
    </script>
</body>

</html>
