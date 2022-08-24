<?php
function am_login_post(){
  /**
     * At this point, $_GET/$_POST variable are available
     *
     * We can do our normal processing here
  */ 
  $debug = true;
  // server response
  //echo '<pre>';
  //print_r($_POST );
  //echo '</pre>';
    
  $post_params = array();
  if ( !empty( $_POST ) ) {
    // Sanitize the POST field
    $action = empty( $_POST['action'] ) ? '' : $_POST['action'];
    $returnUrl = empty( $_POST['returnUrl'] ) ? 'airmed-dashboard' : $_POST['returnUrl'];
    $query = empty( $_POST['query'] ) ? '' : $_POST['query'];
    $email = empty( $_POST['amUsername'] ) ? '' : $_POST['amUsername'];
    $password = empty( $_POST['amPassword'] ) ? '' : $_POST['amPassword'];
    // adde to array
    $post_params['Email']=$email;
    $post_params['Password']=$password;

    // now send an API call to Airmed to check account for login
    $requestPath = '/API/Account/Authenticate';
    $requestArray = airmed_call_request($requestPath,'POST',false,$post_params);
    
    if (array_key_exists('body',$requestArray)){
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];
    }
    else { //WP_error
      $errno = 0;
      $error_message = $requestArray['errors']['http_request_failed']['0'];
    }



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

      //echo "<div>Response: ".$response."</div>";
      $response = str_replace('"','',$response);
      airmed_setSession("__amAuthToken",$response);
      airmed_setSession("__amPostData",$post_params);
      airmed_setSession("__amError","");
      //if(isset($_SESSION['__RequestVerificationToken'])) {
      //  echo "<div>Token: ".$_SESSION['__RequestVerificationToken']."</div>";
      //} else {
      //  echo "<div>Session token not set</div>";
      //}
      // upon success, redirect to $returnurl
      
      //add_query_arg('id',$jsonObj->appID,airmed_pagelink('/airmed/'.$returnUrl))
      if(!empty($query)){$query='?'.$query;}
      if (wp_redirect( airmed_pagelink('/airmed/'.$returnUrl).$query,302)){
        exit;
      }
      //get_header();
      //do_action('load_airmed_dashboard');
      //get_footer();
    }
    else {
      echo "<div>request error ({$errno}):\n {$err_message} </div>";
      if ($debug) echo "<div>request Error: $err </div>";
      else echo "<div> $err </div>";
      
      if ($errno === 22 || $errno === 401){
        airmed_setSession("__amError","incorrect_login");
        echo "<div>error 22</div>";
        if ( wp_get_referer() ) {
          wp_safe_redirect( wp_get_referer() );
        }
      }
    }
  }  
  else {
    echo "<div>Error: No Post Params</div>";
  }
}

function am_new_account_post(){
  /**
     * At this point, $_GET/$_POST variable are available
     *
     * We can do our normal processing here
  */ 
  $debug = true;
  $post_params = array();
  if ( !empty( $_POST ) ) {
    // Sanitize the POST field
    $action = empty( $_POST['action'] ) ? '' : $_POST['action'];
    
    //$email = empty( $_REQUEST['amFirstName'] ) ? '' : $_REQUEST['amFirstName'];
    //$password = empty( $_REQUEST['amLastName'] ) ? '' : $_REQUEST['amLastName'];
    
    // add to array
    $post_params['FName'] = empty( $_POST['amFirstName'] ) ? '' : $_POST['amFirstName'];
    $post_params['LName'] = empty( $_POST['amLastName'] ) ? '' : $_POST['amLastName'];
    $post_params['Phone1'] = empty( $_POST['amPhone'] ) ? '' : $_POST['amPhone'];
    $post_params['DateOfBirth'] = empty( $_POST['amBirthDate'] ) ? '' : $_POST['amBirthDate'];
    $post_params['Email'] = empty( $_POST['amEmail'] ) ? '' : $_POST['amEmail'];
    $post_params['Password'] = empty( $_POST['amPassword'] ) ? '' : $_POST['amPassword'];
    $post_params['ConfirmPassword'] = empty( $_POST['amConfirmPassword'] ) ? '' : $_POST['amConfirmPassword'];
    $post_params['EmailConsent'] = empty( $_POST['amEmailConsent'] ) ? 'false' : 'true';
    $post_params['AcceptedTerms'] = empty( $_POST['amAcceptedTerms'] ) ? 'false' : 'true';
    $post_params['ProducerID'] = empty( $_POST['amProducerID'] ) ? '' : $_POST['amProducerID'];
    
    $post_params['GoogleCaptchaToken'] = empty( $_POST['GoogleCaptchaToken'] ) ? '' : $_POST['GoogleCaptchaToken'];
    //$post_params['GoogleCaptchaToken'] = "";
    $post_params['CallBackURL'] = airmed_pagelink('/airmed/airmed-confirm-email/');

    // server response
    //echo '<pre>';
    //print_r($_POST );
    //print_r($post_params);
    //echo '</pre>';

    // now send an API call to Airmed to create the new online account
    $requestPath = '/API/Account/Signup';
    $requestArray = airmed_call_request($requestPath,'POST',false,$post_params);
    aLog("Signup Post:");
    aLog($requestArray);
    
    if (array_key_exists('body',$requestArray)){
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];
    }
    else { //WP_error
      $errno = 0;
      $error_message = $requestArray['errors']['http_request_failed']['0'];
    }
    
    //echo '<pre>Response:';
    //print_r($requestArray);
    //echo '</pre>';

    // no error proceed with displaying data
    if ($errno === 200) {

      //echo "<div>Response: ".$response."</div>";
      //$response = str_replace('"','',$response);
      //airmed_setSession("__amRequestVerificationToken",$response);
      //airmed_setSession("__amPostData",$post_params);
      //if(isset($_SESSION['__RequestVerificationToken'])) {
      //  echo "<div>Token: ".$_SESSION['__RequestVerificationToken']."</div>";
      //} else {
      //  echo "<div>Session token not set</div>";
      //}
      
      airmed_setSession("__amSuccess","new_account");
      airmed_setSession("__amError","");
      // redirect with email message with success param
      //if (wp_redirect( add_query_arg('success','true',airmed_pagelink('/airmed/airmed-new-account/')),301)){
      if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-new-account/'),301)){
        exit;
      }

    }
    else {
      airmed_setSession("__amPostData",$post_params);
      //echo "<div>Response error ({$errno}):\n {$err_message}</div>";
      $response = str_replace('["','',$response);
      $response = str_replace('"]','',$response);
      //echo "<div> $response </div>";
      //if ($debug){
      //  echo '<pre>';
      //  print_r($post_params);
      //  echo '</pre>';
      //}
  
      if ($errno === 400){
        airmed_setSession("__amError",$response);
        
        //echo "<div>error $errno</div>";
        // go back to previous page to show error
        if ( wp_get_referer() ) {
          wp_safe_redirect( wp_get_referer() );
        }
      }
    }
  }  
  else {
    echo "<div>Error: No Post Params</div>";
  }

  
}

function am_new_application_post(){
  // Sanitize the POST field
  
  $post_params = array();
  header('Content-Type: application/json');
  if ( !empty( $_POST ) ) {
    // Sanitize the POST field
    $action = empty( $_POST['action'] ) ? '' : $_POST['action'];
    
    //$email = empty( $_REQUEST['amFirstName'] ) ? '' : $_REQUEST['amFirstName'];
    //$password = empty( $_REQUEST['amLastName'] ) ? '' : $_REQUEST['amLastName'];
    
    // add to array
    $post_params['FName'] = empty( $_POST['amFirstName'] ) ? '' : $_POST['amFirstName'];
    $post_params['LName'] = empty( $_POST['amLastName'] ) ? '' : $_POST['amLastName'];
    $post_params['Email'] = empty( $_POST['amEmail'] ) ? '' : $_POST['amEmail'];
    $post_params['Phone1'] = empty( $_POST['amPhone'] ) ? '' : $_POST['amPhone'];
    //$post_params['Phone2'] = empty( $_POST['amPhone2'] ) ? '' : $_POST['amPhone2'];
    //$post_params['Fax'] = empty( $_POST['amFax'] ) ? '' : $_POST['amFax'];
    $post_params['DateOfBirth'] = empty( $_POST['amBirthDate'] ) ? '' : $_POST['amBirthDate'];
    $post_params['Gender'] = empty( $_POST['amGender'] ) ? '' : $_POST['amGender'];
    $post_params['PatientID'] = empty( $_POST['amPatientID'] ) ? '' : $_POST['amPatientID'];
    
    $post_params['ResAddress']['Suite'] = empty( $_POST['amSuite'] ) ? '' : $_POST['amSuite'];
    $post_params['ResAddress']['Floor'] = empty( $_POST['amFloor'] ) ? '' : $_POST['amFloor'];
    $post_params['ResAddress']['StreetNumber'] = empty( $_POST['amStreetNumber'] ) ? '' : $_POST['amStreetNumber'];
    $post_params['ResAddress']['StreetName'] = empty( $_POST['amStreetName'] ) ? '' : $_POST['amStreetName'];
    $post_params['ResAddress']['City'] = empty( $_POST['amCity'] ) ? '' : $_POST['amCity'];
    $post_params['ResAddress']['Province'] = empty( $_POST['amProvince'] ) ? '' : $_POST['amProvince'];
    // force Country to Canada for now
    $post_params['ResAddress']['Country'] = empty( $_POST['amCountry'] ) ? 'CA' : $_POST['amCountry'];
    $post_params['ResAddress']['PostalCode'] = empty( $_POST['amPostalCode'] ) ? '' : $_POST['amPostalCode'];
    
    //$temp_file = $_FILES['Signature']['tmp_name'];
    //$type = $_FILES['Signature']['type'];
    //$file_size = $_FILES['Signature']['size'];

    //$upload_directory = "C:\\Users\\muniat\\AppData\\Local\\Temp\\";
    //$new_file = $upload_directory.$_FILES['Signature']['name'];
    //move_uploaded_file($temp_file,$new_file);


    //$sig_file = file_get_contents($new_file);
    //$post_params['Signature'] = base64_encode($sig_file);

    if(isset($_FILES['Signature']) and !$_FILES['Signature']['error']){
      $temp_file = $_FILES['Signature']['tmp_name'];
      if(is_uploaded_file($temp_file)) {
        aLog($temp_file." is uploaded via HTTP POST");
      } else {
        aLog($temp_file." is not uploaded via HTTP POST");
      }
      $imageData = base64_encode(file_get_contents($temp_file));
      $post_params['Signature'] = $imageData;
    }
    
    // now send an API call to Airmed to create patient registration
    //aLog($post_params);
    $requestPath = '/API/Register/RegisterPatient/';
    $requestArray = airmed_call_request($requestPath,'POST',true,$post_params);
    //$requestArray = array();
    // get request response
    aLog("Post Response:");
    aLog($requestArray);

    if (array_key_exists('body',$requestArray)){
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];
    }
    else { //WP_error
      $errno = 0;
      $error_message = $requestArray['errors']['http_request_failed']['0'];
    }


    // no error proceed with displaying data
    if ($errno === 200) {
      
      //Create an array of objects from the JSON returned by the API
      /*
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
      */
      //echo "<div>Response: ".$response."</div>";
      //$response = str_replace('"','',$response);
      //airmed_setSession("__amRequestVerificationToken",$response);
      //airmed_setSession("__amPostData",$post_params);
      //if(isset($_SESSION['__RequestVerificationToken'])) {
      //  echo "<div>Token: ".$_SESSION['__RequestVerificationToken']."</div>";
      //} else {
      //  echo "<div>Session token not set</div>";
      //}
      // upon success, redirect to dashboard
    
      
      // add_query_arg(array('success'=>'true','register'=>'true'),airmed_pagelink('/airmed/airmed-dashboard/');
      airmed_setSession("__amSuccess","new_registration");
      
      airmed_global_updatePatient();
      
      // redirect to dashboard for completion
      //if (wp_redirect( add_query_arg('success','true',airmed_pagelink('/airmed/airmed-dashboard/')),301)){
      //if (wp_redirect( airmed_pagelink('/airmed/airmed-dashboard/'),301)){
      //  exit;
      //}
      //echo "{'error': $errno,'message':'$err_message','response':'$response'}";
      //aLog($errno);
      $response = array("response" => $response,"error" => $errno,"errmessage" => $err_message );
      echo json_encode($response);
    }
    else {
      /*
      airmed_setSession("__amPostData",$post_params);
      echo "<div>Response error ({$errno}):\n {$err_message}</div>";
      $response = str_replace('["','',$response);
      $response = str_replace('"]','',$response);
      echo "<div> $response </div>";
      if ($errno === 400){
        airmed_setSession("__amError",$response);
        
        //echo "<div>error $errno</div>";
        // go back to previous page to show error
        if ( wp_get_referer() ) {
          wp_safe_redirect( wp_get_referer() );
        }
      }
      */
      //echo "{'error': $errno,'message':'$err_message','response':'$response'}";
      //aLog($errno);
      $response = array("response" => $response,"error" => $errno,"errmessage" => $err_message );
      echo json_encode($response);

    }
  }  
  else {
     //echo "<div>Error: No Post Params</div>";
     //echo "{'error':'400','message':'No Post Params','response':''}";
     $errno = 400;
     $err_message = "No Post Params";
     $response = array("response" => "","error" => $errno,"errmessage" => $err_message );
     echo json_encode($response);

     aLog('No Post Params');
  }
}


?>