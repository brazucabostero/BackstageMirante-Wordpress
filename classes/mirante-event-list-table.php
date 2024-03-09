<?php

if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class Mirante_Event_List_Table extends WP_List_Table
{

  public function __construct()
  {
    parent::__construct([
      'singular' => 'pagina',
      'plural'   => 'paginas',
      'ajax'     => false,
    ]);
  }

  public function prepare_items()
  {
    $columns = $this->get_columns();
    $hidden = $this->get_hidden_columns();
    $sortable = $this->get_sortable_columns();

    $data = $this->get_pages_with_custom_meta();

    $perPage = 20;
    $currentPage = $this->get_pagenum();
    $totalItems = count($data);

    $this->set_pagination_args([
      'total_items' => $totalItems,
      'per_page'    => $perPage,
    ]);

    $this->_column_headers = [$columns, $hidden, $sortable];
    $this->items = array_slice($data, (($currentPage - 1) * $perPage), $perPage);
  }

  public function get_columns()
  {
    return [
      'action'  => 'Ações',
      'title'  => 'Título',
      'mirante_event_id' => 'Id do evento',
    ];
  }

  public function get_hidden_columns()
  {
    return [];
  }

  public function get_sortable_columns()
  {
    return [];
  }

  private function get_pages_with_custom_meta()
  {
    global $wpdb;

    $query = "SELECT post_id, meta_value
                  FROM $wpdb->postmeta
                  WHERE meta_key = 'mirante_event_id'";

    $results = $  ->get_results($query);

    $pages = [];
    foreach ($results as $result) {
      $post_id = $result->post_id;
      $page = get_post($post_id);
      if ($page) {
        $pages[] = [
          'action' => $page->ID,
          'title' => $page->post_title,
          'mirante_event_id' => $result->meta_value,
        ];
      }
    }

    return $pages;
  }

  public function column_action($item)
  {
    $page_id = $item['action'];
    $edit_url = admin_url() . "post.php?post=$page_id&action=elementor";
    $view_url = get_permalink($page_id);
    $delete_url = menu_page_url('event-list', false) . '&action=delete&post=' . $page_id;

    $actions = [
      'view' => sprintf(
        '<a class="button button-primary" href="%s">Editar</a>',
        esc_url($edit_url)
      ) . ' ' .
        sprintf(
          '<a class="button button-primary" href="%s">Ver</a>',
          esc_url($view_url)
        )
    ];

    return sprintf('<span class="row-actions">%s</span>', $this->row_actions($actions));
  }

  public function column_default($item, $column_name)
  {
    switch ($column_name) {
      case 'action':
        return $this->column_action($item);
      default:
        return $item[$column_name];
    }
  }

  public function display()
  {
    $this->prepare_items();
    parent::display();
  }
}
