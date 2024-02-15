<?php

namespace Drupal\my_module\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a block with care collaboration intro text.
 *
 * @Block(
 *   id = "care_collaboration_block",
 *   admin_label = @Translation("CareCollaborationIntro block"),
 * )
 */
class CareCollaboration extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Config variable.
   *
   * @var Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * User Private tempstore.
   *
   * @var \Drupal\Core\TempStore\PrivateTempStoreFactory
   */
  protected $tempStore;

  /**
   * {@inheritdoc}
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    ConfigFactoryInterface $config,
    PrivateTempStoreFactory $temp_store_private) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->config = $config;
    $this->tempStore = $temp_store_private;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('config.factory'),
      $container->get('tempstore.private')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $enroll_text = $this->getCareCollabText();
    $data = [
      'intro_text' => $enroll_text,
    ];

    $build = [
      '#theme' => 'care_collaboration_block',
      '#data' => $data,
    ];

    return $build;
  }

  /**
   * {@inheritdoc}
   */
  public function getCareCollabText() {
    $case_manager_label = $this->config->get('pxp_config.misc')->get('case_manager');
    $text = [
      'texts' => [
        $this->t("Need support? You can call your case management team and they'll get back to you in 24hrs."),
        $this->t("Our @case_manager would be happy to help with the features in this app and provide support throughout your journey including:", ['@case_manager' => $case_manager_label]),
      ],
      'lists' => [
        $this->t('Knowledge Center use'),
        $this->t('Medical supplies'),
        $this->t('Appointments'),
        $this->t('Communication preferences'),
        $this->t('Nurse follow-up calls for 1:1 personalised support'),
        $this->t('Any other concerns you may have during your journey'),
      ],
    ];

    return $text;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
