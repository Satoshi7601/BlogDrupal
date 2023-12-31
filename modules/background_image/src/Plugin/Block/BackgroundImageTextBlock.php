<?php

namespace Drupal\background_image\Plugin\Block;

use Drupal\background_image\BackgroundImageManager;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *   id = "background_image_text",
 *   admin_label = @Translation("Background Image - Text"),
 *   category = @Translation("Background Image")
 * )
 */
class BackgroundImageTextBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  protected function baseConfigurationDefaults() {
    $defaults = parent::baseConfigurationDefaults();
    $defaults['label_display'] = 'hidden';
    return $defaults;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $background_image_manager = BackgroundImageManager::service();
    if ($background_image = $background_image_manager->getBackgroundImage()) {
      $build = $background_image_manager->view($background_image, 'text');
    }
    $build['#cache']['contexts'][] = 'user.permissions';
    $build['#cache']['contexts'][] = 'background_image.settings.text';
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildConfigurationForm($form, $form_state);

    $form['label_display']['#access'] = FALSE;

    return $form;
  }


}
