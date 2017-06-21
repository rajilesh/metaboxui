<?php






if ( ! function_exists( 'ot_type_rj_upload_media' ) ) {

  

  function ot_type_rj_upload_media( $args = array() ) {

    

    /* turns arguments array into variables */

    extract( $args );

    

    /* verify a description */

    $has_desc = $field_desc ? true : false;

    

    /* If an attachment ID is stored here fetch its URL and replace the value */

    if ( $field_value && wp_attachment_is_image( $field_value ) ) {

    

      $attachment_data = wp_get_attachment_image_src( $field_value, 'original' );

      

      /* check for attachment data */

      if ( $attachment_data ) {

      

        $field_src = $attachment_data[0];

    

        

      }

      

    }

    /* format setting outer wrapper */

    echo '<div class="format-setting type-upload ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      

      /* description */

      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      

      /* format setting inner wrapper */

      echo '<div class="format-setting-inner">';

      

        /* build upload */

        echo '<div class="option-tree-ui-upload-parent">';

          

    $field_value = str_replace(base_url(),'[base_url]',$field_value);

          /* input */

          echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-upload-input ' . esc_attr( $field_class ) . '" />';

          

          /* add media button */

          echo '<a href="javascript:void(0);" class="ot_upload_media option-tree-ui-button button button-primary light" rel="' . $post_id . '" title="' . __( 'Add Media', 'option-tree' ) . '"><span class="icon ot-icon-plus-circle"></span>' . __( 'Add Media', 'option-tree' ) . '</a>';

        

        echo '</div>';

        

        /* media */

        if ( $field_value ) {

            

          echo '<div class="option-tree-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';

            

            /* replace image src */

            if ( isset( $field_src ) )

              $field_value = $field_src;

              

            if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value ) )

              echo '<div class="option-tree-ui-image-wrap"><img src="' . esc_url( do_shortcode($field_value) ) . '" alt="" /></div>';

            

            echo '<a href="javascript:(void);" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' . __( 'Remove Media', 'option-tree' ) . '"><span class="icon ot-icon-minus-circle"></span>' . __( 'Remove Media', 'option-tree' ) . '</a>';

            

          echo '</div>';

          

        }

        

      echo '</div>';

    

    echo '</div>';

    

  }

  

}



/* End of file ot-functions-option-types.php */

/* Location: ./includes/ot-functions-option-types.php */







if ( ! function_exists( 'ot_type_rj_upload_attach_id' ) ) {

  

  function ot_type_rj_upload_attach_id( $args = array() ) {

    

    /* turns arguments array into variables */

    extract( $args );

    

    /* verify a description */

    $has_desc = $field_desc ? true : false;

    

    /* If an attachment ID is stored here fetch its URL and replace the value */

    if ( $field_value && wp_attachment_is_image( $field_value ) ) {

    

      $attachment_data = wp_get_attachment_image_src( $field_value, 'original' );

      

      /* check for attachment data */

      if ( $attachment_data ) {

      

        $field_src = $attachment_data[0];

    

        

      }

      

    }

    /* format setting outer wrapper */

    echo '<div class="format-setting type-upload ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';

      

      /* description */

      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';

      

      /* format setting inner wrapper */

      echo '<div class="format-setting-inner">';

      

        /* build upload */

        echo '<div class="option-tree-ui-upload-parent">';

          

    $field_value = str_replace(base_url(),'[base_url]',$field_value);

          /* input */

          echo '<input type="text" name="' . esc_attr( $field_name ) . '" id="' . esc_attr( $field_id ) . '" value="' . esc_attr( $field_value ) . '" class="widefat option-tree-ui-upload-input ' . esc_attr( $field_class ) . '" />';

          

          /* add media button */

          echo '<a href="javascript:void(0);" class="rj_ot_upload_media option-tree-ui-button button button-primary light" rel="' . $post_id . '" title="' . __( 'Add Media', 'option-tree' ) . '"><span class="icon ot-icon-plus-circle"></span>' . __( 'Add Media', 'option-tree' ) . '</a>';

        

        echo '</div>';

        

        /* media */

        if ( $field_value ) {

            

          echo '<div class="option-tree-ui-media-wrap" id="' . esc_attr( $field_id ) . '_media">';

            

            /* replace image src */

            if ( isset( $field_src ) )

              $field_value = $field_src;

              

            if ( preg_match( '/\.(?:jpe?g|png|gif|ico)$/i', $field_value ) )

              echo '<div class="option-tree-ui-image-wrap"><img src="' . esc_url( do_shortcode($field_value) ) . '" alt="" /></div>';

            

            echo '<a href="javascript:(void);" class="option-tree-ui-remove-media option-tree-ui-button button button-secondary light" title="' . __( 'Remove Media', 'option-tree' ) . '"><span class="icon ot-icon-minus-circle"></span>' . __( 'Remove Media', 'option-tree' ) . '</a>';

            

          echo '</div>';

          

        }

        

      echo '</div>';

    echo '</div>';

    

  }

  

}


/**
 * Button option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_rj_button' ) ) {
  
  function ot_type_rj_button( $args = array() ) {
    
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
    
    /* format setting outer wrapper */
    echo '<div class="format-setting type-button ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '">';
      
      /* description */
      echo $has_desc ? '<div class="description">' . htmlspecialchars_decode( $field_desc ) . '</div>' : '';
      
      /* format setting inner wrapper */
      echo '<div class="format-setting-inner">';
      
        echo '<div class="option-tree-ui-measurement-input-wrap">';
        
          echo '<input type="text" name="' . esc_attr( $field_name ) . '[0]" id="' . esc_attr( $field_id ) . '-0" value="' . ( isset( $field_value[0] ) ? esc_attr( $field_value[0] ) : '' ) . '" class=" option-tree-ui-input inline_rj_input' . esc_attr( $field_class ) . '" placeholder="Label" />';
        
        
       echo '<input type="text" name="' . esc_attr( $field_name ) . '[1]" id="' . esc_attr( $field_id ) . '-1" value="' . ( isset( $field_value[1] ) ? esc_attr( $field_value[1] ) : '' ) . '" class=" option-tree-ui-input inline_rj_input '  . esc_attr( $field_class ) . '" placeholder="Link" />';
        
      echo '</div>';
      
      
         
       
      
      echo '</div>';
    
    echo '</div>';
    
  }
  
}



/**
 * Location option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
$is_rj_google_map_loded = false;
if ( ! function_exists( 'ot_type_rj_location' ) ) {
  
  function ot_type_rj_location( $args = array() ) {
    global $is_rj_google_map_loded;
    /* turns arguments array into variables */
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
      
      if($is_rj_google_map_loded==false){
      echo ' <script src="https://maps.googleapis.com/maps/api/js?v=3.24&signed_in=true&libraries=places"></script>';
      //echo ' <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDIJ9XX2ZvRKCJcFRrl-lRanEtFUow4piM&libraries=places&callback=initAutocomplete"></script>';
          $is_rj_google_map_loded=true;
      }
      $unique_id = esc_attr( $field_name ) . '_0_';
     
    
    /* format setting outer wrapper */
    echo '<div class="format-setting type-location ' . ( $has_desc ? 'has-desc' : 'no-desc' ) . '" id="' . esc_attr( $field_name ) . '_0_wrap">';
      
     echo ' <div id="' . esc_attr( $field_name ) . '_0_locationField">
      <input id="' . esc_attr( $field_name ) . '_0" placeholder="Enter a location"
             onBlur="'.$unique_id.'geolocate()" type="text" autocomplete="off" name="' . esc_attr( $field_name ) . '[0]" value="' . ( isset( $field_value[0] ) ? esc_attr( $field_value[0] ) : '' ) . '" />
    </div>

   <table id="' . esc_attr( $field_name ) . '_0_address">
      <tr>
        <td class="label">Cordinates</td>
        <td class="slimField"><input class="field latitude" name="' . esc_attr( $field_name ) . '[1]" value="' . ( isset( $field_value[1] ) ? esc_attr( $field_value[1] ) : '' ) . '" /></td>
        <td class="wideField" colspan="2"><input class="field longitude" name="' . esc_attr( $field_name ) . '[2]" value="' . ( isset( $field_value[2] ) ? esc_attr( $field_value[2] ) : '' ) . '" /></td>
      </tr>
      <tr>
        <td class="label">Street address</td>
        <td class="slimField"><input class="field street_number" name="' . esc_attr( $field_name ) . '[3]" value="' . ( isset( $field_value[3] ) ? esc_attr( $field_value[3] ) : '' ) . '" /></td>
        <td class="wideField" colspan="2"><input class="field route"  name="' . esc_attr( $field_name ) . '[4]" value="' . ( isset( $field_value[4] ) ? esc_attr( $field_value[4] ) : '' ) . '" /></td>
      </tr>
      <tr>
        <td class="label">City</td>
        <td class="wideField" colspan="3"><input class="field locality" name="' . esc_attr( $field_name ) . '[5]" value="' . ( isset( $field_value[5] ) ? esc_attr( $field_value[5] ) : '' ) . '" /></td>
      </tr>
      <tr>
        <td class="label">State</td>
        <td class="slimField"><input class="field administrative_area_level_1" name="' . esc_attr( $field_name ) . '[6]" value="' . ( isset( $field_value[6] ) ? esc_attr( $field_value[6] ) : '' ) . '" /></td>
        <td class="label">Zip code</td>
        <td class="wideField"><input class="field postal_code"  name="' . esc_attr( $field_name ) . '[7]" value="' . ( isset( $field_value[7] ) ? esc_attr( $field_value[7] ) : '' ) . '"></td>
      </tr>
      <tr>
        <td class="label">Country</td>
        <td class="wideField" colspan="3"><input class="field country" name="' . esc_attr( $field_name ) . '[8]" value="' . ( isset( $field_value[8] ) ? esc_attr( $field_value[8] ) : '' ) . '" /></td>
      </tr>
    </table>';
        
      echo '</div>';
      
      echo '<div id="'.$unique_id.'map_canvas" class="rj_map_option_type" style="width:100%; height:350px;"></div>';
      
       ?>
       <script>
 
           // This example displays an address form, using the autocomplete feature
// of the Google Places API to help users fill in the information.

var <?php echo $unique_id ?>placeSearch, <?php echo $unique_id ?>autocomplete;
var <?php echo $unique_id ?>componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};
           
var <?php echo $unique_id ?>geocoder;
           
           
           
           var <?php echo $unique_id ?>lat = "<?php echo esc_attr( $field_value[1]); ?>";
      var <?php echo $unique_id ?>long = "<?php echo esc_attr( $field_value[2] ); ?>";
            if (navigator.geolocation && <?php echo $unique_id ?>lat=="" && <?php echo $unique_id ?>long=="") {
    navigator.geolocation.getCurrentPosition(function(position) {
         <?php echo $unique_id ?>lat= position.coords.latitude;
         <?php echo $unique_id ?>long= position.coords.longitude;
    });
                
            }
           <?php echo $unique_id ?>geocoder = new google.maps.Geocoder();
           var <?php echo $unique_id ?>map = new google.maps.Map(document.getElementById('<?php echo $unique_id ?>map_canvas'), {
    zoom: 8,
    center: new google.maps.LatLng(<?php echo $unique_id ?>lat, <?php echo $unique_id ?>long),
    mapTypeId: google.maps.MapTypeId.ROADMAP
});

var <?php echo $unique_id ?>myMarker = new google.maps.Marker({
    position: new google.maps.LatLng(<?php echo $unique_id ?>lat, <?php echo $unique_id ?>long),
    draggable: true
});

google.maps.event.addListener(<?php echo $unique_id ?>myMarker, 'dragend', function (evt) {
   <?php echo $unique_id ?>geocodePosition(<?php echo $unique_id ?>myMarker.getPosition());
});

google.maps.event.addListener(<?php echo $unique_id ?>myMarker, 'dragstart', function (evt) {
});
           
function <?php echo $unique_id ?>geocodePosition(pos) {
  <?php echo $unique_id ?>geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
        document.querySelector('#<?php echo esc_attr( $field_name ) . '_0'; ?>').value =  responses[0].formatted_address;
        <?php echo $unique_id ?>fillInAddress_map(responses);
    } else {
    }
  });
}       

<?php echo $unique_id ?>map.setCenter(<?php echo $unique_id ?>myMarker.position);
<?php echo $unique_id ?>myMarker.setMap(<?php echo $unique_id ?>map);
           
 // [START region_fillform]
function <?php echo $unique_id ?>fillInAddress_map(res) {

    
  var place = res[0];

  for (var component in <?php echo $unique_id ?>componentForm) {
   document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .'+ component).value = '';
  document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .'+ component).disabled = false;
  }
  // Get each component of the address from the place details
  // and fill the corresponding field on the form.

    var <?php echo esc_attr( $field_name ) . '_0_address'; ?>_latitude = place.geometry.location.lat();
    var <?php echo esc_attr( $field_name ) . '_0_address'; ?>_longitude = place.geometry.location.lng();
   document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .latitude').value =<?php echo esc_attr( $field_name ) . '_0_address'; ?>_latitude;
   document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .longitude').value =<?php echo esc_attr( $field_name ) . '_0_address'; ?>_longitude;
    
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (<?php echo $unique_id ?>componentForm[addressType]) {
      var val = place.address_components[i][<?php echo $unique_id ?>componentForm[addressType]];
      document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .'+ addressType).value = val;
    }
  }
}
// [END region_fillform]          
           
           
           


function <?php echo $unique_id ?>initialize() {
  // Create the autocomplete object, restricting the search
  // to geographical location types.
  <?php echo $unique_id ?>autocomplete = new google.maps.places.Autocomplete(
      /** @type {HTMLInputElement} */(document.getElementById('<?php echo  esc_attr( $field_name ) . '_0'; ?>')),
      { types: ['geocode'] });
      
  // When the user selects an address from the dropdown,
  // populate the address fields in the form.
  /*
  google.maps.event.addListener(<?php echo $unique_id ?>autocomplete, 'place_changed', function() {
    <?php echo $unique_id ?>fillInAddress();
  });
  */
  
  <?php echo $unique_id ?>autocomplete.addListener('place_changed', <?php echo $unique_id ?>fillInAddress);
}

// [START region_fillform]
function <?php echo $unique_id ?>fillInAddress() {
  // Get the place details from the autocomplete object.
  var place = <?php echo $unique_id ?>autocomplete.getPlace();
 
   

  for (var component in <?php echo $unique_id ?>componentForm) {
   document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .'+ component).value = '';
  document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .'+ component).disabled = false;
  }
  // Get each component of the address from the place details
  // and fill the corresponding field on the form.

    var <?php echo esc_attr( $field_name ) . '_0_address'; ?>_latitude = place.geometry.location.lat();
    var <?php echo esc_attr( $field_name ) . '_0_address'; ?>_longitude = place.geometry.location.lng();
    document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .latitude').value =<?php echo esc_attr( $field_name ) . '_0_address'; ?>_latitude;
    document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .longitude').value =<?php echo esc_attr( $field_name ) . '_0_address'; ?>_longitude;
    
     var <?php echo $unique_id ?>geolocation2 = new google.maps.LatLng(<?php echo esc_attr( $field_name ) . '_0_address'; ?>_latitude,<?php echo esc_attr( $field_name ) . '_0_address'; ?>_longitude);
     <?php echo $unique_id ?>myMarker.setPosition(<?php echo $unique_id ?>geolocation2);
     <?php echo $unique_id ?>map.setCenter(<?php echo $unique_id ?>geolocation2);
<?php echo $unique_id ?>myMarker.setMap(<?php echo $unique_id ?>map);
    
  for (var i = 0; i < place.address_components.length; i++) {
    var addressType = place.address_components[i].types[0];
    if (<?php echo $unique_id ?>componentForm[addressType]) {
      var val = place.address_components[i][<?php echo $unique_id ?>componentForm[addressType]];
      document.querySelector('#<?php echo esc_attr( $field_name ) . '_0_address'; ?> .'+ addressType).value = val;
    }
  }
}
// [END region_fillform]

// [START region_geolocation]
// Bias the autocomplete object to the user's geographical location,
// as supplied by the browser's 'navigator.geolocation' object.
function <?php echo $unique_id ?>geolocate() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var <?php echo $unique_id ?>geolocation = new google.maps.LatLng(
          position.coords.latitude, position.coords.longitude);
      var <?php echo $unique_id ?>circle = new google.maps.Circle({
        center: <?php echo $unique_id ?>geolocation,
        radius: position.coords.accuracy
      });
      <?php echo $unique_id ?>autocomplete.setBounds(<?php echo $unique_id ?>circle.getBounds());
        
       
    });
  }
}
// [END region_geolocation]
document.querySelector('body').onload = <?php echo $unique_id ?>initialize();
           
           
           
    </script>
      <?php
         
       
      
    
  }
  
}



/**
 * Shortcode option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
$shortcode_args = array();
if ( ! function_exists( 'ot_type_rj_shortcode' ) ) {
  
  function ot_type_rj_shortcode( $args = array() ) {
    /* turns arguments array into variables */
      global $shortcode_args;
      $shortcode_args = $args;
    extract( $args );
    
    /* verify a description */
    $has_desc = $field_desc ? true : false;
      echo '<table id="' . esc_attr( $field_name ) . '">
      <tr>
        <td class="label">Shortcode</td>
        <td class="slimField"><input class="field ' . esc_attr( $field_name ) . '" name="' . esc_attr( $field_name ) . '" value="' . ( isset( $field_value ) ? esc_attr( $field_value ) : '' ) . '" />
        
        
        ';
      
      echo $has_desc ? ' ' . htmlspecialchars_decode( $field_desc ) . ' ' : '';
echo '        </td>
      </tr>
      </table>';
      
      echo do_shortcode($field_value);
  }
}
if ( ! function_exists( 'ot_type_rj_users' ) ) {
  
  function ot_type_rj_users( $args = array() ) {
   
    /* turns arguments array into variables */
    extract( $args );
    $multiple = ($args['field_rows']=='multiple') ? 'multiple' : '';
    //wp_enqueue_script( 'select3', plugin_dir_url(__FILE__) . '../js/select2.js' );
   // wp_enqueue_style( 'select3', plugin_dir_url(__FILE__) . '../css/select2.css', false, OT_VERSION );
     //wp_add_inline_script( 'select3', 'jQuery(document).ready(function(){jQuery( \'#users_'.esc_attr( $field_name ).'\' ).select3();});' );
  $user_args = array('fields'=>array('ID','user_login','display_name'));
  
  $user_args = apply_filters('rj_ot_user_args',$user_args,$args);
  $allusers = get_users( $user_args );
  
  $options ='';
  
  $val = ( (isset( $field_value ) && $field_value !='') ? ( $field_value ) : (($multiple !='') ? array() : array()) );
$val = ($multiple !='' && !is_array($val)) ? array($val) : $val;
  if(!empty($allusers)){
    foreach($allusers as $alluser){
      if($multiple !='' && in_array($alluser->ID,$val)){
        $selected = " selected ";
      }else if($val==$alluser->ID){
        $selected = " selected ";
      }else{
        $selected = "";
      }
      $user_option_value_label_text = ucwords($alluser->display_name)." - (".($alluser->user_login).")";
      //Now user can change user select field value label using users_option_value_label filter.
      $users_option_value_label = apply_filters('users_option_value_label',$user_option_value_label_text,$alluser);
      
      $options .="<option value='".$alluser->ID."' ".$selected." >".$users_option_value_label."</option>";
    }
  }
    /* verify a description */
    $has_desc = $field_desc ? true : false;
      echo '<table id="' . esc_attr( $field_name ) . '" class="tax_meta_fields user_meta_field">
      <tr>
        <td class="slimField">
    <select name="' . esc_attr( $field_name ) .(($multiple !='') ? '[]' : ''). '" '.$multiple.' id="users_'.esc_attr( $field_name ).'">
    <option value="">Choose a user</option>
    '.$options.'
    </select>
        
       
        ';
      
      echo $has_desc ? ' ' . htmlspecialchars_decode( $field_desc ) . ' ' : '';
echo '        </td>
      </tr>
      </table>';
  }
}
if ( ! function_exists( 'ot_type_rj_users_checkbox' ) ) {
  
  function ot_type_rj_users_checkbox( $args = array() ) {
   
    /* turns arguments array into variables */
    extract( $args );
    $multiple = ($args['field_rows']=='multiple') ? 'multiple' : '';
    //wp_enqueue_script( 'select3', plugin_dir_url(__FILE__) . '../js/select2.js' );
   // wp_enqueue_style( 'select3', plugin_dir_url(__FILE__) . '../css/select2.css', false, OT_VERSION );
     //wp_add_inline_script( 'select3', 'jQuery(document).ready(function(){jQuery( \'#users_'.esc_attr( $field_name ).'\' ).select3();});' );
  $user_args = array('fields'=>array('ID','user_login','display_name'));
  
  $user_args = apply_filters('rj_ot_user_checkbox_args',$user_args,$args);
  $allusers = get_users( $user_args );
  
  $options ='';
  
  $val = ( (isset( $field_value ) && $field_value !='') ? ( $field_value ) : (($multiple !='') ? array() : array()) );
$val = ($multiple !='' && !is_array($val)) ? array($val) : $val;
  if(!empty($allusers)){
    $field_name = esc_attr( $field_name ) . (($multiple !='') ? '[]' : '');
    foreach($allusers as $alluser){
      if($multiple !='' && in_array($alluser->ID,$val)){
        $selected = " checked='checked' ";
      }else if($val==$alluser->ID){
        $selected = " checked='checked' ";
      }else{
        $selected = "";
      }
      $user_option_value_label_text = ucwords($alluser->display_name)." - (".($alluser->user_login).")";
      //Now user can change user select field value label using users_option_value_label filter.
      $users_option_value_label = apply_filters('users_option_value_label',$user_option_value_label_text,$alluser);
      
      $options .="<p><input type='checkbox' name='".$field_name."' value='".$alluser->ID."' ".$selected." /><label>".$users_option_value_label." </label></p>";
    }
  }
    /* verify a description */
    $has_desc = $field_desc ? true : false;
      echo '<table id="' . esc_attr( $field_name ) . '" class="tax_meta_fields user_meta_field">
      <tr>
        <td class="slimField ">
        <div class="users_checkbox_field">
    '.$options.'</div>';
      
      echo $has_desc ? ' ' . htmlspecialchars_decode( $field_desc ) . ' ' : '';
echo '        </td>
      </tr>
      </table>';
  }
}

if ( ! function_exists( 'ot_type_rj_users_radio' ) ) {
  
  function ot_type_rj_users_radio( $args = array() ) {
   
    /* turns arguments array into variables */
    extract( $args );
    $multiple = ($args['field_rows']=='multiple') ? 'multiple' : '';
    //wp_enqueue_script( 'select3', plugin_dir_url(__FILE__) . '../js/select2.js' );
   // wp_enqueue_style( 'select3', plugin_dir_url(__FILE__) . '../css/select2.css', false, OT_VERSION );
     //wp_add_inline_script( 'select3', 'jQuery(document).ready(function(){jQuery( \'#users_'.esc_attr( $field_name ).'\' ).select3();});' );
  $user_args = array('fields'=>array('ID','user_login','display_name'));
  
  $user_args = apply_filters('rj_ot_user_radio_args',$user_args,$args);
  $allusers = get_users( $user_args );
  
  $options ='';
  
  $val = ( (isset( $field_value ) && $field_value !='') ? ( $field_value ) : (($multiple !='') ? array() : array()) );
$val = ($multiple !='' && !is_array($val)) ? array($val) : $val;
  if(!empty($allusers)){
    $field_name = esc_attr( $field_name ) . (($multiple !='') ? '[]' : '');
    foreach($allusers as $alluser){
      if($multiple !='' && in_array($alluser->ID,$val)){
        $selected = " checked='checked' ";
      }else if($val==$alluser->ID){
        $selected = " checked='checked' ";
      }else{
        $selected = "";
      }
      $user_option_value_label_text = ucwords($alluser->display_name)." - (".($alluser->user_login).")";
      //Now user can change user select field value label using users_option_value_label filter.
      $users_option_value_label = apply_filters('users_option_value_label',$user_option_value_label_text,$alluser);
      
      $options .="<p><input type='checkbox' name='".$field_name."' value='".$alluser->ID."' ".$selected." /><label>".$users_option_value_label." </label></p>";
    }
  }
    /* verify a description */
    $has_desc = $field_desc ? true : false;
      echo '<table id="' . esc_attr( $field_name ) . '" class="tax_meta_fields user_meta_field">
      <tr>
        <td class="slimField ">
        <div class="users_checkbox_field">
    '.$options.'</div>';
      
      echo $has_desc ? ' ' . htmlspecialchars_decode( $field_desc ) . ' ' : '';
echo '        </td>
      </tr>
      </table>';
  }
}


function custom_field_args($args){
  $custom_field = $args['field_std'];
    $page_id = $args['post_id'];
    $options = get_post_meta($page_id,$custom_field,true);
        $options_array = explode('|',$options);
        $args['field_choices'] = array();
        if ( is_array( $options_array ) && ! empty( $options_array ) ) {
          foreach( $options_array as $options_a ) {
            $values = explode(':',$options_a);
            $value = ($values[0]) ? $values[0] : $values[0];
            $label = ($values[1]) ? $values[1] : $value;
            $src = ($values[2]) ? $values[2] : '';
            $args['field_choices'][] = array(
                'label'=>$label,
                'value'=>$value,
                'src'=>$src,
              );
          }
        }
        $args = apply_filters('rj_ot_args_modifier',$args,$args['field_id']);
        return $args;
        
}


/**
 * Custom Field Select option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_rj_custom_field_select' ) ) {
  
  function ot_type_rj_custom_field_select( $args = array() ) {
   
    $args = custom_field_args($args);
    ot_type_select($args);
    
  }
  
}


/**
 * Custom Field Checkbox option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_rj_custom_field_checkbox' ) ) {
  
  function ot_type_rj_custom_field_checkbox( $args = array() ) {
    
    $args = custom_field_args($args);
    ot_type_checkbox($args);
    
  }
  
}

/**
 * Custom Field Radio option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_rj_custom_field_radio' ) ) {
  
  function ot_type_rj_custom_field_radio( $args = array() ) {
    
    $args = custom_field_args($args);
    ot_type_radio($args);
    
  }
  
}

/**
 * Custom Field Text option type.
 *
 * See @ot_display_by_type to see the full list of available arguments.
 *
 * @param     array     An array of arguments.
 * @return    string
 *
 * @access    public
 * @since     2.0
 */
if ( ! function_exists( 'ot_type_rj_custom_field_text' ) ) {
  
  function ot_type_rj_custom_field_text( $args = array() ) {
    $custom_field = $args['field_std'];
    $page_id = $args['post_id'];
    $value = get_post_meta($page_id,$custom_field,true);
    $args['field_name'] = $custom_field;
    $args['field_value'] = $value;
    ot_type_text($args);
    
  }
  
}

// add new files to filter
function rj_add_custom_option_types( $types ) {



  $types['rj_upload_media'] = 'Attachment URL';

  $types['rj_upload_attach_id'] = 'Attachment ID';

  $types['rj_button'] = 'Button';

  $types['rj_location'] = 'Map';

  $types['rj_shortcode'] = 'Shortcode';
  
  $types['rj_users'] = 'Users';
  
  $types['rj_users_checkbox'] = 'Users Checkbox';

  $types['rj_users_radio'] = 'Users Radio';
  
  $types['rj_custom_field_select'] = 'Custom Field Select';
  
  $types['rj_custom_field_checkbox'] = 'Custom Field Checkbox';
  
  $types['rj_custom_field_radio'] = 'Custom Field Radio';
  
  $types['rj_custom_field_text'] = 'Custom Field Text';



  return $types;



}

add_filter( 'ot_option_types_array', 'rj_add_custom_option_types' );