<?php
function airmed_login_shortcode($atts){
  //aLog('Login Shortcode');
  global $wp;
  $debug = true;
  $pageHTML = "";
  $sArgs = shortcode_atts(
    array(
      'returnUrl' => '',
    ),
    $atts
  );
  $returnUrl = esc_attr($sArgs['returnUrl']); 
  $test = '';
  $test2 = '';
  
  airmed_setSession('__amAuthToken','');
  //$_SESSION['__amAuthUser'] = '';
  //if(isset($wp->query_vars["airmed_prod_type"])){ $test = $wp->query_vars["airmed_prod_type"];}
  //if(isset($wp->query_vars["page_name"])){ $test2 = $wp->query_vars["page_name"];}
  
  //$airmed = AirMed::get_instance(); //added to use public functions from class
  //echo "<div>test: $test</div>";
  //echo "<div>test2: $test2</div>";

  // get slug name of page
  //$slug = get_post_field( 'post_name', get_post() );  // eg. wordpress/airmed/airmed-login/ returns airmed-login
  //$pageHTML.= "<div>Option: " . get_post_field( 'post_name', get_post() )."</div>";

  // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
  $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";

  $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));

  $pageHTML.="  <div class='airmed-content login'>";
  $pageHTML.="    <div class='row'>";
  $pageHTML.="      <div class='col col-12'>";
  $pageHTML.="        <div class='col-inner'>";
  $pageHTML.="          <h3>Sign in to your account</h3>";
  $pageHTML.="       </div>";
  $pageHTML.="     </div>";
  $pageHTML.="    </div>";
  $pageHTML.="    <div class='row'>";
  $pageHTML.="      <div class='col col-12'>";
  $pageHTML.="        <div class='am-login'>";
  if(!empty($_SESSION['__amError']) && ($_SESSION['__amError'] === "incorrect_login")) {
     $pageHTML.="<div class='alert alert-danger'>The username or password are incorrect. Please try again.</div>";
     $_SESSION['__amError'] = '';
  }
  if(!empty($_SESSION['__amSuccess']) && ($_SESSION['__amSuccess'] === "email_confirmation")) {
     $pageHTML.="<div class='alert alert-success'>Your e-mail has been confirmed. Please login.</div>";
     $_SESSION['__amSuccess'] = '';
  }
  $pageHTML.="          <form class='am-login-form form-horizontal' method='post' action='".esc_url(admin_url('admin-post.php'))."'>";
  $pageHTML.="            <div class='am-login-content col-md-6 col-12'>"; 
  $pageHTML.="              <p>Don't have an account? Go ahead, <a href='".airmed_pagelink('/airmed/airmed-new-account')."'>create one here.</a></p>";
  $pageHTML.="              <p>Forgot your password? You can <a href=''>reset it here</a>.</p>";
  $pageHTML.="            </div>";
  $pageHTML.="            <div class='am-login-form-content col-md-6 col-12'>";
  $pageHTML.="              <div class='mb-3'>";
  $pageHTML.="                <label for='amUsername'>Username</label>";
  $pageHTML.="                <input id='amUsername' name='amUsername' class='form-control' placeholder='Enter username' type='text' />";
  $pageHTML.="              </div>";
  $pageHTML.="              <div class='mb-3'>";
  $pageHTML.="                <label for='amPassword'>Password</label>";
  $pageHTML.="                <input id='amPassword' name='amPassword' type='password' class='form-control' placeholder='Password'/>";
  $pageHTML.="              </div>";
  $pageHTML.="              <div class='mb-3 form-check'>";
  $pageHTML.="                <input id='amShowPassword' type='checkbox' class='form-check-input'/>";
  $pageHTML.="                <label class='form-check-label' for='amShowPasswrod'>Show Password</label>";
  $pageHTML.="              </div>";
  $pageHTML.="              <input type='hidden' name='action' value='am_login_form' />";
  $pageHTML.="              <input type='hidden' name='returnUrl' value='$returnUrl' />";
  $pageHTML.="              <input type='hidden' name='query' value='".$_SERVER['QUERY_STRING']."' />";
  $pageHTML.="              <button type='submit' class='btn btn-primary'>Sign In</button>";
  $pageHTML.="            </div>";
  $pageHTML.="          </form";
  $pageHTML.="        </div>";
  $pageHTML.="      </div>";
  $pageHTML.="    </div>";
  $pageHTML.="  </div>";
  $pageHTML.= "</div>";  // end of airmed wrapper

  //$pageHTML.= includeModals();

  echo $pageHTML;
}// End airmed new acount shortcode

function airmed_new_account_shortcode(){
  global $wp;
  $debug = true;
  $pageHTML = "";
  $fname = "";
  $lname = "";
  $phone = "";
  $birthdate = "";
  $email = "";
  $emailconsent = "";
  $terms = "";
  $termsurl = "";
  $privacyurl = "";
  $applicationurl = "";
  $medicalurl = "";
  
  $success = "";
  
  //$locations = get_nav_menu_locations();
  //aLog($locations);
  
  //if(isset($wp->query_vars["success"])){ 
  //  $success = $wp->query_vars["success"];
  //}
  //$verification='';
  //if(isset($wp->query_vars["verification"])){ $verification = $wp->query_vars["verification"];}

  // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
  $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide new-account'>";
  
  $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
  
  //if ( ! empty( $_POST ) ) {
    // Sanitize the POST field
  //  $action = empty( $_REQUEST['action'] ) ? '' : $_REQUEST['action'];
  //  $pageHTML.="  <div class='row'>action: $action</div>";
  //}
  
  // if success show wait for email message
  if(!empty($_SESSION['__amSuccess']) && ($_SESSION['__amSuccess'] === "new_account")) {
    // if true show email sent to email used for verification
    $pageHTML.="  <div class='row'>";
    $pageHTML.="    <div class='col col-12'>";
    $pageHTML.="      <div class='col-inner'>";
    $pageHTML.="        <p class='text-start'>Thank you for signing up. A verification email has been sent to the email used for the new online account. <br> Please click the link within that email to finish the new account process and proceed to application.</p>";
    $pageHTML.="     </div>";
    $pageHTML.="   </div>";

    $_SESSION['__amSuccess'] = false;
  }
  else{  // no success, show form
    
    // get posted form data in case of 
    if(!empty($_SESSION['__amPostData']) && !empty($_SESSION['__amError'])){
      $post_params = $_SESSION['__amPostData'];
      $fname = $post_params['FName'];
      $lname = $post_params['LName'];
      $phone = $post_params['Phone1'];
      $birthdate = $post_params['DateOfBirth'];
      $email = $post_params['Email'];
      if ($post_params['EmailConsent'] === "true"){$emailconsent = "checked";}
      if ($post_params['AcceptedTerms'] === "true"){$terms = "checked";}
      //echo '<pre>';
      //print_r($post_params);
      //echo '</pre>';
      $_SESSION['__amPostData'] = '';
    }
    
    // Need to do a producer lookup to get their info and fill in variables
    $requestPath = '/API/Producer/GetProducer';
    $requestArray = airmed_call_request($requestPath,'GET',false,null);
    aLog($requestArray);
    // get request response
    //$response = $requestArray[0];
    // get request error
    //$err = $requestArray[1];
    //$errno = $requestArray[2];
    //$err_message = $requestArray[3];

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

      /*  "streetNumber": "55",
          "streetName": "Quarterman Rd",
          "suite": null,
          "floor": null,
          "streetAddress2": null,
          "streetAddress3": null,
          "city": "Guelph",
          "province": "ON",
          "country": "CA",
          "postalCode": "N1C 1C2"
       */
      $mailAddress = "";
      if (!empty($jsonObj->mailAddress->suite)){$mailAddress.= "#".$jsonObj->mailAddress->suite." - ";}
      if (!empty($jsonObj->mailAddress->streetNumber)){$mailAddress.= $jsonObj->mailAddress->streetNumber." ";}
      if (!empty($jsonObj->mailAddress->streetName)){$mailAddress.= $jsonObj->mailAddress->streetName;}
      if (!empty($jsonObj->mailAddress->floor)){$mailAddress.= ", Flr ".$jsonObj->mailAddress->floor;}
      $mailAddress.= ", ".$jsonObj->mailAddress->city;
      $mailAddress.= ", ".$jsonObj->mailAddress->province;
      if (!empty($jsonObj->mailAddress->country)){$mailAddress.= ", ".$jsonObj->mailAddress->country;}
      $mailAddress.= " ".$jsonObj->mailAddress->postalCode;
      
      $shipAddress = "";
    
      $termsurl = $jsonObj->termsAndConditionsLink;
      $privacyurl = $jsonObj->privacyPolicyLink;
      //$applicationurl = $jsonObj->ApplicationFormsLink;
      //$medicalurl = $jsonObj->MedicalDocumentLink;
    
      // new account or post not done with success
      $pageHTML.="  <div class='airmed-content'>";
      $pageHTML.="    <div class='am-page-title row pb-0'>";
      $pageHTML.="      <div class='col col-12'>";
      $pageHTML.="        <div class='col-inner'>";
      $pageHTML.="          <h1>Create New Account</h1>";
      $pageHTML.= airmed_application_step_progress(1);
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

      $pageHTML.="    <div class='row justify-content-md-center'>";
      $pageHTML.="      <div class='col-lg-10 col-xl-8'>";
      $pageHTML.="        <div class='am-account'>";
      if(!empty($_SESSION['__amError'])){
         if ($_SESSION['__amError'] === "incorrect_missing_field") {
           $pageHTML.="<div class='alert alert-danger'>Not all fields were filled. Please re-fill and submit.</div>";
         }
         else {
           $sError = $_SESSION['__amError'];
           $pageHTML.="<div class='alert alert-danger'>$sError</div>";
         }
         $_SESSION['__amError'] = '';
      }
      $pageHTML.="          <form class='am-account-form' method='post' action='".esc_url(admin_url('admin-post.php'))."'>";
      $pageHTML.="            <div class='am-account-form-content col-12'>";
      $pageHTML.="              <div class='card'>";
      $pageHTML.="              <div class='card-body'>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-12'>";
      $pageHTML.="                  <span class='text-red'>All fields are required</span>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-3 col-form-label'>";
      $pageHTML.="                  <label class='input-required' for='amFirstName'>First Name</label>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='col-sm-9'>";
      $pageHTML.="                  <input id='amFirstName' name='amFirstName' class='form-control' type='text' required='' value='$fname'/>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-3 col-form-label'>";
      $pageHTML.="                  <label class='input-required' for='amLastName'>Last Name</label>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='col-sm-9'>";
      $pageHTML.="                  <input id='amLastName' name='amLastName' class='form-control' type='text' required='' value='$lname'/>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-3 col-form-label'>";
      $pageHTML.="                  <label class='input-required' for='amPhone'>Phone</label>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='col-sm-9'>";
      //$pageHTML.="                  <input id='amPhone' name='amPhone' class='form-control phone' type='tel' pattern='\([0-9]{3}\)[0-9]{3}-[0-9]{4}' required='' value='$phone'/><small>( Format: 123-456-7890 )</small>";
      $pageHTML.="                  <input id='amPhone' name='amPhone' class='form-control phone' type='tel' pattern='\(\d{3}\)[\s]\d{3}-\d{4}' required='' value='$phone' placeholder='(555) 555-5555' maxlength='14'/>";
      //$pageHTML.="                  <small>( Format: (123)456-7890 )</small>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-3 col-form-label'>";
      $pageHTML.="                  <label class='input-required' for='amBirthDate'>Birth Date</label>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='col-sm-9'>";
      $pageHTML.="                  <input id='amBirthDate' name='amBirthDate' class='form-control' type='date' required='' value='$birthdate'/>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-3 col-form-label'>";
      $pageHTML.="                  <label class='input-required' for='amEmail'>Email</label>";
      $pageHTML.="                  <a id='airmed-username' class='am-popover' tabindex='0' role='button' data-container='body' data-am-toggle='popover' data-am-trigger='focus' title='' data-placement='right' data-original-title='Email / Username Help'>";
      //$pageHTML.="                    <i  class='far fa-question-circle'></i>";
      $pageHTML.="                    <i  class='dashicons dashicons-editor-help'></i>";
      $pageHTML.="                  <div id='poc-airmed-username' class='am-popovertext'>The email address you specify in this field will also be used as your username to login to the website.</div>";
      $pageHTML.="                  </a>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='col-sm-9'>";
      $pageHTML.="                  <input id='amEmail' name='amEmail' class='form-control' type='email' required='' value='$email'/>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-3 col-form-label pos-relative'>";
      $pageHTML.="                  <label for='amPasswod' class='input-required'>Password</label>";
      $pageHTML.="                  <a id='airmed-pwd' class='am-popover' tabindex='0' role='button' data-container='body' data-am-toggle='popover' data-am-trigger='focus' title='' data-placement='right' data-original-title='Password Help'>";
      //$pageHTML.="                    <i  class='far fa-question-circle'></i>";
      $pageHTML.="                    <i  class='dashicons dashicons-editor-help'></i>";
      $pageHTML.="                    <div id='poc-airmed-pwd' class='am-popovertext'>";
      $pageHTML.="                      <ul>";
      $pageHTML.="                        <li><small>Password must be a minimum of 6 characters long.</small></li>";
      $pageHTML.="                        <li><small>Password must contain at least one character that is not a letter or number (eg. &amp;, ^, *, -, _, %, #, $ ).</small></li>";
      $pageHTML.="                        <li><small>Password must contain at least one digit (0-9).</small></li>";
      $pageHTML.="                        <li><small>Password must contain at least one lowercase letter. (eg. a, b, c)</small></li>";
      $pageHTML.="                        <li><small>Password must contain at least one uppercase letter. (eg. A, B, C)</small></li>";
      $pageHTML.="                      </ul>";
      $pageHTML.="                    </div>";
      $pageHTML.="                  </a>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='col-sm-9'>";
      $pageHTML.="                  <input id='amPassword' name='amPassword' type='password' class='form-control' required pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,20}$' data-val-regex-pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,20}$'/>";
      $pageHTML.="                  <div class='invalid-feedback'>The password has invalid requirements</div>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-3 col-form-label'>";
      $pageHTML.="                  <label for='amConfirmPasswrod' class='input-required'>Confirm Password</label>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='col-sm-9'>";
      $pageHTML.="                  <input id='amConfirmPassword' name='amConfirmPassword' type='password' class='form-control' required readonly pattern='^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,20}$'/>";
      $pageHTML.="                  <div class='invalid-feedback'>The passwords do not match</div>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3 form-check'>";
      $pageHTML.="                <div class='col-sm-9 offset-sm-3'>";
      $pageHTML.="                  <input id='amShowPassword' type='checkbox' class='form-check-input'/>";
      $pageHTML.="                  <label class='form-check-label' for='amShowPasswrod'>Show Password</label>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-12'>";
      $pageHTML.="                  <div class='form-group card card-body'>";
      $pageHTML.="                    <div class='mb-3 form-check'>";
      $pageHTML.="                      <input id='amConsent' name='amEmailConsent' type='checkbox' class='form-check-input' $emailconsent/>";
      $pageHTML.="                      <label class='form-check-label' for='amConsent'>I consent to receive email notifications that will keep me up to date with my application status, order details and product information.</label>";
      $pageHTML.="                    </div>";
      $pageHTML.="                    <div class='mb-3 form-check'>";
      $pageHTML.="                      <input id='amAccepted' name='amAcceptedTerms' type='checkbox' class='form-check-input' required $terms/>";
      $pageHTML.="                      <label class='form-check-label' for='amAccepted'>I have read and agree with the Terms and Conditions.</label>";
      $pageHTML.="                    </div>";
      $pageHTML.="                    <div class=''>";
      $pageHTML.="                      <a href='$termsurl' class='btn btn-sm btn-outline-primary'>Terms and Conditions</a>";
      $pageHTML.="                      <a href='$privacyurl' class='btn btn-sm btn-outline-primary'>Privacy Policy</a>";
      $pageHTML.="                    </div>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-12'>";
      $pageHTML.="                  <input type='hidden' name='amProducerID' value='$jsonObj->id' required />";
      $pageHTML.="                  <input type='hidden' id='GoogleCaptchaToken' name='GoogleCaptchaToken' />";
      $pageHTML.="                  <input type='hidden' name='action' value='am_new_account_form' />";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='row mb-3'>";
      $pageHTML.="                <div class='col-sm-12'>";
      $pageHTML.="                  <button type='submit' class='btn btn-primary'>Submit</button>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="          </form";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";
      $pageHTML.="    </div>";
      $pageHTML.="    <script src='https://www.google.com/recaptcha/api.js?render=6LeP5XQcAAAAAJfXEeAAbpJl0LGVxTBYzNrS5yku'></script>";
      $pageHTML.="  </div>";

      $pageHTML.= airmed_modal_application_steps($jsonObj,1);

    } // end of request error check
    else{
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
  $pageHTML.= "</div>";  // end of airmed wrapper

  //$pageHTML.= includeModals();

  echo $pageHTML;

}// End airmed login shortcode

function airmed_new_application_shortcode(){
  global $wp;
  $debug = true;
  $pageHTML = "";
  $hasToken = false;
  $success = false;
  $termsurl = "";
  $privacyurl = "";
  $applicationurl = "";
  $medicalurl = "";
  $fname = "";
  $lname = "";
  $phone = "";
  $birthdate = "";
  $gender = "";
  $email = "";
  $suite = "";
  $floor = "";
  $streetNumber = "";
  $streetName = "";
  $address2 = "";
  $city = "";
  $province = "";
  $postal = "";
  
  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}
  
  if ($hasToken){
    if(isset($wp->query_vars["success"])){ 
      $success = $wp->query_vars["success"];
    }
    // Need to do a producer lookup to get their info and fill in variables
    $requestPath = '/API/Producer/GetProducer';
    $requestArray = airmed_call_request($requestPath,'GET',false,null);
    // get request response
    //$response = $requestArray[0];
    // get request error
    //$err = $requestArray[1];
    //$errno = $requestArray[2];
    //$err_message = $requestArray[3];
     
    //print_r( $requestArray);
    //$errno = 0;
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
    $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide new-account'>";

    $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
    
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

      // get posted form data in case of error
      if(!empty($_SESSION['__amPostData']) && !empty($_SESSION['__amError'])){
        $post_params = $_SESSION['__amPostData'];
        $fname = $post_params['FName'];
        $lname = $post_params['LName'];
        $phone = $post_params['Phone1'];
        $birthdate = $post_params['DateOfBirth'];
        $gender = $post_params['Gender'];
        $email = $post_params['Email'];
        $patientID = $post_params['PatientID'];
        $suite = $post_params['Suite'];
        $floor = $post_params['Floor'];
        $streetNumber = $post_params['StreetNumber'];
        $streetName = $post_params['StreetName'];
        $city = $post_params['City'];
        $province = $post_params['Province'];
        $country = $post_params['Country'];
        $postal = $post_params['PostalCode'];

        //echo '<pre>';
        //print_r($post_params);
        //echo '</pre>';
        $_SESSION['__amPostData'] = '';
      }


      $mailAddress = "";
      if (!empty($jsonObj->mailAddress->suite)){$mailAddress.= "#".$jsonObj->mailAddress->suite." - ";}
      if (!empty($jsonObj->mailAddress->streetNumber)){$mailAddress.= $jsonObj->mailAddress->streetNumber." ";}
      if (!empty($jsonObj->mailAddress->streetName)){$mailAddress.= $jsonObj->mailAddress->streetName;}
      if (!empty($jsonObj->mailAddress->floor)){$mailAddress.= ", Flr ".$jsonObj->mailAddress->floor;}
      $mailAddress.= ", ".$jsonObj->mailAddress->city;
      $mailAddress.= ", ".$jsonObj->mailAddress->province;
      if (!empty($jsonObj->mailAddress->country)){$mailAddress.= ", ".$jsonObj->mailAddress->country;}
      $mailAddress.= " ".$jsonObj->mailAddress->postalCode;
      
      $shipAddress = "";
    
      $termsurl = $jsonObj->termsAndConditionsLink;
      $privacyurl = $jsonObj->privacyPolicyLink;
      $applicationurl = $jsonObj->applicationFormsLink;
      $medicalurl = $jsonObj->medicalDocumentLink;

      // **** Get Patient Info
      // Get Patient Info based on auth token
      
      $requestPath = '/API/Patient/Get';
      $requestArray = airmed_call_request($requestPath,'GET',null);

      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];
      //Create an array of objects from the JSON returned by the API
      $jsonObjPat = json_decode($response);
      $patientID = $jsonObjPat->patientID;
      
      //$patientID = 'PATI2021092810124010000021';
      

      $pageHTML.="  <div class='airmed-content'>";
      
      $pageHTML.="    <div class='am-page-title row pb-0'>";
      $pageHTML.="      <div class='col col-12'>";
      $pageHTML.="        <div class='col-inner'>";
      $pageHTML.="          <h1>Patient Application</h1>";
      $pageHTML.= airmed_application_step_progress(2);
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
      
      $pageHTML.="    <div class='row justify-content-md-center'>";
      $pageHTML.="      <div class='col-lg-10 col-xl-7'>";
      $pageHTML.="        <div class='am-account'>";
      if(!empty($_SESSION['__amError'])){
         if ($_SESSION['__amError'] === "incorrect_missing_field") {
           $pageHTML.="<div class='alert alert-danger'>Not all fields were filled. Please re-fill and submit.</div>";
         }
         else {
           $sError = $_SESSION['__amError'];
           $pageHTML.="<div class='alert alert-danger'>$sError</div>";
         }
         $_SESSION['__amError'] = '';
      }
      $pageHTML.="          <form class='am-account-form' method='post' action='".esc_url(admin_url('admin-post.php'))."' data-redirect='".add_query_arg(array('success'=>'true','register'=>'true'),airmed_pagelink('/airmed/airmed-dashboard/'))."'>";

      // new ajax post method - form action used for ajax url
      //$pageHTML.="          <form class='am-account-form' method='post' action='".getAirmedAPIHost()."/API/Register/RegisterPatient/' data-redirect='".add_query_arg(array('success'=>'true','register'=>'true'),airmed_pagelink('/airmed/airmed-dashboard/'))."'>";
      
      $pageHTML.="            <div class='am-registration-form-content col-12'>";
      $pageHTML.="              <div class='card'>";
      $pageHTML.="              <div class='card-body'>";
      $pageHTML.="                <div class='row mb-4'>";
      $pageHTML.="                  <div class='col-sm-12'>";
      $pageHTML.="                    <span >Please fill out the online registration form below to complete your application</span>";
      $pageHTML.="                  </div>";
      $pageHTML.="                  <div class='col-sm-12'>";
      $pageHTML.="                    <span class='text-red'>* - Indicates required field</span>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <h4>Patient Information</h4>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amFirstName'>First Name</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      // Pre filled from login account
      $pageHTML.="                    <input id='amFirstName' name='amFirstName' class='form-control' type='text' required='' value='$fname' />";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amLastName'>Last Name</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      // Pre filled from login account
      $pageHTML.="                    <input id='amLastName' name='amLastName' class='form-control' type='text' required='' value='$lname'/>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amBirthDate'>Birth Date</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <input id='amBirthDate' name='amBirthDate' class='form-control' type='date' required='' value='$birthdate' />";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amGender'>Gender</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <select id='amGender' name='amGender' class='form-control' required='' />";
      $selected = ($gender === '') ? 'selected' : '';
      $pageHTML.="                      <option value='' $selected> - Select Gender - </option>";
      $selected = ($gender === 'M') ? 'selected' : '';
      $pageHTML.="                      <option value='M' $selected>Female</option>";
      $selected = ($gender === 'F') ? 'selected' : '';
      $pageHTML.="                      <option value='F' $selected>Male</option>";
      $selected = ($gender === 'Other') ? 'selected' : '';
      $pageHTML.="                      <option value='Other' $selected>Other</option>";
      $selected = ($gender === 'Undisclosed') ? 'selected' : '';
      $pageHTML.="                      <option value='Undisclosed' $selected>Undisclosed</option>";
      $pageHTML.="                    </select>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amPhone'>Phone</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      //$pageHTML.="                    <input id='amPhone' name='amPhone' class='form-control phone' type='tel' pattern='[0-9]{3}-[0-9]{3}-[0-9]{4}' required=''  value='$phone'/><small>( Format: 123-456-7890 )</small>";
      $pageHTML.="                    <input id='amPhone' name='amPhone' class='form-control phone' type='tel' pattern='\(\d{3}\)[\s]\d{3}-\d{4}' required=''  value='$phone'  placeholder='(555) 555-5555' maxlength='14'/>";
      //$pageHTML.="                    <small>( Format: (123)456-7890 )</small>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-4'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amEmail'>Email</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      // Pre filled from login account
      $pageHTML.="                    <input id='amEmail' name='amEmail' class='form-control' type='email' required=''  value='$email'/>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      
      //$pageHTML.="                <div class='row mb-3'>";
      //$pageHTML.="                  <div class='col-sm-12'>";
      //$pageHTML.="                    <h4>Residing Address</h4>";
      //$pageHTML.="                  </div>";
      //$pageHTML.="                </div>";
      
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                    <h4>Residing Address</h4>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label' for='amSuite'>Suite</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <input id='amSuite' name='amSuite' class='form-control' type='text' value='$suite'/>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label' for='amFloor'>Floor</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <input id='amFloor' name='amFloor' class='form-control' type='text' value='$floor'/>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amStreetNumber'>Street Number</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <input id='amStreetNumber' name='amStreetNumber' class='form-control' type='text' required=''  value='$streetNumber'/>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amStreetName'>Street Name</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <input id='amStreetName' name='amStreetName' class='form-control' type='text' required='' value='$streetName' />";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label' for='amAddress2'>Address 2</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <input id='amAddress2' name='amAddress2' class='form-control' type='text' value='$address2'/>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amCity'>City</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <input id='amCity' name='amCity' class='form-control' type='text' required='' value='$city' />";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amProvince'>Province</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <select id='amProvince' name='amProvince' class='form-control' required='' />";
      $selected = ($province === '') ? 'selected' : '';
      $pageHTML.="                      <option value='' $selected> - Select Province - </option>";
      $selected = ($province === 'AB') ? 'selected' : '';
      $pageHTML.="                      <option value='AB' $selected>Alberta</option>";
      $selected = ($province === 'BC') ? 'selected' : '';
      $pageHTML.="                      <option value='BC' $selected>British Columbia</option>";
      $selected = ($province === 'MB') ? 'selected' : '';
      $pageHTML.="                      <option value='MB' $selected>Manitoba</option>";
      $selected = ($province === 'NB') ? 'selected' : '';
      $pageHTML.="                      <option value='NB' $selected>New Brunswick</option>";
      $selected = ($province === 'NL') ? 'selected' : '';
      $pageHTML.="                      <option value='NL' $selected>Newfoundland and Labrador</option>";
      $selected = ($province === 'NS') ? 'selected' : '';
      $pageHTML.="                      <option value='NS' $selected>Nova Scotia</option>";
      $selected = ($province === 'NT') ? 'selected' : '';
      $pageHTML.="                      <option value='NT' $selected>Northwest Territories</option>";
      $selected = ($province === 'NU') ? 'selected' : '';
      $pageHTML.="                      <option value='NU' $selected>Nunavut</option>";
      $selected = ($province === 'ON') ? 'selected' : '';
      $pageHTML.="                      <option value='ON' $selected>Ontario</option>";
      $selected = ($province === 'PE') ? 'selected' : '';
      $pageHTML.="                      <option value='PE' $selected>Prince Edward Island</option>";
      $selected = ($province === 'QC') ? 'selected' : '';
      $pageHTML.="                      <option value='QC' $selected>Quebec</option>";
      $selected = ($province === 'SK') ? 'selected' : '';
      $pageHTML.="                      <option value='SK' $selected>Saskatchewan</option>";
      $selected = ($province === 'YT') ? 'selected' : '';
      $pageHTML.="                      <option value='YT' $selected>Yukon</option>";
      $pageHTML.="                    </select>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-4'>";
      $pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amPostalCode'>Postal Code</label>";
      $pageHTML.="                  <div class='col-sm-8'>";
      $pageHTML.="                    <input id='amPostalCode' name='amPostalCode' class='form-control' type='text' required='' value='$postal' />";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-4'>";
      $pageHTML.="                  <div class='col-sm-12'>";
      $pageHTML.="                    <div class='card card-body'>";
      $pageHTML.="                      <h4>Applicant Attestation</h4>";
      $pageHTML.="                        <ul>";
      $pageHTML.="                          <li>";
      $pageHTML.="                            The applicant acknowledges that some of the information provided in this document may be shared with Health Canada, our service providers, Veterans Affairs, and/or insurance providers, as applicable, solely for the purposes of providing service support.";
      $pageHTML.="                          </li><li>";
      $pageHTML.="                            The applicant gives ABC Medicinals Inc. permission to share their ordering information with their prescribing physician and/or the clinic through which they received their consultation.";
      $pageHTML.="                          </li><li>";
      $pageHTML.="                            The applicant ordinarily resides in Canada.";
      $pageHTML.="                          </li><li>";
      $pageHTML.="                             The information in the application and the Supporting Document is correct and complete.";
      $pageHTML.="                          </li><li>";
      $pageHTML.="                             The Supporting Document is not being used to seek or obtain dried or fresh marijuana or cannabis oil from another source.";
      $pageHTML.="                          </li><li>";
      $pageHTML.="                             For applicants applying using a Registration Certificate: The application is for the purpose of obtaining an interim supply of fresh or dried marijuana or cannabis oil.";
      $pageHTML.="                          </li><li>";
      $pageHTML.="                            For applicants applying using a Medical Document: The original of the Medical Document accompanies the application.";
      $pageHTML.="                          </li><li>";
      $pageHTML.="                             The applicant will use dried marihuana or cannabis oil only for their own medical purposes.";
      $pageHTML.="                          </li>";
      $pageHTML.="                        </ul>";
      $pageHTML.="                      <span class='text-danger'><i><small>** &quot;Supporting Document&quot; refers to either a signed Medical Document or a Registration Certificate issued by Health Canada.</small></i></span>";
      $pageHTML.="                    </div>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <h4>Signature</h4>";
      //$pageHTML.="                  <label class='col-sm-4 col-form-label input-required' for='amSignature'>Digital Signature</label>";
      $pageHTML.="                  <div class='col-12'>";
      $pageHTML.="                    <canvas id='amSignature-canvas' > </canvas>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <div class='col-sm-12'>";
      //$pageHTML.="                    <button id='amSignature-save'>Save</button>";
      $pageHTML.="                    <button class='btn btn-warning' id='amSignature-clear'>Clear</button>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <div class='col-sm-12'>";
      $pageHTML.="                    <div class='card card-body'>";
      $pageHTML.="                      A digital signature is required for this application.  Please write your signature using your mouse, finger or pen in the box above.";
      $pageHTML.="                    </div>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='row mb-3'>";
      $pageHTML.="                  <div class='col-sm-12'>";
      $pageHTML.="                    <input type='hidden' name='action' value='am_application_form' />";
      $pageHTML.="                    <input type='hidden' name='amPatientID' value='$patientID' />";
      
      $pageHTML.="                    <button type='submit' class='btn btn-primary'>Sign Up</button>";
      $pageHTML.="                    <span class='registering-user text-danger' style='display: none;'>Creating your account ... ";
      $pageHTML.="                      <img class='loader' src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif' id='airmed-modal-loading-indicator'>";
      $pageHTML.="                    </span>";
      $pageHTML.="                  </div>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      /*
      $pageHTML.="            <div class='am-account-info-content  col-md-6'>";
      $pageHTML.="              <div class='card'>";
      $pageHTML.="              <div class='card-body'>";
      $pageHTML.="                <h3>Sign Up Process</h3>";
      $pageHTML.="                <div class='signup-process'>";
      $pageHTML.="                  <div class='row'>";
      $pageHTML.="                    <div class='col-xl-3 col-lg-4 steps'>";
      $pageHTML.="                      <h4>Step 1</h4>";
      $pageHTML.="                      <div class='step-progress'>";
      $pageHTML.="                        <i class='dashicons dashicons-yes-alt fs-3x text-green'></i><br>";
      //$pageHTML.="                        <i class='far fa-3x fa-check-circle text-green'></i><br>";
      $pageHTML.="                        <span class='text-green'>Completed</span>";
      $pageHTML.="                      </div>";
      $pageHTML.="                    </div>";
      $pageHTML.="                    <div class='col-xl-9 col-lg-8 process-text'>";
      $pageHTML.="                      <h5>Sign up with $jsonObj->name</h5>";
      $pageHTML.="                      <p>To create an account simply fill out your information on this page. An account will allow you to view full product details.</p>";
      $pageHTML.="                      <p class='notes'>*By creating an account you are under NO obligation to complete the registration process with $jsonObj->name.</p>";
      $pageHTML.="                    </div>";
      $pageHTML.="                  </div>";
      $pageHTML.="                  <div class='row'>";
      $pageHTML.="                    <div class='col-xl-3 col-lg-4 steps'>";
      $pageHTML.="                      <h4>Step <span class='fw-700'>2</span></h4>";
      $pageHTML.="                    </div>";
      $pageHTML.="                    <div class='col-xl-9 col-lg-8 process-text'>";
      $pageHTML.="                      <h5>Complete Application Process</h5>";
      $pageHTML.="                      <p>Once you have registered and logged into the site, you can complete your application by filling out a simple online form which will accompany your medical document.</p>";
      $pageHTML.="                      <p>PDF copies of our forms can be downloaded <a href='$applicationurl'>here</a>. Please send paper forms to us at $mailAddress</p>";
      $pageHTML.="                      <p class='notes'>*Please use courier or mail via Canada Post.</p>";
      $pageHTML.="                    </div>";
      $pageHTML.="                  </div>";
      $pageHTML.="                  <div class='row'>";
      $pageHTML.="                    <div class='col-xl-3 col-lg-4 steps'>";
      $pageHTML.="                      <h4>Step <span class='fw-700'>3</span></h4>";
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
      */
      $pageHTML.="          </form";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";
      $pageHTML.="    </div>";
      $pageHTML.="  </div>";

      $pageHTML.= airmed_modal_application_steps($jsonObj,2);

      $pageHTML.="<script src='https://cdn.jsdelivr.net/npm/signature_pad@2.3.2/dist/signature_pad.min.js'></script>";

    }
    else{
      //$error_message = curl_strerror($errno);
      echo "<div>New Application - request error ({$errno}):\n {$err_message} </div>";
      if ($debug) echo "<div>request Error: $err </div>";
      else echo "<div> $err </div>";

    }
    $pageHTML.= "</div>";  // end of airmed wrapper

    //$pageHTML.= includeModals();

    echo $pageHTML;
  }
  else { // redirect to login
    //if (wp_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-application'));
  }

}// End airmed application shortcode

function airmed_confirm_email_shortcode(){
  global $wp;
  $debug = true;
  $pageHTML = "";
  
  $userid = "";
  $code = "";
  // check for passed in parameters for email link
  if(isset($wp->query_vars["userId"]) && isset($wp->query_vars["code"])){ 
    $userid = $wp->query_vars["userId"];
    $code = $wp->query_vars["code"];
    
    // this is not ideal.. the special chars should be coming as url encoded
    //$code = urlencode(str_replace(" ", "+",$code));
    //$code = str_replace("\/ ", "/",$code);

    //print(urldecode($code));
    // get post params read for Confirm Email Post
    $post_params = array();
    // add to array
    $post_params['ID']=$userid;
    $post_params['Code']=$code;

    // now send an API call to Airmed to confirm email
    $requestPath = '/API/Account/ConfirmPatientEmail/';
    $requestArray = airmed_call_request($requestPath,'POST',false,$post_params);

    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'].' - '.$response;
  }
  else {
    // if not both set, force error
    $errno = 400;
    $err = "Bad Request";
    $err_message = $err." - missing userid or code";
  }

  // no error proceed with displaying data
  if ($errno === 200) {
    airmed_setSession("__amSuccess","email_confirmation");

    // upon success, redirect to dashboard
    //if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array());
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

function airmed_dashboard_shortcode(){
  global $wp;
  $debug = true;
  $pageHTML = "";
  $success = false;
  $register = false;
  $hasRegistered = false;
  //$hasToken = false;

  $airmed = new stdClass();
  $airmed = $_SESSION['__airmed'];

  //if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}

  
  if ($airmed->hasToken){
    // going to need to look up stuff from the patient to determine their online account stage to determine what to do
    //$requestPath = '/API/Patient/Get/';
    //$requestArray = airmed_call_request($requestPath,'GET',true,null);
    //$response = $requestArray['body'];
    //$err = $requestArray['response']['message'];
    //$errno = $requestArray['response']['code'];
    //$err_message = $requestArray['response']['message'];
    
    // no error proceed with displaying data
    //if ($errno === 200) {
    if(!is_null($airmed->patient)){
      //Create an array of objects from the JSON returned by the API
      //$jsonObj = json_decode($response);
      $jsonObj = $airmed->patient;
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

      if(isset($wp->query_vars["success"])){ 
        $success = $wp->query_vars["success"];
      }
      if(isset($wp->query_vars["register"])){ 
        $register = $wp->query_vars["register"];
      }

      $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
      //$pageHTML.= airmed_topmenu_shortcode(array('embed' => true, 'patObj' => json_encode($jsonObj)));
      $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
      
      // if application registration created
      if(!$jsonObj->canApply){

        $pageHTML.="  <div class='airmed-dashboard-content'>";

        // medical document not received or processed
        if(!$jsonObj->canPurchase){
          // ***** Provide successful registration message
          
          //$pageHTML.="<section id='airmed-generic' class=''>";
          //$pageHTML.="  <div class='container-fluid'>";
          //$pageHTML.="    <div class='row'>";
          //$pageHTML.="      <div class='col-sm-6'>";

          $pageHTML.= dashboard_application_medical_documents();

          //$pageHTML.="      </div>";
          //$pageHTML.="    </div>";
          //$pageHTML.="  </div>";
          //$pageHTML.="</section>";
          
        }
        else { // show regular dashboard as user has full access

          $pageHTML.="<section id='airmed-generic' class=''>";
          $pageHTML.="  <div class='container-fluid'>";
          $pageHTML.="    <div class='row'>";
          $pageHTML.="      <div class='col-sm-6'>";

          $pageHTML.= dashboard_client_details($jsonObj);

          $pageHTML.="      </div>";
          $pageHTML.="      <div class='col-sm-6'>";
          $pageHTML.="        <div class='row'>";
          $pageHTML.="          <div class='col-sm-12'>";

          $pageHTML.= dashboard_order_products($jsonObj);
          $pageHTML.="          </div>";
          $pageHTML.="        </div>";
          
          $pageHTML.="        <div class='row'>";
          $pageHTML.="          <div class='col-sm-12'>";
          $pageHTML.= dashboard_order_progress($jsonObj);
          $pageHTML.="          </div>";
          $pageHTML.="        </div>";

          $pageHTML.="      </div>";
          $pageHTML.="    </div>";
          $pageHTML.="  </div>";
          $pageHTML.="</section>";
          
        }

        $pageHTML.="  </div>";

      }
      else {  // Need to register an application

        $pageHTML.="  <div class='airmed-application-content'>";
        $pageHTML.="    <div class='row'>";
        $pageHTML.="      <div class='col col-12'>";
        $pageHTML.="        <div class='col-inner'>";
        $pageHTML.="          <h4>Welcome to Step 2</h4>";
        $pageHTML.="       </div>";
        $pageHTML.="     </div>";
        $pageHTML.="    </div>";
        // email just confirmed
        if(!empty($_SESSION['__amSuccess']) && $_SESSION['__amSuccess'] === 'email_confirmation'){
          $pageHTML.="    <div class='row'>";
          $pageHTML.="      <div class='col col-12'>";
          $pageHTML.="        <p>Now that you have confirmed your email, it is time to complete your application by providing your address and signing, and sending in your medical documents. You will be able to order when your medical document is received and the account is approved.</p>";
          $pageHTML.="      </div>";
          $pageHTML.="    </div>";
          $_SESSION['__amSuccess'] = '';
        }
        else{
          $pageHTML.="    <div class='row'>";
          $pageHTML.="      <div class='col col-12'>";
          $pageHTML.="        <p>You require a valid application. Please apply by providing your address and signing, and sending in your medical documents. You will be able to order when your medical document is received and the application is approved.</p>";
          $pageHTML.="      </div>";
          $pageHTML.="    </div>";
        }
        $pageHTML.="    <div class='row'>";
        $pageHTML.="      <div class='col col-12 col-md-6'>";
        $pageHTML.="        <div class='card airmed-application-card'>";
        $pageHTML.="          <div class='card-body'>";
        $pageHTML.="            <div class='text-center'>";
        $pageHTML.="              <p>";
        $pageHTML.="                <a href='".airmed_pagelink('/airmed/airmed-new-application')."' class='navigation_button btn btn-primary'>";
        //$pageHTML.="                  <i class='far fa-id-card'></i>";
        //$pageHTML.="                  <i class='dashicons dashicons-id'></i>";
        $pageHTML.="                  <span>Apply Online</span>";
        $pageHTML.="                </a>";
        $pageHTML.="              </p>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='card card-body'>";
        $pageHTML.="              <h4>Quick Application</h4>";
        $pageHTML.="              <p>";
        $pageHTML.="                <span class='registration-text'>Quick application should be used by most individuals.  Please ensure that you meet the following conditions, then click the button above to apply online.</span>";
        $pageHTML.="              </p>";
        $pageHTML.="              <div class='registration-conditions'>";
        $pageHTML.="                <ul>";
        $pageHTML.="                  <li>Product will be shipped directly to your primary residence</li>";
        $pageHTML.="                  <li>Your primary residence is NOT a hostel, shelter or care facility</li>";
        $pageHTML.="                  <li>Product will NOT be delivered to your Medical Practitioner</li>";
        $pageHTML.="                  <li>You do NOT have a caregiver</li>";
        $pageHTML.="                  <li>You have NOT registered with Health Canada</li>";
        $pageHTML.="                </ul>";
        $pageHTML.="              </div>";
        $pageHTML.="            </div>";
        $pageHTML.="          </div>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>";  // end of first column
        $pageHTML.="      <div class='col col-12 col-md-6'>";
        $pageHTML.="        <div class='card airmed-application-card'>";
        $pageHTML.="          <div class='card-body'>";
        $pageHTML.="            <div class='text-center'>";
        $pageHTML.="              <p>";
        $pageHTML.="                <a href='".airmed_pagelink('/airmed/airmed-application')."' class='navigation_button btn btn-primary'>";
        //$pageHTML.="                  <i class='fas fa-print'></i>";
        $pageHTML.="                  <span>Print Application Form(s)</span>";
        $pageHTML.="                </a>";
        $pageHTML.="              </p>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='card card-body'>";
        $pageHTML.="              <h4>Expanded Application</h4>";
        $pageHTML.="              <div class='registration-text'>";
        $pageHTML.="                <p>Expanded application is necessary for individuals that have additional requirements for delivery of their product.</p>";
        $pageHTML.="                <p>If you do not meet the conditions for the quick application process, or you simply want to print out the application forms, please click on the &quot;Print Application Form(s)&quot; button above.</p>";
        $pageHTML.="              </div>";
        $pageHTML.="            </div>";
        $pageHTML.="          </div>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>"; // end of second column
        $pageHTML.="    </div>";
        $pageHTML.="  </div>";

        //$pageHTML.= "</div>";  // end of airmed wrapper
      }  
      $pageHTML.= "</div>";  // end of airmed wrapper
  
      //$pageHTML.= includeModals();

      echo $pageHTML;
    }
    else{
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
  else { // redirect to login
    //if (wp_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-dashboard'));
  }
}

function airmed_patient_shortcode(){
  global $wp;
  $debug = true;
  $pageHTML = "";
  $isEdit = false;
  $hasToken = false;
  
  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}

  if ($hasToken){
    if(isset($wp->query_vars["edit"]) && $wp->query_vars["edit"] === 'true'){ $edit = true;}
    // going to need to look up stuff from the patient to determine their online account stage to determine what to do
    $requestPath = '/API/Patient/Get/';
    
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    // no error proceed with displaying data
    if ($errno === 200) {
      
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
      // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
      $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
      
      $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
      
      $pageHTML.="    <div class='airmed-content'>";
      $pageHTML.="      <div class='row'>";
      $pageHTML.="        <div class='col col-12'>";
      $pageHTML.="          <div class='col-inner'>";
      $pageHTML.="            <h4 class='content-title'>My Account</h4>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";
      $pageHTML.="      <div class='row'>";
      $pageHTML.="        <div class='col col-12'>";
      $pageHTML.="          <div class='card airmed-profile-card'>";
      $pageHTML.="            <div class='card-body'>";
      
      if ($isEdit){
        
      }
      else{
        // date comes from server using PST so need to set timezone for date function
        date_default_timezone_set("America/Vancouver");

        $pageHTML.="              <div class='row mb-2'>";
        $pageHTML.="                <div class='col-sm-12'>";
        $pageHTML.="                  <address>";
        $pageHTML.="                    <strong>$jsonObj->fName $jsonObj->lName</strong><br>";
        $pageHTML.="                    Gender: $jsonObj->gender<br>";
        $pageHTML.="                    Birth Date: ".Date('M d, Y',(int)substr($jsonObj->dateOfBirth,6,-10))."<br>";
        $pageHTML.="                  </address>";
        $pageHTML.="                </div>";
        $pageHTML.="              </div>";
        $pageHTML.="              <div class='row'>";
        $pageHTML.="                <div class='col-sm-6 mb-2'>";
        $pageHTML.="                  <div class='card'>";
        $pageHTML.="                    <div class='card-header'>Contact Info";
        $pageHTML.="                    </div>";
        $pageHTML.="                    <div class='card-body'>";
        $pageHTML.="                      <address>";
        $pageHTML.="                        Phone 1: $jsonObj->phone1<br>";
        $pageHTML.="                        Email: <a href='mailto:$jsonObj->email'>$jsonObj->email</a><br>";
        $pageHTML.="                      </address>";
        $pageHTML.="                    </div>";
        $pageHTML.="                  </div>";
        $pageHTML.="                </div>";
                // Caregiver info
        if($jsonObj->hasCaregiver){
          $pageHTML.="                <div class='col-sm-6 mb-2'>";
          $pageHTML.="                  <div class='card'>";
          $pageHTML.="                    <div class='card-header'>Caregiver";
          $pageHTML.="                    </div>";
          $pageHTML.="                    <div class='card-body'>";
          $pageHTML.="                      <address>";
          $pageHTML.="                        $jsonObj->caregiverFName $jsonObj->caregiverLName<br>";
          if (!empty($jsonObj->caregiverPhone1)){$pageHTML.="                    Phone 1: $jsonObj->caregiverPhone1<br>";}
          if (!empty($jsonObj->caregiverEmail)){      $pageHTML.="                    Email: <a href='mailto:$jsonObj->caregiverEmail'>$jsonObj->caregiverEmail</a><br>";}
          $pageHTML.="                      </address>";
          $pageHTML.="                    </div>";
          $pageHTML.="                  </div>";
          $pageHTML.="                </div>";
        }
        
        $pageHTML.="              </div>";
        //Residential Address
        $pageHTML.="              <div class='row'>";
        $pageHTML.="                <div class='col-sm-6 mb-2'>";
        $pageHTML.="                  <div class='card'>";
        $pageHTML.="                    <div class='card-header'>Address (Residence)";
        $pageHTML.="                    </div>";
        $pageHTML.="                    <div class='card-body'>";
        
        $resAddress = "";
        if (!empty($jsonObj->resAddress->suite)){$resAddress.= "#".$jsonObj->resAddress->suite." - ";}
        if (!empty($jsonObj->resAddress->streetNumber)){$resAddress.= $jsonObj->resAddress->streetNumber." ";}
        if (!empty($jsonObj->resAddress->streetName)){$resAddress.= $jsonObj->resAddress->streetName;}
        if (!empty($jsonObj->resAddress->floor)){$resAddress.= "</br> Flr ".$jsonObj->resAddress->floor;}
        if (!empty($jsonObj->resAddress->streetAddress2)){$resAddress.= "</br> ".$jsonObj->resAddress->streetAddress2;}
        if (!empty($jsonObj->resAddress->streetAddress3)){$resAddress.= "</br> ".$jsonObj->resAddress->streetAddress3;}
        if (!empty($jsonObj->resAddress->city)){$resAddress.= "</br> ".$jsonObj->resAddress->city;}
        if (!empty($jsonObj->resAddress->province)){$resAddress.= ", ".$jsonObj->resAddress->province;}
        if (!empty($jsonObj->resAddress->country)){$resAddress.= "</br> ".$jsonObj->resAddress->country;}
        if (!empty($jsonObj->resAddress->postalCode)){$resAddress.= "</br> ".$jsonObj->resAddress->postalCode;}

        $pageHTML.="                      <address>$resAddress</address>";
        $pageHTML.="                    </div>";
        $pageHTML.="                  </div>";
        $pageHTML.="                </div>";
        
        // Mailing Address
        $pageHTML.="                <div class='col-sm-6'>";
        $pageHTML.="                  <div class='card'>";
        $pageHTML.="                    <div class='card-header'>Mailing Address";
        $pageHTML.="                    </div>";
        $pageHTML.="                    <div class='card-body'>";
        $pageHTML.="                    <address>";
        if ($jsonObj->mailSameAsRes){
          $pageHTML.="Same as Residence";
        }
        else{
          $mailAddress = "";
          if (!empty($jsonObj->mailAddress->suite)){$mailAddress.= "#".$jsonObj->mailAddress->suite." - ";}
          if (!empty($jsonObj->mailAddress->streetNumber)){$mailAddress.= $jsonObj->mailAddress->streetNumber." ";}
          if (!empty($jsonObj->mailAddress->streetName)){$mailAddress.= $jsonObj->mailAddress->streetName;}
          if (!empty($jsonObj->mailAddress->floor)){$mailAddress.= "</br> Flr ".$jsonObj->mailAddress->floor;}
          if (!empty($jsonObj->mailAddress->streetAddress2)){$mailAddress.= "</br> ".$jsonObj->mailAddress->streetAddress2;}
          if (!empty($jsonObj->mailAddress->streetAddress3)){$mailAddress.= "</br> ".$jsonObj->mailAddress->streetAddress3;}
          if (!empty($jsonObj->mailAddress->city)){$mailAddress.= "</br> ".$jsonObj->mailAddress->city;}
          if (!empty($jsonObj->mailAddress->province)){$mailAddress.= ", ".$jsonObj->mailAddress->province;}
          if (!empty($jsonObj->mailAddress->country)){$mailAddress.= "</br> ".$jsonObj->mailAddress->country;}
          if (!empty($jsonObj->mailAddress->postalCode)){$mailAddress.= "</br> ".$jsonObj->mailAddress->postalCode;}
          $pageHTML.=$mailAddress;
        }
        $pageHTML.="                      </address>";
        $pageHTML.="                    </div>";
        $pageHTML.="                  </div>";
        $pageHTML.="                </div>";
      }
      $pageHTML.="              </div>";  // card body
      $pageHTML.="            </div>"; //card
      $pageHTML.="          </div>";
      $pageHTML.="        </div>";
      $pageHTML.="     </div>";  // airmed-content

      $pageHTML.= "</div>";  // end of airmed wrapper
      
      //$pageHTML.= includeModals();

      echo $pageHTML;
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
  else { // redirect to login
    //if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-patient'));
  }

}

// airmed_topmenu_shortcode - there is an $embed parameter passed as whether to embed it in html markup or not as well as a patient object
function airmed_topmenu_shortcode($atts){
  //aLog("topmenu");
  global $wp;
  $sArgs = shortcode_atts(
    array(
      'embed' => false,
      //'patObj' => '',
    ),
    $atts
  );
  
  $amAccount = new stdClass();
  $amAccount = $_SESSION['__airmed'];

  //$amAccount = new stdClass();
  //$amAccount->hasToken = false;
  //$amAccount->name = '';
  //$amAccount->messages = 0;
  //$amAccount->newMessages = 0;
  //$amAccount->urgentMessages = 0;

  $embed = $sArgs['embed'];
  //$patObj = $sArgs['patObj'];
  //$patObj = $amAccount->patient;
  //aLog($patObj);
  $debug = true;
  //$hasToken = false;
  $pageHTML = "";
  
  $slideout = get_option( 'airmed_options_login_type',1 );
  $use_site_menu = get_option( 'airmed_options_use_site_menu','');
  $hide_logo = get_option( 'airmed_options_hide_logo','');
  $move_reg_nav = get_option( 'airmed_options_main_nav_register','');
  $show_shop = get_option( 'airmed_options_show_shop','');

  $logo_image = empty(get_option( 'airmed_options_logo')) ? plugins_url('../images/default-logo.png',__FILE__) : plugins_url('../images/'.get_option( 'airmed_options_logo' ),__FILE__);
  
  //if(!empty($_SESSION['__amAuthToken'])) { $amAccount->hasToken = true;}
  
  // has token which means logged in
  if ($amAccount->hasToken){
    
    // get messages
    /*
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

    $totalMessages = $urgentMessages + $newMessages;
    $amAccount->messages = $totalMessages;
    $amAccount->newMessages = $newMessages;
    $amAccount->urgentMessages = $urgentMessages;
    */

    // get patient info
    /*
    if(empty($patObj)){
      $requestPath = '/API/Patient/Get/';
      $requestArray = airmed_call_request($requestPath,'GET',true,null);
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];

      $patientName = '';
      if ($errno === 200) {
        $jsonObj = json_decode($response);
        $patientName = $jsonObj->fName.' '.$jsonObj->lName;
        $amAccount->name = $patientName;
      }
    }
    else {  // else $patObj filled from arguments
      $patientName = '';
      $jsonObj = json_decode($patObj);
      $patientName = $jsonObj->fName.' '.$jsonObj->lName;
      $amAccount->name = $patientName;
    }
    */
    
    if(empty($use_site_menu)){
      $pageHTML.="<header id='airmed-header' class='main-header alignwide'>";
      //$pageHTML.="  <div class='item-media'>";
      //$pageHTML.="    <!-- Sidebar toggle button-->";
      //$pageHTML.="    <a href='#' class='sidebar-toggle' data-toggle='push-menu' role='button'>";
      //$pageHTML.="      <i class='fas fa-bars'></i><span class='visually-hidden'>Toggle navigation</span>";
      //$pageHTML.="    </a>";
      //$pageHTML.="  </div>";
      if(empty($hide_logo)){
        $pageHTML.="    <div class='logo navbar-brand'>";
        $pageHTML.="      <img alt='Brand' src='$logo_image'>";
        $pageHTML.="    </div>";
      }

      $pageHTML.="  <nav class='navbar navbar-static-top navbar-expand-lg ' role='navigation'>";

      $pageHTML.="    <!-- Collect the nav links, forms, and other content for toggling -->";
      $pageHTML.="    <button class='navbar-toggler collapsed' type='button' data-am-toggle='collapse' data-am-target='#airmed-navbar-collapse-2' aria-controls='navbar-collapse-2' aria-expanded='false' aria-label='Toggle navigation'>";
      //$pageHTML.="      <i class='fas fa-bars'></i>";
      $pageHTML.="      <i class='dashicons dashicons-menu-alt2'></i>";
      $pageHTML.="      <span class='visually-hidden'>Toggle navigation</span>";
      $pageHTML.="    </button>";

      $pageHTML.="    <div class='navbar-collapse collapse navbar-left' id='airmed-navbar-collapse-2'>";
      $pageHTML.="      <ul class='nav navbar-nav navbar-top-links'>";
      $pageHTML.="          <li class='navbar-item mobile-navbar-item'>";
      $pageHTML.="            <a class='' href='".airmed_pagelink('/airmed/')."'>";
      $pageHTML.="              Shop";
      //$pageHTML.="            <a class='' href='".airmed_pagelink('/airmed/airmed-dashboard')."'>";
      //$pageHTML.="              <i class='fas fa-dashboard' style='display: none;'></i>";
      //$pageHTML.="              Dashboard";
      $pageHTML.="            </a>";
      $pageHTML.="          </li>";
      //$pageHTML.="          <li class='navbar-item dropdown mobile-navbar-item'>";
      //$pageHTML.="            <a class='dropdown-toggle' data-am-toggle='dropdown' role='button' href='#' aria-expanded='false'>";
      //$pageHTML.="              Client Menu";
      //$pageHTML.="            </a>";
      //$pageHTML.="            <div class='dropdown-menu client-menu'>";
      //$pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-dashboard/')."' class='dropdown-item'>Dashboard</a>";
      //$pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-applications/')."' class='dropdown-item'>My Applications</a>";
      //$pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-orders/')."' class='dropdown-item'>My Orders</a>";
      //$pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-messages')."' class='dropdown-item'>My Messages</a>";
  ////    $pageHTML.="              <div class='dropdown-divider'></div>";
      //$pageHTML.="            </div>";
      //$pageHTML.="          </li>";

      $pageHTML.="      </ul>";
      $pageHTML.="    </div><!-- /.navbar-collapse -->";

      $pageHTML.="    <!-- Collect the nav links, forms, and other content for toggling -->";
      $pageHTML.="    <button class='navbar-toggler collapsed' type='button' data-am-toggle='collapse' data-am-target='#airmed-navbar-collapse-1' aria-controls='navbar-collapse-1' aria-expanded='false' aria-label='Toggle navigation'>";
      //$pageHTML.="      <i class='fas fa-bars'></i>";
      $pageHTML.="      <i class='dashicons dashicons-menu-alt2'></i>";
      $pageHTML.="      <span class='visually-hidden'>Toggle navigation</span>";
      $pageHTML.="    </button>";

      $pageHTML.="    <div class='navbar-collapse collapse navbar-right' id='airmed-navbar-collapse-1'>";
      $pageHTML.="      <ul class='nav navbar-nav navbar-top-links ms-auto'>";
      $pageHTML.="          <li class='navbar-item mobile-navbar-item'>";
      $pageHTML.="            <a class='airmed-modal-link' href='#airmed-cart-slideout' data-am-toggle='collapse' aria-controls='airmed-cart-slideout' aria-expanded='false' role='button'>";
      $pageHTML.="              Cart<span id='airmed-menu-cart-items' class='badge bg-dark rounded-pill'>$amAccount->numOfItems</span>";
      $pageHTML.="            </a>";
      $pageHTML.="          </li>";

      /*  
      // Messages 
      $pageHTML.="          <li class='navbar-item mobile-navbar-item' style='display: none;'>";
      $pageHTML.="            <a class='' href='".airmed_pagelink('/airmed/airmed-messages')."'>";
      $pageHTML.="              My Messages";
      $pageHTML.="              <i class='dashicons dashicons-bell' aria-hidden='true'></i>";
      //$pageHTML.="              <i class='far fa-bell'></i>";
      $pageHTML.="            </a>";
      $pageHTML.="          </li>";
      $pageHTML.="          <li id='airmed-messages-menu' class='navbar-item dropdown full-navbar-item'>";
      $pageHTML.="            <a class='dropdown-toggle' data-am-toggle='dropdown' role='button' href='#' aria-expanded='false'>";
      $pageHTML.="              <i class='dashicons dashicons-bell' aria-hidden='true'></i>";
      //$pageHTML.="              <i class='far fa-bell'></i>";
      $pageHTML.="              <span class='badge badge-message bg-warning text-dark rounded-pill'>$totalMessages</span>";
      $pageHTML.="            </a>";
      $pageHTML.="            <div class='dropdown-menu messages'>";
      $pageHTML.="              <div class='dropdown-item no-hover' >";
      $pageHTML.="                Urgent Messages";
      $pageHTML.="                <span class='pull-right text-muted small urgent-messages'>$urgentMessages</span>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='dropdown-item no-hover' >";
      $pageHTML.="                New Messages";
      $pageHTML.="                <span class='pull-right text-muted small new-messages'>$newMessages</span>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='dropdown-divider'></div>";
      $pageHTML.="                <a class='dropdown-item' href='".airmed_pagelink('/airmed/airmed-messages')."'>";
      $pageHTML.="                  <strong>Go To My Messages</strong>";
      //$pageHTML.="                  <i class=' fas fa-angle-right'></i>";
      $pageHTML.="                  <i class='dashicons dashicons-arrow-right-alt2' aria-hidden='true'></i>";
      $pageHTML.="                </a>";
      $pageHTML.="            </div>";
      $pageHTML.="          </li>";
      */
      //if(empty($_SESSION['__amPatient'])) {

      $pageHTML.="          <li class='dropdown-account mobile-navbar-item'>";
      $pageHTML.="            <a class='dropdown-toggle position-relative' data-am-toggle='dropdown' role='button' href='#' aria-expanded='false'>";
      $pageHTML.="              <span class='position-relative'>$amAccount->name";
      $pageHTML.="              <span class='badge-message position-absolute start-100 translate-middle p-2 bg-danger border border-light rounded-circle'><span class='visually-hidden'>Urget Messages</span></span>";
      $pageHTML.="              <span class='badge-message position-absolute start-100 translate-middle p-2 bg-warning border border-light rounded-circle'><span class='visually-hidden'>New Messages</span></span>";
      $pageHTML.="</span>";
      //$pageHTML.="              <i class='dashicons dashicons-admin-users' aria-hidden='true'></i>";
      $pageHTML.="            </a>";
      $pageHTML.="            <div class='dropdown-menu' aria-labelledby='dropdownMenuButton'>";
      $pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-dashboard/')."' class='dropdown-item'>Dashboard</a>";
      $pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-orders/')."' class='dropdown-item'>Orders</a>";
      //$pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-applications/')."' class='dropdown-item'>Applications</a>";
      $pageHTML.="              <div class='dropdown-divider'></div>";
      $pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-messages')."' class='dropdown-item'>Messages</a>";
      $pageHTML.="              <div class='dropdown-item no-hover ' >";
      $pageHTML.="                <span class='ps-3'>Urgent Messages</span>";
      $pageHTML.="                <span class='float-end urgent-messages badge rounded-pill bg-danger mt-1'>$amAccount->urgentMessages</span>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='dropdown-item no-hover ' >";
      $pageHTML.="                <span class='ps-3'>New Messages</span>";
      $pageHTML.="                <span class='float-end new-messages badge rounded-pill bg-warning text-dark mt-1'>$amAccount->newMessages</span>";
      $pageHTML.="              </div>";
      $pageHTML.="              <div class='dropdown-divider'></div>";
      $pageHTML.="              <a href='".airmed_pagelink('/airmed/airmed-patient')."' class='dropdown-item'>";
      //$pageHTML.="              <i class='dashicons  dashicons-admin-users' aria-hidden='true'></i>";
      $pageHTML.="                Account Details";
      $pageHTML.="              </a>";

      //$pageHTML.="              <a href='/Home/SetCulture/fr-CA' class='dropdown-item external'><i class='fas fa-language  fa-fw'></i>Français</a>";
      $pageHTML.="              <div class='dropdown-divider'></div>";
      
      // Need to work on a logout method 
      //$pageHTML.="                <form action='/Account/LogOff' id='logoutForm' method='post'><input name='__RequestVerificationToken' type='hidden' value='_pclyW6EAKzuPUNVuddW4kTulufF6tfXi26xbZLG7LPsvKH5hkLXwar6Q1Unb1TIGzMV0xlqq8-lQuvWIdUWJEZFwCJ2oSgMX2gLKOMYBgdfCaysw1kZEWfAdEEOepd1lrqJLA2'>";
      $pageHTML.="                  <a href='".airmed_pagelink('/airmed/airmed-login')."' class='dropdown-item external'>";
      //$pageHTML.="                    <i class='fas fa-sign-out-alt fa-fw'></i>Logout";
      $pageHTML.="                    <i class='dashicons  dashicons-migrate' aria-hidden='true'></i>Logout";
      $pageHTML.="                  </a>";
      //$pageHTML.="                </form>              </div>";
      $pageHTML.="            <!-- /.dropdown-user -->";
      $pageHTML.="          </li>";
      $pageHTML.="      </ul>";
      $pageHTML.="    </div><!-- /.navbar-collapse -->";

      $pageHTML.="    <div id='actionmenu-btn' data-toggle='action-menu' class='actionmenu-btn text-center' style='display: none;'>";
      $pageHTML.="      <i class='fas fa-spin fa-gear'></i>";
      $pageHTML.="    </div>";
      $pageHTML.="  </nav>";
      $pageHTML.="  <!--End Header-->";
      $pageHTML.="</header>";
    }    
    //$pageHTML.= airmed_account_slideout($amAccount);
  }
  // not logged in
  else{
    if(empty($use_site_menu)){

      $pageHTML.= "<header id='airmed-header' class='main-header alignwide'>";
      if(empty($hide_logo)){
        $pageHTML.= "  <div class='logo navbar-brand'>";
        $pageHTML.= "    <img alt='Brand' src='$logo_image'>";
        $pageHTML.= "  </div>";
      }

      $pageHTML.= "  <nav class='navbar navbar-static-top navbar-expand-lg' role='navigation'>";
      $pageHTML.="    <button class='navbar-toggler collapsed' type='button' data-am-toggle='collapse' data-am-target='#airmed-navbar-collapse-2' aria-controls='navbar-collapse-2' aria-expanded='false' aria-label='Toggle navigation'>";
      $pageHTML.="      <i class='dashicons dashicons-menu-alt2'></i>";
      $pageHTML.="      <span class='visually-hidden'>Toggle navigation</span>";
      $pageHTML.="    </button>";

      $pageHTML.="    <div class='navbar-collapse collapse navbar-left' id='airmed-navbar-collapse-2'>";
      $pageHTML.="      <ul class='nav navbar-nav navbar-top-links'>";
      if(!empty($show_shop)){
        $pageHTML.="          <li class='navbar-item mobile-navbar-item'>";
        $pageHTML.="            <a class='' href='".airmed_pagelink('/airmed/')."'>";
        $pageHTML.="              Shop";
        $pageHTML.="            </a>";
        $pageHTML.="          </li>";
      }
      $pageHTML.= "      </ul>";
      $pageHTML.= "    </div><!-- /.navbar-collapse -->";

      if(empty($move_reg_nav)){
        $pageHTML.= "    <!-- Collect the nav links, forms, and other content for toggling -->";
        $pageHTML.="    <button class='navbar-toggler collapsed' type='button' data-am-toggle='collapse' data-am-target='#airmed-navbar-collapse-1' aria-controls='navbar-collapse-1' aria-expanded='false' aria-label='Toggle navigation'>";
        $pageHTML.="      <i class='dashicons dashicons-menu-alt2'></i>";
        $pageHTML.="      <span class='visually-hidden'>Toggle navigation</span>";
        $pageHTML.="    </button>";
        $pageHTML.="    <div class='navbar-collapse collapse navbar-right' id='airmed-navbar-collapse-1'>";
        $pageHTML.="      <ul class='nav navbar-nav navbar-top-links ms-auto'>";
        $pageHTML.="        <li class='navbar-item mobile-navbar-item'>";
        $pageHTML.="          <a href='".airmed_pagelink('/airmed/airmed-new-account')."' class=''>Sign Up</a>";
        $pageHTML.="        </li>";
        $pageHTML.="        <li class='navbar-item mobile-navbar-item'>";
        $pageHTML.="          <a class='airmed-modal-link' data-am-toggle='modal' data-am-target='#airmed-modal-login'>AirMed Login</a>";
        //$pageHTML.="          <a href='".airmed_pagelink('/airmed/airmed-login')."' class=''>Login</a>";
        $pageHTML.="        </li>";
        $pageHTML.="      </ul>";
        $pageHTML.="    </div><!-- /.navbar-collapse -->";
      }

      $pageHTML.="  </nav>";
      $pageHTML.="  <!--End Header-->";
      $pageHTML.="</header>";
    }
  }

  //$pageHTML.= airmed_cart_slideout();

  if ($embed) {
    return $pageHTML;
  }
  else {  
    echo $pageHTML;
  }
}

function airmed_messages_shortcode(){
  global $wp;
  $debug = true;
  $pageHTML = "";
  $hasToken = false;

  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}
  
  if ($hasToken){
    
    $requestPath = '/API/Message/GetAllMessages/';
    
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    
    //print_r($requestArray);
    //$errno = 0;
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    // no error proceed with displaying data
    if ($errno === 200) {

          //Create an array of objects from the JSON returned by the API
      $jsonObj = json_decode($response);
      $received = $jsonObj->received;
      $sent = $jsonObj->sent;
      
      // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
      $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
      
      $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
      
      $pageHTML.="    <div class='airmed-messages-content'>";
      $pageHTML.="      <div class='row'>";
      $pageHTML.="        <div class='col col-12'>";
      $pageHTML.="          <div class='col-inner'>";
      $pageHTML.="            <h4 class='content-title'>My Messages</h4>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";

      $pageHTML.="      <div class='row'>";
      $pageHTML.="        <div class='col col-12'>";

      //$pageHTML.="<div class='card card-primary'>";
      //$pageHTML.="  <div class='card-body'>";
      $pageHTML.="    <div class='tabpanel-messages' role='tabpanel'>";
      $pageHTML.="      <!-- Nav tabs -->";
      $pageHTML.="      <ul class='nav am-nav-tabs' role='tablist'>";
      
      $inboxActive = "active";
      $sentActive = "";
      $inboxSelected = "true";
      $sentSelected = "false";
      if(isset($wp->query_vars["tab"]) && ($wp->query_vars["tab"] == "sent")){ 
        $inboxActive = "";
        $sentActive = "active";
        $inboxSelected = "false";
        $sentSelected = "true";
      }
      
      $pageHTML.="        <li role='presentation' class='am-nav-item'><a class='am-nav-link $inboxActive' href='#' data-am-toggle='tab' data-am-target='#airmed-inbox' aria-controls='inbox' aria-selected='$inboxSelected' role='tab'>Inbox</a></li>";
      $pageHTML.="        <li role='presentation' class='am-nav-item'><a class='am-nav-link $sentActive' href='#' data-am-toggle='tab' data-am-target='#airmed-sent' aria-controls='sent' aria-selected='$sentSelected' role='tab'>Sent Items</a></li>";
      $pageHTML.="      </ul>";
      $pageHTML.="      <!-- Tab panes -->";
      $pageHTML.="      <div class='am-tab-content'>";
      $pageHTML.="        <div role='tabpanel' class='am-tab-pane $inboxActive' id='airmed-inbox'>";

      $pageHTML.="            <table id='airmed-inboxTable' class='airmed-dataTable table-striped table-condensed'>";
      $pageHTML.="              <thead>";
      $pageHTML.="                <tr role='row'>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Date Created";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Subject";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Sender";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Priority";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Read";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                  </th>";
      $pageHTML.="                </tr>";
      $pageHTML.="              </thead>";
      $pageHTML.="              <tbody>";

      // date comes from server using PST so need to set timezone for date function
      date_default_timezone_set("America/Vancouver");

      if(!empty($received)){
        //Loop through the API results
        foreach($received as $itemObj) {
          $viewed = $itemObj->viewed ? "Yes" : "No";
          $pageHTML.="                <tr class='odd'>";

          //$pageHTML.="                  <td valign='top'>".date('Y-m-d g:ia',strtotime($itemObj->dateCreated))."</td>";
          $pageHTML.="                  <td valign='top'>".date('Y-m-d g:ia',(int)substr($itemObj->dateCreated,6,-10))."</td>";
          $pageHTML.="                  <td valign='top'>$itemObj->subject</td>";
          $pageHTML.="                  <td valign='top'>$itemObj->senderName</td>";
          $pageHTML.="                  <td valign='top'>$itemObj->priority</td>";
          $pageHTML.="                  <td valign='top'>$viewed</td>";
          $pageHTML.="                  <td valign='top' class='text-center'>";
          $pageHTML.="                    <a title='Message Details' href='' class='btn btn-info btn-sm' data-am-toggle='modal' data-am-target='#airmed-modal-message' data-itype='receivedMessage' data-prodid='$itemObj->id'>";
          $pageHTML.="                      <span>Details</span>";
          $pageHTML.="                    </a>";
          $pageHTML.="                  </td>";
          $pageHTML.="                </tr>";
        }
      }
      $pageHTML.="              </tbody>";
      $pageHTML.="            </table>";

      $pageHTML.="        </div>";
      $pageHTML.="        <div role='tabpanel' class='am-tab-pane $sentActive' id='airmed-sent'>";

      $pageHTML.="            <table id='airmed-sentTable' class='airmed-dataTable table-striped table-condensed'>";
      $pageHTML.="              <thead>";
      $pageHTML.="                <tr role='row'>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Date Created";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Subject";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Recipient";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                    Priority";
      $pageHTML.="                  </th>";
      $pageHTML.="                  <th>";
      $pageHTML.="                  </th>";
      $pageHTML.="                </tr>";
      $pageHTML.="              </thead>";
      $pageHTML.="              <tbody>";
      if(!empty($sent)){
        //Loop through the API results
        foreach($sent as $itemObj) {
          $pageHTML.="                <tr >";
          //$pageHTML.="                  <td valign='top'>".date('Y-m-d g:ia',strtotime($itemObj->dateCreated))."</td>";
          $pageHTML.="                  <td valign='top'>".date('Y-m-d g:ia',(int)substr($itemObj->dateCreated,6,-10))."</td>";          $pageHTML.="                  <td valign='top'>$itemObj->subject</td>";
          $pageHTML.="                  <td valign='top'>$itemObj->recipientName</td>";
          $pageHTML.="                  <td valign='top'>$itemObj->priority</td>";
          $pageHTML.="                  <td valign='top' class='text-center'>";
          $pageHTML.="                    <a title='Message Details' href='' class='btn btn-info btn-sm' data-am-toggle='modal' data-am-target='#airmed-modal-message' data-itype='sentMessage' data-prodid='$itemObj->id'>";
          $pageHTML.="                      <span>Details</span>";
          $pageHTML.="                    </a>";
          $pageHTML.="                  </td>";
          $pageHTML.="                </tr>";
        }
      }
      $pageHTML.="              </tbody>";
      $pageHTML.="            </table>";

      $pageHTML.="        </div>";
      $pageHTML.="      </div>";
      $pageHTML.="    </div>";
      //$pageHTML.="  </div>";
      //$pageHTML.="</div>";

      $pageHTML.="        </div>";
      $pageHTML.="     </div>";  // airmed-content

      $pageHTML.= airmed_modal_message();

      $pageHTML.= "</div>";  // end of airmed wrapper
    
      //$pageHTML.= includeModals();

      echo $pageHTML;
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
  else { // redirect to login
    //if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-messages'));
  }
}

function airmed_applications_shortcode(){
  $debug = true;
  $pageHTML = "";
  $hasToken = false;

  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}
  
  if ($hasToken){
    // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
    $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
    
    $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
    
    $pageHTML.="    <div class='airmed-content'>";
    $pageHTML.="      <div class='row'>";
    $pageHTML.="        <div class='col col-12'>";
    $pageHTML.="          <div class='col-inner'>";
    $pageHTML.="            <h4 class='content-title'>My Applications</h4>";
    $pageHTML.="          </div>";
    $pageHTML.="        </div>";
    $pageHTML.="      </div>";

    $pageHTML.="      <div class='row'>";
    $pageHTML.="        <div class='col col-12'>";

    $pageHTML.="          <div class='card card-primary'>";
    $pageHTML.="            <div class='card-body'>";

    $pageHTML.="              <table id='airmed-appTable' class='airmed-dataTable table-striped table-bordered table-condensed'>";
    $pageHTML.="                <thead>";
    $pageHTML.="                  <tr role='row'>";
    $pageHTML.="                    <th>";
    $pageHTML.="                      Date Created";
    $pageHTML.="                    </th>";
    $pageHTML.="                    <th>";
    $pageHTML.="                      Producer";
    $pageHTML.="                    </th>";
    $pageHTML.="                    <th>";
    $pageHTML.="                      Status";
    $pageHTML.="                    </th>";
    $pageHTML.="                    <th>";
    $pageHTML.="                    </th>";
    $pageHTML.="                  </tr>";
    $pageHTML.="                </thead>";
    $pageHTML.="                <tbody>";
    //$pageHTML.="                  <tr class='odd'>";
    //$pageHTML.="                    <td valign='top'></td>";
    //$pageHTML.="                    <td valign='top'></td>";
    //$pageHTML.="                    <td valign='top'></td>";
    //$pageHTML.="                    <td valign='top'></td>";
    //$pageHTML.="                  </tr>";
    $pageHTML.="                </tbody>";
    $pageHTML.="              </table>";

    $pageHTML.="            </div>"; // end of card-body
    $pageHTML.="          </div>";  // end of card

    $pageHTML.="        </div>";
    $pageHTML.="     </div>";  // airmed-content

    $pageHTML.= "</div>";  // end of airmed wrapper
  
    //$pageHTML.= includeModals();

    echo $pageHTML;
  }
  else { // redirect to login
    //if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-applications'));
  }
}

function airmed_orders_shortcode(){
  $debug = true;
  $pageHTML = "";
  $hasToken = false;

  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}
  
  if ($hasToken){
    
    $requestPath = '/API/Order/GetAllOrders/';
    
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    
    //print_r($requestArray);
    //$errno = 0;
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    // no error proceed with displaying data
    if ($errno === 200) {

          //Create an array of objects from the JSON returned by the API
      $jsonObj = json_decode($response);

      // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
      $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
      
      $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
      
      $pageHTML.="    <div class='airmed-content'>";
      $pageHTML.="      <div class='row'>";
      $pageHTML.="        <div class='col col-12'>";
      $pageHTML.="          <div class='col-inner'>";
      $pageHTML.="            <h4 class='content-title'>My Orders</h4>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";

      $pageHTML.="      <div class='row'>";
      $pageHTML.="        <div class='col col-12'>";

      $pageHTML.="          <div class='card card-primary'>";
      $pageHTML.="            <div class='card-body'>";

      $pageHTML.="              <table id='airmed-orderTable' class='airmed-dataTable table-striped table-bordered table-condensed'>";
      $pageHTML.="                <thead>";
      $pageHTML.="                  <tr role='row'>";
      $pageHTML.="                    <th>";
      $pageHTML.="                      Date Created";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th>";
      $pageHTML.="                      Order No";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th>";
      $pageHTML.="                      Total";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th>";
      $pageHTML.="                      Order State";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th>";
      $pageHTML.="                    </th>";
      $pageHTML.="                  </tr>";
      $pageHTML.="                </thead>";
      $pageHTML.="                <tbody>";
      
      // date comes from server using PST so need to set timezone for date function
      date_default_timezone_set("America/Vancouver");

      if(!empty($jsonObj)){
        //Loop through the API results
        //$pageHTML.="                    Birth Date: ".idate('U')."<br>";
        //$pageHTML.="                    Birth Date: ".Date('Y-m-d g:ia',idate('U'))."<br>";
        //$pageHTML.="                    Birth Date: ".Date('M d, Y',(int)substr($jsonObj->dateOfBirth,6,-10))."<br>";
        //$pageHTML.="                    Birth Date: ".substr($jsonObj->dateOfBirth,6,-10)."<br>";
        //$pageHTML.="                    Birth Date: ".substr($jsonObj->dateOfBirth,6,-2)."<br>";
        //$pageHTML.="                    Birth Date: ".date('Y-m-d g:ia',strtotime($jsonObj->dateOfBirth))."</td>";


        foreach($jsonObj as $itemObj) {
          $pageHTML.="                <tr class='odd'>";
          $pageHTML.="                  <td valign='top'>".date('Y-m-d g:ia',(int)substr($itemObj->dateCreated,6,-10))."</td>";
          //$pageHTML.="                  <td valign='top'>".date('Y-m-d g:ia',strtotime((int)substr($itemObj->dateCreated,6,-2)))."</td>";
          $pageHTML.="                  <td valign='top'>$itemObj->orderNo</td>";
          $pageHTML.="                  <td valign='top' class='text-end'>$".number_format($itemObj->total,2)."</td>";
          $pageHTML.="                  <td valign='top' class='text-center'>$itemObj->orderState</td>";
          $pageHTML.="                  <td valign='top' class='text-center'>";
          //$pageHTML.="                    <a title='Order Details' href='' class='btn btn-info btn-sm' data-am-toggle='modal' data-am-target='#airmed-modal-order' data-prodid='$itemObj->id'>";
          $pageHTML.="                    <a title='Order Details' href='".add_query_arg('id',$itemObj->id,airmed_pagelink('/airmed/airmed-order/'))."' class='btn btn-info btn-sm' >";
          $pageHTML.="                      <span>Details</span>";
          $pageHTML.="                    </a>";
          $pageHTML.="                  </td>";
          $pageHTML.="                </tr>";
        }
      }

      $pageHTML.="                </tbody>";
      $pageHTML.="              </table>";

      $pageHTML.="            </div>"; // end of card-body
      $pageHTML.="          </div>";  // end of card

      $pageHTML.="        </div>";
      $pageHTML.="     </div>";  // airmed-content

      $pageHTML.= "</div>";  // end of airmed wrapper

      //$pageHTML.= includeModals();

      echo $pageHTML;
    }
    else {
      //$error_message = curl_strerror($errno);
      echo "<div>request error ({$errno}):\n {$err_message} </div>";
      if ($debug) echo "<div>request Error: $err </div>";
      else echo "<div> $err </div>";
    }
  }
  else { // redirect to login
    //if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-orders'));
  }
}

function airmed_application_shortcode(){
  global $wp;
  $debug = true;
  $pageHTML = "";
  $hasToken = false;
  $id = "";

  if(isset($wp->query_vars["id"])){ 
    $id = $wp->query_vars["id"];
  }

  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}
  if(empty($id)){
    echo "<div>Missing ID. Try again or contact your system administrator.</div>";
  }
  else if ($hasToken){
    /*
    $requestPath = '/API/Register/GetApplication/';
    
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];
    */
    $errno = 200;
    // no error proceed with displaying data
    if ($errno === 200) {

      //Create an array of objects from the JSON returned by the API
      //$jsonObj = json_decode($response);
      
      // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
      $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
      
      $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
      
      $pageHTML.="  <div class='airmed-application-content'>";
      $pageHTML.="    <div class='row'>";
      $pageHTML.="      <div class='col col-12'>";
      $pageHTML.="        <div class='col-inner'>";
      $pageHTML.="          <h4 class='content-title pull-left'>My Applications</h4>";
      $pageHTML.="          <a href='".airmed_pagelink('/airmed/airmed-applications/')."' class='pull-right btn btn-sm btn-default'><span>All Applications</span></a>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";
      $pageHTML.="    </div>";

      $pageHTML.="    <div class='row'>";
      $pageHTML.="      <div class='col col-12'>";
      $pageHTML.="        app id: $id";


      $pageHTML.="      </div>";
      $pageHTML.="    </div>";

      $pageHTML.="  </div>";  // airmed-content
      $pageHTML.= "</div>";  // end of airmed wrapper

      //$pageHTML.= includeModals();

      echo $pageHTML;
    }
    else {
      //$error_message = curl_strerror($errno);
      echo "<div>request error ({$errno}):\n {$err_message} </div>";
      if ($debug) echo "<div>request Error: $err </div>";
      else echo "<div> $err </div>";
    }
  }
  else { // redirect to login
    airmed_login_shortcode(array('returnUrl' => 'airmed-application'));
  }

}

function airmed_order_shortcode(){
  global $wp;
  $id = "";
  $debug = true;
  $pageHTML = "";
  $isEdit = false;
  $hasToken = false;

  if(isset($wp->query_vars["id"])){ 
    $id = $wp->query_vars["id"];
  }
  $success = '';
  if(isset($wp->query_vars["success"])){ 
    $success = $wp->query_vars["success"];
  }


  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}
    
  if ($hasToken){
    $airmed = new stdClass();
    $airmed = $_SESSION['__airmed'];

    $requestPath = '/API/Order/GetOrder/'.$id;
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    
    //print_r($requestArray);
    //$errno = 0;
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    // no error proceed with displaying data
    if ($errno === 200) {

      // date comes from server using PST so need to set timezone for date function
      date_default_timezone_set("America/Vancouver");

      //Create an array of objects from the JSON returned by the API
      $jsonObj = json_decode($response);

      // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
      $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
      
      $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
      
      $pageHTML.="  <div class='airmed-content airmed-cart'>";
      $pageHTML.="    <div class='row'>";
      $pageHTML.="      <div class='col-12'>";
      $pageHTML.="        <div class='col-inner'>";
      $pageHTML.="          <h4 class='content-title'>My Order</h4>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";
      $pageHTML.="    </div>";
      if ($success == 'true'){
        $success_msg='<p>Thank you for your purchase.</p><p>We will be putting your order together very soon.</p>';
        $success_msg.='<p>A tracking number will be sent to your email address when your order is shipped.</p>';
        $success_msg = get_option( 'airmed_options_payment_success_msg',$success_msg);

        
        $pageHTML.="    <div class='row'>";
        $pageHTML.="      <div class='col-12'>";
        $pageHTML.="        <div class='alert alert-success payment-success'>";
        $pageHTML.="          <div class='col-4 col-sm-2'><i class='dashicons dashicons-yes-alt'></i></div>";
        $pageHTML.="          <div class='col-8 col-sm-10'>$success_msg</div>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>";
        $pageHTML.="    </div>";
      }
      $pageHTML.="    <div class='row mb-2'>";  // order info
      $pageHTML.="      <div class='col-12 col-md-6 mb-2'>";  //order details
      $pageHTML.="        <div class='card airmed-order-card'>";
      $pageHTML.="          <div class='card-header'>";
      $pageHTML.="            <span>Order #:</span>";
      $pageHTML.="            <span>$jsonObj->orderNo<span>";
      $pageHTML.="          </div>";
      $pageHTML.="          <div class='card-body'>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Date Ordered:</span>";
      //$pageHTML.="                <span>".date('Y-m-d g:ia',strtotime($jsonObj->dateCreated))."<span>";
      $pageHTML.="                <span>".date('Y-m-d g:ia',(int)substr($jsonObj->dateCreated,6,-10))."<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Source:</span>";
      $pageHTML.="                <span>$jsonObj->orderSource<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Order State:</span>";
      $pageHTML.="                <span>$jsonObj->orderState<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Order Status:</span>";
      $pageHTML.="                <span>$jsonObj->orderStatus<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      if(!empty($jsonObj->shipmentPIN)){
        $pageHTML.="            <div class='row'>";
        $pageHTML.="              <div class='col-12'>";
        $pageHTML.="                <span>Shipment #:</span>";
        $puroURL = "https://www.purolatior.com/purolator/ship-track/tracking-details.page?pin=";
        $pageHTML.="                <span><a href='".$puroURL.$jsonObj->shipmentPIN."'>$jsonObj->shipmentPIN</a><span>";
        $pageHTML.="              </div>";
        $pageHTML.="            </div>";
      }
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Items in Order:</span>";
      $pageHTML.="                <span>$jsonObj->itemCount<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Plants in Order:</span>";
      $pageHTML.="                <span>$jsonObj->totalPlants<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Grams in Order:</span>";
      $pageHTML.="                <span>".number_format($jsonObj->totalGrams,1)."g<span>";
      $pageHTML.="                <span class='side-note'>(Max 150g)</span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";  // end of order details

      $pageHTML.="      <div class='col-12 col-md-6 mb-2'>";  // order details
      $pageHTML.="        <div class='card airmed-order-card'>";
      $pageHTML.="          <div class='card-header'>";
      $pageHTML.="            <span>Application Details</span>";
      $pageHTML.="          </div>";
      $pageHTML.="          <div class='card-body'>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Expires:</span>";
      //$pageHTML.="                <span>".date('Y-m-d',strtotime($jsonObj->expirationDate))."<span>";
      $pageHTML.="                <span>".date('Y-m-d g:ia',(int)substr($jsonObj->expirationDate,6,-10))."<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Max Grams/Month:</span>";
      $pageHTML.="                <span>".number_format($jsonObj->maxMonthlyGrams,1)."g<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Grams/Last 30 days:</span>";
      $pageHTML.="                <span>".number_format($jsonObj->gramsLastThirtyDays,1)."g<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>"; 
      $pageHTML.="      </div>";  // end of app details
      $pageHTML.="    </div>";  // end of order row
      
      $pageHTML.="    <div class='alert alert-success hide d-flex align-items-center no-background' role='alert'>
                        <i class='dashicons dashicons-yes'></i>
                        Cart Updated
                      </div>";

      // mobile look
      $pageHTML.="    <div class='row airmed-mobile'>";
      $pageHTML.="      <div class='col'>";
      $pageHTML.="        <h5 class=''>Order Items</h5>";
      if(!empty($jsonObj->orderItems)){
        
        $img_show_order = get_option( 'airmed_options_img_show_order') ? "enabled": "";
        
        //Loop through the API results for Mobile rows
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
          
          $pageHTML.="              <div class='card airmed-order-card mb-2'>";
          
          //processing part
          $pageHTML.= "               <div class='am-loading d-flex justify-content-center hide'>";
          $pageHTML.= "                 <div>Updating...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
          $pageHTML.="                </div>";
          
          $pageHTML.="                <div class='card-body'>";
          if($img_show_order == 'enabled'){
            $pageHTML.="                  <div class='row pt-2'>";
            $pageHTML.="                    <div class='col text-center'>";
            $pageHTML.="                      <img class='img-line-item' src='".$itemObj->thisImg."' alt='product image'/>";
            $pageHTML.="                    </div>";
            $pageHTML.="                  </div>";
          }

          $pageHTML.="                  <div class='row p-2'>";
          $pageHTML.="                    <div class='col'>";
          $pageHTML.="                      <div class='item-name'>".substr($itemObj->description,0,strpos($itemObj->description,' ($'))."</div>";
          $pageHTML.="                      <div class='item-weight'>".substr($itemObj->description,strpos($itemObj->description,' ($'))."</div>";
          $pageHTML.="                    </div>";
          $pageHTML.="                  </div>";
          $pageHTML.="                  <div class='row'>";
          $pageHTML.="                    <div class='col'>";
          $pageHTML.="                      <div class='item-unit-price text-end p-2'>";
          //$pageHTML.="                        <small class='multiplier'>".$itemObj->quantity." x</small>";
          if($itemObj->unitSalePrice > 0){
            $pageHTML.="                   <span class='strikethrough'>$".number_format($itemObj->unitPrice,2)."</span><span class='text-danger'>$".number_format($itemObj->unitSalePrice,2)."</span>";
          }
          else {
            $pageHTML.="                   $".number_format($itemObj->unitPrice,2);
          }
          $pageHTML.="                      </div>";
          $pageHTML.="                    </div>";
          $pageHTML.="                  </div>";

          $pageHTML.="                  <div class='row p-2'>";
          $pageHTML.="                    <div class='col-2 text-center'>";
          $pageHTML.="                    </div>";
          $pageHTML.="                    <div class='col-6 text-center'>".$itemObj->quantity."x";
          $pageHTML.="                    </div>";
          $pageHTML.="                    <div class='col-4 text-end'>";
          $pageHTML.="                      <span>$".number_format($itemObj->totalPrice,2)."</span>";
          $pageHTML.="                    </div>";
          $pageHTML.="                  </div>";
          $pageHTML.="                </div>";
          $pageHTML.="              </div>";
        }
      }


      $pageHTML.="      </div>";
      $pageHTML.="    </div>";  // end of mobile items row


      $pageHTML.="    <div class='row'>";
      $pageHTML.="      <div class='col col-12'>";
      $pageHTML.="        <div class='card airmed-order-card'>";
      $pageHTML.="          <div class='card-body'>";

      $pageHTML.="            <div class='row cart-table position-relative airmed-non-mobile'>";
      $pageHTML.="              <div class='col-12'>";

      //processing part
      $pageHTML.= "               <div class='am-loading d-flex justify-content-center hide'>";
      $pageHTML.= "                 <div>Updating...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
      $pageHTML.="                </div>";

      $pageHTML.="            <div class='table-responsive'>";
      $pageHTML.="              <table id='airmed-cartTable' class='table'>";
      $pageHTML.="                <thead>";
      $pageHTML.="                  <tr role='row'>";
      if($img_show_order == 'enabled'){
        $pageHTML.="                    <th class='text-start'></th>";
      }
      $pageHTML.="                    <th class='text-start'>";
      $pageHTML.="                      Product";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th class='text-end'>";
      $pageHTML.="                      Price";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th class='text-center'>";
      $pageHTML.="                      Quantity";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th class='text-end'>";
      $pageHTML.="                      Subtotal";
      $pageHTML.="                    </th>";
      $pageHTML.="                  </tr>";
      $pageHTML.="                </thead>";
      $pageHTML.="                <tbody>";
      

      if(!empty($jsonObj->orderItems)){
        //Loop through the API results for larger devices
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
          
          $pageHTML.="                <tr id='$itemObj->id' class='odd'>";
          if($img_show_order == 'enabled'){
            $pageHTML.="                  <td valign='middle' class='text-center'>";
            $pageHTML.="                    <div class='img-container'>";
            $pageHTML.="                      <img class='img-line-item' src='".$itemObj->thisImg."' alt='product image'/>";
            $pageHTML.="                    </div>";
            $pageHTML.="                  </td>";
          }

          $pageHTML.="                  <td valign='middle'>".$itemObj->description."</td>";
          $pageHTML.="                  <td valign='middle' class='text-end'>";
          if($itemObj->unitSalePrice > 0){
            $pageHTML.="                   <span class='text-danger'>$".number_format($itemObj->unitSalePrice,2)."</span><span class='strikethrough'>$".number_format($itemObj->unitPrice,2)."</span>";
          }
          else {
            $pageHTML.="                   $".number_format($itemObj->unitPrice,2);
          }
          $pageHTML.="                  </td>";
          $pageHTML.="                  <td valign='middle' class='text-center'>".$itemObj->quantity."x";
          $pageHTML.="                  </td>";
          $pageHTML.="                  <td valign='middle' class='text-end'>$".number_format($itemObj->totalPrice,2)."</td>";
          $pageHTML.="                </tr>";
        }
      }

      $pageHTML.="                </tbody>";
      $pageHTML.="              </table>";
      $pageHTML.="            </div>";  // table-responsive

      $pageHTML.="      </div>";  // col
      $pageHTML.="    </div>";  // row cart-table
      
      $pageHTML.="    <div class='row cart-totals-section'>";
      $pageHTML.="      <div class='col-12 col-md-6 offset-md-6'>";

      $pageHTML.="        <div class='row cart-totals'>";
      $pageHTML.="          <div class='col-12 text-start'>Cart Totals";
      $pageHTML.="          </div>";  // col
      $pageHTML.="        </div>";  // row
      
      $pageHTML.="        <div class='row cart-subtotal'>";
      $pageHTML.="          <div class='col-6 text-start'>Subtotal</div>"; 
      $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->subTotal,2)."</div>"; 
      $pageHTML.="        </div>";  // row

      $pageHTML.="        <div class='row cart-shipping'>";
      $pageHTML.="          <div class='col-6 text-start'>Estimated Shipping</div>"; 
      $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->shippingAmount,2)."</div>"; 
      $pageHTML.="        </div>";  // row

      if($jsonObj->taxAmountHST > 0){
        $pageHTML.="        <div class='row cart-hst'>";
        $pageHTML.="          <div class='col-6 text-start'>HST</div>"; 
        $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->taxAmountHST,2)."</div>"; 
        $pageHTML.="        </div>";  // row
      }

      if($jsonObj->taxAmountPST > 0){
        $pageHTML.="        <div class='row cart-pst'>";
        $pageHTML.="          <div class='col-6 text-start'>PST</div>"; 
        $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->taxAmountPST,2)."</div>"; 
        $pageHTML.="        </div>";  // row
      }
      
      if($jsonObj->taxAmountGST > 0){
        $pageHTML.="        <div class='row cart-gst'>";
        $pageHTML.="          <div class='col-6 text-start'>GST</div>"; 
        $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->taxAmountGST,2)."</div>"; 
        $pageHTML.="        </div>";  // row
      }

      $pageHTML.="        <div class='row cart-total'>";
      $pageHTML.="          <div class='col-6 text-start'>Total</div>"; 
      $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->total,2)."</div>"; 
      $pageHTML.="        </div>";  // row
       
      if($jsonObj->orderState == 'New'){
        $pageHTML.="        <div class='row checkout'>";
        $pageHTML.="          <div class='col-12'>"; 
        $pageHTML.="            <a class='btn btn-primary w-100 p-3' href='".add_query_arg('id',$jsonObj->id,airmed_pagelink('/airmed/airmed-checkout/'))."' >Proceed to Checkout</a>";
        $pageHTML.="          </div>"; 
        $pageHTML.="        </div>";  // row
      }
      
      $pageHTML.="          </div>";  // col
      $pageHTML.="        </div>";  // row cart-totals

      $pageHTML.="          </div>";  // card body
      $pageHTML.="        </div>"; //card

      $pageHTML.="      </div>";  // col
      $pageHTML.="    </div>";  // row 
      
      $pageHTML.="  </div>";  // airmed-content

      $pageHTML.= "</div>";  // end of airmed wrapper

      //$pageHTML.= includeModals();

      echo $pageHTML;
    }
    else {
      //$error_message = curl_strerror($errno);
      echo "<div>request error ({$errno}):\n {$err_message} </div>";
      if ($debug) echo "<div>request Error: $err </div>";
      else echo "<div> $err </div>";
    }
  }
  else { // redirect to login
    //if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-orders'));
  }

}

function airmed_cart_shortcode(){
  global $wp;
  $id = "";
  $debug = true;
  $pageHTML = "";
  $isEdit = false;
  $hasToken = false;

  if(isset($wp->query_vars["id"])){ 
    $id = $wp->query_vars["id"];
  }
  
  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}
    
  if ($hasToken){
    $airmed = new stdClass();
    $airmed = $_SESSION['__airmed'];

    
    $requestPath = '/API/Order/GetOrder/'.$id;
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    
    //print_r($requestArray);
    //$errno = 0;
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    // no error proceed with displaying data
    if ($errno === 200) {

      // date comes from server using PST so need to set timezone for date function
      date_default_timezone_set("America/Vancouver");

      //Create an array of objects from the JSON returned by the API
      $jsonObj = json_decode($response);

      // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
      $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
      
      $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
      
      $pageHTML.="  <div class='airmed-content airmed-cart'>";
      $pageHTML.="    <div class='row'>";
      $pageHTML.="      <div class='col-12'>";
      $pageHTML.="        <div class='col-inner'>";
      $pageHTML.="          <h4 class='content-title'>AirMed Cart</h4>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";
      $pageHTML.="    </div>";
      $pageHTML.="    <div class='row mb-2'>";  // order info
      
      $pageHTML.="      <div class='col-12 col-md-6 mb-2'>";  //order details
      $pageHTML.="        <div class='card airmed-order-card'>";
      $pageHTML.="          <div class='card-header'>";
      $pageHTML.="            <span>Order #:</span>";
      $pageHTML.="            <span>$jsonObj->orderNo<span>";
      $pageHTML.="          </div>";
      $pageHTML.="          <div class='card-body'>";
      //$pageHTML.="            <div class='row'>";
      //$pageHTML.="              <div class='col-12'>";
      //$pageHTML.="                <span>Date Ordered:</span>";
      //$pageHTML.="                <span>".date('Y-m-d g:ia',strtotime($jsonObj->dateCreated))."<span>";
      //$pageHTML.="              </div>";
      //$pageHTML.="            </div>";
      //$pageHTML.="            <div class='row'>";
      //$pageHTML.="              <div class='col-12'>";
      //$pageHTML.="                <span>Source:</span>";
      //$pageHTML.="                <span>$jsonObj->orderSource<span>";
      //$pageHTML.="              </div>";
      //$pageHTML.="            </div>";
      //$pageHTML.="            <div class='row'>";
      //$pageHTML.="              <div class='col-12'>";
      //$pageHTML.="                <span>Order State:</span>";
      //$pageHTML.="                <span>$jsonObj->orderState<span>";
      //$pageHTML.="              </div>";
      //$pageHTML.="            </div>";
      //$pageHTML.="            <div class='row'>";
      //$pageHTML.="              <div class='col-12'>";
      //$pageHTML.="                <span>Order Status:</span>";
      //$pageHTML.="                <span>$jsonObj->orderStatus<span>";
      //$pageHTML.="              </div>";
      //$pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Items in Order:</span>";
      $pageHTML.="                <span>$jsonObj->itemCount<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Plants in Order:</span>";
      $pageHTML.="                <span>$jsonObj->totalPlants<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Grams in Order:</span>";
      $pageHTML.="                <span>".number_format($jsonObj->totalGrams,1)."g<span>";
      $pageHTML.="                <span class='side-note'>(Max 150g)</span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";  // end of order details

      $pageHTML.="      <div class='col-12 col-md-6 mb-2'>";  // order details
      $pageHTML.="        <div class='card airmed-order-card'>";
      $pageHTML.="          <div class='card-header'>";
      $pageHTML.="            <span>Application Details</span>";
      $pageHTML.="          </div>";
      $pageHTML.="          <div class='card-body'>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Expires:</span>";
      //$pageHTML.="                <span>".date('Y-m-d',strtotime($jsonObj->expirationDate))."<span>";
      $pageHTML.="                <span>".date('Y-m-d',(int)substr($jsonObj->expirationDate,6,-10))."<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Max Grams/Month:</span>";
      $pageHTML.="                <span>".number_format($jsonObj->maxMonthlyGrams,1)."g<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <span>Grams/Last 30 days:</span>";
      $pageHTML.="                <span>".number_format($jsonObj->gramsLastThirtyDays,1)."g<span>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>"; 
      $pageHTML.="      </div>";  // end of app details
      $pageHTML.="    </div>";  // end of order row
      
      $pageHTML.="    <div class='alert alert-success hide d-flex align-items-center no-background' role='alert'>
                        <i class='dashicons dashicons-yes'></i>
                        Cart Updated
                      </div>";

      // mobile look
      $pageHTML.="    <div class='row airmed-mobile'>";
      $pageHTML.="      <div class='col'>";
      $pageHTML.="        <h5 class=''>Order Items</h5>";
      if(!empty($jsonObj->orderItems)){

        $img_show_order = get_option( 'airmed_options_img_show_order') ? "enabled": "";
        
        //Loop through the API results for mobile rows
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
          
          $pageHTML.="              <div class='card airmed-order-card mb-2'>";
          
          //processing part
          $pageHTML.= "         <div class='am-loading d-flex justify-content-center hide'>";
          $pageHTML.= "           <div>Updating...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
          $pageHTML.="          </div>";
          
          $pageHTML.="                <div class='card-body'>";
          if($img_show_order == 'enabled'){
            $pageHTML.="                  <div class='row pt-2'>";
            $pageHTML.="                    <div class='col text-center'>";
            $pageHTML.="                      <img class='img-line-item' src='".$itemObj->thisImg."' alt='product image'/>";
            $pageHTML.="                    </div>";
            $pageHTML.="                  </div>";
          }

          $pageHTML.="                  <div class='row p-2'>";
          $pageHTML.="                    <div class='col'>";
          $pageHTML.="                      <div class='item-name'>".substr($itemObj->description,0,strpos($itemObj->description,' ($'))."</div>";
          $pageHTML.="                      <div class='item-weight'>".substr($itemObj->description,strpos($itemObj->description,' ($'))."</div>";
          $pageHTML.="                    </div>";
          $pageHTML.="                  </div>";
          $pageHTML.="                  <div class='row'>";
          $pageHTML.="                    <div class='col'>";
          $pageHTML.="                      <div class='item-unit-price text-end p-2'>";
          $pageHTML.="                        <small class='multiplier'>".$itemObj->quantity." x</small>";
          if($itemObj->unitSalePrice > 0){
            $pageHTML.="                   <span class='strikethrough'>$".number_format($itemObj->unitPrice,2)."</span><span class='text-danger'>$".number_format($itemObj->unitSalePrice,2)."</span>";
          }
          else {
            $pageHTML.="                   $".number_format($itemObj->unitPrice,2);
          }
          $pageHTML.="                      </div>";
          $pageHTML.="                    </div>";
          $pageHTML.="                  </div>";

          $pageHTML.="                  <div class='row p-2'>";
          $pageHTML.="                    <div class='col-2 text-center'>";
          $pageHTML.="                      <a class='item-removeButton has-background' data-am-prodid='$itemObj->id' data-am-orderid='$jsonObj->id' data-am-quantity='$itemObj->quantity' data-am-source='".add_query_arg('id',$airmed->openOrderID,airmed_pagelink('/airmed/airmed-cart/'))."' href='#'><i title='Remove' class='dashicons dashicons-trash'></i></a>";
          $pageHTML.="                    </div>";
          $pageHTML.="                    <div class='col-6 text-center'>";
          $pageHTML.="                      <select class='form-select quantity' data-am-itemid='$itemObj->id' data-am-orderid='$jsonObj->id' data-am-quantity='$itemObj->quantity' data-am-refresh='".add_query_arg('id',$jsonObj->id,airmed_pagelink('/airmed/airmed-cart/'))."'>";
          for ($c = 1; $c <=10; $c++){
            $selected = ($itemObj->quantity == $c) ? "selected" : "";
            $pageHTML.="                      <option value='".$c."' $selected >".$c."</option>";
          }
          $pageHTML.="                      </select>";
          $pageHTML.="                    </div>";
          $pageHTML.="                    <div class='col-4 text-end'>";
          $pageHTML.="                      <span>$".number_format($itemObj->totalPrice,2)."</span>";
          $pageHTML.="                    </div>";
          $pageHTML.="                  </div>";
          $pageHTML.="                </div>";
          $pageHTML.="              </div>";
        }
      }

      $pageHTML.="      </div>";
      $pageHTML.="    </div>";  // end of mobile items row


      $pageHTML.="    <div class='row'>";
      $pageHTML.="      <div class='col col-12'>";
      $pageHTML.="        <div class='card airmed-order-card'>";
      $pageHTML.="          <div class='card-body'>";

      $pageHTML.="            <div class='row cart-table position-relative airmed-non-mobile'>";
      $pageHTML.="              <div class='col-12'>";

      //processing part
      $pageHTML.= "         <div class='am-loading d-flex justify-content-center hide'>";
      $pageHTML.= "           <div>Updating...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
      $pageHTML.="          </div>";

      $pageHTML.="            <div class='table-responsive'>";
      $pageHTML.="              <table id='airmed-cartTable' class='table'>";
      $pageHTML.="                <thead>";
      $pageHTML.="                  <tr role='row'>";
      $pageHTML.="                    <th class='text-center'>";
      $pageHTML.="                      ";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th class='text-center'>";
      $pageHTML.="                      ";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th class='text-start'>";
      $pageHTML.="                      Product";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th class='text-end'>";
      $pageHTML.="                      Price";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th class='text-center'>";
      $pageHTML.="                      Quantity";
      $pageHTML.="                    </th>";
      $pageHTML.="                    <th class='text-end'>";
      $pageHTML.="                      Subtotal";
      $pageHTML.="                    </th>";
      $pageHTML.="                  </tr>";
      $pageHTML.="                </thead>";
      $pageHTML.="                <tbody>";
      

      if(!empty($jsonObj->orderItems)){
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

          $pageHTML.="                <tr id='$itemObj->id' class='odd'>";

          $pageHTML.="                  <td valign='middle' class='text-center'>";
          $pageHTML.="                    <a class='item-removeButton has-background' data-am-prodid='$itemObj->id' data-am-orderid='$jsonObj->id' data-am-quantity='$itemObj->quantity' data-am-source='".add_query_arg('id',$airmed->openOrderID,airmed_pagelink('/airmed/airmed-cart/'))."' href='#'><i title='Remove' class='dashicons dashicons-trash'></i></a>";
          $pageHTML.="                  </td>";
          if($img_show_order == 'enabled'){
            $pageHTML.="                  <td valign='middle' class='text-center'>";
            $pageHTML.="                    <div class='img-container'>";
            $pageHTML.="                      <img class='img-line-item' src='".$itemObj->thisImg."' alt='product image'/>";
            $pageHTML.="                    </div>";
            $pageHTML.="                  </td>";
          }
          $pageHTML.="                  <td valign='middle'>".$itemObj->description."</td>";
          $pageHTML.="                  <td valign='middle' class='text-end'>";
          if($itemObj->unitSalePrice > 0){
            $pageHTML.="                   <span class='text-danger'>$".number_format($itemObj->unitSalePrice,2)."</span><span class='strikethrough'>$".number_format($itemObj->unitPrice,2)."</span>";
          }
          else {
            $pageHTML.="                   $".number_format($itemObj->unitPrice,2);
          }
          $pageHTML.="                  </td>";
          $pageHTML.="                  <td valign='middle' class='text-center'>";
          $pageHTML.="                    <select class='form-select quantity' data-am-itemid='$itemObj->id' data-am-orderid='$jsonObj->id' data-am-quantity='$itemObj->quantity' data-am-refresh='".add_query_arg('id',$jsonObj->id,airmed_pagelink('/airmed/airmed-cart/'))."'>";
          for ($c = 1; $c <=10; $c++){
            $selected = ($itemObj->quantity == $c) ? "selected" : "";
            $pageHTML.="                      <option value='".$c."' $selected >".$c."</option>";
          }
          $pageHTML.="                    </select>";
          $pageHTML.="                  </td>";
          $pageHTML.="                  <td valign='middle' class='text-end'>$".number_format($itemObj->totalPrice,2)."</td>";
          $pageHTML.="                </tr>";
        }
      }

      $pageHTML.="                </tbody>";
      $pageHTML.="              </table>";
      $pageHTML.="            </div>";  // table-responsive

      $pageHTML.="      </div>";  // col
      $pageHTML.="    </div>";  // row cart-table
      
      $pageHTML.="    <div class='row cart-totals-section'>";
      $pageHTML.="      <div class='col-12 col-md-6 offset-md-6 position-relative'>";

      //processing part
      $pageHTML.= "         <div class='am-loading d-flex justify-content-center hide'>";
      $pageHTML.= "           <div>Updating...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
      $pageHTML.="          </div>";

      $pageHTML.="        <div class='row cart-totals'>";
      $pageHTML.="          <div class='col-12 text-start'>Cart Totals";
      $pageHTML.="          </div>";  // col
      $pageHTML.="        </div>";  // row
      
      $pageHTML.="        <div class='row cart-subtotal'>";
      $pageHTML.="          <div class='col-6 text-start'>Subtotal</div>"; 
      $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->subTotal,2)."</div>"; 
      $pageHTML.="        </div>";  // row

      $pageHTML.="        <div class='row cart-shipping'>";
      $pageHTML.="          <div class='col-6 text-start'>Estimated Shipping</div>"; 
      $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->shippingAmount,2)."</div>"; 
      $pageHTML.="        </div>";  // row

      if($jsonObj->taxAmountHST > 0){
        $pageHTML.="        <div class='row cart-hst'>";
        $pageHTML.="          <div class='col-6 text-start'>HST</div>"; 
        $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->taxAmountHST,2)."</div>"; 
        $pageHTML.="        </div>";  // row
      }

      if($jsonObj->taxAmountPST > 0){
        $pageHTML.="        <div class='row cart-pst'>";
        $pageHTML.="          <div class='col-6 text-start'>PST</div>"; 
        $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->taxAmountPST,2)."</div>"; 
        $pageHTML.="        </div>";  // row
      }
      
      if($jsonObj->taxAmountGST > 0){
        $pageHTML.="        <div class='row cart-gst'>";
        $pageHTML.="          <div class='col-6 text-start'>GST</div>"; 
        $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->taxAmountGST,2)."</div>"; 
        $pageHTML.="        </div>";  // row
      }
      
      $pageHTML.="        <div class='row cart-total'>";
      $pageHTML.="          <div class='col-6 text-start'>Total</div>"; 
      $pageHTML.="          <div class='col-6 text-end'>$".number_format($jsonObj->total,2)."</div>"; 
      $pageHTML.="        </div>";  // row
      
      if(!empty($jsonObj->orderItems)){
        $pageHTML.="        <div class='row checkout'>";
        $pageHTML.="          <div class='col-12'>"; 
        $pageHTML.="            <a class='btn btn-primary w-100 p-3' href='".add_query_arg('id',$jsonObj->id,airmed_pagelink('/airmed/airmed-checkout/'))."' >Proceed to Checkout</a>";
        $pageHTML.="          </div>"; 
        $pageHTML.="        </div>";  // row
      }
      
      $pageHTML.="          </div>";  // col
      $pageHTML.="        </div>";  // row cart-totals

      $pageHTML.="          </div>";  // card body
      $pageHTML.="        </div>"; //card

      $pageHTML.="      </div>";  // col
      $pageHTML.="    </div>";  // row 
      
      $pageHTML.="  </div>";  // airmed-content

      $pageHTML.= "</div>";  // end of airmed wrapper

      //$pageHTML.= includeModals();

      echo $pageHTML;
    }
    else {
      //$error_message = curl_strerror($errno);
      echo "<div>request error ({$errno}):\n {$err_message} </div>";
      if ($debug) echo "<div>request Error: $err </div>";
      else echo "<div> $err </div>";
    }
  }
  else { // redirect to login
    //if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-orders'));
  }
}

function airmed_checkout_shortcode(){
  global $wp;
  $id = "";
  $debug = true;
  $pageHTML = "";
  $isEdit = false;
  $hasToken = false;

  if(isset($wp->query_vars["id"])){ 
    $id = $wp->query_vars["id"];
  }
  
  if(!empty($_SESSION['__amAuthToken'])) { $hasToken = true;}
    
  if ($hasToken){
    $requestPath = '/API/Order/GetOrder/'.$id;
    $requestArray = airmed_call_request($requestPath,'GET',true,null);
    
    //print_r($requestArray);
    //$errno = 0;
    $response = $requestArray['body'];
    $err = $requestArray['response']['message'];
    $errno = $requestArray['response']['code'];
    $err_message = $requestArray['response']['message'];

    // no error proceed with displaying data
    if ($errno === 200) {

      //Create an array of objects from the JSON returned by the API
      $jsonObj = json_decode($response);

      // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
      $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";
      
      $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
      
      $pageHTML.="    <div class='airmed-content airmed-checkout'>";
      //processing part
      $pageHTML.= "     <div class='am-loading d-flex justify-content-center hide'>";
      $pageHTML.= "       <div>Processing...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
      $pageHTML.="      </div>";

      $pageHTML.="      <div class='row'>";
      $pageHTML.="        <div class='col-12'>";
      $pageHTML.="          <div class='col-inner'>";
      $pageHTML.="            <h4 class='content-title'>AirMed Checkout</h4>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";

      $pageHTML.="      <div class='row shipping-payment'>";
      $pageHTML.="        <div class='col-12 col-sm-6 col-lg-7 col-xl-8'>";
      $pageHTML.="          <div>";

      $pageHTML.="            <div class='row shipping pt-2'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <h5 class=''>Shipping Info</h5>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";

      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-3'>Contact:</div>";
      $pageHTML.="              <div class='col-9'>$jsonObj->email</div>";
      $pageHTML.="            </div>";

      // Mailing Address
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-3'>Ship to:</div>";
      $pageHTML.="              <div class='col-9'>";

      $shipAddress = "";
      if (!empty($jsonObj->shipAddress->suite)){$shipAddress.= "#".$jsonObj->shipAddress->suite." - ";}
      if (!empty($jsonObj->shipAddress->streetNumber)){$shipAddress.= $jsonObj->shipAddress->streetNumber." ";}
      if (!empty($jsonObj->shipAddress->streetName)){$shipAddress.= $jsonObj->shipAddress->streetName;}
      if (!empty($jsonObj->shipAddress->floor)){$shipAddress.= "</br> Flr ".$jsonObj->shipAddress->floor;}
      if (!empty($jsonObj->shipAddress->streetAddress2)){$shipAddress.= "</br> ".$jsonObj->shipAddress->streetAddress2;}
      if (!empty($jsonObj->shipAddress->streetAddress3)){$shipAddress.= "</br> ".$jsonObj->shipAddress->streetAddress3;}
      if (!empty($jsonObj->shipAddress->city)){$shipAddress.= "</br> ".$jsonObj->shipAddress->city;}
      if (!empty($jsonObj->shipAddress->province)){$shipAddress.= ", ".$jsonObj->shipAddress->province;}
      if (!empty($jsonObj->shipAddress->country)){$shipAddress.= "</br> ".$jsonObj->shipAddress->country;}
      if (!empty($jsonObj->shipAddress->postalCode)){$shipAddress.= "</br> ".$jsonObj->shipAddress->postalCode;}
      $pageHTML.=$shipAddress;

      $pageHTML.="              </div>";
      $pageHTML.="            </div>";

      $pageHTML.="            <div class='row payment mt-2 pt-2'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <h5 class=''>Payment</h5>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";

      // payment setup
      $global_payments_method = get_option( 'airmed_options_global_payments_method','Sandbox' );
      $frameAction = $global_payments_method == 'Live' ? 'https://pay.realexpayments.com/pay' : 'https://pay.sandbox.realexpayments.com/pay';
      
      // get secret from API
      
      // used for internal testing
      //$merchantId = "airmedcanada";
      //$secret = "secret";
      
      $requestPath = '/API/GlobalPayments/GetGlobalPaymentAPIKeys/';
      $requestArray = airmed_call_request($requestPath,'GET',true,null);
    
      //print_r($requestArray);
      //$errno = 0;
      $response = $requestArray['body'];
      $err = $requestArray['response']['message'];
      $errno = $requestArray['response']['code'];
      $err_message = $requestArray['response']['message'];
      //Create an array of objects from the JSON returned by the API
      if ($errno === 200) {
        $jsonObjKeys = json_decode($response);
        $merchantId = $jsonObjKeys->merchantID;
        $secret = $jsonObjKeys->secret;
      }
      else {
        $pageHTML.="            <div class='row'>";
        $pageHTML.="              <div class='col-12'>";

        //$error_message = curl_strerror($errno);
        $pageHTML.="<div>request error ({$errno}):\n {$err_message} </div>";
        if ($debug) $pageHTML.="<div>request Error: $err </div>";
        else $pageHTML.="<div> $err </div>";
        $pageHTML.="              <div>Unable to get GlobalPayments info.</br>Please refresh the page.</br>If this error persists, please contact support</div>>";
        $pageHTML.="              </div>>";
        $pageHTML.="            </div>";
      }

      $subAccount = "";
      $amount = $jsonObj->total;
      $amount = str_replace(".","",$amount);
      $timestamp = date("YmdHis");
      
      $currency = "CAD";
      $custnum = $jsonObj->clientNO;
      $orderId = $timestamp."-".$jsonObj->id;

      $inputStr = sha1($timestamp.".".$merchantId.".".$orderId.".".$amount.".".$currency);
      $sha1Str = sha1($inputStr.".".$secret);

      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <form id='airmed-payment-form' action='$frameAction' method='POST' target='airmed-payment-iframe' data-am-orderid='$jsonObj->id' data-am-patientid='$jsonObj->patientID'>";
      $pageHTML.="                  <input type='hidden' name='TIMESTAMP' value='$timestamp'>";
      $pageHTML.="                  <input type='hidden' name='MERCHANT_ID' value='$merchantId'>";
      $pageHTML.="                  <input type='hidden' name='ACCOUNT' value='$subAccount'>";
      $pageHTML.="                  <input type='hidden' name='ORDER_ID' value='$orderId'>";
      $pageHTML.="                  <input type='hidden' name='AMOUNT' value='$amount'>";
      $pageHTML.="                  <input type='hidden' name='CURRENCY' value='$currency'>";
      $pageHTML.="                  <input type='hidden' name='SHA1HASH' value='$sha1Str'>";
      $pageHTML.="                  <input type='hidden' name='AUTO_SETTLE_FLAG' value='1'>";
      $pageHTML.="                  <input type='hidden' name='HPP_VERSION' value='2'>";
      $pageHTML.="                  <!-- iFrame Optimization Fields -->";
      $pageHTML.="                  <input type='hidden' name='HPP_POST_DIMENSIONS' value='".airmed_pagelink('/airmed/airmed-checkout/')."'>";
      $pageHTML.="                  <input type='hidden' name='HPP_POST_RESPONSE' value='".airmed_pagelink('/airmed/airmed-checkout/')."'>";
      $pageHTML.="                  <!-- End iFrame Optimization Fields -->";
      $pageHTML.="                  <input type='hidden' name='MERCHANT_RESPONSE_URL' value='".add_query_arg('id',$jsonObj->id,airmed_pagelink('/airmed/airmed-order/'))."'>";
      $pageHTML.="                  <input type='submit' value='Pay via Global Payments'>";
      $pageHTML.="                </form>";
      
      //processing part
      $pageHTML.= "                 <div class='am-loading d-flex justify-content-center p-3 position-relative hide'>";
      $pageHTML.= "                   <div>Loading GlobalPayments form...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
      $pageHTML.="                  </div>";
      
      $pageHTML.="               <iframe class='hide' id='airmed-payment-iframe' name='airmed-payment-iframe' frameborder='0' scrolling='no'></iframe>";

      //$pageHTML.="              <a class='btn btn-primary'>Pay via Global Payments</a>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";

      $pageHTML.="          </div>";
      
      $pageHTML.="        </div>";
      $pageHTML.="        <div class='col-12 col-sm-6 col-lg-5 col-xl-4 airmed-order-summary'>";
      $pageHTML.="          <div class='card'>";

      //processing part
      $pageHTML.= "           <div class='am-loading d-flex justify-content-center hide'>";
      $pageHTML.= "             <div>Updating...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
      $pageHTML.="            </div>";

      $pageHTML.="            <div class='card-body'>";
      $pageHTML.="            <div class='row'>";
      $pageHTML.="              <div class='col-12'>";
      $pageHTML.="                <div class='col-inner'>";
      $pageHTML.="                  <h5 class='d-inline-block'>Order Summary</h5>";
      $pageHTML.="                  <a class='btn btn-secondary btn-sm btn-edit' href='".add_query_arg('id',$jsonObj->id,airmed_pagelink('/airmed/airmed-cart/'))."'>Edit</a>";
      $pageHTML.="                </div>";
      $pageHTML.="              </div>";
      $pageHTML.="            </div>";

      //Loop through the API results
      foreach($jsonObj->orderItems as $itemObj) {
        $pageHTML.="            <div class='row'>";
        $pageHTML.="              <div class='col-6 col-xxl-7'>";
        $pageHTML.="                <div class='item-name'>".substr($itemObj->description,0,strpos($itemObj->description,' ($'))."</div>";
//        $pageHTML.="                <div class='item-weight'>".substr($itemObj->description,strpos($itemObj->description,' ($'))."</div>";
        $pageHTML.="              </div>";
        $pageHTML.="              <div class='col-2 text-end'>";
        $pageHTML.="                <small class='multiplier'>x".$itemObj->quantity."</small>";
        $pageHTML.="              </div>";
        $pageHTML.="              <div class='col-4 col-xxl-3 text-end'>";
        $pageHTML.="                <span>$".number_format($itemObj->totalPrice,2)."</span>";
        $pageHTML.="              </div>";
        $pageHTML.="            </div>";
      }

      //$pageHTML.="        <div class='row cart-totals'>";
      //$pageHTML.="          <div class='col-12 text-start'>Cart Totals";
      //$pageHTML.="          </div>";  // col
      //$pageHTML.="        </div>";  // row
      
      $pageHTML.="            <div class='row cart-subtotal'>";
      $pageHTML.="              <div class='col-8 text-start'>Subtotal</div>"; 
      $pageHTML.="              <div class='col-4 text-end'>$".number_format($jsonObj->subTotal,2)."</div>"; 
      $pageHTML.="            </div>";  // row

      $pageHTML.="            <div class='row cart-shipping'>";
      $pageHTML.="              <div class='col-8 text-start'>Estimated Shipping</div>"; 
      $pageHTML.="              <div class='col-4 text-end'>$".number_format($jsonObj->shippingAmount,2)."</div>"; 
      $pageHTML.="            </div>";  // row

      if(($jsonObj->hasCoupon == 'true') && ($jsonObj->couponPreTax == 'true')) {
        $pageHTML.="            <div class='row cart-promo'>";
        $pageHTML.="              <div class='col-8'>$jsonObj->couponCode $jsonObj->couponDescription";
        $pageHTML.="                <div class='promo-remove'>";
        $pageHTML.="                  <a id='airmed-remove-coupon' class='' data-am-orderid='$jsonObj->id' href='#'>";
        $pageHTML.="                    <i class='dashicons dashicons-trash'></i>Remove Coupon";
        $pageHTML.="                  </a>";
        $pageHTML.="                </div>";
        $pageHTML.="              </div>"; 
        $pageHTML.="              <div class='col-4 text-end'>$".number_format($jsonObj->couponTotal,2)."</div>"; 
        $pageHTML.="            </div>";  // row
      }

      if($jsonObj->taxAmountHST > 0){
        $pageHTML.="            <div class='row cart-hst'>";
        $pageHTML.="              <div class='col-8 text-start'>HST</div>"; 
        $pageHTML.="              <div class='col-4 text-end'>$".number_format($jsonObj->taxAmountHST,2)."</div>"; 
        $pageHTML.="            </div>";  // row
      }

      if($jsonObj->taxAmountPST > 0){
        $pageHTML.="            <div class='row cart-pst'>";
        $pageHTML.="              <div class='col-8 text-start'>PST</div>"; 
        $pageHTML.="              <div class='col-4 text-end'>$".number_format($jsonObj->taxAmountPST,2)."</div>"; 
        $pageHTML.="            </div>";  // row
      }
      
      if($jsonObj->taxAmountGST > 0){
        $pageHTML.="            <div class='row cart-gst'>";
        $pageHTML.="              <div class='col-8 text-start'>GST</div>"; 
        $pageHTML.="              <div class='col-4 text-end'>$".number_format($jsonObj->taxAmountGST,2)."</div>"; 
        $pageHTML.="            </div>";  // row
      }

      if($jsonObj->hasCoupon <> 'true'){
        $pageHTML.="            <div class='row cart-promo'>";
        $pageHTML.="              <div class='col-6'><input id='airmed-coupon-code' placeholder='Promo Code' class='w-100' /></div>"; 
        $pageHTML.="              <div class='col-6 text-end'><a id='airmed-apply-coupon' data-am-orderid='$jsonObj->id' class='btn btn-secondary w-100'>Apply</a></div>"; 
        $pageHTML.="            </div>";  // row
      }
      if($jsonObj->hasCoupon == 'true' && $jsonObj->couponPreTax <> 'true' ){
        $pageHTML.="            <div class='row cart-promo'>";
        $pageHTML.="              <div class='col-8'>$jsonObj->couponCode $jsonObj->couponDescription";
        $pageHTML.="                <div class='promo-remove'>";
        $pageHTML.="                  <a id='airmed-remove-coupon' class='' data-am-orderid='$jsonObj->id' href='#'>";
        $pageHTML.="                    <i class='dashicons dashicons-trash'></i>Remove Coupon";
        $pageHTML.="                  </a>";
        $pageHTML.="                </div>";
        $pageHTML.="              </div>"; 
        $pageHTML.="              <div class='col-4 text-end'>$".number_format($jsonObj->couponTotal,2)."</div>"; 
        $pageHTML.="            </div>";  // row
      }      
      $pageHTML.="              <div class='row cart-total'>";
      $pageHTML.="                <div class='col-8 text-start'>Total</div>"; 
      $pageHTML.="                <div class='col-4 text-end'>$".number_format($jsonObj->total,2)."</div>"; 
      $pageHTML.="              </div>";  // row

      $pageHTML.="            </div>";  // card-body
      $pageHTML.="          </div>";  // card

      $pageHTML.="        </div>";  // ends col 4
      $pageHTML.="      </div>";  // ends row

      $pageHTML.="    </div>";  // airmed-content

      $pageHTML.= "</div>";  // end of airmed wrapper

      //$pageHTML.= includeModals();

      echo $pageHTML;
    }
    else {
      //$error_message = curl_strerror($errno);
      echo "<div>request error ({$errno}):\n {$err_message} </div>";
      if ($debug) echo "<div>request Error: $err </div>";
      else echo "<div> $err </div>";
    }
  }
  else { // redirect to login
    //if (wp_safe_redirect( airmed_pagelink('/airmed/airmed-login/'),301)){
    //  exit;
    //}
    airmed_login_shortcode(array('returnUrl' => 'airmed-orders'));
  }
}


?>