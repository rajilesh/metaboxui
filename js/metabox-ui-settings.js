(function ($) {   
    
     setTimeout(function(){
    $('.option-tree-section-add').text('Add New Metabox');
     },200); 
     setTimeout(function(){
    $('.option-tree-section-add').text('Add New Metabox');
     },1000); 
   $(document).on('click', '.option-tree-section-add', function() {
    hide_divs(100);
    hide_divs(500);
    hide_divs(1000);
    hide_divs(5000);
       
      
   });
     $(document).on('change', '.post_type_section_field select', function() {
     
        var post_type_selected = $( this).val() || [];
         if(jQuery.inArray("page", post_type_selected) !== -1){
             $(this).parents('.post_type_section_field').siblings('.template_page_section_field').show();
         }else{
             $(this).parents('.post_type_section_field').siblings('.template_page_section_field').hide();
         }
         
     });
    
    function hide_divs(time){
        
         setTimeout(function(){
       //  $('.post_type_section_field').hide();
         $('.template_page_section_field').hide();
         $('.taxonomies_section_field').hide();
         $('.widget_template_section_field').hide();
         
        
     },time)
    }
    
    // Return an array of the selected opion values
// select is an HTML select element
function getSelectValues(select) {
  var result = [];
  var options = select && select.options;
  var opt;

  for (var i=0, iLen=options.length; i<iLen; i++) {
    opt = options[i];

    if (opt.selected) {
      result.push(opt.value || opt.text);
    }
  }
  return result;
}

})(jQuery); 