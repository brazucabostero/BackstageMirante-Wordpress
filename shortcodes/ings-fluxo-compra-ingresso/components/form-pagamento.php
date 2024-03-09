<?php

/**
 * @var $args
 */

defined('ABSPATH') || exit();

$transaction = $args['transaction'];
$user = $args['user'];
$userAddress = $user->address;

// print_r($user);
// print_r($transaction);

?>
<?php if (isset($transaction->responseError->code) && $transaction->responseError->code == '2029') : ?>
  <div class="container text-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-emoji-frown-fill text-white" viewBox="0 0 16 16">
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm-2.715 5.933a.5.5 0 0 1-.183-.683A4.498 4.498 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.498 3.498 0 0 0 8 10.5a3.498 3.498 0 0 0-3.032 1.75.5.5 0 0 1-.683.183zM10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8z" />
    </svg>
    <h3 class="my-4">
      Usuário não autenticado. <br>
      Por favor, tente novamente.
    </h3>
  </div>
<?php elseif (isset($transaction->responseError->code) && $transaction->responseError->code == '6027') : ?>
  <div class="container text-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-emoji-frown-fill text-white" viewBox="0 0 16 16">
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm-2.715 5.933a.5.5 0 0 1-.183-.683A4.498 4.498 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.498 3.498 0 0 0 8 10.5a3.498 3.498 0 0 0-3.032 1.75.5.5 0 0 1-.683.183zM10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8z" />
    </svg>
    <h3 class="my-4">
      Você excedeu o limite de ingressos disponíveis por conta. <br>
      Para mais informações, verifique a descrição do evento.
    </h3>
  </div>

  <script>
    jQuery(".ings-resume").empty();
  </script>
<?php else : ?>
  <?php
  // print_r($transaction);
  $transactionData = $transaction->responseData->data;
  ?>
  <!-- COMPONENTE PARA FLUXO DE PAGAMENTO -->
  <section id="ings-form-pagamento" class="ings-payment-type <?= !user_has_address($user) ? "d-none" : "" ?>">
    <div class="container">
      <div class="row">
        <article class="col-md-12">
          <h3>Meio de pagamento</h3>
          <ul class="ings-payment-type-tabs" id="ings-payment" role="tablist">
            <?php if (!empty($transactionData->availablePaymentMethods->CartaoCredito)) : ?>
              <li class="nav-item" role="presentation" style="border: 1px solid #7A7A7A; border-radius: 4px;">
                <button class="active form-pag" id="card-credit" data-bs-toggle="tab" data-bs-target="#card-credit-pane" type="button" role="tab" aria-controls="card-credit-pane" aria-selected="true">
                  Crédito
                </button>
              </li>
            <?php endif; ?>
            <?php if (!empty($transactionData->availablePaymentMethods->pix) && $user->identity->type->name !== "Id internacional") : ?>
              <li class="nav-item" role="presentation" style="border: 1px solid #7A7A7A; border-radius: 4px;">
                <button class="nav-link form-pag" id="pix" data-bs-toggle="tab" data-bs-target="#pix-pane" type="button" role="tab" aria-controls="pix-pane" aria-selected="false">
                  Pix
                </button>
              </li>
            <?php endif; ?>
          </ul>
          <div class="tab-content" id="ings-payment-content">
            <?php if (!empty($transactionData->availablePaymentMethods->CartaoCredito)) : ?>
              <div class="tab-pane fade show active" id="card-credit-pane" role="tabpanel" aria-labelledby="card-credit" tabindex="0">
                <span class="text-black-70">Quais os dados do seu cartão de crédito?</span>
                <ul class="credit-flags">
                  <li>
                    <img src="<?= get_stylesheet_directory_uri(); ?>/img/amex.svg" />
                  </li>
                  <li>
                    <img src="<?= get_stylesheet_directory_uri(); ?>/img/diners.svg" />
                  </li>
                  <li>
                    <img src="<?= get_stylesheet_directory_uri(); ?>/img/discover.svg" />
                  </li>
                  <li>
                    <img src="<?= get_stylesheet_directory_uri(); ?>/img/elo.svg" />
                  </li>
                  <li>
                    <img src="<?= get_stylesheet_directory_uri(); ?>/img/hipercard.svg" />
                  </li>
                  <li>
                    <img src="<?= get_stylesheet_directory_uri(); ?>/img/jcb.svg" />
                  </li>
                  <li>
                    <img src="<?= get_stylesheet_directory_uri(); ?>/img/mastercard.svg" />
                  </li>
                  <li>
                    <img src="<?= get_stylesheet_directory_uri(); ?>/img/visa.svg" />
                  </li>
                </ul>
                <form id="form-card-credit" class="form-card-credit">
                  <ul>
                    <li>
                      <label>Número do cartão</label>
                      <input id="customer_numero_cartao" class="event_get_capture_device" type="text" name="customer_numero_cartao" placeholder="__ __ __ __">
                      <p id="customer_numero_cartao_feedback" class="text-danger">
                    </li>
                    <li>
                      <label>Validade</label>
                      <input id="customer_validade_cartao" class="event_get_capture_device" placeholder="__/__" name="customer_validade_cartao">
                    </li>
                    <li>
                      <label>Cód. Segurança</label>
                      <input id="customer_cvv_cartao" type="text" placeholder="CVV" name="customer_cvv_cartao">
                    </li>
                    <li>
                      <label>Nome impresso no cartão</label>
                      <input id="customer_nome_cartao" type="text" name="customer_nome_cartao" placeholder="João M Santos">
                    </li>
                    <li>
                      <label>Cpf</label>
                      <input id="customer_cpf" type="text" placeholder="_____-___" name="customer_cpf">
                    </li>
                    <li>
                      <label>Nascimento</label>
                      <input id="customer_nascimento" type="date" name="customer_nascimento" placeholder="__/__/____">
                    </li>
                    <li>
                      <label>Parcelamento</label>
                      <select id="customer_cartao_parcelas" name="customer_cartao_parcelas">
                        <?php
                        foreach ($transactionData->availablePaymentMethods->CartaoCredito->installments as $parcela) :
                          $juros = ($parcela->value != $parcela->total) ? '(com juros)' : '(sem juros)';
                        ?>
                          <option value="<?= $parcela->quantity ?>" data-taxa-cartao="<?= $parcela->taxValue; ?>" data-total-cartao="<?= $parcela->total; ?>">
                            <?= $parcela->quantity ?>x de R$ <?= format_price_to_brl($parcela->value) ?> <?= $juros ?>
                          </option>
                        <?php endforeach; ?>
                      </select>
                    </li>
                  </ul>
                  <input type="hidden" name="type" value="CartaoCredito">
                  <input type="hidden" name="tds_reference_id" id="tds_reference_id" value="">
                  <input type="hidden" name="totalCartao" value="<?= $arrayCartao[0]['total']; ?>">
                  <!-- <a class="btn-salvar" href="#">
                    Salvar
                  </a> -->
                </form>
                <!-- <div class="one-click-buy">
                  <div>
                    <h4>COMPRA COM 1-CLIQUE</h4>
                    <span>Ative e na próxima vez, compre sem preencher os dados</span>
                  </div>
                  <div>
                    <div class="form-check form-switch">
                      <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault" checked>
                    </div>
                  </div>
                </div>
                <span class="span.text-white-70">
                  Podemos fazer uma cobrança de baixo valor durante a verificação do seu cartão. A mesma será estornada automaticamente.
                </span> -->
              </div>
            <?php endif; ?>
            <?php if (!empty($transactionData->availablePaymentMethods->pix) && $user->identity->type->name !== "Id internacional") :
              $arrayPix = json_decode(json_encode($transactionData->availablePaymentMethods->pix->installments), true);
            ?>
              <div class="tab-pane fade" id="pix-pane" role="tabpanel" aria-labelledby="pix" tabindex="0">
                <form id="form-pix">
                  <label for="customer_cpf">CPF:</label> <br>
                  <input style="background-color: aliceblue; color: black;" id="customer_cpf" type="text" placeholder="000.000.000-00" name="customer_cpf">
                  <input type="hidden" name="type" value="pix">
                  <input type="hidden" name="totalPix" value="<?= $arrayPix[0]['total']; ?>">
                </form>
              </div>
            <?php endif; ?>
          </div>
        </article>
      </div>
    </div>
  </section>

  <!-- COMPONENTE ENDEREÇO DE COBRANÇA -->
  <section class="ings-payment-adress <?= !user_has_address($user) ? "d-none" : "" ?>">
    <div class="container">
      <div class="row">
        <article class="col-md-12">
          <h3>Endereço de cobrança</h3>
          <span class="text-black-70">Precisamos do seu endereço apenas para garantir a segurança do seu pagamento</span>
        </article>
      </div>
      <div class="row">
        <article class="col-md-12">
          <div class="current-adress">
            <span class="d-block">
              Rua: <?= $userAddress->street ?> <?= !empty($userAddress->complement) ? "-" : "" ?> <?= $userAddress->complement ?>
            </span>
            <span class="d-block">
              <?= $userAddress->city ?> - <?= $userAddress->state ?> / CEP: <?= $userAddress->zipcode ?> / Tel: <?= $user->phone ?>
            </span>
            <button id="ings-change-customer-address" class="btn btn-link">
              ALTERAR ENDEREÇO
            </button>
          </div>
        </article>
      </div>
    </div>
  </section>
  <iframe name="device_capture" height="0" width="0"></iframe>
  <form action="#" method="POST" target="device_capture" id="form">
    <input type="hidden" id="access_token" name="JWT">
  </form>
<?php 
  $userJson     = stripslashes($_COOKIE['wp_ing_user']);
  $userLogged   = json_decode($userJson, true);
  $ingresse_api = new IngresseAPI();
?>
  <script>
    jQuery(".event_get_capture_device").on("blur",function(){
      const numberCard      = jQuery("#customer_numero_cartao").val().split(" ").join("");
      const monthExpirate   = jQuery("#customer_validade_cartao").val().split("/")[0];
      const yearExpirate    = jQuery("#customer_validade_cartao").val().split("/")[1];
      
      if( monthExpirate !== "" && yearExpirate !== ""  && numberCard !== "" ){
        const dateExpiration  = monthExpirate+"/20"+yearExpirate;
        (async () => {
          const rawResponse = await fetch("https://api.ingresse.com/shop/<?=$transactionData->transactionId?>/device-capture?publickey=<?=$ingresse_api::API_KEY?>&userToken=<?=$userLogged['token']?>", {
            method: 'POST',
            headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
            },
            body: JSON.stringify({creditCard: {number: numberCard, expiration: dateExpiration}})
          }).then((response) => {
            return response.json();
          }).then( responseDeviceCapture => {
            const dataDeviceCapture     = responseDeviceCapture.responseData.device_capture;
            jQuery("#form").attr("action",dataDeviceCapture.url);
            jQuery("#access_token").val(dataDeviceCapture.access_token);
            jQuery("#form").submit();
          });
        })();
      }
    });

    window.addEventListener("message", (event) => {
      if(event.origin.startsWith("https://centinelapi") ){
        const dataResponse    = JSON.parse(event.data);
        const tdsReferenceId  = dataResponse.SessionId;
        jQuery("#tds_reference_id").val(tdsReferenceId);
      }
    }, false);

    function validatePaymentsFields() {
      const paymentMethod = jQuery('.nav-item[role=presentation]').find('button.active').first().attr('id');
      let isValid = true;

      jQuery(`#form-${paymentMethod}`).find('input[id]').each(function() {
        if (jQuery(this).val() === '') {
          isValid = false;
        }
      });

      if (paymentMethod === 'credit-card') {
        if (jQuery('#customer_numero_cartao_feedback').html().replace(' ', '').length > 0) {
          isValid = false;
        }
      }

      jQuery('#ings-form-customer-data').find('input[name]').each(function() {
        if (jQuery(this).val() === '' && jQuery(this).attr('name') !== 'complemento') {
          isValid = false;
        }
      });

      if (isValid) {
        jQuery('#btn-payment').removeClass('disabled');
      } else {
        jQuery('#btn-payment').addClass('disabled');
      }
    }

    (() => {
      validatePaymentsFields();

      jQuery('[name=customer_cpf]').mask('000.000.000-00');

      jQuery('.form-pag').on('click', e => {
        var type = e.target.id;
        var valorTotal = 0;
        if (type == 'pix') {
          jQuery('.esp-tax-process').hide();
          valorTotal = jQuery("[name=totalPix]").val();
          jQuery('#valor-total-cartao').html(`R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(valorTotal)}`);
          jQuery('#btn-payment').html(`PAGAR R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(valorTotal)}`);
        } else {
          jQuery('.esp-tax-process').show();
          valorTotal = jQuery("[name=totalCartao]").val();
          jQuery('#valor-total-cartao').html(`R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(valorTotal)}`);
          jQuery('#btn-payment').html(`PAGAR R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(valorTotal)}`);
        }
        validatePaymentsFields();
      });

      jQuery('#ings-change-customer-address').on('click', () => {
        jQuery('#ings-customer-data').removeClass('d-none');
        jQuery('#ings-form-pagamento').addClass('d-none');
        jQuery('.ings-payment-adress').addClass('d-none');
      });

      jQuery('#ings-form-pagamento').find('input[id]').on('change', validatePaymentsFields);
    })();
  </script>
  <?php if (!empty($transactionData->availablePaymentMethods->CartaoCredito)) : ?>
    <script>
      function initSelect() {
        try {
          var initTaxa = jQuery("#customer_cartao_parcelas option[value='1']").attr('data-taxa-cartao')
          var initTotal = jQuery("#customer_cartao_parcelas option[value='1']").attr('data-total-cartao')

          jQuery('#taxa-processamento-cartao').html(`R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(initTaxa)}`);
          jQuery('#valor-total-cartao').html(`R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(initTotal)}`);
          jQuery('#btn-payment').html(`PAGAR R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(initTotal)}`);
        } catch (e) {}
      }

      (() => {
        initSelect();

        jQuery('#customer_numero_cartao').validateCreditCard(function(result) {
          if (result.valid) {
            jQuery('#btn-payment').removeClass('disabled');
            jQuery('#customer_numero_cartao_feedback').html('');
          } else {
            jQuery('#btn-payment').addClass('disabled');
            jQuery('#customer_numero_cartao_feedback').html('Número de cartão inválido');
          }
          validatePaymentsFields();
        })

        jQuery('#customer_validade_cartao').mask('00/00');
        jQuery('#customer_cvv_cartao').mask('0000');
        jQuery('#customer_numero_cartao').mask('0000 0000 0000 0000');

        // regrar de parcelamentos
        jQuery('#customer_cartao_parcelas').on('change', e => {
          var taxaCartao = e.target.selectedOptions[0].attributes['data-taxa-cartao'].value;
          var totalIngressosCartao = e.target.selectedOptions[0].attributes['data-total-cartao'].value;

          jQuery('#taxa-processamento-cartao').html(`R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(taxaCartao)}`);
          jQuery('#valor-total-cartao').html(`R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(totalIngressosCartao)}`);
          jQuery('#btn-payment').html(`PAGAR R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(totalIngressosCartao)}`);
        });
      })()
    </script>

  <?php endif; ?>
<?php endif; ?>