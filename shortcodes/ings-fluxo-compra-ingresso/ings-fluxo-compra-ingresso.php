<?php

function botao_comprar_callback()
{
  global $post;
  $eventId = get_post_meta($post->ID, 'mirante_event_id', true);
  $ingresse_Api = new IngresseAPI();
  $event = $ingresse_Api->getEvent($eventId);
  $sessions = $event->data->sessions;
  $sessionsAvailable = array_filter($sessions, function ($session) {
    return $session->status === 'available';
  });

  $sessionsAvailableData = array_map(function ($session) use ($eventId, $ingresse_Api) {
    $sessions = $ingresse_Api->getSession($eventId, $session->id)->responseData;
    $_session['event'] = $session;
    $_session['sessions'] = array_filter($sessions, function ($item) {
      return $item->status === 'available' && $item->salable == 'true';
    });
    $_session['datetime'] = $session->dateTime;

    return $_session;
  }, $sessionsAvailable);

  $_SESSION['current_event'] = $event;
  $_SESSION['sessions_available'] = $sessionsAvailableData;

  usort($sessionsAvailableData, function ($a, $b) {
    return strtotime($a['datetime']) - strtotime($b['datetime']);
  });


  ob_start();
  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'ings-fluxo-compra-ingresso-view.php';
  return ob_get_clean();
}

add_shortcode('botao_comprar', 'botao_comprar_callback');
