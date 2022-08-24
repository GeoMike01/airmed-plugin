<?php
/*
 * Plugin name: AirMed Update Checker
 * Description: This simple plugin does nothing, only gets updates from a custom server
 * Version: 1.0
 * Author: Mike Uniat (based on Misha Rudrastyh)
 * Author URI: https://rudrastyh.com
 * License: GPL
 */

defined( 'ABSPATH' ) || exit;


if( ! class_exists( 'airmedUpdateChecker' ) ) {

	class airmedUpdateChecker{

		public $plugin_slug;
		public $version;
		public $cache_key;
		public $cache_allowed;

		public function __construct() {

			$this->plugin_slug = plugin_basename( __DIR__ );
      //$this->plugin_slug = plugin_basename(plugin_dir_path( __DIR__ ));
			$this->version = '0.0.13';
			$this->cache_key = 'airmed_custom_upd';
			$this->cache_allowed = false;

			add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
			add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );
			add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );
      aLog("checker added");
		}

		public function request(){

			$remote = get_transient( $this->cache_key );
      //aLog("REQUEST");
      //aLog("--- cach key");
      //aLog($remote);
      //aLog("--- end cach key:");
			if( false === $remote || ! $this->cache_allowed ) {
        //aLog("getting json info file");
				$remote = wp_remote_get(
					'http://localhost:81/wordpress/wp-content/updates/info.json',
					array(
						'timeout' => 10,
						'headers' => array(
							'Accept' => 'application/json'
						)
					)
				);

				if(
					is_wp_error( $remote )
					|| 200 !== wp_remote_retrieve_response_code( $remote )
					|| empty( wp_remote_retrieve_body( $remote ) )
				) {
          aLog("updater request wp_error");
					return false;
				}
        //aLog("set transient cach key");
				set_transient( $this->cache_key, $remote, DAY_IN_SECONDS );

			}

			$remote = json_decode( wp_remote_retrieve_body( $remote ) );
      //aLog("JSON returned:");
      //aLog($remote);
			return $remote;

		}

		function info( $res, $action, $args ) {

			// print_r( $action );
			// print_r( $args );
      aLog("update checking info");
			// do nothing if you're not getting plugin information right now
			if( 'plugin_information' !== $action ) {
				return $res;
			}

			// do nothing if it is not our plugin
			if( $this->plugin_slug !== $args->slug ) {
				return $res;
			}

			// get updates
			$remote = $this->request();

			if( ! $remote ) {
				return $res;
			}

			$res = new stdClass();

			$res->name = $remote->name;
			$res->slug = $remote->slug;
			$res->version = $remote->version;
			$res->tested = $remote->tested;
			$res->requires = $remote->requires;
			$res->author = $remote->author;
			$res->author_profile = $remote->author_profile;
			$res->download_link = $remote->download_url;
			$res->trunk = $remote->download_url;
			$res->requires_php = $remote->requires_php;
			$res->last_updated = $remote->last_updated;

			$res->sections = array(
				'description' => $remote->sections->description,
				'installation' => $remote->sections->installation,
				'changelog' => $remote->sections->changelog
			);

			if( ! empty( $remote->banners ) ) {
				$res->banners = array(
					'low' => $remote->banners->low,
					'high' => $remote->banners->high
				);
			}

			return $res;

		}

		public function update( $transient ) {
      aLog("updating");
			if ( empty($transient->checked ) ) {
				return $transient;
			}

			$remote = $this->request();
      aLog("Request Returned:");
      //aLog($remote);
      //aLog($this->version." -- ".$remote->version);
      //aLog($remote->requires." -- ".get_bloginfo( 'version' ));
      //aLog($remote->requires_php." -- ".PHP_VERSION);
			if(
				$remote
				&& version_compare( $this->version, $remote->version, '<' )
				&& version_compare( $remote->requires, get_bloginfo( 'version' ), '<=' )
				&& version_compare( $remote->requires_php, PHP_VERSION, '<' )
			) {
				$res = new stdClass();
        //aLog("slug: ".$this->plugin_slug);
				$res->slug = $this->plugin_slug;
        //aLog("plugin: ".plugin_basename( __FILE__ ));
				$res->plugin = plugin_basename( __FILE__ ); // misha-update-plugin/misha-update-plugin.php
        //aLog("version: ".$remote->version);
				$res->new_version = $remote->version;
        //aLog("tested: ".$remote->tested);
				$res->tested = $remote->tested;
        //aLog("url: ".$remote->download_url);
				$res->package = $remote->download_url;
        aLog($res);
				$transient->response[ $res->plugin ] = $res;

	    }

			return $transient;

		}

		public function purge( $upgrader, $options ){

			if (
				$this->cache_allowed
				&& 'update' === $options['action']
				&& 'plugin' === $options[ 'type' ]
			) {
				// just clean the cache when new plugin version is installed
              aLog("cleaning cached after update installed");
				delete_transient( $this->cache_key );
			}

		}

	}

	new airmedUpdateChecker();

}
