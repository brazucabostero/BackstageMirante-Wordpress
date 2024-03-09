<?php

function endereco_callback()
{
    if (empty($_COOKIE['wp_ing_user'])) wp_redirect(get_site_url() . '/login');

    $user = json_decode(stripslashes($_COOKIE['wp_ing_user']));

    if (empty($user)) wp_redirect(get_site_url() . '/login');

    ob_start();
    require_once INGS_ENDERECO_PATH . 'ings-endereco-view.php';
    return ob_get_clean();
}

add_shortcode('endereco', 'endereco_callback');
