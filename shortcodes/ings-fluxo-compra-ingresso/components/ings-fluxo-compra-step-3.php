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

  <h3 class="ings-transfer">DE QUEM SÃO OS INGRESSOS?</h3>
  <h4 class="ings-transfer">Se o ingresso for seu clique em “Meu Ingresso”, caso contrário clique em transferir</h4>

  <div class="card">
    <div class="card-body">
      <?php foreach ($selectedTickets as $ticketId => $ticket) : ?>
        <?php if ($ticket["type"]['quantity'] == "0") : continue;
        endif; ?>
        <?php
        $total_price += intval($ticket["type"]['quantity']) * floatval($ticket["type"]['price_singular']);
        $total_quantity += intval($ticket["type"]['quantity']);
        ?>
        <div class="row my-2">
          <div class="col-md-6 col-sm-12 ings-transfer-text-content">
            <h4 class="ings-session-name"><?= $ticket["session"]['name'] ?></h4>
            <p class="ings-transfer-type"><?= $ticket["type"]['name'] ?></p>
            <?php foreach ($ticket["type"]['dates'] as $date) : ?>
              <div class="ings-transfer-date"><span class="tag"><?= get_day_shortname_by_date($date); ?></span>
              <span class="data-hora">  <?= get_datetime_short_formated($date); ?></span>
              
              </div>
            <?php endforeach; ?>
          </div>
          <div id="step-3-actions" class="col-md-6 col-sm-12 ings-transfer-action <?= $ticket["user"] ? 'd-none' : ''  ?>">
            <div class="d-grid gap-2">
              <button class="ings-meu-ingresso-buttom" onclick="handleClickMyTicketInfo(<?= $ticketId ?>)">
                MEU INGRESSO
              </button>
              <button class="ings-meu-transfer-buttom" onclick="handleClickTransferHolder(<?= $ticketId ?>)">
                TRANSFERIR
              </button>
            </div>
          </div>
          <div id="my-ticket-info" class="col-md-6 col-sm-12 ings-transfer-action-mail <?= !$ticket["user"] ? 'd-none' : ''  ?>">
            <div class="ings-meu-transfer-buttom-remove-container">
              <p class="ings-transfer-mail"><?= $ticket["user"]['email'] ?></p>
              <button class="ings-meu-transfer-buttom-remove" onclick="handleClickRemoveTicketInfo(<?= $ticketId ?>)">
                REMOVER
              </button>
            </div>
          </div>
          <div id="transfer-ticket" class="col-4 d-none">
            <form id="transfer-ticket-form">
              <label for="sessions[<?= $ticket["session"]['id'] ?>][types][<?= $ticket["type"]['id'] ?>][customer_transfer_email]">
                Pesquise pelo novo titular
              </label>
              <br>
              <input class="m-2" type="email" id="sessions[<?= $ticket["session"]['id'] ?>][types][<?= $ticket["type"]['id'] ?>][term]" name="term" />
              <input type="hidden" name="session" value="<?= $ticket["session"]['id'] ?>">
              <input type="hidden" name="type" value="<?= $ticket["type"]['id'] ?>">
              <input type="hidden" name="action" value="get_users_available_to_transfer_fragment">
              <button onclick="handleClickGetModalTransferUsers()" type="button" class="btn btn-primary btn-lg rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTranferenciaTitular">
                Pesquisar
              </button>
              <button onclick="handleClickTransferHolder()" type="button" class="btn btn-link btn-lg rounded-pill">
                Voltar
              </button>
            </form>
          </div>
        </div>
        <hr>
      <?php endforeach; ?>
    </div>
  </div>

  
  <button onclick="handleClickContinue()" type="button" class="btn ings-modal-transfer-continue-button btn-lg rounded-pill <?= $canNextStep ? '' : 'disabled'  ?>">
    CONTINUAR
  </button>
</div>

<script>
  function handleClickMyTicketInfo(ticketId) {
    // jQuery('#my-ticket-info').toggleClass('d-none');
    // jQuery('#step-3-actions').toggleClass('d-none');
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: {
          action: "ings_fluxo_compra_transfer_ticket",
          ticketId
      },
      success: function(response) {
        jQuery('#modal-body-fluxo-compra').empty().html(response);
      }
    });
  }

  function handleClickRemoveTicketInfo(ticketId) {
    // jQuery('#my-ticket-info').toggleClass('d-none');
    // jQuery('#step-3-actions').toggleClass('d-none');
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: {
          action: "ings_fluxo_compra_transfer_ticket",
          ticketId,
          remove: true
      },
      success: function(response) {
        jQuery('#modal-body-fluxo-compra').empty().html(response);
      }
    });
  }

  function handleClickTransferHolder(ticketId) {
    // jQuery('#transfer-ticket').toggleClass('d-none');
    // jQuery('#step-3-actions').toggleClass('d-none');
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: {
          action: "ings_fluxo_compra_transfer_step",
          ticketId,
      },
      success: function(response) {
        jQuery('#modal-body-fluxo-compra').empty().html(response);
      }
    });
  }

  function handleClickContinue() {
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
  }

  function handleClickGetModalTransferUsers() {
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: jQuery('#transfer-ticket-form').serialize(),
      success: function(response) {
        jQuery('#modal-body-transfer-user').html(response);
      }
    });
  }
</script>