<?php

function meus_dados_callback()
{
    if (empty($_COOKIE['wp_ing_user'])) wp_redirect(get_site_url() . '/login');

    $user = json_decode(stripslashes($_COOKIE['wp_ing_user']));

    if (empty($user)) wp_redirect(get_site_url() . '/login');

    $phone_number = is_object($user->phone) ? $user->phone->number : $user->phone;

    ob_start();
    require_once INGS_MEUS_DADOS_PATH . 'ings-meus-dados-view.php';
    return ob_get_clean();
}

add_shortcode('meus_dados', 'meus_dados_callback');
