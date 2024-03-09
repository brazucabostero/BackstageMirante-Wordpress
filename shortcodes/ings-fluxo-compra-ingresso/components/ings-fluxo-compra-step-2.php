<script type="text/javascript">
  /* Popup Cancelled/Closed by user callback */
  function cancelCallback(message) {
    console.log(message);
  }

  /* Authentication Done callback */
  function doneCallback(data) {
    Cookies.set('wp_ing_token', data.content.token)
    Cookies.set('wp_ing_jwt', data.content.jwt)
    Cookies.set('wp_ing_user', data.content)

    jQuery('#form-step-2').submit();
  }

  /* Authentication Confirmed callback */
  function confirmedCallback(message) {
    console.log(message);
  }

  /* Authentication Login Success callback */
  function loginSuccessCallback(message) {
    document.querySelector(".e-form__buttons__wrapper__button-next").click()
  }

  /* Authentication Login Failure callback */
  function loginFailureCallback(message) {
    console.log(message);
  }

  /* Authentication Register Success callback */
  function registerSuccessCallback(message) {
    console.log(message);
  }

  /* Authentication Register Failure callback */
  function registerFailureCallback(message) {
    console.log(message);
  }

  // Back to Step 1 by clicking on previews bradcrumb
  function backToStep1(){
    jQuery('#form-action').removeAttr("value");
    jQuery('#form-action').attr("value", "ings_fluxo_compra_step_1");

    jQuery("#form-step-2").submit();
  }


  /* Object with Auth Options */
  var options  = {
    companyId: 31,               // Number
    apikey: 'tDgFYzwDkGVTxWeAgQxs73Hrs74CaNn2',  // String
  };

  /* Object with Auth Callbacks */
  var callbacks = {
    cancel: cancelCallback,
    done: doneCallback,
    confirm: confirmedCallback,
    loginSuccess: loginSuccessCallback,
    loginFailure: loginFailureCallback,
    registerSuccess: registerSuccessCallback,
    registerFailure: registerFailureCallback,
  };

  (() => {
    jQuery('#form-step-2').on('submit', e => {
      e.preventDefault();

      jQuery.ajax({
        type: "post",
        url: my_ajax_object.ajax_url,
        data: jQuery('#form-step-2').serialize(),
        success: function(response) {
          jQuery('#step-2-container').remove();
          jQuery('#modal-body-fluxo-compra').addClass('ings-modal-ff-primary').css({
            height: '100%',
          }).html(response);
          jQuery('#button-step-3-next').addClass('d-none')
        }
      });
    });
  })()
</script>

<div id="step-2-container" style="height: 100%">
  <div class="step-style">
    Ingressos
    <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    <span class="step-active">
      Identificação
    </span>
    <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    Nomear ingressos
    <svg class="bradcrumb-space" width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M1 9L5 5L1 1" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
    </svg>
    Pagamento
  </div>
  <div id="container-auth" style="height: 100%"></div>

  <form id="form-step-2">
    <input id="form-action" type="hidden" name="action" value="ings_fluxo_compra_step_3">
  </form>
</div>

<script type="text/javascript">
  var authEmbedded = null;

  function initializeAuth() {
    authEmbedded = new Authing(options, callbacks, 'container-auth');
  }

  initializeAuth()
</script>