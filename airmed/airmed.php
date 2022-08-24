<?php 
/* Plugin name: AirMed 
Description: AirMed API Plugin to allow clients to interact with a producer's store.
Version: 0.0.17
Author: AirMed
*/  


//* this can be removed after */
/*
function register_custom_widget_area() {
  register_sidebar(
    array(
      'id' => 'new-widget-area',
      'name' => esc_html__( 'My new widget area', 'theme-domain' ),
      'description' => esc_html__( 'A new widget area made for testing purposes', 'theme-domain' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget' => '</div>',
      'before_title' => '<div class="widget-title-holder"><h3 class="widget-title">',
      'after_title' => '</h3></div>'
    )
  );
}

add_action( 'widgets_init', 'register_custom_widget_area' );
*/

if ( ! function_exists('write_log')) {
   function write_log ( $log )  {
      if ( is_array( $log ) || is_object( $log ) ) {
         error_log( print_r( $log, true ) );
      } else {
         error_log( $log );
      }
   }
}

// may not be needed anymore as don't see it being used
function loadJSON($Obj, $json){
    $dcod = json_decode($json);
    $prop = get_object_vars ( $dcod );
    foreach($prop as $key => $lock)
    {
        if(property_exists ( $Obj ,  $key ))
        {
            if(is_object($dcod->$key))
            {
                loadJSON($Obj->$key, json_encode($dcod->$key));
            }
            else
            {
                $Obj->$key = $dcod->$key;
            }
        }
    }
}

/**
  * Main AirMed class.
*/
if ( ! class_exists( 'AirMed' ) ) {
  class AirMed{
    
    /*
    Instance of the class
    @var Twitter_Demo
    */
    private static $instance;
    
    /*
    Initialize the plugin
    @access private
    */
    private function __construct(){
      //add_action( 'the_content', array( $this, 'display_twitter_information' ) );
      add_action('plugins_loaded', array ( $this, 'init') );
    }
    
    /*
    creates instance of the class
    @access public
    @return Twitter_Demo
    */
    public static function get_instance(){
      if ( null == self::$instance ) {
        self::$instance = new self;
      }
      return self::$instance;
    }

    /*
    */
    public function init(){
      define( 'AIRMED__PLUGIN_DIR', wp_normalize_path( plugin_dir_path( __FILE__ ) ) );
      //require_once( AIRMED__PLUGIN_DIR . 'class.airmed-widget.php' );
      //flush_rewrite_rules();

      $this->includes();
      
      //add_action('init',array($this,'am_add_rewrite_rules'));
      
      add_action('init',array($this,'am_startSession'),1);
      // add_action('init', array($this,'wp_airmed_activate_au'),1);
      add_action('wp_logout',array($this,'am_endSession'));
      add_action('wp_login',array($this,'am_endSession'));
      
      //add font awesome
      //fa_custom_setup_cdn_webfont('https://pro.fontawesome.com/releases/v5.10.0/css/all.css','sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p');
      //fa_custom_setup_cdn_svg('https://pro.fontawesome.com/releases/v5.10.0/js/all.js','sha384-G/ZR3ntz68JZrH4pfPJyRbjW+c0+ojii5f+GYiYwldYU69A+Ejat6yIfLSxljXxD');
      //fa_custom_setup_cdn_webfont('https://use.fontawesome.com/releases/v5.15.4/css/all.css','sha384-DyZ88mC6Up2uqS4h/KRgHuoeGwBcD4Ng9SiP4dIRy0EXTlnuz47vAwmeGwVChigm');
      //fa_custom_setup_cdn_svg('https://use.fontawesome.com/releases/v5.15.4/js/all.js','sha384-rOA1PnstxnOBLzCLMcre8ybwbTmemjzdNlILg8O7z1lUkLXozs4DHonlDtnE7fpc');
      //fa_custom_setup_cdn_svg('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js','sha512-Tn2m0TIpgVyTzzvmxLNuqbSJH3JP8jm+Cy3hvHrW7ndTDcJ1w5mBiksqDBb8GpE2ksktFvDB/ykZ0mDpsZj20w==');
      add_action('admin_enqueue_scripts',array($this,'add_admin_scripts'));
      add_action('wp_enqueue_scripts',array($this,'add_scripts'));
      
      // styles added for text area editor
      add_editor_style(plugins_url('/css/admin-styles.css',__FILE__));
      
      
      // add page posts
      $this->add_page_posts();
      
      // add page modal ajax calls
      $this->add_airmed_ajax_actions();
      
      // used for new REST API way
      /*
      add_action('rest_api_init', function(){
        register_rest_route('airmed', '/modal/', array(
          'methods' => 'post',
          'callback' => array($this,'call_airmed_ajax_modal')
        ));
      });
      */
      
      // add page posts
      $this->add_shortcodes();
      
      // add url query parameters
      add_filter( 'query_vars', array($this,'airmed_query_vars'));
      
      add_filter('http_request_args', 'am_http_request_args', 100, 1);
      function am_http_request_args($r)
      {
        $r['timeout'] = 30;
        return $r;
      }
 
      add_action('http_api_curl', 'am_http_api_curl', 100, 1);
      function am_http_api_curl($handle)
      {
        curl_setopt( $handle, CURLOPT_CONNECTTIMEOUT, 30 );
        curl_setopt( $handle, CURLOPT_TIMEOUT, 30 );
      }

      // call the airmed function to create the admin Airmed menu
      $this->airmed_menu();
    }

    function wp_airmed_activate_au()
    {
      
      //require_once AIRMED__PLUGIN_DIR . '/includes/airmed-update-checker.php';
      require_once AIRMED__PLUGIN_DIR . '/includes/wp_airmed_autoupdate.php';
      $wptuts_plugin_current_version = '0.0.17';
      $wptuts_plugin_remote_path = 'http://localhost:81/wordpress/wp-content/updates/update.php';
      $wptuts_plugin_slug = plugin_basename(__FILE__);
      new wp_airmed_auto_update ($wptuts_plugin_current_version, $wptuts_plugin_remote_path, $wptuts_plugin_slug);
      //aLog("setup");
    }
    
    function am_add_rewrite_rules(){
      //add_rewrite_rule('^airmed/([^/]*)/?','index.php?airmed=$matches[1]&page_name=login','top');
    }

    function am_startSession(){
      //aLog("startSession");
      if(!session_id()){
        session_start();
        //$_SESSION['__airmed'] = '';
      }
    }

    function am_endSession(){
      //$_SESSION['__airmed'] = '';
      session_destroy();
    }

    // script and style adds for wordpress admin
    public function add_admin_scripts(){
      // load wordpress dashicons
      wp_enqueue_style('dashicons');

      //add bootstrap 4.6.0
      //wp_enqueue_script('bootstrap_min','https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js',array(),null,true);
      //wp_enqueue_style('bootstrap_css','https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css');
      //wp_enqueue_script('popper_min','https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js',array(),null,true);

      wp_register_style('airmed_admin_bs_styles',plugins_url('/css/airmed-bootstrap.css',__FILE__));
      wp_enqueue_style('airmed_admin_bs_styles');

      wp_register_style('airmed_admin_styles',plugins_url('/css/admin-styles.css',__FILE__));
      wp_enqueue_style('airmed_admin_styles');

      wp_register_script('airmed_admin_script', plugins_url('/js/airmed-settings.js',__FILE__), array('jquery'), null, true);
      wp_enqueue_script('airmed_admin_script');

    }

    // script and style adds
    public function add_scripts(){
      // load wordpress dashicons
      wp_enqueue_style('dashicons');

      //add bootstrap 4.6.0
      //wp_enqueue_script('bootstrap_min','https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js',array(),null,true);
      //wp_enqueue_style('bootstrap_css','https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css');

      //wp_register_script('airmed_bs_script',plugins_url('/plugins/bootstrap.js',__FILE__),array(),null,true);
      wp_register_script('airmed_bs_script',plugins_url('/js/airmed-bootstrap.js',__FILE__),array(),null,true);
      wp_enqueue_script('airmed_bs_script');
      //wp_enqueue_script('bootstrap_min','https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',array(),null,true);
      
      wp_register_style('airmed_bs_styles',plugins_url('/css/airmed-bootstrap.css',__FILE__));
      wp_enqueue_style('airmed_bs_styles');
      
      // jquery validator
      wp_enqueue_script('validator_min','https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js',array(),null,true);
      
      //add dataTables
      wp_register_script('dataTables_min', plugins_url('/plugins/dataTables/datatables.min.js',__FILE__), array(), null, true);
      wp_enqueue_script('dataTables_min');
      wp_register_style('dataTables_css',plugins_url('/plugins/dataTables/datatables.min.css',__FILE__));
      wp_enqueue_style('dataTables_css');

      wp_register_style('airmed_color_styles',plugins_url('/css/color-styles.css',__FILE__));
      wp_enqueue_style('airmed_color_styles');

      $custom_color_css_file = get_option('airmed_options_custom_color_css','');
      $css_path = AIRMED__PLUGIN_DIR . 'css/'.$custom_color_css_file;
      if(!empty($custom_color_css_file) && file_exists($css_path)){
        wp_register_style('airmed_custom_color_styles',plugins_url('/css/'.$custom_color_css_file,__FILE__));
        wp_enqueue_style('airmed_custom_color_styles');
      }


      wp_register_style('airmed_styles',plugins_url('/css/styles.css',__FILE__));
      wp_enqueue_style('airmed_styles');
      
      $custom_css_file = get_option('airmed_options_custom_css','');
      $css_path = AIRMED__PLUGIN_DIR . 'css/'.$custom_css_file;
      if(!empty($custom_css_file) && file_exists($css_path)){
        wp_register_style('airmed_custom_styles',plugins_url('/css/'.$custom_css_file,__FILE__));
        wp_enqueue_style('airmed_custom_styles');
      }
      
      // google reCaptcha
      //wp_enqueue_script('google_recaptcha','https://www.google.com/recaptcha/api.js?render=6LeP5XQcAAAAAJfXEeAAbpJl0LGVxTBYzNrS5yku',array(),null,true);
      
      // can look at breaking out all the different theme styles
     
      //jquery is a dependancy, and we set last param to true to load in footer
      wp_register_script('airmed_script', plugins_url('/js/airmed.js',__FILE__), array('jquery'), null, true);
      wp_enqueue_script('airmed_script');
      
      wp_localize_script('airmed_script',
                         'airmedajaxmodal', 
                         array('ajaxurl' => admin_url( 'admin-ajax.php' )) // used for old admin-ajax way
                         //array('restURL'=> rest_url('/airmed/'))  // rest_url preappends the wp-json path
      );
      
    }

    // setup extra url parameters
    function airmed_query_vars( $qvars ) {
      array_push($qvars,"id","edit","success","verification","userId","code","returnUrl","tab","filters","search");
      //$qvars[] = 'airmed_prod_type';
      return $qvars;
    }

    public function getHMAC(){
      
    }
    
    /**
     * Include required files.
     * @access private
     */
    private function includes() {
      require_once AIRMED__PLUGIN_DIR . '/includes/functions.php';
      //require_once AIRMED__PLUGIN_DIR . '/includes/airmed-update-checker.php';
      require_once AIRMED__PLUGIN_DIR . '/includes/post_functions.php';
      //require_once AIRMED__PLUGIN_DIR . '/includes/font_awesome_functions.php';
      include(AIRMED__PLUGIN_DIR . '/includes/airmed_inventory_shortcode.php');
      include(AIRMED__PLUGIN_DIR . '/includes/airmed_shortcodes.php');
    }

    // create shortcode object for wordpress
    private function add_shortcodes(){
      add_shortcode( 'wp_airmed', 'airmed_inventory_shortcode' );
      add_shortcode( 'wp_airmed_login', 'airmed_login_shortcode' );
      add_shortcode( 'wp_airmed_new_account', 'airmed_new_account_shortcode' );
      add_shortcode( 'wp_airmed_confirm_email', 'airmed_confirm_email_shortcode' );
      add_shortcode( 'wp_airmed_new_application', 'airmed_new_application_shortcode' );
      add_shortcode( 'wp_airmed_dashboard', 'airmed_dashboard_shortcode' );
      add_shortcode( 'wp_airmed_patient', 'airmed_patient_shortcode' );
      add_shortcode( 'wp_airmed_topmenu', 'airmed_topmenu_shortcode' );
      add_shortcode( 'wp_airmed_messages', 'airmed_messages_shortcode' );
      add_shortcode( 'wp_airmed_orders', 'airmed_orders_shortcode' );
      add_shortcode( 'wp_airmed_applications', 'airmed_applications_shortcode' );
      add_shortcode( 'wp_airmed_order', 'airmed_order_shortcode' );
      add_shortcode( 'wp_airmed_application', 'airmed_application_shortcode' );
      add_shortcode( 'wp_airmed_cart', 'airmed_cart_shortcode' );
      add_shortcode( 'wp_airmed_checkout', 'airmed_checkout_shortcode' );
    }
     
    // create form posts
    private function add_page_posts(){
      add_action('admin_post_nopriv_am_login_form','am_login_post');
      add_action('admin_post_am_login_form','am_login_post');

      add_action('admin_post_nopriv_am_new_account_form','am_new_account_post');
      add_action('admin_post_am_new_account_form','am_new_account_post');

      add_action('admin_post_nopriv_am_application_form','am_new_application_post');
      add_action('admin_post_am_application_form','am_new_application_post');

      add_action('load_airmed_dashboard','airmed_dashboard_shortcode');

    }

    // create ajax calls
    private function add_airmed_ajax_actions(){

      // used for old admin-ajax way
      add_action('wp_ajax_nopriv_airmed_modal',array($this,'call_airmed_modal'));
      add_action('wp_ajax_airmed_modal',array($this,'call_airmed_modal'));

      add_action('wp_ajax_nopriv_airmed_login',array($this,'call_airmed_login'));
      add_action('wp_ajax_airmed_login',array($this,'call_airmed_login'));

      add_action('wp_ajax_nopriv_airmed_message_read',array($this,'call_airmed_message_read'));
      add_action('wp_ajax_airmed_message_read',array($this,'call_airmed_message_read'));

      add_action('wp_ajax_nopriv_airmed_message_reply',array($this,'call_airmed_message_reply'));
      add_action('wp_ajax_airmed_message_reply',array($this,'call_airmed_message_reply'));

      add_action('wp_ajax_nopriv_airmed_add_to_order',array($this,'call_airmed_add_to_order'));
      add_action('wp_ajax_airmed_add_to_order',array($this,'call_airmed_add_to_order'));

      add_action('wp_ajax_nopriv_airmed_remove_from_order',array($this,'call_airmed_remove_from_order'));
      add_action('wp_ajax_airmed_remove_from_order',array($this,'call_airmed_remove_from_order'));
      
      add_action('wp_ajax_nopriv_airmed_update_order_item',array($this,'call_airmed_update_order_item'));
      add_action('wp_ajax_airmed_update_order_item',array($this,'call_airmed_update_order_item'));

      add_action('wp_ajax_nopriv_airmed_add_coupon',array($this,'call_airmed_add_coupon'));
      add_action('wp_ajax_airmed_add_coupon',array($this,'call_airmed_add_coupon'));
      
      add_action('wp_ajax_nopriv_airmed_remove_coupon',array($this,'call_airmed_remove_coupon'));
      add_action('wp_ajax_airmed_remove_coupon',array($this,'call_airmed_remove_coupon'));

      add_action('wp_ajax_nopriv_airmed_checkout_hash',array($this,'call_airmed_checkout_hash'));
      add_action('wp_ajax_airmed_checkout_hash',array($this,'call_airmed_checkout_hash'));

      add_action('wp_ajax_nopriv_airmed_globalpayment_postpayment',array($this,'call_airmed_globalpayment_postpayment'));
      add_action('wp_ajax_airmed_globalpayment_postpayment',array($this,'call_airmed_globalpayment_postpayment'));
    }
    
    // used for item modal ajax calls
    function call_airmed_modal(){
      //$result['type'] = "success";
      //$result['action'] = $_POST["action"];

      $prodid = $_POST["prodid"];
      $itype = $_POST["itype"];
      //if (array_key_exists('dateViewed',$jsonObj)
      //$method = $_POST["mtype"];
      //$read  = $_POST["read"];
      //write_log($prodid." - ".$itype);

      $endpoint = getAirmedEndpoint($itype,$prodid);
      
      $apiHost = getAirmedAPIHost();
      $curl_url = $apiHost.'/API/'.$endpoint;
      
      $bearer = '';
      if (!empty($_SESSION['__amAuthToken'])) { 
        $bearer = $_SESSION['__amAuthToken'];
        $header = array("Content-Type: application/json","Authorization: Bearer $bearer");
      }
      else {
        $apiKey = getAirmedAPIKey();
        $apiID = getAirmedAPIId();
        
        $requestURI = strtolower(rawurlencode($curl_url));
        $requestMethod = 'GET';
        $requestTimeStamp = time();  //microtime() provides micro seconds.  moment().valueOf() out puts milliseconds
        $nonce = airmed_GUID();
        $requestContentBase64String = "";
        $signatureRawData = $apiID . $requestMethod . $requestURI . $requestTimeStamp . $nonce . $requestContentBase64String;    
        $signature = utf8_encode($signatureRawData);
        $secretByteArray = base64_decode($apiKey);
        $signatureBytes = hash_hmac('SHA256',$signature,$secretByteArray,true);
        $requestSignatureBase64String = base64_encode($signatureBytes);
        $hmacKey = "Airmed-HMAC " . $apiID . ":" . $requestSignatureBase64String . ":" . $nonce . ":" . $requestTimeStamp;
        
        $header = array("Content-Type: application/json","Authorization: $hmacKey");
      }
      
      aLog("Modal call: ".$curl_url);
      aLog($_POST);
      
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $curl_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_FAILONERROR => true,  //Required for HTTP error codes
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "GET",
        CURLOPT_HTTPHEADER => $header,
      ));
    
      $response = curl_exec($curl);
      $err = curl_error($curl);
      if($errno = curl_errno($curl)) {
        $result['type'] = "error";

        $error_message = curl_strerror($errno);
        //echo "<div>cURL error ({$errno}):\n {$error_message} </div>";
        //if ($debug) echo "cURL Error #:" . $err;
        //else echo "<div> $err </div>";
        $result['error'] = $err;
        $result['errorno'] = $errno;
        $result['errormessage'] = $error_message;
        $result['message'] = "cURL error ({$err}): ".$error_message;
        write_log("cURL error ({$err}): ".$error_message);
        wp_send_json( $result );
      }
      curl_close($curl);

      if (!$err) {
        header('Content-Type: application/json');
        //Create an array of objects from the JSON returned by the API
        $jsonObj = json_decode($response);
        if ($jsonObj === false) {
          // Avoid echo of empty string (which is invalid JSON), and
          // JSONify the error message instead:
          $jsonObj = json_encode(["jsonError" => json_last_error_msg()]);
          if ($jsonObj === false) {
            // This should not happen, but we go all the way now:
            $jsonObj = '{"jsonError":"unknown"}';
          }
          // Set HTTP response status code to: 500 - Internal Server Error
          http_response_code(500);
        }
        
       
        // date comes from server using PST so need to set timezone for date function
        date_default_timezone_set("America/Vancouver");
        
        // used for message modal
        if (property_exists($jsonObj, 'dateViewed')){
          aLog("dateViewed:".$jsonObj->dateViewed);
          //$jsonObj->dateViewed = date('Y-m-d g:ia',strtotime($jsonObj->dateViewed));
          $jsonObj->dateViewed = date('Y-m-d g:ia',(int)substr($jsonObj->dateViewed,6,-10));
          $response = json_encode($jsonObj);
        }
        //write_log($response);
        
        echo $response;
        //write_log(json_encode($jsonObj));
        //$json = json_encode($jsonObj, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
        //echo $json;
        //echo json_encode($jsonObj);
        //wp_send_json( $jsonObj );
        //wp_send_json($testdata);
        //write_log($testdata);
        //echo $testdata;
      }
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    // ajax login
    function call_airmed_login(){
      $debug = true;
        
      $post_params = array();
      if ( !empty( $_POST ) ) {
        // Sanitize the POST field
        $action = empty( $_POST['action'] ) ? '' : $_POST['action'];
        $returnUrl = empty( $_POST['returnUrl'] ) ? airmed_pagelink('/airmed/airmed-dashboard') : sanitize_text_field($_POST['returnUrl']);
        $query = empty( $_POST['query'] ) ? '' : sanitize_text_field($_POST['query']);
        $email = empty( $_POST['amUsername'] ) ? '' : $_POST['amUsername'];
        $password = empty( $_POST['amPassword'] ) ? '' : $_POST['amPassword'];
        // add to array
        $post_params['Email']=$email;
        $post_params['Password']=$password;

        // now send an API call to Airmed to check account for login
        $requestPath = '/API/Account/Authenticate';
        $responseArray = airmed_call_request($requestPath,'POST',false,$post_params);
        
        aLog('Response:');
        aLog($responseArray);
        
        if (array_key_exists('body',$responseArray)){  // could have API error
          $response = $responseArray['body'];
          $err = $responseArray['response']['message'];
          $errno = $responseArray['response']['code'];
          $err_message = $responseArray['response']['message'];
        }
        else { //WP_error
          $errno = 0;
          $error_message = $responseArray['errors']['http_request_failed']['0'];
        }

        // no error proceed with displaying data
        if ($errno === 200) {
          
          //echo "<div>Response: ".$response."</div>";
          $authResponse = str_replace('"','',$response);
          airmed_setSession("__amAuthToken",$authResponse);
          //airmed_setSession("__amPostData",$post_params);
          airmed_setSession("__amError","");
          
          if(!empty($query)){$query='?'.$query;}
          $returnUrl = $returnUrl.$query;

          $response = array("response" => $authResponse,"returnUrl" => $returnUrl,"errno" => $errno,"errmessage" => $err_message );
        }
        else {
          
          if ($errno === 22 || $errno === 401){
            airmed_setSession("__amError","incorrect_login");
          }
          $response = array("response" => "","errno" => $errno,"errmessage" => $err_message );

        }
      }  
      else {
        $response = array("response" => "","errno" => 401,"errmessage" => "Error: No Post Params");
      }

/****************/
      
     
      header('Content-Type: application/json');
      //echo $body;
      echo json_encode($response);
      //echo json_encode($responseArray['response']);
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    // ajax message read post
    function call_airmed_message_read(){
      $post_data = array();
      $post_data['ID'] = $_POST["ID"];
      $post_data['Read']  = $_POST["read"];
      //$post_data['$itype'] = $_POST["itype"];

      $curl_url = getAirmedAPIHost().'/API/Message/UpdateRead/';
      
      $bearer = '';
      if (!empty($_SESSION['__amAuthToken'])) { 
        $bearer = $_SESSION['__amAuthToken'];
      }
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');

      
      aLog("Ajax message read: ".$curl_url);
      aLog("header:");
      aLog($header);
      aLog("Post Data:");
      aLog($post_data);
      /*
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => $curl_url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_FAILONERROR => true,  //Required for HTTP error codes
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "PUT",
        CURLOPT_POSTFIELDS => $post_data,
        CURLOPT_HTTPHEADER => $header,
      ));
      $response = curl_exec($curl);
      $err = curl_error($curl);
      $errno = curl_errno($curl);
      $error_message = curl_strerror($errno);
      aLog($response);
      aLog($err);
      aLog($error_message);
      
      curl_close($curl);
      */
      /*
      $curl = curl_init();
      curl_setopt($curl, CURLOPT_URL, $curl_url);
      curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
      //curl_setopt($curl, CURLOPT_POST, 1);
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
      curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
      */
      /*
      $response = curl_exec($curl);
      
      aLog($response);
      $err = curl_error($curl);
      if($errno = curl_errno($curl)) {
        $result['type'] = "error";

        $error_message = curl_strerror($errno);
        //echo "<div>cURL error ({$errno}):\n {$error_message} </div>";
        //if ($debug) echo "cURL Error #:" . $err;
        //else echo "<div> $err </div>";
        $result['error'] = $err;
        $result['errorno'] = $errno;
        $result['errormessage'] = $error_message;
        $result['message'] = "cURL error ({$err}): ".$error_message;
        //write_log("cURL error ({$err}): ".$error_message);
        wp_send_json( $result );
      }
      curl_close($curl);

      if (!$err) {
        header('Content-Type: application/json');
        //Create an array of objects from the JSON returned by the API
        $jsonObj = json_decode($response);
        if ($jsonObj === false) {
          // Avoid echo of empty string (which is invalid JSON), and
          // JSONify the error message instead:
          $jsonObj = json_encode(["jsonError" => json_last_error_msg()]);
          if ($jsonObj === false) {
            // This should not happen, but we go all the way now:
            $jsonObj = '{"jsonError":"unknown"}';
          }
          // Set HTTP response status code to: 500 - Internal Server Error
          http_response_code(500);
        }
        
        //print_r($jsonObj);
        $result['type'] = "success";
        //write_log($response);
        echo $response;
      }
      */
      
      $json_post = json_encode($post_data);
      //aLog($json_post);
      $args = array();
      $args += ['headers'=>$header];
      $args += ['method'=>'PUT'];
      $args += ['body'=>$json_post];
      //$args += ['body'=>'{"ID":"MSGS2021120713454310000031","Read":"true"}'];

      $responseArray = wp_remote_request($curl_url,$args);
      
      aLog('Response:');
      aLog($responseArray);
      $body = wp_remote_retrieve_body($responseArray);
      if (array_key_exists('body',$responseArray)){
        $response["response"] = $responseArray['body'];
        $response["err"] = $responseArray['response']['message'];
        $response["errno"] = $responseArray['response']['code'];
        $response["err_message"] = $responseArray['response']['message'];


        //if (empty($response["response"])){aLog("body is empty");}
        //else{aLog("body is not empty");}
        
        $jsonObj = json_decode($response["response"]);
                
        // date comes from server using PST so need to set timezone for date function
        date_default_timezone_set("America/Vancouver");
        aLog("dateviewed:".$jsonObj->dateViewed." timezone:".date('T'));
        if (!empty($jsonObj->dateViewed)){
          
          //$jsonObj->dateViewed = date('Y-m-d g:ia',strtotime($jsonObj->dateViewed));
          $jsonObj->dateViewed = date('Y-m-d g:ia',(int)substr($jsonObj->dateViewed,6,-10));
          
          //$response["response"] = json_encode($jsonObj);
          $response["response"] = $jsonObj;
        }
      }
      else { //WP_error
        $response["errno"] = 0;
        $response["error_message"] = $requestArray['errors']['http_request_failed']['0'];
      }
      header('Content-Type: application/json');
      //echo $responseArray;
      //echo "{'error': $errno,'message':'$err_message','response':$body}";
      //echo $body;
      echo json_encode($response);
      
      //echo "";
      //aLog($response);
      //aLog($body);
      //aLog(json_encode($response));
      //echo $$response["response"];
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    // ajax message reply post
    function call_airmed_message_reply(){
      $post_data = array();
      $post_data['ID'] = $_POST["ID"];
      $post_data['Priority']  = $_POST["Priority"];
      $post_data['Details']  = str_replace("\'","'",$_POST["Details"]);
      $post_data['Subject']  = str_replace("\'","'",$_POST["Subject"]);
      $post_data['RecipientName']  = $_POST["RecipientName"];
      $type  = $_POST["Reply"];
      
      $page = "Reply";
      
      aLog("type: ".$type);
      
      if ($type != "true"){ 
        $page = "Followup";
      }
      
      $curl_url = getAirmedAPIHost()."/API/Message/$page/";
      
      $bearer = '';
      if (!empty($_SESSION['__amAuthToken'])) { 
        $bearer = $_SESSION['__amAuthToken'];
      }
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');
      //$header += ['Content-Type'=>'application/json'];


      aLog("Ajax message reply: ".$curl_url);
      aLog($header);
      //aLog($post_data);

      $json_post = json_encode($post_data);
      aLog($json_post);
      $args = array();
      $args += ['headers'=>$header];
      $args += ['body'=>$json_post];
      //$args += ['body'=>'{"ID":"MSGS2021120915110710000017","Read":true}'];

      $responseArray = wp_remote_post($curl_url,$args);
      
      aLog('Response:');
      aLog($responseArray);
      
      $body = wp_remote_retrieve_body($responseArray);
      if (array_key_exists('body',$responseArray)){
        $response["response"] = $responseArray['body'];
        $response["err"] = $responseArray['response']['message'];
        $response["errno"] = $responseArray['response']['code'];
        $response["err_message"] = $responseArray['response']['message'];
      }
      else { //WP_error
        $response["errno"] = 0;
        $response["error_message"] = $requestArray['errors']['http_request_failed']['0'];
      }
      
      header('Content-Type: application/json');
      //echo $body;
      echo json_encode($response);
      //echo json_encode($responseArray['response']);
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    function hasOpenOrder(){
      $bearer = $_SESSION['__amAuthToken'];
      $requestURL = getAirmedAPIHost()."/API/Order/HasOpenOrder/";
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');
      $requestArray = wp_remote_get($requestURL,array('headers'=>$header));
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];

      aLog("HasOpenOrder:");
      aLog($response);
      return $response;
    }

    function createOrder(){
      $bearer = $_SESSION['__amAuthToken'];
      $requestURL = getAirmedAPIHost()."/API/Order/CreateOrder/";
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');
      $requestArray = wp_remote_get($requestURL,array('headers'=>$header));
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];

      aLog("CreateOrder:");
      aLog($response);
      return $response;
    }

    function getOrder($id){
      $bearer = $_SESSION['__amAuthToken'];
      $requestURL = getAirmedAPIHost()."/API/Order/GetOrder/$id";
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');
      $requestArray = wp_remote_get($requestURL,array('headers'=>$header));
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];

      aLog("GetOrder:");
      aLog($response);
      return $response;
    }

    // ajax add to order post
    function call_airmed_add_to_order(){
     
      // check for open order
      $hasOpenOrder = json_decode($this->hasOpenOrder());

      // if not open, create
      if(empty($hasOpenOrder->hasOpenOrder)){
        //aLog("new order");
        $newOrder = json_decode($this->createOrder());
        $orderID = $newOrder->orderID;
        //aLog($newOrder);
      }
      else{
        $orderID = $hasOpenOrder->orderID;
        aLog("order exists: ".$orderID);
      }
      
      $requestURL = getAirmedAPIHost()."/API/Order/AddToOrder/";
      $bearer = $_SESSION['__amAuthToken'];
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');

      $post_data = array();
      $post_data['OrderID'] = $orderID;
      $post_data['ItemID'] = $_POST["itemid"];
      $post_data['Qty'] = "1";

      //aLog("Ajax message reply: ".$requestURL);
      //aLog($header);
      //aLog($post_data);

      $json_post = json_encode($post_data);
      aLog($json_post);
      $args = array();
      $args += ['headers'=>$header];
      $args += ['body'=>$json_post];
      //$args += ['body'=>'{"ID":"MSGS2021120915110710000017","Read":true}'];

      $responseArray = wp_remote_post($requestURL,$args);
      
      aLog('Add to Order Response:');
      aLog($responseArray);
      
      $body = wp_remote_retrieve_body($responseArray);
      if (array_key_exists('body',$responseArray)){
        $response["response"] = $responseArray['body'];
        $response["err"] = $responseArray['response']['message'];
        $response["errno"] = $responseArray['response']['code'];
        $response["err_message"] = $responseArray['response']['message'];
        if ($response["errno"] == 200){
          // get order after new item added
          $jsonUpdatedOrder = $this->getOrder($orderID);
          $updatedOrder = json_decode($jsonUpdatedOrder);
          //aLog("Updated Order:");
          //aLog($newOrder);
          $response["response"] = $updatedOrder;
        }
      }
      else { //WP_error
        $response["response"] = '';
        $response["errno"] = 0;
        $response["error_message"] = $requestArray['errors']['http_request_failed']['0'];
      }
      
      //$response["errno"] = 0;
      //$response["error_message"] = "hello";
      header('Content-Type: application/json');
      //echo $body;
      echo json_encode($response);
      //echo json_encode($responseArray['response']);
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    // ajax add to order post
    function call_airmed_remove_from_order(){

      $requestURL = getAirmedAPIHost()."/API/Order/RemoveFromOrder/";
      $bearer = $_SESSION['__amAuthToken'];
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');

      $post_data = array();
      $post_data['ItemID'] = $_POST["itemid"];
      $orderID = $_POST["orderid"];

      //aLog("Ajax message reply: ".$requestURL);
      //aLog($header);
      //aLog($post_data);

      $json_post = json_encode($post_data);
      aLog($json_post);
      $args = array();
      $args += ['headers'=>$header];
      $args += ['body'=>$json_post];
      $responseArray = wp_remote_post($requestURL,$args);
      
      aLog('Remove from Order Response:');
      aLog($responseArray);
      
      $body = wp_remote_retrieve_body($responseArray);
      if (array_key_exists('body',$responseArray)){
        $response["response"] = $responseArray['body'];
        $response["err"] = $responseArray['response']['message'];
        $response["errno"] = $responseArray['response']['code'];
        $response["err_message"] = $responseArray['response']['message'];
        
        // get order after new item added
        $jsonUpdatedOrder = $this->getOrder($orderID);
        $updatedOrder = json_decode($jsonUpdatedOrder);
        $response["response"] = $updatedOrder;

      }
      else { //WP_error
        $response["response"] = '';
        $response["errno"] = 0;
        $response["error_message"] = $requestArray['errors']['http_request_failed']['0'];
      }
      
      //$response["errno"] = 0;
      //$response["error_message"] = "hello";
      header('Content-Type: application/json');
      //echo $body;
      echo json_encode($response);
      //echo json_encode($responseArray['response']);
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    // ajax add to order post
    function call_airmed_update_order_item(){
     
      $requestURL = getAirmedAPIHost()."/API/Order/UpdateQuantity/";
      $bearer = $_SESSION['__amAuthToken'];
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');

      $post_data = array();
      $post_data['ItemID'] = $_POST["itemid"];
      $post_data['Qty'] = $_POST["qty"];

      //aLog("Ajax message reply: ".$requestURL);
      //aLog($header);
      //aLog($post_data);

      $json_post = json_encode($post_data);
      aLog($json_post);
      $args = array();
      $args += ['headers'=>$header];
      $args += ['body'=>$json_post];


      $responseArray = wp_remote_post($requestURL,$args);
      
      aLog('Update Item Response:');
      aLog($responseArray);
      
      $body = wp_remote_retrieve_body($responseArray);
      if (array_key_exists('body',$responseArray)){
        $response["response"] = $responseArray['body'];
        $response["err"] = $responseArray['response']['message'];
        $response["errno"] = $responseArray['response']['code'];
        $response["err_message"] = $responseArray['response']['message'];
        //if ($response["errno"] == 200){
          // get order after new item added
        //  $jsonUpdatedOrder = $this->getOrder($orderID);
        //  $updatedOrder = json_decode($jsonUpdatedOrder);
          //aLog("Updated Order:");
          //aLog($newOrder);
        //  $response["response"] = $updatedOrder;
        //}
      }
      else { //WP_error
        $response["response"] = '';
        $response["errno"] = 0;
        $response["error_message"] = $requestArray['errors']['http_request_failed']['0'];
      }

      $response["response"] = '';
      $response["errno"] = 200;
      $response["error_message"] = "hello";
      header('Content-Type: application/json');
      //echo $body;
      echo json_encode($response);
      //echo json_encode($responseArray['response']);
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    // ajax add to order post
    function call_airmed_add_coupon(){
     
      $requestURL = getAirmedAPIHost()."/API/Order/ApplyCoupon/";
      $bearer = $_SESSION['__amAuthToken'];
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');

      $orderId = $_POST["orderid"];
      $post_data = array();
      $post_data['ID'] = $orderId;
      $post_data['CouponCode'] = $_POST["couponcode"];

      //aLog("Ajax message reply: ".$requestURL);
      //aLog($header);
      //aLog($post_data);

      $json_post = json_encode($post_data);
      aLog($json_post);
      $args = array();
      $args += ['headers'=>$header];
      $args += ['body'=>$json_post];

      $responseArray = wp_remote_post($requestURL,$args);
      
      aLog('Apply Coupon Response:');
      aLog($responseArray);
      
      $body = wp_remote_retrieve_body($responseArray);
      if (array_key_exists('body',$responseArray)){
        $response["response"] = $responseArray['body'];
        $response["err"] = $responseArray['response']['message'];
        $response["errno"] = $responseArray['response']['code'];
        $response["err_message"] = $responseArray['response']['message'];
        
        //if ($response["errno"] == 200){
          // get order after new item added
          //$jsonUpdatedOrder = $this->getOrder($orderId);
          //$updatedOrder = json_decode($jsonUpdatedOrder);
          //aLog("Updated Order:");
          //aLog($newOrder);
          //$response["response"] = $updatedOrder;
        //}
      }
      else { //WP_error
        $response["response"] = '';
        $response["errno"] = 0;
        $response["error_message"] = $requestArray['errors']['http_request_failed']['0'];
      }
      
      //$response["errno"] = 0;
      //$response["error_message"] = "hello";
      header('Content-Type: application/json');
      //echo $body;
      echo json_encode($response);
      //echo json_encode($responseArray['response']);
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    // ajax add to order post
    function call_airmed_remove_coupon(){

      $requestURL = getAirmedAPIHost()."/API/Order/RemoveCoupon/";
      $bearer = $_SESSION['__amAuthToken'];
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');

      $post_data = array();
      $orderID = $_POST["orderid"];
      $post_data['ID'] = $orderID;

      //aLog("Ajax message reply: ".$requestURL);
      //aLog($header);
      //aLog($post_data);

      $json_post = json_encode($post_data);
      //aLog($json_post);
      $args = array();
      $args += ['headers'=>$header];
      $args += ['body'=>$json_post];
      $responseArray = wp_remote_post($requestURL,$args);
      
      aLog('Remove Coupon Response:');
      aLog($responseArray);
      
      $body = wp_remote_retrieve_body($responseArray);
      if (array_key_exists('body',$responseArray)){
        $response["response"] = $responseArray['body'];
        $response["err"] = $responseArray['response']['message'];
        $response["errno"] = $responseArray['response']['code'];
        $response["err_message"] = $responseArray['response']['message'];
        
        // get order after new item added
        //$jsonUpdatedOrder = $this->getOrder($orderID);
        //$updatedOrder = json_decode($jsonUpdatedOrder);
        //$response["response"] = $updatedOrder;

      }
      else { //WP_error
        $response["response"] = '';
        $response["errno"] = 0;
        $response["error_message"] = $requestArray['errors']['http_request_failed']['0'];
      }
      
      //$response["errno"] = 0;
      //$response["error_message"] = "hello";
      header('Content-Type: application/json');
      //echo $body;
      echo json_encode($response);
      //echo json_encode($responseArray['response']);
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    // ajax cross reference checkout hash
    function call_airmed_checkout_hash(){

      $data = $_POST["data"];
      //aLog($data);
      //aLog("AuthCode: ".$data["AUTHCODE"]);
      $timestamp = $data["TIMESTAMP"];
      $merchantid = $data["MERCHANT_ID"];
      $orderid = $data["ORDER_ID"];
      $result = $data["RESULT"];
      $message = $data["MESSAGE"];
      $pasref = $data["PASREF"];
      $authcode = $data["AUTHCODE"];
      $orgSHA1 = $data["SHA1HASH"];
      
      // get secret from API
      
      $inputStr = sha1($timestamp.".".$merchantid.".".$orderid.".".$result.".".$message.".".$pasref.".".$authcode);
      $sha1Str = sha1($inputStr.".secret");
      //aLog($sha1Str." - ".$orgSHA1);
      
      if ($orgSHA1 == $sha1Str){
        $response["errno"] = 0;
        $response["message"] = "Authorized";
        $response["returnurl"] = $data["MERCHANT_RESPONSE_URL"];
      }
      else {
        $response["errno"] = 100;
        $response["message"] = "SHA1 hash values to not match.";
      }
      header('Content-Type: application/json');
      echo json_encode($response);
      
      // don't forget to end your scripts with a die() function - very important
      die();
    }

    function call_airmed_globalpayment_postpayment(){
      // $_POST["data"] comes in as a json object
      $post_data = $_POST["data"];
      $post_data["PatientID"] = $_POST["patientid"];
      $post_data["OrderID"] = $_POST["orderid"];
      aLog("post payment:");
      aLog($post_data);

      $requestURL = getAirmedAPIHost()."/API/GlobalPayments/PostPayment/";
      $bearer = $_SESSION['__amAuthToken'];
      $header = array('Authorization'=>'Bearer '.$bearer,'Content-Type'=>'application/json');

      $json_post = json_encode($post_data);
      aLog("JSON Post:");
      aLog($json_post);
      $args = array();
      $args += ['headers'=>$header];
      $args += ['body'=>$json_post];

      $responseArray = wp_remote_post($requestURL,$args);
      
      aLog('PostPayment Response:');
      aLog($responseArray);
      
      $body = wp_remote_retrieve_body($responseArray);
      if (array_key_exists('body',$responseArray)){
        $response["response"] = $responseArray['body'];
        $response["err"] = $responseArray['response']['message'];
        $response["errno"] = $responseArray['response']['code'];
        $response["err_message"] = $responseArray['response']['message'];
      }
      else { //WP_error
        $response["response"] = '';
        $response["errno"] = 0;
        $response["error_message"] = $requestArray['errors']['http_request_failed']['0'];
      }
      
      //$response["errno"] = 0;
      //$response["error_message"] = "hello";
      header('Content-Type: application/json');
      //echo $body;
      echo json_encode($response);
      //echo json_encode($responseArray['response']);
      
      // don't forget to end your scripts with a die() function - very important
      die();

    }

    // sets up the wordpress admin Airmed Menu
    private function airmed_menu(){
      // add hook for the admin menu
      add_action( 'admin_menu', 'airmed_options_menu' );
      
      if( !function_exists("airmed_options_menu") ) { 
        // setup airmed options menu
        function airmed_options_menu(){
          $page_title = 'AirMed';
          $menu_title = 'AirMed';
          $capability = 'manage_options';
          $menu_slug  = 'airmed-options';
          $function   = 'airmed_options_page';
          $theme_function = 'airmed_options_theme_page';
          $icon_url   = 'dashicons-media-code';
          $position   = 2;
          add_menu_page( $page_title,
                         $menu_title,
                         $capability,
                         $menu_slug,
                         $function,
                         $icon_url,
                         $position );
          add_submenu_page($menu_slug,'Settings','Settings',$capability,'airmed-options',$function);
          add_submenu_page($menu_slug,'Catalog Themes','Catalog Themes',$capability,'airmed-options-themes',$theme_function);
          add_action('admin_init','update_airmed_options_settings');
          add_action('admin_notices', function(){settings_errors();});
        }
      }

      //Register plugin settings to db
      if( !function_exists("update_airmed_options_settings") ) { 
        function update_airmed_options_settings() {
          register_setting( 'airmed-options-settings', 'airmed_options_api_id' ); 
          register_setting( 'airmed-options-settings', 'airmed_options_api_host' ); 
          register_setting( 'airmed-options-settings', 'airmed_options_api_key' ); 
          register_setting( 'airmed-options-settings', 'airmed_options_logo' ); 
          register_setting( 'airmed-options-settings', 'airmed_options_carousel' );
          register_setting( 'airmed-options-settings', 'airmed_options_cat_carousel' );
          register_setting( 'airmed-options-settings', 'airmed_options_img_hover' );
          register_setting( 'airmed-options-settings', 'airmed_options_cat_img_hover' );
          
          register_setting( 'airmed-options-settings', 'airmed_options_cat_img_hover1' );
          register_setting( 'airmed-options-settings', 'airmed_options_cat_img_hover2' );
          register_setting( 'airmed-options-settings', 'airmed_options_img_hover1' );
          register_setting( 'airmed-options-settings', 'airmed_options_img_hover2' );
          register_setting( 'airmed-options-settings', 'airmed_options_img_show_cart' );
          register_setting( 'airmed-options-settings', 'airmed_options_img_show_order' );
          
          register_setting( 'airmed-options-settings', 'airmed_options_custom_css' );
          register_setting( 'airmed-options-settings', 'airmed_options_custom_color_css' );
          register_setting( 'airmed-options-settings', 'airmed_options_hide_logo' );
          register_setting( 'airmed-options-settings', 'airmed_options_main_nav_register' );
          register_setting( 'airmed-options-settings', 'airmed_options_use_site_menu' );
          register_setting( 'airmed-options-settings', 'airmed_options_login_type' );
          register_setting( 'airmed-options-settings', 'airmed_options_show_shop');
          register_setting( 'airmed-options-settings', 'airmed_options_global_payments_method' );
          register_setting( 'airmed-options-settings', 'airmed_options_payment_success_msg' );

          //register_setting( 'airmed-options-settings', 'airmed_options_allow_to_shop');
          register_setting( 'airmed-options-settings', 'airmed_options_shop_navigation_order' );
          register_setting( 'airmed-options-settings', 'airmed_options_cart_navigation_order' );
          register_setting( 'airmed-options-settings', 'airmed_options_login_navigation_order' );
          register_setting( 'airmed-options-themes', 'airmed_options_theme_modal' ); 
          register_setting( 'airmed-options-themes', 'airmed_options_theme_filter' ); 
          register_setting( 'airmed-options-themes', 'airmed_options_theme_catalog' ); 
          register_setting( 'airmed-options-themes', 'airmed_options_theme_nav' ); 
          
        } 
      }

      //Create plugin main settings page
      if( !function_exists("airmed_options_page") ) { 
        function airmed_options_page(){ 
      ?>
      <div id='airmed-wrapper' class='airmed-wrapper airmed-settings'>
        <h3 class='airmed-admin-title'>AirMed Settings</h3> 
        <div class='card airmed-card'>
          <div class='card-body'>

            <form method="post" action="options.php">
      <?php 
          settings_fields( 'airmed-options-settings' );
          do_settings_sections( 'airmed-options-settings' );
          $cat_carousel_ischecked = get_option( 'airmed_options_cat_carousel') ? "checked": "";
          $carousel_ischecked = get_option( 'airmed_options_carousel') ? "checked": "";
          $cat_img_hover_ischecked = get_option( 'airmed_options_cat_img_hover') ? "checked": "";
          $img_hover_ischecked = get_option( 'airmed_options_img_hover') ? "checked": "";
          $logo_ischecked = get_option( 'airmed_options_hide_logo') ? "checked": "";
          $img_show_cart_ischecked = get_option( 'airmed_options_img_show_cart') ? "checked": "";
          $img_show_order_ischecked = get_option( 'airmed_options_img_show_order') ? "checked": "";
          $show_shop_ischecked = get_option( 'airmed_options_show_shop') ? "checked": "";
          $nav_register_ischecked = get_option( 'airmed_options_main_nav_register') ? "checked": "";
          $use_site_menu_ischecked = get_option( 'airmed_options_use_site_menu') ? "checked": "";
          
          $menuLocations = get_nav_menu_locations(); // Get our nav locations (set in our theme, usually functions.php). This returns an array of menu locations ([LOCATION_NAME] = MENU_ID);
          $menuID = $menuLocations['primary']; // Get the *primary* menu ID
          $primaryNav = wp_get_nav_menu_items($menuID);
          $defaultCount = count($primaryNav);
          $navLinkCount = $defaultCount+3;
      ?>
              <div class="row mb-3">
                <label for="airmed_options_api_id" class='col-sm-2 col-form-label'>API ID:</label>
                <div class='col-sm-4'>
                  <input class="form-control regular-text" type="text" id="airmed_options_api_id" name="airmed_options_api_id" value="<?php echo get_option( 'airmed_options_api_id' ); ?>"/>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_api_key" class='col-sm-2 col-form-label'>API Key:</label>
                <div class='col-sm-4'>
                  <input class="form-control regular-text" type="text" id="airmed_options_api_key" name="airmed_options_api_key" value="<?php echo get_option( 'airmed_options_api_key' ); ?>"/>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_api_host" class='col-sm-2 col-form-label'>API Host:</label>
                <div class='col-sm-4'>
                  <input class="form-control regular-text" type="text" id="airmed_options_api_host" name="airmed_options_api_host" value="<?php echo get_option( 'airmed_options_api_host' ); ?>"/>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_global_payments_method" class='col-sm-2 col-form-label'>Global Payments:</label>
                <div class='col-sm-3'>
                  <?php $global_payments_method = get_option( 'airmed_options_global_payments_method','Sandbox' ); ?>
                  <select id='airmed_options_global_payments_method' name="airmed_options_global_payments_method" class="form-control">
                    <option value="Sandbox" <?php if($global_payments_method == 'Sandbox'){echo "selected";} ?> >Sandbox</option>
                    <option value="Live" <?php if($global_payments_method == 'Live'){echo "selected";} ?> >Live</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_logo" class='col-sm-2 col-form-label'>Logo File:</label>
                <div class='col-sm-4'>
                  <input class="form-control regular-text" type="text" id="airmed_options_logo" name="airmed_options_logo" value="<?php echo get_option( 'airmed_options_logo' ); ?>"/>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_custom_color_css" class='col-sm-2 col-form-label'>Custom Color CSS File:</label>
                <div class='col-sm-4'>
                  <input class="form-control regular-text" type="text" id="airmed_options_custom_css" name="airmed_options_custom_color_css" value="<?php echo get_option( 'airmed_options_custom_color_css' ); ?>"/>
                  <div class="alert alert-info airmed-theme-alert">This file <strong>MUST</strong> reside in the Wordpress plugins AirMed CSS folder</div>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_custom_css" class='col-sm-2 col-form-label'>Custom CSS File:</label>
                <div class='col-sm-4'>
                  <input class="form-control regular-text" type="text" id="airmed_options_custom_css" name="airmed_options_custom_css" value="<?php echo get_option( 'airmed_options_custom_css' ); ?>"/>
                  <div class="alert alert-info airmed-theme-alert">This file <strong>MUST</strong> reside in the Wordpress plugins AirMed CSS folder</div>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_login_type" class='col-sm-2 col-form-label'>Login Method:</label>
                <div class='col-sm-3'>
                  <?php $slideout = get_option( 'airmed_options_login_type',1 ); ?>
                  <select id='airmed_options_login_type' name="airmed_options_login_type" class="form-control">
                    <option value="1" <?php if($slideout == 1){echo "selected";} ?> >Page</option>
                    <option value="2" <?php if($slideout == 2){echo "selected";} ?> >Modal</option>
                    <option value="3" <?php if($slideout == 3){echo "selected";} ?> >Slide out</option>
                  </select>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_show_shop" class='col-sm-2 col-form-label form-check-label'>Show Shop Prior to Login:</label>
                <div class='col-sm-10'>
                  <div class="form-check">
                    <input class="regular-text" type="checkbox" id="airmed_options_show_shop" name="airmed_options_show_shop" <?php echo $show_shop_ischecked ?>/>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_hide_logo" class='col-sm-2 col-form-label form-check-label'>Hide Header Logo:</label>
                <div class='col-sm-10'>
                  <div class="form-check">
                    <input class="regular-text" type="checkbox" id="airmed_options_hide_logo" name="airmed_options_hide_logo" <?php echo $logo_ischecked ?>/>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_main_nav_register" class='col-sm-2 col-form-label form-check-label'>Move Register/Login to site navigation:</label>
                <div class='col-sm-10'>
                  <div class="form-check">
                    <input class="regular-text" type="checkbox" id="airmed_options_main_nav_register" name="airmed_options_main_nav_register" <?php echo $nav_register_ischecked ?>/>
                  </div>
                </div>
              </div>
              <div class="row mb-3">
                <label for="airmed_options_use_site_menu" class='col-sm-2 col-form-label form-check-label'>Use Site Menu:</label>
                <div class='col-sm-10'>
                  <div class="form-check">
                    <input class="regular-text" type="checkbox" id="airmed_options_use_site_menu" name="airmed_options_use_site_menu" <?php echo $use_site_menu_ischecked ?>/>
                  </div>
                </div>
              </div>
              <div class="row mb-3">  <? // Navigation Menu ?>
                <div class="col-md-6">
                  <div class='card airmed-card'>
                    <div class='card-header'>Navigation Menu Ordering</div>
                    <div class='card-body'>
                      <div class="row mb-3 alert alert-info airmed-theme-alert">
                        ** Order number includes current nav items as well as AirMed's three items (Shop, Cart, Login/Account)
                      </div>
                      <div class="row mb-3">
                        <label for="airmed_options_shop_navigation_order" class='col-sm-3 col-form-label'>Shop Link:</label>
                        <div class='col-sm-9'>
                          <?php $nav_order = get_option( 'airmed_options_shop_navigation_order',$defaultCount+1 ); ?>
                          <select id='airmed_options_shop_navigation_order' name="airmed_options_shop_navigation_order" class="form-control">
                            <?php for ($c = 1; $c <= $navLinkCount; $c++): ?>
                            <option value="<?php echo $c ?>" <?php if($nav_order == $c){echo "selected";} ?> ><?php echo $c ?></option>
                            <?php endfor; ?>
                            
                          </select>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <label for="airmed_options_cart_navigation_order" class='col-sm-3 col-form-label'>Cart Link:</label>
                        <div class='col-sm-9'>
                          <?php $nav_order = get_option( 'airmed_options_cart_navigation_order',$defaultCount+2 ); ?>
                          <select id='airmed_options_cart_navigation_order' name="airmed_options_cart_navigation_order" class="form-control">
                            <?php for ($c = 1; $c <= $navLinkCount; $c++): ?>
                            <option value="<?php echo $c ?>" <?php if($nav_order == $c){echo "selected";} ?> ><?php echo $c ?></option>
                            <?php endfor; ?>
                            
                          </select>
                        </div>
                      </div>
                      <div class="row mb-3">
                        <label for="airmed_options_login_navigation_order" class='col-sm-3 col-form-label'>Login / Account Link:</label>
                        <div class='col-sm-9'>
                          <?php $nav_order = get_option( 'airmed_options_login_navigation_order',$defaultCount+3 ); ?>
                          <select id='airmed_options_login_navigation_order' name="airmed_options_login_navigation_order" class="form-control">
                            <?php for ($c = 1; $c <= $navLinkCount; $c++): ?>
                            <option value="<?php echo $c ?>" <?php if($nav_order == $c){echo "selected";} ?> ><?php echo $c ?></option>
                            <?php endfor; ?>
                            
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row mb-3">  <? //Product Images ?>
                <div class="col-xl-6 col-md-8">
                  <div class='card airmed-card'>
                    <div class='card-header'>Product Image Settings</div>
                    <div class='card-body'>
                    
                      <div class="row mb-3">
                        <div class="col-md-6">
                          <div class='card airmed-card'>
                            <div class='card-body'>

                              <div class="row">
                                <label for="airmed_options_cat_img_hover" class='col-sm-9 col-form-label form-check-label'>Use Image Hover in Catalog:</label>
                                <div class='col-sm-3'>
                                  <div class="form-check">
                                    <input class="regular-text" type="checkbox" id="airmed_options_cat_img_hover" name="airmed_options_cat_img_hover" <?php echo $cat_img_hover_ischecked ?>/>
                                  </div>
                                </div>
                                <label for="airmed_options_img_hover1" class='col-5 col-form-label'>Image 1:</label>
                                <div class='col-7'>
                                  <?php $hover_image1 = get_option( 'airmed_options_cat_img_hover1','Brand'); ?>
                                  <select id='airmed_options_cat_img_hover1' name="airmed_options_cat_img_hover1" class="form-select form-select-sm">
                                    <option value="Brand" <?php if($hover_image1 == "Brand"){echo "selected";} ?> >Brand</option>
                                    <option value="Strain" <?php if($hover_image1 == "Strain"){echo "selected";} ?> >Strain</option>
                                    <option value="Product" <?php if($hover_image1 == "Product"){echo "selected";} ?> >Product</option>
                                  </select>
                                </div>
                                <label for="airmed_options_cat_img_hover2" class='col-5 col-form-label'>Image 2:</label>
                                <div class='col-7'>
                                  <?php $hover_image2 = get_option( 'airmed_options_cat_img_hover2','Brand'); ?>
                                  <select id='airmed_options_cat_img_hover2' name="airmed_options_cat_img_hover2" class="form-select form-select-sm">
                                    <option value="Brand" <?php if($hover_image2 == "Brand"){echo "selected";} ?> >Brand</option>
                                    <option value="Strain" <?php if($hover_image2 == "Strain"){echo "selected";} ?> >Strain</option>
                                    <option value="Product" <?php if($hover_image2 == "Product"){echo "selected";} ?> >Product</option>
                                  </select>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                        <div class="col-md-6">
                          <div class='card airmed-card'>
                            <div class='card-body'>

                              <div class="row">
                                <label for="airmed_options_img_hover" class='col-sm-9 col-form-label form-check-label'>Use Image Hover in Modal:</label>
                                <div class='col-sm-3'>
                                  <div class="form-check">
                                    <input class="regular-text" type="checkbox" id="airmed_options_img_hover" name="airmed_options_img_hover" <?php echo $img_hover_ischecked ?>/>
                                  </div>
                                </div>
                                <label for="airmed_options_cat_img_hover1" class='col-5 col-form-label'>Image 1:</label>
                                <div class='col-7'>
                                  <?php $hover_image1 = get_option( 'airmed_options_img_hover1','Brand'); ?>
                                  <select id='airmed_options_img_hover1' name="airmed_options_img_hover1" class="form-select form-select-sm">
                                    <option value="Brand" <?php if($hover_image1 == "Brand"){echo "selected";} ?> >Brand</option>
                                    <option value="Strain" <?php if($hover_image1 == "Strain"){echo "selected";} ?> >Strain</option>
                                    <option value="Product" <?php if($hover_image1 == "Product"){echo "selected";} ?> >Product</option>
                                  </select>
                                </div>
                                <label for="airmed_options_img_hover2" class='col-5 col-form-label'>Image 2:</label>
                                <div class='col-7'>
                                  <?php $hover_image2 = get_option( 'airmed_options_img_hover2','Brand'); ?>
                                  <select id='airmed_options_img_hover2' name="airmed_options_img_hover2" class="form-select form-select-sm">
                                    <option value="Brand" <?php if($hover_image2 == "Brand"){echo "selected";} ?> >Brand</option>
                                    <option value="Strain" <?php if($hover_image2 == "Strain"){echo "selected";} ?> >Strain</option>
                                    <option value="Product" <?php if($hover_image2 == "Product"){echo "selected";} ?> >Product</option>
                                  </select>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>
                      
                      <div class="row">
                        <div class="col-md-6"> <? //Carousel ?>
                          <div class='card airmed-card'>
                            <div class='card-body'>

                              <div class="row">
                                <label for="airmed_options_cat_carousel" class='col-sm-9 col-form-label form-check-label'>Use Image Carousel in Catalog:</label>
                                <div class='col-sm-3'>
                                  <div class="form-check">
                                    <input class="regular-text" type="checkbox" id="airmed_options_cat_carousel" name="airmed_options_cat_carousel" <?php echo $cat_carousel_ischecked ?>/>
                                  </div>
                                </div>
                                <label for="airmed_options_carousel" class='col-sm-9 col-form-label form-check-label'>Use Image Carousel in Modal:</label>
                                <div class='col-sm-3'>
                                  <div class="form-check">
                                    <input class="regular-text" type="checkbox" id="airmed_options_carousel" name="airmed_options_carousel" <?php echo $carousel_ischecked ?>/>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>

                        <div class="col-md-6">  <? //Cart and Order ?>
                          <div class='card airmed-card'>
                            <div class='card-body'>

                              <div class="row">
                                <label for="airmed_options_img_show_cart" class='col-sm-9 col-form-label form-check-label'>Show Image in Cart:</label>
                                <div class='col-sm-3'>
                                  <div class="form-check">
                                    <input class="regular-text" type="checkbox" id="airmed_options_img_show_cart" name="airmed_options_img_show_cart" <?php echo $img_show_cart_ischecked ?>/>
                                  </div>
                                </div>
                                <label for="airmed_options_img_show_order" class='col-sm-9 col-form-label form-check-label'>Show Image in Orders:</label>
                                <div class='col-sm-3'>
                                  <div class="form-check">
                                    <input class="regular-text" type="checkbox" id="airmed_options_img_show_order" name="airmed_options_img_show_order" <?php echo $img_show_order_ischecked ?>/>
                                  </div>
                                </div>
                              </div>

                            </div>
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-3">  <? // Payment message ?>
                <div class="col-md-6">
                  <div class='card airmed-card'>
                    <div class='card-header'>Payment Success Message</div>
                    <div class='card-body'>

                <?
                $default_content='<p>Thank you for your purchase.</p><p>We will be putting your order together very soon.</p>';
                $default_content.='<p>A tracking number will be sent to your email address when your order is shipped.</p>';
                $default_content = get_option( 'airmed_options_payment_success_msg',$default_content);
                $editor_id = 'airmed_options_payment_success_msg';
                $arg =array(
                  'media_buttons' => false,
                  'textarea_rows' => 8,
                  'quicktags' => true,
                  'wpautop' => false,
                  //'editor_css' => ''
                  'teeny' => true); 

                wp_editor( $default_content, $editor_id,$arg );
                ?>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row mb-3">
                <div class='col-12'>
                  <?php submit_button(); ?>
                </div>
              </div>
            </form>

          </div>
        </div>
      </div>
      <?php
        }
      }  //End of plugin settings page

      //Create plugin settings theme page
      if( !function_exists("airmed_options_theme_page") ) { 
        function airmed_options_theme_page(){ 
      ?>
      <div id="airmed-wrapper" class='airmed-wrapper airmed-themes'>
      <div class="airmed-themes">
      <h3 class='airmed-admin-title'>AirMed Catalog Themes</h3> 
      <div class='card airmed-card'>
        <div class='card-body'>
          <form method="post" action="options.php">
      <?php 
          settings_fields( 'airmed-options-themes' );
          do_settings_sections( 'airmed-options-themes' ); 
      ?>
            <div class="row mb-3" data-option="nav">
              <label for="airmed_options_theme_nav" class='col-sm-4 col-lg-2 col-form-label'>Catalog Navigation Theme:</label>
              <?php $theme_nav = get_option( 'airmed_options_theme_nav',1 ); ?>
              <div class='col-sm-8 col-lg-4'>
                <select id='airmed_options_theme_nav' name="airmed_options_theme_nav" class="form-control">
                  <option value="1" <?php if($theme_nav == 1){echo "selected";} ?> >Nav Theme 1</option>
                  <option value="2" <?php if($theme_nav == 2){echo "selected";} ?> >Nav Theme 2</option>
                  <option value="3" <?php if($theme_nav == 3){echo "selected";} ?> >Nav Theme 3</option>
                </select>
                <div class='alert alert-danger airmed-theme-alert'>** Nav Selection Theme 3 <strong>MUST</strong> be used with Filter Theme 1 and 2 only **</div>
              </div>
              <div class='col-lg-6 col-sm-12 col-12 image-cell'>
                <div class="img-container">
                  <div class="airmed-arrow airmed-arrow-left"></div>
                  <img class="image mx-auto" data-image="1" src="<?php echo plugins_url('/images/nav1.png',__FILE__); ?>" />
                  <img class="image mx-auto" data-image="2" src="<?php echo plugins_url('/images/nav2.png',__FILE__); ?>" />
                  <img class="image mx-auto" data-image="3" src="<?php echo plugins_url('/images/nav3.png',__FILE__); ?>" />
                </div>
              </div>
           </div>
            <div class="row mb-3" data-option="filter">
              <label for="airmed_options_theme_filter" class='col-sm-4 col-lg-2 col-form-label'>Catalog Filter Theme:</label>
              <?php $theme_filter = get_option( 'airmed_options_theme_filter',1 ); ?>
              <div class='col-sm-8 col-lg-4'>
                <select id='airmed_options_theme_filter' name="airmed_options_theme_filter" class="form-control" >
                  <option value="1" <?php if($theme_filter == 1){echo "selected";} ?> >Filter Theme 1</option>
                  <option value="2" <?php if($theme_filter == 2){echo "selected";} ?> >Filter Theme 2</option>
                  <option value="3" <?php if($theme_filter == 3){echo "selected";} ?> >Filter Theme 3</option>
                </select>
              </div>
              <div class='col-lg-6 col-sm-12 col-12 image-cell'>
                <div class="img-container">
                  <div class="airmed-arrow airmed-arrow-left"></div>
                  <img class="image mx-auto" data-image="1" src="<?php echo plugins_url('/images/filter1.png',__FILE__); ?>" />
                  <img class="image mx-auto" data-image="2" src="<?php echo plugins_url('/images/filter2.png',__FILE__); ?>" />
                  <img class="image mx-auto" data-image="3" src="<?php echo plugins_url('/images/filter3.png',__FILE__); ?>" />
                </div>
              </div>
            </div>
            <div class="row mb-3" data-option="catalog">
              <label for="airmed_options_theme_catalog" class='col-sm-4 col-lg-2 col-form-label'>Catalog Theme:</label>
              <?php $theme_catalog = get_option( 'airmed_options_theme_catalog',1 ); ?>
              <div class='col-sm-8 col-lg-4'>
                <select id='airmed_options_theme_catalog' name="airmed_options_theme_catalog" class="form-control" >
                  <option value="1" <?php if($theme_catalog == 1){echo "selected";} ?> >Catalog Theme 1</option>
                  <option value="2" <?php if($theme_catalog == 2){echo "selected";} ?> >Catalog Theme 2</option>
                  <option value="3" <?php if($theme_catalog == 3){echo "selected";} ?> >Catalog Theme 3</option>
                </select>
              </div>
              <div class='col-lg-6 col-sm-12 col-12 image-cell'>
                <div class="img-container">
                  <div class="airmed-arrow airmed-arrow-left"></div>
                  <img class="image mx-auto" data-image="1" src="<?php echo plugins_url('/images/catalog1.png',__FILE__); ?>" />
                  <img class="image mx-auto" data-image="2" src="<?php echo plugins_url('/images/catalog2.png',__FILE__); ?>" />
                  <img class="image mx-auto" data-image="3" src="<?php echo plugins_url('/images/catalog3.png',__FILE__); ?>" />
                </div>
            </div>
            </div>
            <div class="row mb-3" data-option="modal">
              <label for="airmed_options_theme_modal" class='col-sm-4 col-lg-2 col-form-label'>Modal Theme:</label>
              <?php $theme_modal = get_option( 'airmed_options_theme_modal',1 ); ?>
              <div class='col-sm-8 col-lg-4'>
                <select id='airmed_options_theme_modal' name="airmed_options_theme_modal" class="form-control" >
                  <option value="1" <?php if($theme_modal == 1){echo "selected";} ?> >Modal Theme 1</option>
                  <option value="2" <?php if($theme_modal == 2){echo "selected";} ?> >Modal Theme 2</option>
                  <!-- <option value="3" <?php if($theme_modal == 3){echo "selected";} ?> >Theme 3</option> -->
                </select>
              </div>
              <div class='col-lg-6 col-sm-12 col-12 image-cell'>
                <div class="img-container">
                  <div class="airmed-arrow airmed-arrow-left"></div>
                  <img class="image mx-auto" data-image="1" src="<?php echo plugins_url('/images/modal1.png',__FILE__); ?>" />
                  <img class="image mx-auto" data-image="2" src="<?php echo plugins_url('/images/modal2.png',__FILE__); ?>" />
                  <img class="image mx-auto" data-image="3" src="<?php echo plugins_url('/images/modal3.png',__FILE__); ?>" />
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class='col-12'>
           <?php submit_button(); ?>
              </div>
            </div>
          </form>
        </div>
      </div>
      </div>
      </div>
      <?php
        }
      }  //End of plugin options themes page
    }

  }
  //Trigger the plugin
  AirMed::get_instance();

}

/*
 * Plugin Updater 
 * Modified by Mike Uniat (based on Misha Rudrastyh)
 * URI - https://rudrastyh.com
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
      $this->version = '0.0.17';
      $this->cache_key = 'airmed_custom_upd';
      $this->cache_allowed = false;

      add_filter( 'plugins_api', array( $this, 'info' ), 20, 3 );
      add_filter( 'site_transient_update_plugins', array( $this, 'update' ) );
      add_action( 'upgrader_process_complete', array( $this, 'purge' ), 10, 2 );
      //aLog("checker added");
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
          //'http://localhost:81/wordpress/wp-content/api-updates/info.json',
          'http://airmeddemo.com/api-updates/info.json',
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
          //aLog("updater request wp_error");
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
      //aLog("update checking info");
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
      //$res->author_profile = $remote->author_profile;
      $res->download_link = $remote->download_url;
      $res->trunk = $remote->download_url;
      $res->requires_php = $remote->requires_php;
      $res->last_updated = $remote->last_updated;
      $res->homepage = $remote->homepage;
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
      //aLog("updating");
      if ( empty($transient->checked ) ) {
        return $transient;
      }
      //aLog($transient);
      $remote = $this->request();
      //aLog("Request Returned:");
      //write_log($remote);
      //aLog($remote);
      //aLog($this->version." -- ".$remote->version);
      //aLog($remote->requires." -- ".get_bloginfo( 'version' ));  //Wordpress version
      //aLog($remote->requires_php." -- ".PHP_VERSION);            //PHP Version
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
        //aLog($res);
        $res->homepage = $remote->homepage;
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
        //aLog("cleaning cached after update installed");
        delete_transient( $this->cache_key );
      }

    }

  }

  new airmedUpdateChecker();

}


/**
 * Run Airmed thingy
 *
 * @return object
 */
//if ( ! function_exists( 'airmed' ) ) {
  /**
   * Initialize Airmed
   */
  //function airmed() {
  //  return AirMed::get_instance();
  //}
//}

/*
 * Register hooks that are fired when the plugin is activated, deactivated, or uninstalled.
 */
function airmed_activation() {
  require_once dirname( __FILE__ ) . '/includes/activate.php';
  require_once dirname( __FILE__ ) . '/includes/functions.php';
  AM_Activate::get_instance();
}
register_activation_hook( __FILE__, 'airmed_activation' );

function airmed_deactivate() {
  airmed_remove_pages();
}
register_deactivation_hook( __FILE__, 'airmed_deactivate' );

function airmed_uninstall(){
  // remove option settings
}
register_uninstall_hook( __FILE__, 'airmed_uninstall' );

function aLog($m){
  error_log(print_r($m,true),3,"C:/Airmed/Logging/errors.log");
  error_log("\n",3,"C:/Airmed/Logging/errors.log");
}

?>