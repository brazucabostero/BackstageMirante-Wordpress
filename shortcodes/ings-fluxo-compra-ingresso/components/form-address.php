<?php
/**
 * @var $args
 */

$user = $args['user'];
$userAdress = $user->address;
?>
<section id="ings-customer-data" class="ings-payment-form <?= user_has_address($user) ? "d-none" : "" ?>">
  <h3 class="title-form">
    Endereço de cobrança
  </h3>
  <p class="text-form">
    Precisamos do seu endereço apenas para garantir a segurança do seu pagamento.
  </p>
  <form id="ings-form-customer-data">
    <article class="form">
      <div>
        <label class="cep">
          Cep
          <a class="busca-cep" href="#">Não sei meu cep</a>
        </label>
        <input type="text" name="cep" value="<?= $userAdress->zipcode ?>">
      </div>
      <div class="logradouro">
        <label>Logradouro</label>
        <input type="text" name="logradouro" value="<?= $userAdress->street ?>">
      </div>
      <div>
        <label>Número</label>
        <input type="text" name="numero" value="<?= $userAdress->number ?>">
      </div>
      <div>
        <label>Complemento</label>
        <input type="text" name="complemento" value="<?= $userAdress->complement ?>">
      </div>
      <div>
        <label>Bairro</label>
        <input type="text" name="bairro" value="<?= $userAdress->district ?>">
      </div>
      <div>
        <label>Cidade</label>
        <input type="text" name="cidade" value="<?= $userAdress->city ?>">
      </div>
      <div>
        <label>Estado</label>
        <input type="text" name="estado" value="<?= $userAdress->state ?>">
      </div>
      <div>
        <label>Celular</label>
        <input type="text" name="telefone" value="<?= $user->phone ?>">
      </div>
      <div>
        <button class="btn btn-primary btn-large rounded-pill" type="submit">
          Salvar
        </button>
      </div>
    </article>
    <input type="hidden" name="action" value="save_customer_data">
  </form>
</section>

<script>
  (() => {
    jQuery('#ings-form-customer-data').on('submit', e => {
      e.preventDefault();
      jQuery.ajax({
        type: "post",
        url: my_ajax_object.ajax_url,
        data: jQuery('#ings-form-customer-data').serialize(),
        success: function(response) {
          jQuery('#ings-form-pagamento').remove();
          jQuery('.ings-payment-adress').remove();
          jQuery('#ings-main-content').append(response);
          jQuery('#ings-customer-data').addClass('d-none');
        },
        error: function(jqXhr, textStatus, response) {
          alert(jqXhr.responseJSON.data)
        }
      });
    });
  })()
</script>
