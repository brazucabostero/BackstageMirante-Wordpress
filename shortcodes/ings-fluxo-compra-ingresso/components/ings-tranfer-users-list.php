<?php if (count($users)): ?>
  <span style="margin-top: 1rem; color: #FFF; text-align: center; font-family: Encode Sans; font-size: 20px; font-weight: 600;">
    Resultado para "<?= $_POST['term'] ?>"
  </span>

  <ul class="list-group" style="">
    <?php foreach ($users as $user_index => $user): ?>
      <li class="list-group-item">
        <a href="javascript:void(0);"  style="cursor: pointer;" onclick="handleClickSelectNewHolder(<?= $user_index ?>)">
        <div class="row">
          <div class="col-2">
            <img class="img-fluid rounded" width="100px" height="100px" src="<?= $user->picture ?>" alt="">
          </div>
          <div class="col-8 d-flex flex-column">
            <p>Nome: <?= $user->name ?></p>
            <p>E-mail: <?= $user->email ?></p>
          </div>
          <div class="col-2 d-flex align-items-center">
            <svg width="33" height="32" viewBox="0 0 33 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M13.8333 21.3327L19.1666 15.9993L13.8333 10.666" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
          </div>
        </div>
        </a>
      </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
  <div style="display: flex; flex-direction: column; gap: 1rem; margin-top: 1rem; align-items: center;">
    <span style="color: #FFF; text-align: center; font-family: Encode Sans; font-size: 20px; font-weight: 600;">
      Nenhum amigo encontrado
    </span>

    <button class="ings-search-user-buttom" onclick="handleClickSelectNewUserHolder()">
      ENVIAR PARA EMAIL
    </button>
  </div>
<?php endif; ?>

<script>
  function handleClickSelectNewHolder(userId) {
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: {
          action: "ings_fluxo_compra_transfer_ticket",
          transfer: true,
          userId
      },
      success: function(response) {
        jQuery('#modal-body-fluxo-compra').empty().html(response);
      }
    });
  }

  function handleClickSelectNewUserHolder() {
    jQuery.ajax({
      type: "post",
      url: my_ajax_object.ajax_url,
      data: {
          action: "ings_fluxo_compra_transfer_not_user_ticket",
          transfer: true,
          email: jQuery('#search-user-input').val()
      },
      success: function(response) {
        jQuery('#modal-body-fluxo-compra').empty().html(response);
      }
    });
  }
</script>