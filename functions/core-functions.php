<?php

function format_price_to_brl($price)
{
    $float_price = floatval($price);
    return number_format($float_price, '2', ',', '.');
}

function get_day_by_date($datetime)
{
    $dataObj = new DateTime($datetime);
    $dia_da_semana = $dataObj->format('l');

    $dias_semana = array(
        'Monday' => 'Segunda-feira',
        'Tuesday' => 'Terça-feira',
        'Wednesday' => 'Quarta-feira',
        'Thursday' => 'Quinta-feira',
        'Friday' => 'Sexta-feira',
        'Saturday' => 'Sábado',
        'Sunday' => 'Domingo'
    );

    return $dias_semana[$dia_da_semana];
}

function get_day_shortname_by_date($datetime)
{
  $dataObj = new DateTime($datetime);
  $dia_da_semana = $dataObj->format('l');

  $dias_semana = array(
    'Monday' => 'SEG',
    'Tuesday' => 'TER',
    'Wednesday' => 'QUA',
    'Thursday' => 'QUI',
    'Friday' => 'SEX',
    'Saturday' => 'SAB',
    'Sunday' => 'DOM'
  );

  return $dias_semana[$dia_da_semana];
}

function get_datetime_formated($datetime)
{
    $dataObj = new DateTime($datetime);

    return $dataObj->format('d/m/Y H:i');
}

function get_datetime_short_formated($datetime)
{
  $dataObj = new DateTime($datetime);

  return $dataObj->format('d/m - H:i');
}

function user_has_address($user) {
  if(is_array($user)) {
    $user_address = $user['address'];

    $address_is_empty =
      empty($user_address['city']) ||
      empty($user_address['district']) ||
      empty($user_address['number']) ||
      empty($user_address['state']) ||
      empty($user_address['street']) ||
      empty($user_address['zipcode']);

    return !$address_is_empty;
  }
  elseif (is_object($user)) {
    $user_address = $user->address;

    $address_is_empty =
      empty($user_address->city) ||
      empty($user_address->district) ||
      empty($user_address->number) ||
      empty($user_address->state) ||
      empty($user_address->street) ||
      empty($user_address->zipcode);

    return !$address_is_empty;
  }

  return false;
}