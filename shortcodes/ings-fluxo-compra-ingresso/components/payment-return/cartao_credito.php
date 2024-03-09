<?php

/**
 * @var $payment_response
 */
?>

<div class="container">
  <?php if ($payment_response->responseData->data->status === 'declined' || empty($payment_response)) : ?>
    <div class="row text-center">
      <svg style="height: 110px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
      </svg>
      <h3>Pagamento recusado</h3>
    </div>
    <!-- <div class="row m-2 justify-content-center">
      <div class="col-4 text-center">
        <button class="btn btn-light btn-large rounded-pill" onclick="tryPaymentAgain()">
          Tentar com outro cartão
        </button>
      </div>
    </div> -->
  <?php elseif ($payment_response->responseError->code == '1031') : ?>
    <div class="row text-center">
      <svg style="height: 110px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
      </svg>
      <h3>Pagamento recusado</h3>
    </div>
    <!-- <div class="row m-2 justify-content-center">
      <div class="col-4 text-center">
        <button class="btn btn-light btn-large rounded-pill" onclick="tryPaymentAgain()">
          Tentar com outro cartão
        </button>
      </div>
    </div> -->
  <?php elseif ($payment_response->responseError->code == '6044') : ?>
    <div class="row text-center">
      <i class="fas text-warning fa-exclamation-triangle fa-8x"></i>
      <h3 class="mt-3">Você excedeu o limite de ingressos disponíveis por conta</h3>
      <h5>
        Para mais informações, verifique a descrição do evento. (#6044)
      </h5>
    </div>
    <!-- <div class="row m-2 justify-content-center">
      <div class="col-4 text-center">
        <button class="btn btn-light btn-large rounded-pill" onclick="tryPaymentAgain()">
          Tentar com outro cartão
        </button>
      </div>
    </div> -->
  <?php elseif ($payment_response->responseError->code == '3023') : ?>
    <div class="row text-center">
      <i class="fas text-warning fa-exclamation-triangle fa-8x"></i>
      <h3 class="mt-3">Transação não realizada</h3>
      <h5>
        Sua sessão de compra expirou. Por favor, refaça o processo de compra. (#3023)
      </h5>
    </div>
    <!-- <div class="row m-2 justify-content-center">
      <div class="col-4 text-center">
        <button class="btn btn-light btn-large rounded-pill" onclick="tryPaymentAgain()">
          Tentar com outro cartão
        </button>
      </div>
    </div> -->
  <?php elseif ($payment_response->responseData->data->status === 'manual review') : ?>
    <div class="row text-center">
      <i class="fas text-warning fa-exclamation-triangle fa-8x"></i>
      <h3 class="mt-3">
        Compra em Análise
      </h3>
    </div>
    <script>
      (() => {
        jQuery('#btn-payment').attr('href', window.location.href.replace('?ingressos=true', '') + '?ingressos=true');
        jQuery('#btn-payment').html("Comprar mais ingressos");
        jQuery('#btn-payment').removeClass('d-none');
        //window.removeEventListener('beforeunload', handleWindowExit);
        jQuery('#btn-cancelar').off('click', handleModalExitWithCancelTransaction);
        jQuery('#btn-cancelar').on('click', handleModalExit);
      })();
    </script>
  <?php elseif ($payment_response->responseData->data->status === 'approved') : ?>
    <div class="row text-center p-4 rounded" style="background: #76d371;">
      <h3 style="
          font-family: 'ENCODE SANS';
          font-size: 34px;
          font-weight: 700;
        ">
        Compra realizada com sucesso
      </h3>
    </div>
    <script>
      (() => {
        jQuery('#btn-payment').attr('href', window.location.href.replace('?ingressos=true', '') + '?ingressos=true');
        jQuery('#btn-payment').html("Comprar mais ingressos");
        jQuery('#btn-payment').removeClass('d-none');
        //window.removeEventListener('beforeunload', handleWindowExit);
        jQuery('#btn-cancelar').off('click', handleModalExitWithCancelTransaction);
        jQuery('#btn-cancelar').on('click', handleModalExit);
        conversaoPixel();
      })();
    </script>
   <?php elseif ($payment_response->responseData->data->status === 'pending'): ?>
    <div id="container_iframe" class="row m-2 justify-content-center">
      <iframe id="CHALLENGE_IFRAME" name="CHALLENGE_IFRAME"> </iframe>
      <form target="CHALLENGE_IFRAME" id="CHALLENGE_FORM" method="POST">
        <input id="CHALLENGE_JWT" type="hidden" name="JWT" />
      </form>
    </div>
      <script>

function removerCifrao(valor) {

if (typeof valor === 'string' && valor.startsWith('R$')) {

    return parseFloat(valor.replace('R$', '').replace('.', '').replace(',', '.').trim());
}

return valor;
}


function meioPagamento() {
    var credit = document.getElementById('card-credit');
    var pix = document.getElementById('pix');

    if (credit.className.endsWith('active')) {
        return "Cartão de Crédito";
    } else {
        return "Pix";
    }
}

function conversaoPixel(){
  let valorTotal = document.getElementById('valor-total-cartao').innerText
              let valorConvertido = removerCifrao(valorTotal);
              let metodoPagamento = meioPagamento();
              
              fbq('track', 'Purchase', {
                value: valorConvertido,
                currency: 'BRL',
                content_type: metodoPagamento
              });
}





        const windowSizeWidth   = <?=$payment_response->responseData->data->challenge->window_size->width ?>;
        const windowSizeHeight  = <?=$payment_response->responseData->data->challenge->window_size->height ?>;
        const urlFormChallenge  = "<?=$payment_response->responseData->data->challenge->url?>";
        const accesTokenSession = "<?=$payment_response->responseData->data->challenge->access_token?>";
        var pollingInterval;
        const iframe = document.getElementById("CHALLENGE_IFRAME")
        iframe.width = windowSizeWidth
        iframe.height = windowSizeHeight

        jQuery("#container_iframe").closest('.container').css('width', windowSizeWidth);
        const form = document.getElementById("CHALLENGE_FORM")
        form.action = urlFormChallenge
        
        const input = document.getElementById("CHALLENGE_JWT")
        input.value = accesTokenSession
        
        form.submit()
        const isObjectEmpty = (objectName) => {
                return (
                  objectName &&
                  Object.keys(objectName).length === 0 &&
                  objectName.constructor === Object
                );
              };
        async function getStatusTransaction(){
          const formData = new FormData();
          formData.append('action', "ings_check_status_transaction");
          formData.append('transaction_id', "<?=$payment_response->responseData->data->transactionId?>");
          const rawResponse = await fetch(my_ajax_object.ajax_url, {
            method: 'POST',
            body: formData,
          }).then((response) => {
            return response.json();
          }).then( responseStatusTransaction => {
            if(responseStatusTransaction.responseData.status == 'approved'){
              clearInterval(pollingInterval);
              jQuery("#container_iframe").html('<div id="compra-sucesso" class="row text-center p-4 rounded" style="background: #76d371;">\
                                                  <h3 style=" \
                                                      font-family: \'ENCODE SANS\'; \
                                                      font-size: 34px; \
                                                      font-weight: 700; \
                                                    "> \
                                                    Compra realizada com sucesso \
                                                  </h3> \
                                                </div>');
                                                conversaoPixel();
              jQuery('#btn-payment').attr('href', window.location.href.replace('?ingressos=true', '') + '?ingressos=true');
              jQuery('#btn-payment').html("Comprar mais ingressos");
              jQuery('#btn-payment').removeClass('d-none');
              //window.removeEventListener('beforeunload', handleWindowExit);
              jQuery('#btn-cancelar').off('click', handleModalExitWithCancelTransaction);
              jQuery('#btn-cancelar').on('click', handleModalExit);

            }
            if(responseStatusTransaction.responseData.status == 'declined' || isObjectEmpty(responseStatusTransaction.responseData) !== false ){
              clearInterval(pollingInterval);
              jQuery("#container_iframe").html('<div class="row text-center">\
                                                  <svg style="height: 110px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16"> \
                                                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" /> \
                                                  </svg> \
                                                  <h3>Pagamento recusado</h3>\
                                                </div> ');
              
            }
          });
        }
        window.addEventListener("message", (event) => {
          if(event.origin.includes("ingresse.com")){
            pollingInterval = setInterval(getStatusTransaction, 10000);
          }
        }, false);
      </script>
  <?php else : ?>
    <div class="row text-center">
      <svg style="height: 110px" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293 5.354 4.646z" />
      </svg>
      <h3>Pagamento recusado</h3>
    </div>
    <!-- <div class="row m-2 justify-content-center">
      <div class="col-4 text-center">
        <button class="btn btn-light btn-large rounded-pill" onclick="tryPaymentAgain()">
          Tentar com outro cartão
        </button>
      </div>
    </div> -->
  <?php endif; ?>
</div>