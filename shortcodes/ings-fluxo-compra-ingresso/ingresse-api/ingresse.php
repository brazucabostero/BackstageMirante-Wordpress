<?php

class IngresseAPI
{
  const API_KEY = 'tDgFYzwDkGVTxWeAgQxs73Hrs74CaNn2';

  public function getEvent($eventId)
  {
    $response = wp_remote_get("https://event.ingresse.com/public/{$eventId}?companyId=31");

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
    } else {
      $body = wp_remote_retrieve_body($response);
      return json_decode($body);
    }
  }

  public function getSessionsByEvent($eventId)
  {
    $url = "https://api.ingresse.com/event/{$eventId}?apikey=" . self::API_KEY . "";
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
    } else {
      $body = wp_remote_retrieve_body($response);
      return json_decode($body);
    }
  }

  public function getSession($eventId, $sessionId)
  {
    $url = "https://api.ingresse.com/event/{$eventId}/session/{$sessionId}/tickets?apikey=" . self::API_KEY;
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
    } else {
      $body = wp_remote_retrieve_body($response);
      return json_decode($body);
    }
  }

  public function processPayment($data, $userToken, $jwt)
  {
    $url = "https://api.ingresse.com/shop?apikey=" . self::API_KEY . "&usertoken={$userToken}";
    $response = wp_remote_post($url, [
      'body' => json_encode($data),
      'headers' => [
        'Authorization' => "Bearer " . $jwt,
        'Content-Type' => 'application/json'
      ],
    ]);

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("[processPayment] -" . $error_message);
    } else {
      $body = wp_remote_retrieve_body($response);
      error_log("[processPayment] - " . $body);
      return json_decode($body);
    }
  }

  public function startTransaction($data, $userToken, $jwt)
  {
    $url = "https://api.ingresse.com/shop?apikey=" . self::API_KEY . "&usertoken={$userToken}";
    $response = wp_remote_post($url, [
      'body' => $data,
      'headers' => [
        'Authorization' => "Bearer " . $jwt,
      ],
    ]);

    error_log(json_encode($data));

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("[startTransaction] - " . $error_message);
    } else {
      $body = wp_remote_retrieve_body($response);
      error_log("[startTransaction] - " . $body);
      return json_decode($body);
    }
  }

  public function getUsersAvailableToTransfer($term, $userToken)
  {
    $url = "https://api.ingresse.com/search/transfer/user?apikey=" . self::API_KEY . "&term={$term}&usertoken={$userToken}";
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
    } else {
      $body = wp_remote_retrieve_body($response);
      return json_decode($body)->responseData;
    }
  }

  public function cancelTransaction($transactionId, $userToken, $jwt)
  {
    if (empty($transactionId)) return false;
    $url = "https://api.ingresse.com/shop/{$transactionId}/cancel?apikey=" . self::API_KEY . "&usertoken={$userToken}";
    $response = wp_remote_post($url, [
      'headers' => [
        'Authorization' => "Bearer " . $jwt,
      ],
    ]);

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("[cancelTransaction] - " . $error_message);
    } else {
      $body = wp_remote_retrieve_body($response);
      error_log("[cancelTransaction] - " . $body);
      return json_decode($body);
    }
  }

  public function updateUserData($data, $userId, $userToken)
  {
    $url = "https://api.ingresse.com/users/{$userId}?apikey=" . self::API_KEY . "&usertoken={$userToken}";
    $response = wp_remote_request(
      $url,
      [
        'method' => 'PUT',
        'headers' => [
          'Content-Type' => 'application/json'
        ],
        'body' => json_encode($data)
      ]
    );
    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("[updateUserData] - " . $error_message);
    } else {
      $body = wp_remote_retrieve_body($response);
      return json_decode($body);
    }
  }

  public function getTicketsByPasskey($eventId, $passkey)
  {
    $url = "https://api.ingresse.com/event/{$eventId}/session/0/tickets?apikey=" . self::API_KEY . "&sessionId=0&passkey={$passkey}";
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("[getTicketsByPasskey] - " . $error_message);
    } else {
      $body = wp_remote_retrieve_body($response);
      error_log("[getTicketsByPasskey] - " . $body);
      return json_decode($body);
    }
  }

  public function getTransactionDetails($transactionId, $userToken)
  {
    $url = "https://api.ingresse.com/sale/{$transactionId}?apikey=" . self::API_KEY . "&usertoken={$userToken}";
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("[getTicketsByPasskey] - " . $error_message);
    } else {
      $body = wp_remote_retrieve_body($response);
      error_log("[getTicketsByPasskey] - " . $body);
      return json_decode($body);
    }
  }

  public function getAllEvents()
  {
    $url = 'https://event-search.ingresse.com/31?from=now-1d&size=200&offset=0';
    $response = wp_remote_get($url);
    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("[getTicketsByPasskey] - " . $error_message);
    } else {
      $body = wp_remote_retrieve_body($response);
      error_log("[getTicketsByPasskey] - " . $body);
      return json_decode($body);
    }
  }

  public function getUser($userId, $userToken)
  {
    $url = "https://api.ingresse.com/users/{$userId}?apikey=" . self::API_KEY . "&usertoken={$userToken}";
    $response = wp_remote_get($url);

    if (is_wp_error($response)) {
      $error_message = $response->get_error_message();
      error_log("[getUser] - " . $error_message);
    } else {
      $body = wp_remote_retrieve_body($response);
      error_log("[getUser] - " . $body);
      return json_decode($body);
    }
  }
}
