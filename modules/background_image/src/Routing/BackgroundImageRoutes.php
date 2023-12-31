<?php

namespace Drupal\background_image\Routing;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\StreamWrapper\StreamWrapperManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Route;

/**
 * Defines a route subscriber to register a url for serving background image CSS.
 */
class BackgroundImageRoutes implements ContainerInjectionInterface {

  /**
   * The stream wrapper manager service.
   *
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface
   */
  protected $streamWrapperManager;

  /**
   * Constructs a new BackgroundImageRoutes object.
   *
   * @param \Drupal\Core\StreamWrapper\StreamWrapperManagerInterface $stream_wrapper_manager
   *   The stream wrapper manager service.
   */
  public function __construct(StreamWrapperManagerInterface $stream_wrapper_manager) {
    $this->streamWrapperManager = $stream_wrapper_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('stream_wrapper_manager')
    );
  }

  /**
   * Returns an array of route objects.
   *
   * @return \Symfony\Component\Routing\Route[]
   *   An array of route objects.
   */
  public function routes() {
    $routes = [];
    // Generate background image CSS files. If clean URLs are disabled the
    // files will always be served through the menu system. If clean URLs are
    // enabled and the file already exists, PHP will be bypassed.
    $directory_path = $this->streamWrapperManager->getViaScheme('public')->getDirectoryPath();

    $routes['background_image.css'] = new Route(
      '/' . $directory_path . '/background_image/css/{background_image}/{scheme}/{language}/{file}',
      [
        '_controller' => 'Drupal\background_image\Controller\BackgroundImageCssController::deliver',
      ],
      [
        '_access' => 'TRUE',
      ],
    );
    return $routes;
  }

}
