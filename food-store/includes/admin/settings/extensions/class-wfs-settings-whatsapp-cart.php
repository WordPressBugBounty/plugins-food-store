<?php
/**
 * FoodStore Whatsapp Cart Settings
 *
 * @package FoodStore/Admin
 */

defined( 'ABSPATH' ) || exit;


/**
 * WFS_Settings_Whatsapp_Cart.
 */
class WFS_Settings_Whatsapp_Cart_Extension extends WFS_Settings_Page {

  /**
   * Constructor.
   */
  public function __construct() {
    
    $this->id    = 'whatsapp-cart';
    $this->label = __( 'WhatsApp Cart', 'food-store' );
    parent::__construct();

    add_action( 'foodstore_admin_field_whatsapp_cart_upgrade_button', array( $this, 'render_whatsapp_cart_upgrade_button' ) );
  }

  /**
   * Render Whatsapp Cart upgrade button
   *
   * @param [array] $value
   * @return void
   */
  public function render_whatsapp_cart_upgrade_button( $value ) {
    WFS_Settings_Extension_Helper::render_upgrade_button( __( 'Enable Whatsapp Cart', 'food-store' ), '7156' );
  }

  /**
   * Get sections.
   *
   * @return array
   */
  public function get_sections() {
    
    $sections = array(
      ''          => __( 'General', 'food-store' ),
      'checkout'  => __( 'Checkout', 'food-store' ),
    );
    return apply_filters( 'foodstore_get_sections_' . $this->id, $sections );
  }

  /**
   * Output sections.
   *
   * When the WhatsApp Cart extension plugin is active, it already renders its own
   * sections for the same 'whatsapp-cart' id, so bail here to avoid duplicate output.
   */
  public function output_sections() {

    if ( $this->is_extension_active() ) {
      return;
    }

    parent::output_sections();
  }

  /**
   * Check whether the Food Store - Whatsapp Cart extension plugin is active.
   *
   * @return bool
   */
  public function is_extension_active() {
    return class_exists( 'WAC_Admin_Settings', false );
  }

  /**
   * Get the settings registered by the Food Store - Whatsapp Cart extension plugin.
   *
   * @param string $current_section Current section name.
   * @return array
   */
  public function get_extension_settings( $current_section = '' ) {

    foreach ( WFS_Admin_Settings::get_settings_pages() as $page ) {
      if ( $page instanceof WAC_Admin_Settings ) {
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

    if ( 'checkout' === $current_section ) {

      $settings = apply_filters(
        
        'foodstore_whatsapp_checkout_settings',
        
        array(

          array(
            'title'   => __( 'Enable Whatsapp button on Order Receipt Page', 'food-store' ),
            'id'      => '_wac_receipt_enable',
            'type'    => 'title',
          ),

          array(
            'type'      => 'whatsapp_cart_upgrade_button',
            'id'        => '_wfs_whatsapp_cart_upgrade_button',
          ),

          array(
            'title'     => __( 'Whatsapp Number', 'food-store' ),
            'id'        => '_wac_receipt_business_number',
            'desc'      => __( 'Enter your WhatsApp business number to receive orders.', 'food-store' ), 
            'type'      => 'text',
          ),

          array(
            'title'     => __( 'WhatsApp Text Prefix', 'food-store' ),
            'id'        => '_wac_receipt_text_prefix',
            'type'      => 'textarea',
            'css'       => 'min-width: 50%; height: 75px;',
            'default'   => __( 'Hello, here are my order details,', 'food-store' ),
          ),

          array(
            'title'     => __('WhatsApp Cart Elements', 'food-store'),
            'id'        => '_wac_receipt_elements',
            'type'      => 'multiselect',
            'desc_tip'  => __('Select elements that you want to include in WhatsApp text.', 'food-store'),
            'options'   => apply_filters( 'wac_receipt_elements', array(
              'id'        => __('Order ID', 'food-store'),
              'qty'       => __('Quantity', 'food-store'),
              'name'      => __('Item Name', 'food-store'),
              'addon'     => __('Item Addons', 'food-store'),
              'note'      => __('Item Note', 'food-store'),
              'price'     => __('Item Total', 'food-store'),
              'service'   => __('Service Details', 'food-store'),
              'total'     => __('Cart Total', 'food-store'),
              'customer'  => __('Customer Details', 'food-store'),
              'date'      => __('Order Date', 'food-store'),
            ) ),
            'class'     => 'wc-enhanced-select',
          ),

          array(
            'title'     => __( 'WhatsApp Text Suffix', 'food-store' ),
            'id'        => '_wac_receipt_text_suffix',
            'type'      => 'textarea',
            'css'       => 'min-width: 50%; height: 75px;',
            'default'   => __( 'Thank You!', 'food-store' ),
          ),

          array(
            'title'     => __( 'Button Text', 'food-store' ),
            'id'        => '_wac_receipt_button_text', 
            'type'      => 'text',
            'default'   => __( 'Send Order Details', 'food-store' ),
          ),

          array(
            'title'     => __( 'Custom Title', 'food-store' ),
            'id'        => '_wac_receipt_title_text', 
            'type'      => 'text',
            'default'   => __( "Thanks and You're Awesome!", 'food-store' ),
          ),

          array(
            'title'     => __( 'Custom Subtitle', 'food-store' ),
            'id'        => '_wac_receipt_subtitle_text', 
            'type'      => 'text',
            'default'   => __( "For faster response, send your order details by clicking below button.", 'food-store' ),
          ),

          array(
            'type'    => 'sectionend',
            'id'      => 'wac_checkout',
          ),
        )
      );

    } else {

      $settings = apply_filters(
        
        'foodstore_whatsapp_settings',
        
        array(

          array(
            'title'   => __( 'General Settings', 'food-store' ),
            'type'    => 'title',
            'id'      => 'wac_general',
          ),

          array(
            'type'      => 'whatsapp_cart_upgrade_button',
            'id'        => '_wfs_whatsapp_cart_upgrade_button',
          ),

          array(
            'title'     => __( 'Whatsapp Number', 'food-store' ),
            'id'        => '_wac_business_number',
            'desc'      => __( 'Enter your WhatsApp business number to receive orders.', 'food-store' ), 
            'type'      => 'text',
          ),

          array(
            'title'     => __( 'WhatsApp Text Prefix', 'food-store' ),
            'id'        => '_wac_order_text_prefix',
            'type'      => 'textarea',
            'css'       => 'min-width: 50%; height: 75px;',
            'default'   => __( 'Hi, I would like to buy the following products.', 'food-store' ),
          ),

          array(
            'title'     => __('WhatsApp Cart Elements', 'food-store'),
            'id'        => '_wac_order_elements',
            'type'      => 'multiselect',
            'desc_tip'  => __('Select elements that you want to include in WhatsApp text.', 'food-store'),
            'options'   => apply_filters( 'wac_order_elements', array(
              'qty'       => __('Quantity', 'food-store'),
              'name'      => __('Item Name', 'food-store'),
              'addon'     => __('Item Addons', 'food-store'),
              'note'      => __('Item Note', 'food-store'),
              'price'     => __('Item Total', 'food-store'),
              'service'   => __('Service Details', 'food-store'),
              'total'     => __('Cart Total', 'food-store'),
            ) ),
            'class'     => 'wc-enhanced-select',
          ),

          array(
            'title'     => __( 'WhatsApp Text Suffix', 'food-store' ),
            'id'        => '_wac_order_text_suffix',
            'type'      => 'textarea',
            'css'       => 'min-width: 50%; height: 75px;',
            'default'   => __( 'Thank You!', 'food-store' ),
          ),

          array(
            'title'     => __( 'Button Text', 'food-store' ),
            'id'        => '_wac_button_text', 
            'type'      => 'text',
            'default'   => __( 'Order on WhatsApp', 'food-store' ),
          ),

          array(
            'title'   => __( 'Disable Checkout Button', 'food-store' ),
            'id'      => '_wac_disable_checkout',
            'desc'    => __( 'This will disable the Proceed to Checkout button.', 'food-store' ), 
            'default' => 'no',
            'type'    => 'checkbox',
          ),

          array(
            'title'   => __( 'Empty Cart?', 'food-store' ),
            'id'      => '_wac_empty_cart',
            'desc'    => __( 'Cart will be cleared once user clicks on WhatsApp button.', 'food-store' ), 
            'default' => 'no',
            'type'    => 'checkbox',
          ),

          array(
            'type'    => 'sectionend',
            'id'      => 'wac_general',
          ),
        )
      );
    }

    return apply_filters( 'foodstore_get_settings_' . $this->id, $settings, $current_section );
  }

  /**
   * Output the settings.
   *
   * When the WhatsApp Cart extension plugin is active, it already renders its own
   * settings for the same 'whatsapp-cart' id, so bail here to avoid a duplicate output.
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
   * When the WhatsApp Cart extension plugin is active, it already saves its own
   * settings for the same 'whatsapp-cart' id, so bail here to avoid a duplicate save.
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

return new WFS_Settings_Whatsapp_Cart_Extension();