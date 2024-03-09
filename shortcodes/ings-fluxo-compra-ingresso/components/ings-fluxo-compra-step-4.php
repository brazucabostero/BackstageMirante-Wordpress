<?php

/**
 * @var $sessions
 * @var $transaction
 * @var $user
 */

$total_quantity = 0;
$total_price = 0;
$total_tax = 0;
$tickets_count = 0;

foreach ($sessions as $session) {
  foreach ($session['types'] as $type) {
    $tickets_count += intval($type['quantity']);
  }
}
?>

<main>
  <div class="step-style">
    Ingressos
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
    <span class="step-active">
      Pagamento
    </span>
  </div>
  <section class="ings-payment-card-user">
    <div class="avatar">
      <img id="ings-user-avatar" src="<?= $user->photo ?>" />
    </div>
    <span id="ings-user-name" class="name"><?= $user->fullName ?></span>
    <span id="ings-user-mail" class="mail"><?= $user->email ?></span>
    <a class="link d-none" href="#">Trocar de usuário</a>
  </section>

  <section id="ings-main-content">
    <?php
    get_template_part(
      'shortcodes/ings-fluxo-compra-ingresso/components/form-address',
      '',
      ['user' => $user]
    );
    get_template_part(
      'shortcodes/ings-fluxo-compra-ingresso/components/form-pagamento',
      '',
      [
        'transaction' => $transaction,
        'user' => $user
      ]
    ); ?>
  </section>
  <section id="ings-payment-result" class="ings-payment-result d-none">
  </section>

  <section class="ings-resume">
    <article class="m-v-space-3">
      <h3>Resumo da compra</h3>
      <span><?= $tickets_count ?> ingresso(s)</span> <a class="change-ingress d-none" href="#">Alterar ingresso(s)</a>
    </article>
    <article class="resume-box">
      <div class="header-box">
        <strong>Ingressos selecionados</strong>
        <strong>Qtd</strong>
        <strong>Preço</strong>
      </div>
      <hr class="divisor">
      <?php foreach ($sessions as $session_id => $session) : ?>
        <?php foreach ($session['types'] as $type_id => $type) :
          if (intval($type['quantity']) <= 0) continue;
          $total_price += intval($type['quantity']) * floatval($type['price_singular']);
          $total_tax += intval($type['quantity']) * floatval($type['tax']);
          $total_quantity += intval($type['quantity']);
        ?>
          <div class="loop-ingress-box">
            <b style="font-size: larger;"><?= $session['name'] ?></b> <span><?= $type['quantity'] ?></span>
            <span>R$ <?= format_price_to_brl(floatval($type['price_singular']) + floatval($type['tax'])) ?></span>
            <span class="consumacao"><?= $type['name'] ?></span> <span></span> <span>Taxas inclusas</span>
            <?php foreach ($type['dates'] as $date) : ?>
              <div class="date"><span class="tag"><?= get_day_shortname_by_date($date); ?></span>
                <?= get_datetime_short_formated($date); ?>
              </div>
            <?php endforeach; ?>
          </div>
          <hr class="divisor">
        <?php endforeach; ?>
      <?php endforeach; ?>
      <div class="totals-box">
        <span>Total em ingressos</span> <span><?= $total_quantity ?></span>
        <span>R$ <?= format_price_to_brl($total_price); ?></span>
        <span class="service-tax">Taxa de serviços</span> <span></span> <span>R$
          <?= format_price_to_brl($total_tax); ?></span>
        <span class="tax-process esp-tax-process">Taxa de processamento</span> <span class="esp-tax-process"></span> <span id="taxa-processamento-cartao" class="esp-tax-process">R$ 0,00</span>
        <span class="total-payment"><strong>Total a pagar</strong></span>
        <span><strong id="valor-total-cartao">R$ <?= format_price_to_brl($total_price + $total_tax); ?></strong></span>
      </div>
    </article>

    <!--//-->
    <hr class="divisor">
    <!--//-->

    <article class="payment-start">
      <div>
        <img src="cadeado.svg" alt="">
      </div>
      <div>
        <span class="text-1">Esta aplicação possui</span>
        <span class="text-2">Certificado de segurança</span>
      </div>
      <div>
        <a id="btn-payment" class="btn-payment" href="javascript:handleSubmitPaymentMethod()">
          Pagar R$ <?= format_price_to_brl($total_price + $total_tax); ?></a>
      </div>
    </article>
  </section>
  <script src="<?= get_stylesheet_directory_uri(); ?>/lib/js/endereco-cobranca.js"></script>
  <script>
    function handleSubmitPaymentMethod() {
      let paymentMethod = jQuery('#ings-payment').find('li button.active').attr('id');
      if (paymentMethod) {
        var form = jQuery(`#form-${paymentMethod}`)
      } else {
        var form = jQuery('<form>');
      }

      form.append('<input type="hidden" name="action" value="ings_pay_transaction">')

      jQuery.ajax({
        type: "post",
        url: my_ajax_object.ajax_url,
        data: form.serialize(),
        success: function(response) {
          jQuery('#ings-main-content').addClass('d-none');
          jQuery('#ings-payment-result').html(response);
          jQuery('#ings-payment-result').removeClass('d-none');
          jQuery("#ings-modal-fluxo-compra").animate({
            scrollTop: 0
          }, "slow");
        },
        error: function(jqXHR, textStatus, errorMessage) {
          alert(jqXHR.responseText);
        }
      });
    }

    function tryPaymentAgain() {
      jQuery('#ings-main-content').removeClass('d-none');
      jQuery('#ings-payment-result').html("");
      jQuery('#ings-payment-result').addClass('d-none');
    }

    (() => {
      jQuery('#change-ingress').on('click', e => {
        e.preventDefault();
        let queryParams = new URLSearchParams(window.location.search);
        let eventId = queryParams.get('event');
        jQuery.ajax({
          type: "post",
          url: `${my_ajax_object.ajax_url}?event_id=${eventId}`,
          data: {
            action: 'ings_fluxo_compra_step_1'
          },
          success: function(response) {
            jQuery('#modal-body-fluxo-compra').html(response);
          }
        });
      });

      jQuery('[name=cep]').on('change', async e => {
        let cep = e.target.value.replace('-', '').replace(' ', '');
        let url = `https://viacep.com.br/ws/${cep}/json/`;
        let response = await fetch(url);
        let data = await response.json();

        if (response.status > 200) return false;

        jQuery('[name=logradouro]').val(data.logradouro);
        jQuery('[name=bairro]').val(data.bairro);
        jQuery('[name=cidade]').val(data.localidade);
        jQuery('[name=estado]').val(data.uf);
      });

      jQuery('#btn-cancelar').off('click', handleModalExit);
      jQuery('#btn-cancelar').on('click', handleModalExitWithCancelTransaction);
    })()
  </script>
</main>