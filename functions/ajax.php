<?php

add_action('wp_enqueue_scripts', 'enqueue_ajax_scripts');
function enqueue_ajax_scripts()
{
  wp_enqueue_script('ajax-script', get_template_directory_uri() . '/js/my-ajax-script.js', array('jquery'));
  wp_localize_script('ajax-script', 'my_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

add_action('wp_ajax_ings_fluxo_compra_step_1', 'ings_fluxo_compra_step_1');
add_action('wp_ajax_nopriv_fluxo_compra_step_1', 'ings_fluxo_compra_step_1');
function ings_fluxo_compra_step_1()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (!isset($_GET['event_id'])) return false;

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-1.php';
  die();
}

add_action('wp_ajax_ings_fluxo_compra_step_2', 'ings_fluxo_compra_step_2');
add_action('wp_ajax_nopriv_ings_fluxo_compra_step_2', 'ings_fluxo_compra_step_2');
function ings_fluxo_compra_step_2()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (empty($_POST['sessions'])) return false;


  $_SESSION['step_1_request'] = $_POST;

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-2.php';
  die();
}

add_action('wp_ajax_ings_fluxo_compra_step_3', 'ings_fluxo_compra_step_3');
add_action('wp_ajax_nopriv_ings_fluxo_compra_step_3', 'ings_fluxo_compra_step_3');
function ings_fluxo_compra_step_3()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (!isset($_COOKIE['wp_ing_user'])) return false;

  $userJson = stripslashes($_COOKIE['wp_ing_user']);

  $user = json_decode($userJson, true);
  $user_requested_data = $_SESSION['step_1_request'];

  $event_id = $user_requested_data['event_id'];
  $event = $_SESSION['current_event'];
  $sessionAvailable = $_SESSION['sessions_available'];
  $items = $user_requested_data['sessions'];
  $selectedTickets = [];
  $isTrasnferRequired = $event->data->attributes->ticketTransferRequired->value;

  $canNextStep = !$isTrasnferRequired;

  foreach ($items as $session) {
    foreach ($session['types'] as $type) {
      if ($type['quantity'] == "0") continue;

      $tickets = array_fill(0, $type['quantity'], ["session" => $session, "type" => $type, "user" => null, "session_name" => $session['name']]);

      $selectedTickets = array_merge(
        $selectedTickets,
        $tickets
      );
    }
  }

  $_SESSION['selected_tickets'] = $selectedTickets;

  $_SESSION['selected_transfer_ticket'] = null;

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-3.php';
  die();
}

add_action('wp_ajax_ings_fluxo_compra_transfer_ticket', 'ings_fluxo_compra_transfer_ticket');
add_action('wp_ajax_nopriv_ings_fluxo_compra_transfer_ticket', 'ings_fluxo_compra_transfer_ticket');
function ings_fluxo_compra_transfer_ticket()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (!isset($_COOKIE['wp_ing_user'])) return false;

  $userJson = stripslashes($_COOKIE['wp_ing_user']);

  $user = json_decode($userJson, true);
  $user_requested_data = $_SESSION['step_1_request'];
  $transferRequestData = $_POST;

  $event_id = $user_requested_data['event_id'];
  $event = $_SESSION['current_event'];
  $sessionAvailable = $_SESSION['sessions_available'];
  $items = $user_requested_data['sessions'];
  $isTrasnferRequired = $event->data->attributes->ticketTransferRequired->value;

  $tranferTo = $user;
  $ticketId = $transferRequestData["ticketId"];

  if ($transferRequestData["transfer"]) {
    $choicedUser = $_SESSION["users_transfer_list"][$transferRequestData["userId"]];
    $tranferTo = [
      "id" => $choicedUser->id,
      "name" => $choicedUser->name,
      "picture" => $choicedUser->picture,
      "email" => $choicedUser->email
    ];
    $ticketId = $_SESSION['selected_transfer_ticket'];
  }

  if ($transferRequestData["remove"]) {
    $tranferTo = null;
  }

  $_SESSION['selected_tickets'][$ticketId]["user"] = $tranferTo;
  $_SESSION['selected_transfer_ticket'] = null;
  $selectedTickets = $_SESSION['selected_tickets'];
  $canNextStep = true;

  if ($isTrasnferRequired) {
    foreach ($selectedTickets as $ticket) {
      if (!$ticket["user"]) {
        $canNextStep = false;
      }
    }
  }

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-3.php';
  die();
}

add_action('wp_ajax_ings_fluxo_compra_transfer_not_user_ticket', 'ings_fluxo_compra_transfer_not_user_ticket');
add_action('wp_ajax_nopriv_ings_fluxo_compra_transfer_not_user_ticket', 'ings_fluxo_compra_transfer_not_user_ticket');
function ings_fluxo_compra_transfer_not_user_ticket()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (!isset($_COOKIE['wp_ing_user'])) return false;

  $userJson = stripslashes($_COOKIE['wp_ing_user']);

  $user_requested_data = $_SESSION['step_1_request'];
  $transferRequestData = $_POST;

  $event_id = $user_requested_data['event_id'];
  $event = $_SESSION['current_event'];
  $sessionAvailable = $_SESSION['sessions_available'];
  $items = $user_requested_data['sessions'];
  $isTrasnferRequired = $event->data->attributes->ticketTransferRequired->value;

  $ticketId = $transferRequestData["ticketId"];

  if ($transferRequestData["transfer"]) {
    $tranferTo = [
      "id" => $transferRequestData["email"],
      "name" => $transferRequestData["email"],
      "picture" => $transferRequestData["email"],
      "email" => $transferRequestData["email"]
    ];
    $ticketId = $_SESSION['selected_transfer_ticket'];
  }

  $_SESSION['selected_tickets'][$ticketId]["user"] = $tranferTo;
  $_SESSION['selected_transfer_ticket'] = null;
  $selectedTickets = $_SESSION['selected_tickets'];
  $canNextStep = true;

  if ($isTrasnferRequired) {
    foreach ($selectedTickets as $ticket) {
      if (!$ticket["user"]) {
        $canNextStep = false;
      }
    }
  }

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-3.php';
  die();
}

add_action('wp_ajax_ings_fluxo_compra_cancel_transfer_step', 'ings_fluxo_compra_cancel_transfer_step');
add_action('wp_ajax_nopriv_ings_fluxo_compra_cancel_transfer_step', 'ings_fluxo_compra_cancel_transfer_step');
function ings_fluxo_compra_cancel_transfer_step()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (!isset($_COOKIE['wp_ing_user'])) return false;

  $userJson = stripslashes($_COOKIE['wp_ing_user']);

  $user = json_decode($userJson, true);
  $user_requested_data = $_SESSION['step_1_request'];
  $transferRequestData = $_POST;

  $event_id = $user_requested_data['event_id'];
  $event = $_SESSION['current_event'];
  $sessionAvailable = $_SESSION['sessions_available'];
  $items = $user_requested_data['sessions'];
  $isTrasnferRequired = $event->data->attributes->ticketTransferRequired->value;

  $selectedTickets = $_SESSION['selected_tickets'];
  $canNextStep = true;

  if ($isTrasnferRequired) {
    foreach ($selectedTickets as $ticket) {
      if (!$ticket["user"]) {
        $canNextStep = false;
      }
    }
  }

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-3.php';
  die();
}

add_action('wp_ajax_ings_fluxo_compra_transfer_step', 'ings_fluxo_compra_transfer_step');
add_action('wp_ajax_nopriv_ings_fluxo_compra_transfer_step', 'ings_fluxo_compra_transfer_step');
function ings_fluxo_compra_transfer_step()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (!isset($_COOKIE['wp_ing_user'])) return false;

  $userJson = stripslashes($_COOKIE['wp_ing_user']);

  $user = json_decode($userJson, true);
  $user_requested_data = $_SESSION['step_1_request'];
  $transferRequestData = $_POST;

  $event_id = $user_requested_data['event_id'];
  $event = $_SESSION['current_event'];
  $sessionAvailable = $_SESSION['sessions_available'];
  $items = $user_requested_data['sessions'];

  $selectedTicket = $_SESSION['selected_tickets'][$transferRequestData["ticketId"]];
  $_SESSION['selected_transfer_ticket'] = $transferRequestData["ticketId"];

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-3-transfer.php';
  die();
}

add_action('wp_ajax_ings_fluxo_compra_step_4', 'ings_fluxo_compra_step_4');
add_action('wp_ajax_nopriv_ings_fluxo_compra_step_4', 'ings_fluxo_compra_step_4');
function ings_fluxo_compra_step_4()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (!isset($_COOKIE['wp_ing_user'])) return false;

  $userJson = stripslashes($_COOKIE['wp_ing_user']);
  $user = json_decode($userJson);
  $user_requested_data = $_SESSION['step_1_request'];
  $sessions = $user_requested_data['sessions'];
  $event_id = $user_requested_data['event_id'];
  $passkey = $user_requested_data['passkey'];
  $sessions_with_users = $_SESSION['selected_tickets'];
  $domain = get_site_url();
  $tickets = [];

  foreach ($sessions as $session) {
    foreach ($session['types'] as $type) {

      if (intval($type['quantity']) <= 0) continue;

      $types_with_users = array_filter($sessions_with_users, function ($session) use ($type) {
        return $session['type']['id'] === $type['id'];
      });

      $holders_ids = array_map(function ($t) {
        return $t['user']['id'];
      }, $types_with_users);

      $holders = [];
      foreach ($holders_ids as $holder_id) {
        $holder = new stdClass();
        $holder->email = $holder_id ?? $user->id;

        $holders[] = $holder;
      }
      $tickets[] = [
        'quantity' => intval($type['quantity']),
        'guestTypeId' => $type['id'],
        'holder' => $holders
      ];
    }
  }

  $request = [
    'userId' => $user->id,
    'eventId' => $event_id,
    'passkey' => $passkey,
    'domain' => $domain,
    'tickets' => $tickets
  ];

  $ingresse_API = new IngresseAPI();
  $transaction = $ingresse_API->startTransaction($request, $user->token, $user->jwt);

  $_SESSION['request_transaction'] = $request;
  $_SESSION['transaction'] = $transaction;

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-4.php';
  die();
}

add_action('wp_ajax_ings_save_customer_data', 'save_customer_data');
add_action('wp_ajax_nopriv_save_customer_data', 'save_customer_data');
function save_customer_data()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  if (empty($_POST)) {
    status_header(400);
    wp_send_json_error("Dados ausentes");
    die();
  }

  $cep = $_POST['cep'];
  $logradouro = $_POST['logradouro'];
  $complemento = $_POST['complemento'];
  $numero = $_POST['numero'];
  $bairro = $_POST['bairro'];
  $cidade = $_POST['cidade'];
  $estado = $_POST['estado'];
  $telefone = $_POST['telefone'];

  if (empty($cep)) {
    status_header(400);
    wp_send_json_error('cep é obrigatório');
    die();
  }
  if (empty($logradouro)) {
    status_header(400);
    wp_send_json_error('logradouro é obrigatório');
    die();
  }
  if (empty($numero)) {
    status_header(400);
    wp_send_json_error('numero é obrigatório');
    die();
  }
  if (empty($bairro)) {
    status_header(400);
    wp_send_json_error('bairro é obrigatório');
    die();
  }
  if (empty($cidade)) {
    status_header(400);
    wp_send_json_error('cidade é obrigatório');
    die();
  }
  if (empty($estado)) {
    status_header(400);
    wp_send_json_error('estado é obrigatório');
    die();
  }
  if (empty($telefone)) {
    status_header(400);
    wp_send_json_error('telefone é obrigatório');
    die();
  }

  $_SESSION['customer_data'] = $_POST;
  $data = new stdClass();
  $address = new stdClass();
  $address->city = $cidade;
  $address->complement = $complemento;
  $address->district = $bairro;
  $address->number = $numero;
  $address->state = $estado;
  $address->street = $logradouro;
  $address->zip = $cep;
  $address->zipcode = $cep;

  $data->address = $address;

  $userJson = stripslashes($_COOKIE['wp_ing_user']);
  $userLogged = json_decode($userJson);
  $userLogged->address = $address;

  $ingresse_api = new IngresseAPI();
  $ingresse_api->updateUserData($data, $userLogged->id, $userLogged->token);

  $transaction = $_SESSION['transaction'];
  setcookie('wp_ing_user', json_encode($userLogged), time() + 3600, '/');
  get_template_part(
    'shortcodes/ings-fluxo-compra-ingresso/components/form-pagamento',
    '',
    [
      'transaction' => $transaction,
      'user' => $userLogged
    ]
  );
  die();
}

add_action('wp_ajax_get_users_available_to_transfer_fragment', 'get_users_available_to_transfer_fragment');
add_action('wp_ajax_nopriv_get_users_available_to_transfer_fragment', 'get_users_available_to_transfer_fragment');
function get_users_available_to_transfer_fragment()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  $userJson = stripslashes($_COOKIE['wp_ing_user']);
  $userLogged = json_decode($userJson, true);

  $session = $_POST['session'];
  $type = $_POST['type'];
  $term = $_POST['term'];

  $ingresse_api = new IngresseAPI();
  $users = $ingresse_api->getUsersAvailableToTransfer($term, $userLogged['token']);

  $_SESSION["users_transfer_list"] = $users;

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-tranfer-users-list.php';
  die();
}

add_action('wp_ajax_update_ticket_holder', 'update_ticket_holder');
add_action('wp_ajax_nopriv_update_ticket_holder', 'update_ticket_holder');
function update_ticket_holder()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  $session = $_POST['session'];
  $type = $_POST['type'];
  $user_id = $_POST['id'];
  $user_email = $_POST['email'];

  $_SESSION['step_1_request']['sessions'][$session]['types'][$type]['holder']['id'] = $user_id;
  $_SESSION['step_1_request']['sessions'][$session]['types'][$type]['holder']['email'] = $user_email;

  die();
}

add_action('wp_ajax_ings_start_transaction', 'ings_start_transaction');
add_action('wp_ajax_nopriv_ings_start_transaction', 'ings_start_transaction');
function ings_start_transaction()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die();
  }

  $ingresse_API = new IngresseAPI();
  $sessions = $_SESSION['selected_tickets'];
  $userJson = stripslashes($_COOKIE['wp_ing_user']);
  $userLogged = json_decode($userJson, true);
  $user_id = $userLogged['id'];
  $user_requested_data = $_SESSION['step_1_request'];
  $event_id = $user_requested_data['event_id'];
  $domain = get_site_url();
  $tickets = [];
  foreach ($sessions as $session) {
    $holder = new stdClass();
    $holder->email = $session->user['holder']['id'];
    $holders = [];
    for ($i = 0; $i < intval($session->type['quantity']); $i++) {
      $holders[$i] = $holder;
    }

    $tickets[] = [
      'quantity' => intval($session->type['quantity']),
      'guestTypeId' => $session->type['id'],
      'holder' => $holders
    ];
  }

  $tickets = array_filter($tickets, function ($ticket) {
    return intval($ticket['quantity']) > 0;
  });

  $request = [
    'userId' => $user_id,
    'eventId' => $event_id,
    'domain' => $domain,
    'tickets' => $tickets
  ];

  $transaction = $ingresse_API->startTransaction($request, $userLogged['token'], $userLogged['jwt']);

  $_SESSION['request_transaction'] = $request;
  $_SESSION['transaction'] = $transaction;

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-fluxo-compra-step-5.php';
  die();
}

add_action('wp_ajax_ings_pay_transaction', 'ings_pay_transaction');
add_action('wp_ajax_nopriv_ings_pay_transaction', 'ings_pay_transaction');
function ings_pay_transaction()
{
  if (!defined('DOING_AJAX')) {
    status_header(400);
    die('Não autorizado');
  }

  $type = $_POST['type'];
  $cpf = $_POST['customer_cpf'];

  $ingresse_api = new IngresseAPI();

  $userJson = stripslashes($_COOKIE['wp_ing_user']);
  $userLogged = json_decode($userJson, true);
  $transaction = $_SESSION['transaction'];

  $request = new stdClass();

  if ($type === 'CartaoCredito') {
    if (
      empty($_POST['customer_numero_cartao']) ||
      empty($_POST['customer_validade_cartao']) ||
      empty($_POST['customer_cvv_cartao']) ||
      empty($_POST['customer_nome_cartao']) ||
      empty($_POST['customer_nascimento']) ||
      empty($_POST['customer_cartao_parcelas'])
    ) {
      status_header(400);
      die('Cartão invalido');
    }

    $expiracy = $_POST['customer_validade_cartao'];
    $expiracy_month = explode('/', $expiracy)[0];
    $expiracy_year = explode('/', $expiracy)[1];

    $credit_card = new stdClass();
    $credit_card->birthdate = $_POST['customer_nascimento'];
    $credit_card->number = $_POST['customer_numero_cartao'];
    $credit_card->cvv = $_POST['customer_cvv_cartao'];
    $credit_card->cpf = $cpf;
    $credit_card->expiracyMonth = $expiracy_month;
    $credit_card->expiracyYear = $expiracy_year;
    $credit_card->holderName = $_POST['customer_nome_cartao'];
    $request->installments = $_POST['customer_cartao_parcelas'];

    $request->creditcard = $credit_card;
    $request->tds_reference_id  = $_POST["tds_reference_id"];
  }

  $request->eventId = $_SESSION['step_1_request']['event_id'];
  $request->userId = $userLogged['id'];
  $request->transactionId = $transaction->responseData->data->transactionId;
  $request->paymentMethod = $type;
  $request->installments = $request->installments ?? 1;
  $request->document = $userLogged['document']['number'];

  $_SESSION['requested_payment'] = $request;
  $_SESSION['payment_response'] = $ingresse_api->processPayment($request, $userLogged['token'], $userLogged['jwt']);

  $payment_response = $_SESSION['payment_response'];

  if ($type === 'pix') require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/payment-return/pix.php';
  else if ($type === 'CartaoCredito') require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/payment-return/cartao_credito.php';
  else if ($type === 'wireTransfer') require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/payment-return/tranferencia.php';
  else if ($type === 'boleto') require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/payment-return/boleto.php';
  else require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/payment-return/without_payment_method.php';
  die();
}

add_action('wp_ajax_ings_cancel_transaction', 'ings_cancel_transaction');
add_action('wp_ajax_nopriv_ings_cancel_transaction', 'ings_cancel_transaction');
function ings_cancel_transaction()
{
  $userJson = stripslashes($_COOKIE['wp_ing_user']);
  $userLogged = json_decode($userJson, true);
  $transaction = $_SESSION['transaction'];
  $transaction_id = $transaction->responseData->data->transactionId;

  if (!empty($transaction_id)) {
    $ingresse_api = new IngresseAPI();
    $result = $ingresse_api->cancelTransaction($transaction_id, $userLogged['token'], $userLogged['jwt']);;
    unset($_SESSION['step_1_request']);
    unset($_SESSION['current_event']);
    unset($_SESSION['sessions_available']);
    unset($_SESSION['customer_data']);
    unset($_SESSION['request_transaction']);
    unset($_SESSION['transaction']);
    unset($_SESSION['requested_payment']);
    unset($_SESSION['payment_response']);
  }
  die();
}

add_action('wp_ajax_ings_get_tickets_by_passkey', 'ings_get_tickets_by_passkey');
add_action('wp_ajax_nopriv_ings_get_tickets_by_passkey', 'ings_get_tickets_by_passkey');
function ings_get_tickets_by_passkey()
{
  $event_id = $_POST['event_id'];
  $passkey = $_POST['passkey'];
  if (empty($event_id)) {
    status_header(400);
    die();
  }
  $ingresse_api = new IngresseAPI();
  $result = $ingresse_api->getTicketsByPasskey($event_id, $passkey);
  $sessions = $result->responseData;

  $ticketsDiff = array_filter($sessions, function ($session) {
    return array_filter($session->type, function ($type) {
      return $type->hidden == true;
    });
  });

  if (empty($ticketsDiff)) {
    status_header(400);
    die('Código já utilizado ou inválido. Verifique e tente novamente');
  }

  require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/ings-passkey-tickets.php';
  die();
}

add_action('wp_ajax_ings_check_pix_was_paid', 'ings_check_pix_was_paid');
add_action('wp_ajax_nopriv_ings_check_pix_was_paid', 'ings_check_pix_was_paid');
function ings_check_pix_was_paid()
{
  $transaction = $_SESSION['transaction'];
  $transaction_id = $transaction->responseData->data->transactionId;
  $userJson = stripslashes($_COOKIE['wp_ing_user']);
  $user = json_decode($userJson);

  $ingresse_api = new IngresseAPI();
  $result = $ingresse_api->getTransactionDetails($transaction_id, $user->token)->responseData;

  if ($result->status === 'approved') {
    require_once INGS_FLUXO_COMPRA_INGRESSO_PATH . 'components/payment-return/pix_paid.php';
    die();
  } else {
    status_header(400);
    die('Pix ainda não confirmado. <br>Geralmente a confirmação de pagamento pode levar alguns minutos.');
  }
}

add_action('wp_ajax_ings_update_user', 'ings_update_user');
add_action('wp_ajax_nopriv_ings_update_user', 'ings_update_user');
function ings_update_user()
{
  unset($_POST['action']);
  $user = json_decode(stripslashes($_COOKIE['wp_ing_user']));

  $ingresse_api = new IngresseAPI();
  $response = $ingresse_api->updateUserData($_POST, $user->id, $user->token);

  if (!empty($response->responseError) && $response->responseError->code == '1144') {
    status_header(400);
    die('Senha deve possuir mais do que 8 caracteres. code: #1144');
  }

  if (!empty($response->responseError) && $response->responseError->code == '6056') {
    status_header(400);
    die('Senha atual incorreta. code: #6056');
  }

  if (!empty($response->responseError) && $response->responseError->code == '1171') {
    status_header(400);
    die('Senha não pode conter números sequenciais. code: #1171');
  }

  if (!empty($response->responseError) && $response->responseError->code == '1146') {
    status_header(400);
    die('Campo de telefone está inválido. code: #1146');
  }

  if (!empty($response->responseError) && $response->responseError->code == '6063') {
    status_header(400);
    die('E-mail já está em uso. code: #6063');
  }

  if (!empty($response->responseError) && $response->responseError->code == '0') {
    status_header(400);
    die('Erro no upload da imagem. code: #0');
  }

  sleep(3);
  $userResponse = $ingresse_api->getUser($user->id, $user->token);
  $user = (object) array_merge((array) $user, (array) $userResponse->responseData);

  echo json_encode($user);
  exit();
}
add_action('wp_ajax_ings_check_status_transaction', 'ings_check_status_transaction');
add_action('wp_ajax_nopriv_ings_check_status_transaction', 'ings_check_status_transaction');
function ings_check_status_transaction(){
  $ingresse_api           = new IngresseAPI();
  $transaction_id = $_POST["transaction_id"];
  $userJson       = stripslashes($_COOKIE['wp_ing_user']);
  $user           = json_decode($userJson);
  $result         = $ingresse_api->getTransactionDetails($transaction_id, $user->token);
  echo json_encode($result);
  wp_die();
}

add_action('wp_ajax_ings_get_data_user', 'ings_get_data_user');
add_action('wp_ajax_nopriv_ings_get_data_user', 'ings_get_data_user');
function ings_get_data_user(){
  $userJson       = stripslashes($_COOKIE['wp_ing_user']);
  $user           = json_decode($userJson);
  $userDataResponse   = [
    "email" => $user->email,
    'id'    => $user->userId
  ];
  echo json_encode($userDataResponse);
  wp_die();
}