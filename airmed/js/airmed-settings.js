// plugin admin area
jQuery(document).ready( function(){
  //jQuery("#airmed_themes .image-cell img").addClass("d-none");
  jQuery("#airmed-wrapper .airmed-themes select").each(function(){
    var val = jQuery("option:selected",this).attr("value");
    var p = jQuery(this).closest(".row");
    //var o = p.attr("data-option");
    //jQuery("img[data-image='"+val+"']",p).removeClass("d-none");
    jQuery("img[data-image='"+val+"']",p).addClass("d-block");
  });
  jQuery("#airmed-wrapper .airmed-themes select").on("change",function(){
    var val = jQuery("option:selected",this).attr("value");
    var p = jQuery(this).closest(".row");
    jQuery("img",p).removeClass("d-block"); 
    jQuery("img[data-image='"+val+"']",p).addClass("d-block");
  });
});