<?php


/**
 * Implements hook_init().
 */
function sample_custom_init() {
  global $base_url;
  $url = parse_url($base_url);

  // Change URL's that reference an external URL so they are pointing to their staging environment
  if (in_array($url['host'], array('sample.example-development.com', 'sample.example-staging.com'))) {

    $dev_url = $url['host'];

    drupal_add_js('
      jQuery(document).ready(function(){
        $ = jQuery;

        $(\'a[href*="sample.com"]\').each(function(){
          $(this).attr("href", $(this).attr("href").replace("://www.sample.com", "://' . $dev_url . '"));
          $(this).attr("href", $(this).attr("href").replace("://sample.com", "://' . $dev_url . '"));
        });
      });

    ', array(
      'type' => 'inline',
      'scope' => 'footer',
    ));
  }
}




/*
 * Implements hook_node_view()
 */
function sample_node_view($node, $view_mode, $langcode) {
  if ($node->type == 'exhibition' && in_array($view_mode, array('full', 'teaser'))) {

    if (!empty($node->content['field_subtitle'])) {
      $pattern = '/(<\/.*>)/i';
      $title = $node->content['title_field'][0]['#markup'];
      $sub_title = $node->content['field_subtitle']['#items'][0]['value'];
      $add_colon = preg_replace($pattern, ': <span class="sub-title">' . $sub_title . '</span>$1', $title);
      $node->content['title_field'][0]['#markup'] = $add_colon;
    }
    if (!empty($node->content['links']['node']['#links']['node-readmore'])) {
      $string = $node->content['links']['node']['#links']['node-readmore']['title'];
      $pattern = '/^(.*?)(?=<)/i';
      $replacement = '${1} >';
      $string = $node->content['links']['node']['#links']['node-readmore']['title'] = preg_replace($pattern, $replacement, $string);
    }

    if (!empty($node->field_date_range)) {
      // Custom exhibition date format
      $node->content['custom_exhibition_date'] = array(
        '#markup' => smart_dates_format(new DateTime($node->field_date_range[LANGUAGE_NONE][0]['value']), new DateTime($node->field_date_range[LANGUAGE_NONE][0]['value2'])),
        '#prefix' => '<div class="exhibition-date">',
        '#suffix' => '</div>',
      );
    }


    if (!empty($node->field_reception_date)) {
      $node->content['custom_reception_date'] = array(
        '#markup' => smart_dates_format(new DateTime($node->field_reception_date[LANGUAGE_NONE][0]['value']), new DateTime($node->field_reception_date[LANGUAGE_NONE][0]['value2'])),
        '#prefix' => '<div class="exhibition-date"><span class="field-label">' . t('Opening Reception') . ':</span>',
        '#suffix' => '</div>',
      );
    }

    if (!empty($node->content['field_relation'])) {

      $node->content['field_relation']['#prefix'] = '<div class="field-relation">';
      $node->content['field_relation']['#suffix'] = '</div>';

      $relation_markup = '';
      foreach ($node->field_relation[LANGUAGE_NONE] as $index => $relation) {

        $start_date           = $relation['entity']->field_date_range[LANGUAGE_NONE][0]['value'];
        $end_date             = $relation['entity']->field_date_range[LANGUAGE_NONE][0]['value2'];
        $relation_date_string = smart_dates_format(new DateTime($start_date), new DateTime($end_date));

        // Render title
        $related_title = $relation['entity']->title;
        if (!empty($relation['entity']->field_subtitle)) {
          $related_title .= ': ' . $relation['entity']->field_subtitle[LANGUAGE_NONE][0]['value'];
        }

        $node->content['field_relation'][$index]['#markup'] = '<div class="date">' . $relation_date_string . '</div>' . $related_title .   l(t('More >'), 'node/' . $relation['target_id'], array(
          'class' => array('more-link'),
        ));
      }
    }
  }
  ...
}


