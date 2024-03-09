<?php
$total_price = 0;
$total_quantity = 0;
?>
<div class="container text-center">
  <div class="step-style">
    Ingressos
    <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    Identificação
    <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    <span class="step-active">
      Nomear ingressos
    </span>
    <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    Pagamento
  </div>

  <h3 style="font-weight: 700; font-family: 'Encode Sans'; font-size: 26px; margin-bottom: 1rem;">DE QUEM SÃO OS INGRESSOS?</h3>
  <h4 style="color: rgba(255, 255, 255, 0.50); font-family: Encode Sans; font-size: 20px; font-weight: 600; margin-bottom: 1rem;">
    Busque e selecione o amigo que receberá o ingresso
  </h4>
  <h4 style="display:flex; justify-content: center; align-items: baseline; gap: 1rem; font-family: 'Encode Sans'; font-size: 20px; font-weight: 600; margin-bottom: 3rem;">
    <span>
      <?= $selectedTicket["session"]['name'] . ' - ' . $selectedTicket["type"]['name'] ?>
    </span>
    <a href="javascript:void(0);" onclick="handleClickCancelTransfer()" style="color: #305FEB; font-family: Encode Sans; font-size: 14px; font-weight: 600;">Escolher outro ingresso</a>
  </h4>

  <div class="container ings-user-transfer">
    <input id="search-user-input" class="ings-search-user-input" type="email" name="term" placeholder="Busque amigos por nome ou e-mail"/>
    <button onclick="handleClickSearchUsers()" class="ings-search-user-buttom">
      BUSCAR
    </button>
  </div>

  <div id="search-result" style="display: flex; flex-direction: column; align-items: center; gap: 3rem;">

  </div>
</div>

<script>
  function handleClickCancelTransfer() {
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: {
          action: "ings_fluxo_compra_cancel_transfer_step"
      },
      success: function(response) {
        jQuery('#modal-body-fluxo-compra').empty().html(response);
      }
    });
  }

  function handleClickSearchUsers() {
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: {
        action: 'get_users_available_to_transfer_fragment',
        term: jQuery('#search-user-input').val()
      },
      success: function(response) {
        jQuery('#search-result').html(response);
      }
    });
  }

  (() => {
    jQuery('#button-step-3-next').on('click', () => {
      jQuery.ajax({
        type: "post",
        url: my_ajax_object.ajax_url,
        data: {
          action: "ings_fluxo_compra_step_4"
        },
        success: function(response) {
          jQuery('#modalFluxoCompra .container, #modalFluxoCompra .row').removeClass('ings-modal-full-height')
          jQuery('#modal-body-fluxo-compra').removeClass('ings-modal-ff-primary').html(response);
          jQuery('#button-step-3-next').addClass('d-none')
        }
      });
    });
  })()
</script>