<?php

/**
 * @var $payment_response
 * @var $request
 * @var $userLogged
 */
?>

<div class="container">
  <?php if ($payment_response->responseError->code == '6014') : ?>
    <div class="row text-center">
      <svg style="height: 110px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
      </svg>
      <h3>O Limite de tickets por clientes deste evento foi excedido.</h3>
    </div>
  <?php else : ?>

    <div class="row text-center" style="color: black;">
      <h3>Ingressos reservados. Aguardando o pagamento</h3>
      <h5>Para finalizar a compra, faça o pagamento via PIX abaixo.</h5>
    </div>
    <div class="row text-center">
      <p>Você receberá também uma confirmação no e-mail: <?= $userLogged['email'] ?></p>
    </div>
    <div class="card" style="color: black;">
      <div class="card-body">
        <div class="row">
          <div class="col">
            <h3>Escaneie este código para pagar</h3>
            <ol>
              <li>Abra seu Internet Banking ou App de Pagamentos</li>
              <li>Escolha a opção de “pagar via PIX”</li>
              <li>Escaneie o código ao lado:</li>
            </ol>
            <p><b>Você tem 30 minutos para efetuar o pagamento.</b></p>
            <p>Após o pagamento, os ingressos comprados aparecerão na sua conta em “Meus Ingressos”.</p>
          </div>
          <div class="col text-center">
            <img src="<?= $payment_response->responseData->data->qrcode->image ?>" alt="">
          </div>
        </div>
        <hr>
        <div class="row" style="color: black;">
          <h3>Ou copie esse código QR para fazer o pagamento</h3>
          <p>Escolha pagar via PIX pelo seu Internet Banking ou App de Pagamentos.Depois cole o seguinte código:</p>
          <div class="input-group mb-3">
            <input type="text" id="pix-key" class="form-control" aria-describedby="basic-addon2" value="<?= $payment_response->responseData->data->qrcode->url ?>" disabled>
            <button class="btn btn-primary" type="button" id="button-addon2" onclick="copyToClipboard()">Copiar</button>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <p class="text-center">
        Caso o pagamento não seja realizado no tempo estipulado, sua compra será cancelada.
      </p>
      <p id="pix-return-notice" class="text-center text-danger"></p>
      <button id="btn-pix-paid" class="btn btn-primary btn-lg">
        JÁ FIZ O PAGAMENTO VIA PIX
      </button>
    </div>
    <div id="pix-toast" class="toast align-items-center fixed-bottom start-50" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="d-flex">
        <div class="toast-body text-white text-center">
          Pix copiado!
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
      </div>
    </div>
    <script>
      function copyToClipboard() {
        let pixKey = jQuery('#pix-key').val()
        navigator.clipboard.writeText(pixKey);
        jQuery('#pix-toast').toast('show');
      }

      (() => {
        jQuery('#btn-payment').addClass('d-none');
        jQuery('#btn-pix-paid').on('click', e => {
          jQuery.ajax({
            type: "get",
            url: my_ajax_object.ajax_url,
            data: {
              action: "ings_check_pix_was_paid"
            },
            success: function(response) {
              jQuery('#ings-main-content').addClass('d-none');
              jQuery('#ings-payment-result').html(response);
              jQuery('#ings-payment-result').removeClass('d-none');
            },
            error: function(jqXhr, textStatus, response) {
              jQuery('#pix-return-notice').html(jqXhr.responseText)
            }
          });
        });
        jQuery('#btn-cancelar').off('click', handleModalExitWithCancelTransaction);
        jQuery('#btn-cancelar').on('click', handleModalExit);
      })()
    </script>
  <?php endif; ?>
</div>