<?php
function airmed_inventory_shortcode(){
  global $wp;
  $debug = true;
  $pageHTML = "";
  $hasToken = false;
  //$airmed = AirMed::get_instance(); //added to use public functions from class

  $airmed = new stdClass();
  $airmed = $_SESSION['__airmed'];

  $amCat = new stdClass();
  $amCat->theme_nav = get_option( 'airmed_options_theme_nav',1 );
  $amCat->class_nav = "nav-theme".$amCat->theme_nav;
  $amCat->theme_catalog = get_option( 'airmed_options_theme_catalog',1 );
  $amCat->class_catalog = "catalog-theme".$amCat->theme_catalog;
  $amCat->theme_filter = get_option( 'airmed_options_theme_filter',1 );
  $amCat->class_filter = "filter-theme".$amCat->theme_filter;
  $amCat->theme_modal = get_option( 'airmed_options_theme_modal',1 );
  $amCat->class_modal = "modal-theme".$amCat->theme_modal;
  $amCat->cat_carousel = get_option( 'airmed_options_cat_carousel') ? "enabled": "";
  $amCat->carousel = get_option( 'airmed_options_carousel') ? "enabled": "";

  $amCat->cat_img_hover = get_option( 'airmed_options_cat_img_hover') ? "enabled": "";
  $amCat->cat_img_hover1 = get_option( 'airmed_options_cat_img_hover1','Brand');
  $amCat->cat_img_hover2 = get_option( 'airmed_options_cat_img_hover2','Brand');
  $amCat->img_hover = get_option( 'airmed_options_img_hover') ? "enabled": "";
  $amCat->img_hover1 = get_option( 'airmed_options_img_hover1','Brand');
  $amCat->img_hover2 = get_option( 'airmed_options_img_hover2','Brand');

  $amCat->tab_prod = '';
  $amCat->tab_deriv = '';
  $amCat->tab_merch = '';
  $amCat->tab_acc = '';
  $amCat->tab_plants = '';
  $amCat->tab_mats = '';
  $amCat->tab_prod_s = 'false';
  $amCat->tab_deriv_s = 'false';
  $amCat->tab_merch_s = 'false';
  $amCat->tab_acc_s = 'false';
  $amCat->tab_plants_s = 'false';
  $amCat->tab_mats_s = 'false';
  $amCat->panel_prod = '';
  $amCat->panel_derivs = '';
  $amCat->panel_merch = '';
  $amCat->panel_acc = '';
  $amCat->panel_plants = '';
  $amCat->panel_mats = '';
  $amCat->prod_type = '';
  $amCat->panel_prod = '';

  $amCat->filt_prof_all = '';
  $amCat->filt_prof_indica = '';
  $amCat->filt_prof_sativa = '';
  $amCat->filt_prof_hy50 = '';
  $amCat->filt_prof_hyind = '';
  $amCat->filt_prof_hysat = '';
  $amCat->filt_prof_other = '';

  $amCat->filt_thc_all = '';
  $amCat->filt_thc_low = '';
  $amCat->filt_thc_mid = '';
  $amCat->filt_thc_high = '';
  
  $amCat->filt_cbd_all = '';
  $amCat->filt_cbd_low = '';
  $amCat->filt_cbd_mid = '';
  $amCat->filt_cbd_high = '';

  $amCat->canAddToCart = false;

  if(!empty($_SESSION['__amAuthToken'])) { 
    $hasToken = true;
    $amCat->canAddToCart = $airmed->patient->canPurchase;
  }
  $amCat->hasToken = $hasToken;
  
  $amCat->slideout = get_option('airmed_options_login_type',1 );

  
  $queryTab = "";
  if(isset($wp->query_vars["tab"])){ 
    $queryTab = sanitize_text_field($wp->query_vars["tab"]);
  }
  $querySearch = "";
  if(isset($wp->query_vars["search"])){ 
    $querySearch = sanitize_text_field($wp->query_vars["search"]);
  }
  $queryFilters = "";
  if(isset($wp->query_vars["filters"])){ 
    $queryFilters = sanitize_text_field($wp->query_vars["filters"]);
  }
  
  // setup tabs after login
  switch ($queryTab) {
    case 'airmed-derivs':
      $amCat->prod_type = 'derivativeProducts';
      break;
    case 'airmed-plants':
      $amCat->prod_type = 'plants';
      break;
    case 'airmed-materials':
      $amCat->prod_type = 'sourceMaterials';
      break;
    case 'airmed-accessories':
      $amCat->prod_type = 'accessories';
      break;
    case 'airmed-merchandise':
      $amCat->prod_type = 'merchandise';
      break;
    default:
      $amCat->prod_type = 'products';
      break;
  }
  
  // setup filters after login
  $filt_prof = '';
  $filt_thc = '';
  $filt_cbd = '';
  $aFilters = explode(',',$queryFilters);
  if(count($aFilters) > 1){
    $aFilt = explode('=',$aFilters[0]);
    $filt_prof = $aFilt[1];
    $aFilt = explode('=',$aFilters[1]);
    $filt_thc = $aFilt[1];
    $aFilt = explode('=',$aFilters[2]);
    $filt_cbd = $aFilt[1];
  }
  //aLog($filt_prof." - ".$filt_thc." - ".$filt_cbd);
  if($amCat->theme_filter == 1){
    if($filt_prof ==''){$amCat->filt_prof_all = 'checked';}
    if($filt_prof == '.indica'){$amCat->filt_prof_indica = 'checked';}
    if($filt_prof == '.sativa'){$amCat->filt_prof_sativa = 'checked';}
    if($filt_prof == '.hy50'){$amCat->filt_prof_hy50 = 'checked';}
    if($filt_prof == '.hyind'){$amCat->filt_prof_hyind = 'checked';}
    if($filt_prof == '.hysat'){$amCat->filt_prof_hysat = 'checked';}
    if($filt_prof == '.other'){$amCat->filt_prof_other = 'checked';}

    if($filt_thc == ''){$amCat->filt_thc_all = 'checked';}
    if($filt_thc == '.thcLow'){$amCat->filt_thc_low = 'checked';}
    if($filt_thc == '.thcMid'){$amCat->filt_thc_mid = 'checked';}
    if($filt_thc == '.thcHigh'){$amCat->filt_thc_high = 'checked';}
    
    if($filt_cbd == ''){$amCat->filt_cbd_all = 'checked';}
    if($filt_cbd == '.cbdLow'){$amCat->filt_cbd_low = 'checked';}
    if($filt_cbd == '.cbdMid'){$amCat->filt_cbd_mid = 'checked';}
    if($filt_cbd == '.cbdHigh'){$amCat->filt_cbd_high = 'checked';}
  }
  else if($amCat->theme_filter == 2){
    //if($filt_prof ==''){$amCat->filt_prof_all = 'filter-options-item-selected';}
    if($filt_prof == '.indica'){$amCat->filt_prof_indica = 'filter-options-item-selected';}
    if($filt_prof == '.sativa'){$amCat->filt_prof_sativa = 'filter-options-item-selected';}
    if($filt_prof == '.hy50'){$amCat->filt_prof_hy50 = 'filter-options-item-selected';}
    if($filt_prof == '.hyind'){$amCat->filt_prof_hyind = 'filter-options-item-selected';}
    if($filt_prof == '.hysat'){$amCat->filt_prof_hysat = 'filter-options-item-selected';}
    if($filt_prof == '.other'){$amCat->filt_prof_other = 'filter-options-item-selected';}

    //if($filt_thc == ''){$amCat->filt_thc_all = 'filter-options-item-selected';}
    if($filt_thc == '.thcLow'){$amCat->filt_thc_low = 'filter-options-item-selected';}
    if($filt_thc == '.thcMid'){$amCat->filt_thc_mid = 'filter-options-item-selected';}
    if($filt_thc == '.thcHigh'){$amCat->filt_thc_high = 'filter-options-item-selected';}
    
    //if($filt_cbd == ''){$amCat->filt_cbd_all = 'filter-options-item-selected';}
    if($filt_cbd == '.cbdLow'){$amCat->filt_cbd_low = 'filter-options-item-selected';}
    if($filt_cbd == '.cbdMid'){$amCat->filt_cbd_mid = 'filter-options-item-selected';}
    if($filt_cbd == '.cbdHigh'){$amCat->filt_cbd_high = 'filter-options-item-selected';}
  }
  else if($amCat->theme_filter == 3){
    //if($filt_prof ==''){$amCat->filt_prof_all = 'selected';}
    if($filt_prof == '.indica'){$amCat->filt_prof_indica = 'selected';}
    if($filt_prof == '.sativa'){$amCat->filt_prof_sativa = 'selected';}
    if($filt_prof == '.hy50'){$amCat->filt_prof_hy50 = 'selected';}
    if($filt_prof == '.hyind'){$amCat->filt_prof_hyind = 'selected';}
    if($filt_prof == '.hysat'){$amCat->filt_prof_hysat = 'selected';}
    if($filt_prof == '.other'){$amCat->filt_prof_other = 'selected';}

    //if($filt_thc == ''){$amCat->filt_thc_all = 'selected';}
    if($filt_thc == '.thcLow'){$amCat->filt_thc_low = 'selected';}
    if($filt_thc == '.thcMid'){$amCat->filt_thc_mid = 'selected';}
    if($filt_thc == '.thcHigh'){$amCat->filt_thc_high = 'selected';}
    
    //if($filt_cbd == ''){$amCat->filt_cbd_all = 'selected';}
    if($filt_cbd == '.cbdLow'){$amCat->filt_cbd_low = 'selected';}
    if($filt_cbd == '.cbdMid'){$amCat->filt_cbd_mid = 'selected';}
    if($filt_cbd == '.cbdHigh'){$amCat->filt_cbd_high = 'selected';}
  }
  //aLog($amCat);
  
  // Catalog Theme 1 layout (portrait white space)
  function catalogTheme1($amCat,$item){
    $cbdInfo = false;
    $pageHTML = '';
    
    //setup values
    if($item->prodType == 'products'){
      $cbdInfo = true;
      $item->packageSize = number_format($item->packageSize,2).' g';
    }
    if($item->prodType == 'derivativeProducts'){
      $cbdInfo = true;
      $item->name = $item->name.' ('.$item->group.')';
      $item->packageSize = $item->volume.' ml / '.$item->weight.' g';
    }
    
    $pageHTML.="<div id='$item->prodId' class='airmed-item  $item->filter'>";


    //if item is on sale
    if($item->onSale){$pageHTML.="  <div title='On Sale' class='prod-item-sale'><i class='dashicons dashicons-tag'></i></div>";}
    
    //$pageHTML.="  <div title='More Info' class='prod-item-info' data-am-toggle='modal' data-am-target='#airmed-modal-products' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'><i class='dashicons dashicons-info'></i></div>";

    #//Show the image first to keep the top edge of the grid level
    $pageHTML.="  <div title='More Info...' class='prod-image' data-am-toggle='modal' data-am-target='#airmed-modal-products' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
    //if (empty($item->thisImg))$pageHTML.="&nbsp;";
    //else $pageHTML.="<div class='img-container'><img class='img-cell' src='".$item->thisImg."' /></div>";
    // deal with single image or carousel
    
    //$pageHTML.="      <a >";
    if(($amCat->cat_img_hover == 'enabled') && ($item->thisImgType == 'multi')){
      $pageHTML.="        <div class='img-hover'>";
      $pageHTML.="          <img class='img-cell normal' src='".$item->hover1."' alt='product image 1'/>";
      $pageHTML.="          <img class='img-cell hover' src='".$item->hover2."' alt='product image 2'/>";
      $pageHTML.="        </div>";
    }
    else if(($amCat->cat_carousel == 'enabled') && ($item->thisImgType == 'multi')){
      $pageHTML.="        <div class='img-container'>";
      $pageHTML.="          <div id='airmed-product-images-$item->prodId' class='am-carousel am-slide' data-am-ride='carousel'>";
      $pageHTML.="            <div class='carousel-indicators'>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='0' class='active'></button>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='1'></button>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='2'></button>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='carousel-inner'>";
      $pageHTML.="                <div class='carousel-item active'>";
      $pageHTML.="                  <img class='d-block w-100 img-responsive' src='$item->imgBrand' alt='First slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='carousel-item'>";
      $pageHTML.="                    <img class='d-block w-100 img-responsive' src='$item->imgStrain' alt='Second slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='carousel-item'>";
      $pageHTML.="                    <img class='d-block w-100 img-responsive' src='$item->imgProduct' alt='Third slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="            </div>";
      $pageHTML.="          </div>";
      $pageHTML.="        </div>";
    }
    else {
      $pageHTML.="      <div class='img-container'>";
      $pageHTML.="        <img class='img-cell' src='".$item->thisImg."' alt='product image'/>";
      $pageHTML.="      </div>";
    }
    //$pageHTML.="      </a>";
    
    //$pageHTML.="     <div class='user-login'><a>Sign In</a></div>";
    $pageHTML.="   </div>"; // end of prod-image

    $pageHTML.="  <div title='More Info...' class='prod-info'  data-am-toggle='modal' data-am-target='#airmed-modal-products' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
    $pageHTML.="    <div class='title'>".$item->strainName."</div>";
    $pageHTML.="    <p class='producer'>by ".$item->producerName."</p>";
    $pageHTML.="    <p class='description'>".$item->group."</p>";
    if($cbdInfo){
      $pageHTML.="   <div class='thc-cbd-info'>";
      $pageHTML.="     <p><span class='thc-info'>THC ".number_format($item->thc,2)."</span><span> | </span><span class='cbd-info'>CBD ".number_format($item->cbd,2)."</span></p>";
      //$pageHTML.="     <p><span class='cbd-info'>CBD ".number_format($item->cbd,2)."</span></p>";
      $pageHTML.="     <p><span class='pkg-size'>$item->packageSize</span></p>";
      if($item->discreteUnits){
        $pageHTML.="        <p><span>$item->unitType ($item->unitCount per Package)</span></p>";
      }
      $pageHTML.="   </div>";

    }
    
    $pageHTML.="    <p class='price'>$".number_format($item->packagePrice,2)."</p>";
    $pageHTML.="    <p class='stock'>".$item->status."</p>";
    $pageHTML.="  </div>";
    $pageHTML.="  <div class='add-to-cart-button'>";
    //processing part
    $pageHTML.= "  <div class='am-loading d-flex justify-content-center hide'>";
    $pageHTML.= "    <div>Adding...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
    $pageHTML.="   </div>";

    if($amCat->canAddToCart){
      //$pageHTML.="  <div class='add-to-cart-button'>";
      $pageHTML.="    <a href='' class='primary button is-small mb-0 add_to_cart_button is-outline' data-am-prodid='$item->prodId' >Add to cart</a>";
    }
    elseif ($amCat->hasToken){
      $pageHTML.="    <span class='primary is-small mb-0 add_to_cart_button is-outline approval' >Pending Approval</span>";
    }
    else{
      if($amCat->slideout == 1){
        $pageHTML.="    <a href='".airmed_pagelink('/airmed/airmed-login')."' class='primary button is-small mb-0 add_to_cart_button is-outline' >Login</a>";
      }
      else{  // slideout or modal
        $pageHTML.="    <a href='' class='primary button is-small mb-0 add_to_cart_button is-outline' data-am-toggle='modal' data-am-target='#airmed-modal-login'>Login</a>";
      }
    }
    $pageHTML.="  </div>";  // end of add-to-cart
    $pageHTML.="</div>"; //end of airmed-item
        
    return $pageHTML;
  }

  // Catalog Theme 2 layout (portrait cards)
  function catalogTheme2($amCat,$item){
    $cbdInfo = false;
    $profileInfo = false;
    $pageHTML = '';
    global $hasToken;
    aLog($item);
    //setup values
    if($item->prodType == 'products'){
      $cbdInfo = true;
      $profileInfo = true;
      $item->packageSize = number_format($item->packageSize,2).' g';
    }
    if($item->prodType == 'derivativeProducts'){
      $cbdInfo = true;
      $profileInfo = true;
      $item->name = $item->name.' ('.$item->group.')';
      $item->packageSize = $item->volume.' ml / '.$item->weight.' g';
    }
    if($item->prodType == 'plants'){
      $profileInfo = true;
    }

    $pageHTML.="<div id='$item->prodId' class='card airmed-item $item->filter'>";
    
    //processing part
    $pageHTML.= "  <div class='am-loading d-flex justify-content-center hide'>";
    $pageHTML.= "    <div>Adding...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
    $pageHTML.="   </div>";
    
    $pageHTML.="  <div class='product-image fix'  title='More Info...' href=''  data-am-toggle='modal' data-am-target='#airmed-modal-products' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
    //$pageHTML.="      <a title='More Info...' href=''  data-am-toggle='modal' data-am-target='#airmed-modal-products' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
    // deal with single image or carousel
    if(($amCat->cat_img_hover == 'enabled') && ($item->thisImgType == 'multi')){
      $pageHTML.="        <div class='img-hover'>";
      $pageHTML.="          <img class='card-img-top normal' src='".$item->hover1."' alt='product image 1'/>";
      $pageHTML.="          <img class='card-img-top hover' src='".$item->hover2."' alt='product image 2'/>";
      $pageHTML.="        </div>";
    }
    else if(($amCat->cat_carousel == 'enabled') && ($item->thisImgType == 'multi')){
      //$pageHTML.="        <div class='img-container'>";
      $pageHTML.="          <div id='airmed-product-images-$item->prodId' class='am-carousel am-slide' data-am-ride='carousel'>";
      $pageHTML.="            <div class='carousel-indicators'>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='0' class='active'></button>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='1'></button>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='2'></button>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='carousel-inner'>";
      $pageHTML.="                <div class='carousel-item active'>";
      $pageHTML.="                  <img class='d-block w-100 img-responsive' src='$item->imgBrand' alt='First slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='carousel-item'>";
      $pageHTML.="                    <img class='d-block w-100 img-responsive' src='$item->imgStrain' alt='Second slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='carousel-item'>";
      $pageHTML.="                    <img class='d-block w-100 img-responsive' src='$item->imgProduct' alt='Third slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="            </div>";
      $pageHTML.="          </div>";
    }
    else {
      $pageHTML.="        <img src='$item->thisImg' class='card-img-top' alt='product image'>";
    }
    
    //$pageHTML.="      </a>";
    //$pageHTML.="      <div class='new-area'>";
    //$pageHTML.="          <div class='new'>";
    //$pageHTML.="              <span class='text-new'><span>New</span></span>";
    //$pageHTML.="          </div>";
    //$pageHTML.="      </div>";
    if($item->onSale){
      $pageHTML.="      <div class='prod-item-sale'>";
      $pageHTML.="        <img src='".plugins_url('../images/onsale.png',__FILE__)."' />";
      $pageHTML.="      </div>";
    }
    $pageHTML.="  </div>";
    $pageHTML.="  <div class='card-body' title='More Info...' href=''  data-am-toggle='modal' data-am-target='#airmed-modal-products' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
    $pageHTML.="      <h4 class='name'>$item->strainName</h4>";
    $pageHTML.="      <h5 class='name text-muted'><em>$item->name</em></h5>";
    if($item->discreteUnits){
      $pageHTML.="      <h6 class='discrete-units'>";
      $pageHTML.="        <span>$item->unitType ($item->unitCount per Package)</span>";
      $pageHTML.="      </h6>";
    }
    $pageHTML.="      <span class='amount'>";
    $pageHTML.="          Total:  $".number_format($item->packagePrice,2);
    $pageHTML.="      </span>";
    $pageHTML.="  </div>";
    $pageHTML.="  <div class='card-footer'>";
    if($cbdInfo){
      $pageHTML.="      <div class='thccbd'>";
      $pageHTML.="          <div class='thc' value='".number_format($item->thc,2)."'><span>THC:</span>$item->thcLessThan ".number_format($item->thc,2)." $item->measure</div>";
      $pageHTML.="          <div class='cbd' value='".number_format($item->cbd,2)."'><span>CBD:</span>$item->cbdLessThan ".number_format($item->cbd,2)." $item->measure</div>";
      $pageHTML.="      </div>";
    }
    $pageHTML.="      <div class='extra-info pull-right'>";
    if($profileInfo){$pageHTML.="          <div class='profile text-end'>$item->category</div>";}
    $pageHTML.="          <div class='text-end'>";
    $pageHTML.="              <strong>$item->packageSize</strong>";
    $pageHTML.="          </div>";
    $pageHTML.="      </div>";
    if($amCat->canAddToCart){
      $pageHTML.="      <div class='add-to-cart' title='Add to Cart'>";
      //$pageHTML.="        <a href='' class='primary button is-small mb-0 add_to_cart_button is-outline' >Add to cart</a>";
      $pageHTML.="          <a data-am-prodid='$item->prodId'>";
      $pageHTML.="            <i class='dashicons dashicons-cart'></i>";
      $pageHTML.="            <i class='dashicons dashicons-yes hide'></i>";
      //$pageHTML.="            <i class='dashicons dashicons-lock'></i>";
      $pageHTML.="          </a>";
      $pageHTML.="      </div>";
    }
    elseif ($amCat->hasToken){
      $pageHTML.="      <div class='add-to-cart' title='Pending Approval'>";
      $pageHTML.="          <a >";
      $pageHTML.="            <i class='dashicons dashicons-warning'></i>";
      $pageHTML.="          </a>";
      $pageHTML.="      </div>";
    }
    else{
      $pageHTML.="      <div class='add-to-cart' title='Login'>";
      if($amCat->slideout == 1){
        $pageHTML.="          <a href='".airmed_pagelink('/airmed/airmed-login')."'>";
        $pageHTML.="            <i class='dashicons dashicons-lock'></i>";
        $pageHTML.="          </a>";

      }
      else{  // slideout or modal
        $pageHTML.="          <a href='' data-am-toggle='modal' data-am-target='#airmed-modal-login'>";
        $pageHTML.="            <i class='dashicons dashicons-lock'></i>";
        $pageHTML.="          </a>";
      }
      
      $pageHTML.="      </div>";
    }
    $pageHTML.="  </div>";
    $pageHTML.="</div>";
    return $pageHTML;
  }

  // Catalog Theme 3 layout (Landscape)
  function catalogTheme3($amCat,$item){
    $cbdInfo = false;
    $profileInfo = false;
    $pageHTML = '';
    global $hasToken;

    //setup values
    if($item->prodType == 'products'){
      $cbdInfo = true;
      $profileInfo = true;
      $item->packageSize = number_format($item->packageSize,2).' g';
    }
    if($item->prodType == 'derivativeProducts'){
      $cbdInfo = true;
      $profileInfo = true;
      $item->name = $item->name.' ('.$item->group.')';
      $item->packageSize = $item->volume.' ml / '.$item->weight.' g';
    }
    if($item->prodType == 'plants'){
      $profileInfo = true;
    }
    if($item->prodType == 'accessories' || $item->prodType == 'merchandise'){
      $item->packageSize = $item->category;
    }
    
    $pageHTML.="<div id='$item->prodId' class='airmed-item am-row $item->filter'>";
    //processing part
    $pageHTML.= "        <div class='am-loading d-flex justify-content-center hide'>";
    $pageHTML.= "          <div>Adding...</div><img src='".plugin_dir_url( __FILE__ )."../images/ajax-loader.gif'>";
    $pageHTML.="         </div>";
    
    //image part
    $pageHTML.="  <div class='product-image fix  col-2' title='More Info...' href=''  data-am-toggle='modal' data-am-target='#airmed-modal-".$item->modal."' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
    //$pageHTML.="      <a title='More Info...' href=''  data-am-toggle='modal' data-am-target='#airmed-modal-".$item->modal."' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
    
    if(($amCat->cat_img_hover == 'enabled') && ($item->thisImgType == 'multi')){
      $pageHTML.="        <div class='img-hover'>";
      $pageHTML.="          <img class='normal' src='".$item->hover1."' alt='product image 1'/>";
      $pageHTML.="          <img class='hover' src='".$item->hover2."' alt='product image 2'/>";
      $pageHTML.="        </div>";
    }
    else if(($amCat->cat_carousel == 'enabled') && ($item->thisImgType == 'multi')){
      $pageHTML.="          <div id='airmed-product-images-$item->prodId' class='am-carousel am-slide' data-am-ride='carousel'>";
      $pageHTML.="            <div class='carousel-indicators'>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='0' class='active'></button>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='1'></button>";
      $pageHTML.="                <button type='button' data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='2'></button>";
      $pageHTML.="            </div>";
      $pageHTML.="            <div class='carousel-inner'>";
      $pageHTML.="                <div class='carousel-item active'>";
      $pageHTML.="                  <img class='d-block w-100 img-responsive' src='$item->imgBrand' alt='First slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='carousel-item'>";
      $pageHTML.="                    <img class='d-block w-100 img-responsive' src='$item->imgStrain' alt='Second slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="                <div class='carousel-item'>";
      $pageHTML.="                    <img class='d-block w-100 img-responsive' src='$item->imgProduct' alt='Third slide'>";
      $pageHTML.="                </div>";
      $pageHTML.="            </div>";
      $pageHTML.="          </div>";
    }
    else {
      $pageHTML.="        <img src='$item->thisImg' class='' alt='product image'>";
    }

    //$pageHTML.="      </a>";
    $pageHTML.="  </div>";
    $pageHTML.="  <div class='prod-info col-6' title='More Info...' href=''  data-am-toggle='modal' data-am-target='#airmed-modal-".$item->modal."' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
    $pageHTML.="    <div class='title'>".$item->strainName."</div>";
    $pageHTML.="    <div class='brand'>".$item->name."</div>";
    //$pageHTML.="    <div class='extra-info'>";
    if($cbdInfo){
      $pageHTML.="    <div class='thc-cbd-info'>";
      $pageHTML.="      <div><span class='thc-info'>THC: $item->thcLessThan ".number_format($item->thc,2)." $item->measure</span></div>";
      $pageHTML.="      <div><span class='cbd-info'>CBD: $item->cbdLessThan ".number_format($item->cbd,2)." $item->measure</span></div>";
      $pageHTML.="    </div>";
    }
    $pageHTML.="      <div class='extra-info'>";
    if($profileInfo){ $pageHTML.="        <div class='profile'><span>".$item->category."</span></div>"; }
    $pageHTML.="        <div class='pkgsize'><span>".$item->packageSize."</span></div>";
    $pageHTML.="      </div>";
    //$pageHTML.="      <div class='amount'>$".number_format($item->packagePrice,2)."</div>";
    //$pageHTML.="    </div>";
    
    //$pageHTML.="   <div class='thc-cbd-info'>";
    //$pageHTML.="     <p><span class='thc-info'>THC ".number_format($item->THC,2)."</span></p>";
    //$pageHTML.="     <p><span class='cbd-info'>CBD ".number_format($item->CBD,2)."</span></p>";
    //$pageHTML.="   </div>";
    //$pageHTML.="   <p class='stock'>".$itemObj->status."</p>";
    $pageHTML.="  </div>";
    
    $pageHTML.="  <div class='price-info  col-2 text-end'>";
    $pageHTML.="    <div class='amount text-end'>$".number_format($item->packagePrice,2)."</div>";
    $pageHTML.="  </div>";

    if($amCat->canAddToCart){
      $pageHTML.="  <div class='add-to-cart text-center  col-2'>";
      $pageHTML.= "   <a data-am-prodid='$item->prodId'><button class='btn btn-sm btn-primary login-btn'>Add to Cart</button></a>";
      $pageHTML.="  </div>";
    }
    elseif ($amCat->hasToken){
      $pageHTML.="  <div class='add-to-cart text-center  col-2'>";
      $pageHTML.="      <span><button class='btn btn-sm btn-outline-warning login-btn has-text-color disabled has-background'>Pending Approval</button></span>";
      $pageHTML.="  </div>";
    }
    else {
      $pageHTML.="  <div class='add-to-cart text-center  col-2'>";
      if($amCat->slideout == 1){
        $pageHTML.= "   <a href='".airmed_pagelink('/airmed/airmed-login')."'><button class='btn btn-sm btn-primary login-btn'>LOGIN</button></a>";
      }
      else{  // slideout or modal
        $pageHTML.= "   <a href='' data-am-toggle='modal' data-am-target='#airmed-modal-login'><button class='btn btn-sm btn-primary login-btn'>LOGIN</button></a>";
      }

    $pageHTML.="  </div>";
    }
    $pageHTML.="</div>"; //end of airmed-flex-item
    return $pageHTML;
  }
  
  function getItemCategory($cat){
    if ($cat == "Sativa"){
      return "sativa";
    } 
    else if ($cat == "Indica"){
      return "indica";
    } 
    else if ($cat == "Hybrid - 50/50"){
      return "hy50";
    }
    else if ($cat == "Hybrid - Indica Dominant"){
      return "hyind";
    }
    else if ($cat == "Hybrid - Sativa Dominant"){
      return "hysat";
    }
    else if ($cat == "Other"){
      return "other";
    } 
    else return "";
  }
  function getCBDFilter($val){
    if ($val>150){
      return "cbdHigh";
    } 
    else if ($val<=150&&$val>50){
      return "cbdMid";
    }
    else return "cbdLow";
  }
  function getTHCFilter($val){
    if ($val>150){
      return "thcHigh";
    } 
    else if ($val<=150&&$val>50){
      return "thcMid";
    } 
    else return "thcLow";
  }

  // sanitize airmed_prod_type
  //$amCat->prod_type = sanitize_text_field( get_query_var('airmed_prod_type'));
  //if(is_null($amCat->prod_type) || empty($amCat->prod_type) ){$amCat->prod_type='products';}

/* Old curl call
  //$apiHost = "https://staging.airmed.ca";
  //$apiHost = get_option( 'airmed_options_api_host' );
  //$apiHost = getAirmedAPIHost();
  //$apiKey = "pBrGpYEL9x5sdjVz4EC+ZivlXakXxj7VdNHposaldXY=";  //airmed secret
  //$apiKey = get_option( 'airmed_options_api_key' );
  //$apiKey = getAirmedAPIKey();
  //$apiID = "3092ffc3-fe0e-4f27-9e81-b5f19cd77673";  //airmed
  //$apiID = get_option( 'airmed_options_api_id' );
  //$apiID = getAirmedAPIId();

  //$curl_url = $apiHost.'/API/Test/Get';
  // determine page call
  //$curl_endpoint = $airmed->getAirmedEndpoint($amCat->prod_type,'');
  //$curl_url = $apiHost.'/API/Catalog/'.$curl_endpoint;
  
  $curl_url = $apiHost.'/API/Catalog/GetFullCatalog';
  #$apiHost = $curl_url;

  $requestURI = strtolower(rawurlencode($curl_url));
  $requestMethod = 'GET';
  $requestTimeStamp = time();  //microtime() provides micro seconds.  moment().valueOf() out puts milliseconds
  $nonce = GUID();
  $requestContentBase64String = "";

  $signatureRawData = $apiID . $requestMethod . $requestURI . $requestTimeStamp . $nonce . $requestContentBase64String;
  #$signatureRawData = $apiID . $requestMethod . $requestURI . $requestTimeStamp . $nonce;
  #$signatureRawData = $apiID . $requestMethod . $requestURI . "1620327099" ."{300F8AA4-E6A1-4A97-BC14-D010F4E83ABC}";
  #$signatureRawData = $apiID . $requestMethod . $requestURI . "{300F8AA4-E6A1-4A97-BC14-D010F4E83ABC}";
  #$signatureRawData = $apiID . $requestMethod . $requestURI;
  $signature = utf8_encode($signatureRawData);
  $secretByteArray = base64_decode($apiKey);
  #$signatureBytes = hash_hmac('SHA256',$signature,$secretByteArray);
  #$signatureBytes = hash_hmac('SHA256',$signatureRawData,$apiKey,true);
  #$signatureBytes = hash_hmac('SHA256',$signature,$apiKey,true);
  $signatureBytes = hash_hmac('SHA256',$signature,$secretByteArray,true);

  #$requestSignatureBase64String = $signatureBytes;
  $requestSignatureBase64String = base64_encode($signatureBytes);

  // var hmacKey = AppId + ":" + requestSignatureBase64String + ":" + nonce + ":" + requestTimeStamp;
  $hmacKey = "Airmed-HMAC " . $apiID . ":" . $requestSignatureBase64String . ":" . $nonce . ":" . $requestTimeStamp;
      
  // initialize curl
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
    CURLOPT_HTTPHEADER => array(
      "Authorization: $hmacKey"
    ),
  ));
  
  // get curl response
  $response = curl_exec($curl);
  // get curl error
  $err = curl_error($curl);
  // check for error
  if($errno = curl_errno($curl)) {
    $error_message = curl_strerror($errno);
    echo "<div>cURL error ({$errno}):\n {$error_message} </div>";
    if ($debug) echo "<div>cURL Error: $err </div>";
    else echo "<div> $err </div>";
  }
  curl_close($curl);
  */

  $requestPath = '/API/Catalog/GetFullCatalog';
  $requestArray = airmed_call_request($requestPath,'GET',false,null);
  //echo '<pre>Response:';
  //print_r($requestArray);
  //echo '</pre>';

  // get request response
  //$response = $requestArray[0];
  // get request error
  //$err = $requestArray[1];
  //$errno = $requestArray[2];
  //$err_message = $requestArray[3];

  $response = $requestArray['body'];
  $err = $requestArray['response']['message'];
  $errno = $requestArray['response']['code'];
  $err_message = $requestArray['response']['message'];

  //echo '<pre>err:';
  //print_r($err);
  //echo '</pre>';


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

    /*
    #echo "Response: " . $response;
    #echo "</pre>";
    
    //Reorder the items so the newest releases are first
    #$newestReleasesFirst = array_reverse($jsonObj->results);

    //inbed css if needed
    #$css = plugins_url('/css/styles.css',__FILE__);
    #$pageCSS = "<link rel='stylesheet' href='$css'>";
    #$pageCSS = "";
    
    */

    //isolate each returned set
    $jsonProducerName = $jsonObj->producerName;
    $jsonProducerId = $jsonObj->producerId;
    $jsonProductImage = $jsonObj->productImage;
    property_exists($jsonObj,'products') ? $jsonProducts = $jsonObj->products :$jsonProducts = '';
    property_exists($jsonObj,'derivativeProducts') ? $jsonDerivs = $jsonObj->derivativeProducts : $jsonDerivs = '';
    property_exists($jsonObj,'sourceMaterials') ? $jsonSourceMats = $jsonObj->sourceMaterials : $jsonSourceMats = '';
    property_exists($jsonObj,'accessories') ? $jsonAccessories = $jsonObj->accessories : $jsonAccessories = '';
    property_exists($jsonObj,'plants') ? $jsonPlants = $jsonObj->plants : $jsonPlants = '';
    property_exists($jsonObj,'merchandise') ? $jsonMerchandise = $jsonObj->merchandise : $jsonMerchandise = '';

    if (empty($queryTab)){
      // determine which nav tab or link is active based on response
      if (!empty($jsonProducts)){
        $amCat->tab_prod = 'active';
        $amCat->tab_prod_s = 'true';
        $amCat->panel_prod = 'show active';
      } 
      else if (!empty($jsonDerivs)){
        $amCat->tab_deriv = 'active';
        $amCat->tab_deriv_s = 'true';
        $amCat->panel_derivs = 'show active';
      }
      else if (!empty($jsonPlants)){
        $amCat->tab_plants = 'active';
        $amCat->tab_plants_s = 'true';
        $amCat->panel_plants = 'show active';
      }
      else if (!empty($jsonSourceMats)){
        $amCat->tab_mats = 'active';
        $amCat->tab_mats_s = 'true';
        $amCat->panel_mats = 'show active';
      }
      else if (!empty($jsonAccessories)){
        $amCat->tab_acc = 'active';
        $amCat->tab_acc_s = 'true';
        $amCat->panel_acc = 'show active';
      }
      else if (!empty($jsonMerchandise)){
        $amCat->tab_merch = 'active';
        $amCat->tab_merch_s = 'true';
        $amCat->panel_merch = 'show active';
      }
    }
    else {
      // determine which nav tab or link is active based on querystring
      if ($amCat->prod_type === 'products'){
        $amCat->tab_prod = 'active';
        $amCat->tab_prod_s = 'true';
        $amCat->panel_prod = 'show active';
      } 
      else if ($amCat->prod_type === 'derivativeProducts'){
        $amCat->tab_deriv = 'active';
        $amCat->tab_deriv_s = 'true';
        $amCat->panel_derivs = 'show active';
      }
      else if ($amCat->prod_type === 'plants'){
        $amCat->tab_plants = 'active';
        $amCat->tab_plants_s = 'true';
        $amCat->panel_plants = 'show active';
      }
      else if ($amCat->prod_type === 'sourceMaterials'){
        $amCat->tab_mats = 'active';
        $amCat->tab_mats_s = 'true';
        $amCat->panel_mats = 'show active';
      }
      else if ($amCat->prod_type === 'accessories'){
        $amCat->tab_acc = 'active';
        $amCat->tab_acc_s = 'true';
        $amCat->panel_acc = 'show active';
      }
      else if ($amCat->prod_type === 'merchandise'){
        $amCat->tab_merch = 'active';
        $amCat->tab_merch_s = 'true';
        $amCat->panel_merch = 'show active';
      }
    }
    
    // airmed wrapper - wordpress alignwide class is used to keep the airmed wrapper in the wordpress entry-content element
    $pageHTML.="<div id='airmed-wrapper' class='airmed-wrapper alignwide'>";

    $pageHTML.= airmed_topmenu_shortcode(array('embed' => true));
    
    $amAccount = new stdClass();
    $amAccount = $_SESSION['__airmed'];

    if (($hasToken) && ($amAccount->patient->canPurchase)){
      $amCat->canAddToCart = true;
    }
    $pageHTML.="<script type='text/javascript'>var jsonObj = ".$response.";</script>";

    //$pageHTML.="<form id='airmed_prod_type_form' action='' method='post' name='prod_filter'>Filter <input type='text' name='airmed_prod_type' /><input type='submit' name='submit' value='Submit' /></form>";
    //$pageHTML.="<a href='". esc_url( add_query_arg('airmed_prod_type', 'products'))."'>products</a>";
    //$pageHTML.="<a href='". esc_url( add_query_arg('airmed_prod_type', 'derivativeProducts'))."'>derivativeProducts</a>";
    //$pageHTML.="<a href='". esc_url( add_query_arg('airmed_prod_type', 'merchandise'))."'>merchandise</a>";
    //$prod_type = $_POST('airmed_prod_type');

    $pageHTML.="<div class='airmed-content catalog'>";

    //*****************
    //   Navigation and Filters
    //*****************

    // navigation for theme_nav 1
    if ($amCat->theme_nav == '1'){
      $pageHTML.="<div class='$amCat->class_nav airmed-nav '>";
      $pageHTML.="  <div class='row'>";
      $pageHTML.="      <div class='am-tabbed-content'>";
      $pageHTML.="        <ul role='presentation' class='nav nav-line-bottom nav-normal nav-size-large nav-center am-nav-tabs' role='tablist'>";
      if(!empty($jsonProducts)){$pageHTML.="          <li class='am-nav-item has-icon ' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_prod' href='#' data-am-target='#airmed-products' aria-controls='airmed-products' aria-selected='$amCat->tab_prod_s'><span>Dry Cannabis</span></a></li>";}
      if(!empty($jsonDerivs)){$pageHTML.="          <li class='am-nav-item has-icon ' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_deriv' href='#' data-am-target='#airmed-derivs' aria-controls='airmed-derivs' aria-selected='$amCat->tab_deriv_s'><span>Oils and Extracts</span></a></li>";}
      if(!empty($jsonPlants)){$pageHTML.="          <li class='am-nav-item has-icon ' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_plants' href='#' data-am-target='#airmed-plants' aria-controls='airmed-plants' aria-selected='$amCat->tab_plants_s'><span>Plants</span></a></li>";}
      if(!empty($jsonSourceMats)){$pageHTML.="          <li class='am-nav-item has-icon ' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_mats' href='#' data-am-target='#airmed-materials' aria-controls='airmed-materials' aria-selected='$amCat->tab_mats_s'><span>Materials</span></a></li>";}
      if(!empty($jsonAccessories)){$pageHTML.="          <li class='am-nav-item has-icon ' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_acc' href='#' data-am-target='#airmed-accessories' aria-controls='airmed-accessories' aria-selected='$amCat->tab_acc_s'><span>Accessories</span></a></li>";}
      if(!empty($jsonMerchandise)){$pageHTML.="          <li class='am-nav-item has-icon ' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_merch' href='#' data-am-target='#airmed-merchandise' aria-controls='airmed-merchandise' aria-selected='$amCat->tab_merch_s'><span>Merchandise</span></a></li>";}
      $pageHTML.="        </ul>";
      $pageHTML.="      </div>";
      $pageHTML.="  </div>";
      $pageHTML.="</div>";
    }

    // navigation for theme_nav 2
    if ($amCat->theme_nav == '2'){
      $pageHTML.="<div class='$amCat->class_nav airmed-nav'>";
      $pageHTML.="  <div class='row'>";
      $pageHTML.="    <div class='col col-12'>";
      $pageHTML.="      <div class='col-inner'>";
      $pageHTML.="        <div class='am-tabbed-content'>";
      $pageHTML.="          <ul class='nav nav-line-bottom nav-normal nav-size-large am-nav-tabs nav-center' role='tablist'>";
      if(!empty($jsonProducts)){$pageHTML.="          <li class='am-nav-item has-icon $amCat->tab_prod' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_prod' href='#' data-am-target='#airmed-products' aria-controls='airmed-products' aria-selected='$amCat->tab_prod_s'><span>Dry Cannabis</span></a></li>";}
      if(!empty($jsonDerivs)){$pageHTML.="          <li class='am-nav-item has-icon $amCat->tab_deriv' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_deriv' href='#' data-am-target='#airmed-derivs' aria-controls='airmed-derivs' aria-selected='$amCat->tab_deriv_s'><span>Oils and Extracts</span></a></li>";}
      if(!empty($jsonPlants)){$pageHTML.="          <li class='am-nav-item has-icon $amCat->tab_plants' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_plants' href='#' data-am-target='#airmed-plants' aria-controls='airmed-plants' aria-selected='$amCat->tab_plants_s'><span>Plants</span></a></li>";}
      if(!empty($jsonSourceMats)){$pageHTML.="          <li class='am-nav-item has-icon $amCat->tab_mats' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_mats' href='#' data-am-target='#airmed-materials' aria-controls='airmed-materials' aria-selected='$amCat->tab_mats_s'><span>Materials</span></a></li>";}
      if(!empty($jsonAccessories)){$pageHTML.="          <li class='am-nav-item has-icon $amCat->tab_acc' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_acc' href='#' data-am-target='#airmed-accessories' aria-controls='airmed-accessories' aria-selected='$amCat->tab_acc_s'><span>Accessories</span></a></li>";}
      if(!empty($jsonMerchandise)){$pageHTML.="          <li class='am-nav-item has-icon $amCat->tab_merch' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_merch' href='#' data-am-target='#airmed-merchandise' aria-controls='airmed-merchandise' aria-selected='$amCat->tab_merch_s'><span>Merchandise</span></a></li>";}
      $pageHTML.="          </ul>";
      $pageHTML.="        </div>";
      $pageHTML.="      </div>";
      $pageHTML.="    </div>";
      $pageHTML.="  </div>";
      $pageHTML.="</div>";
    }
    
    if ($amCat->theme_filter == '3'){
     $pageHTML.="<div id='airmed-filters' class='$amCat->class_filter  $amCat->class_nav row'>";
     $pageHTML.="  <div id='airmed-quicksearch' class='col-sm-3 col-xl-2 col-12'>";
     $pageHTML.="    <div class='airmed-search btn-group'>";
     $pageHTML.="      <input type='text' placeholder='Search...' class='form-control'  value='$querySearch'>";
     $pageHTML.="    </div>";
     $pageHTML.="  </div>";
     $pageHTML.="  <div class='col-sm-9 col-xl-10 col-12'>";
     $pageHTML.="    <form class='filter g-1'>";
     $pageHTML.="      <div class='col-auto filter-item' data-filter-group='profile'>";
     $pageHTML.="        <div class=''>";
     $pageHTML.="          <div class='col-auto'><label class='text-end' for='airmed-filter-profile'>Profile</label></div>";
     $pageHTML.="          <div class='col-auto'><select id='airmed-filter-profile' class='form-select'>";
     $pageHTML.="            <option data-filter='' value='' $amCat->filt_prof_all>All</option>";
     $pageHTML.="            <option data-filter='.indica' value='.indica' $amCat->filt_prof_indica>Indica</option>";
     $pageHTML.="            <option data-filter='.sativa' value='.sativa' $amCat->filt_prof_sativa>Sativa</option>";
     $pageHTML.="            <option data-filter='.hy50' value='.hy50' $amCat->filt_prof_hy50>Hybrid</option>";
     $pageHTML.="            <option data-filter='.hyind' value='.hyind' $amCat->filt_prof_hyind>Indica Dominant</option>";
     $pageHTML.="            <option data-filter='.hysat' value='.hysat' $amCat->filt_prof_hysat>Sativa Dominant</option>";
     $pageHTML.="            <option data-filter='.other' value='.other' $amCat->filt_prof_other>Other</option>";
     $pageHTML.="          </select></div>";
     $pageHTML.="        </div>";
     $pageHTML.="      </div>";
     $pageHTML.="      <div class='col-auto filter-item' data-filter-group='thc'>";
     $pageHTML.="        <div class=''>";
     $pageHTML.="          <div class='col-auto'><label class='text-end' for='airmed-filter-thc'>THC</label></div>";
     $pageHTML.="          <div class='col-auto'><select id='airmed-filter-thc' class='form-select'>";
     $pageHTML.="            <option data-filter='' value='' $amCat->filt_thc_all>All</option>";
     $pageHTML.="            <option data-filter='.thcLow' value='.thcLow' $amCat->filt_thc_low>Less Than 50 mg/g</option>";
     $pageHTML.="             <option data-filter='.thcMid' value='.thcMid' $amCat->filt_thc_mid>50-150 mg/g</option>";
     $pageHTML.="            <option data-filter='.thcHigh' value='.thcHigh' $amCat->filt_thc_high>Greater Than 150 mg/g</option>";
     $pageHTML.="          </select></div>";
     $pageHTML.="        </div>";
     $pageHTML.="      </div>";
     $pageHTML.="      <div class='col-auto filter-item' data-filter-group='cbd'>";
     $pageHTML.="        <div class=''>";
     $pageHTML.="          <div class='col-auto'><label class='text-end' for='airmed-filter-cbd'>CBD</label></div>";
     $pageHTML.="          <div class='col-auto'><select id='airmed-filter-cbd' class='form-select'>";
     $pageHTML.="            <option data-filter='' value='' $amCat->filt_cbd_all>All</option>";
     $pageHTML.="            <option data-filter='.cbdLow' value='.cbdLow' $amCat->filt_cbd_low>Less Than 50 mg/g</option>";
     $pageHTML.="            <option data-filter='.cbdMid' value='.cbdMid' $amCat->filt_cbd_mid>50-150 mg/g</option>";
     $pageHTML.="            <option data-filter='.cbdHigh' value='.cbdHigh' $amCat->filt_cbd_high>Greater Than 150 mg/g</option>";
     $pageHTML.="          </select></div>";
     $pageHTML.="        </div>";
     $pageHTML.="      </div>";
     $pageHTML.="    </form>";
     $pageHTML.="  </div>";
     $pageHTML.="</div>";
    }
    
    // airmed-content for catalogs and filter
    $pageHTML.="<div class='$amCat->class_nav catalog-content'>";
    
    $pageHTML.="<div class='row'>";
    //$pageHTML.="<div class=''>";
    
    // sidebar filters
    if ($amCat->theme_filter != '3'){
      $pageHTML.="<div id='airmed-filters' class='col col-sm-3 col-12 $amCat->class_filter'>";
      $pageHTML.="  <aside class='col-inner'>";
      $pageHTML.="    <div class='filter'>";
      
      // navigation for theme_nav 3 inside filter
      if ($amCat->theme_nav == '3'){
        $pageHTML.="      <div class='filter-item filter-type $amCat->class_nav airmed-nav'>";
        $pageHTML.="        <div class='filter-header'>";
        $pageHTML.="          <div class='filter-title'>Product</div>";
        $pageHTML.="        </div>";
        $pageHTML.="        <div class='filter-inner'>";
        $pageHTML.="          <ul class='nav nav-normal nav-center am-nav-tabs'>";
        if(!empty($jsonProducts)){$pageHTML.="          <li class='tab am-nav-item has-icon $amCat->tab_prod' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_prod' href='#' data-am-target='#airmed-products' aria-controls='airmed-products' aria-selected='$amCat->tab_prod_s'><i class='dashicons dashicons-arrow-right $amCat->tab_prod'></i><span>Dry Cannabis</span></a></li>";}
        if(!empty($jsonDerivs)){$pageHTML.="          <li class='tab am-nav-item has-icon $amCat->tab_deriv' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_deriv' href='#' data-am-target='#airmed-derivs' aria-controls='airmed-derivs' aria-selected='$amCat->tab_deriv_s'><i class='dashicons dashicons-arrow-right $amCat->tab_deriv'></i><span>Oils and Extracts</span></a></li>";}
        if(!empty($jsonPlants)){$pageHTML.="          <li class='tab am-nav-item has-icon $amCat->tab_plants' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_plants' href='#' data-am-target='#airmed-plants' aria-controls='airmed-plants' aria-selected='$amCat->tab_plants_s'><i class='dashicons dashicons-arrow-right $amCat->tab_plants'></i><span>Plants</span></a></li>";}
        if(!empty($jsonSourceMats)){$pageHTML.="          <li class='tab am-nav-item has-icon $amCat->tab_mats' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_mats' href='#' data-am-target='#airmed-materials' aria-controls='airmed-materials' aria-selected='$amCat->tab_mats_s'><i class='dashicons dashicons-arrow-right $amCat->tab_mats'></i><span>Materials</span></a></li>";}
        if(!empty($jsonAccessories)){$pageHTML.="          <li class='tab am-nav-item has-icon $amCat->tab_acc' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_acc' href='#' data-am-target='#airmed-accessories' aria-controls='airmed-accessories' aria-selected='$amCat->tab_acc_s'><i class='dashicons dashicons-arrow-right $amCat->tab_acc'></i><span>Accessories</span></a></li>";}
        if(!empty($jsonMerchandise)){$pageHTML.="          <li class='tab am-nav-item has-icon $amCat->tab_merch' role='presentation'><a data-am-toggle='tab' role='tab' class='am-nav-link $amCat->tab_merch' href='#' data-am-target='#airmed-merchandise' aria-controls='airmed-merchandise' aria-selected='$amCat->tab_merch_s'><i class='dashicons dashicons-arrow-right $amCat->tab_merch'></i><span>Merchandise</span></a></li>";}
        $pageHTML.="          </ul>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>";
        $pageHTML.="      <div class='filter-section-header'>";
        $pageHTML.="        <span>Filters</span>";
        $pageHTML.="      </div>";
      }
      
      // filter items for theme_filter 1
      if ($amCat->theme_filter == '1'){
        $pageHTML.="      <div id='airmed-quicksearch' class='filter-item filter-type airmed-search' data-filter-group='search'>";
        $pageHTML.="        <div class='btn-group'>";
        $pageHTML.="          <input type='text' placeholder='Search...' class='form-control form-control-sm' value='$querySearch'>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>";
        $pageHTML.="      <div class='filter-item filter-type' data-filter-group='profile'>";
        $pageHTML.="        <div class='filter-header'>";
        $pageHTML.="          <div class='filter-title'>Profile</div>";
        $pageHTML.="        </div>";
        $pageHTML.="        <div class='filter-inner'>";
        $pageHTML.="          <div class='filter-list'>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-profile-all' class='filter-value' data-filter='' name='filtertype' type='radio' $amCat->filt_prof_all />";
        $pageHTML.="                <label for='airmed-filter-profile-all' class=''>All</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-profile-indica' class='filter-value' data-filter='.indica'  name='filtertype' type='radio' $amCat->filt_prof_indica />";
        $pageHTML.="                <label for='airmed-filter-profile-indica' class=''>Indica</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-profile-sativa' class='filter-value' data-filter='.sativa' name='filtertype' type='radio' $amCat->filt_prof_sativa />";
        $pageHTML.="                <label for='airmed-filter-profile-sativa' class=''>Sativa</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-profile-hybrid' class='filter-value' data-filter='.hy50' name='filtertype' type='radio' $amCat->filt_prof_hy50 />";
        $pageHTML.="                <label for='airmed-filter-profile-hybrid' class=''>Hybrid</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-profile-inddom' class='filter-value' data-filter='.hyind' name='filtertype' type='radio' $amCat->filt_prof_hyind />";
        $pageHTML.="                <label for='airmed-filter-profile-inddom' class=''>Indica Dominant</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-profile-satdom' class='filter-value' data-filter='.hysat' name='filtertype' type='radio' $amCat->filt_prof_hysat />";
        $pageHTML.="                <label for='airmed-filter-profile-satdom' class=''>Sativa Dominant</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-profile-other'class='filter-value' data-filter='.other' name='filtertype' type='radio' $amCat->filt_prof_other />";
        $pageHTML.="                <label for='airmed-filter-profile-other' class=''>Other</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="          </div>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>";
        $pageHTML.="      <div class='filter-item filter-type' data-filter-group='thc'>";
        $pageHTML.="        <div class='filter-header'>";
        $pageHTML.="          <div class='filter-title'>THC</div>";
        $pageHTML.="        </div>";
        $pageHTML.="        <div class='filter-inner'>";
        $pageHTML.="          <div class='filter-list'>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-thc-all' class='filter-value' data-filter='' name='filterTHC' type='radio' $amCat->filt_thc_all />";
        $pageHTML.="                <label for='airmed-filter-thc-all' class=''>All</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-thc-low' class='filter-value' data-filter='.thcLow'  name='filterTHC' type='radio' $amCat->filt_thc_low />";
        $pageHTML.="                <label for='airmed-filter-thc-low' class=''>Less Than 50 mg/g</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-thc-mid' class='filter-value' data-filter='.thcMid' name='filterTHC' type='radio' $amCat->filt_thc_mid />";
        $pageHTML.="                <label for='airmed-filter-thc-mid' class=''>50-150 mg/g</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-thc-high' class='filter-value' data-filter='.thcHigh' name='filterTHC' type='radio' $amCat->filt_thc_high />";
        $pageHTML.="                <label for='airmed-filter-thc-high' class=''>Greater Than 150 mg/g</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="          </div>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>";
        $pageHTML.="      <div class='filter-item filter-type' data-filter-group='cbd'>";
        $pageHTML.="        <div class='filter-header'>";
        $pageHTML.="          <div class='filter-title'>CBD</div>";
        $pageHTML.="        </div>";
        $pageHTML.="        <div class='filter-inner'>";
        $pageHTML.="          <div class='filter-list'>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-cbd-all' class='filter-value' data-filter='' name='filterCBD' type='radio' $amCat->filt_cbd_all />";
        $pageHTML.="                <label for='airmed-filter-cbd-all' class=''>All</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-cbd-low' class='filter-value' data-filter='.cbdLow'  name='filterCBD' type='radio' $amCat->filt_cbd_low />";
        $pageHTML.="                <label for='airmed-filter-cbd-low' class=''>Less Than 50 mg/g</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-cbd-mid' class='filter-value' data-filter='.cbdMid' name='filterCBD' type='radio' $amCat->filt_cbd_mid />";
        $pageHTML.="                <label for='airmed-filter-cbd-mid' class=''>50-150 mg/g</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-list-item'>";
        $pageHTML.="              <a class=''>";
        $pageHTML.="                <input id='airmed-filter-cbd-high' class='filter-value' data-filter='.cbdHigh' name='filterCBD' type='radio' $amCat->filt_cbd_high />";
        $pageHTML.="                <label for='airmed-filter-cbd-high' class=''>Greater Than 150 mg/g</label>";
        $pageHTML.="              </a>";
        $pageHTML.="            </div>";
        $pageHTML.="          </div>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>";
      } // end of filter theme 1
      
      // filter items for theme_filter 2
      if ($amCat->theme_filter == '2'){
        $pageHTML.="      <div id='airmed-quicksearch' class='filter-item filter-type airmed-search' data-filter-group='search'>";
        $pageHTML.="        <div class='btn-group'>";
        $pageHTML.="          <input type='text' placeholder='Search...' class='form-control form-control-sm' value='$querySearch'>";
        $pageHTML.="        </div>";
        $pageHTML.="      </div>";
        
        $pageHTML.="      <ul class='filter-list'>";
        $pageHTML.="        <li class='filter-list-item'>";
        $pageHTML.="          <button class='filter-tab' data-filter-group='profile'>";
        $pageHTML.="            <div class='filter-name'>Profile</div>";
        $pageHTML.="            <i class='filter-expand filter-view-icon dashicons dashicons-plus-alt2 invisible'></i>";
        $pageHTML.="            <i class='filter-contract filter-view-icon dashicons dashicons-minus'></i>";
        $pageHTML.="          </button>";
        $pageHTML.="          <div class='filter-options filter-options-show'>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_prof_indica' data-filter='.indica'>";
        $pageHTML.="                <span class='filter-options-text'>Indica</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_prof_sativa' data-filter='.sativa'>";
        $pageHTML.="                <span class='filter-options-text'>Sativa</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_prof_hy50' data-filter='.hy50'>";
        $pageHTML.="                <span class='filter-options-text'>Hybrid</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_prof_hyind' data-filter='.hyind'>";
        $pageHTML.="                <span class='filter-options-text'>Indica Dominant</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_prof_hysat' data-filter='.hysat'>";
        $pageHTML.="                <span class='filter-options-text'>Sativa Dominant</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_prof_other' data-filter='.other'>";
        $pageHTML.="                <span class='filter-options-text'>Other</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="          </div>";
        $pageHTML.="        </li>";
        $pageHTML.="        <li class='filter-list-item'>";
        $pageHTML.="          <button class='filter-tab' data-filter-group='thc'>";
        $pageHTML.="            <div class='filter-name'>THC</div>";
        $pageHTML.="            <i class='filter-expand filter-view-icon dashicons dashicons-plus-alt2 invisible'></i>";
        $pageHTML.="            <i class='filter-contract filter-view-icon dashicons dashicons-minus'></i>";
        $pageHTML.="          </button>";
        $pageHTML.="          <div class='filter-options filter-options-show'>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_thc_low' data-filter='.thcLow'>";
        $pageHTML.="                <span class='filter-options-text'>Less Than 50 mg/g</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_thc_mid' data-filter='.thcMid'>";
        $pageHTML.="                <span class='filter-options-text'>50-150 mg/g</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_thc_high' data-filter='.thcHigh'>";
        $pageHTML.="                <span class='filter-options-text'>Greater Than 150 mg/g</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="          </div>";
        $pageHTML.="        </li>";
        $pageHTML.="        <li class='filter-list-item'>";
        $pageHTML.="          <button class='filter-tab' data-filter-group='cbd'>";
        $pageHTML.="            <div class='filter-name'>CBD</div>";
        $pageHTML.="            <i class='filter-expand filter-view-icon dashicons dashicons-plus-alt2 invisible'></i>";
        $pageHTML.="            <i class='filter-contract filter-view-icon dashicons dashicons-minus'></i>";
        $pageHTML.="          </button>";
        $pageHTML.="          <div class='filter-options filter-options-show'>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item $amCat->filt_cbd_low' data-filter='.cbdLow'>";
        $pageHTML.="                <span class='filter-options-text'>Less Than 50 mg/g</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item$amCat->filt_cbd_mid' data-filter='.cbdMid'>";
        $pageHTML.="                <span class='filter-options-text'>50-150 mg/g</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="            <div class='filter-container'>";
        $pageHTML.="              <button role='button' class='filter-options-item$amCat->filt_cbd_high' data-filter='.cbdHigh'>";
        $pageHTML.="                <span class='filter-options-text'>Greater Than 150 mg/g</span>";
        $pageHTML.="              </button>";
        $pageHTML.="            </div>";
        $pageHTML.="          </div>";
        $pageHTML.="        </li>";
        $pageHTML.="        <li class='clear-filters'>";

        $pageHTML.="          <div class='pb-3 pt-3 ps-2'>";
        $pageHTML.="            <a id='airmed-clear-filters' class='clear-filters-btn' >Clear Filters</a>";
        $pageHTML.="          </div>";
        $pageHTML.="        </li>";


        $pageHTML.="      </ul>";
      } // end of filter theme 2

      $pageHTML.="    </div>";
      $pageHTML.="  </aside>";
      $pageHTML.="</div>";
    }

    //*****************
    //   Catalogs
    //*****************
    
    if ($amCat->theme_filter != '3'){
      $pageHTML.="<div class='col col-sm-9 col-12 $amCat->class_catalog catalog-content am-tab-content'>";
    }
    else 
    {
      $pageHTML.="<div class='col col-12 $amCat->class_catalog catalog-content am-tab-content'>";
    }

    if(!($amCat->canAddToCart) && $amCat->hasToken){
      $pageHTML.="  <div class='alert alert-secondary alert-dismissible fade show' role='alert'>";
      if($airmed->patient->canApply){
        $pageHTML.="  Purchasing will be accessible once registered and an application is approved.";
        $pageHTML.="    <a href='".airmed_pagelink('/airmed/airmed-new-application')."' class='navigation_button btn btn-secondary'>";
        $pageHTML.="      <span>Apply Online</span>";
        $pageHTML.="    </a>";
      }
      else{
        $pageHTML.="Application has been received.  Purchasing will be accessible once it has been approved.";
      }

      $pageHTML.="    <button type='button' class='btn-close has-background' data-am-dismiss='alert' aria-label='Close'></button>";
      $pageHTML.="  </div>";
    }

    // Products
    if(!empty($jsonProducts)){
      $pageHTML.="<div id='airmed-products' class='airmed-flex-container airmed-flex-products alignwide am-tab-pane fade $amCat->panel_prod' role='tabpanel'>";
      //Loop through the API results
      foreach($jsonProducts as $itemObj) {
        //$jsonProdType = "products";
        //$jsonProdId = $itemObj->productID;
        $item = new stdClass();
        $item->modal = "products";
        $item->prodType = "products";
        $item->prodId = $itemObj->productID;
        $item->name = $itemObj->name;
        $item->strainName = $itemObj->strainName;
        $item->category = $itemObj->category;
        $item->group = '';
        $item->productImage = $itemObj->productImage;
        $item->imgBrand = $itemObj->brandImgThumbString;
        $item->imgStrain = $itemObj->strainImgThumbString;
        $item->imgProduct = $itemObj->productImgThumbString;
        $item->onSale = $itemObj->onSale;
        $item->packagePrice = $itemObj->packagePrice;
        $item->packageSize = $itemObj->packageSize;
        $item->pricePer = $itemObj->pricePerGram;
        $item->salePricePer = $itemObj->salePricePerGram;
        $item->qtyAvailable = '';
        $item->qty = '';
        $item->cbd = $itemObj->cbd;
        $item->cbdLessThan = $itemObj->cbdLessThan;
        $item->thc = $itemObj->thc;
        $item->thcLessThan = $itemObj->thcLessThan;
        $item->measure = $itemObj->measure;
        $item->discreteUnits = $itemObj->discreteUnits;
        $item->unitType = $itemObj->unitType;
        $item->unitCount = $itemObj->unitCount;
        $item->volume = '';
        $item->weight = '';
        $item->extractionLotNo = '';
        $item->description = '';
        $item->status = $itemObj->status;
        $item->producerName = $itemObj->producerName;
        $item->producerLogo = '';
        $item->thisImg = '';
        $item->thisImgType = 'multi';
        $item->hover1 = '';
        $item->hover2 = '';
        
        //Brand
        $item->imgBrand = strpos($item->imgBrand,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgBrand;
        //Strain
        $item->imgStrain = strpos($item->imgStrain,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgStrain;
        //Product
        $item->imgProduct = strpos($item->imgProduct,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgProduct;

        if($amCat->cat_img_hover == 'enabled'){
          $item->hover1 = getAirMedHoverImage($amCat->cat_img_hover1,$item);
          $item->hover2 = getAirMedHoverImage($amCat->cat_img_hover2,$item);
        }
        
        if($item->productImage == 0){
          $item->thisImg = $item->imgBrand;
        }
        else if ($item->productImage == 1){
          $item->thisImg = $item->imgStrain;
        }
        else if ($item->productImage == 2){
          $item->thisImg = $item->imgProduct; 
        }

        $item->filter = getItemCategory($itemObj->category);
        $item->filter.= ' '.getTHCFilter(number_format($item->thc,2));
        $item->filter.= ' '.getCBDFilter(number_format($item->cbd));
        
        // display the catalog item
        if ($amCat->theme_catalog == 3){
          $pageHTML.=catalogTheme3($amCat,$item);
        }
        else if($amCat->theme_catalog == 2){
          $pageHTML.=catalogTheme2($amCat,$item);
        }
        else{
          $pageHTML.=catalogTheme1($amCat,$item);
        }
      }
      $pageHTML.="</div>"; //end of airmed-products
    }
    
    // Derivative Products
    if(!empty($jsonDerivs)){
      $pageHTML.="<div id='airmed-derivs' class='airmed-flex-container airmed-flex-derivs alignwide am-tab-pane fade $amCat->panel_derivs' role='tabpanel'>";
      //Loop through the API results
      foreach($jsonDerivs as $itemObj) {
        //$jsonProdType = "derivativeProducts";
        //$jsonProdId = $itemObj->derivativeProductID;
        $item = new stdClass();
        $item->modal = "products";
        $item->prodType = "derivativeProducts";
        $item->prodId = $itemObj->derivativeProductID;
        $item->name = $itemObj->brandName;
        $item->strainName = $itemObj->strainName;
        $item->category = $itemObj->category;
        $item->group = $itemObj->productType;
        $item->productImage = $itemObj->productImage;
        $item->imgBrand = $itemObj->brandImgThumbString;
        $item->imgStrain = $itemObj->strainImgThumbString;
        $item->imgProduct = $itemObj->productImgThumbString;
        $item->onSale = $itemObj->onSale;
        $item->packagePrice = $itemObj->packagePrice;
        $item->packageSize = '';
        $item->pricePer = $itemObj->pricePerGram;
        $item->salePricePer = $itemObj->salePricePerGram;
        $item->qtyAvailable = $itemObj->qtyAvailable;
        $item->qty = $itemObj->qty;
        $item->cbd = $itemObj->oilCBD;
        $item->cbdLessThan = $itemObj->oilCBDLessThan;
        $item->thc = $itemObj->oilTHC;
        $item->thcLessThan = $itemObj->oilTHCLessThan;
        $item->measure = $itemObj->measure;
        $item->discreteUnits = $itemObj->discreteUnits;
        $item->unitType = $itemObj->unitType;
        $item->unitCount = $itemObj->unitCount;
        $item->volume = $itemObj->packageVolume;
        $item->weight = $itemObj->packageWeight;
        $item->extractionLotNo = $itemObj->extractionLotNO;
        $item->description = $itemObj->description;
        $item->status = $itemObj->status;
        $item->filter = '';
        $item->producerName = $itemObj->producerName;
        $item->producerLogo = '';
        $item->thisImg = '';
        $item->thisImgType = 'multi';
        $item->hover1 = '';
        $item->hover2 = '';

        
        //Brand
        $item->imgBrand = strpos($item->imgBrand,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgBrand;
        //Strain
        $item->imgStrain = strpos($item->imgStrain,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgStrain;
        //Product
        $item->imgProduct = strpos($item->imgProduct,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgProduct;
        if($item->productImage == 0){
          $item->thisImg = $item->imgBrand;
        }
        else if ($item->productImage == 1){
          $item->thisImg = $item->imgStrain;
        }
        else if ($item->productImage == 2){
          $item->thisImg = $item->imgProduct; 
        }

        $item->filter = getItemCategory($itemObj->category);
        $item->filter.= ' '.getTHCFilter(number_format($item->thc,2));
        $item->filter.= ' '.getCBDFilter(number_format($item->cbd));

        // display the catalog item
        if ($amCat->theme_catalog == 3){
          $pageHTML.= catalogTheme3($amCat,$item);
        }
        else if($amCat->theme_catalog == 2){
          $pageHTML.=catalogTheme2($amCat,$item);
        }
        else{
          $pageHTML.=catalogTheme1($amCat,$item);
        }

        /*
        $pageHTML.="<div class='airmed-flex-item'>";
        
        //if item is on sale
        if($itemObj->onSale){$pageHTML.="  <div title='On Sale' class='prod-item-sale'><i class='dashicons dashicons-tag'></i></div>";}
        
        $pageHTML.="  <div title='More Info' class='prod-item-info' data-am-toggle='modal' data-am-target='#airmed-modal-products' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'><i class='dashicons dashicons-info'></i></div>";

        // check for product image, then straing, then brand last
        if (strpos($itemObj->productImgThumbString,'default-product.png')) {
          if (strpos($itemObj->strainImgThumbString,'default-product.png')) {
            if (strpos($itemObj->brandImgThumbString,'default-product.png'))$thisImg = plugins_url('/images/default-product.png',__FILE__); //need to put defaultimage here
            else $thisImg = $itemObj->brandImgThumbString;
          }
          else $thisImg = $itemObj->strainImgThumbString;
        }
        else  $thisImg = $itemObj->productImgThumbString;

        #//Show the image first to keep the top edge of the grid level
        $pageHTML.="  <div class='prod-image'>";
        if (empty($thisImg))$pageHTML.="&nbsp;";
        else $pageHTML.="<div class='img-container'><img class='img-cell' src='".$item->thisImg."' /></div>";
        $pageHTML.="     <div class='user-login'><a>Sign In</a></div>";
        $pageHTML.="   </div>";

        $pageHTML.="  <div class='prod-info'>";
        $pageHTML.="   <div class='title'>".$item->strainName."</div>";
        $pageHTML.="   <p class='producer'>by ".$item->producerName."</p>";
        $pageHTML.="   <p class='description'>".$item->group."</p>";
        $pageHTML.="   <div class='thc-cbd-info'>";
        $pageHTML.="     <p><span class='thc-info'>THC ".number_format($item->thc,2)."</span></p>";
        $pageHTML.="     <p><span class='cbd-info'>CBD ".number_format($item->cbd,2)."</span></p>";
        $pageHTML.="   </div>";
        $pageHTML.="   <p class='stock'>".$itemObj->status."</p>";
        $pageHTML.="  </div>";
        $pageHTML.="</div>"; //end of airmed-flex-item
        */
        
      }
      $pageHTML.="</div>"; //end of airmed-flex-derivs
    }
    
    // Plants
    if(!empty($jsonPlants)){
      $pageHTML.="<div id='airmed-plants' class='airmed-flex-container airmed-flex-plants alignwide am-tab-pane fade $amCat->panel_plants' role='tabpanel'>";
      //Loop through the API results
      foreach($jsonPlants as $itemObj) {
        //$jsonProdType = "plants";
        //$jsonProdId = $itemObj->plantProductID;
        $item = new stdClass();
        $item->modal = "plants";
        $item->prodType = "plants";
        $item->prodId = $itemObj->plantProductID;
        $item->name = $itemObj->brandName;
        $item->strainName = $itemObj->strainName;
        $item->category = $itemObj->category;
        $item->group = '';
        $item->productImage = $itemObj->productImage;
        $item->imgBrand = $itemObj->brandImgThumbString;
        $item->imgStrain = $itemObj->strainImgThumbString;
        $item->imgProduct = $itemObj->productImgThumbString;
        $item->onSale = $itemObj->onSale;
        $item->packagePrice = $itemObj->packagePrice;
        $item->packageSize = $itemObj->plantsPerPackage." plants/pkg";
        $item->pricePer = $itemObj->pricePerPlant;
        $item->salePricePer = $itemObj->salePricePerPlant;
        $item->qtyAvailable = $itemObj->qtyAvailable;
        $item->qty = $itemObj->qty;
        $item->cbd = '';
        $item->cbdLessThan = '';
        $item->thc = '';
        $item->thcLessThan = '';
        $item->measure = '';
        $item->discreteUnits = '';
        $item->unitType = '';
        $item->unitCount = '';
        $item->volume = '';
        $item->weight = '';
        $item->extractionLotNo = '';
        $item->description = $itemObj->description;
        $item->status = $itemObj->status;
        $item->filter = '';
        $item->producerName = $itemObj->producerName;
        $item->producerLogo = '';
        $item->thisImg = '';
        $item->thisImgType = 'multi';
        $item->hover1 = '';
        $item->hover2 = '';
        
        //Brand
        $item->imgBrand = strpos($item->imgBrand,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgBrand;
        //Strain
        $item->imgStrain = strpos($item->imgStrain,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgStrain;
        //Product
        $item->imgProduct = strpos($item->imgProduct,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgProduct;
        if($item->productImage == 0){
          $item->thisImg = $item->imgBrand;
        }
        else if ($item->productImage == 1){
          $item->thisImg = $item->imgStrain;
        }
        else if ($item->productImage == 2){
          $item->thisImg = $item->imgProduct; 
        }

        $item->filter = getItemCategory($itemObj->category);

        // display the catalog item
        if ($amCat->theme_catalog == 3){
          $pageHTML.= catalogTheme3($amCat,$item);
        }
        else if($amCat->theme_catalog == 2){
          $pageHTML.=catalogTheme2($amCat,$item);
        }
        else{
          $pageHTML.=catalogTheme1($amCat,$item);
        }

      }
      $pageHTML.="</div>"; //end of airmed-plants
    }
    
    // Source Materials
    if(!empty($jsonSourceMats)){
      $pageHTML.="<div id='airmed-materials' class='airmed-flex-container airmed-flex-sourceMaterials alignwide am-tab-pane fade $amCat->panel_mats' role='tabpanel'>";
      //Loop through the API results
      foreach($jsonSourceMats as $itemObj) {
        //$jsonProdType = "sourceMaterials";
        //$jsonProdId = $itemObj->sourceMaterialProductID;
        $item = new stdClass();
        $item->modal = "plants";
        $item->prodType = "sourceMaterials";
        $item->prodId = $itemObj->sourceMaterialProductID;
        $item->name = $itemObj->name;
        $item->strainName = $itemObj->strainName;
        $item->category = $itemObj->category;
        $item->group = '';
        $item->productImage = $itemObj->productImage;
        $item->imgBrand = $itemObj->brandImgThumbString;
        $item->imgStrain = $itemObj->strainImgThumbString;
        $item->imgProduct = $itemObj->productImgThumbString;
        $item->onSale = $itemObj->onSale;
        $item->packagePrice = $itemObj->packagePrice;
        $item->packageSize = $itemObj->seedsPerPackage." seeds/pkg";
        $item->pricePer = '';
        $item->salePricePer = '';
        $item->qtyAvailable = '';
        $item->qty = '';
        $item->cbd = '';
        $item->cbdLessThan = '';
        $item->thc = '';
        $item->thcLessThan = '';
        $item->measure = '';
        $item->discreteUnits = '';
        $item->unitType = '';
        $item->unitCount = '';
        $item->volume = '';
        $item->weight = '';
        $item->extractionLotNo = '';
        $item->description = '';
        $item->status = $itemObj->status;
        $item->filter = '';
        $item->producerName = $itemObj->producerName;
        $item->producerLogo = '';
        $item->thisImg = '';
        $item->thisImgType = 'multi';
        $item->hover1 = '';
        $item->hover2 = '';
        
        //Brand
        $item->imgBrand = strpos($item->imgBrand,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgBrand;
        //Strain
        $item->imgStrain = strpos($item->imgStrain,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgStrain;
        //Product
        $item->imgProduct = strpos($item->imgProduct,'default-product.png') ? plugins_url('../images/default-product.png',__FILE__) : $item->imgProduct;
        if($item->productImage == 0){
          $item->thisImg = $item->imgBrand;
        }
        else if ($item->productImage == 1){
          $item->thisImg = $item->imgStrain;
        }
        else if ($item->productImage == 2){
          $item->thisImg = $item->imgProduct; 
        }

        $item->filter = getItemCategory($itemObj->category);

        // display the catalog item
        if ($amCat->theme_catalog == 3){
          $pageHTML.= catalogTheme3($amCat,$item);
        }
        else if($amCat->theme_catalog == 2){
          $pageHTML.=catalogTheme2($amCat,$item);
        }
        else{
          $pageHTML.=catalogTheme1($amCat,$item);
        }
        
        /*
        $pageHTML.="<div class='airmed-item row'>";
        //image part
        $pageHTML.="  <div class='product-image fix'>";
        $pageHTML.="      <a title='More Info...' href=''  data-am-toggle='modal' data-am-target='#airmed-modal-plants' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'>";
        // deal with single image or carousel
        if($itemObj->productImage != 99){
          $pageHTML.="        <img src='$item->thisImg' class='' alt='product image'>";
        }
        else {
          $pageHTML.="          <div id='airmed-product-images-$item->prodId' class='am-carousel am-slide' data-am-ride='carousel'>";
          $pageHTML.="            <ol class='carousel-indicators'>";
          $pageHTML.="                <li data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='0' class='active'></li>";
          $pageHTML.="                <li data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='1'></li>";
          $pageHTML.="                <li data-am-target='#airmed-product-images-$item->prodId' data-am-slide-to='2'></li>";
          $pageHTML.="            </ol>";
          $pageHTML.="            <div class='carousel-inner'>";
          $pageHTML.="                <div class='carousel-item active'>";
          $pageHTML.="                  <img class='d-block w-100 img-responsive' src='$item->imgBrand' alt='First slide'>";
          $pageHTML.="                </div>";
          $pageHTML.="                <div class='carousel-item'>";
          $pageHTML.="                    <img class='d-block w-100 img-responsive' src='$item->imgStrain' alt='Second slide'>";
          $pageHTML.="                </div>";
          $pageHTML.="                <div class='carousel-item'>";
          $pageHTML.="                    <img class='d-block w-100 img-responsive' src='$item->imgProduct' alt='Third slide'>";
          $pageHTML.="                </div>";
          $pageHTML.="            </div>";
          $pageHTML.="          </div>";
        }
        $pageHTML.="      </a>";
        $pageHTML.="  </div>";

        $pageHTML.="  <div class='prod-info'>";
        $pageHTML.="    <div class='title'>".$item->strainName."</div>";
        $pageHTML.="    <div class='brand'>".$item->name."</div>";
        $pageHTML.="    <div class='amount'>$".number_format($item->packagePrice,2)."</div>";
        $pageHTML.="    <div class='pkgsize'>".$item->packageSize."</div>";
        //$pageHTML.="   <div class='thc-cbd-info'>";
        //$pageHTML.="     <p><span class='thc-info'>THC ".number_format($itemObj->THC,2)."</span></p>";
        //$pageHTML.="     <p><span class='cbd-info'>CBD ".number_format($itemObj->CBD,2)."</span></p>";
        //$pageHTML.="   </div>";
        //$pageHTML.="   <p class='stock'>".$itemObj->status."</p>";
        $pageHTML.="  </div>";
        $pageHTML.="  <div class='add-to-cart'>";
        $pageHTML.= "   <button class='btn btn-sm btn-primary login-btn has-text-color'>LOGIN</button>";
        $pageHTML.="  </div>";
        $pageHTML.="</div>"; //end of airmed-flex-item
        */
        
      }
      $pageHTML.="</div>"; //end of airmed-materials
    }
    
    // Accessories
    if(!empty($jsonAccessories)){
      $pageHTML.="<div id='airmed-accessories' class='airmed-flex-container airmed-flex-accessories alignwide am-tab-pane fade $amCat->panel_acc' role='tabpanel'>";
      //Loop through the API results
      foreach($jsonAccessories as $itemObj) {
        //$jsonProdType = "accessories";
        //$jsonProdId = $itemObj->retailProductID;

        $item = new stdClass();
        $item->modal = "retail";
        $item->prodType = "accessories";
        $item->prodId = $itemObj->retailProductID;
        $item->name = '';
        $item->strainName = $itemObj->name;
        $item->category = $itemObj->productCategory;
        $item->group = $itemObj->productGroup;
        $item->productImage = '';
        $item->imgBrand = '';
        $item->imgStrain = '';
        $item->imgProduct = '';
        $item->onSale = $itemObj->onSale;
        $item->packagePrice = $itemObj->price;
        $item->packageSize = '';
        $item->pricePer = '';
        $item->salePricePer = '';
        $item->qtyAvailable = '';
        $item->qty = '';
        $item->cbd = '';
        $item->cbdLessThan = '';
        $item->thc = '';
        $item->thcLessThan = '';
        $item->measure = '';
        $item->discreteUnits = '';
        $item->unitType = '';
        $item->unitCount = '';
        $item->volume = '';
        $item->weight = '';
        $item->extractionLotNo = '';
        $item->description = '';
        $item->status = '';
        $item->filter = '';
        $item->producerName = $itemObj->producerName;
        $item->producerLogo = '';
        $item->thisImg = '';
        $item->thisImgType = 'single';
        $item->hover1 = '';
        $item->hover2 = '';
        
        // check for product image, then straing, then brand last
        if (strpos($itemObj->productImageThumb,'default-product.png'))$item->thisImg = plugins_url('../images/default-product.png',__FILE__); //need to put defaultimage here
        else $item->thisImg = $itemObj->productImageThumb;

        // display the catalog item
        if ($amCat->theme_catalog == 3){
          $pageHTML.= catalogTheme3($amCat,$item);
        }
        else if($amCat->theme_catalog == 2){
          $pageHTML.=catalogTheme2($amCat,$item);
        }
        else{
          $pageHTML.=catalogTheme1($amCat,$item);
        }
          
        /*
        $pageHTML.="<div class='airmed-flex-item'>";
        
        //if item is on sale
        if($itemObj->onSale){$pageHTML.="  <div title='On Sale' class='prod-item-sale'><i class='dashicons dashicons-tag'></i></div>";}
        
        $pageHTML.="  <div title='More Info' class='prod-item-info' data-am-toggle='modal' data-am-target='#airmed-modal-retail' data-itype='".$item->prodType."' data-prodId='".$item->prodId."'><i class='dashicons dashicons-info'></i></div>";

        #//Show the image first to keep the top edge of the grid level
        $pageHTML.="  <div class='prod-image'>";
        if (empty($item->thisImg))$pageHTML.="&nbsp;";
        else $pageHTML.="<div class='img-container'><img class='img-cell' src='".$item->thisImg."' /></div>";
        $pageHTML.="     <div class='user-login'><a>Sign In</a></div>";
        $pageHTML.="   </div>";

        $pageHTML.="  <div class='prod-info'>";
        $pageHTML.="   <div class='title'>".$itemObj->name."</div>";
        $pageHTML.="   <p class='producer'>".$itemObj->productCategory."</p>";
        //$pageHTML.="   <p class='description'>".$itemObj->description."</p>";
        //$pageHTML.="   <p class='stock'>".$itemObj->status."</p>";

        //$pageHTML.="    <div class='airmed-item-description' style='float:left;'>".$itemObj->show->summary."</div>";
        $pageHTML.="  </div>";
        $pageHTML.="</div>"; //end of airmed-flex-item
        */
      }

      $pageHTML.="</div>"; //end of airmed-flex-retail
    }

    // Merchandise
    if(!empty($jsonMerchandise)){
      $pageHTML.="<div id='airmed-merchandise' class='airmed-flex-container airmed-flex-merchandise alignwide am-tab-pane fade $amCat->panel_merch' role='tabpanel'>";
      //Loop through the API results
      foreach($jsonMerchandise as $itemObj) {
        $jsonProdType = "merchandise";
        $jsonProdId = $itemObj->retailProductID;

        $item = new stdClass();
        $item->modal = "retail";
        $item->prodType = "merchandise";
        $item->prodId = $itemObj->retailProductID;
        $item->name = '';
        $item->strainName = $itemObj->name;
        $item->category = $itemObj->productCategory;
        $item->group = $itemObj->productGroup;
        $item->productImage = '';
        $item->imgBrand = '';
        $item->imgStrain = '';
        $item->imgProduct = '';
        $item->onSale = $itemObj->onSale;
        $item->packagePrice = $itemObj->price;
        $item->packageSize = '';
        $item->pricePer = '';
        $item->salePricePer = '';
        $item->qtyAvailable = '';
        $item->qty = '';
        $item->cbd = '';
        $item->cbdLessThan = '';
        $item->thc = '';
        $item->thcLessThan = '';
        $item->measure = '';
        $item->discreteUnits = '';
        $item->unitType = '';
        $item->unitCount = '';
        $item->volume = '';
        $item->weight = '';
        $item->extractionLotNo = '';
        $item->description = '';
        $item->status = '';
        $item->filter = '';
        $item->producerName = $itemObj->producerName;
        $item->producerLogo = '';
        $item->thisImg = '';
        $item->thisImgType = 'single';
        $item->hover1 = '';
        $item->hover2 = '';

        // check for product image, then straing, then brand last
        if (strpos($itemObj->productImageThumb,'default-product.png'))$item->thisImg = plugins_url('../images/default-product.png',__FILE__); //need to put defaultimage here
        else $item->thisImg = $itemObj->productImageThumb;

        // display the catalog item
        if ($amCat->theme_catalog == 3){
          $pageHTML.= catalogTheme3($amCat,$item);
        }
        else if($amCat->theme_catalog == 2){
          $pageHTML.=catalogTheme2($amCat,$item);
        }
        else{
          $pageHTML.=catalogTheme1($amCat,$item);
        }
      }
      $pageHTML.="</div>"; //end of airmed-merchandise
    }

    $pageHTML.="</div>"; //end of col-9

    //$pageHTML.="</div>"; //end of div
    $pageHTML.="</div>"; //end of row
    $pageHTML.="</div>"; //end of theme
    $pageHTML.="</div>"; //end of airmed-content
    
    //*****************
    //   Inventory Modals
    //*****************
    //$pageHTML.= "<div id='airmed-modals' class='airmed-wrapper' aria-hidden='true'>";
    // Products and Derivatives
    $pageHTML.= "<div id='airmed-modal-products' class='modal airmed-modal airmed-info $amCat->class_modal fade' role='dialog' aria-hidden='true'>";
    $pageHTML.= "  <div class='modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable' role='document'>";
    
    $pageHTML.= airmed_modal_load();
    
    // Product content of modal
    $pageHTML.= "    <div class='modal-content item-content hide'>";
    if ($amCat->theme_modal == 2){
      $pageHTML.= "      <div class='modal-header'>";
      $pageHTML.= "        <div class='modal-title'>";
      $pageHTML.= "          <h4 class='name'>[strainName]</h4>";
      $pageHTML.= "          <h5 class='brandName'>[name]</h5>";
      $pageHTML.= "        </div>";
      $pageHTML.= "        <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'>";
      //$pageHTML.= "          <span aria-hidden='true'>&times;</span>";
      $pageHTML.= "        </button>";
      $pageHTML.= "      </div>";
    }
    $pageHTML.= "      <div class='modal-body'>";
    if ($amCat->theme_modal <> 2){$pageHTML.= "        <button type='button' class='btn-close has-background float-end' data-am-dismiss='modal' aria-label='Close'></button>";}
    $pageHTML.= "        <div id='airmed-modal-prod-content' class='row'>";
    $pageHTML.= "          <div class='col-sm-5'>";
    $pageHTML.= "            <div class='product-img'>";
    
    if($amCat->img_hover == 'enabled'){
      $pageHTML.="               <div class='img-hover'>";
      $pageHTML.="                 <img class='normal' src='' data-am-image='$amCat->img_hover1' alt='product image 1'/>";
      $pageHTML.="                 <img class='hover' src=''  data-am-image='$amCat->img_hover2' alt='product image 2'/>";
      $pageHTML.="               </div>";
    }
    else if($amCat->carousel == 'enabled'){
      $pageHTML.= "                <div id='am-detail-images' class='am-carousel am-slide hide' data-am-ride='carousel'>";
      $pageHTML.= "                    <div class='carousel-indicators'>";
      $pageHTML.= "                        <button type='button' data-am-target='#am-detail-images' data-am-slide-to='0' class='active'></button>";
      $pageHTML.= "                        <button type='button' data-am-target='#am-detail-images' data-am-slide-to='1'></button>";
      $pageHTML.= "                        <button type='button' data-am-target='#am-detail-images' data-am-slide-to='2'></button>";
      $pageHTML.= "                    </div>";
      $pageHTML.= "                    <div class='carousel-inner'>";
      $pageHTML.= "                        <div class='carousel-item active'>";
      $pageHTML.= "                            <img class='d-block w-100 img-responsive img0' src='' alt='First slide'>";
      $pageHTML.= "                         </div>";
      $pageHTML.= "                         <div class='carousel-item'>";
      $pageHTML.= "                             <img class='d-block w-100 img-responsive img1' src='' alt='Second slide'>";
      $pageHTML.= "                         </div>";
      $pageHTML.= "                         <div class='carousel-item'>";
      $pageHTML.= "                            <img class='d-block w-100 img-responsive img2' src='' alt='Third slide'>";
      $pageHTML.= "                        </div>";
      $pageHTML.= "                    </div>";
      $pageHTML.= "                    <button type='button' class='carousel-control-prev' data-am-target='#am-detail-images' role='button' data-am-slide='prev'>";
      $pageHTML.= "                        <span class='carousel-control-prev-icon' aria-hidden='true'></span>";
      $pageHTML.= "                        <span class='visually-hidden'>Previous</span>";
      $pageHTML.= "                    </button>";
      $pageHTML.= "                    <button type='button' class='carousel-control-next' data-am-target='#am-detail-images' role='button' data-am-slide='next'>";
      $pageHTML.= "                        <span class='carousel-control-next-icon' aria-hidden='true'></span>";
      $pageHTML.= "                        <span class='visually-hidden'>Next</span>";
      $pageHTML.= "                    </button>";
      $pageHTML.= "               </div>";
    }
    else {
      //get image correctly based on productImage value: 0-Branding, 1-Strain, 2-Product
      $pageHTML.= "                <img src='' class='img-responsive' alt='' />";
    }
    $pageHTML.= "            </div>";
    $pageHTML.= "            <div class='row'>";
    $pageHTML.= "                <div class='col-sm-12 text-center'>";
    $pageHTML.= "                    <span class='pkg-size'>[packageSize] g</span>";
    $pageHTML.= "                </div>";
    $pageHTML.= "            </div>";
    $pageHTML.= "            <div class='row'>";
    $pageHTML.= "                <div class='col-sm-12 text-center'>";
    $pageHTML.= "                    <div class='category'>";
    $pageHTML.= "                        Category:";
    $pageHTML.= "                        <span>[Category]</span>";
    $pageHTML.= "                    </div>";
    $pageHTML.= "                </div>";
    $pageHTML.= "            </div>";
    $pageHTML.= "        </div>";
    $pageHTML.= "        <div class='col-sm-7'>";
    $pageHTML.= "            <div class='single-pro-details'>";
    if ($amCat->theme_modal <> 2){    
      $pageHTML.= "                <h4 class='name'>[strainName]</h4>";
      $pageHTML.= "                <h5 class='brandName'>[name]</h5>";
    }
    $pageHTML.= "                <div class='discrete-units hide'>[unitType] ([unitCount] per Package)</div>";
    $pageHTML.= "                <div class='productType hide text-start'>[productType]</div>";
    $pageHTML.= "                <div class='row thccbd'>";
    $pageHTML.= "                    <div class='col-sm-12 text-start'>";
    $pageHTML.= "                        <span class='thc'>THC:</span><span class='thc-value'>[thcLessThan] [thc] [measure]</span><span class='cbd'>CBD:</span><span class='cbd-value'>[cbdLessThan] [cbd] [measure]</span>";
    $pageHTML.= "                    </div>";
    $pageHTML.= "                </div>";
    $pageHTML.= "                <div class='d-flex pricing'>";
    $pageHTML.= "                    <div class='col-sm-6 text-start'>";
    //$pageHTML.= "                        @if (Model.SalePricePerGram > 0)";
    //$pageHTML.= "                        {";
    //$pageHTML.= "                            <del><span class='amount-del pricePerGram'>[pricePerGram]</span></del>";
    //$pageHTML.= "                            <span class='text-danger salePricePerGram'>[salePricePerGrame] /g</span>";
    //$pageHTML.= "                        }";
    //$pageHTML.= "                        else";
    //$pageHTML.= "                        {";
    $pageHTML.= "                           <span class='pricePerGram'>[pricePerGram] /g</span>";
    $pageHTML.= "                           <span class='salePricePerGram text-danger hide'>[salePricePerGram] /g</span>";
    //$pageHTML.= "                        }";
    $pageHTML.= "                    </div>";
    $pageHTML.= "                    <div class='col-sm-6 text-end'>";
    $pageHTML.= "                        Total: <span class='packagePrice'>[packagePrice]</span>";
    $pageHTML.= "                    </div>";
    $pageHTML.= "                </div>";
    // only show if description is present
    $pageHTML.= "                    <div class='strain-desc' >";
    $pageHTML.= "                        <span class='strain-title'>Strain Details</span><br />";
    $pageHTML.= "                        <span class='strainDescription'>[strainDescription]</span>";
    $pageHTML.= "                    </div>";
    // only show if description is present
    $pageHTML.= "                    <div class='brand-desc'>";
    $pageHTML.= "                        <div class=''>";
    $pageHTML.= "                            <span class='brand-title'>Brand Details</span><br />";
    $pageHTML.= "                            <span class='brandDescription'>[brandDescription]</span>";
    $pageHTML.= "                        </div>";
    $pageHTML.= "                    </div>";
    if ($amCat->theme_modal <> 2 ){
      $pageHTML.= "                <div class='text-end add-to-cart'>";
      //$pageHTML.= "                    <button class='btn btn-sm btn-primary login-btn has-text-color'>LOGIN</button>";
      $pageHTML.= "                </div>";
    }
    $pageHTML.= "            </div>";
    $pageHTML.= "        </div>";
    $pageHTML.= "    </div>";

    $pageHTML.= "      </div>";
    if ($amCat->theme_modal == 2){
      $pageHTML.= "      <div class='modal-footer'>";
      $pageHTML.= "      <button type='button' class='btn btn-secondary' data-am-dismiss='modal'>Close</button>";
      //$pageHTML.= "      <button type='button' class='btn btn-primary'>Save changes</button>";
      //$pageHTML.= "        <button class='btn btn-sm btn-primary login-btn has-text-color'>LOGIN</button>";

      $pageHTML.= "      </div>";
    }
    $pageHTML.= "    </div>";
    $pageHTML.= "  </div>";
    $pageHTML.= "</div>";

    // **** Plants and Source Materials ****
    $pageHTML.= "<div id='airmed-modal-plants' class='modal airmed-modal airmed-info $amCat->class_modal fade' role='dialog' aria-hidden='true'>";
    $pageHTML.= "  <div class='modal-dialog modal-lg modal-dialog-centered' role='document'>";
    
    $pageHTML.= airmed_modal_load();
    
    // Product content of modal
    $pageHTML.= "    <div class='modal-content item-content hide'>";
    if ($amCat->theme_modal == 2){
      $pageHTML.= "      <div class='modal-header'>";
      $pageHTML.= "        <div class='modal-title'>";
      $pageHTML.= "          <h4 class='name'>[strainName]</h4>";
      $pageHTML.= "          <h5 class='brandName'>[name]</h5>";
      $pageHTML.= "        </div>";
      $pageHTML.= "        <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'>";
      //$pageHTML.= "          <span aria-hidden='true'>&times;</span>";
      $pageHTML.= "        </button>";
      $pageHTML.= "      </div>";
    }
    $pageHTML.= "      <div class='modal-body'>";
    if ($amCat->theme_modal <> 2){$pageHTML.= "        <button type='button' class='btn-close has-background float-end' data-am-dismiss='modal' aria-label='Close'></button>";}
    $pageHTML.= "        <div id='airmed-modal-prod-content' class='row'>";
    $pageHTML.= "          <div class='col-sm-5'>";
    $pageHTML.= "            <div class='product-img'>";
    
    if($amCat->img_hover == 'enabled'){
      $pageHTML.="               <div class='img-hover'>";
      $pageHTML.="                 <img class='normal' src='' data-am-image='$amCat->img_hover1' alt='product image 1'/>";
      $pageHTML.="                 <img class='hover' src='' data-am-image='$amCat->img_hover2' alt='product image 2'/>";
      $pageHTML.="               </div>";
    }
    else if($amCat->carousel == 'enabled'){
      $pageHTML.= "                    <div id='plant-detail-images' class='am-carousel am-slide' data-am-ride='carousel'>";
      $pageHTML.= "                        <div class='carousel-indicators'>";
      $pageHTML.= "                            <button type='button' data-am-target='#plant-detail-images' data-am-slide-to='0' class='active'></button>";
      $pageHTML.= "                            <button type='button' data-am-target='#plant-detail-images' data-am-slide-to='1'></button>";
      $pageHTML.= "                            <button type='button' data-am-target='#plant-detail-images' data-am-slide-to='2'></button>";
      $pageHTML.= "                        </div>";
      $pageHTML.= "                        <div class='carousel-inner'>";
      $pageHTML.= "                            <div class='carousel-item active'>";
      $pageHTML.= "                                <img class='d-block w-100 img-responsive img0' src='' alt='First slide'>";
      $pageHTML.= "                            </div>";
      $pageHTML.= "                            <div class='carousel-item'>";
      $pageHTML.= "                                <img class='d-block w-100 img-responsive img1' src='' alt='Second slide'>";
      $pageHTML.= "                            </div>";
      $pageHTML.= "                            <div class='carousel-item'>";
      $pageHTML.= "                                <img class='d-block w-100 img-responsive img2' src='' alt='Third slide'>";
      $pageHTML.= "                            </div>";
      $pageHTML.= "                        </div>";
      $pageHTML.= "                        <button type='button' class='carousel-control-prev' data-am-target='#plant-detail-images' role='button' data-am-slide='prev'>";
      $pageHTML.= "                            <span class='carousel-control-prev-icon' aria-hidden='true'></span>";
      $pageHTML.= "                            <span class='visually-hidden'>Previous</span>";
      $pageHTML.= "                        </button>";
      $pageHTML.= "                        <button type='button' class='carousel-control-next' data-am-target='#plant-detail-images' role='button' data-am-slide='next'>";
      $pageHTML.= "                            <span class='carousel-control-next-icon' aria-hidden='true'></span>";
      $pageHTML.= "                            <span class='visually-hidden'>Next</span>";
      $pageHTML.= "                        </button>";
      $pageHTML.= "                    </div>";
    }
    else {
      //get image correctly based on productImage value: 0-Branding, 1-Strain, 2-Product
      $pageHTML.= "              <img src='' class='img-responsive' alt='' />";
    }
    $pageHTML.= "            </div>";
    $pageHTML.= "            <div class='row'>";
    $pageHTML.= "                <div class='col-sm-12 text-center'>";
    $pageHTML.= "                    <span class='pkg-size'>[packageSize] g</span>";
    $pageHTML.= "                </div>";
    $pageHTML.= "            </div>";
    $pageHTML.= "            <div class='row'>";
    $pageHTML.= "                <div class='col-sm-12 text-center'>";
    $pageHTML.= "                    <div class='category'>";
    $pageHTML.= "                        Category:";
    $pageHTML.= "                        <span>[Category]</span>";
    $pageHTML.= "                    </div>";
    $pageHTML.= "                </div>";
    $pageHTML.= "            </div>";
    $pageHTML.= "        </div>";
    $pageHTML.= "        <div class='col-sm-7'>";
    $pageHTML.= "            <div class='single-pro-details'>";
    if ($amCat->theme_modal <> 2){    
      $pageHTML.= "                <h4 class='name'>[strainName]</h4>";
      $pageHTML.= "                <h5 class='brandName'>[name]</h5>";
    }
    $pageHTML.= "                <div class='d-flex pricing'>";
    $pageHTML.= "                  <div class='col-sm-6 text-start'>";
    $pageHTML.= "                    <span class='pricePerGram'>[pricePerGram] /g</span>";
    $pageHTML.= "                    <span class='salePricePerGram text-danger hide'>[salePricePerGram] /g</span>";
    $pageHTML.= "                  </div>";
    $pageHTML.= "                  <div class='col-sm-6 text-end'>";
    $pageHTML.= "                     Total: <span class='packagePrice'>[packagePrice]</span>";
    $pageHTML.= "                  </div>";
    $pageHTML.= "                </div>";
    // only show if description is present
    $pageHTML.= "                    <div class='strain-desc' >";
    $pageHTML.= "                        <span class='strain-title'>Strain Details</span><br />";
    $pageHTML.= "                        <span class='strainDescription'>[strainDescription]</span>";
    $pageHTML.= "                    </div>";
    if ($amCat->theme_modal <> 2 ){
      $pageHTML.= "                <div class='text-end add-to-cart'>";
      //$pageHTML.= "                    <button class='btn btn-sm btn-primary login-btn has-text-color'>LOGIN</button>";
      $pageHTML.= "                </div>";
    }
    $pageHTML.= "            </div>";
    $pageHTML.= "        </div>";
    $pageHTML.= "    </div>";
    $pageHTML.= "      </div>";
    if ($amCat->theme_modal == 2){
      $pageHTML.= "      <div class='modal-footer'>";
      $pageHTML.= "        <button type='button' class='btn btn-secondary' data-am-dismiss='modal'>Close</button>";
      //$pageHTML.= "      <button type='button' class='btn btn-primary'>Save changes</button>";
      //$pageHTML.= "        <button class='btn btn-sm btn-primary login-btn has-text-color'>LOGIN</button>";

      $pageHTML.= "      </div>";
    }
    $pageHTML.= "    </div>";
    $pageHTML.= "  </div>";
    $pageHTML.= "</div>";

    // Retail (Accessories/Merchandise) Modal
    $pageHTML.= "<div id='airmed-modal-retail' class='modal airmed-modal airmed-info $amCat->class_modal fade' role='dialog' aria-hidden='true'>";
    $pageHTML.= "  <div class='modal-dialog modal-dialog-centered' role='document'>";
    $pageHTML.= airmed_modal_load();
    $pageHTML.= "    <div class='modal-content item-content hide'>";
    if ($amCat->theme_modal == 2){
      $pageHTML.= "      <div class='modal-header'>";
      $pageHTML.= "        <div class='modal-title'>";
      $pageHTML.= "          <h4 class='name'>[name]</h4>";
      $pageHTML.= "        </div>";
      $pageHTML.= "        <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'>";
      //$pageHTML.= "          <span aria-hidden='true'>&times;</span>";
      $pageHTML.= "        </button>";
      $pageHTML.= "      </div>";
    }
    $pageHTML.= "      <div class='modal-body'>";
    if ($amCat->theme_modal <> 2){$pageHTML.= "        <button type='button' class='btn-close has-background' data-am-dismiss='modal' aria-label='Close'></button>";}
    $pageHTML.= "        <div id='airmed-modal-prod-content' class='row'>";
    $pageHTML.= "          <div class='col-sm-5'>";
    $pageHTML.= "            <div class='product-img'>";
    $pageHTML.= "              <img src='' class='img-responsive' alt='' />"; 
    $pageHTML.= "            </div>";
    $pageHTML.= "            <div class='row'>";
    $pageHTML.= "                <div class='col-sm-12 text-center'>";
    $pageHTML.= "                    <div class='category'>";
    $pageHTML.= "                        Category:";
    $pageHTML.= "                        <span>[Category]</span>";
    $pageHTML.= "                    </div>";
    $pageHTML.= "                </div>";
    $pageHTML.= "            </div>";
    $pageHTML.= "        </div>";
    $pageHTML.= "        <div class='col-sm-7'>";
    $pageHTML.= "          <div class='single-pro-details'>";
    if ($amCat->theme_modal <> 2){    
      $pageHTML.= "          <h4 class='name'>[name]</h4>";
    }
    $pageHTML.= "            <div class='d-flex pricing'>";
    $pageHTML.= "              <div class='col-sm-12 text-start'>";
    $pageHTML.= "                Total: <span class='packagePrice'>[price]</span>";
    $pageHTML.= "              </div>";
    $pageHTML.= "            </div>";
    if ($amCat->theme_modal <> 2 ){
      $pageHTML.= "          <div class='text-end add-to-cart'>";
      //$pageHTML.= "                    @{";
      //$pageHTML.= "                        var returnUrl = Url.Action('ProductCatalog', 'Order');";
      //$pageHTML.= "                    }";
      //$pageHTML.= "            <button class='btn btn-sm btn-primary login-btn has-text-color'>LOGIN</button>";
      $pageHTML.= "          </div>";
    }
    $pageHTML.= "          </div>";
    $pageHTML.= "        </div>";
    $pageHTML.= "      </div>";
    $pageHTML.= "    </div>";
    if ($amCat->theme_modal == 2){
      $pageHTML.= "      <div class='modal-footer'>";
      $pageHTML.= "      <button type='button' class='btn btn-secondary' data-am-dismiss='modal'>Close</button>";
      //$pageHTML.= "      <button type='button' class='btn btn-primary'>Save changes</button>";
      //$pageHTML.= "        <button class='btn btn-sm btn-primary login-btn has-text-color'>LOGIN</button>";

      $pageHTML.= "      </div>";
    }
    $pageHTML.= "    </div>";
    $pageHTML.= "  </div>";
    $pageHTML.= "</div>";  // end of modal
   //$pageHTML.= "</div>";  // end of modals
   
    //$pageHTML.= includeModals();
    
    $pageHTML.="</div>"; //end of airmed-wrapper
    
    // get slug name of page
    //$slug = get_post_field( 'post_name', get_post() );
    //echo "<div>Option: " . get_post_field( 'post_name', get_post() )."</div>";
    //$p = get_page_by_path('/airmed/');
    //echo "<div>Login: " . $p->ID ."</div>";
    //echo "<div>ID: ".get_queried_object_id()."</div>";
    
    echo $pageHTML;
    echo "<script type='text/javascript'>var pluginDir = '".plugin_dir_url( __FILE__ )."';</script>";
    
  } // end of check for curl error
  else { // curl error displayed
    //$error_message = curl_strerror($errno);
    echo "<div>cURL error ({$errno}):\n {$err_message} </div>";
    if ($debug) echo "<div>cURL Error: $err </div>";
    else echo "<div> $err </div>";
  }

}// End airmedproducts shortcode

?>