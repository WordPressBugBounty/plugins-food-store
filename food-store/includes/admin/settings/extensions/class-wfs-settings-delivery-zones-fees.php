<?php
/**
 * FoodStore Delivery Zones Fees Settings
 *
 * @package FoodStore/Admin
 */

defined( 'ABSPATH' ) || exit;


/**
 * WFS_Settings_Delivery_Zones_Fees_Extension.
 */
class WFS_Settings_Delivery_Zones_Fees_Extension extends WFS_Settings_Page {

  /**
   * Constructor.
   */
  public function __construct() {
    
    $this->id    = 'delivery_zones_fees';
    $this->label = __( 'Delivery Zones Fees', 'food-store' );
    parent::__construct();

    add_action( 'foodstore_admin_field_delivery_zones_fees_upgrade_button', array( $this, 'render_delivery_zones_fees_upgrade_button' ) );
  }

  /**
   * Render Delivery Zones Fees upgrade button
   *
   * @param [array] $value
   * @return void
   */
  public function render_delivery_zones_fees_upgrade_button( $value ) {
    WFS_Settings_Extension_Helper::render_upgrade_button( __( 'Enable Delivery Zones Fees', 'food-store' ), '7812' );
  }

  /**
   * Get sections.
   *
   * @return array
   */
  public function get_sections() {
    
    $sections = array(
        ''                        => __( 'General', 'food-store' ),
        'zip-based'      => __( 'Zip Based', 'food-store' ),
        'location-based' => __( 'Location Based', 'food-store' ),
      );
    return apply_filters( 'foodstore_get_sections_' . $this->id, $sections );
  }

  /**
   * Output sections.
   *
   * When the Delivery Zones and Fees extension plugin is active, it already renders its own
   * sections for the same 'delivery_zones_fees' id, so bail here to avoid duplicate output.
   */
  public function output_sections() {

    if ( $this->is_extension_active() ) {
      return;
    }

    parent::output_sections();
  }

  /**
   * Check whether the Food Store - Delivery Zones Fees extension plugin is active.
   *
   * @return bool
   */
  public function is_extension_active() {
    return class_exists( 'WFS_Settings_Delivery_Zones_Fees', false );
  }

  /**
   * Get the settings registered by the Food Store - Delivery Zones Fees extension plugin.
   *
   * @param string $current_section Current section name.
   * @return array
   */
  public function get_extension_settings( $current_section = '' ) {

    foreach ( WFS_Admin_Settings::get_settings_pages() as $page ) {
      if ( $page instanceof WFS_Settings_Delivery_Zones_Fees ) {
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

    $current_section = isset( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : '';

      if( 'zip-based' === $current_section ) {

        $settings = apply_filters(
          
          'foodstore_settings_zip_based',
          
          array(

            array(  
              'title'   => __( 'ZIP Based Delivery Method', 'food-store' ), 
              'type'    => 'title',
              'desc'    => __( 'Add delivery fee, ZIP / Postal Codes, Minimum order amount for which the fee would be calculated.', 'food-store' ),  
              'id'      => '_wfs_store_days_options' 
            ),

            array(
              'title'   => __( 'Store Open / Close Settings', 'food-store' ),
              'desc'    => __( 'Enable Store Timing.', 'food-store' ),
              'type'    => 'zip_based_locations',
              'id'      => '_wfs_zip_based_locations',
            ),

            
            array( 
              'type'    => 'sectionend', 
              'id'      => '_wfs_store_days_options'
            )
          )
        );

      } 

      else if( 'location-based' === $current_section ) {
        
        $settings = apply_filters(
          
          'foodstore_settings_location_based',
          
          array(

            array(  
              'title'   => __( 'Location Based Settings', 'food-store' ), 
              'type'    => 'title',
              'desc'    => __( 'Set location based settings', 'food-store' ), 
              'id'      => '_wfs_service_time_settings' 
            ),

            array(  
              'title'   => __( 'Google Map API Key', 'food-store' ), 
              'type'    => 'text',
              'desc'    => __('Enter google map api key. You can get your google map api from <a href="https://developers.google.com/maps/documentation/javascript/get-api-key" target="_blank">here</a>', 'food-store'), 
              'id'      => '_wfs_dzaf_google_map_api' 
            ),

            array(
              'title'   => __( 'Store Location', 'food-store' ),
              'desc'    => __( 'Store location settings.', 'food-store' ),
              'type'    => 'store_location_settings',
              'id'      => '_wfs_store_location_settings',
            ),

            array(  
              'title'     => __( 'Distance Unit', 'food-store' ), 
              'type'      => 'select',
              'options'   => array( 'km' => 'KM', 'miles' => 'Miles' ),
              'desc_tip'  => __( 'Select distance unit what you want to use for distance units.', 'food-store' ), 
              'id'        => '_wfs_dzaf_distance_unit' 
            ),


            array(
              'title'   => __( 'Delivery Settings', 'food-store' ),
              'desc'    => __( 'Delivery fee settings.', 'food-store', 'food-store' ),
              'type'    => 'distance_based_locations',
              'id'      => '_wfs_distance_based_locations',
            ),
            
            array( 
              'type'    => 'sectionend', 
              'id'      => '_wfs_service_time_settings'
            )
          )
        );
      
      } else {
        
        $settings = apply_filters(
          
          'foodstore_settings_delivery_zone_fee_general',
          
          array(

            array(
              'title'   => __( 'General Settings', 'food-store' ),
              'type'    => 'title',
              'desc'    => '',
              'id'      => '_wfs_dzaf_delivery_fee_method_title'
            ),

            array(
              'type'      => 'delivery_zones_fees_upgrade_button',
              'id'        => '_wfs_delivery_zones_fees_upgrade_button',
            ),

            array(
              'title'     => __( 'Placeholder Text', 'food-store' ),
              'type'      => 'text',
              'default'   => 'Please Select Location',
              'desc_tip'  => __( 'Enter the placeholder text that would appear for the input field of zip/postal code.
              ', 'food-store'),
              'id'        => '_wfs_dzaf_placeholder_text' 
            ),

            array(  
              'title'       => __( 'Error Message', 'food-store' ), 
              'type'        => 'textarea',
              'desc_tip'    => __( 'Error message to be shown when no zip/location has been entere by user', 'food-store'), 
              'id'          => '_wfs_dzaf_empty_zip_location' 
            ),

            array(  
              'title'       => __( 'Unavailable Error Message', 'food-store' ), 
              'type'        => 'textarea',
              'desc_tip'    => __( 'Error Message For Unavailable ZIP / Postal Code / Distance', 'food-store'), 
              'id'          => '_wfs_dzaf_unavilable_area_message' 
            ),

            array(  
              'title'     => __( 'Set Delivery Fee Method', 'food-store' ), 
              'type'      => 'radio',
              'id'        => '_wfs_dzaf_delivery_fee_method',
              'options'   => array( 
                'zip'       => __( 'ZIP Based', 'food-store' ), 
                'location'  => __( 'Location Based', 'food-store' ),
              ),
              'default'   => 'zip',
              'desc_tip'  => __( 'Choose how you want to add fee on the service.', 'food-store' ),
            ),

            array(  
              'title'     => __( 'Autocomplete Address On Checkout Page', 'food-store' ), 
              'type'      => 'checkbox',
              'id'        => '_wfs_dzaf_autocomplete_address',
              'desc_tip'  => __( 'This option will make autocomplete address on checkout page based on user delivery location.', 'food-store' ),
            ),

            array(  
              'title'     => __( 'Apply Autocomplete Address ', 'food-store' ), 
              'type'      => 'radio',
              'id'        => '_wfs_dzaf_apply_autocomplete_address',
              'options'   => array( 
                'billing'           => __( 'On billing address', 'food-store' ), 
                'shipping'          => __( 'On shipping address', 'food-store' ),
                'billing_shipping'  => __( 'Both billing and shipping address', 'food-store' ),
              ),
              'default'   => 'billing_address',
              'desc_tip'  => __( 'Choose on which address the autocomplete will be applied.', 'food-store' ),
            ),

            array( 
              'type'    => 'sectionend', 
              'id'      => '_wfs_store_timing_title'
            )
          )
        );
      
      }

      return apply_filters( 'foodstore_get_settings_' . $this->id, $settings, $current_section );
  }

  /**
   * Output the settings.
   *
   * When the Delivery Zones and Fees extension plugin is active, it already renders its own
   * settings for the same 'delivery_zones_fees' id, so bail here to avoid a duplicate output.
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
   * When the Delivery Zones and Fees extension plugin is active, it already saves its own
   * settings for the same 'delivery_zones_fees' id, so bail here to avoid a duplicate save.
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

return new WFS_Settings_Delivery_Zones_Fees_Extension();