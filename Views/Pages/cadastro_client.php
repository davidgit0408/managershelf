<?php
    error_reporting(E_ALL & ~E_NOTICE);
?>
<!DOCTYPE html>
<style>
body { background-image:url(<?php echo base_url('assets/img/theme/bg.jpg') ?>) }
#form{ top: 0; bottom: 0; left: 0; right: 0; margin: auto;}
</style>

<main class="main h-100 w-100" style="margin-left:0px">
	<div class="h-100" style="overflow:hidden;">
		<div class="row h-100">
        <div class="col-10 col-sm-10 col-md-10 col-lg-10 col-xl-10" id="form">
            <div class="card">
                <div class="card-header border-0" >
                    <div class="row align-items-center col-12 ">
                        <div class="col-8 mt-2" >
                            <h3 class="mb-0" style="color:#ff9821">Adicionar Cliente</h3>
                        </div>
                    </div> 
                </div>
                <div class="card-body" style="background-color: #f7f2f2;">
                <form role="form" method="post" action="<?php echo base_url('index.php/client/add_client') ?>">
                    <div class="pl-lg-4">
                      <div class="row">
                        <div class="col-lg-6">
                          <div class="form-group">
                            <label class="mb-1 form-control-label " for="name">Nome</label>
                            <input type="text" name="name" class="form-control" placeholder="Insira o nome do Cliente" >
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label class="mb-1 form-control-label" for="birthday">Data de Nascimento</label>
                            <input type="text" name="birthday" class="form-control date" placeholder="Insira a data de nascimento do Cliente" >
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label class="mb-1 form-control-label" for="tel">Telefone</label>
                            <input type="text" name="tel" class="form-control phone_with_ddd" placeholder="Insira o telefone para contato" >
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label class="mt-2 mb-1 form-control-label" for="cpf_cnpj">CPF/CNPJ</label>
                            <input type="text" name="cpf_cnpj" class="form-control cpfcnpj" placeholder="Insira o CPF/CNPJ do Cliente" maxlength="14">
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label class="mt-2 mb-1 form-control-label" for="email">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Insira o email do Cliente">
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label class="mt-2 mb-1 form-control-label" for="email">RG</label>
                            <input type="text" name="rg" class="form-control rg" placeholder="Insira o RG do Cliente">
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label class="mt-2 mb-1 form-control-label" for="country">Estado Civil</label>
                            <select name="marital_status" class="form-control" required>
                                  <option disabled selected>Selecione um Estado</option>
                                  <option value="Solteiro(a)">Solteiro(a)</option>
                                <option value="Casado(a)">Casado(a)</option>
                                <option value="Divorciado(a)">Divorciado(a)</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-lg-3">
                          <div class="form-group">
                            <label class="mt-2 mb-1 form-control-label" for="pass">Senha</label>
                            <input type="password" name="pass" class="form-control form-control-alternative" placeholder="Crie uma senha para o cliente" value="" required>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- Address -->
                    <h6 class="mt-4 card-subtitle text-muted ">Localização</h6>
                      <hr class="mt-2">
                      <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-8">
                                  <div class="form-group">
                                    <label class="mb-1 form-control-label" for="address">Endereço</label>
                                    <input name="address" id="endereço" class="form-control form-control-alternative endereço" placeholder="Endereço" value="" type="text" required>
                                  </div>
                                </div>
                                
                                <div class="col-md-4">
                                  <div class="form-group">
                                    <label class="mb-1 form-control-label" for="address">Bairro</label>
                                    <input name="district" id="bairro" class="form-control form-control-alternative" placeholder="Bairro" value="" type="text" required>
                                  </div>
                                </div>
                          </div>
                          <div class="row">
                            <div class="col-lg-4">
                              <div class="form-group">
                                <label class="mt-2 mb-1 form-control-label" for="city">Cidade</label>
                                <input type="text" name="city" id="cidade" class="form-control form-control-alternative" placeholder="Cidade" value="" required>
                              </div>
                            </div>
                            
                            <div class="col-lg-4">
                              <div class="form-group" >
                                <label class="mt-2 mb-1 form-control-label" for="role" style="display:block;" required>Estado</label>
                                <select name="state" class="form-control" name="role" style="" required>
                                      <option  disabled selected>Selecione um Estado</option>
                                      <option value="AC">Acre</option>
                                    <option value="AL">Alagoas</option>
                                    <option value="AP">Amapá</option>
                                    <option value="AM">Amazonas</option>
                                    <option value="BA">Bahia</option>
                                    <option value="CE">Ceará</option>
                                    <option value="DF">Distrito Federal</option>
                                    <option value="ES">Espírito Santo</option>
                                    <option value="GO">Goiás</option>
                                    <option value="MA">Maranhão</option>
                                    <option value="MT">Mato Grosso</option>
                                    <option value="MS">Mato Grosso do Sul</option>
                                    <option value="MG">Minas Gerais</option>
                                    <option value="PA">Pará</option>
                                    <option value="PB">Paraíba</option>
                                    <option value="PR">Paraná</option>
                                    <option value="PE">Pernambuco</option>
                                    <option value="PI">Piauí</option>
                                    <option value="RJ">Rio de Janeiro</option>
                                    <option value="RN">Rio Grande do Norte</option>
                                    <option value="RS">Rio Grande do Sul</option>
                                    <option value="RO">Rondônia</option>
                                    <option value="RR">Roraima</option>
                                    <option value="SC">Santa Catarina</option>
                                    <option value="SP">São Paulo</option>
                                    <option value="SE">Sergipe</option>
                                    <option value="TO">Tocantins</option>
        
                                </select>
                              </div>
                            </div>
                            <div class="col-lg-4">
                              <div class="mt-2 mb-1 form-group">
                                <label class="form-control-label" for="input-country">CEP</label>
                                <input type="text" name="zipcode" class="form-control form-control-alternative cep" placeholder="CEP" required>
                              </div>
                            </div>
                        </div>
                        
                      </div>
                    
                      <p class="text-center">
                        <input type="hidden" name="company" value="<?php echo $_GET['id_company'] ?>">
                        <input class="mt-4 btn btn-github" type="submit" value="Cadastrar" name="register">
                      </p>
                </form>
                </div>
            </div>
    	 </div>
    </div>
</div>
