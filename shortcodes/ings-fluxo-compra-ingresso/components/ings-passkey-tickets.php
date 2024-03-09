<?php

/**
 * @var $sessions
 * @var $sessionsFiltered
 * @var $passkey
 */

$sessionsFiltered = array_filter($sessions, function ($session) {
  return $session->status === 'available' && $session->salable == 'true';
});
?>
<?php if (empty($sessionsFiltered)) : ?>
  <div class="passkey-tickets">
    <div class="alert alert-light" role="alert">
      Nenhum ingresso encontrado com o código <?= $passkey; ?>
    </div>
  </div>
<?php else : ?>
  <?php foreach ($sessionsFiltered as $session) : ?>
    <?php
    $typesFiltered = array_filter($session->type, function ($type) {
      return $type->status === 'available';
    });
    ?>
    <div class="card">
      <div class="card-header">
        <small class="ings-card-event-data">
          <?php
          $arrayData = json_decode(json_encode($session), true);
          $date = $arrayData['type'][0]['dates'][0]['date'];
          $datetime = $arrayData['type'][0]['dates'][0]['datetime'];
          $time = $arrayData['type'][0]['dates'][0]['time'];
          echo $date . ' ' . $time;
          ?>
        </small>
        <h3 class="ticket-group">
          <?= get_day_by_date($datetime); ?>
        </h3>
      </div>
      <div class="card-body">
        <div class="tickets-list">
          <div class="accordion-item">
            <h2 class="accordion-header">
              <button type="button" class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?= $session->id ?>" aria-controls="collapseOne">
                <h3 class="ticket-group">
                  <?= $session->name ?>
                </h3>
              </button type="button">
            </h2>
            <?php
            $hasHiddenTickets = count(array_filter($typesFiltered, function ($type) {
              return $type->hidden == 'true';
            })) >= 1;
            ?>
            <div id="collapse<?= $session->id ?>" class="accordion-collapse collapse  <?= $hasHiddenTickets ? "show" : "" ?>" data-bs-parent="#accordionExample">
              <div class="accordion-body">
                <?php foreach ($typesFiltered as $type) : ?>
                  <?php if ($type->hidden == 'true') : ?>
                    <span class="bg-success rounded m-3 mb-0 p-2 d-inline-block">
                      ingresso(s) liberado(s) pelo código <?= $passkey ?>
                    </span>
                  <?php endif; ?>
                  <div class="row">
                    <div class="col-9">
                      <div class="ings-ticket-select">
                        <b class="ticket-group"><?= $type->name; ?></b><br>
                        <b class="ticket-price">
                          R$ <?= format_price_to_brl(floatval($type->price) + floatval($type->tax)); ?>
                        </b>
                        <span class="ticket-tax"> + taxa</span>
                      </div>
                    </div>
                    <div class="col-3 ings-qtd-flex">
                      <button class="ings-minus-bt" type="button" onclick="handleButtonMinusClick('<?= $type->id; ?>', '0')">
                        <span>
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-dash" viewBox="0 0 16 16">
                            <path d="M4 8a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7A.5.5 0 0 1 4 8z" />
                          </svg>
                        </span>
                      </button type="button">
                      <input class="ings-qtd-input" id="session-type-<?= $type->id; ?>" name="sessions[<?= $session->id ?>][types][<?= $type->id ?>][quantity]" type="number" min="0" max="<?= $type->restrictions->maximum; ?>" value="0" data-price="<?= floatval($type->price) + floatval($type->tax) ?>">
                      <button class="ings-plus-bt" type="button" onclick="handleButtonPlusClick('<?= $type->id; ?>', '<?= $type->restrictions->maximum; ?>')">
                        <span>
                          <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
                          </svg>
                        </span>
                      </button>
                    </div>
                  </div>
                  <?php foreach ($type->dates as $date) : ?>
                    <input type="hidden" name="sessions[<?= $session->id ?>][types][<?= $type->id ?>][dates][<?= $date->id ?>]" value="<?= $date->datetime ?>">
                  <?php endforeach; ?>
                  <input type="hidden" name="sessions[<?= $session->id ?>][types][<?= $type->id ?>][price_singular]" value="<?= floatval($type->price) ?>">
                  <input type="hidden" name="sessions[<?= $session->id ?>][types][<?= $type->id ?>][tax]" value="<?= floatval($type->tax) ?>">
                  <input type="hidden" name="sessions[<?= $session->id ?>][types][<?= $type->id ?>][name]" value="<?= $type->name ?>">
                  <input type="hidden" name="sessions[<?= $session->id ?>][types][<?= $type->id ?>][id]" value="<?= $type->id ?>">
                <?php endforeach; ?>
              </div>
            </div>
          </div>
          <input type="hidden" name="sessions[<?= $session->id ?>][name]" value="<?= $session->name ?>">
          <input type="hidden" name="sessions[<?= $session->id ?>][id]" value="<?= $session->id ?>">
        </div>
      </div>
    </div>




  <?php endforeach; ?>
  <input type="hidden" name="passkey" value="<?= $passkey; ?>">
  <script>
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
    })();
  </script>
<?php endif; ?>