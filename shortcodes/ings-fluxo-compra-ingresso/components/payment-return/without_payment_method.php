<script async src="https://www.googletagmanager.com/gtag/js?id=AW-11248481640"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'AW-11248481640');
</script>
<div id="compra-sucesso" class="row text-center p-4 rounded" style="background: #76d371;">
    <h3 style="
          font-family: 'ENCODE SANS';
          font-size: 34px;
          font-weight: 700;
        ">
        Compra realizada com sucesso
    </h3>
</div>
<script>

function converterParaNumero(valorString) {
    const numero = parseFloat(valorString.replace('R$ ', '').replace(',', '.'));
    return isNaN(numero) ? 0 : numero;
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

    
    (() => {

        let valorTotal = document.getElementById('valor-total-cartao').innerText
    let valorConvertido = converterParaNumero(valorTotal)
    let metodoPagamento = meioPagamento();


        jQuery('#btn-payment').attr('href', window.location.href.replace('?ingressos=true', '') + '?ingressos=true');
        jQuery('#btn-payment').removeClass('d-none');
        jQuery('#btn-payment').html("Comprar mais ingressos");
        //window.removeEventListener('beforeunload', handleWindowExit);
        jQuery('#btn-cancelar').off('click', handleModalExitWithCancelTransaction);
        jQuery('#btn-cancelar').on('click', handleModalExit);

        console.log(`Meio de pagamento é ${metodoPagamento}`)

        function gtag_report_conversion(url) {
  var callback = function () {
    if (typeof(url) != 'undefined') {
      window.location = url;
    }
  };
  gtag('event', 'conversion', {
      'send_to': 'AW-11248481640/OJhtCN29r_QYEOjq2PMp',
      'value': valorConvertido,
      'currency': 'BRL',
      'event_callback': callback
  });
  return false;
}


    fbq('track', 'Purchase', {
      value: valorConvertido,
      currency: 'BRL',
      content_type: metodoPagamento
    });
    })();
</script>