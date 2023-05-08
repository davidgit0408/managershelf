<?php
error_reporting(E_ALL & ~E_NOTICE);
$session = session();
/* VALOR TOTAL A PAGAR */
/* $total = 1000.00; */ //temporariamente inserido manualmente
$formated_total = "R$ " . number_format($total, 2, ',', '.');
$baseurl = "https://$_SERVER[HTTP_HOST]";

//Apenas para o Smart Payment
//Pegar as credenciais do Mercado pago e popular o valor data-preference-id com o id criado para a transação
$curl = curl_init();
curl_setopt_array($curl, array(
  CURLOPT_URL => base_url() . '/MercadoPago/controllers/smartPaymentController.php',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_POSTFIELDS => (['amount' => $total, 'produto' => "Pesquisa de Mercado ManagerShelf."]),
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
));

$response_smart = curl_exec($curl);

curl_close($curl);
echo $response_smart;


?>
<style>
  .hidden,
  .hide {
    display: none !important
  }

  input::placeholder,
  .select-placeholder {
    color: #999 !important;
  }

  .btn-container {
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .pagseguro-logo {
    display: flex;
    flex-direction: column;
    padding: 40px;
    align-items: center;
    gap: 10px;
    padding-bottom: 0;
  }

  #botao_pagar {
    width: 100px;
  }

  .payment-opts button:focus {
    box-shadow: none;
  }

  .close {
    border: none;
    background-color: rgba(0, 0, 0, 0);
    color: #a00;
  }

  .close span {
    font-size: 26px;
  }

  .modal-header {
    display: flex;
    justify-content: flex-end;
  }
</style>
<script src="https://secure.mlstatic.com/sdk/javascript/v1/mercadopago.js"></script>
<meta name="viewport" content="width=device-width, initial-scale=1">
<div class="row">
  <div class="col-md-12 container mt-3">
    <div class="card">
      <div class="card-header border-0" style="background-color: #fc9700;">
        <div class="row align-items-center col-12 ">
          <div class="col-8" style="background-color: #fc9700;">
            <h3 class="mb-0 text-white">Pagamento</h3>
          </div>
          <div class="col-4 text-right p-0 mt-1"></div>
        </div>
      </div>
      <div class="card-body">
        <?php
        $success_msg = $session->getFlashdata('success_msg');
        $error_msg = $session->getFlashdata('error_msg');

        if ($error_msg) { ?>
          <div class="mt-3 alert alert-danger alert-dismissible" role="alert">
            <div class="alert-message">
              <?php echo $error_msg; ?>
            </div>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        <?php
        } else {
        ?>
          <div>
            <div class="payment-opts" style="display: flex;">
              <button class="credito-btn btn btn-secondary" style="margin: 0px 5px 0px 5px;" onclick="hiddenModal('cartao')">Cartão de crédito</button>
              <button class="boleto-btn btn btn-light" style="margin: 0px 5px 0px 5px;" onclick="hiddenModal('boleto')">Boleto bancário</button>
              <button class="boleto-btn btn btn-light" style="margin: 0px 5px 0px 5px; background-color: #e9ecef;" onclick="hiddenModal('pix')">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-qr-code" viewBox="0 0 16 16">
                  <path d="M2 2h2v2H2V2Z" />
                  <path d="M6 0v6H0V0h6ZM5 1H1v4h4V1ZM4 12H2v2h2v-2Z" />
                  <path d="M6 10v6H0v-6h6Zm-5 1v4h4v-4H1Zm11-9h2v2h-2V2Z" />
                  <path d="M10 0v6h6V0h-6Zm5 1v4h-4V1h4ZM8 1V0h1v2H8v2H7V1h1Zm0 5V4h1v2H8ZM6 8V7h1V6h1v2h1V7h5v1h-4v1H7V8H6Zm0 0v1H2V8H1v1H0V7h3v1h3Zm10 1h-1V7h1v2Zm-1 0h-1v2h2v-1h-1V9Zm-4 0h2v1h-1v1h-1V9Zm2 3v-1h-1v1h-1v1H9v1h3v-2h1Zm0 0h3v1h-2v1h-1v-2Zm-4-1v1h1v-2H7v1h2Z" />
                  <path d="M7 12h1v3h4v1H7v-4Zm9 2v2h-3v-1h2v-1h1Z" />
                </svg>
                Pix
              </button>
              <button id="modal-btn" type="button" class="btn btn-primary" data-toggle="modal" data-target="#centerModal" hidden>Modal de pagamento</button>
              <form action="<?php echo $baseurl; ?>/MercadoPago/controllers/paymentController.php" method="POST" style="margin: 0px 5px 0px 5px;">
                <!--Pagemento Smart-->
                <script src="https://www.mercadopago.com.br/integrations/v1/web-payment-checkout.js" data-button-label="Pagar com Mercado Pago" data-preference-id="<?php echo $response_smart; ?>">
                </script>
              </form>
            </div>
          </div>
          <h6 class="mt-4 card-subtitle text-muted ">Dados do pagamento</h6>
          <hr class="mt-2 card-hr">
          <p class="text-center text-danger hidden form-warning" style="font-size: 18px">Por favor preencha todos os campos do formulário.</p>
          <div id="pagar_pix" hidden>
            <p>Para sua segurança, preencha os campos dados para gerar o <strong>QrCode</strong> conforme o pagador.</p>
            <form id="" class="row">
              <div class="col-12 col-md-4 form-group mb-3">
                <label>Primeiro nome</label>
                <input type="text" class="form-control" name="" id="firstNamePix" />
                <small id="error FirstNamePix" class="text-danger"></small>
              </div>
              <div class="col-12 col-md-4 form-group mb-3">
                <label>Sobrenome</label>
                <input type="text" class="form-control" name="" id="lastNamePix" required />
                <small id="errorLastNamePix" class="text-danger"></small>
              </div>
              <div class="col-12 col-md-4 form-group mb-3">
                <label>Email</label>
                <input type="text" class="form-control" name="" id="emailPix" />
                <small id="errorEmailPix" class="text-danger"></small>
              </div>
            </form>
            <div class="col-lg-12 mt-5 btn-container">
              <button id="gerar_pix" onclick="gerarPix()" class="btn bg-orange text-white" style="width: 200px">Gerar QrCode
                <div id="loadQrCode" class="spinner-border spinner-border-sm visually-hidden" role="status">
                </div>
              </button>
              <div class="pagseguro-logo">
                <span class="">QrCode Gerado Por&nbsp</span>
                <img class="" width="200px" src="<?php echo base_url('assets/img/icons/common/mercadopago.png') ?>">
              </div>
            </div>
          </div>
          <div id="pagar_boleto" hidden>
            <p>A confirmação do pagamento via boleto bancário é automática e ocorre <strong>entre 48 e 72 horas</strong>. </p>
            <form id="" class="row">
              <div class="col-12 col-md-4 form-group mb-3">
                <label>Primeiro nome</label>

                <input type="text" class="form-control" name="" id="firstNameBol" />
                <small id="error  FirstNameBol" class="text-danger"></small>
              </div>
              <div class="col-12 col-md-4 form-group mb-3">
                <label>Sobrenome</label>
                <input type="text" class="form-control" name="" id="lastNameBol" required />
                <small id="errorLastNameBol" class="text-danger"></small>
              </div>
              <div class="col-12 col-md-4 form-group mb-3">
                <label>Email</label>
                <input type="text" class="form-control" name="" id="emailBol" />
                <small id="errorEmailBol" class="text-danger"></small>
              </div>
              <div class="col-12 col-md-6 form-group mb-3">
                <label>Documento</label>
                <select name="" class="form-control" id="typeDocBol">

                  <option value="" selected disabled>Selecione</option>
                  <option value="cnpj">CNPJ</option>
                  <option value="cpf">CPF</option>
                </select>

                <small id="errorTypeDocBol" class="text-danger"></small>
              </div>
              <div class="col-12 col-md-6 form-group mb-3">
                <label>Número documento</label>
                <input type="text" class="form-control" name="" id="docNumberBol" />
                <small id="errorDocNumberBol" class="text-danger"></small>
              </div>


            </form>
            <div class="col-lg-12 mt-5 btn-container">
              <button id="gerar_boleto" onclick="gerarBoleto()" class="btn bg-orange text-white" style="width: 200px">Gerar Boleto
                <div id="loadBoleto" class="spinner-border spinner-border-sm visually-hidden" role="status">
                </div>
              </button>

              <div class="pagseguro-logo">
                <span class="">Boleto Gerado Por&nbsp</span>
                <img class="" width="200px" src="<?php echo base_url('assets/img/icons/common/mercadopago.png') ?>">
              </div>
            </div>
          </div>
          <form id="form-checkout" class="row">
            <div class="col-12 col-md-3 form-group mb-3">
              <label>Valor Total a pagar</label>
              <p style="margin-bottom: 0rem;"><?php echo $formated_total; ?>
                <hr class="mt-2 card-hr">
              </p>
            </div>
            <div class="col-12 col-md-3 form-group mb-3">
              <label>Nome proprietario cartão</label>
              <input type="text" class="form-control" name="cardholderName" id="form-checkout__cardholderName" />
              <small class="text-danger" id="errorCardholderName"></small>
            </div>
            <div class="col-12 col-md-2 form-group mb-3">
              <label>Documento</label>
              <select name="identificationType" class="form-control" id="form-checkout__identificationType">
                <option>Selecionar</option>
              </select>
              <small class="text-danger" id="errorIdentificationType"></small>
            </div>
            <div class="col-12 col-md-4 form-group mb-3">
              <label>Número documento</label>
              <input type="text" class="form-control" name="identificationNumber" id="form-checkout__identificationNumber" />
              <small class="text-danger" id="errorIdentificationNumber"></small>
            </div>
            <div class="col-12 col-md-4 form-group mb-3">
              <label>Número do cartão</label>
              <div class="input-group">
                <input type="text" class="form-control" name="cardNumber" id="form-checkout__cardNumber" />
                <span class="input-group-text" id="basic-addon1">
                  <i class="fas fa-credit-card" id="icon-card"></i>
                </span>
              </div>
              <small class="text-danger" id="errorCardNumber"></small>
            </div>
            <div class="col-12 col-md-3 form-group mb-3">
              <label>Mês de expiração do cartão</label>
              <input type="text" class="form-control" name="cardExpirationMonth" id="form-checkout__cardExpirationMonth" />
              <small class="text-danger" id="errorCardExpirationMonth"></small>
            </div>
            <div class="col-12 col-md-3 form-group mb-3">
              <label>Ano de expiração do cartão</label>
              <input type="text" class="form-control" name="cardExpirationYear" id="form-checkout__cardExpirationYear" />
              <small class="text-danger" id="errorCardExpirationYear"></small>
            </div>
            <div class="col-12 col-md-2 form-group mb-3">
              <label>Código de segurança </label>
              <input type="text" class="form-control" name="securityCode" id="form-checkout__securityCode" />
              <small class="text-danger" id="errorSecurityCode"></small>
            </div>
            <div class="col-12 col-md-6 form-group mb-3">
              <label>E-mail</label>
              <input type="email" name="cardholderEmail" class="form-control" id="form-checkout__cardholderEmail" />
              <small class="text-danger" id="errorCardholderEmail"></small>
            </div>

            <div class="col-12 col-md-3 form-group mb-3">
              <label>Bandeira</label>
              <select name="issuer" class="form-control" id="form-checkout__issuer">
                <option>Selecionar</option>
              </select>
            </div>

            <div class="col-12 col-md-3 form-group mb-3">
              <label>Parcelas</label>
              <select name="installments" class="form-control" id="form-checkout__installments">
                <option>Selecionar</option>
              </select>
            </div>
            <div class="col-lg-12 mt-5 btn-container">
              <button id="form-checkout__submit" onclick="valida()" type="submit" class="btn bg-orange text-white" style="width: 200px">Pagar</button>
              <div class="pagseguro-logo">
                <span class="">Pagamento via&nbsp</span>
                <img class="" width="200px" src="<?php echo base_url('assets/img/icons/common/mercadopago.png') ?>">
              </div>
            </div>
          </form>
          <div class="modal fade" id="centerModal" tabindex="-1" role="dialog" aria-labelledby="centerModalTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header" style="display: flex;background-color: #f89e24;justify-content: space-between;">
                  <h5 class="modal-title" style="color: white;font-size: 20px;">Pagamento</h5>
                  <button type="button" id="close" class="close" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-square-fill" viewBox="0 0 16 16">
                      <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                    </svg>
                  </button>
                </div>
                <div class="modal-body" id="mensagem_sistema"></div>
              </div>
            </div>
          </div>
        <?php
        }
        ?>
      </div>
    </div>
  </div>
</div>
<script>
  function hiddenModal(data) {
    if (data == 'boleto') {
      document.querySelector("#form-checkout").hidden = true;
      document.querySelector("#pagar_boleto").hidden = false;
      document.querySelector("#pagar_pix").hidden = true;
    }
    if (data == 'pix') {
      document.querySelector("#form-checkout").hidden = true;
      document.querySelector("#pagar_boleto").hidden = true;
      document.querySelector("#pagar_pix").hidden = false;
    }
    if (data == 'cartao') {
      document.querySelector("#form-checkout").hidden = false;
      document.querySelector("#pagar_boleto").hidden = true;
      document.querySelector("#pagar_pix").hidden = true;
    }
  }
</script>
<script src="https://sdk.mercadopago.com/js/v2"></script>
<!--<script src="lib/js/index.js"></script>-->
<script>
  function validarCpf(cpf) {
    if (!cpf || cpf.length != 11 ||
      cpf == "00000000000" ||
      cpf == "11111111111" ||
      cpf == "22222222222" ||
      cpf == "33333333333" ||
      cpf == "44444444444" ||
      cpf == "55555555555" ||
      cpf == "66666666666" ||
      cpf == "77777777777" ||
      cpf == "88888888888" ||
      cpf == "99999999999") {
      return false
    }
    var soma = 0
    var resto
    for (var i = 1; i <= 9; i++)
      soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11)) resto = 0
    if (resto != parseInt(cpf.substring(9, 10))) {
      return false
    }
    soma = 0
    for (var i = 1; i <= 10; i++)
      soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i)
    resto = (soma * 10) % 11
    if ((resto == 10) || (resto == 11)) resto = 0
    if (resto != parseInt(cpf.substring(10, 11))) {
      return false
    }
    return true
  }

  function validaCnpj(cnpj) {
    if (!cnpj || cnpj.length != 14 ||
      cnpj == "00000000000000" ||
      cnpj == "11111111111111" ||
      cnpj == "22222222222222" ||
      cnpj == "33333333333333" ||
      cnpj == "44444444444444" ||
      cnpj == "55555555555555" ||
      cnpj == "66666666666666" ||
      cnpj == "77777777777777" ||
      cnpj == "88888888888888" ||
      cnpj == "99999999999999") {
      return false
    }
    var tamanho = cnpj.length - 2
    var numeros = cnpj.substring(0, tamanho)
    var digitos = cnpj.substring(tamanho)
    var soma = 0
    var pos = tamanho - 7
    for (var i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--
      if (pos < 2) pos = 9
    }
    var resultado = soma % 11 < 2 ? 0 : 11 - soma % 11
    if (resultado != digitos.charAt(0)) {
      return false;
    }
    tamanho = tamanho + 1
    numeros = cnpj.substring(0, tamanho)
    soma = 0
    pos = tamanho - 7
    for (var i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--
      if (pos < 2) pos = 9
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11
    if (resultado != digitos.charAt(1)) {
      return false
    }
    return true;
  }

  function valida() {
    var error = false
    if (!$('#form-checkout__cardholderName').val()) {
      error = true
      $('#form-checkout__cardholderName').addClass('is-invalid')
      $('#errorCardholderName').text('Este campo é obrigatório')
    } else {
      $('#form-checkout__cardholderName').removeClass('is-invalid')
      $('#errorCardholderName').text('')
    }

    if (!$('#form-checkout__cardholderEmail').val()) {
      error = true
      $('#form-checkout__cardholderEmail').addClass('is-invalid')
      $('#errorCardholderEmail').text('Este campo é obrigatório')
    } else {
      $('#form-checkout__cardholderEmail').removeClass('is-invalid')
      $('#errorCardholderEmail').text('')
    }

    if (!$('#form-checkout__cardNumber').val()) {
      error = true
      $('#form-checkout__cardNumber').addClass('is-invalid')
      $('#errorCardNumber').text('Este campo é obrigatório')
    } else {
      $('#form-checkout__cardNumber').removeClass('is-invalid')
      $('#errorCardNumber').text('')
    }

    if (!$('#form-checkout__cardExpirationMonth').val()) {
      error = true
      $('#form-checkout__cardExpirationMonth').addClass('is-invalid')
      $('#errorCardExpirationMonth').text('Este campo é obrigatório')
    } else {
      $('#form-checkout__cardExpirationMonth').removeClass('is-invalid')
      $('#errorCardExpirationMonth').text('')
    }

    if (!$('#form-checkout__cardExpirationYear').val()) {
      error = true
      $('#form-checkout__cardExpirationYear').addClass('is-invalid')
      $('#errorCardExpirationYear').text('Este campo é obrigatório')
    } else {
      $('#form-checkout__cardExpirationYear').removeClass('is-invalid')
      $('#errorCardExpirationYear').text('')
    }

    if (!$('#form-checkout__securityCode').val()) {
      error = true
      $('#form-checkout__securityCode').addClass('is-invalid')
      $('#errorSecurityCode').text('Este campo é obrigatório')
    } else {
      $('#form-checkout__securityCode').removeClass('is-invalid')
      $('#errorSecurityCode').text('')
    }
    if (!$('#form-checkout__identificationType').val()) {
      $('#form-checkout__identificationType').addClass('is-invalid')
      $('#errorIdentificationType').text('Este campo é obrigatório.')
      error = true
    } else {
      $('#form-checkout__identificationType').removeClass('is-invalid')
      $('#errorIdentificationType').text('')
      if ($('#form-checkout__identificationType').val() == 'CPF') {
        if (validarCpf($('#form-checkout__identificationNumber').val()) == false) {
          $('#form-checkout__identificationNumber').addClass('is-invalid')
          $('#errorIdentificationNumber').text('Insira um CPF válido.')
          error = true
        } else {
          $('#form-checkout__identificationNumber').removeClass('is-invalid')
          $('#errorIdentificationNumber').text('')
        }
      } else if ($('#form-checkout__identificationType').val() == 'CNPJ') {
        if (validaCnpj($('#form-checkout__identificationNumber').val()) == false) {
          $('#form-checkout__identificationNumber').addClass('is-invalid')
          $('#errorIdentificationNumber').text('Insira um CNPJ válido.')
          error = true
        } else {
          $('#form-checkout__identificationNumber').removeClass('is-invalid')
          $('#errorIdentificationNumber').text('')
        }
      }
    }
    if (error) {
      return
    }
  }

  //your public key can be found in https://www.mercadopago.com.br/developers/pt/guides/online-payments/checkout-pro/test-integration
  const KEY = 'APP_USR-335dce27-1954-465c-978e-3260cf327bc1'
  //the new MercadoPago is variable global imported from https://sdk.mercadopago.com/js/v2
  const mp = new MercadoPago(KEY);
  (function(win, doc) {
    console.log(mp.response)
    var valorTotal = String('<?php echo $total; ?>'),
      valorTotal = String((valorTotal.length < 2) ? `${valorTotal}.00` : valorTotal) // valores menores que 2 reais precisam de centavos para validacao. 
    const cardForm = mp.cardForm({
      amount: valorTotal, //value product
      autoMount: true,
      form: {
        id: "form-checkout",
        cardholderName: {
          id: "form-checkout__cardholderName",
          placeholder: "Titular",
        },
        cardholderEmail: {
          id: "form-checkout__cardholderEmail",
          placeholder: "E-mail",
        },
        cardNumber: {
          id: "form-checkout__cardNumber",
          placeholder: "Número do cartão",
        },
        cardExpirationMonth: {
          id: "form-checkout__cardExpirationMonth",
          placeholder: "Ex: 11",
        },
        cardExpirationYear: {
          id: "form-checkout__cardExpirationYear",
          placeholder: "Ex: 2021",
        },
        securityCode: {
          id: "form-checkout__securityCode",
          placeholder: "CVV",
        },
        installments: {
          id: "form-checkout__installments",
          placeholder: "Parcelas",
        },
        identificationType: {
          id: "form-checkout__identificationType",
          placeholder: "Tipo de documento",
        },
        identificationNumber: {
          id: "form-checkout__identificationNumber",
          placeholder: "Número do documento",
        },
        issuer: {
          id: "form-checkout__issuer",
          placeholder: "Banco emissor",
        },
      },
      callbacks: {
        onFormMounted: error => {
          if (error) return console.warn("Form Mounted handling error: ", error);
        },
        onPaymentMethodsReceived: (error, paymentMethods) => {
          if ($('#test')) {
            $('#test').remove()
          }
          if (error) return console.warn('paymentMethods handling error: ', error)
          const span = doc.getElementById('basic-addon1')
          const icon_card = doc.getElementById('icon-card')
          const img = doc.createElement('img')
          icon_card.style.display = "none"
          img.src = paymentMethods[0].thumbnail
          img.style.height = 30
          img.style.width = '50px'
          img.className = "img-thumbnail"
          img.id = 'test'
          span.appendChild(img)
        },
        onCardTokenReceived: (error, token) => {
          if (error) return console.warn('Token handling error: ', error)
          console.log('Token available: ', token)
        },
        onSubmit: event => {
          event.preventDefault();
          const {
            paymentMethodId: payment_method_id,
            issuerId: issuer_id,
            cardholderEmail: email,
            amount,
            token,
            installments,
            identificationNumber,
            identificationType,
          } = cardForm.getCardFormData();

          //Pagamento via cartão
          fetch("<?php echo $baseurl; ?>/MercadoPago/controllers/paymentController.php", {
              method: "POST",
              headers: {
                "Content-Type": "application/json",
              },
              body: JSON.stringify({
                token,
                issuer_id,
                payment_method_id,
                transaction_amount: Number(amount),
                installments: Number(installments),
                description: "Pesquisa de Mercado ManangerShelf.",
                payer: {
                  email,
                  identification: {
                    type: identificationType,
                    number: identificationNumber,
                  },
                },
              }),
            }).then(response => response.text())
            .then((result) => {
              response = JSON.parse(result)
              if (response.status == 'Em processamento') {
                document.getElementById('mensagem_sistema').innerHTML = response.mensagem
                document.getElementById('modal-btn').click()
                $.ajax({
                  url: "<?php echo $baseurl; ?>/index.php/do_paymentCredit",
                  type: 'POST',
                  data: {
                    id: '<?php echo $id_transacao ?>',
                    id_transacao: response.id,
                    payment_method: 'Crédito',
                  },
                  dataType: "application/json"
                }).done(function(response) {
                  $("#loadBoleto").addClass("visually-hidden")
                }).fail(function(jqXHR, textStatus) {
                  $("#loadBoleto").addClass("visually-hidden")
                  alert("Houve um erro ao salvar seu pedido, entre em contato com nossa equipe de suporte ManagerShelf para atualizar as informacoes do seu pedido."); //Apenas em casos extremos este alerta sera utilizado (O pagamentro esta criado, porem nao sera salvo no banco)
                });
              } else if (response.status == 'error') {
                document.getElementById('mensagem_sistema').innerHTML = response.mensagem
                document.getElementById('modal-btn').click()
              }
            })
            .catch(error => {
              console.log('error', error)
              document.getElementById('modal-btn').click()
            });
        },
      },
    });
  })(window, document)
</script>
<script>
  function gerarBoleto() {

    <?php if ($total == 1) {
      $amount = 5;
    } else {
      $amount = $total;
    } ?>
    if (<?php echo $amount; ?> < 5) {
      document.getElementById('mensagem_sistema').innerHTML = '<? echo $amount; ?> O valor minimo para pagamentos por boleto é de <b>R$5.00</b>.'
      document.getElementById('modal-btn').click()

    } else {

      var email = $('#emailBol').val();
      var first_name = $('#firstNameBol').val();
      var last_name = $('#lastNameBol').val();

      var identificationType = $('#typeDocBol').val();
      var identificationNumber = $('#docNumberBol').val();
      var error = false
      if (!email) {

        $('#emailBol').addClass('is-invalid')
        $('#errorEmailBol').text('Este campo é obrigatório.')
        error = true
      } else if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email) == false) {
        $('#emailBol').addClass('is-invalid')
        $('#errorEmailBol').text('Insira um email válido.')
        error = true
      } else {
        $('#emailBol').removeClass('is-invalid')
        $('#errorEmailBol').text('')
      }
      if (!first_name) {
        $('#firstNameBol').addClass('is-invalid')
        $('#errorFirstNameBol').text('Este campo é obrigatório.')
        error = true
      } else {
        $('#firstNameBol').removeClass('is-invalid')
        $('#errorFirstNameBol').text('')
      }
      if (!last_name) {
        $('#lastNameBol').addClass('is-invalid')
        $('#errorLastNameBol').text('Este campo é obrigatório.')
        error = true
      } else {
        $('#lastNameBol').removeClass('is-invalid')
        $('#errorLastNameBol').text('')
      }
      if (!identificationType) {
        $('#typeDocBol').addClass('is-invalid')
        $('#errorTypeDocBol').text('Este campo é obrigatório.')
        error = true
      } else {
        $('#typeDocBol').removeClass('is-invalid')
        $('#errorTypeDocBol').text('')
        if (identificationType == 'cpf') {
          if (validarCpf(identificationNumber) == false) {
            $('#docNumberBol').addClass('is-invalid')
            $('#errorDocNumberBol').text('Insira um CPF válido.')
            error = true
          } else {
            $('#docNumberBol').removeClass('is-invalid')
            $('#errorDocNumberBol').text('')

          }
        } else if (identificationType == 'cnpj') {
          if (validaCnpj(identificationNumber) == false) {
            $('#docNumberBol').addClass('is-invalid')
            $('#errorDocNumberBol').text('Insira um CNPJ válido.')
            error = true
          } else {
            $('#docNumberBol').removeClass('is-invalid')
            $('#errorDocNumberBol').text('')
          }
        }
      }
      if (error) {
        return
      }
      $("#loadBoleto").removeClass("visually-hidden")
      var amount = "<?php echo $amount; ?>";

      //Pagamento via boleto
      fetch("<?php echo $baseurl; ?>/MercadoPago/controllers/boletoController.php", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify({
            transaction_amount: Number(amount),
            description: "Pesquisa de mercado ManangerShelf",
            payer: {
              email,
              first_name,
              last_name,
              identification: {
                type: identificationType,
                number: identificationNumber,
              },
            },
          }),
        })
        .then(response => response.text())
        .then((result) => {
          response = JSON.parse(result)
          if (response.status == 'Aguardando Pagamento') {
            document.getElementById('mensagem_sistema').innerHTML = response.mensagem
            document.getElementById('modal-btn').click()
            $.ajax({
              url: "<?php echo $baseurl; ?>/index.php/do_payment",
              type: 'POST',
              data: {
                id: '<?php echo $id_transacao; ?>',
                id_transacao: response.id,
                method: 'Boleto',
                url_boleto: response.boleto,
              },
              dataType: "application/json"
            }).done(function(response) {
              $("#loadBoleto").addClass("visually-hidden")
            }).fail(function(jqXHR, textStatus) {
              $("#loadBoleto").addClass("visually-hidden")
              alert("Houve um erro ao salvar seu pedido, entre em contato com nossa equipe de suporte ManagerShelf."); //Apenas em casos extremos este alerta sera utilizado (O pagamentro esta criado, porem nao sera salvo no banco)
            });
          } else if (response.status == 'error') {
            document.getElementById('mensagem_sistema').innerHTML = response.mensagem
            document.getElementById('modal-btn').click()
          }

        })
        .catch(error => {
          console.log('error', error)
          document.getElementById('mensagem_sistema').innerHTML = "<p>Ocorreu um erro ao realizar o pagamento.<br> Por Favor, entre em contato com nossa equipe ManagerShelf.</p>"
          document.getElementById('modal-btn').click()
        });
    }
  }
</script>
<script>
  function gerarPix() {

    var email = $('#emailPix').val();
    var first_name = $('#firstNamePix').val();
    var last_name = $('#lastNamePix').val();

    var error = false
    if (!email) {
      $('#emailPix').addClass('is-invalid')
      $('#errorEmailPix').text('Este campo é obrigatório.')
      error = true
    } else if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(email) == false) {
      $('#emailPix').addClass('is-invalid')
      $('#errorEmailPix').text('Insira um email válido.')
      error = true
    } else {
      $('#emailPix').removeClass('is-invalid')
      $('#errorEmailPix').text('')
    }
    if (!first_name) {
      $('#firstNamePix').addClass('is-invalid')
      $('#errorFirstNamePix').text('Este campo é obrigatório.')
      error = true
    } else {
      $('#firstNamePix').removeClass('is-invalid')
      $('#errorFirstNamePix').text('')
    }
    if (!last_name) {
      $('#lastNamePix').addClass('is-invalid')
      $('#errorLastNamePix').text('Este campo é obrigatório.')
      error = true
    } else {
      $('#lastNamePix').removeClass('is-invalid')
      $('#errorLastNamePix').text('')
    }
    if (error) {
      return
    }
    $("#loadQrCode").removeClass("visually-hidden")
    var amount = "<?php echo $amount; ?>";

    //Pagamento via boleto
    fetch("<?php echo $baseurl; ?>/MercadoPago/controllers/pixController.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          transaction_amount: Number(amount),
          description: "Pesquisa de mercado ManangerShelf",
          payer: {
            email,
            first_name,
            last_name
          },
        }),
      })
      .then(response => response.text())
      .then((result) => {
        response = JSON.parse(result)
        console.log(response)
        document.getElementById('mensagem_sistema').innerHTML = response.mensagem
        document.getElementById('modal-btn').click() /
          $.ajax({
            url: "<?php echo $baseurl; ?>/index.php/do_payment",
            type: 'POST',
            data: {
              id: '<?php echo $id_transacao; ?>',
              id_transacao: response.transaction_id,
              method: 'Pix',
            },
            dataType: "application/json"
          }).done(function(response) {
            $("#loadQrCode").addClass("visually-hidden")
          }).fail(function(jqXHR, textStatus) {
            $("#loadQrCode").addClass("visually-hidden")
            // alert("Houve um erro ao salvar seu pedido, entre em contato com nossa equipe de suporte ManagerShelf."); //Apenas em casos extremos este alerta sera utilizado (O pagamentro esta criado, porem nao sera salvo no banco)
          });
      })
      .catch(error => {
        $("#loadQrCode").addClass("visually-hidden")
        console.log('error', error)
        document.getElementById('mensagem_sistema').innerHTML = "<p>Ocorreu um erro ao gerar o pix.<br> Por Favor, entre em contato com nossa equipe ManagerShelf ou tente outra forma de pagamento.</p>"
        document.getElementById('modal-btn').click()
      });
  }

  function copiarChavePix(chavePix) {
    var content = document.getElementById('chave_pix');
    var botao_pix = document.querySelector("#botao_copiar_pix");
    console.log(botao_pix)
    content.select();
    document.execCommand('copy');
    botao_pix.textContent = 'Chave Copiada!';
  }
</script>
</body>
