// main plugin
jQuery(document).ready( function(){
   
   /*
   **  Global
   */
  window.airmed = {};
  airmed.blankSignatureError = "Signature cannot be blank. Please provide a signature in the field.";
  airmed.modalSubmit = false;
  
  //bootstrap popover function
  //jQuery('[data-am-toggle="popover"]').popover({
  //  html:true,
  //  content: function(){
  //    return jQuery('#poc-'+jQuery(this).attr('id')).html()
  //  }
  //});
  jQuery('[data-am-toggle="popover"]').on("click",function(){
    let id = jQuery(this).attr("id");
    jQuery('#poc-'+id).toggleClass('show');
  });

  function sanitizeString(str){
    str = str.replace(/[^a-z0-9áéíóúñü \.,_-]/gim,"");
    return str.trim();
  }
  
  function getCookie(cname) {
    let name = cname + "=";
    let decodedCookie = decodeURIComponent(document.cookie);
    let ca = decodedCookie.split(';');
    for(let i = 0; i <ca.length; i++) {
      let c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
    }
    return "";
  }
  
  function setCookie(cname, cvalue, exdays) {
    if(exdays){
      const d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      let expires = "expires="+ d.toUTCString();
    } else {
      let exdays = "";
    }
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
  
  function getUrlParameter(name) {
    name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
    var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
  }
  
  // *** Move Modals to Body element ***
  if (jQuery("#airmed-modals").length){
    jQuery('#airmed-modals').appendTo('body');
    jQuery('.airmed-modal').each(function(i){
      jQuery(this).appendTo('#airmed-modals');
    });
  }

  // get current wordpress page id
  function get_current_page_id() {
    var page_body = jQuery('body.page');

    var id = 0;

    if(page_body) {
        var classList = page_body.attr('class').split(/\s+/);

        jQuery.each(classList, function(index, item) {
            if (item.indexOf('page-id') >= 0) {
                var item_arr = item.split('-');
                id =  item_arr[item_arr.length -1];
                return false;
            }
        });
    }
    return id;
  }
  
  // hide titles
  jQuery("#post-"+get_current_page_id()+" header.entry-header").hide();

  // determine message icons in site nav
  if (jQuery("#airmed-account-slideout").length){
    let newMessages = jQuery("#account-slideout-new-messages").html();
    let urgentMessages = jQuery("#account-slideout-urgent-messages").html();
    if(newMessages == '0'){jQuery("#airmed-menu-new-messages").hide();}
    if(urgentMessages == '0'){jQuery("#airmed-menu-urgent-messages").hide();}
  }
   
  //***  Inventory Page  ****
/*
  function getProduct(prods,key,value){
    return prods.filter( function(prods){ return prods[key] === value} );
  }
*/
  
  function modalAjaxCall(modal,prodid,itype){
    jQuery.ajax({
      //url: "http://localhost:81/wordpress/test.json",
      url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
      //url: airmedajaxmodal.restURL + 'modal/',
      datatype: 'json',
      method: 'post',
      data: {
        action: 'airmed_modal',
        prodid: prodid,
        itype: itype
      },
      success: function( result ) {
        if(result.hasOwnProperty('type') && result.type == 'error'){
          alert(result.message);
          modal.find('button.close').click();
        }
        else{
          if(result.hasOwnProperty('productID')){updateProductModal(result,modal);}
          else if(result.hasOwnProperty('derivativeProductID')){updateDerivativeModal(result,modal);}
          else if(result.hasOwnProperty('plantProductID')){updatePlantModal(result,modal);}
          else if(result.hasOwnProperty('sourceMaterialProductID')){updateSourceMaterialModal(result,modal);}
          else if(result.hasOwnProperty('retailProductID')){updateRetailModal(result,modal);}
          else if(itype == 'receivedMessage' && result.hasOwnProperty('id')){updateMessageModal(result,modal,true);}
          else if(itype == 'sentMessage' && result.hasOwnProperty('id')){updateMessageModal(result,modal,false);}
          else { 
            alert('No product found');
            modal.find('button.close').click();
          }
        }
        
      },
      error: function(xhr,status,err){
        var e = "XHR Error: "+xhr.status+"-"+xhr.statusText;
        //alert(airmedajaxmodal.restURL + 'modal/');
        alert(e);
        alert(xhr.responseText);
        //var json = JSON.parse(xhr.responseText);
        //if (xhr.readyState == 4 && xhr.status == 200) {
        //  var myObj = JSON.parse(xhr.responseText);
        //}
        
      }
    });
  }
   
  // Products modal
  jQuery('#airmed-modal-products,#airmed-modal-plants,#airmed-modal-retail,#airmed-modal-message').on('show.am.modal', function (e) {
    var modal = jQuery(this);
    var button = jQuery(e.relatedTarget); // Button that triggered the modal
    var itype = button.data('itype'); // Extract info from data-* attributes
    var prodid = button.data('prodid'); // Extract info from data-* attributes
    //var prods = jsonObj[itype];  // gets the correct product array
    //var prod = getProduct(prods,"productID",prodid);  // gets the product in the array
    //var item = button.siblings(".prod-info");
    
    
    modalAjaxCall(modal,prodid,itype);
    /*
    jQuery.ajax({
      headers: { 'Authorization': $hmacKey },
      type: "post",
      url: postURL,
      data: d,
      tryCount: 0,
      success: function(r){
        var d = new Date().toLocaleString();
        r=r.trim();
        if(r==="Success"){
         jQuery(m).html(d + ' - Post Success').addClass('alert alert-success').removeClass('alert-danger');
        }
        else{
         jQuery(m).html(d + ' - TimeOut').addClass('alert alert-danger').removeClass('alert-success');
        }
        //alert(r);
      },
      error: function(xhr,status,err){
        var d = new Date().toLocaleString();
        this.tryCount++;
        //alert(this.tryCount);
        var e = "XHR Error: "+xhr.status+"-"+xhr.statusText;
        //alert(e);
        if(xhr.status != 404) {
          if (this.tryCount <= 1) {
           jQuery(m).html(d+' '+e+'</br>Trying Again').removeClass('alert-success').addClass('alert alert-danger');
           $.ajax(this);return;
          }
        }
        jQuery(m).html(d+' '+e).removeClass('alert-success').addClass('alert alert-danger');
      }
    });
    */
    
    //modal.find('.modal-title').text(prod[0].name);
    
    /* 
    jQuery.ajax({
      //url: "http://localhost:81/wordpress/test.json",
      url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
      //url: airmedajaxmodal.restURL + 'modal/',
      datatype: 'json',
      type: 'post',
      data: {
        action: 'airmed_script',
        prodid: prodid,
        itype: itype
      },
      success: function( result ) {
        //var json = JSON.parse(result);
        //alert(result);
        //update modal
        updateModal(result,modal);
        
      },
      error: function(xhr,status,err){
        var e = "XHR Error: "+xhr.status+"-"+xhr.statusText;
        //alert(airmedajaxmodal.restURL + 'modal/');
        alert(e);
        alert(xhr.responseText);
        //var json = JSON.parse(xhr.responseText);
        //if (xhr.readyState == 4 && xhr.status == 200) {
        //  var myObj = JSON.parse(xhr.responseText);
        //}
        
      }
    });
    */

  });
  
  function updateProductModal(r,m){
   
    m.find('.modal-title h4').html(r.strainName);
    m.find('.modal-title h5').html(r.name);
    if(r.discreteUnits){
      m.find('.discrete-units').removeClass('hide').html(r.unitType +' ('+r.unitCount+' per Package)');
    }
    else {
      m.find('.discrete-units').addClass('hide');
    }
    

    setImages(r,m);
    
    var ps = r.packageSize;
    m.find('.pkg-size').html(ps.toFixed(2)+' g');
    m.find('.category span').html(r.category);
    m.find('.single-pro-details h4').html(r.strainName);
    m.find('.single-pro-details h5').html(r.name);
    
    //hide since don't have property
    m.find('.productType').addClass('hide');
    var thc = r.thc;
    m.find('.thccbd .thc-value').html(r.thcLessThan+' '+thc.toFixed(1)+' '+r.measure+" |");
    var cbd = r.cbd;
    m.find('.thccbd .cbd-value').html(r.cbdLessThan+' '+cbd.toFixed(1)+' '+r.measure);
    var sppg = r.salePricePerGram;
    var ppg = r.pricePerGram;
    if(r.salePricePerGram > 0){
      m.find('.pricing .salePricePerGram').removeClass('hide').html("$"+sppg.toFixed(2)+' /g');
      m.find('.pricing .pricePerGram').addClass('strikethrough').html("$"+ppg.toFixed(2)+'');
    }
    else {
      m.find('.pricing .salePricePerGram').addClass('hide').html("$"+sppg.toFixed(2)+' /g');
      m.find('.pricing .pricePerGram').removeClass('strikethrough').html("$"+ppg.toFixed(2)+' /g');
    }
    var pp = r.packagePrice;
    m.find('.pricing .packagePrice').html("$"+pp.toFixed(2));
    
    m.find('.strain-desc .strainDescription').html(r.strainDescription);
    var sdv = r.strainDescription;
    sdv === '' ? m.find('.strain-desc').addClass('hide'):m.find('.strain-desc').removeClass('hide');
    
    m.find('.brand-desc .brandDescription').html(r.brandDescription);
    var bdv = r.brandDescription;
    bdv === '' ? m.find('.brand-desc').addClass('hide') : m.find('.brand-desc').removeClass('hide');
    
    m.find('.loading-content').addClass('hide');
    m.find('.item-content').removeClass('hide');
  }

  function updateDerivativeModal(r,m){
    //alert(r);
    
    m.find('.modal-title h4').html(r.strainName);
    m.find('.modal-title h5').html(r.name);
    if(r.discreteUnits){
      m.find('.discrete-units').removeClass('hide').html(r.unitType +' ('+r.unitCount+' per Package)');
    }
    else {
      m.find('.discrete-units').addClass('hide');
    }

    setImages(r,m);

    var pv = r.packageVolume;
    var pw = r.packageWeight;
    m.find('.pkg-size').html(pv.toFixed(2)+'ml / '+pw.toFixed(2)+'g');
    
    m.find('.category span').html(r.category);
    m.find('.single-pro-details h4').html(r.strainName);
    m.find('.single-pro-details h5').html(r.name);
    m.find('.productType').removeClass('hide').html(r.productType);
    var thc = r.oilTHC;
    m.find('.thccbd .thc-value').html(r.oilTHCLessThan+' '+thc.toFixed(1)+' '+r.measure+" |");
    var cbd = r.oilCBD;
    m.find('.thccbd .cbd-value').html(r.oilCBDLessThan+' '+cbd.toFixed(1)+' '+r.measure);
    var sppg = r.salePricePerGram;
    m.find('.pricing .salePricePerGram').html("$"+sppg.toFixed(2)+' /g');
    var ppg = r.pricePerGram;
    m.find('.pricing .pricePerGram').html("$"+ppg.toFixed(2)+' /g');
    var pp = r.packagePrice;
    m.find('.pricing .packagePrice').html("$"+pp.toFixed(2));
    
    m.find('.strain-desc .strainDescription').html(r.strainDescription);
    var sdv = r.strainDescription;
    sdv === '' ? m.find('.strain-desc').addClass('hide'):m.find('.strain-desc').removeClass('hide');
    
    m.find('.brand-desc .brandDescription').html(r.brandDescription);
    var bdv = r.brandDescription;
    bdv === '' ? m.find('.brand-desc').addClass('hide') : m.find('.brand-desc').removeClass('hide');
    
    m.find('.loading-content').addClass('hide');
    m.find('.item-content').removeClass('hide');

  }

  function updatePlantModal(r,m){

    m.find('.modal-title h4').html(r.strainName);
    m.find('.modal-title h5').html(r.name);

    setImages(r,m);
    
    m.find('.pkg-size').html(r.plantsPerPackage+' plants/pkg');
    m.find('.category span').html(r.category);
    m.find('.single-pro-details h4').html(r.strainName);
    m.find('.single-pro-details h5').html(r.name);
    
    m.find('.pricing div').first().removeClass('hide');
    m.find('.pricing div').last().removeClass('text-start').addClass('text-end');
    var sppg = r.salePricePerPlant;
    var ppg = r.pricePerPlant;
    if(r.salePricePerPlant > 0){
      m.find('.pricing .salePricePerGram').removeClass('hide').html("$"+sppg.toFixed(2)+' /plant');
      m.find('.pricing .pricePerGram').addClass('strikethrough').html("$"+ppg.toFixed(2)+'');
    }
    else {
      m.find('.pricing .salePricePerGram').addClass('hide').html("$"+sppg.toFixed(2)+' /plant');
      m.find('.pricing .pricePerGram').removeClass('strikethrough').html("$"+ppg.toFixed(2)+' /plant');
    }
    var pp = r.packagePrice;
    m.find('.pricing .packagePrice').html("$"+pp.toFixed(2));
    
    m.find('.strain-desc .strainDescription').html(r.strainDescription);
    var sdv = r.description;
    sdv === '' ? m.find('.strain-desc').addClass('hide'):m.find('.strain-desc').removeClass('hide');
    
    m.find('.loading-content').addClass('hide');
    m.find('.item-content').removeClass('hide');

  }

  function updateSourceMaterialModal(r,m){
    
    m.find('.modal-title h4').html(r.strainName);
    m.find('.modal-title h5').html(r.name);

    setImages(r,m);
    
    m.find('.pkg-size').html(r.seedsPerPackage+' seeds/pkg');
    m.find('.category span').html(r.category);
    m.find('.single-pro-details h4').html(r.strainName);
    m.find('.single-pro-details h5').html(r.name);
    
    var pp = r.packagePrice;
    m.find('.pricing .packagePrice').html("$"+pp.toFixed(2));
    m.find('.pricing div').first().addClass('hide');
    m.find('.pricing div').last().removeClass('text-end').addClass('text-start');
    
    m.find('.strain-desc').addClass('hide');
    m.find('.loading-content').addClass('hide');
    m.find('.item-content').removeClass('hide');

  }

  // used for both Accessories and Merchandise
  function updateRetailModal(r,m){
    
    m.find('.modal-title h4').html(r.name);

    var img = "";
    img = r.productImageThumb;
    img = img.includes("default-product.png") ? pluginDir+"../images/default-product.png" : img;
    m.find('.product-img img').attr('src',img);
    
    m.find('.category span').html(r.productCategory);
    m.find('.single-pro-details h4').html(r.name);
    m.find('.productType').html(r.productType);
    var pp = r.price;
    m.find('.pricing .packagePrice').html("$"+pp.toFixed(2));
    
    m.find('.loading-content').addClass('hide');
    m.find('.item-content').removeClass('hide');

  }

  function updateMessageModal(r,m,reply){
    m.find("#airmed-msgReply").hide();
    m.find("#airmed-msgError").hide();
    m.find("#airmed-msgSuccess").hide();
    m.find("#airmed-msgDetails").show();
    
    m.find('.modal-title h4').html(r.subject);
    m.find('.prod-logo').attr('src',r.logo);
    m.find('.prod-name').html(r.producerName);
    m.find('.prod-email').html(r.producerEmail);
    m.find('.prod-phone').html(r.producerPhone1);
    m.find('.msg-priority span').html(r.priority);
    if(reply){
      m.find('.msg-sender').show();
      m.find('.msg-senderEmail').show();
      m.find('.msg-sender span').html(r.senderName);
      m.find('.msg-sender~input').val(r.senderName);
      m.find('.msg-senderEmail span').html(r.senderEmail);
      m.find('.msg-recipient').hide();
      m.find('.msg-recipientEmail').hide();
      m.find('#airmed-replyBtn, #airmed-reply-submit').show();
      m.find('#airmed-followUpBtn, #airmed-followup-submit').hide();
      if (r.viewed) {
        jQuery("#airmed-markUnreadBtn").show();
        jQuery("#airmed-markReadBtn").hide();
      } else {
        jQuery("#airmed-markUnreadBtn").hide();
        jQuery("#airmed-markReadBtn").show();
      }
    }
    else{
      
      m.find('.msg-recipient').show();
      m.find('.msg-recipientEmail').show();
      m.find('.msg-sender').hide();
      m.find('.msg-senderEmail').hide();
      m.find('.msg-recipient span').html(r.recipientName);
      m.find('.msg-sender~input').val(r.recipientName);
      m.find('.msg-recipientEmail span').html(r.recipientEmail);
      m.find('#airmed-replyBtn, #airmed-reply-submit').hide();
      m.find('#airmed-followUpBtn, #airmed-followup-submit').show();
      jQuery("#airmed-markUnreadBtn").hide();
      jQuery("#airmed-markReadBtn").hide();
    }
    
    m.find('.msg-type span').html(r.messageType);
    m.find('.msg-details').html(r.details);
    m.find('#airmed-isViewed').html((r.viewed)?r.dateViewed:'No');
    m.find('#airmed-message-viewed').val(r.viewed);
    m.find('#airmed-message-id').val(r.id);
    m.find('#airmed-DetailsText').val("");
    
    // sets up reply form
    switch (r.priority){
      case "Low":
        p = 1;
        break;
      case "Medium":
        p = 2;
        break;
      case "High":
        p = 3;
        break;
      case "Urgent":
        p = 4;
        break;
      default: 
        p = 1;
    }
    m.find('#airmed-SubjectInput').val(r.subject);
    m.find('#airmed-DetailsText').html('');
    m.find('#airmed-Priority').val(p);
    m.find('#airmed-messageID').val(r.id);

    m.find('.loading-content').addClass('hide');
    m.find('.item-content').removeClass('hide');
  }

  function setImages(r,m){
    var img = "";
    img = (r.productImage == 0) ? r.brandImgThumbString : (r.productImage == 1 ? r.straingImgThumbString : (r.productImage == 2 ? r.productImgThumbString : ""));
    img = img.includes("default-product.png") ? pluginDir+"../images/default-product.png" : img;

    if(m.find('.product-img .img-hover').length){
      imgBrand = r.brandImgThumbString.includes("default-product.png") ? pluginDir+"../images/default-product.png" : r.brandImgThumbString;
      imgStrain = r.strainImgThumbString.includes("default-product.png") ? pluginDir+"../images/default-product.png" : r.strainImgThumbString;
      imgProduct = r.productImgThumbString.includes("default-product.png") ? pluginDir+"../images/default-product.png" : r.productImgThumbString;
      
      image1 = m.find('.product-img .img-hover img.normal ').attr('data-am-image');
      image2 = m.find('.product-img .img-hover img.hover ').attr('data-am-image');

      image1 = (image1 == 'Brand') ? imgBrand : ( image1 == 'Strain' ? imgStrain : imgProduct);
      image2 = (image2 == 'Brand') ? imgBrand : ( image2 == 'Strain' ? imgStrain : imgProduct);
      
      m.find('.product-img img.normal').attr('src',image1);
      m.find('.product-img img.hover').attr('src',image2);
    }
    else if(m.find('.product-img .am-carousel').length){
      img0 = r.brandImgThumbString.includes("default-product.png") ? pluginDir+"../images/default-product.png" : r.brandImgThumbString;
      img1 = r.strainImgThumbString.includes("default-product.png") ? pluginDir+"../images/default-product.png" : r.strainImgThumbString;
      img2 = r.productImgThumbString.includes("default-product.png") ? pluginDir+"../images/default-product.png" : r.productImgThumbString;
      m.find('.product-img>img').addClass('hide');
      // setup carousel
      m.find('.product-img .am-carousel').removeClass('hide');
      m.find('.product-img .am-carousel .img0').attr('src',img0);
      m.find('.product-img .am-carousel .img1').attr('src',img1);
      m.find('.product-img .am-carousel .img2').attr('src',img2);
    }
    else {
      //if(m.find('.product-img .am-carousel').length){m.find('.product-img .am-carousel').addClass('hide');}
      m.find('.product-img>img').removeClass('hide').attr('src',img);
    }
  }

  //make sure modal is reset to load first
  jQuery('.modal.airmed-info').on('hidden.am.modal',function(){
    var modal = jQuery(this);
    modal.find('.loading-content').removeClass('hide');
    modal.find('.item-content').addClass('hide');
  });

  // *** setup catalog filters ***
  var rExp,
    checkThcCbd={
    thcHigh:function(){var n=jQuery(this).find(".thc").attr("value");return n>150},
    thcMed:function(){var n=jQuery(this).find(".thc").attr("value");return n<=150&&n>50},
    thcLow:function(){var n=jQuery(this).find(".thc").attr("value");return n<=50},
    cbdHigh:function(){var n=jQuery(this).find(".cbd").attr("value"); return n>50},
    cbdMed:function(){var n=jQuery(this).find(".cbd").attr("value");return n<=50&&n>10},
    cbdLow:function(){var n=jQuery(this).find(".cbd").attr("value");return n<=10}},
    qs;
  
  // *** filter catalog via search ***
  qs=jQuery("#airmed-quicksearch input").keyup(
    function(){
      rExp=new RegExp(qs.val(),"gi");
      filterCatalog(jQuery(this))
    }
  );

  // reset all filters and hidden items upon catalog change
  function resetFilters(e){
    var profile = ['products','derivs','plants','material'],
        thccbd = ['products','derivs'],
    cat = e.find('.am-nav-link').attr('aria-controls').substring(7);
    
    jQuery('#airmed-filters.filter-theme1 .filter-item, #airmed-filters.filter-theme2 .filter-list-item, #airmed-filters.filter-theme3 .filter-item').not('#airmed-quicksearch').not('.airmed-nav').addClass('d-none');
    
    // filter theme 1/3
    if(profile.indexOf(cat)>-1){
      jQuery('#airmed-filters.filter-theme1 .filter-item[data-filter-group="profile"]').removeClass('d-none');
      jQuery('#airmed-filters.filter-theme3 .filter-item[data-filter-group="profile"]').removeClass('d-none');
    }
    if(thccbd.indexOf(cat)>-1){
      jQuery('#airmed-filters.filter-theme1 .filter-item[data-filter-group="thc"]').removeClass('d-none');
      jQuery('#airmed-filters.filter-theme1 .filter-item[data-filter-group="cbd"]').removeClass('d-none');
      jQuery('#airmed-filters.filter-theme3 .filter-item[data-filter-group="thc"]').removeClass('d-none');
      jQuery('#airmed-filters.filter-theme3 .filter-item[data-filter-group="cbd"]').removeClass('d-none');
    }
    jQuery('#airmed-filters.filter-theme1 .filter-item .filter-list .filter-list-item:first-child input').prop('checked', true);
    
    //used for filter theme 3
    jQuery('#airmed-filters.filter-theme3 .filter-item select option:first-child').attr('selected', 'selected');
    
    //filter theme 2
    if(profile.indexOf(cat)>-1){
      jQuery('#airmed-filters.filter-theme2 .filter-tab[data-filter-group="profile"]').parent('.filter-list-item').removeClass('d-none');
    }
    if(thccbd.indexOf(cat)>-1){
      jQuery('#airmed-filters.filter-theme2 .filter-tab[data-filter-group="thc"]').parent('.filter-list-item').removeClass('d-none');
      jQuery('#airmed-filters.filter-theme2 .filter-tab[data-filter-group="cbd"]').parent('.filter-list-item').removeClass('d-none');
    }
    
    jQuery('#airmed-filters.filter-theme2 .filter-options .filter-options-item-selected').removeClass('filter-options-item-selected');

    // quick search
    jQuery('#airmed-quicksearch input').val('');

    //catalog reset
    jQuery('#airmed-wrapper>.airmed-content .am-tab-content>.airmed-flex-container.active>.airmed-item').removeClass('d-none');
  }
  
  function filterCatalog(i){
    var filterItems={},
      profile=jQuery('#airmed-filters.filter-theme1 .filter-item[data-filter-group="profile"] input:checked, #airmed-filters.filter-theme3 .filter-item[data-filter-group="profile"] select option:selected, #airmed-filters.filter-theme2 .filter-tab[data-filter-group="profile"]~.filter-options .filter-options-item-selected').attr("data-filter"),
      thc=jQuery('#airmed-filters.filter-theme1 .filter-item[data-filter-group="thc"] input:checked, #airmed-filters.filter-theme3 .filter-item[data-filter-group="thc"] select option:selected, #airmed-filters.filter-theme2 .filter-tab[data-filter-group="thc"]~.filter-options  .filter-options-item-selected').attr("data-filter"),
      cbd=jQuery('#airmed-filters.filter-theme1 .filter-item[data-filter-group="cbd"] input:checked, #airmed-filters.filter-theme3 .filter-item[data-filter-group="cbd"] select option:selected, #airmed-filters.filter-theme2 .filter-tab[data-filter-group="cbd"]~.filter-options .filter-options-item-selected').attr("data-filter"),
      p=jQuery('#airmed-filters.filter-theme2 .filter-tab[data-filter-group="profile"]~.filter-options .filter-options-item-selected');
      //group = i.attr('data-filter-group');
      
      // shorthand if statements
      profile&&(filterItems.profile=profile);
      thc&&(filterItems.thc=thc);
      cbd&&(filterItems.cbd=cbd);
      
    jQuery("#airmed-wrapper>.airmed-content .am-tab-content>.airmed-flex-container.active>.airmed-item").each(function(){
      var i=jQuery(this),searchMatch,n,e;
      i.addClass("d-none");
      
      //used for search - if r exists
      searchMatch=rExp?i.text().match(rExp):true;

      n=true;
      for(e in filterItems){
        filter=filterItems[e];
        //filter=checkThcCbd[filter]||filter;
        if(filter){
          if(!jQuery(this).is(filter)){
            n=false;
          }
        }
        if(!n)break;
      }
      
      /*
      n=true;
      for(e in filterItems){
        filter=filterItems[e];
        //filter=checkThcCbd[filter]||filter;
        filter&&(n=n&&jQuery(this).is(filter));
        if(!n)break;
      }
      */
      
      if(n && searchMatch){
        i.removeClass("d-none");
      }
      
    })
  }
  
  // filter theme1 - do action for each filter radio input or select change
  jQuery("#airmed-filters .filter-item").each(function(){
    var i=jQuery(this);
    i.on("change","input, select",function(){
      filterCatalog(i);
    })
  });
  
  // filter theme2 - open filter section for each filter section button
  jQuery("#airmed-filters .filter-tab").click(function(){
    let i=jQuery(this).find('.filter-expand');
    let s=jQuery(this).siblings('.filter-options');
    let c = 'invisible';
    let d = 'filter-options-show';
    if(i.hasClass(c)){
      i.removeClass(c);
      s.removeClass(d);
    }
    else {
      i.addClass(c);
      s.addClass(d);
    }
    
  });

  // filter theme2 - do catalog action for each filter button item
  jQuery("#airmed-filters .filter-tab").each(function(){
    var i=jQuery(this);
    i.siblings('.filter-options').on("click",".filter-container",function(){
      let c = 'filter-options-item-selected';
      jQuery(this).siblings().find('.'+c).removeClass(c);
      j = jQuery(this).find('.filter-options-item');
      j.hasClass(c) ? j.removeClass(c) : j.addClass(c);
      filterCatalog(i);
    })
  });

  // reset everything if nav tab change
  jQuery("#airmed-wrapper .airmed-nav .nav .am-nav-item").on("click",function(){
    if(!jQuery(this).hasClass('active')){
      jQuery(this).siblings().removeClass('active');
      jQuery(this).addClass('active');
      resetFilters(jQuery(this));
    }
  });

  // clear filters for filter theme2
  jQuery("#airmed-clear-filters").on("click",function(){
    var e = jQuery(".airmed-wrapper .airmed-nav .am-nav-link.active").parent();
    resetFilters(e);
  });

  /*
  **  Login and New Account Page
  */

  //jQuery('#amPhone').mask('(999) 999-9999? x99999');

  jQuery(".phone").keyup(function() {
    jQuery(this).val(jQuery(this).val().replace(/^\(?(\d{3})\)?[\s-]?(\d{3})[\s-]?(\d+)$/, "($1) $2-$3"));
  });

  // Hide/show password input field
  jQuery("#amShowPassword").on("click",function(e){
    (jQuery('#amPassword').attr("type") == "text") ? jQuery('#amPassword').attr("type","password") : jQuery('#amPassword').attr("type","text");
    (jQuery('#amConfirmPassword').attr("type") == "text") ? jQuery('#amConfirmPassword').attr("type","password") : jQuery('#amConfirmPassword').attr("type","text");
  });
  jQuery("#amModalShowPassword").on("click",function(e){
    (jQuery('#amModalPassword').attr("type") == "text") ? jQuery('#amModalPassword').attr("type","password") : jQuery('#amModalPassword').attr("type","text");
  });
  
  // password validation checks
  function passwordCheck(){
    var p = jQuery('#amPassword').val();
    var cp = jQuery('#amConfirmPassword').val();
    jQuery('#amPassword').removeClass('is-invalid');
    jQuery('#amConfirmPassword').removeClass('is-invalid');
    
    //check validation
    var r = jQuery('#amPassword').attr('data-val-regex-pattern');
    var rx = new RegExp(r);
    if (rx.test(p)){
      jQuery('#amConfirmPassword').prop('readonly',false);
      //if both password and confirmPassword filled, check if the same
      if (p!='' && cp!=''){
       if (p !== cp){
         // throw error passwords do not match
         jQuery('#amConfirmPassword').addClass('is-invalid');
         //alert("no match");
         return false;
       }
      }
    }
    else {
      // throw error password does not match requirements
      jQuery('#amPassword').addClass('is-invalid');
      //alert('the password has invalid requirements');
      return false;
    }
    return true;
  }
  jQuery('#amPassword,#amConfirmPassword').on("change",function(e){
    if(jQuery('#amConfirmPassword').length){var pCheck = passwordCheck();}
  });

  // modal error
  function showModalError(title,message){
    jQuery('#airmed-modal-error .modal-title h1').html(title);
    jQuery('#airmed-modal-error .card-body').html(message);
    jQuery('#airmed-modal-error').modal({
      backdrop: 'static',
      keyboard: false
    });
    jQuery('#airmed-modal-error').modal('show');

  }

  // *** generate a reCaptcha v3 token and populate the hidden variable with the result ***
  if (jQuery("#GoogleCaptchaToken").length){
    grecaptcha.ready(function () {
      grecaptcha.execute('6LeP5XQcAAAAAJfXEeAAbpJl0LGVxTBYzNrS5yku', { action: 'signup' }).then(function (token) {
        jQuery("#GoogleCaptchaToken").val(token);
      });
    });
  }

  // *** Digital Signature Post***
  if (jQuery("#amSignature-canvas").length){
  
    // Adjust canvas coordinate space taking into account pixel ratio,
    // to make it look crisp on mobile devices.
    // This also causes canvas to be cleared.
    var clearButton = jQuery("#amSignature-clear"),
        saveButton = jQuery("#amSignature-save"),
        //regButton = jQuery("#amSignuater-reg"),
        canvas = jQuery("#amSignature-canvas")[0],
        signaturePad = new SignaturePad(canvas);
        
    function resizeCanvas(canvas) {
        // When zoomed out to less than 100%, for some very strange reason,
        // some browsers report devicePixelRatio as less than 1
        // and only part of the canvas is cleared then.
        var ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.parentNode.offsetWidth * ratio;
        canvas.height = canvas.parentNode.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
    }

    function dataURItoBlob(dataURI) {
        var byteString = atob(dataURI.split(',')[1]);
        var ab = new ArrayBuffer(byteString.length);
        var ia = new Uint8Array(ab);
        for (var i = 0; i < byteString.length; i++) {
            ia[i] = byteString.charCodeAt(i);
        }
        return new Blob([ab], { type: 'image/png' });
    }

    //window.onresize = resizeCanvas;

    //var canvas = jQuery("#signature-canvas")[0];
    //resizeCanvas(canvas);

    clearButton.on("click", function (e) {
        e.preventDefault();
        signaturePad.clear();
    });
    
    saveButton.on("click", function(e){
      if(signaturePad.isEmpty())alert(airmed.blankSignatureError);
      else if(jQuery(this).valid()){
        var data = signaturePad.toDataURL('image/png');
        var blob = dataURItoBlob(data);
      }
    });

    // form submission
    jQuery(".am-account-form").unbind("submit").bind("submit",function(e){
      e.preventDefault();
      if(signaturePad.isEmpty())alert(airmed.blankSignatureError);
      else if(jQuery(this).valid()){
        let fn = jQuery("#airmed-wrapper .am-account-form input[name='amPatientID']").val();
        var filename=fn+"_sig.png",
            data = signaturePad.toDataURL('image/png'),
            blob = dataURItoBlob(data),
            formData = new FormData(this),
            redirect = jQuery(this).data('redirect');
        formData.append("Signature",blob,filename);
        jQuery.ajax({
          url: jQuery(this).attr("action"),
          type:this.method,
          data:formData,
          contentType: false,
          processData: false,
          beforeSend:function(){
            jQuery(".registering-user").show()
          },
          success:function(s){
            if (s.error === 200){
              //alert('redirect to '+redirect)
              window.location.href = redirect;
            }
            else {
              jQuery(".registering-user").hide();
              //s = s.replaceAll("'{","{");
              //s = s.replaceAll("}'","}");
              //s = s.replaceAll("'","\"");
              //if (s.includes("{")){
              //  s = JSON.parse(s);
              //}

              alert("Error:"+s.error+"\nMessage: "+s.message) 
            }
          },
          error:function(err){
            jQuery(".registering-user").hide()
            alert("wordpress or ajax error occured")
          }
        });
      }
    });
    
  }  // end of Signature check

  // *** Messages tables***
  if (jQuery(".airmed-dataTable").length){
    jQuery('.airmed-dataTable').dataTable({
      paging: true,
      stateSave: true,
      sDom: '<ftip>',
      responsive: true,
      columnDefs: [
          { "responsivePriority": 1, "targets": -1 },
          { "responsivePriority": 2, "targets": 1 },
          { "responsivePriority": 3, "targets": 0 }
      ],
      "language":{"emptyTable":"No data available"}
    });
    
  }
  /*
  if (jQuery("#airmed-inboxTable").length){
    var itable = jQuery('#airmed-inboxTable').dataTable({
      paging: true,
      stateSave: true,
      sDom: '<ftip>',
      responsive: true,
      columnDefs: [
          { "responsivePriority": 1, "targets": -1 },
          { "responsivePriority": 2, "targets": 1 },
          { "responsivePriority": 3, "targets": 0 }
      ],
      "language":{"emptyTable":"No Messages"}
    });
    
    var stable = jQuery('#airmed-sentTable').dataTable({
      paging: true,
      stateSave: true,
      sDom: '<ftip>',
      responsive: true,
      columnDefs: [
          { "responsivePriority": 1, "targets": -1 },
          { "responsivePriority": 2, "targets": 1 },
          { "responsivePriority": 3, "targets": 0 }
      ],
      "language":{"emptyTable":"No Messages"}
    });
    
  }
  */
  
  // *** progress meter ***
  if (jQuery("#airmed-progress-meter").length){
    jQuery("#airmed-register-chevron").mouseenter(function () {
        jQuery("#airmed-base-txt").hide();
        jQuery("#airmed-register-txt").show();
    }).mouseleave(function () {
        jQuery("#airmed-register-txt").hide();
        jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-submit-app-chevron").mouseenter(function () {
        jQuery("#airmed-base-txt").hide();
        jQuery("#airmed-submit-app-txt").show();
    }).mouseleave(function () {
        jQuery("#airmed-submit-app-txt").hide();
        jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-processing-app-chevron").mouseenter(function () {
        jQuery("#airmed-base-txt").hide();
        jQuery("#airmed-processing-app-txt").show();
    }).mouseleave(function () {
        jQuery("#airmed-processing-app-txt").hide();
        jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-app-order-chevron").mouseenter(function () {
        jQuery("#airmed-base-txt").hide();
        jQuery("#airmed-app-order-txt").show();
    }).mouseleave(function () {
        jQuery("#airmed-app-order-txt").hide();
        jQuery("#airmed-base-txt").show();
    });

    jQuery("#airmed-app-approved-chevron").mouseenter(function () {
        jQuery("#airmed-base-txt").hide();
        jQuery("#airmed-app-approved-txt").show();
    }).mouseleave(function () {
        jQuery("#airmed-app-approved-txt").hide();
        jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-order-chevron").mouseenter(function () {
        jQuery("#airmed-base-txt").hide();
        jQuery("#airmed-order-txt").show();
    }).mouseleave(function () {
        jQuery("#airmed-order-txt").hide();
        jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-processing-order-chevron").mouseenter(function () {
        jQuery("#airmed-base-txt").hide();
        jQuery("#airmed-processing-order-txt").show();
    }).mouseleave(function () {
        jQuery("#airmed-processing-order-txt").hide();
        jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-shipped-chevron").mouseenter(function () {
        jQuery("#airmed-base-txt").hide();
        jQuery("#airmed-shipped-txt").show();
    }).mouseleave(function () {
        jQuery("#airmed-shipped-txt").hide();
        jQuery("#airmed-base-txt").show();
    });
  }
  
  // *** order status progress ***
  if (jQuery("#airmed-orderStatusCard").length){
    jQuery("#airmed-register-chevron").mouseenter(function () {
      jQuery("#airmed-base-txt").hide();
      jQuery("#airmed-register-txt").show();
    }).mouseleave(function () {
      jQuery("#airmed-register-txt").hide();
      jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-submit-app-chevron").mouseenter(function () {
      jQuery("#airmed-base-txt").hide();
      jQuery("#airmed-submit-app-txt").show();
    }).mouseleave(function () {
      jQuery("#airmed-submit-app-txt").hide();
      jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-processing-app-chevron").mouseenter(function () {
      jQuery("#airmed-base-txt").hide();
      jQuery("#airmed-processing-app-txt").show();
    }).mouseleave(function () {
      jQuery("#airmed-processing-app-txt").hide();
      jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-app-order-chevron").mouseenter(function () {
      jQuery("#airmed-base-txt").hide();
      jQuery("#airmed-app-order-txt").show();
    }).mouseleave(function () {
      jQuery("#airmed-app-order-txt").hide();
      jQuery("#airmed-base-txt").show();
    });

    jQuery("#airmed-app-approved-chevron").mouseenter(function () {
      jQuery("#airmed-base-txt").hide();
      jQuery("#airmed-app-approved-txt").show();
    }).mouseleave(function () {
      jQuery("#airmed-app-approved-txt").hide();
      jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-order-chevron").mouseenter(function () {
      jQuery("#airmed-base-txt").hide();
      jQuery("#airmed-order-txt").show();
    }).mouseleave(function () {
      jQuery("#airmed-order-txt").hide();
      jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-processing-order-chevron").mouseenter(function () {
      jQuery("#airmed-base-txt").hide();
      jQuery("#airmed-processing-order-txt").show();
    }).mouseleave(function () {
      jQuery("#airmed-processing-order-txt").hide();
      jQuery("#airmed-base-txt").show();
    });
    jQuery("#airmed-shipped-chevron").mouseenter(function () {
      jQuery("#airmed-base-txt").hide();
      jQuery("#airmed-shipped-txt").show();
    }).mouseleave(function () {
      jQuery("#airmed-shipped-txt").hide();
      jQuery("#airmed-base-txt").show();
    });
  
  }

  // *** modal message read, reply, and forward ***
  if (jQuery("#airmed-modal-message").length){
    jQuery("#airmed-markReadBtn, #airmed-markUnreadBtn").click(function (e) {
      //var param = {};
      //param["prodid"] = jQuery('#airmed-message-id').val();
      //param["Read"] = jQuery('#airmed-message-viewed').val();
      //param["mtype"] = "POST";
      var read = jQuery('#airmed-message-viewed').val() === "true" ? false : true;
      jQuery.ajax({
        //url: "http://localhost:81/wordpress/test.json",
        url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
        //url: "/PatientMessage/UpdateRead",
        datatype: 'json',
        method: 'POST',
        data: {
          action: 'airmed_message_read',
          ID: jQuery('#airmed-message-id').val(),
          itype: 'messageRead',
          read: read
        },
        success: function (response) {
          if (response.errno == 200) {
            airmed.modalSubmit = true;
            //if (response.jsonRead.isViewed == true) {
            if (read){
              //var date = new Date(parseInt(response.jsonRead.dateViewed.substr(6))).toISOString().substr(0, 10);
              /*
              var d = new Date();
              var hr = d.getHours();
              var min = d.getMinutes();
              if (min < 10) {
                  min = "0" + min;
              }
              var ampm = "am";
              if( hr > 12 ) {
                  hr -= 12;
                  ampm = "pm";
              }
              if (hr === 12){ampm = "pm";}
              var date = d.getDate();
              var month = d.getMonth()+1;
              var year = d.getFullYear();
              var x = year+"-"+month+"-"+date+" "+ hr + ":" + min + ampm;
              */
              //var r = JSON.parse(response.response);
              
              jQuery("#airmed-message-viewed").val(true);
              jQuery('#airmed-isViewed').html(response.response.dateViewed);
              jQuery("#airmed-markUnreadBtn").show();
              jQuery("#airmed-markReadBtn").hide();
            } else {
              jQuery("#airmed-message-viewed").val(false);
              jQuery('#airmed-isViewed').html("No");
              jQuery("#airmed-markUnreadBtn").hide();
              jQuery("#airmed-markReadBtn").show();
            }
          } else {
            //jQuery("#airmed-msgError").show().html(response);
            //jQuery("#airmed-msgReply").hide();
            //jQuery("#airmed-msgDetails").hide();
          }
        },
        error: function (response) {
          var r = response;
          alert('error');
            //jQuery("#airmed-msgError").show().html(response);
            //jQuery("#airmed-msgReply").hide();
            //jQuery("#airmed-msgDetails").hide();
        },
      });
    });

    jQuery("#airmed-replyBtn, #airmed-followUpBtn").click(function () {
      jQuery("#airmed-msgReply").show();
      jQuery("#airmed-msgDetails").hide();
    });

    jQuery("#airmed-reply-submit, #airmed-followup-submit ").click(function (e) {
      e.preventDefault();
      var subject = jQuery('#airmed-SubjectInput').val();
      var details = jQuery('#airmed-DetailsText').val();
      //  check to make sure all fields are filled before ajax call. 
      if ((subject == "")||(details == "")){
        if (subject == ""){jQuery('#airmed-SubjectInput').focus();alert("The message subject needs to be filled");}
        if (details == ""){jQuery('#airmed-DetailsText').focus();alert("The message details needs to be filled");}
      }
      else {
        var reply = jQuery(this).data('reply');
        jQuery.ajax({
            // need to do wordpress ajax call instead
            //url: "/PatientMessage/Reply",
            url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
            method: "POST",
            data: {
              action: 'airmed_message_reply',
              //action: action,
              ID: jQuery('#airmed-message-id').val(),
              RecipientName: jQuery('#airmed-replyForm input[name="RecipientName"]').val(),
              Subject: subject,
              Priority: jQuery('#airmed-Priority').val(),
              Details : details,
              Reply : reply
            },
            success: function (response) {
              airmed.modalSubmit = true;
              alert("success - ");
              //jQuery("#airmed-msgSuccess").show();
              //jQuery("#airmed-msgReply").hide();
              
              //jQuery('#airmed-modal-message .modal-content').html(response);
            },
            error: function (response) {
              alert("error");
              //jQuery("#airmed-msgError").show();
              //jQuery("#airmed-msgReply").hide();
              //jQuery('#airmed-modal-message .modal-content').html(response);
            },
        });
      }
    });

    // used to refresh the page after a modal submission was done
    jQuery('#airmed-modal-message').on('hidden.am.modal', function () {
      if (airmed.modalSubmit){
        //alert('change took place');
        //airmed.modalSubmit = false;
        var params = "";
        if(jQuery('#airmed-sent').hasClass('active')){params = "?tab=sent"};
        const urlPieces = [location.protocol, '//', location.host, location.pathname, params];
        let url = urlPieces.join('');
        //alert(url);
        window.location.href = url;
      }
    });
    
    // clear and reparse the ajax form for validation
    //var form = jQuery(".modal-body form").removeData("validator").removeData("unobtrusiveValidation");
    //jQuery.validator.unobtrusive.parse(form);
  }
  
  // *** gets the current Catalog layout if logging in
  function getCatalogQuery(){
    // get tab
    var tab = jQuery('#airmed-wrapper .nav-theme1 .am-nav-link.active').attr('aria-controls');
    if(!(tab !== undefined)){
      tab = jQuery('#airmed-wrapper .nav-theme2 .am-nav-link.active').attr('aria-controls');
    }
    if(!(tab !== undefined)){
      tab = jQuery('#airmed-wrapper .nav-theme3 .am-nav-link.active').attr('aria-controls');
    }
    var query = "tab="+tab;
    
    // get search
    var search = jQuery('#airmed-quicksearch input ').val();
    if(search !== ''){
      query+='&search='+search;
    }
    
    // get filters
    var filters = '';
    if (jQuery('#airmed-wrapper .filter-theme1').length){
      jQuery('#airmed-wrapper .filter-theme1 .filter-item .filter-list-item input:checked').each( function(){
        if(filters.length > 0){filters+=','};
        filters += jQuery(this).closest('.filter-item').attr('data-filter-group') + "=";
        filters += jQuery(this).attr('data-filter');
      });
      query+='&filters='+filters;
    }
    else if (jQuery('#airmed-wrapper .filter-theme2').length) {
      jQuery('#airmed-wrapper .filter-theme2 .filter-list-item .filter-options-item-selected').each( function(){
        if(filters.length > 0){filters+=','};
        filters += jQuery(this).closest('.filter-list-item').children('.filter-tab').attr('data-filter-group') + "=";
        filters += jQuery(this).attr('data-filter');
      });
      query+='&filters='+filters;
    }
    else if (jQuery('#airmed-wrapper .filter-theme3').length) {
      jQuery('#airmed-wrapper .filter-theme3 .filter .filter-item .form-select option:selected').each( function(){
        if(filters.length > 0){filters+=','};
        filters += jQuery(this).closest('.filter-item').attr('data-filter-group') + "=";
        filters += jQuery(this).attr('data-filter');
      });
      query+='&filters='+filters;
    }

    return query;
  }
  
  // *** modal login ***
  if (jQuery("#airmed-modal-login").length){

    jQuery('#airmed-modal-login').on('hidden.am.modal', function () {
      jQuery("#airmed-modal-login .am-loading").addClass("hide");
      jQuery("#airmed-modal-login .alert").addClass("hide");
    });
    //jQuery('#airmed-modal-login').on('shown.am.modal', function () {
    //  var query = getCatalogQuery();
    //    alert(query);
    //});
    
    jQuery("#airmed-modal-login-submit").click(function (e) {
      e.preventDefault();
      jQuery("#airmed-modal-login .am-loading").removeClass("hide");
      //var rUrl = location.pathname.slice(-8);
      var returnUrl = '';
      var query = '';
      //if (rUrl == '/airmed/'){
      if(jQuery('#airmed-wrapper .airmed-content.catalog').length){
        query = getCatalogQuery();
        returnUrl = document.location.href;
      }
      var username = jQuery('#amModalUsername').val();
      var pwd = jQuery('#amModalPassword').val();
      
      jQuery.ajax({
          // need to do wordpress ajax call instead
          url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
          method: "POST",
          datatype: 'json',
          data: {
            action: 'airmed_login',
            returnUrl: returnUrl,
            query: query,
            //action: action,
            amUsername: username,
            amPassword: pwd
          },
          success: function (response) {
            //airmed.modalSubmit = true;
            //alert("success - ");
            if(response.errno == '200'){
              window.location.href = response.returnUrl;
            }
            else {
              jQuery("#airmed-modal-login .am-loading").addClass("hide");
              jQuery("#airmed-modal-login .alert").removeClass("hide");
            }
          },
          error: function (response) {
            jQuery("#airmed-modal-login .am-loading").addClass("hide");
            jQuery("#airmed-modal-login .alert").removeClass("hide");
            alert("PHP error");
          },
      });
    });
  }
 
   // *** Catalog check for catalog query params ***
  if (jQuery("#airmed-wrapper > .catalog").length){
    //var filters = getUrlParameter("filters"));
    
    search = jQuery("#airmed-quicksearch input").val();
    if (search !== ''){
      jQuery("#airmed-quicksearch input").keyup();
    }
    else {
      filterCatalog();
    }
  }
 
  /* Add Update Menu Cart */
  function updateMenuCartCount(){
    //alert('update cart');
    // update cart menu count
    let cartItems = jQuery('#airmed-menu-cart-items').html();
    cartItems++;
    jQuery('#airmed-menu-cart-items').html(cartItems);
    
  }
  
  /* Update Modal Cart*/
  function updateModalCart(order){
    let orderId = jQuery('#airmed-cart-slideout').attr('data-am-orderid');
    // make sure there's an order id set
    if (!orderId.length){
      jQuery('#airmed-cart-slideout').attr('data-am-orderid',order.id);
    }
    
    // hide/show no items context and footer
    if(order.orderItems.length){
      jQuery('#airmed-cart-slideout .modal-body-cart-container .no-items').addClass('hide');
      jQuery('#airmed-cart-slideout .modal-footer').removeClass('hide');
      // update button links if needed
      jQuery('#airmed-cart-slideout .modal-footer .btn').each(function(i){
        let href = jQuery(this).attr('href');
        if(href.indexOf('?id=') < 0) {
          jQuery(this).attr('href',href+'='+order.id);
        }
      });
    }
    else {
      jQuery('#airmed-cart-slideout .modal-body-cart-container .no-items').removeClass('hide');
      jQuery('#airmed-cart-slideout .modal-footer').addClass('hide');
    }
    
    // remove original items
    jQuery('#airmed-cart-slideout .modal-body-cart-container .item-container:not(.default)').remove();
    
    //redraw modal cart items
    order.orderItems.forEach( function(item,i){
      let clone = jQuery('#airmed-cart-slideout .modal-body-cart-container .item-container.default').clone(true).removeClass('hide').removeClass('default');
      let desc = item.description.split(" ($");
      jQuery(clone).find('.item-name').html(desc[0]);
      if (desc.length > 1 ){jQuery(clone).find('.item-weight').html("($"+desc[1]);}

      jQuery(clone).find('.item-removeButton').attr('data-am-prodid',item.id).attr('data-am-orderid',order.id).attr('data-am-quantity',item.quantity).attr('data-am-source','slideout');
      jQuery(clone).find('.item-quantity').html(item.quantity);
      jQuery(clone).find('.item-unitPrice').html('$'+ item.unitPrice.toFixed(2));
      if(item.unitSalePrice > 0){
        jQuery(clone).find('.item-unitPrice').addClass('strikethrough');
        jQuery(clone).find('.item-price .text-danger').removeClass('hide').html('$'+ item.unitSalePrice.toFixed(2));
      }
      let imgContainer = jQuery(clone).children('img');
      if ( imgContainer.length ){
        let img = "";
        img = (item.productImage == 0) ? item.brandImgThumbString : (item.productImage == 1 ? item.straingImgThumbString : (item.productImage == 2 ? item.productImgThumbString : ""));
        img = img.includes("default-product.png") ? pluginDir+"../images/default-product.png" : img;
        jQuery(imgContainer).attr('src',img);
      }
      // append copy to modal cart body
      jQuery('#airmed-cart-slideout .modal-body-cart-container').append(clone);
    });

    // subtotal
    jQuery('#airmed-cart-slideout .modal-footer .subtotal>span~span').html('$'+ order.subTotal.toFixed(2));
    
  }

  function updateCatalogItem(order,prodid){
    let item = jQuery('#'+prodid);
    jQuery(item).find('.am-loading').addClass('hide');
    jQuery(item).find('.add-to-cart a>button').addClass('added');
  }

  /* Add to cart */
  function addToOrder(prodid){
    jQuery.ajax({
      // need to do wordpress ajax call instead
      //url: "/PatientMessage/Reply",
      url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
      method: "POST",
      data: {
        action: 'airmed_add_to_order',
        //action: action,
        itemid: prodid,
        
      },
      success: function (response) {
        let item = jQuery('#'+prodid);
        if(response.errno != 200 ){
          // airmed API error
          alert(response.errno+' - '+response.err+': '+response.response.replace('["','').replace('"]','') );
        }
        else{
          //alert("add success - " + response);
          // need to update cart slider and top menu cart count
          updateMenuCartCount();
          updateModalCart(response.response);
          // theme 1
          jQuery(item).find('.add-to-cart-button a').addClass('added');
          // theme 2
          jQuery(item).find('.add-to-cart .dashicons-cart').addClass('hide');
          jQuery(item).find('.add-to-cart .dashicons-yes').removeClass('hide');
          // theme 3
          jQuery(item).find('.add-to-cart a>button').addClass('added');
          //updateCatalogItem(response.response,prodid);
          
        }
        jQuery(item).find('.am-loading').addClass('hide')
      },
      error: function (response) {
        alert("error");
      },
    });


  }
  jQuery("#airmed-wrapper #airmed-products .airmed-item a[data-am-prodid],#airmed-wrapper #airmed-derivs .airmed-item a[data-am-prodid],#airmed-wrapper #airmed-merchandise .airmed-item a[data-am-prodid]").click(function(e){
    //alert(jQuery(this).attr('data-am-prodid'));
    e.preventDefault();
    var prodid = jQuery(this).attr('data-am-prodid');
    jQuery('#'+prodid).find('.am-loading').removeClass('hide');
    // theme 1
    jQuery(this).removeClass('added');

    // theme 2
    jQuery('#'+prodid).find('.add-to-cart .dashicons-cart').removeClass('hide');
    jQuery('#'+prodid).find('.add-to-cart .dashicons-yes').addClass('hide');

    // theme 3
    jQuery(this).children().removeClass('added');

    //jQuery('#airmed-cart-slideout .am-loading').removeClass('hide');
    addToOrder(prodid);

  });

  /* Remove Update Cart */
  function removeUpdateMenuCartCount(item){
    //alert('update menu cart');
    // update cart menu count
    let cartItems = jQuery('#airmed-menu-cart-items').html();
    cartItems = cartItems - item.quantity;
    jQuery('#airmed-menu-cart-items').html(cartItems);
  }

  /* Update current order page */
  function removeUpdatePage(){
    
  }

  /* Remove from order */
  function removeFromOrder(item){
    jQuery.ajax({
      // need to do wordpress ajax call instead
      //url: "/PatientMessage/Reply",
      url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
      method: "POST",
      data: {
        action: 'airmed_remove_from_order',
        //action: action,
        itemid: item.itemid,
        orderid: item.orderid,
      },
      success: function (response) {
        if(response.errno != 200 ){
          alert(response.errno+' - '+response.err+': '+response.response.replace('["','').replace('"]','') );
        }
        else{
          //alert("remove success - " + response);
          // need to update cart slider and top menu cart count or refresh page
          if(item.source == 'slideout'){
            removeUpdateMenuCartCount(item);
            updateModalCart(response.response);
            jQuery('#airmed-cart-slideout .am-loading').addClass('hide');
          }
          else {
            //window.location.href = item.source;
            sessionStorage.setItem("alert","success");
            window.location.reload(true);
          }
        }

      },
      error: function (response) {
        jQuery('#airmed-cart-slideout .am-loading').addClass('hide');
        alert("error");
      },
    });


  }
  jQuery(".airmed-wrapper .airmed-cart a[data-am-prodid].item-removeButton,.airmed-wrapper #airmed-cart-slideout .item-container a[data-am-prodid].item-removeButton").click(function(e){
    //alert(jQuery(this).attr('data-am-prodid'));
    e.preventDefault();
    var item = {};
    item.itemid = jQuery(this).attr('data-am-prodid');
    item.source = jQuery(this).attr('data-am-source');
    item.quantity = jQuery(this).attr('data-am-quantity');
    item.orderid = jQuery('#airmed-cart-slideout').attr('data-am-orderid');
    if(item.source == 'slideout'){
      jQuery('#airmed-cart-slideout .am-loading').removeClass('hide');
    }
    else {
      if(jQuery(".airmed-wrapper .airmed-cart .airmed-mobile").css("display") == "none"){
        jQuery(".airmed-wrapper .airmed-cart .am-loading").removeClass('hide');
      }
      else{
        jQuery(this).closest(".card").find(".am-loading").removeClass('hide');
      }
      //jQuery(".airmed-wrapper .airmed-cart .alert").addClass('hide');
    }

    removeFromOrder(item);

  });
  
  /* Update Cart and current open order */
  jQuery(".airmed-wrapper .airmed-cart .form-select.quantity").on('change', function(e){
    var that = this;
    var itemid = jQuery(this).attr('data-am-itemid');
    var orderid = jQuery(this).attr('data-am-orderid');
    var oldQuantity = jQuery(this).attr('data-am-quantity');
    var quantity = jQuery(this).val();
    var refresh = jQuery(this).attr('data-am-refresh');
    
    if(jQuery(".airmed-wrapper .airmed-cart .airmed-mobile").css("display") == "none"){
      jQuery(".airmed-wrapper .airmed-cart .am-loading").removeClass('hide');
    }
    else{
      jQuery(this).closest(".card").find(".am-loading").removeClass('hide');
    }

    //jQuery(".airmed-wrapper .airmed-cart .alert").addClass('hide');
    
    jQuery.ajax({
    // need to do wordpress ajax call instead
    //url: "/PatientMessage/Reply",
    url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
    method: "POST",
    data: {
      action: 'airmed_update_order_item',
      orderid: orderid,
      itemid: itemid,
      qty: quantity,
      
    },
    success: function (response) {
      //let item = jQuery('#'+prodid);
      if(response.errno != 200 ){
        // airmed API error
        alert(response.errno+' - '+response.err+': '+response.response.replace('["','').replace('"]','') );
        jQuery(that).val(oldQuantity);
        jQuery(".airmed-wrapper .airmed-cart .am-loading").addClass('hide');
      }
      else{
        //alert("item udpated - success - " + response);
        //jQuery(".airmed-wrapper .airmed-cart .alert").removeClass('hide');
        //jQuery(".airmed-wrapper .airmed-cart .am-loading").addClass('hide');
        //window.location.hash = '#'+itemid;
        sessionStorage.setItem("alert","success");
        window.location.reload(true);
      }
    },
    error: function (response) {
      alert("error");
    },
  });

  });
  
  // show alert on cart page load if an action took place
  if( jQuery(".airmed-wrapper .airmed-cart").length ){
    let a = sessionStorage.getItem("alert");
    sessionStorage.removeItem("alert");
    if( a == "success"){jQuery(".airmed-wrapper .airmed-cart .alert-success").removeClass('hide');}
  }

  /* Handle Coupon calls */
  if( jQuery(".airmed-wrapper .airmed-checkout").length ){
    // Add Coupon
    jQuery(".airmed-wrapper #airmed-apply-coupon").on('click', function(e){
      var that = this;
      var orderid = jQuery(this).attr('data-am-orderid');
      var code = jQuery('#airmed-coupon-code').val();
      
      jQuery(this).closest(".card").find(".am-loading").removeClass('hide');

      jQuery.ajax({
      // need to do wordpress ajax call instead
      url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
      method: "POST",
      data: {
        action: 'airmed_add_coupon',
        orderid: orderid,
        couponcode: code,
      },
      success: function (response) {
        //let item = jQuery('#'+prodid);
        if(response.errno != 200 ){
          // airmed API error
          jQuery(".airmed-wrapper .airmed-checkout .am-loading").addClass('hide');
          alert(response.errno+' - '+response.err+': '+response.response.replace('["','').replace('"]','') );
        }
        else{
          //jQuery(".airmed-wrapper .airmed-checkout .am-loading").addClass('hide');
          //alert("coupon added - success - " + response);
          //sessionStorage.setItem("alert","success");
          window.location.reload(true);
        }
      },
      error: function (response) {
        alert("error");
      },
    });

    });

    // Remove Coupon
    jQuery(".airmed-wrapper #airmed-remove-coupon").on('click', function(e){
      var that = this;
      var orderid = jQuery(this).attr('data-am-orderid');
      
      jQuery(this).closest(".card").find(".am-loading").removeClass('hide');

      jQuery.ajax({
        // need to do wordpress ajax call instead
        url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
        method: "POST",
        data: {
          action: 'airmed_remove_coupon',
          orderid: orderid,
        },
        success: function (response) {
          //let item = jQuery('#'+prodid);
          if(response.errno != 200 ){
            // airmed API error
            jQuery(".airmed-wrapper .airmed-checkout .am-loading").addClass('hide');
            alert(response.errno+' - '+response.err+': '+response.response.replace('["','').replace('"]','') );
          }
          else{
            //jQuery(".airmed-wrapper .airmed-checkout .am-loading").addClass('hide');
            //alert("coupon added - success - " + response);
            //sessionStorage.setItem("alert","success");
            window.location.reload(true);
          }
        },
        error: function (response) {
          alert("error");
        },
      });

    });

    // Enable iframe and payment process
    jQuery(".airmed-wrapper #airmed-payment-form input[type='submit']").on('click', function(e){
      jQuery(".airmed-wrapper .airmed-checkout form~.am-loading").removeClass('hide');

      jQuery(".airmed-wrapper #airmed-payment-iframe").on('load', function(){
        jQuery(".airmed-wrapper .airmed-checkout form~.am-loading").addClass('hide');
        jQuery(".airmed-wrapper #airmed-payment-iframe").removeClass('hide');
      });
      
      // decode base64 response, iframe, or error
      function decodeResponse(response){
        var _r;
        try {  // check if response is JSON
          _r=JSON.parse(response);
        } catch (e) {
          _r = { error: true, message: response };
        }

        try {
          for(var r in _r){
            if(_r[r]) {
              _r[r]=atob(_r[r]);
            }
          }
        } catch (e) { /** */ }
        return _r;
      }
      
      function processPayment(base64data){
        var payData = JSON.parse(base64data);
        jQuery.ajax({
          // need to do wordpress ajax call instead
          url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
          method: "POST",
          data: {
            action: 'airmed_globalpayment_postpayment',
            data: payData,
            orderid: jQuery('#airmed-payment-form').attr('data-am-orderid'),
            patientid: jQuery('#airmed-payment-form').attr('data-am-patientid'),
          },
          success: function (response) {

            if(response.errno == 200 ){
              console.log(`Paymment Process:  ${response.message}`);
              jQuery('.airmed-wrapper .airmed-checkout>.am-loading').removeClass('hide');
              let data = decodeResponse(base64data);
              window.location.href = data.MERCHANT_RESPONSE_URL+'&success=true';
            }
            else{
              console.log(`Payment Process Error: ${response.message}`);
              //alert(response.message);
              showModalError('Payment Process Message','Error: '+response.errno+'<br>'+response.message);
            }
          },
          error: function (response) {
            alert("error");
          },
        });


      }
      
      // verify response SHA1 hash
      function verifyHash(base64data){
        var hashData = decodeResponse(base64data);

        jQuery.ajax({
          // need to do wordpress ajax call instead
          url: airmedajaxmodal.ajaxurl,  // used for old admin-ajax way
          method: "POST",
          data: {
            action: 'airmed_checkout_hash',
            data: hashData,
          },
          success: function (response) {
            //let item = jQuery('#'+prodid);
            if(response.errno == 0 ){
              // airmed API error
              //jQuery(".airmed-wrapper .airmed-checkout .am-loading").addClass('hide');
              //alert(response.errno+' - '+response.message );
              console.log(`Received SHA1 hash: ${response.message}`);

              // not sure what to do here yet.
              // either ajax call or during this hash check to update the order
              // either modal, update page, or direct to order page
              //window.location.reload(true);
              processPayment(base64data);
              //window.location.href = response.returnurl+'&success=true';
            }
            else{
              console.log(`Received SHA1 hash Error: ${response.message}`);
              //alert(response.message);
              showModalError('Payment Process Message','Error: '+response.errno+'<br>'+response.message);
            }
          },
          error: function (response) {
            alert("error");
          },
        });
      }
      
      function realexMessageEvent(e){
        
        if(e.origin == 'https://pay.sandbox.realexpayments.com' || e.origin == 'https://pay.realexpayments.com'){
          if(e.data && (evtdata = decodeResponse(e.data)).iframe){  // iframe resize response
            /* */
            //console.log(`Received IFrame message: ${e.data}, ${e.origin}`);
          }
          else {
            // hide and close frame
            jQuery(".airmed-wrapper #airmed-payment-iframe").addClass('hide');
            jQuery(".airmed-wrapper #airmed-payment-iframe").attr('src','');
           
            var response = decodeResponse(e.data);
            console.log(response);
              
            if(response.error){  // realex error
              console.log(`Received Payment Error: ${response.message}`);
              //alert(response.message);
              showModalError('Payment Process Message',response.message);
            }
            else if(response.RESULT != "00"){  // credit card declined error
              console.log(`Received CC Error: ${response.message}`);
              //alert(response.message);
              showModalError('Payment Process Message','Error: '+response.RESULT+'<br>'+response.MESSAGE);

            }
            else {  // successful response
              console.log(`Received Payment message: ${e.data}, ${e.origin}`);
              console.log(`Now ajax call for hash check`);
              // now do hash check
              verifyHash(e.data);
              //alert('Authorized - do hash check');
            }

          }
        }
        else {
          console.log('Received message: ${e.data}, ${e.origin}');
          //alert('Origin security issue');
          showModalError('Payment Process Message','Error: Origin security issue');
          jQuery(".airmed-wrapper #airmed-payment-iframe").addClass('hide');
          jQuery(".airmed-wrapper #airmed-payment-iframe").src('');
          window.removeEventListener('message', realexMessageEvent);
        }
      }

      window.removeEventListener('message', realexMessageEvent);
      window.addEventListener('message', realexMessageEvent);
    });
    
    
  }

 /*  JS version of image hover over
 $(document).ready(function(){
  let img = $('img'),
  src =  img.attr("src"),
  altSrc = img.attr("data-alt-src");
  
  img.css({
    "width": img.width(),
    "height": img.height(),
    "display": "block",
    "background": "url("+src+")",
    "transition": "all 0.6s ease-in-out"
  })
  img.attr("src", "");
  img.attr("alt", "");
      
 img
   .mouseenter(()=>{
     img.css({
      "background": "url("+altSrc+")",
     })
   })
   .mouseleave(()=>{
    img.css({
      "background": "url("+src+")",
    })
   })
})
 */
 
 
 /*** handling tabs non-bootstrap ***
  if (jQuery("#airmed-wrapper .am-nav-tabs").length){
    //jQuery("#airmed-wrapper .am-nav-tabs .am-nav-link").on("click", function(e){
    jQuery("#airmed-wrapper [data-am-toggle='tab']").on("click", function(e){
      e.preventDefault();
      // handle the links/tabs
      var c = jQuery(this).closest(".am-nav-tabs");
      jQuery(c).find(".am-nav-link.active").removeClass("active show").attr("aria-selected",false);
      var t = jQuery(this).attr('aria-selected',true).addClass("active show").attr("data-target");
      
      // handle the tab panes
      jQuery(t).closest(".am-tab-content").find(".am-tab-pane").removeClass("active show");
      jQuery(t).addClass("active show");
    });
  }
  
  // *** handling dropdowns non-bootstrap ***
  if (jQuery("#airmed-wrapper .dropdown-toggle").length){
  //jQuery("#airmed-wrapper .am-nav-tabs .am-nav-link").on("click", function(e){
    jQuery("#airmed-wrapper [data-am-toggle='dropdown']").on("click", function(e){
      e.preventDefault();
      // handle the button
      function hideDropdowns(){
        jQuery("[data-am-toggle='dropdown'].show").removeClass("show").attr("aria-expanded",false);
        jQuery(".dropdown-menu.show").removeClass("show");
      }
      if (jQuery(this).hasClass("show")){
        jQuery(this).removeClass("show").attr("aria-expanded",false);
        jQuery(this).closest(".dropdown").find(".dropdown-menu").removeClass("show");
        jQuery(this).off("focusout");
      }
      else {
        hideDropdowns();
        jQuery(this).addClass("show").attr("aria-expanded",true);;
        jQuery(this).closest(".dropdown").find(".dropdown-menu").addClass("show");
        jQuery(this).focus();
        e.stopPropagation();
        jQuery(document).one("click", function(){
          hideDropdowns();
        });
      }
      
    });
  }
  
  // *** mobile menu collapses non-bootstrap ***
  if (jQuery("#airmed-wrapper .navbar-toggler").length){
    jQuery("#airmed-wrapper [data-am-toggle='collapse']").on("click", function(e){
      e.preventDefault();

      if (jQuery(this).hasClass("collapsed")){
        jQuery("#airmed-wrapper [data-am-toggle='collapse']:not('.collapsed')").each(function(i){
          let id = jQuery(this).addClass("collapsed").attr("aria-expanded",false).attr("data-target");
          jQuery(id).removeClass("show");
        });
        let id = jQuery(this).removeClass("collapsed").attr("aria-expanded",true).attr("data-target");
        jQuery(id).addClass("show");
      }
      else {
        let id = jQuery(this).addClass("collapsed").attr("aria-expanded",false).attr("data-target");
        jQuery(id).removeClass("show");
      }
      
    });
  }
 */

});

