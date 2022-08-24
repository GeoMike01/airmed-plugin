<?php
/**
 * Create base page for AirMed.
 *
 * This function is called in plugin activation. This function checks if base page already exists,
 * if not then it create a new one and update the option.
 *
 * @see anspress_activate
 * @since 2.3
 * @since 4.1.0 Creates all other AnsPress pages if not exists.
 */
//require wp_normalize_path( plugin_dir_path( __FILE__ ).'../plugin-update-checker/plugin-update-checker.php');
//$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
//  'http://localhost:81/wordpress/wp-content/updates/info.json',
//  __FILE__, //Full path to the main plugin file or functions.php.
//  'airmed'
//);

// *** not used 
function am_curl_get_contents($url){
  $ch = curl_init();

  curl_setopt($ch, CURLOPT_HEADER, 0);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);

  $data = curl_exec($ch);
  curl_close($ch);

  return $data;
}

// *** not used 
function am_imageToBase64($image){
  
    $imageData = base64_encode(file_get_contents($image['tmp_name']));
    aLog("Image: ".$imageData);
    $mime_types = array(
    'pdf' => 'application/pdf',
    'doc' => 'application/msword',
    'odt' => 'application/vnd.oasis.opendocument.text ',
    'docx'	=> 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'gif' => 'image/gif',
    'jpg' => 'image/jpg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'bmp' => 'image/bmp'
    );
    $ext = pathinfo($image['name'], PATHINFO_EXTENSION);
    
    if (array_key_exists($ext, $mime_types)) {
      $a = $mime_types[$ext];
    }
    return 'data:'.$image['type'].';base64,'.$imageData;
}

function airmed_create_pages() {

  //$current_user = wp_get_current_user();
    
  // create page object
  $page = array(
    'post_content'   => '[wp_airmed]',
    'post_title'  => 'AirMed Shop',
    'post_name'  => 'airmed',
    'post_status' => 'publish',
  //  'post_author' => $current_user->ID,
    'post_type'   => 'page',
  );
  // insert the post into the database
  $parentPage = wp_insert_post( $page );
  
  // create page object
  $page = array(
    'post_content'   => '[wp_airmed_login]',
    'post_title'  => 'AirMed Login',
    'post_name'  => 'airmed-login',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );
  
  $page = array(
    'post_content'   => '[wp_airmed_new_account]',
    'post_title'  => 'AirMed New Account',
    'post_name'  => 'airmed-new-account',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );
  
  $page = array(
    'post_content'   => '[wp_airmed_new_application]',
    'post_title'  => 'AirMed New Application',
    'post_name'  => 'airmed-new-application',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_confirm_email]',
    'post_title'  => 'AirMed Confirm Email',
    'post_name'  => 'airmed-confirm-email',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_dashboard]',
    'post_title'  => 'AirMed Dashboard',
    'post_name'  => 'airmed-dashboard',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );
  
  $page = array(
    'post_content'   => '[wp_airmed_patient]',
    'post_title'  => 'AirMed Patient',
    'post_name'  => 'airmed-patient',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_messages]',
    'post_title'  => 'AirMed Messages',
    'post_name'  => 'airmed-messages',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_orders]',
    'post_title'  => 'AirMed Orders',
    'post_name'  => 'airmed-orders',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_order]',
    'post_title'  => 'AirMed Order',
    'post_name'  => 'airmed-order',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_applications]',
    'post_title'  => 'AirMed Applications',
    'post_name'  => 'airmed-applications',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_application]',
    'post_title'  => 'AirMed Application',
    'post_name'  => 'airmed-application',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_cart]',
    'post_title'  => 'AirMed Cart',
    'post_name'  => 'airmed-cart',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );

  $page = array(
    'post_content'   => '[wp_airmed_checkout]',
    'post_title'  => 'AirMed Checkout',
    'post_name'  => 'airmed-checkout',
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_parent'   => $parentPage,
  );
  // insert the post into the database
  $newPage = wp_insert_post( $page );


  
  // store the new page id in options for deactivation
  //update_option('airmed-page',$parentpage);
}

function airmed_remove_pages(){
  // remove parent page last
  $page = get_page_by_path( 'airmed/airmed-checkout', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-cart', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-order', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-application', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-orders', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-applications', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-topmenu', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-patient', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-dashboard', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-messages', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-confirm-email', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-new-application', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-new-account', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed/airmed-login', OBJECT, 'page');
  wp_delete_post($page->ID);
  $page = get_page_by_path( 'airmed', OBJECT, 'page');
  wp_delete_post($page->ID);

}

// create page link based on slug
function airmed_pagelink($slug){
  $page_object = get_page_by_path( $slug );
  $page_id = $page_object->ID;
  return get_permalink( $page_id );
}

//get a GUID
function airmed_GUID()  {
  if (function_exists('com_create_guid') === true) {
    return trim(com_create_guid(), '{}');
  }
  return sprintf('%04X%04X-%04X-%04X-%04X-%04X%04X%04X', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(16384, 20479), mt_rand(32768, 49151), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
}
    
function getAirmedAPIHost(){
  return get_option( 'airmed_options_api_host' );
}
function getAirmedAPIKey(){
  return get_option( 'airmed_options_api_key' );
}
function getAirmedAPIId(){
  return get_option( 'airmed_options_api_id' );
}

// gets url path based on parameters provided
function getAirmedEndpoint($type,$id){
  if (!empty($type)){
    switch($type){
      case "products":
        if (!is_null($id) && !empty($id) ){return "Catalog/GetProduct/$id";}
        return "Catalog/GetAllProducts/";
      case "derivativeProducts":
        if (!is_null($id) && !empty($id) ){return "Catalog/GetDerivativeProduct/$id";}
        return "Catalog/GetAllDerivativeProducts/";
      case "sourceMaterials":
        if (!is_null($id) && !empty($id) ){return "Catalog/GetSourceMaterialProduct/$id";}
        return "Catalog/GetAllSourcerMaterialProducts/";
      case "plants":
        if (!is_null($id) && !empty($id) ){return "Catalog/GetPlantProduct/$id";}
        return "Catalog/GetAllPlantProducts/";
      case "accessories":
        if (!is_null($id) && !empty($id) ){return "Catalog/GetRetailProduct/$id";}
        return "Catalog/GetAllAccessoriesProducts/";
      case "merchandise":
        if (!is_null($id) && !empty($id) ){return "Catalog/GetRetailProduct/$id";}
        return "Catalog/GetAllMerchandiseProducts/";
      case "receivedMessage":
        if (!is_null($id) && !empty($id) ){return "Message/GetReceivedMessage/$id";}
        return "Message/GetAllMessages";
      case "sentMessage":
        if (!is_null($id) && !empty($id) ){return "Message/GetSentMessage/$id";}
        return "Message/GetAllMessages";
      default:
        return "Catalog/GetFullCatalog";
    }
  }
  else { return "";}
}

function airmed_call_request($path,$method,$useBearer,$post = array()){
  $debug = true;
  $apiHost = getAirmedAPIHost();
  $apiKey = getAirmedAPIKey();
  $apiID = getAirmedAPIId();
  $bearer = '';

  $request_url = $apiHost.$path;
  $requestURI = strtolower(rawurlencode($request_url));
  $requestMethod = $method;

  $requestTimeStamp = time();  //microtime() provides micro seconds.  moment().valueOf() out puts milliseconds
  $nonce = airmed_GUID();
  $requestContentBase64String = "";
  
  if($method === "POST"){
    //$post = array('Email'=>'mike.uniat@geotalent.com','Password'=>'12345!Abcde');
    //$post = array('ID'=>'mike.uniat@geotalent.com','Code'=>'12345!Abcde');
    $json_post = json_encode($post);
    aLog("JSON Posted Data:");
    aLog($json_post);
    
    // Hashing the request body, so any change in request body will result in a different hash we will achieve message integrity
    $md5_enc = md5($json_post,true);
    $requestContentBase64String = base64_encode($md5_enc);
    
  }
  // use bearer
  if (!empty($_SESSION['__amAuthToken'])) { 
    $bearer = $_SESSION['__amAuthToken'];
  }
  // use hmac key
  else {
    $signatureRawData = $apiID . $requestMethod . $requestURI . $requestTimeStamp . $nonce . $requestContentBase64String;
    $signature = utf8_encode($signatureRawData);
    $secretByteArray = base64_decode($apiKey);
    $signatureBytes = hash_hmac('SHA256',$signature,$secretByteArray,true);
    $requestSignatureBase64String = base64_encode($signatureBytes);

    // var hmacKey = AppId + ":" + requestSignatureBase64String + ":" + nonce + ":" + requestTimeStamp;
    $hmacKey = "Airmed-HMAC " . $apiID . ":" . $requestSignatureBase64String . ":" . $nonce . ":" . $requestTimeStamp;
    aLog("hmacKey:".$hmacKey);
  }
  
  aLog("Bearer:".$bearer);
  
  if($method == "POST"){
    aLog("POST to:".$path);
    $hArray = !empty($bearer) ? array('Authorization'=>'Bearer '.$bearer) : array('Authorization'=>$hmacKey);
    $hArray += ['Content-Type'=>'application/json'];

    aLog("Posted Data:");
    aLog($post);

    $args = array();

    $args += ['body'=>$json_post];
    $args += ['headers'=>$hArray];
    //$requestArray = wp_remote_post($request_url,array('headers'=>$hArray,'body'=>$json_post));
    $requestArray = wp_remote_post($request_url,$args);

  }
  else {
    aLog("GET:".$path);
    $hArray = !empty($bearer) ? array('Authorization'=>'Bearer '.$bearer) : array('Authorization'=>$hmacKey);
    $requestArray = wp_remote_get($request_url,array('headers'=>$hArray));
  }
  //aLog($requestArray);
  return $requestArray;
}

function airmed_setCookie($name,$value){
  setcookie($name,$value,time()+7200);
}

function airmed_setSession($name,$value){
  $_SESSION[$name] = $value;
}

function airmed_Globals(){
  // clear session variables if on login page
  if(get_post_field('post_name' ) === "airmed-login"){ 
    $_SESSION['__airmed'] = '';
    $_SESSION['__amAuthToken'] = '';
    //aLog("reset");
  }
  // reset object
  if(empty($_SESSION['__airmed'])){
    $airmed = new stdClass();
    $airmed->hasToken = 0;
    $airmed->name = '';
    $airmed->patient = null;
    $airmed->messages = 0;
    $airmed->newMessages = 0;
    $airmed->urgentMessages = 0;
    $airmed->hasOpenOrder = false;
    $airmed->openOrderID = '';
    $airmed->prodLogo = '';
  }
  else{  // resuse object
    $airmed = new stdClass();
    $airmed = $_SESSION['__airmed'];
  }
  //aLog($airmed);
  //aLog($_SESSION['__airmed']);
  // if logged in
  if(!empty($_SESSION['__amAuthToken'])) {

    $airmed->hasToken = true;

    // get patient info if not set
    if(is_null($airmed->patient)){
      $requestPath = '/API/Patient/Get/';
      $requestArray = airmed_call_request($requestPath,'GET',true,null);

      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];
      
      if ($errno === 200) {
        $jsonObj = json_decode($response);
        $airmed->patient = $jsonObj;
        $airmed->name = $jsonObj->fName.' '.$jsonObj->lName;
      }
    }
    
    // get messages
    $requestPath = '/API/Message/GetAllMessages/';
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    $urgentMessages = 0;
    $newMessages = 0;
    $totalMessages = 0;

    // no error proceed with displaying data
    if ($errno === 200) {
      $jsonObj = json_decode($response);
      $received = $jsonObj->received;
      if(!empty($received)){
        //Loop through the API results
        foreach($received as $itemObj) {
          if(!$itemObj->viewed){$newMessages++;}
          if($itemObj->priority === "Urgent"){$urgentMessages++;}
        }
      }
    }

    $airmed->messages = $urgentMessages + $newMessages;;
    $airmed->newMessages = $newMessages;
    $airmed->urgentMessages = $urgentMessages;

    //aLog($airmed);

    // get open order
    $requestPath = '/API/Order/HasOpenOrder/';
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    // no error proceed with displaying data
    if ($errno === 200) {
      $jsonObj = json_decode($response);
      if($jsonObj->hasOpenOrder){
        $airmed->hasOpenOrder = true;
        $airmed->openOrderID = $jsonObj->orderID;
        $airmed->numOfItems = $jsonObj->numOfItems;
      }
      else {
        $airmed->hasOpenOrder = false;
        $airmed->openOrderID = '';
        $airmed->numOfItems = 0;
      }
    }

  }
  $_SESSION['__airmed'] = $airmed;
}

function airmed_global_updatePatient(){
  $airmed = new stdClass();
  $airmed = $_SESSION['__airmed'];

  $requestPath = '/API/Patient/Get/';
  $requestArray = airmed_call_request($requestPath,'GET',true,null);

  $response = $requestArray['body'];
  $err = $requestArray['response']['message'];
  $errno = $requestArray['response']['code'];
  $err_message = $requestArray['response']['message'];
  
  if ($errno === 200) {
    $jsonObj = json_decode($response);
    $airmed->patient = $jsonObj;
    $airmed->name = $jsonObj->fName.' '.$jsonObj->lName;
  }
}

function getAirMedHoverImage($i,$obj){
  switch($i){
    case "Brand":
      return $obj->imgBrand;
    case "Strain":
      return $obj->imgStrain;
    case "Product":
      return $obj->imgProduct;
  }
}

// adds the modal wrapper to the page which is then moved by js to the body element
function includeModals($embed = false){
  
  $slug = get_post_field( 'post_name' );
  //$slideout = get_option( 'airmed_options_login_type',1 );
  if($slug === "airmed-login"){
    $_SESSION['__amAuthToken'] = '';
  }
  $pageHTML = "<div id='airmed-modals' class='airmed-wrapper' aria-hidden='true'>";
  
  $pageHTML.= airmed_modal_error();
  
  // not logged in
  if(empty($_SESSION['__amAuthToken'])){
    $pageHTML.= airmed_modal_login();
  }
  else { // logged in
    $amAccount = new stdClass();
    $amAccount = $_SESSION['__airmed'];

    $pageHTML.= airmed_cart_slideout();
    $pageHTML.= airmed_account_slideout($amAccount);
  }
  $pageHTML.="</div>";
  if($embed){
    echo $pageHTML;
  }
  else{
    return $pageHTML;
  }
}

// Error Modal
function airmed_modal_error(){
  $pageHTML= "  <div id='airmed-modal-error' class='modal fade airmed-modal' tabindex='-1' role='dialog' aria-labelledby='airmed-modal-error-label' aria-hidden='true'>";
  $pageHTML.= "    <div class='modal-dialog'>";
  $pageHTML.= "        <div class='modal-content'>";
  $pageHTML.= "            <div class='modal-header'>";
  //$pageHTML.= "                <button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
  //$pageHTML.= "                    <span aria-hidden='false'>&times;</span>";
  //$pageHTML.= "                </button>";
  $pageHTML.= "          <div class='modal-title'>";
  $pageHTML.= "            <h1 id='airmed-modal-error-label' class='name'></h1>";
  $pageHTML.= "          </div>";
  $pageHTML.= "          <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'></button>";
  $pageHTML.= "            </div>";
  $pageHTML.= "            <div class='modal-body'>";
  $pageHTML.= "                <div class='row'>";
  $pageHTML.= "                    <div class='col-sm-12'>";
  $pageHTML.= "                        <section>";
  $pageHTML.= "                            <div class='card card-primary'>";
  //$pageHTML.= "                                <div class='card-header'>Payment Processing Message</div>";
  $pageHTML.= "                                <div class='card-body' style='white-space: pre-line'>";

  $pageHTML.= "                                </div>";
  //$pageHTML.= "                                <div class='card-footer clearfix'></div>";
  $pageHTML.= "                            </div>";
  $pageHTML.= "                        </section>";
  $pageHTML.= "                    </div>";
  $pageHTML.= "                </div>";
  $pageHTML.= "            </div>";
  $pageHTML.= "            <div class='modal-footer'>";
  $pageHTML.= "                <button type='button' class='btn btn-sm btn-default' data-am-dismiss='modal'>Close</button>";
  $pageHTML.= "            </div>";
  $pageHTML.= "        </div><!-- /.modal-content -->";
  $pageHTML.= "    </div><!-- /.modal-dialog -->";
  $pageHTML.= "  </div><!-- /.modal -->";
  return $pageHTML;
}

// Loading Modal layout
function airmed_modal_load(){
  // Loading modal part
  $pageHTML= "    <div class='modal-content loading-content'>";
  $pageHTML.= "      <div class='modal-header'>";
  $pageHTML.= "        <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'>";
  //$pageHTML.= "          <span aria-hidden='false'>&times;</span>";
  $pageHTML.= "        </button>";
  $pageHTML.= "      </div>";
  $pageHTML.= "      <div class='modal-body'>";
  $pageHTML.= "        <div class='modal-loading text-center'><h3>Processing ...</h3>";
  $pageHTML.= "          <br>";
  $pageHTML.= "          <img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif' id='airmed-modal-loading-indicator'>";
  $pageHTML.= "        </div>";
  $pageHTML.= "     </div>";
  $pageHTML.= "     <div class='modal-footer'>";
  $pageHTML.= "       <button type='button' class='btn btn-sm btn-default' data-dismiss='modal'>Close</button>";
  $pageHTML.= "     </div>";
  $pageHTML.= "    </div>";
  return $pageHTML;
}

// Login Modal
// Application/Signup Steps modal
function airmed_modal_login(){

  $slideout = get_option( 'airmed_options_login_type',1 );
  $slideout_css = "";
  $slideout_col = "col-8";
  if($slideout == 3 ){
    $slideout_css = "modal-dialog-slideout modal-sm";
    $slideout_col = "col-12";
  }

  //$pageHTML = "<div id='airmed-modals' class='airmed-wrapper'>";
  $pageHTML = "  <div id='airmed-modal-login' class='modal fade airmed-modal' role='dialog' aria-hidden='true'>";
  $pageHTML.= "    <div class='modal-dialog modal-dialog-centered $slideout_css' role='document'>";
  $pageHTML.= "      <div class='modal-content item-content'>";

  $pageHTML.= "        <div class='am-loading d-flex justify-content-center hide'>";
  $pageHTML.= "          <div>Processing...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif' id='airmed-modal-loading-indicator'>";
  $pageHTML.="         </div>";
  
  $pageHTML.= "        <div class='modal-header'>";
  $pageHTML.= "          <div class='modal-title'>";
  $pageHTML.= "            <h1 class='name'>Sign In</h1>";
  $pageHTML.= "          </div>";
  $pageHTML.= "          <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'>";
  $pageHTML.= "          </button>";
  $pageHTML.= "        </div>";
 
  $pageHTML.= "        <div class='modal-body'>";
  $pageHTML.= "          <div class='container-fluid'>";
  $pageHTML.= "          <div class='row justify-content-center'>";
  $pageHTML.= "            <div class='$slideout_col'>";
  $pageHTML.="               <form id='am-login-modal-form' class='am-login-modal-form form-horizontal' method='post' role='form'>";
  $pageHTML.="                 <div class='hide alert alert-danger p-2'>The username or password are incorrect. Please try again.</div>";
  $pageHTML.="                 <div class='am-login-modal-form-content'>";
  $pageHTML.="                    <div class='mb-3 mt-3'>";
  $pageHTML.="                      <input id='amModalUsername' name='amUsername' class='form-control' placeholder='Enter username' type='text' required />";
  $pageHTML.="                    </div>";
  $pageHTML.="                   <div class='mb-3'>";
  $pageHTML.="                     <input id='amModalPassword' name='amPassword' type='password' class='form-control' placeholder='Password' required />";
  $pageHTML.="                   </div>";
  $pageHTML.="                   <div class='mb-3 form-check'>";
  $pageHTML.="                     <input id='amModalShowPassword' type='checkbox' class='form-check-input'/>";
  $pageHTML.="                     <label class='form-check-label' for='amModalShowPassword'>Show Password</label>";
  $pageHTML.="                   </div>";
  $pageHTML.="                   <input type='hidden' name='action' value='am_login_form' />";
  //$pageHTML.="                   <input type='hidden' name='query' value='".$_SERVER['QUERY_STRING']."' />";
  $pageHTML.="                   <div class='mb-3 d-flex justify-content-center'>";
  $pageHTML.="                     <button id='airmed-modal-login-submit' type='submit' class='btn btn-primary'>Sign In</button>";
  $pageHTML.="                   </div>";
  $pageHTML.="                   <div class='mb-5 d-flex justify-content-center'>";
  $pageHTML.="                     <a type='button' class='btn btn-outline-dark' href='".airmed_pagelink('/airmed/airmed-new-account')."'>Sign Up</a>";
  $pageHTML.="                   </div>";
  $pageHTML.="                   <div class='mb-1 '>";
  $pageHTML.="                     <a class='' href='".airmed_pagelink('/airmed/airmed-login')."'>Forgot Password?</a>";
  $pageHTML.="                   </div>";
  $pageHTML.="                 </div>";
  $pageHTML.="               </form>";
  
  $pageHTML.= "            </div>";
  $pageHTML.= "           </div>";
  $pageHTML.= "           </div>";
  $pageHTML.= "        </div>";
  
  $pageHTML.= "      </div>";  // end of model-content
  $pageHTML.= "    </div>";  // end of model-dialog
  $pageHTML.= "  </div>";  // end of login modal
  //$pageHTML.= "</div>";  // end of modals
  return $pageHTML;
}

// Message Modal
function airmed_modal_message(){
      //$pageHTML = "<div id='airmed-modals' class='airmed-wrapper'>";
      $pageHTML = "<div id='airmed-modal-message' class='modal airmed-modal airmed-info fade' role='dialog' aria-hidden='true'>";
      $pageHTML.= "  <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
      $pageHTML.= airmed_modal_load();
      $pageHTML.= "    <div class='modal-content item-content hide'>";
      
      $pageHTML.= "      <div class='modal-header'>";
      $pageHTML.= "        <div class='modal-title'>";
      $pageHTML.= "          <h4 class='name'>[subject]</h4>";
      $pageHTML.= "        </div>";
      $pageHTML.= "        <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'>";
      //$pageHTML.= "          <span aria-hidden='true'>&times;</span>";
      $pageHTML.= "        </button>";
      $pageHTML.= "      </div>";
      
      //$logo = empty(get_option( 'airmed_options_logo')) ? plugins_url('../images/default-logo.png',__FILE__) : plugins_url('../images/'.get_option( 'airmed_options_logo' ),__FILE__);
      $pageHTML.= "      <div class='modal-body'>";
      $pageHTML.= "        <div class='row'>";
      $pageHTML.= "          <div class='col-sm-12'>";
      
      // start of details
      $pageHTML.= "            <section id='airmed-msgDetails'>";
      $pageHTML.= "              <div class='card card-primary'>";
      $pageHTML.= "                <div class='card-header'>Message Details</div>";
      $pageHTML.= "                <div class='card-body'>";
      $pageHTML.= "                  <div class='row'>";
      $pageHTML.= "                    <div class='col-sm-6'>";
      $pageHTML.= "                      <div class='card card-body'>";
      $pageHTML.= "                        <address>";
      $pageHTML.= "                          <img  class='prod-logo'src='' alt='logo'><br>";
      $pageHTML.= "                          <strong>";
      $pageHTML.= "                           <span class='prod-name'>[ABC Medicinals Inc.]</span>";
      $pageHTML.= "                          </strong><br><br>";
      $pageHTML.= "                          <label for='ProducerEmail'>Email:</label><span class='prod-email'>[info@airmed.ca]</span><br>";
      $pageHTML.= "                          <label for='ProducerPhone1'>Phone 1:</label> <span class='prod-phone'>[(800) 555-1234]</span><br>";
      $pageHTML.= "                        </address>";
      $pageHTML.= "                      </div>";
      $pageHTML.= "                    </div>";
      $pageHTML.= "                    <div class='col-sm-6'>";
      $pageHTML.= "                      <div class='card card-body'>";
      $pageHTML.= "                        <address>";
      $pageHTML.= "                          <div class='msg-priority'><label for='Priority'>Priority:</label> <span>[Low]</span></div>";
      $pageHTML.= "                          <div class='msg-sender'><label for='SenderName'>Sender:</label> <span>[Support Test]</span></div>";
      $pageHTML.= "                          <div class='msg-senderEmail'><label for='SenderEmail'>Sender Email:</label> <span>[support@airmed.ca]</span></div>";
      $pageHTML.= "                          <div class='msg-recipient'><label for='RecipientName'>Recipient:</label> <span>[Support Test]</span></div>";
      $pageHTML.= "                          <div class='msg-recipientEmail'><label for='RecipientEmail'>Recipient Email:</label> <span>[support@airmed.ca]</span></div>";
      $pageHTML.= "                          <div class='msg-type'><label for='MessageType'>Type of Message:</label> <span>[Application Event]</span></div>";
      $pageHTML.= "                          <div id='airmed-viewedState'><label for='Viewed'>Viewed:</label> <span id='airmed-isViewed'>[No]</span></div>";
      $pageHTML.= "                        </address>";
      $pageHTML.= "                      </div>";
      $pageHTML.= "                    </div>";
      $pageHTML.= "                  </div>";
      $pageHTML.= "                  <div class='row'>";
      $pageHTML.= "                    <div class='col-sm-12'>";
      $pageHTML.= "                      <div class='card card-default height'>";
      $pageHTML.= "                        <div class='card-header'><strong>Message</strong></div>";
      $pageHTML.= "                        <div class='card-body'>";
      $pageHTML.= "                          <div style='white-space: pre-wrap' class='msg-details'>[Your application has been approved. You can now place orders for selected products.]</div>";
      $pageHTML.= "                        </div>";
      $pageHTML.= "                      </div>";
      $pageHTML.= "                    </div>";
      $pageHTML.= "                  </div>";
      $pageHTML.= "                </div>";
      $pageHTML.= "                <div class='card-footer clearfix'>";
      $pageHTML.= "                  <div class='form-actions pull-right'>";
      $pageHTML.= "                    <button type='submit' id='airmed-markReadBtn' name='markReadBtn' class='btn btn-success btn-sm'>";
      $pageHTML.= "                      <i class='fa fa-check-circle'></i>";
      $pageHTML.= "                      <span>Mark As Read</span>";
      $pageHTML.= "                    </button>";
      $pageHTML.= "                    <button type='submit' id='airmed-markUnreadBtn' name='markUnreadBtn' class='btn btn-warning btn-sm' style='display: none;'>";
      $pageHTML.= "                      <i class='fa fa-times-circle'></i>";
      $pageHTML.= "                      <span>Mark As Unread</span>";
      $pageHTML.= "                    </button>";
      $pageHTML.= "                    <button type='button' id='airmed-replyBtn' name='replyBtn' class='btn btn-info btn-sm'>";
      $pageHTML.= "                      <i class='fa fa-reply'></i>";
      $pageHTML.= "                      <span>Reply</span>";
      $pageHTML.= "                    </button>";
      $pageHTML.= "                    <button type='button' id='airmed-followUpBtn' name='followUpBtn' class='btn btn-info btn-sm'>";
      $pageHTML.= "                      <i class='fa fa-reply'></i>";
      $pageHTML.= "                      <span>Follow Up</span>";
      $pageHTML.= "                    </button>";
      $pageHTML.= "                  </div>";
      $pageHTML.= "                </div>";
      $pageHTML.= "              </div>";
      $pageHTML.= "            </section>";  // end of details
      
      // start of reply
      $pageHTML.= "            <section id='airmed-msgReply' class='hide'>";
      $pageHTML.= "              <form class='form-horizontal' id='airmed-replyForm' method='post' role='form' novalidate='novalidate'>";
      $pageHTML.= "                <div class='card card-primary'>";
      $pageHTML.= "                  <div class='card-header'>Message Reply</div>";
      $pageHTML.= "                  <div class='card-body'>";
      $pageHTML.= "                    <div id='fieldwrapper'>";
      $pageHTML.= "                      <fieldset class='step' id='airmed-first'>";
      $pageHTML.= "                        <div class='form-group form-row'>";
      $pageHTML.= "                          <label class='col-sm-2 control-label form-control-sm' for='RecipientName'>Recipient:</label>";
      $pageHTML.= "                          <div class='col-sm-9 form-control-sm msg-sender'><span>[Airmed Support]</span></div>";
      $pageHTML.= "                          <div class='col-sm-9 form-control-sm msg-recipient'><span>[Airmed Support]</span></div>";
      $pageHTML.= "                          <input name='RecipientName' type='hidden' value='[Airmed Support]'>";
      $pageHTML.= "                        </div>";
      $pageHTML.= "                        <div class='form-group form-row'>";
      $pageHTML.= "                          <label class='col-sm-2 control-label form-control-sm input-required' for='airmed-Subject'>Subject:</label>";
      $pageHTML.= "                          <div class='col-sm-9'>";
      $pageHTML.= "                            <input class='form-control form-control-sm text-box single-line' id='airmed-SubjectInput' name='Subject' type='text' required='' value='[RE: Test Complaint Message]'>";
      $pageHTML.= "                            <span class='field-validation-valid' data-valmsg-for='airmed-SubjectInput' data-valmsg-replace='true'></span>";
      $pageHTML.= "                          </div>";
      $pageHTML.= "                        </div>";
      $pageHTML.= "                        <div class='form-group form-row'>";
      $pageHTML.= "                          <label class='col-sm-2 control-label form-control-sm input-required' for='airmed-Priority'>Priority:</label>";
      $pageHTML.= "                          <div class='col-sm-3'>";
      $pageHTML.= "                            <select class='form-control form-control-sm residence' id='airmed-Priority' name='Priority'>";
      $pageHTML.= "                              <option value='1'>Low</option>";
      $pageHTML.= "                              <option value='2'>Medium</option>";
      $pageHTML.= "                              <option value='3'>High</option>";
      $pageHTML.= "                              <option value='4'>Urgent</option>";
      $pageHTML.= "                            </select>";
      //$pageHTML.= "                            <span class='field-validation-valid' data-valmsg-for='airmed-Priority' data-valmsg-replace='true'></span>";
      $pageHTML.= "                          </div>";
      $pageHTML.= "                        </div>";
      $pageHTML.= "                        <div class='form-group form-row'>";
      $pageHTML.= "                          <div class='col-sm-12'>";
      $pageHTML.= "                            <label class='control-label form-control-sm input-required' for='airmed-DetailsText'>Message:</label>";
      $pageHTML.= "                            <textarea class='form-control form-control-sm'  required='' cols='10' id='airmed-DetailsText' name='Details' rows='4'></textarea>";
      //$pageHTML.= "                            <span class='field-validation-valid text-danger' data-valmsg-for='Details' data-valmsg-replace='true'></span>";
      $pageHTML.= "                          </div>";
      $pageHTML.= "                        </div>";
      $pageHTML.= "                      </fieldset>";
      $pageHTML.= "                    </div>";
      $pageHTML.= "                  </div>";
      $pageHTML.= "                  <div class='card-footer clearfix'>";
      $pageHTML.= "                    <span class='text-red'>* indicates required field</span>";
      $pageHTML.= "                    <div class='form-actions pull-right'>";
      $pageHTML.= "                      <button class='navigation_button btn btn-sm btn-primary' id='airmed-reply-submit' data-reply='true' >Submit</button>";
      $pageHTML.= "                      <button class='navigation_button btn btn-sm btn-primary' id='airmed-followup-submit'  data-reply='false'>Submit</button>";
      $pageHTML.= "                    </div>";
      $pageHTML.= "                  </div>";
      $pageHTML.= "                </div>";
      $pageHTML.= "                <input id='airmed-messageID' name='ID' type='hidden' value='[MSGS2021120915110710000017]'>";
      //$pageHTML.= "                <input id='airmed-TicketID' name='TicketID' type='hidden' value='[COMM2021120915095710000013]'>";
      $pageHTML.= "                <input name='action' type='hidden' value='airmed_message_reply'>";
      $pageHTML.= "              </form>";
      $pageHTML.= "            </section>";  // end of reply


      $pageHTML.= "            <section id='airmed-msgError' class='hide'>";
      $pageHTML.= "            </section>";  // end of msgError

      $pageHTML.= "            <section id='airmed-msgSuccess' class='hide'>";
      $pageHTML.= "             <div class='card card-primary'>";
      $pageHTML.= "               <div class='card-header'>Message Reply</div>";
      $pageHTML.= "                 <div class='card-body'>";
      $pageHTML.= "                   <div class='my-3'>";
      $pageHTML.= "                     Your reply has been successfully sent.  All sent messages can be viewed under the &quot;Sent Items&quot; tab.";
      $pageHTML.= "                   </div>";
      $pageHTML.= "                 </div>";
      $pageHTML.= "              </div>";
      $pageHTML.= "            </section>";  // end of msgSuccess

      $pageHTML.= "          </div>";
      $pageHTML.= "        </div>";
      $pageHTML.= "      </div>";
      $pageHTML.= "      <input id='airmed-message-viewed' name='Viewed' type='hidden' value=''>";
      $pageHTML.= "      <input id='airmed-message-id' name='ID' type='hidden' value=''>";
      $pageHTML.= "    </div>";
      $pageHTML.= "  </div>";
      $pageHTML.= "</div>";  // end of message modal
      //$pageHTML.= "</div>";  // end of modals
      return $pageHTML;
}

// Application/Signup Steps modal
function airmed_modal_application_steps($jsonObj,$step){
  $applicationurl = "";
  $medicalurl = "";
  $applicationurl = $jsonObj->applicationFormsLink;
  $medicalurl = $jsonObj->medicalDocumentLink;

  $mailAddress = "";
  if (!empty($jsonObj->mailAddress->suite)){$mailAddress.= "#".$jsonObj->mailAddress->suite." - ";}
  if (!empty($jsonObj->mailAddress->streetNumber)){$mailAddress.= $jsonObj->mailAddress->streetNumber." ";}
  if (!empty($jsonObj->mailAddress->streetName)){$mailAddress.= $jsonObj->mailAddress->streetName;}
  if (!empty($jsonObj->mailAddress->floor)){$mailAddress.= ", Flr ".$jsonObj->mailAddress->floor;}
  $mailAddress.= ", ".$jsonObj->mailAddress->city;
  $mailAddress.= ", ".$jsonObj->mailAddress->province;
  if (!empty($jsonObj->mailAddress->country)){$mailAddress.= ", ".$jsonObj->mailAddress->country;}
  $mailAddress.= " ".$jsonObj->mailAddress->postalCode;


  //$pageHTML = "<div id='airmed-modals' class='airmed-wrapper'>";
  $pageHTML = "<div id='airmed-modal-application-steps' class='modal airmed-modal fade' role='dialog' aria-hidden='true'>";
  $pageHTML.= "  <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
  $pageHTML.= "    <div class='modal-content item-content'>";
  
  $pageHTML.= "      <div class='modal-header'>";
  $pageHTML.= "        <div class='modal-title'>";
  $pageHTML.= "          <h1 class='name'>Application Steps</h1>";
  $pageHTML.= "        </div>";
  $pageHTML.= "        <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'>";
  $pageHTML.= "        </button>";
  $pageHTML.= "      </div>";
 
  $pageHTML.= "      <div class='modal-body rw-account'>";
  $pageHTML.= "        <div class='row'>";
  $pageHTML.= "          <div class='col-12'>";
  
  // start of details
  $pageHTML.="            <div class='rw-account-info-content'>";
  $pageHTML.="              <div class='card'>";
  $pageHTML.="              <div class='card-body'>";
  $pageHTML.="              <h3>Sign Up Process</h3>";
  $pageHTML.="                <div class='signup-process'>";
  $pageHTML.="                  <div class='row mar-b-40'>";
  $pageHTML.="                    <div class='col-xl-3 col-lg-4 steps'>";
  $pageHTML.="                      <h4>Step 1</h4>";
  if($step>1){
    $pageHTML.="                      <div class='step-progress'>";
    $pageHTML.="                        <i class='dashicons dashicons-yes-alt fs-3x text-green'></i><br>";
    $pageHTML.="                        <span class='text-green'>Completed</span>";
    $pageHTML.="                      </div>";
  }
  $pageHTML.="                    </div>";
  $pageHTML.="                    <div class='col-xl-9 col-lg-8 process-text'>";
  $pageHTML.="                      <h5>Sign up with $jsonObj->name</h5>";
  $pageHTML.="                      <p>To create an account simply fill out your information on this page. An account will allow you to view full product details.</p>";
  $pageHTML.="                      <p class='notes'>*By creating an account you are under NO obligation to complete the registration process with $jsonObj->name.</p>";
  $pageHTML.="                    </div>";
  $pageHTML.="                  </div>";
  $pageHTML.="                  <div class='row mar-b-40'>";
  $pageHTML.="                    <div class='col-xl-3 col-lg-4 steps'>";
  $pageHTML.="                      <h4>Step 2</h4>";
  if($step>2){
    $pageHTML.="                      <div class='step-progress'>";
    $pageHTML.="                        <i class='dashicons dashicons-yes-alt fs-3x text-green'></i><br>";
    $pageHTML.="                        <span class='text-green'>Completed</span>";
    $pageHTML.="                      </div>";
  }
  $pageHTML.="                    </div>";
  $pageHTML.="                    <div class='col-xl-9 col-lg-8 process-text'>";
  $pageHTML.="                      <h5>Complete Application Process</h5>";
  $pageHTML.="                      <p>Once you have registered and logged into the site, you can complete your application by filling out a simple online form which will accompany your medical document.</p>";
  $pageHTML.="                      <p>PDF copies of our forms can be downloaded <a href='$applicationurl'>here</a>. Please send paper forms to us at $mailAddress</p>";
  $pageHTML.="                      <p class='notes'>*Please use courier or mail via Canada Post.</p>";
  $pageHTML.="                    </div>";
  $pageHTML.="                  </div>";
  $pageHTML.="                  <div class='row mar-b-40'>";
  $pageHTML.="                    <div class='col-xl-3 col-lg-4 steps'>";
  $pageHTML.="                      <h4>Step 3</h4>";
  $pageHTML.="                    </div>";
  $pageHTML.="                    <div class='col-xl-9 col-lg-8 process-text'>";
  $pageHTML.="                      <h5>Get Authorization</h4>";
  $pageHTML.="                      <p>Download the Medical Document <a href='$medicalurl'>HERE</a>. This document must be signed by your health care practitioner.</p>";
  $pageHTML.="                      <p>Have your practitioner send us your medical document via Secure fax: </p>";
  $pageHTML.="                      <p><strong>OR</strong></p>";
  $pageHTML.="                      <p>Send us the original by mail: <br>$mailAddress</p>";
  $pageHTML.="                      <p class='notes'>*Please use courier or mail via Canada Post.</p>";
  $pageHTML.="                    </div>";
  $pageHTML.="                  </div>";
  $pageHTML.="                </div>";
  $pageHTML.="              </div>";
  $pageHTML.="              </div>";
  $pageHTML.="            </div>";

  $pageHTML.= "          </div>";
  $pageHTML.= "        </div>";
  $pageHTML.= "      </div>";
  
  $pageHTML.= "    </div>";
  $pageHTML.= "  </div>";
  $pageHTML.= "</div>";  // end of steps modal
  //$pageHTML.= "</div>";  // end of modals
  return $pageHTML;
}

// Menu Slideout
function airmed_account_slideout($amAccount){

  $pageHTML = "  <div id='airmed-account-slideout' class='modal fade airmed-modal airmed-account-slideout collapse modal-sm' role='menu' aria-hidden='true'>";
  $pageHTML.= "    <div class='modal-dialog modal-dialog-centered modal-dialog-slideout modal-sm' role='menu'>";
  $pageHTML.= "      <div class='modal-content item-content'>";

  //$pageHTML.= "        <div class='am-loading d-flex justify-content-center hide'>";
  //$pageHTML.= "          <div>Loading...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif' id='airmed-modal-loading-indicator'>";
  //$pageHTML.="         </div>";
  
  $pageHTML.= "        <div class='modal-header'>";
  $pageHTML.= "          <div class='modal-title'>";
  $pageHTML.= "            <h1 class='name'>My Account</h1>";
  $pageHTML.= "          </div>";
  $pageHTML.= "          <button type='button' class='btn-close has-background' data-am-toggle='collapse' data-am-target='#airmed-account-slideout' aria-label='Close'>";
  $pageHTML.= "          </button>";
  $pageHTML.= "        </div>";
 
  $pageHTML.= "        <div class='modal-body'>";
  $pageHTML.= "          <div class='container-fluid'>";
  $pageHTML.= "          <div class='row justify-content-center'>";
  $pageHTML.= "            <div class='col-12'>";
  $pageHTML.="               <div class='mb-3 account-img'>";
  $pageHTML.="                  <i class='dashicons dashicons-admin-users mx-auto d-block'></i>";
  $pageHTML.="                  <span class='h6 text-muted text-center d-block'>$amAccount->name</span>";
  $pageHTML.="               </div>";
  $pageHTML.="               <div class='mb-3'>";
  $pageHTML.="                 <a class='' href='".airmed_pagelink('/airmed/airmed-dashboard/')."' >Dashboard</a>";
  $pageHTML.="               </div>";
  //$pageHTML.="               <div class='mb-3'>";
  //$pageHTML.="                 <a class='' href='".airmed_pagelink('/airmed/airmed-applications/')."' >Applications</a>";
  //$pageHTML.="               </div>";
  $pageHTML.="               <div class='mb-3'>";
  $pageHTML.="                 <a class='' href='".airmed_pagelink('/airmed/airmed-orders/')."' >Orders</a>";
  $pageHTML.="               </div>";
  $pageHTML.="              <div class='slideout-divider'></div>";

  $pageHTML.="               <div class='mb-1'>";
  $pageHTML.="                 <a class='' href='".airmed_pagelink('/airmed/airmed-messages/')."' >Messages</a>";
  $pageHTML.="               </div>";
  $pageHTML.="              <div class='sub-item mb-1 no-hover ' >";
  $pageHTML.="                <span class='ps-5'>Urgent Messages</span>";
  $pageHTML.="                <span id='account-slideout-urgent-messages' class='urgent-messages badge rounded-pill bg-danger mt-1'>$amAccount->urgentMessages</span>";
  $pageHTML.="              </div>";
  $pageHTML.="              <div class='sub-item mb-3 no-hover ' >";
  $pageHTML.="                <span class='ps-5'>New Messages</span>";
  $pageHTML.="                <span id='account-slideout-new-messages' class='new-messages badge rounded-pill bg-warning text-dark mt-1'>$amAccount->newMessages</span>";
  $pageHTML.="              </div>";

  $pageHTML.="              <div class='slideout-divider'></div>";
  $pageHTML.="               <div class='mb-3'>";
  $pageHTML.="                 <a class='' href='".airmed_pagelink('/airmed/airmed-patient/')."' >Account Details</a>";
  $pageHTML.="               </div>";
  $pageHTML.= "            </div>";
  $pageHTML.= "           </div>";
  $pageHTML.= "           </div>";
  $pageHTML.= "        </div>";

  $pageHTML.= "        <div class='modal-footer'>";
  $pageHTML.="               <div class='mb-3'>";
  $pageHTML.="                 <a class='' href='".airmed_pagelink('/airmed/airmed-login/')."' >Logout</a>";
  $pageHTML.="               </div>";
  $pageHTML.= "        </div>";

  
  $pageHTML.= "      </div>";  // end of model-content
  $pageHTML.= "    </div>";  // end of model-dialog
  $pageHTML.= "  </div>";  // end of login modal
  //$pageHTML.= "</div>";  // end of modals
  return $pageHTML;

}

// Menu Slideout
function airmed_cart_slideout(){
  
  function hasNoItems($hide,$showImage){
      $pageHTML ="                   <div class='mb-3 text-center no-items $hide'>";
      $pageHTML.="                     <span class=''>There are no items in the cart</span>";
      $pageHTML.="                   </div>";
      $pageHTML.="                   <div class='item-container default hide'>";
      if($showImage == 'enabled'){
        $pageHTML.="                     <img class='p-1' src='' alt='product image'/>";
      }
      $pageHTML.="                     <div class='item-detail-container'>";
      $pageHTML.="                       <p class='item-name'></p>";
      $pageHTML.="                       <p class='item-weight'></p>";
      $pageHTML.="                       <div class='item-weight-remove'>";
      $pageHTML.="                         <a class='item-removeButton has-background'' data-am-prodid='' data-am-orderid='' data-am-quantity='' data-am-source='' href='#'><i class='dashicons dashicons-trash'></i>Remove</a>";
      $pageHTML.="                       </div>";
      $pageHTML.="                     </div>";
      $pageHTML.="                     <div class='item-option-container'>";
      $pageHTML.="                       <div class='item-count'><span class='item-quantity'></span><span>x</span></div>";
      $pageHTML.="                       <div class='item-price'>";
      $pageHTML.="                         <div class='text-danger hide'></div><div class='item-unitPrice'></div>";
      $pageHTML.="                       </div>";
      $pageHTML.="                     </div>";
      $pageHTML.="                   </div>";

      return $pageHTML;
  }
  
  global $wp;
  $debug = true;
  $pageHTML = "";
  $hasToken = false;
  $hideFooter = "";
  $subTotal = "";
  
  $airmed = new stdClass();
  $airmed = $_SESSION['__airmed'];

  $pageHTML.= "  <div id='airmed-cart-slideout' class='modal fade airmed-modal airmed-cart-slideout collapse' data-am-orderid='$airmed->openOrderID' role='menu' aria-hidden='true'>";
  $pageHTML.= "    <div class='modal-dialog modal-dialog-centered modal-dialog-slideout' role='menu'>";
  $pageHTML.= "      <div class='modal-content item-content'>";

  $pageHTML.= "        <div class='am-loading d-flex justify-content-center hide'>";
  $pageHTML.= "          <div>Removing...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif' id='airmed-modal-loading-indicator'>";
  $pageHTML.="         </div>";
  
  $pageHTML.= "        <div class='modal-header'>";
  $pageHTML.= "          <div class='modal-title'>";
  $pageHTML.= "            <h1 class='name'>My Cart</h1>";
  $pageHTML.= "          </div>";
  $pageHTML.= "          <button type='button' class='btn-close has-background' data-am-toggle='collapse' data-am-target='#airmed-cart-slideout' aria-label='Close'>";
  $pageHTML.= "          </button>";
  $pageHTML.= "        </div>";
  $pageHTML.= "        <div class='modal-body'>";
  $pageHTML.= "          <div class='container-fluid'>";
  $pageHTML.= "            <div class='row justify-content-center'>";
  $pageHTML.= "              <div class='col-12 modal-body-cart-container'>";

  $img_show_order = get_option( 'airmed_options_img_show_cart') ? "enabled": "";

    if (!$airmed->hasOpenOrder){  // no open order

      $pageHTML.= hasNoItems('',$img_show_order);
      
      $hideFooter = "hide";

      //$pageHTML.= "              </div>";  // modal-cart-body-container
      //$pageHTML.= "            </div>";  // row
      //$pageHTML.= "          </div>";
      //$pageHTML.= "        </div>";  // modal body
    }
    else {  // has an open order
      
      $requestPath = '/API/Order/GetOrder/'.$airmed->openOrderID;
      $requestArray = airmed_call_request($requestPath,'GET',true,null);
      
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];

      // no error proceed with displaying data
      if ($errno === 200) {
        
        //Create an array of objects from the JSON returned by the API
        $jsonObj = json_decode($response);

        if(!empty($jsonObj)){
          $img_show_order = get_option( 'airmed_options_img_show_cart') ? "enabled": "";
          if (empty($jsonObj->orderItems)){  // empty order
            $pageHTML.= hasNoItems('',$img_show_order);
            
            $hideFooter = "hide";

            //$pageHTML.= "              </div>";  // modal-cart-body-container
            //$pageHTML.= "            </div>";  // row
            //$pageHTML.= "          </div>";
            //$pageHTML.= "        </div>";  // modal body
          }
          else {  // order has items
            $pageHTML.= hasNoItems('hide',$img_show_order);  // hide the no items DIV so it can be shown if all items removed

            $subTotal = number_format($jsonObj->subTotal,2);
            
            //Loop through the API results
            foreach($jsonObj->orderItems as $itemObj) {
              //Brand
              $itemObj->brandImgThumbString = strpos($itemObj->brandImgThumbString,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $itemObj->brandImgThumbString;
              //Strain
              $itemObj->strainImgThumbString = strpos($itemObj->strainImgThumbString,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $itemObj->strainImgThumbString;
              //Product
              $itemObj->productImgThumbString = strpos($itemObj->productImgThumbString,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $itemObj->productImgThumbString;

              if($itemObj->productImage == 0){
                $itemObj->thisImg = $itemObj->brandImgThumbString;
              }
              else if ($itemObj->productImage == 1){
                $itemObj->thisImg = $itemObj->strainImgThumbString;
              }
              else if ($itemObj->productImage == 2){
                $itemObj->thisImg = $itemObj->productImgThumbString; 
              }

              $pageHTML.="                   <div class='item-container'>";
              if($img_show_order == 'enabled'){
                $pageHTML.="                       <img class='p-1' src='".$itemObj->thisImg."' alt='product image'/>";
              }
              $pageHTML.="                     <div class='item-detail-container'>";
              $pageHTML.="                       <p class='item-name'>".substr($itemObj->description,0,strpos($itemObj->description,' ($'))."</p>";
              $pageHTML.="                       <p class='item-weight'>".substr($itemObj->description,strpos($itemObj->description,' ($'))."</p>";
              $pageHTML.="                       <div class='item-weight-remove'>";
              $pageHTML.="                         <a class='item-removeButton has-background'' data-am-prodid='$itemObj->id' data-am-orderid='$jsonObj->id' data-am-quantity='$itemObj->quantity' data-am-source='slideout' href='#'><i class='dashicons dashicons-trash'></i>Remove</a>";
              $pageHTML.="                       </div>";
              $pageHTML.="                     </div>";
              $pageHTML.="                     <div class='item-option-container'>";
              $pageHTML.="                       <div class='item-count'><span class='item-quantity'>$itemObj->quantity</span><span>x</span></div>";
              $pageHTML.="                       <div class='item-price'>";
              
              if($itemObj->unitSalePrice > 0){
                $pageHTML.="                         <div class='text-danger'>$".number_format($itemObj->unitSalePrice,2)."</div><div class='item-unitPrice strikethrough'>$".number_format($itemObj->unitPrice,2)."</div>";
              }
              else {
                $pageHTML.="                         <div>$".number_format($itemObj->unitPrice,2)."</div>";
              }

              $pageHTML.="                       </div>";
              $pageHTML.="                     </div>";
              $pageHTML.="                   </div>";


            } // end of for
          
          }  // end of else *has items*
        }  // end of jsonbObj not empty
      }
      else {
        //$error_message = curl_strerror($errno);
        echo "<div>request error ({$errno}):\n {$err_message} </div>";
        if ($debug) echo "<div>request Error: $err </div>";
        else echo "<div> $err </div>";
        //if ($errno === 22 || $errno === 400){
          //airmed_setSession("__amError","incorrect_email_verification");
          //if ( wp_get_referer() ) {
          //  wp_safe_redirect( wp_get_referer() );
          //}
        //}
      }
    }
  
  $pageHTML.= "              </div>";  // modal-cart-body-container
  $pageHTML.= "            </div>";  // row
  $pageHTML.= "          </div>";
  $pageHTML.= "        </div>";  // modal body

  $pageHTML.= "        <div class='modal-footer $hideFooter'>";
  $pageHTML.= "          <div class='container-fluid'>";
  $pageHTML.="             <div class='row mb-3'>";
  $pageHTML.="               <div class='col-12'>";
  $pageHTML.="                 <div class='text-end' >";
  $pageHTML.="                   <div class='subtotal' ><span>Subtotal:</span><span>$".$subTotal."</span></div>";
  //$pageHTML.="                   <div class='sales-tax' >*Sales tax will be added at checkout</div>";
  $pageHTML.="                 </div>";
  $pageHTML.="               </div>";
  $pageHTML.="             </div>";
  $pageHTML.="             <div class='row'>";
  $pageHTML.="               <div class='col-12 col-sm-6'>";
  $pageHTML.="                 <div class='text-center'>";
  $pageHTML.="                   <a class='btn btn-secondary' href='".add_query_arg('id',$airmed->openOrderID,airmed_pagelink('/airmed/airmed-cart/'))."' >View Cart</a>";
  $pageHTML.="                 </div>";
  $pageHTML.="               </div>";
  $pageHTML.="               <div class='col-12 col-sm-6'>";
  $pageHTML.="                 <div class='text-center'>";
  $pageHTML.="                   <a class='btn btn-primary' href='".add_query_arg('id',$airmed->openOrderID,airmed_pagelink('/airmed/airmed-checkout/'))."' >Checkout</a>";
  $pageHTML.="                 </div>";
  $pageHTML.="               </div>";
  $pageHTML.="             </div>";
  $pageHTML.="           </div>";
  $pageHTML.= "        </div>";

  $pageHTML.= "      </div>";  // end of model-content
  $pageHTML.= "    </div>";  // end of model-dialog
  $pageHTML.= "  </div>";  // end of login modal
  //$pageHTML.= "</div>";  // end of modals
  
  return $pageHTML;

}

// Application progress
function airmed_application_step_progress($step){
  $step1 = $step2 = $step3 = '';
  switch($step){
    case 1:
      $step1 = 'active';
      break;
    case 2:
      $step1 = 'active';
      $step2 = 'active';
      break;
    case 3:
      $step1 = 'active';
      $step2 = 'active';
      $step3 = 'active';
      break;
    default:
      $step1 = 'active';
      break;
    
  }
  $pageHTML = "";
  $pageHTML.="  <div class='application-heading-tab'>";
  $pageHTML.="    <ul>";
  $pageHTML.="      <li class='$step1'>";
  $pageHTML.="        <a href='#' data-id='#step1'>1. Online Account</a>";
  $pageHTML.="      </li>";
  $pageHTML.="      <li class='$step2'>";
  $pageHTML.="        <a href='#' data-id='#step2'>2. Application</a>";
  $pageHTML.="      </li>";
  $pageHTML.="      <li class='$step3'>";
  $pageHTML.="        <a href='#' data-id='#step3'>3. Medical Docs</a>";
  $pageHTML.="      </li>";
  $pageHTML.="    </ul>";
  $pageHTML.="  </div>";
  return $pageHTML;
}

// $jsonObj is the patient object passed in
function dashboard_application_meter($jsonObj){
  $pageHTML = "";
  $pageHTML.="        <div id='airmed-progress-meter' class='card card-default home-widget primary'>";
  $pageHTML.="          <div class='card-header'>";
  $pageHTML.="            <h4 class='card-title'>";
  //$pageHTML.="              <i class='fas fa-chart-line'></i>";
  $pageHTML.="              <span>Application Progress Meter</span>";
  $pageHTML.="            </h4>";
  $pageHTML.="          </div>";
  $pageHTML.="          <div class='card-body' id='airmed-orderProductscard'>";
  $pageHTML.="            <ul class='nav nav-wizard'>";
  $class = $jsonObj->canApply ? "active" : "complete" ;
  $pageHTML.="              <li id='airmed-register-chevron' class='$class'>";
  //$pageHTML.="                <a href='#'>Register</a>";
  $pageHTML.="                <span>Application</span>";
  $pageHTML.="              </li>";
  $class = $jsonObj->canApply ? "disabled" : "complete" ;
  $pageHTML.="              <li id='airmed-submit-app-chevron' class='complete'>";
  $pageHTML.="                <span>Send Documents</span>";
  $pageHTML.="              </li>";
  $class = $jsonObj->canApply ? "disabled" : ($jsonObj->canPurchase ? "complete" : "active");
  $pageHTML.="              <li id='airmed-processing-app-chevron' class='$class'>";
  $pageHTML.="                <span>Processing</span>";
  $pageHTML.="              </li>";
  $class = $jsonObj->canPurchase ? "active" : "disabled" ;
  $pageHTML.="              <li id='airmed-app-order-chevron' class='$class'>";
  $pageHTML.="                <span>Order</span>";
  $pageHTML.="              </li>";
  $pageHTML.="            </ul>";
  $pageHTML.="            <br>";
  $pageHTML.="            <div class='card card-body col-sm-12'>";
  $pageHTML.="              <div id='airmed-base-txt' style=''>Please use the progress bar above to monitor the current stage of your application.</div>";
  $pageHTML.="              <div id='airmed-register-txt' style='display: none;'>Obtain application documents from the producer and complete application form and medical document.</div>";
  $pageHTML.="              <div id='airmed-submit-app-txt' style='display: none;'>To complete the application process, please ensure you print your application and medical document, consult with your health care provider, and mail your completed and signed documents to us.</div>";
  $pageHTML.="              <div id='airmed-processing-app-txt' style='display: none;'>We have received your documents and are currently processing your application. You're almost ready to order.</div>";
  $pageHTML.="              <div id='airmed-app-order-txt' style='display: none;'>Once your application is approved, you'll be able to access our product catalog and order online.</div>";
  $pageHTML.="            </div>";
  $pageHTML.="          </div>";
  $pageHTML.="          <div class='card-footer'></div>";
  $pageHTML.="        </div>";
  return $pageHTML;
}

function dashboard_application_medical_documents(){
  
  $requestPath = '/API/Producer/GetProducer';
  $requestArray = airmed_call_request($requestPath,'GET',false,null);
  $response = $requestArray['body'];
  $err = $requestArray['response']['message'];
  $errno = $requestArray['response']['code'];
  $err_message = $requestArray['response']['message'];
  $jsonObj = "";

  $mailAddress = "";
  $medicalurl = "";
  $fax = "";
  // no error proceed with displaying data
  if ($errno === 200) {
    //Create an array of objects from the JSON returned by the API
    $jsonObj = json_decode($response);
    #echo "<pre>";
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

    if (!empty($jsonObj->mailAddress->suite)){$mailAddress.= "#".$jsonObj->mailAddress->suite." - ";}
    if (!empty($jsonObj->mailAddress->streetNumber)){$mailAddress.= $jsonObj->mailAddress->streetNumber." ";}
    if (!empty($jsonObj->mailAddress->streetName)){$mailAddress.= $jsonObj->mailAddress->streetName;}
    if (!empty($jsonObj->mailAddress->floor)){$mailAddress.= ", Flr ".$jsonObj->mailAddress->floor;}
    $mailAddress.= "<br>".$jsonObj->mailAddress->city;
    $mailAddress.= ", ".$jsonObj->mailAddress->province;
    if (!empty($jsonObj->mailAddress->country)){$mailAddress.= ", ".$jsonObj->mailAddress->country;}
    $mailAddress.= "<br>".$jsonObj->mailAddress->postalCode;
    $medicalurl = $jsonObj->medicalDocumentLink;
    $fax = $jsonObj->fax;
  }
  else{
    $mailAddress = "Error receiving mail address";
  }
   


  $pageHTML = "";
  //$pageHTML.="  <div class='airmed-content'>";
  
  $pageHTML.="    <div class='am-page-title row pb-0'>";
  $pageHTML.="      <div class='col col-12'>";
  $pageHTML.="        <div class='col-inner'>";
  $pageHTML.="          <h1>Medical Documents</h1>";
  $pageHTML.= airmed_application_step_progress(3);
  $pageHTML.="       </div>";
  $pageHTML.="     </div>";
  $pageHTML.="    </div>";
  $pageHTML.="    <div class='row btn-process aligncenter'>";
  $pageHTML.="      <div class='col col-12'>";
  $pageHTML.="        <div class='d-flex justify-content-center'>";
  $pageHTML.="          <a class='btn btn-outline-dark' role='button' title='Show Application Steps' data-am-toggle='modal' data-am-target='#airmed-modal-application-steps' data-am-step='1' >";
  $pageHTML.="            <span>See Application Step Instructions</span>";
  $pageHTML.="            <i class='dashicons dashicons-editor-help'></i>";
  $pageHTML.="          </a>";
  $pageHTML.="       </div>";
  $pageHTML.="     </div>";
  $pageHTML.="    </div>";
  
  $pageHTML.="    <div class='row'>";
  $pageHTML.="      <div class='col col-12 med-docs'>";
  
  $pageHTML.="        <h4 class='med-title'>Almost There</h4>";
  $pageHTML.="        <p class='med-text'>Thank you for signing up and applying. Please complete your application by submitting your medical document.</p>";
  $pageHTML.="        <p class='med-text'>If it has already been sent, thank you for your patience. It will be processed as we receive it.</p>";
  $pageHTML.="        <div class='med-boxes'>";
  $pageHTML.="          <div class='med-box'>";
  //$pageHTML.="            <p><b>Mail to</b><br> <span class='street'></span><br><span class='city'></span><br> <span class='zip'></span></p> ";
  $pageHTML.="            <p><span>Mail to:</span><br><span>$mailAddress</span></p> ";
  $pageHTML.="            <p class='note'>*Please use courier or Canada Post</p> ";
  $pageHTML.="          </div>";
  $pageHTML.="          <div class='med-box'>";
  $pageHTML.="            <p><span>Fax to:</span><br><span class='fax'>$fax</span></p>";
  $pageHTML.="          </div>";
  $pageHTML.="        </div>";
  $pageHTML.="        <div class='med-buttons'>";
  $pageHTML.="          <a href='$medicalurl' class='btn btn-primary download_template' target='_blank'>Download Medical Form</a>";
  $pageHTML.="        </div>";
  $pageHTML.="        <div class='med-footer'>";
  $pageHTML.="          Please note that you will not be able to add items to your cart and check out until your account has been approved.";
  $pageHTML.="        </div>";
  
  $pageHTML.="      </div>";
  $pageHTML.="    </div>";
  //$pageHTML.="  </div>";
  
  $pageHTML.= airmed_modal_application_steps($jsonObj,3);
  
  return $pageHTML;
}

function dashboard_client_details($jsonObj){
  $pageHTML="";
  $pageHTML.="<div class='card card-default home-widget danger'>";
  $pageHTML.="  <div class='card-header'>";
  $pageHTML.="    <h4 class='card-title'>";
  //$pageHTML.="      <i class='far fa-id-card'></i>";
  $pageHTML.="      <span>Client Details</span>";
  $pageHTML.="    </h4>";
  $pageHTML.="  </div>";
  $pageHTML.="  <div class='card-body'>";
  $pageHTML.="    <div class='sm-box bg-lime-active text-black'>";
  $pageHTML.="      <div class='inner'>";
  $pageHTML.="        <h4>Current Application Details</h4>";
  $pageHTML.="        <br>";
  $pageHTML.="        <label for='AppStatus'>Status:</label> $jsonObj->appStatus<br>";
  $pageHTML.="        <label for='DocumentID'>Document #:</label> $jsonObj->documentID<br>";
  //$pageHTML.="        <label for='ExpirationDate'>Expires:</label> $jsonObj->expirationDate<br>";
  $pageHTML.="        <label for='ExpirationDate'>Expires:</label>".date('Y-m-d g:ia',(int)substr($jsonObj->expirationDate,6,-10))."<br>";
  $pageHTML.="      </div>";
  $pageHTML.="      <div class='icon'>";
  //$pageHTML.="        <i class='fas fa-file-alt'></i>";
  $pageHTML.="        <i class='dashicons dashicons-media-default'></i>";
  $pageHTML.="      </div>";
  $pageHTML.="     <a href='".add_query_arg('id',$jsonObj->appID,airmed_pagelink('/airmed/airmed-application/'))."' class='sm-box-footer'>";
  //$pageHTML.="      <a href='".airmed_pagelink('/airmed/')."Register/Details/APPL2021113011390910000021' class='sm-box-footer'>";
  $pageHTML.="        <text><strong>View Application</strong></text>";
  //$pageHTML.="        <i class='fas fa-arrow-circle-right'></i>";
  $pageHTML.="        <i class='dashicons dashicons-arrow-right-alt'></i>";
  $pageHTML.="      </a>";
  $pageHTML.="    </div>";
  $pageHTML.="    <div class='sm-box bg-gray'>";
  $pageHTML.="      <div class='inner'>";
  $pageHTML.="        <br>";
  $pageHTML.="        <h3><span class='text-danger'>$jsonObj->monthlyGramsRemaining</span></h3>";
  $pageHTML.="        <p><label for='MonthlyGramsRemaining'>Grams Remaining in 30 Day Period</label></p>";
  $pageHTML.="        <p><i><label for='MaxMonthlyGrams'>Max Grams / 30 Day Period:</label> <strong><span class='text-danger'>$jsonObj->maxMonthlyGrams</span></strong></i></p>";
  $pageHTML.="        <p>";
  $pageHTML.="          <i>";
  $pageHTML.="            <label for='NextPeriodStartDate'>Next Order Period Begins:</label>";
  $nextPeriod = empty($jsonObj->nextPeriodStartDate) ? "** No orders shipped" : $jsonObj->nextPeriodStartDate;
  $pageHTML.="              <strong><span class='text-danger'>$nextPeriod</span></strong>";
  $pageHTML.="          </i>";
  $pageHTML.="        </p>";
  $pageHTML.="      </div>";
  $pageHTML.="      <div class='icon'>";
  $pageHTML.="        <i class='dashicons dashicons-products'></i>";
  //$pageHTML.="        <i class='fas fa-balance-scale'></i>";
  $pageHTML.="      </div>";
  $pageHTML.="    </div>";
  $pageHTML.="    <div class='sm-box  bg-aqua'>";
  $pageHTML.="      <div class='inner'>";
  $pageHTML.="        <h4>Last Order</h4>";
  $pageHTML.="          <br>";
  if ($jsonObj->hasOrder){
    //$pageHTML.="            <label for='DateOrdered'>Date Ordered:</label>&nbsp;".date('Y-m-d g:ia',strtotime($jsonObj->dateOrdered))."<br>";
    $pageHTML.="            <label for='DateOrdered'>Date Ordered:</label>&nbsp;".date('Y-m-d g:ia',(int)substr($jsonObj->dateOrdered,6,-10))."<br>";
    $pageHTML.="            <label for='OrderStatus'>OrderStatus</label>&nbsp;$jsonObj->orderStatus<br>";
    $pageHTML.="            <label for='OrderState'>Order State:</label>&nbsp;$jsonObj->orderState<br>";
    $pageHTML.="            <label for='TotalGrams'>Total Grams in Order:</label>&nbsp;$jsonObj->totalGrams<text>g</text><br>";
    $pageHTML.="            <label for='Total'>Total:</label>&nbsp;$".$jsonObj->total."<br>";
  }
  else {
    $pageHTML.="        <br/><br/>No Previous Order<br/><br/>";    
  }
  $pageHTML.="      </div>";
  $pageHTML.="      <div class='icon'>";
  //$pageHTML.="        <i class='fas fa-shopping-cart'></i>";
  $pageHTML.="        <i class='dashicons dashicons-cart'></i>";
  $pageHTML.="      </div>";
  if ($jsonObj->hasOrder){
    $pageHTML.="     <a href='".add_query_arg('id',$jsonObj->lastOrderID,airmed_pagelink('/airmed/airmed-order/'))."' class='sm-box-footer'>";
    $pageHTML.="          <text><strong>Go To Details</strong></text> <i class='fas fa-arrow-circle-right'></i>";
  }
  else {
    $pageHTML.="     <a href='".airmed_pagelink('/airmed/')."' class='sm-box-footer'>";
    //$pageHTML.="        <a href='/airmed/' class='sm-box-footer'>";
    $pageHTML.="          <text><strong>Create Order</strong></text>";
    //$pageHTML.="          <i class='fas fa-plus-circle'></i>";
    $pageHTML.="          <i class='dashicons dashicons-plus-alt'></i>";
  }
  $pageHTML.="        </a>";
  $pageHTML.="    </div>";
  $pageHTML.="  </div>";
  $pageHTML.="</div>";

  return $pageHTML;
}

function dashboard_order_products($jsonObj){
  $pageHTML="";
  $pageHTML.="<div id='airmed-orderProductscard' class='card card-default home-widget success'>";
  $pageHTML.="  <div class='card-header'>";
  $pageHTML.="    <h4 class='card-title'>";
  //$pageHTML.="      <i class='far fa-check-square'></i>";
  $pageHTML.="      <span>Order Products Now</span>";
  $pageHTML.="    </h4>";
  $pageHTML.="  </div>";
  $pageHTML.="  <div class='card-body'>";
  $pageHTML.="    <div class='text-center'>";
  $pageHTML.="      Your application has been approved. Please click the button below to begin ordering product(s).<br>";
  $pageHTML.="    </div>";
  $pageHTML.="  </div>";
  $pageHTML.="  <div class='card-footer'>";
  $pageHTML.="    <div class='text-center widget-btn'>";
  $pageHTML.="     <a class='btn btn-primary btn-lg' href='".airmed_pagelink('/airmed/')."'>Order Now!</a> ";
  //$pageHTML.="      <a class='btn btn-primary btn-lg' href='/Order/Create'>Order Now!</a>";
  $pageHTML.="    </div>";
  $pageHTML.="  </div>";
  $pageHTML.="</div>";

  return $pageHTML;
}

function dashboard_order_progress($jsonObj){
  $pageHTML="";
  $pageHTML.="<div class='card card-default home-widget primary mt-2'>";
  $pageHTML.="  <div class='card-header'>";
  $pageHTML.="    <h4 class='card-title'>";
  //$pageHTML.="      <i class='fas fa-chart-line'></i>";
  $pageHTML.="      <span>Current Order Status</span>";
  $pageHTML.="    </h4>";
  $pageHTML.="  </div>";
  $pageHTML.="  <div class='card-body' id='airmed-orderStatusCard'>";
  $pageHTML.="    <ul class='nav nav-wizard'>";
  $pageHTML.="      <li id='airmed-app-approved-chevron' class='complete'><span>Approved</span></li>";
  $pageHTML.="      <li id='airmed-order-chevron' class='active canclick'><a href='/Order/Create'>Order</a></li>";
  $pageHTML.="      <li id='airmed-processing-order-chevron' class='disabled'><span>Processing Order</span></li>";
  $pageHTML.="      <li id='airmed-shipped-chevron' class='disabled'><span>Shipped</span></li>";
  $pageHTML.="    </ul>";
  $pageHTML.="    <br>";
  $pageHTML.="    <div class='card card-body'>";
  $pageHTML.="      <div id='airmed-base-txt' style=''>The progress bar above indicates the status of your current order.</div>";
  $pageHTML.="      <div id='airmed-app-approved-txt' style='display:none'>Your application is approved. Please click 'Order' above to access the product catalog.</div>";
  $pageHTML.="      <div id='airmed-order-txt' style='display: none;'>Please click 'Order' above to access the product catalog.</div>";
  $pageHTML.="      <div id='airmed-processing-order-txt' style='display: none;'>Your order and shipment are currently being processed.</div>";
  $pageHTML.="      <div id='airmed-shipped-txt' style='display:none'>Your order is shipped! Please click the 'Shipped' button above to view the details of your shipment.</div>";
  $pageHTML.="    </div>";
  $pageHTML.="  </div>";
  //$pageHTML.="  <div class='card-footer'></div>";
  $pageHTML.="</div>";
  return $pageHTML;
}

//add_filter( 'wp_nav_menu_primary-menu_items', 'airmed_test_menu',10,1);
function airmed_test_menu($items){

  $items_array = array();
  while ( false !== ( $item_pos = strpos ( $items, '<li', 10 ) ) ) // Add the position where the menu item is placed
  {
      $items_array[] = substr($items, 0, $item_pos);
      $items = substr($items, $item_pos);
  }
  $items_array[] = $items;
  //aLog($items_array);
  array_splice($items_array, 1, 0, '<li class="menu-item">hi</li>'); // insert custom item after 9th item one
  // put array back into string
  $items = implode('', $items_array);
 
 return $items;
}

// how to add a wp menu item
// can use wp_nav_menu_[menu name slug]_items if we decide on one specificaly for clients to enforce
add_filter('wp_nav_menu_items', 'airmed_custom_nav_menu_items', 10, 2 );

function airmed_custom_nav_menu_items ( $items, $args ) {
  //aLog("custom nav");
  $airmed = new stdClass();
  $airmed = $_SESSION['__airmed'];
  
  $slug = get_post_field('post_name' );
  $slideout = get_option('airmed_options_login_type',1 );
  $register = get_option('airmed_options_main_nav_register','');
  $site_menu = get_option('airmed_options_use_site_menu','');
  $show_shop = get_option( 'airmed_options_show_shop','');
  $current_css = "current-menu-item current-page-item";
  $shop_css = $cart_css = $login_css = $register_css = "";
  
  // css menu styles based on page slug
  if ($slug === "airmed"){
    $shop_css = $current_css;
  }
  if ($slug === "airmed-login"){
    $login_css = $current_css;
  }
  if ($slug === "airmed-cart"){
    $cart_css = $current_css;
  }
  if ($slug === "airmed-new-account"){
    $register_css = $current_css;
  }

  // get menu items and count
  $menuLocations = get_nav_menu_locations(); // Get our nav locations (set in our theme, usually functions.php). This returns an array of menu locations ([LOCATION_NAME] = MENU_ID);
  //aLog($menuLocations);
  $menuID = $menuLocations['primary']; // Get the *primary* menu ID
  $primaryNav = wp_get_nav_menu_items($menuID);
  $defaultCount = count($primaryNav);
  $navLinkCount = $defaultCount+3;

  // get menu order
  $nav_shop_order = get_option( 'airmed_options_shop_navigation_order', $navLinkCount);
  $nav_cart_order = get_option( 'airmed_options_cart_navigation_order', $navLinkCount);
  $nav_login_order = get_option( 'airmed_options_login_navigation_order', $navLinkCount);
  
  // add all <li> tag structures to an array
  $items_array = array();
  while ( false !== ( $item_pos = strpos ( $items, '<li', 10 ) ) ) // Add the position where the menu item is placed
  {
      $items_array[] = substr($items, 0, $item_pos);
      $items = substr($items, $item_pos);
  }
  $items_array[] = $items;
  
  //aLog($items);
  if(!empty($register) || !empty($site_menu)){
    if ($args->theme_location == 'primary') {
      
      // not logged in
      if((!$airmed->hasToken) || ($slug === "airmed-login") ){
        for ($c = 1; $c <= $navLinkCount; $c++):
          
          if(!empty($site_menu)){
            //$items_array[] = "<li class='menu-item airmed-menu-item $shop_css'><a href='".airmed_pagelink('/airmed/')."'>Shop</a></li>";
            if(($nav_shop_order == $c) && !empty($show_shop)){array_splice($items_array, $c-1, 0, "<li class='menu-item airmed-menu-item $shop_css'><a href='".airmed_pagelink('/airmed/')."'>Shop</a></li>");} // insert custom item after 9th item one
            //if($nav_cart_order == $c){array_splice($items_array, $c-1, 0, "<li class='menu-item airmed-menu-item $cart_css'><a class='airmed-modal-link' href='#airmed-cart-slideout' data-am-toggle='collapse' aria-controls='airmed-cart-slideout' aria-expanded='false' role='button'>Cart<span id='airmed-menu-cart-items' class='badge bg-dark rounded-pill'>4</span></a></li>");}
          }
          $register_menu = "<li class='menu-item airmed-menu-item $register_css'><a href='".airmed_pagelink('/airmed/airmed-new-account/')."'>Sign Up</a></li>";
          // no slideout or modal
          if($slideout == 1){
            if($nav_login_order == $c){array_splice($items_array, $c-1, 0, $register_menu."<li class='menu-item airmed-menu-item $login_css'><a href='".airmed_pagelink('/airmed/airmed-login/')."'>AirMed Login</a></li>");}
          }
          else{  // slideout or modal
            if($nav_login_order == $c){array_splice($items_array, $c-1, 0, $register_menu."<li class='menu-item airmed-menu-item'><a class='airmed-modal-link' data-am-toggle='modal' data-am-target='#airmed-modal-login'>AirMed Login</a></li>");}
          }
        endfor;
      }
      else {  // logged in
        for ($c = 1; $c <= $navLinkCount; $c++):
          // use site menu
          if(!empty($site_menu)){
            if($nav_shop_order == $c){array_splice($items_array, $c-1, 0, "<li class='menu-item airmed-menu-item $shop_css'><a href='".airmed_pagelink('/airmed/')."'>Shop</a></li>");}
            // only show cart if can purchase
            if($airmed->patient->canPurchase){
              $cart_menu = "<li class='menu-item airmed-menu-item $cart_css'><a class='airmed-modal-link' href='#airmed-cart-slideout' data-am-toggle='collapse' aria-controls='airmed-cart-slideout' aria-expanded='false' role='button'>";
              $cart_menu .= "  Cart<span id='airmed-menu-cart-items' class='badge bg-dark rounded-pill'>$airmed->numOfItems</span>";
              $cart_menu ."</a></li>";
              if($nav_cart_order == $c){array_splice($items_array, $c-1, 0, $cart_menu);}
            }
            $account_menu = "<li class='menu-item airmed-menu-item'><a class='airmed-modal-link' href='#airmed-account-slideout' data-am-toggle='collapse' aria-controls='airmed-account-slideout' aria-expanded='false' role='button'>";
            $account_menu .= "<span>My Account";
            if($airmed->newMessages){
              $account_menu .= "<i id='airmed-menu-new-messages' class='dashicons dashicons-bell text-warning' title='New Messages'><span class='visually-hidden'>New Messages</span></i>";
            }
            if($airmed->urgentMessages){
              $account_menu.= "<i id='airmed-menu-urgent-messages' class='dashicons dashicons-bell text-danger' title='Urgent Messages'><span class='visually-hidden'>New Urgent Messages</span></i>";
            }
            //$items_array[] = "<span id='#airmed-menu-urgent-messages' class='badge-message bg-danger rounded-pill'></span>";
            //$items_array[] = "<span id='#airmed-menu-new-messages' class='badge-message bg-warning rounded-pill'></span>";
            $account_menu.= "</span></a></li>";
            if($nav_login_order == $c){array_splice($items_array, $c-1, 0, $account_menu);}
          }
          else {
            if($nav_login_order == $c){array_splice($items_array, $c-1, 0, "<li class='menu-item airmed-menu-item'><a href='".airmed_pagelink('/airmed/airmed-login/')."'>AirMed Logout</a></li>");}
          }
        endfor;
      }
    }
  }
  $items = implode('', $items_array);
  return $items;
}

/*
add_filter( 'wp_nav_menu_objects', 'example_last_nav_item', 10, 2 );
function example_last_nav_item( $items, $args ){

    //if ( $args->theme_location == 'primary' ){

        //parent menu item
        $parent_item = array(
            'title'            => __('Profile'),
            'menu_item_parent' => 0, //PARENT ELEMENT HAS 0
            'ID'               => 'profile',
            'db_id'            => 999999, //MAKE SURE IT's UNIQUE ID
            'url'              => 'http://google.com',
            'classes'          => array( 'custom-menu-item' )
        );

        //submenu menu item
        $submenu_item = array(
            'title'            => __('Submenu first element'),
            'menu_item_parent' => 999999, //FOR SUBMENU ITEM SET PARENT's db_id
            'ID'               => 'submenu-item',
            'db_id'            => 99992,
            'url'              => 'http://google.com',
            'classes'          => array( 'custom-menu-item' )
        );

        $items[] = (object) $parent_item;
        $items[] = (object) $submenu_item;
    //}

    return $items;

}
*/

// add main modals div and login modal to the body as well as sets up globals
function airmed_custom_body_adds(){
  //aLog("custom body");
  airmed_Globals();
  includeModals(true);
}
add_action('wp_body_open', 'airmed_custom_body_adds');
?>