<?php
/**
 * FoodStore Tips Settings
 *
 * @package FoodStore/Admin
 */

defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WFS_Settings_Tips', false ) ) {
  return new WFS_Settings_Tips();
}

/**
 * WFS_Settings_Tips.
 */
class WFS_Settings_Tips_Extension extends WFS_Settings_Page {

  /**
   * Constructor.
   */
  public function __construct() {
    
    $this->id    = 'tips';
    $this->label = __( 'Tips', 'food-store' );
    parent::__construct();

    add_action( 'foodstore_admin_field_tips_upgrade_button', array( $this, 'render_tips_upgrade_button' ) );
  }

  /**
   * Render Tips upgrade button
   *
   * @param [array] $value
   * @return void
   */
  public function render_tips_upgrade_button( $value ) {
    WFS_Settings_Extension_Helper::render_upgrade_button( __( 'Enable Tips', 'food-store' ), '7168' );
  }

  /**
   * Get sections.
   *
   * @return array
   */
  public function get_sections() {
    
    $sections = array(
      ''           => __( 'Tips', 'food-store' ),
    );
    return apply_filters( 'foodstore_get_sections_' . $this->id, $sections );
  }

  /**
   * Check whether the Food Store - Tips extension plugin is active.
   *
   * @return bool
   */
  public function is_extension_active() {
    return class_exists( 'FST_Admin_Settings', false );
  }

  /**
   * Get the settings registered by the Food Store - Tips extension plugin.
   *
   * @param string $current_section Current section name.
   * @return array
   */
  public function get_extension_settings( $current_section = '' ) {

    foreach ( WFS_Admin_Settings::get_settings_pages() as $page ) {
      if ( $page instanceof FST_Admin_Settings ) {
        return $page->get_settings( $current_section );
      }
    }

    return array();
  }

  /**
   * Get settings array.
   *
   * @param string $current_section Current section name.
   * @return array
   */
  public function get_settings( $current_section = '' ) {

    if ( $this->is_extension_active() ) {
      $settings = $this->get_extension_settings( $current_section );

      return apply_filters( 'foodstore_get_settings_' . $this->id, $settings, $current_section );
    }

      $settings = apply_filters(

        'foodstore_tips_settings',

        array(

          array(
            'title'     => __( 'Tips Settings', 'food-store' ),
            'type'      => 'title',
            'id'        => 'tips_options',
          ),

          array(
            'type'      => 'tips_upgrade_button',
            'id'        => '_wfs_tips_upgrade_button',
          ),

        array(
          'title'     => __( 'Display Location', 'food-store' ),
          'desc_tip'  => __( 'Choose where you would want to display Tips option.', 'food-store' ),
          'id'        => '_otd_display_location',
          'type'      => 'select',
          'desc_tip'  =>  true,
          'options'   => array(
            'cart_only'   => __( 'On Cart Page', 'food-store' ),
            'checkout_only' => __( 'On Checkout Page', 'food-store' ),
            'on_both'     => __( 'On Both Pages', 'food-store' )
          ),
          'class'     => 'wc-enhanced-select',
        ),
        array(
          'title'     => __( 'Tips Form Heading', 'food-store' ),
          'id'        => '_otd_tips_heading',
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Tips Button Text', 'food-store' ),
          'id'        => '_otd_tips_text',
          'default'   => __( 'Tips', 'food-store' ),
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Allow Remove Tips Option', 'food-store' ),
          'id'        => '_otd_allow_remove',
          'type'      => 'checkbox',
          'default'   => 'yes',
        ),
        array(
          'title'     => __( 'Remove Tips Text', 'food-store' ),
          'id'        => '_otd_remove_tips_text',
          'default'   => __( 'Remove', 'food-store' ),
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Tips Fee Title', 'food-store' ),
          'id'        => '_otd_tips_title',
          'default'   => __( 'Tips', 'food-store' ),
          'type'      => 'text',
        ),
        array(
          'title'     => __( 'Is Taxable?', 'food-store' ),
          'id'        => '_otd_taxable',
          'type'      => 'checkbox'
        ),
          
          array(
            'type'      => 'sectionend',
            'id'        => 'tips_options',
          ),
        )
      );

    return apply_filters( 'foodstore_get_settings_' . $this->id, $settings, $current_section );
  }

  /**
   * Output the settings.
   *
   * When the Tips extension plugin is active, it already renders its own
   * settings for the same 'tips' id, so bail here to avoid a duplicate output.
   */
  public function output() {

    if ( $this->is_extension_active() ) {
      return;
    }

    global $current_section;

    $settings = $this->get_settings( $current_section );
    WFS_Admin_Settings::output_fields( $settings );
  }

  /**
   * Save settings.
   *
   * When the Tips extension plugin is active, it already saves its own
   * settings for the same 'tips' id, so bail here to avoid a duplicate save.
   */
  public function save() {

    if ( $this->is_extension_active() ) {
      return;
    }

    global $current_section;

    $settings = $this->get_settings( $current_section );
    WFS_Admin_Settings::save_fields( $settings );

    if ( $current_section ) {
      do_action( 'foodstore_update_options_' . $this->id . '_' . $current_section );
    }
  }

}

return new WFS_Settings_Tips_Extension();