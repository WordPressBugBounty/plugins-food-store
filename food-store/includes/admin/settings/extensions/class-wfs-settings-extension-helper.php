<?php
/**
 * FoodStore Settings Extension Helper
 *
 * @package FoodStore/Admin
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WFS_Settings_Extension_Helper', false ) ) :

  /**
   * WFS_Settings_Extension_Helper.
   *
   * Shared helpers for the extension settings pages (Tips, Whatsapp Cart,
   * Delivery Zones Fees, etc).
   */
  class WFS_Settings_Extension_Helper {

    /**
     * Render the "Available in Pro" upgrade button field for an extension.
     *
     * @param string $label     Field label describing the locked feature.
     * @param string $plugin_id Freemius plugin ID the checkout URL is built for.
     * @return void
     */
    public static function render_upgrade_button( $label, $plugin_id ) {
      ?>
      <tabel class="form-table">
        <tbody>
          <tr valign="top">
            <th scope="row" class="titledesc">
              <?php echo esc_html( $label ); ?>
            </th>
            <?php
            $checkout_manager = FS_Checkout_Manager::instance();
            $url = $checkout_manager->get_full_checkout_url(
              array(
                'plugin_id'     => $plugin_id,
                'billing_cycle' => WP_FS__PERIOD_ANNUALLY,
              )
            );
            ?>
            <td class="forminp forminp-checkbox">
              <a class="wfs-pro-lock-feature" href="<?php echo esc_url( $url ); ?>" target="_blank">
                <span class="dashicons dashicons-lock"></span><?php esc_html_e( 'Available in Pro', 'food-store' ); ?>
              </a>
            </td>
          </tr>
        </tbody>
      </tabel>
      <?php
    }
  }

endif;
