<?php

/**
 * @file
 * Contains \Drupal\search_api\Plugin\SearchApi\Processor\AddURL.
 */

namespace Drupal\search_api\Plugin\SearchApi\Processor;

use Drupal\Core\TypedData\DataDefinition;
use Drupal\search_api\Datasource\DatasourceInterface;
use Drupal\search_api\Processor\ProcessorPluginBase;

/**
 * @SearchApiProcessor(
 *   id = "add_url",
 *   label = @Translation("URL field"),
 *   description = @Translation("Adds the item's URL to the indexed data.")
 * )
 */
class AddURL extends ProcessorPluginBase {

  /**
   * {@inheritdoc}
   */
  public function alterPropertyDefinitions(array &$properties, DatasourceInterface $datasource = NULL) {
    if ($datasource) {
      return;
    }
    $definition = array(
      'label' => $this->t('URI'),
      'description' => $this->t('A URI where the item can be accessed.'),
      'type' => 'uri',
    );
    $properties['search_api_url'] = new DataDefinition($definition);
  }

  /**
   * {@inheritdoc}
   */
  public function preprocessIndexItems(array &$items) {
    // Annoyingly, this doc comment is needed for PHPStorm. See
    // http://youtrack.jetbrains.com/issue/WI-23586
    /** @var \Drupal\search_api\Item\ItemInterface $item */
    foreach ($items as $item) {
      // Only run if the field is enabled for the index.
      if ($field = $item->getField('search_api_url')) {
        $url = $item->getDatasource()->getItemUrl($item->getOriginalObject());
        if ($url) {
          $field->addValue($url->toString());
        }
      }
    }
  }

}
