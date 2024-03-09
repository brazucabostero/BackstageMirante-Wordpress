<?php

/**
 * @var $eventId
 * @var $event
 * @var $sessionsAvailableData
 */

$eventsSessions = array_filter($sessionsAvailableData, function ($event) {
  return !empty($event['sessions']);
});

$modal_is_open = !empty($_GET['ingressos']);
?>

<style>
  .disabled {
    pointer-events: none;
    opacity: 0.65;
  }

  .button-buyVirrey {
  background: linear-gradient(180deg, #F36F00, #F2295B);
  border: none;
  border-radius: 30px;
  width: 224px;
  height: 59px;
  color: white;
  font-size: 16px;
  font-weight: 700;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 15px;
  transition: .2s linear;
}
</style>

<div class="button-buy-container">
  <button type="button" class="button-buyVirrey" data-bs-toggle="modal" data-bs-target="#modalFluxoCompra">
    COMPRAR INGRESSOS
  </button>
</div>

<div class="modal fade" id="modalFluxoCompra" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable modal-dialog-centered ings-modal-compras" role="document">
    <div class="modal-content">
      <div class="modal-header d-block">
        <h5 class="modal-title" id="modalTitleId">
          <div class="container">
            <div class="row">
              <div class="col-8 ings-buy-flow-left">
                <img src="<?= $event->data->poster->large; ?>">
                <div class="title-flex">
                  <!-- <small>Powered by Ingresse</small> -->
                  <small><br></small>
                  <p class="title"><?= $event->data->title ?></p>
                </div>
              </div>
              <div class="col-4 ings-modal-header-buttons">
                <a href="<?php bloginfo('url'); ?>/contato" class="ajuda">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-question-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                    <path d="M5.255 5.786a.237.237 0 0 0 .241.247h.825c.138 0 .248-.113.266-.25.09-.656.54-1.134 1.342-1.134.686 0 1.314.343 1.314 1.168 0 .635-.374.927-.965 1.371-.673.489-1.206 1.06-1.168 1.987l.003.217a.25.25 0 0 0 .25.246h.811a.25.25 0 0 0 .25-.25v-.105c0-.718.273-.927 1.01-1.486.609-.463 1.244-.977 1.244-2.056 0-1.511-1.276-2.241-2.673-2.241-1.267 0-2.655.59-2.75 2.286zm1.557 5.763c0 .533.425.927 1.01.927.609 0 1.028-.394 1.028-.927 0-.552-.42-.94-1.029-.94-.584 0-1.009.388-1.009.94z" />
                  </svg>
                  Ajuda
                </a>
                <button class="fechar" type="button" type="button" class="" id="btn-cancelar">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                    <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z" />
                  </svg>
                  Fechar
                </button>
              </div>
            </div>
          </div>
        </h5>
      </div>
      <?php if (empty($sessionsAvailableData) || empty($eventsSessions)) : ?>
        <div class="m-5">
          <h3 class="text-center">Não há ingressos disponíveis no momento</h3>
        </div>
      <?php else : ?>
        <div id="ings-modal-fluxo-compra" class="container ings-modal-height-100">
          <div class="row">
            <div id="modal-body-fluxo-compra" class="modal-body col-md-12">
              <div id="step-1-container">
                <div class="step-style">
                  <span class="step-active">
                    Ingressos
                  </span>
                  <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Identificação
                  <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Nomear ingressos
                  <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                  </svg>
                  Pagamento
                </div>
                <div class="ings-codigo">
                  <div class="row">
                    <div class="col d-flex align-items-center justify-content-end">
                      <a id="insert-passkey" href="#">Utilizar Código</a>
                    </div>
                  </div>
                </div>
                <form id="form-step-1" method="post">
                  <div class="accordion" id="accordionListSessions">
                    <?php
                    foreach ($sessionsAvailableData as $sessionData) : ?>
                      <?php
                      $ticketsAvailable = array_filter($sessionData['sessions'], function ($ticket) {
                        return $ticket->status === 'available' && $ticket->salable == 'true';
                      });
                      ?>
                      <?php if (empty($ticketsAvailable)) : continue;
                      endif; ?>
                      <div class="card">
                        <div class="card-header">
                          <small class="ings-card-event-data">
                            <?= get_datetime_formated($sessionData['datetime']); ?>
                          </small>
                          <h3 class="ticket-group">
                            <?= get_day_by_date($sessionData['datetime']); ?>
                          </h3>
                        </div>
                        <div class="card-body">
                          <div class="tickets-list">
                            <?php foreach ($ticketsAvailable as $ticket) : ?>
                              <div class="accordion-item">
                                <h2 class="accordion-header">
                                  <button id="ingresseButton" type="button" class="colorAccordion accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne-<?= $ticket->id ?>" aria-controls="collapseOne-<?= $ticket->id ?>">
                                    <h3 class="ticket-group">
                                      <?= $ticket->name ?>
                                    </h3>
                                  </button>
                                </h2>
                                <?php $typesFiltered = array_filter($ticket->type, function ($type) {
                                  return $type->status === 'available';
                                }); ?>
                                <div id="collapseOne-<?= $ticket->id ?>" class="accordion-collapse collapse" data-bs-parent="#accordionListSessions">
                                  <div class="accordion-body">
                                    <?php foreach ($typesFiltered as $type) : ?>
                                      <div class="row">
                                        <div class="col-9">
                                          <div class="ings-ticket-select">
                                            <b class="ticket-tyme-name"><?= $type->name; ?></b>
                                            <b class="ticket-price">R$ <?= format_price_to_brl($type->price); ?></b>
                                            <span class="ticket-tax"> + taxa</span>
                                          </div>
                                        </div>
                                        <div class="col-3 ings-qtd-section">
                                          <div class="ings-qtd-flex">
                                            <button class="ings-minus-bt" type="button" onclick="handleButtonMinusClick('<?= $type->id; ?>', '0')">
                                              <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                                                  <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />
                                                </svg>
                                              </span>
                                            </button type="button">
                                            <input class="ings-qtd-input" id="session-type-<?= $type->id; ?>" name="sessions[<?= $ticket->id ?>][types][<?= $type->id ?>][quantity]" type="number" min="0" max="<?= min($type->quantityInStock, $type->restrictions->maximum); ?>" value="0" data-price="<?= floatval($type->price) ?>">
                                            <button class="ings-plus-bt" type="button" onclick="handleButtonPlusClick('<?= $type->id; ?>', '<?= min($type->quantityInStock, $type->restrictions->maximum) ?>')">
                                              <span>
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                                                  <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                                                </svg>
                                              </span>
                                            </button>
                                          </div>
                                          <span id="ings-qtd-max-<?= $type->id; ?>" class="ings-qtd-max-label d-none">Limite máximo</span>
                                        </div>
                                      </div>
                                      <?php foreach ($type->dates as $date) : ?>
                                        <input type="hidden" name="sessions[<?= $ticket->id ?>][types][<?= $type->id ?>][dates][<?= $date->id ?>]" value="<?= $date->datetime ?>">
                                      <?php endforeach; ?>
                                      <input type="hidden" name="sessions[<?= $ticket->id ?>][types][<?= $type->id ?>][price_singular]" value="<?= floatval($type->price) ?>">
                                      <input type="hidden" name="sessions[<?= $ticket->id ?>][types][<?= $type->id ?>][tax]" value="<?= floatval($type->tax) ?>">
                                      <input type="hidden" name="sessions[<?= $ticket->id ?>][types][<?= $type->id ?>][name]" value="<?= $type->name ?>">
                                      <input type="hidden" name="sessions[<?= $ticket->id ?>][types][<?= $type->id ?>][id]" value="<?= $type->id ?>">
                                    <?php endforeach; ?>
                                  </div>
                                </div>
                              </div>
                              <input type="hidden" name="sessions[<?= $ticket->id ?>][name]" value="<?= $ticket->name ?>">
                              <input type="hidden" name="sessions[<?= $ticket->id ?>][id]" value="<?= $ticket->id ?>">
                            <?php endforeach; ?>
                          </div>
                        </div>
                      </div>
                    <?php endforeach; ?>
                  </div>
                  <input type="hidden" name="event_id" value="<?= $eventId ?>">
                  <input type="hidden" name="action" value="ings_fluxo_compra_step_2">
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer bandeija-fixed d-block border-top-0 d-none" id="esp-total">
          <div class="container" style="margin: 0 auto;">
            <div class="row">
              <div class="col-6" id="total">
              </div>
              <div class="col-6" style="text-align: right;">
                <button id="button-step-1-next" type="button" onclick="jQuery('#form-step-1').submit()" class="ings-avancar">Avançar
                </button>
                <button id="button-step-3-next" type="button" class="d-none ings-avancar">Avançar</button>
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="modal fade" id="modalTranferenciaTitular" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
  <div class="modal-dialog modal-dialog-centered modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Transferencia de titular</h1>
      </div>
      <div id="modal-body-transfer-user" class="modal-body">
      </div>
      <div class="modal-footer">
        <button class="btn btn-primary" data-bs-target="#modalFluxoCompra" data-bs-toggle="modal">Concluido</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="modalCodigoPromocional" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog">
  <div class="modal-dialog modal-fullscreen modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-body">
        <div class="row my-4">
          <h3 class="text-center ings-codepromo-title">CÓDIGO</h3>
        </div>
        <form class="d-inline" id="passkey-form">
          <div class="ings-codepromo-input-row">
            <div class="ings-codepromo-input-box">
              <label for="passkey" class="form-label">
                RESPEITE MAIÚSCULAS E MINÚSCULAS
              </label>
              <input type="text" class="form-control ings-codepromo-input" id="passkey" name="passkey">
              <small id="passkey-notice-error" class="text-danger my-1"></small>
            </div>
          </div>
          <input type="hidden" class="form-control form-control-sm" name="event_id" value="<?= $eventId ?>">
          <input type="hidden" class="form-control form-control-sm" name="action" value="ings_get_tickets_by_passkey">
          <div class="ings-codepromo-buttons">
            <button type="button" onclick="jQuery('#modalCodigoPromocional').modal('hide')" class="ings-codepromo-cancel">
              Cancelar
            </button>
            <button type="submit" class="ings-codepromo-apply">
              Buscar
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="<?= get_stylesheet_directory_uri() . '/lib/js/ings-fluxo-compra-ingresso.js' ?>"></script>
<script src="<?= get_stylesheet_directory_uri() . '/lib/js/jquery.creditCardValidator.js' ?>"></script>

<script>
  //const myModal = new bootstrap.Modal(document.getElementById('modalFluxoCompra'), {})

  var botao = document.getElementById('ingresseButton');

  botao.click()


  function handleButtonMinusClick(id, min) {
    let inputElement = jQuery(`[id=session-type-${id}]`);
    let inputVal = parseInt(inputElement.val());
    jQuery(`#ings-qtd-max-${id}`).addClass("d-none")
    if (inputVal <= parseInt(min)) {
      inputElement.val(min);
      inputElement.trigger('change');
      return;
    }

    inputElement.val(inputVal - 1);
    inputElement.trigger('change');

  }

  function handleButtonPlusClick(id, max) {
    let inputElement = jQuery(`[id=session-type-${id}]`);
    let inputVal = parseInt(inputElement.val());
    if (inputVal >= parseInt(max)) {
      inputElement.val(max);
      inputElement.trigger('change');
      jQuery(`#ings-qtd-max-${id}`).removeClass("d-none")
      return;
    }

    inputElement.val(inputVal + 1);
    inputElement.trigger('change');
  }

  jQuery(document).ready(function() {
    <?php if ($modal_is_open) : ?>
      jQuery('#modalFluxoCompra').modal('show');
    <?php endif; ?>
  });

  (() => {
    jQuery('[id^="session-type-"]').on('change', () => {
      let totalPrice = 0;
      let totalQuantity = 0;

      let elementList = jQuery('[id^="session-type-"]')
      elementList.each(function() {
        let inputElement = jQuery(this);
        let price = parseFloat(inputElement.data("price"));
        let quantity = parseInt(inputElement.val());

        totalPrice += price * quantity;
        totalQuantity += quantity;
      });

      if (totalQuantity > 0) {
        jQuery("#esp-total").removeClass('d-none');
      } else {
        jQuery("#esp-total").addClass('d-none');
      }

      jQuery('#total').html(`
      <div class="bandeja-left">
        <small class="qtd-ingressos">
            ${totalQuantity} Ingresso(s)
        </small>
        <p>
            <span class="total">R$ ${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 2 }).format(totalPrice)}</span>
            <span class="taxa">+ taxa</span>
        </p>
        </div>
      `);
    });

    jQuery('#insert-passkey').on('click', e => {
      e.preventDefault();

      jQuery('#modalCodigoPromocional').modal('show');
    });

    jQuery('#passkey-form').on('submit', e => {
      e.preventDefault();

      jQuery.ajax({
        type: "post",
        url: my_ajax_object.ajax_url,
        data: jQuery('#passkey-form').serialize(),
        success: function(response) {
          jQuery('#accordionListSessions').html(response);
          jQuery('#modalCodigoPromocional').modal('hide');
          jQuery('#passkey-notice-error').html("");
        },
        error: function(errorResponse) {
          jQuery('#passkey-notice-error').html(errorResponse.responseText);
        }
      });
    });

    jQuery('#btn-cancelar').on('click', handleModalExit);
  })();
</script>