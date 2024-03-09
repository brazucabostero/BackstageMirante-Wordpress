<?php
/**
 * @var $transaction
 */
?>
<?php if (isset($transaction->responseError->code) && $transaction->responseError->code == '2029'): ?>
  <div class="container text-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-emoji-frown-fill text-white" viewBox="0 0 16 16">
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm-2.715 5.933a.5.5 0 0 1-.183-.683A4.498 4.498 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.498 3.498 0 0 0 8 10.5a3.498 3.498 0 0 0-3.032 1.75.5.5 0 0 1-.683.183zM10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8z"/>
    </svg>
    <h3 class="my-4">
      Usuário não autenticado. <br>
      Por favor, tente novamente.
    </h3>
  </div>
<?php elseif (isset($transaction->responseError->code) && $transaction->responseError->code == '6027'): ?>
  <div class="container text-center">
    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" class="bi bi-emoji-frown-fill text-white" viewBox="0 0 16 16">
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zM7 6.5C7 7.328 6.552 8 6 8s-1-.672-1-1.5S5.448 5 6 5s1 .672 1 1.5zm-2.715 5.933a.5.5 0 0 1-.183-.683A4.498 4.498 0 0 1 8 9.5a4.5 4.5 0 0 1 3.898 2.25.5.5 0 0 1-.866.5A3.498 3.498 0 0 0 8 10.5a3.498 3.498 0 0 0-3.032 1.75.5.5 0 0 1-.683.183zM10 8c-.552 0-1-.672-1-1.5S9.448 5 10 5s1 .672 1 1.5S10.552 8 10 8z"/>
    </svg>
    <h3 class="my-4">
      Você excedeu o limite de ingressos disponíveis por conta. <br>
      Para mais informações, verifique a descrição do evento.
    </h3>
  </div>
<?php else: ?>
  <?php $transactionData = $transaction->responseData->data; ?>
  <div class="container">
    <div class="accordion accordion-flush" id="accordionExample">
      <?php if (!empty($transactionData->availablePaymentMethods->pix)): ?>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne"
                    aria-expanded="true" aria-controls="flush-collapseOne">
              PIX
            </button>
          </h2>
          <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
            <div class="accordion-body p-3">
              <form class="pix-form" id="pix_form">
                <label class="form-label" for="customer_cpf">CPF:</label> <br>
                <input id="customer_cpf" class="form-control my-2" type="text" placeholder="000.000.000-00"
                       name="customer_cpf">
                <input type="hidden" name="type" value="pix">
                <input type="hidden" name="action" value="ings_pay_transaction">
                <button type="submit" class="btn btn-light rounded-pill">Enviar</button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
      <?php if (!empty($transactionData->availablePaymentMethods->CartaoCredito)): ?>
        <div class="accordion-item">
          <h2 class="accordion-header">
            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
              Cartão de crédito
            </button>
          </h2>
          <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
            <div class="accordion-body p-3">
              <form id="cartao_credito_form">
                <div class="row">
                  <div class="col">
                    <label class="form-label" for="customer_numero_cartao">Número do cartão:</label> <br>
                    <input id="customer_numero_cartao" class="form-control my-2" type="text"
                           name="customer_numero_cartao">
                    <p id="customer_numero_cartao_feedback" class="text-danger">
                    </p>
                  </div>
                  <div class="col">
                    <label class="form-label" for="customer_validade_cartao">Validade (mm/aa)</label> <br>
                    <input id="customer_validade_cartao" class="form-control my-2" type="text" placeholder="11/29"
                           name="customer_validade_cartao">
                  </div>
                  <div class="col">
                    <label class="form-label" for="customer_cvv_cartao">CVV:</label> <br>
                    <input id="customer_cvv_cartao" class="form-control my-2" type="text" placeholder="123"
                           name="customer_cvv_cartao">
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label class="form-label" for="customer_nome_cartao">Nome do titular do cartão:</label> <br>
                    <input id="customer_nome_cartao" class="form-control my-2" type="text" name="customer_nome_cartao">
                  </div>
                  <div class="col">
                    <label class="form-label" for="customer_cpf">CPF:</label> <br>
                    <input id="customer_cpf" class="form-control my-2" type="text" placeholder="000.000.000-00"
                           name="customer_cpf">
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    <label class="form-label" for="customer_nascimento">Data de dascimento:</label> <br>
                    <input id="customer_nascimento" class="form-control my-2" type="date" name="customer_nascimento">
                  </div>
                  <div class="col">
                    <label class="form-label" for="customer_cartao_parcelas">Quantidade de parcelas:</label> <br>
                    <select id="customer_cartao_parcelas" class="form-select my-2" type="text"
                            name="customer_cartao_parcelas">
                      <?php foreach ($transactionData->availablePaymentMethods->CartaoCredito->installments as $parcela): ?>
                        <option value="<?= $parcela->quantity ?>"><?= $parcela->quantity ?>x de
                          R$ <?= format_price_to_brl($parcela->value) ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
                <input type="hidden" name="type" value="CartaoCredito">
                <input type="hidden" name="action" value="ings_pay_transaction">
                <button type="submit" class="btn btn-light rounded-pill">Enviar</button>
              </form>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
  <script src="<?= get_stylesheet_directory_uri() . '/lib/js/jquery.creditCardValidator.js' ?>"></script>
  <script>
    (() => {
      jQuery('#customer_numero_cartao').validateCreditCard(function (result) {
        if (result.valid) {
          jQuery('#cartao_credito_form').find('[type=submit]').first().attr('disabled', false);
          jQuery('#customer_numero_cartao_feedback').html('');
        } else {
          jQuery('#cartao_credito_form').find('[type=submit]').first().attr('disabled', true);
          jQuery('#customer_numero_cartao_feedback').html('Número de cartão inválido');
        }
      })

      function handleSubmitPaymentMathod(e) {
        e.preventDefault();
        let form = jQuery(e.target);
        jQuery.ajax({
          type: "post",
          url: my_ajax_object.ajax_url,
          data: form.serialize(),
          success: function (response) {
            jQuery('#modal-body-fluxo-compra').html(response);
          }
        });
      }

      jQuery('#cartao_credito_form').on('submit', handleSubmitPaymentMathod)
      jQuery('#boleto_form').on('submit', handleSubmitPaymentMathod)
      jQuery('#pix_form').on('submit', handleSubmitPaymentMathod)
      jQuery('#customer_validade_cartao').mask('00/00');
      jQuery('#customer_cvv_cartao').mask('0000');
      jQuery('#customer_numero_cartao').mask('0000000000000000000');
      jQuery('[name=customer_cpf]').mask('000.000.000-00');

      window.addEventListener('beforeunload', handleWindowExit);
    })()
  </script>
<?php endif; ?>