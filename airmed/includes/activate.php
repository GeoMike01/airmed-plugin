<?php
/**
 * Installation and activation of AirMed, register hooks that are fired when the plugin is activated.
 *
 * @package     AirMed
 * @copyright   Copyright (c) 2013, GeoMetrix Data Systems Inc.
 * @license     https://www.gnu.org/licenses/gpl-2.0.txt GNU Public License
 * @since       0.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
  exit;
}

/**
 * Activate Airmed.
 */
class AM_Activate {
  /**
   * Instance of this class.
   *
   * @var      object
   */
  protected static $instance = null;

  /**
   * Return an instance of this class.
   *
   * @return object A single instance of this class.
   */
  public static function get_instance() {

    // If the single instance hasn't been set, set it now.
    if ( null === self::$instance ) {
      //airmed();
      self::$instance = new self();
      //$GLOBALS['network_wide'] = $network_wide;
    }
    return self::$instance;
  }

  /**
   * Construct class.
   */
  public function __construct() {
    //global $network_wide;
    //$this->network_wide = $network_wide;
    //$this->delete_options();

    $this->activate();
  }


  /**
   * Delete old options.
   *
   * @since 4.1.5
   */
  public function delete_options() {
    //$settings = get_option( 'anspress_opt', [] );
    //unset( $settings['user_page_title_questions'] );
    //unset( $settings['user_page_slug_questions'] );
    //unset( $settings['user_page_title_answers'] );
    //unset( $settings['user_page_slug_answers'] );
    //update_option( 'anspress_opt', $settings );
    //wp_cache_delete( 'anspress_opt', 'ap' );
    //wp_cache_delete( 'ap_default_options', 'ap' );
  }

  /**
   * Create pages
   */
  public function activate() {

    // Create main pages.
    airmed_create_pages();

  }

}


?>