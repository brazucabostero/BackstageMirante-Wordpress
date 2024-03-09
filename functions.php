<?php

/**
 * Theme functions and definitions.
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

if (!defined('ABSPATH')) {
  exit; // Exit if accessed directly.
}

session_start();

define('HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0');
define('INGS_FLUXO_COMPRA_INGRESSO_PATH', get_stylesheet_directory() . '/shortcodes/ings-fluxo-compra-ingresso/');
define('INGS_MEUS_DADOS_PATH', get_stylesheet_directory() . '/shortcodes/ings-meus-dados/');
define('INGS_ENDERECO_PATH', get_stylesheet_directory() . '/shortcodes/ings-endereco/');

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */

require_once "functions/core-functions.php";
require_once "functions/classes.php";
require_once "functions/ajax.php";
require_once "shortcodes/ings-fluxo-compra-ingresso/ings-fluxo-compra-ingresso.php";
require_once "shortcodes/ings-meus-dados/ings-meus-dados.php";
require_once "shortcodes/ings-endereco/ings-endereco.php";

function hello_elementor_child_scripts_styles()
{

  wp_enqueue_style(
    'hello-elementor-child-style',
    get_stylesheet_directory_uri() . '/style.css',
    [
      'hello-elementor-theme-style',
    ],
    HELLO_ELEMENTOR_CHILD_VERSION
  );
  wp_enqueue_style(
    'bootstrap',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css',
    [],
    '5.3'
  );
  wp_enqueue_script(
    'bootstrap',
    'https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js',
    [],
    '5.3'
  );
  wp_enqueue_script(
    'jquery-mask',
    get_stylesheet_directory_uri() . '/lib/js/jquery.mask.min.js',
    [],
    '5.3'
  );
}

add_action('wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20);

function getEventsHome()
{
    $api_url = 'https://api.ingresse.com/event?apikey=tDgFYzwDkGVTxWeAgQxs73Hrs74CaNn2';
    $response = wp_remote_get($api_url);
    $events_data = json_decode(wp_remote_retrieve_body($response))->responseData->data;

    ob_start();
    ?>
    <div class='list'>
        <?php $current_year = ''; ?>
        <?php foreach ($events_data as $event) : ?>
            <?php
            if (stripos($event->title, 'After') !== false) {
                continue;
            }

            $event_title = $event->title;
            $event_description = strip_tags($event->description);
            $event_dates = array_map(function($date) {
                return $date->dateTime->date;
            }, $event->date);

            sort($event_dates);

            $event_dates_formatted = '';
            if (count($event_dates) == 1) {
                $event_dates_formatted = format_single_date($event_dates[0]);
            } elseif (count($event_dates) > 1) {
                $event_dates_formatted = format_multiple_dates($event_dates);
            }

            $event_type = $event_description;
            $event_post_id = get_post_id_by_event_id($event->id);
            $event_link = $event_post_id ? get_permalink($event_post_id) : '#';

            // $event_year = substr($event_dates[0], -4);

            // if ($event_year == '2025' && $event_year != $current_year) {
            //     echo '<center><p class="data" style="font-size:50px;">2025</p></center> <hr>';
            //     $current_year = $event_year;
            // }
            ?>

            <div class="ings-home-event-list-item">
                <div class="ings-home-event-list-item-show">
                    <p class="data"><?= $event_dates_formatted; ?></p>
                    <p class="show"><?= $event_title; ?></p>
                </div>
                <div class="ings-home-event-list-item-options">
                    <ul>
                        <li><i class="fa fa-check icon"></i>
                            <span class="text-icon">Acesso Exclusivo</span>
                        </li>
                        <li><i class="fa fa-check icon"></i>
                            <span class="text-icon">Open Bar & Open Food</span>
                        </li>
                        <li><i class="fa fa-check icon"></i>
                            <span class="text-icon">Banheiros Exclusivos</span>
                        </li>
                        <li><i class="fa fa-check icon"></i>
                            <span class="text-icon"><?= $event_type; ?></span>
                        </li>
                    </ul>
                </div>
                <div class="ings-home-event-list-buttom button-wrapper">
                    <a class="button" href="<?= $event_link; ?>" target="_self">COMPRE AQUI <i class="fa fa-arrow-right"></i></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php

    return ob_get_clean();
}

function get_post_id_by_event_id($event_id) {
    global $wpdb;
    $query = $wpdb->prepare("SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = 'mirante_event_id' AND meta_value = %d", $event_id);
    return $wpdb->get_var($query);
}

function format_single_date($date) {
    $date_obj = DateTime::createFromFormat('d/m/Y', $date);
    setlocale(LC_TIME, 'pt_BR.utf-8');
    return strftime('%e de %B de %Y', $date_obj->getTimestamp());
}

function format_multiple_dates($dates) {
    $formatted_dates = array_map(function($date) {
        $date_obj = DateTime::createFromFormat('d/m/Y', $date);
        return strftime('%e', $date_obj->getTimestamp());
    }, $dates);
    $month = strftime('%B', DateTime::createFromFormat('d/m/Y', $dates[0])->getTimestamp());
    $year = DateTime::createFromFormat('d/m/Y', $dates[0])->format('Y');
    return implode(' e ', $formatted_dates) . " de $month de $year";
}




add_shortcode('eventos_home', 'getEventsHome');

add_action('init', 'add_get_val');
function add_get_val()
{
  global $wp;
  $wp->add_query_var('event');
}

function getParam($param)
{
  if (get_query_var($param)) {
    return get_query_var($param);
  }
}

function infosEvent()
{
  $param = getParam('event');
  if (!$param) {
    return '';
  }

  $request = wp_remote_get('https://event.ingresse.com/public/' . $param . '?companyId=31');
  if (is_wp_error($request)) {
    return 'ERRO';
  }
  $body = wp_remote_retrieve_body($request);
  $data = json_decode($body);

  ob_start();

?>

  <html>

  <body>
    <div class="container-detalhes">
      <p class="title">
        <?= $data->data->title ?>
      </p>
      <p class="descricao">
        <?= strip_tags($data->data->description) ?>
      </p>
      <div class="list-items">
        <div class="item">
          <div class="icon-item">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ingress-desc-icon-01.png" />
          </div>
          <div class="text-item">
            Acesso Exclusivo + Pista Premium
          </div>
        </div>

        <div class="item">
          <div class="icon-item">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ingress-desc-icon-02.png" />
          </div>
          <div class="text-item">
            Open Bar Premium (dentro do Backstage Mirante)
          </div>
        </div>

        <div class="item">
          <div class="icon-item">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ingress-desc-icon-03.png" />
          </div>
          <div class="text-item">
            Open Food (dentro do Backstage Mirante)
          </div>
        </div>

        <div class="item">
          <div class="icon-item">
            <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/ingress-desc-icon-04.png" />
          </div>
          <div class="text-item">
            After show de 2h para aproveitar ao máximo
          </div>
        </div>
      </div>
      <div class="descricao">
        <p>
          A retirada do ingresso para acesso à pista premium será feita na recepção do camarote no dia do evento
          com documento com foto e cartão de crédito que foi efetuada a compra. (Caso esse tenha sido o meio de
          pagamento).
        </p>
      </div>

      <?= do_shortcode("[botao_comprar]"); ?>

      <hr>
      <div class="location">
        <p class="title-location">
          Confira a localização
        </p>
        <div class="description-location">
          O acesso é feito pelo edíficio garagem do Allianz Parque:
          <br>
          <br>
          <strong> Entrada pelo Portao C1</strong>, exclusivamente pelos elevadores sociais do estacionamento, até
          o sétimo andar.
        </div>
        <br>
        <div class="map-location">
          <div style="width: 100%">
            <iframe width="100%" height="416" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?width=100%25&amp;height=416&amp;hl=en&amp;q=Allianz%20Parque+(My%20Business%20Name)&amp;t=&amp;z=14&amp;ie=UTF8&amp;iwloc=B&amp;output=embed"></iframe>
          </div>
          </iframe>
        </div>
      </div>
    </div>
  </body>
<script>

</script>
  </html>
<?php

  return ob_get_clean();
}

add_shortcode('infos_detalhes_evento', 'infosEvent');

function imageEvent()
{
  $param = getParam('event');
  if (!$param) {
    return '';
  }
  $request = wp_remote_get('https://event.ingresse.com/public/' . $param . '?companyId=31');
  if (is_wp_error($request)) {
    return 'ERRO';
  }
  $body = wp_remote_retrieve_body($request);
  $data = json_decode($body);

  if ($data) {
    $img = '<img class="img-event" src="' . $data->data->poster->large . '" >';
  }

  return $img;
}

add_shortcode('imagem_detalhes_evento', 'imageEvent');

add_filter('http_request_args', function ($args) {
  $args['timeout'] = 20;
  $args['blocking'] = true;
  return $args;
});


function cabecalhoSite()
{
  if (isset($_COOKIE["wp_ing_user"])) {
    $logado = true;
    $userJson = $_COOKIE['wp_ing_user'];
    $userData = json_decode(stripslashes($userJson));
  } else {
    $logado = false;
  }

  ob_start();
?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script>


      document.addEventListener("DOMContentLoaded", function() {
        jQuery('.dropdown').click(function(e) {
          e.preventDefault();
          if (jQuery('.dropdown').find('.dropdown-menu').is(":hidden")) {
            jQuery('.dropdown-menu').addClass('show');
          }
        });

        jQuery('.exit').click(function(e) {
          e.preventDefault();
          document.cookie = "wp_ing_user=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
          if (window.location.pathname == '/carteira/') {
            window.location.replace('/');
          } else {
            location.href = '<?= get_site_url(); ?>';
          }
        });

        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenuList = document.querySelector('.mobile-menu-list');

        mobileMenuButton.addEventListener('click', function(event) {
          event.preventDefault();
          mobileMenuList.classList.toggle('open');
          mobileMenuButton.classList.toggle('open');
        });

      });
    </script>
    <!-- <title>Seu Site</title> -->
    <style>
      body {
        font-family: 'Encode Sans', sans-serif;
        margin: 0;
      }

      

      .custom-header {
        color: #fff;
        height: 165px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        /* Alinhar itens ao espaço entre eles */
        padding: 0 20px;
      }

      .custom-menu {
        list-style: none;
        display: flex;
        gap: 60px;
        /* Espaçamento entre as opções */
        margin: 0;
        padding: 0;
        flex: 1;
        justify-content: center;
        align-items: center;
      }

      .custom-menu-item {
        text-transform: uppercase;
      }

      .custom-menu-item a {
        text-decoration: none;
        color: white;
        font-size: 12px !important;
        font-weight: 600 !important;
        line-height: 18px !important;
        letter-spacing: 0.1em !important;
      }

      .custom-menu-item:hover a {
        color: #ccc;
      }

      .custom-logo {
        max-height: 60px;
        max-width: 100%;
      }

      .custom-logo-mobile {
        max-height: 100px;
        max-width: 100%;
      }

      .user-profile {
        display: flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
      }

      .user-image {
        width: 48px;
        height: 48px;
        border-radius: 30px;
        overflow: hidden;
      }

      .user-name {
        font-weight: bold;
      }

      .mobile-menu-button {
        display: none;
        /* Ocultar o botão do menu hambúrguer por padrão */
      }

      .mobile-menu-list {
        list-style: none;
        padding: 0;
        display: none;
        /* Esconder a lista de opções do menu hambúrguer por padrão */
        background-color: #000;
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        z-index: 1;
      }

      .mobile-menu-item {
        padding: 10px 20px;
      }

      .custom-logo-mobile {
        display: none;
      }

      .dropdown-menu {
        width: 100% !important;
      }

      .dropdown-menu.show {
        display: block !important;
        background: white !important;
        right: 0;
        top: 60px;
      }

      .exit {
        color: black !important;
        text-align: right !important;
        background: white !important;
      }

      .drop-item {
        color: black !important;
        text-align: right !important;
        background: white !important;
      }

      .dropdown-item {
        color: black !important;
        text-align: right !important;
        background: white !important;
        cursor: pointer;
      }

      @media (max-width: 767px) {
        .custom-header {
          flex-direction: column;
          height: 88px;
          min-height: 88px !important;
        }

        .custom-menu {
          display: none;
          /* Esconder o menu principal em mobile */
        }

        .custom-logo-mobile {
          display: flex !important;
          height: 42px !important;
          margin-top: 20px !important;
          margin-left: 5px !important;
        }

        .mobile-menu-button {
          display: flex;
          align-items: center;
          color: white;
          padding: 0 20px;
          border: none;
          margin-top: -42px;
          margin-bottom: 30px;
          width: 65px;
          height: 48px;
          background: rgba(33, 33, 33, 1);
          font-size: 32px;
        }

        .mobile-menu-button.open {
          /* alguma ação ao abrir o menu */
        }

        .mobile-menu-button:hover {
          color: #ccc;
        }

        .mobile-menu-list {
          position: static;
          width: 100%;
          background-color: transparent;
          display: none;
        }

        .mobile-menu-item {
          padding: 10px 20px;
          text-align: left;
        }

        /* Mostrar a lista de opções quando o botão hambúrguer estiver aberto */
        .mobile-menu-button.open+.mobile-menu-list {
          z-index: 5 !important;
          display: block;
        }

        .mobile-menu-link {
          text-decoration: none;
          color: black;
          font-size: 14px;
          font-weight: 600;
          line-height: 18px;
          letter-spacing: 0.1em;
          text-align: left;
        }

        .list-group {
          list-style-type: none !important;
        }
      }

      @media (min-width: 768px) {
        .custom-menu {
          flex: 1;
          /* Ocupar o espaço disponível no header */
          justify-content: center;
          /* Centralizar as opções */ 
        }
      }
    </style>
  </head>

  <body>
    <header class="custom-header">

    <ul class="custom-menu">
      <a href="<?= get_site_url(); ?>">
          <img src="<?= get_site_url(); ?>/wp-content/uploads/2023/07/Logo-Mirante.png" alt="Logo" class="custom-logo" style="margin-right: 100px;">
        </a>
        <li class="custom-menu-item"><a href="<?= get_site_url(); ?>" class="custom-menu-link">HOME</a></li>
        <li class="custom-menu-item"><a href="<?= get_site_url(); ?>/shows" class="custom-menu-link">SHOWS</a></li>
        <li class="custom-menu-item"><a href="<?= get_site_url(); ?>/ingresso" class="custom-menu-link">COMPRAR INGRESSOS</a></li>
        <li class="custom-menu-item"><a href="<?= get_site_url(); ?>/After-show" class="custom-menu-link">AFTER SHOWS</a> </li>
        <li class="custom-menu-item"><a href="<?= get_site_url(); ?>/fotos" class="custom-menu-link">GALERIA DE FOTOS</a></li>
        <li class="custom-menu-item"><a href="<?= get_site_url(); ?>/contato" class="custom-menu-link">CONTATO</a></li>
        <li class="custom-menu-item">
          <a href="<?= get_site_url() ?>/<?= $logado ? 'carteira' : 'criar-conta' ?>" class="custom-menu-link">
            <?= $logado ? 'CARTEIRA DE INGRESSOS' : 'CRIAR CONTA' ?>
          </a>
        </li>
        <?php if ($logado) : ?>
          <li class="custom-menu-item dropdown">
            <a href="#" class="dropdown-toggle user-profile" id="userDropdown" role="button" aria-expanded="false">
              <div class="user-image">
                <img src="<?= $userData->photo ?>" alt="Usuário">
              </div>
            </a>
            <div class="dropdown-menu">
              <a class="dropdown-item" onclick="location.href = '/meus-dados'">Meus dados</a>
              <a class="dropdown-item" onclick="location.href = '/enderecos'">Meu endereço</a>
              <a href="#" class="dropdown-item exit" onclick="logout()">Sair</a>
            </div>
          </li>
        <?php else : ?>
          <li class="custom-menu-item"><a href="<?= get_site_url() ?>/login" class="custom-menu-link">LOGIN</a></li>
        <?php endif; ?>
      </ul>
      <!-- Botão do menu hambúrguer para mobile -->
      <img src="<?= get_site_url(); ?>/wp-content/uploads/2023/07/Logo-Mirante.png" alt="Logo" class="custom-logo-mobile align-self-start">
      <button class="mobile-menu-button align-self-end" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenuList" aria-controls="mobileMenuList" aria-expanded="false" aria-label="Toggle mobile menu">
        &#9776;
      </button>

      <div class="collapse mobile-menu-list" style="background: white;" id="mobileMenuList">
        <ul class="list-group">
          <li class="mobile-menu-item"><a href="<?= get_site_url() ?>" class="mobile-menu-link">HOME</a></li>
          <li class="mobile-menu-item"><a href="<?= get_site_url() ?>/shows" class="mobile-menu-link">SHOWS</a></li>
          <li class="mobile-menu-item"><a href="<?= get_site_url(); ?>/After-show" class="mobile-menu-link">AFTER SHOWS</a></li>
          <li class="mobile-menu-item"><a href="<?= get_site_url(); ?>/ingresso" class="mobile-menu-link">INGRESSOS</a></li>
          <li class="mobile-menu-item"><a href="<?= get_site_url() ?>/contato" class="mobile-menu-link">CONTATO</a></li>
          <?php if (!$logado) : ?>
            <li class="mobile-menu-item"><a href="<?= get_site_url() ?>/login" class="mobile-menu-link">LOGIN</a></li>
          <?php endif; ?>

          <li class="mobile-menu-item">
            <a href="<?= get_site_url() ?>/<?= $logado ? 'carteira' : 'criar-conta' ?>" class="mobile-menu-link"><?= $logado ? 'CARTEIRA DE INGRESSOS' : 'CRIAR CONTA' ?>
            </a>
          </li>
          <?php if ($logado) : ?>
            <li class="mobile-menu-item">
              <a href="/meus-dados" class="mobile-menu-link">MEUS DADOS</a>
            </li>
            <li class="mobile-menu-item">
              <a href="/enderecos" class="mobile-menu-link">MEU ENDEREÇO</a>
            </li>
            <li class="mobile-menu-item">
              <a href="javascript:void(0);" class="mobile-menu-link exit" onclick="logout()">
                SAIR
              </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </header>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  </body>

  </html>
<?php
  return ob_get_clean();
}

add_shortcode('cabecalho_site', 'cabecalhoSite');


function carteira_content()
{
  require_once "shortcodes/carteira.php";
}

add_shortcode('carteira_content', 'carteira_content');

add_action('admin_menu', 'register_admin_menus');
function register_admin_menus()
{
  add_menu_page(
    'Eventos',
    'Lista de eventos',
    'manage_options',
    'event-list',
    'event_list_page',
    'dashicons-calendar-alt',
    '2'
  );
}

function event_list_page()
{
  require_once get_stylesheet_directory() . '/admin-pages/event_list_page.php';
}

function my_hide_notices_to_all_but_super_admin()
{
  remove_all_actions('user_admin_notices');
  remove_all_actions('admin_notices');
}

add_action('in_admin_header', 'my_hide_notices_to_all_but_super_admin', 99);

add_action('pre_get_posts', 'wp_list_pages_excludes_callback');
function wp_list_pages_excludes_callback($query)
{
  global $wpdb;
  $sql = "select post_id from {$wpdb->postmeta} where meta_key = 'mirante_event_id'";
  $posts = $wpdb->get_col($sql);

  if (is_admin() && !empty($_GET['post_type']) && $_GET['post_type'] === 'page') {
    $query->set('post__not_in', $posts);
  }
}

add_action('template_redirect', function () {
  ob_start();
});
