<?php

if (!empty($_GET['action']) && $_GET['action'] === 'sync') {
  global $wpdb;

  $ingresse_api = new IngresseAPI();
  $eventsSearch = $ingresse_api->getAllEvents()->data->hits;
  $events = array_map(function ($event) {
    return $event->_source;
  }, $eventsSearch);
  $events_ids = array_map(function ($event) {
    return $event->id;
  }, $events);

  update_option('ingresse_public_events', implode(', ', $events_ids));

  foreach ($events as $event) {
    $post_search = $wpdb->get_results("select * from {$wpdb->postmeta} where meta_key = 'mirante_event_id' and meta_value = '{$event->id}'");
    $post_exists = !empty($post_search);

    $new_page = array(
      'post_title' => $event->title,
      'post_status' => 'publish',
      'post_type' => 'page',
    );

    if ($post_exists) {
      $new_page['ID'] = $post_search[0]->post_id;
    }

    $page_id = wp_insert_post($new_page);
    if (!is_wp_error($page_id)) {
      update_post_meta($page_id, '_elementor_template_type', 'page');
      update_post_meta($page_id, '_elementor_page_template', 'default');
      update_post_meta($page_id, 'mirante_event_id', $event->id);
      update_post_meta($page_id, 'mirante_event_city', $event->place->city);
      update_post_meta($page_id, 'mirante_event_street', $event->place->street);
      $event_datetimes = array_map(function ($session) {
        return $session->dateTime;
      }, $event->sessions);
      sort($event_datetimes);
      $first_event_date = $event_datetimes[array_key_first($event_datetimes)];
      update_post_meta($page_id, 'mirante_event_session_datetime', $first_event_date);
      update_post_meta($page_id, 'mirante_event_session_datetimes', $event_datetimes);
    }
  }
}

if (!empty($_GET['action']) && !empty($_GET['post']) && $_GET['action'] === 'delete') {
  wp_delete_post($_GET['post'], true);
}

$table = new Mirante_Event_List_Table();

?>

<style>
  #action {
    width: 110px;
  }
</style>

<div class="wrap">
  <h1 class="wp-heading-inline">
    <?= get_admin_page_title(); ?>
  </h1>
  <a href="<?php menu_page_url('event-list') ?>&action=sync" class="page-title-action">
    Sincronizar
  </a>

  <?php $table->display(); ?>
</div>