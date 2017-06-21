<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	
include_once( 'rj-ot-meta-box-api.php' );	

add_action( 'admin_init', 'rj_user_ot_save_settings', 6 );




function rj_user_ot_save_settings(){

	if(isset($_REQUEST['page']) && $_REQUEST['page']=='rj-ot-user_metabox'){

	  rj_ot_admin_scriptss();

	  rj_ot_admin_styless();
    wp_enqueue_script( 'rj_ot_user_script', plugin_dir_url(dirname(__FILE__) ) . 'js/user-ot-metabox-ui-settings.js' );

	  

}

	  

	



    /* check and verify import settings nonce */

    if ( isset( $_POST['option_tree_settings_nonce'] ) && wp_verify_nonce( $_POST['option_tree_settings_nonce'], 'rj_option_tree_settings_form' ) && isset($_GET['page']) && $_GET['page']=='rj-ot-user_metabox' ) {



      /* settings value */

      $settings = isset( $_POST[ot_settings_id()] ) ? $_POST[ot_settings_id()] : '';

      

      /* validate sections */

      if ( isset( $settings['sections'] ) ) {

        

        /* fix numeric keys since drag & drop will change them */

        $settings['sections'] = array_values( $settings['sections'] );

        

        /* loop through sections */

        foreach( $settings['sections'] as $k => $section ) {

          

          /* remove from array if missing values */

          if ( ( ! isset( $section['title'] ) && ! isset( $section['id'] ) ) || ( '' == $section['title'] && '' == $section['id'] ) ) {

          

            unset( $settings['sections'][$k] );

            

          } else {

            

            /* validate label */

            if ( '' != $section['title'] ) {

            

             $settings['sections'][$k]['title'] = wp_kses_post( $section['title'] );

              

            }

            

            /* missing title set to unfiltered ID */

            if ( ! isset( $section['title'] ) || '' == $section['title'] ) {

              

              $settings['sections'][$k]['title'] = wp_kses_post( $section['id'] );

            

            /* missing ID set to title */ 

            } else if ( ! isset( $section['id'] ) || '' == $section['id'] ) {

              

              $section['id'] = wp_kses_post( $section['title'] );

              

            }

            

            /* sanitize ID once everything has been checked first */

            $settings['sections'][$k]['id'] = ot_sanitize_option_id( wp_kses_post( $section['id'] ) );

            

          }

          

        }

        

        $settings['sections'] = ot_stripslashes( $settings['sections'] );

      

      }

      

      /* validate settings by looping over array as many times as it takes */

      if ( isset( $settings['settings'] ) ) {

        

        $settings['settings'] = ot_validate_settings_array( $settings['settings'] );

        

      }

      

      /* validate contextual_help */

      if ( isset( $settings['contextual_help']['content'] ) ) {

        

        /* fix numeric keys since drag & drop will change them */

        $settings['contextual_help']['content'] = array_values( $settings['contextual_help']['content'] );

        

        /* loop through content */

        foreach( $settings['contextual_help']['content'] as $k => $content ) {

          

          /* remove from array if missing values */

          if ( ( ! isset( $content['title'] ) && ! isset( $content['id'] ) ) || ( '' == $content['title'] && '' == $content['id'] ) ) {

          

            unset( $settings['contextual_help']['content'][$k] );

            

          } else {

            

            /* validate label */

            if ( '' != $content['title'] ) {

            

             $settings['contextual_help']['content'][$k]['title'] = wp_kses_post( $content['title'] );

              

            }

          

            /* missing title set to unfiltered ID */

            if ( ! isset( $content['title'] ) || '' == $content['title'] ) {

              

              $settings['contextual_help']['content'][$k]['title'] = wp_kses_post( $content['id'] );

            

            /* missing ID set to title */ 

            } else if ( ! isset( $content['id'] ) || '' == $content['id'] ) {

              

              $content['id'] = wp_kses_post( $content['title'] );

              

            }

            

            /* sanitize ID once everything has been checked first */

            $settings['contextual_help']['content'][$k]['id'] = ot_sanitize_option_id( wp_kses_post( $content['id'] ) );

            

          }

          

          /* validate textarea description */

          if ( isset( $content['content'] ) ) {

          

            $settings['contextual_help']['content'][$k]['content'] = wp_kses_post( $content['content'] );

            

          }

          

        }

      

      }

      

      /* validate contextual_help sidebar */

      if ( isset( $settings['contextual_help']['sidebar'] ) ) {

      

        $settings['contextual_help']['sidebar'] = wp_kses_post( $settings['contextual_help']['sidebar'] );

        

      }

      

      $settings['contextual_help'] = ot_stripslashes( $settings['contextual_help'] );

      

      /* default message */

      $message = 'failed';

      

      /* is array: save & show success message */

      if ( is_array( $settings ) ) {

        

        /* WPML unregister ID's that have been removed */

        if ( function_exists( 'icl_unregister_string' ) ) {

          

          $current = get_option( ot_settings_id() );

          $options = get_option( ot_options_id() );

          

          if ( isset( $current['settings'] ) ) {

            

            /* Empty ID array */

            $new_ids = array();

            

            /* Build the WPML IDs array */

            foreach( $settings['settings'] as $setting ) {

            

              if ( $setting['id'] ) {

                

                $new_ids[] = $setting['id'];



              }

              

            }

            

            /* Remove missing IDs from WPML */

            foreach( $current['settings'] as $current_setting ) {

              

              if ( ! in_array( $current_setting['id'], $new_ids ) ) {

              

                if ( ! empty( $options[$current_setting['id']] ) && in_array( $current_setting['type'], array( 'list-item', 'slider' ) ) ) {

                  

                  foreach( $options[$current_setting['id']] as $key => $value ) {

          

                    foreach( $value as $ckey => $cvalue ) {

                      

                      ot_wpml_unregister_string( $current_setting['id'] . '_' . $ckey . '_' . $key );

                      

                    }

                  

                  }

                

                } else if ( ! empty( $options[$current_setting['id']] ) && $current_setting['type'] == 'social-icons' ) {

                  

                  foreach( $options[$current_setting['id']] as $key => $value ) {

          

                    foreach( $value as $ckey => $cvalue ) {

                      

                      ot_wpml_unregister_string( $current_setting['id'] . '_' . $ckey . '_' . $key );

                      

                    }

                  

                  }

                  

                } else {

                

                  ot_wpml_unregister_string( $current_setting['id'] );

                  

                }

              

              }

              

            }



          }

          

        }

		

     

       update_option( 'rj_user_'.ot_settings_id(), $settings );

        $message = 'success';

        

      }

      

      /* redirect */

      wp_redirect( add_query_arg( array( 'action' => 'save-settings', 'message' => $message ), $_POST['_wp_http_referer'] ) );

      exit;

      

    }

    

    return false;



  

}






	




















if ( ! function_exists( 'rj_user_ot_metabox_callback' ) ) {

  

  function rj_user_ot_metabox_callback() {

    global $blog_id;

    

    echo '

	<h2>User Profile Fields UI</h2>

	<form method="post" id="option-tree-settings-form">';

      

      /* form nonce */

      wp_nonce_field( 'rj_option_tree_settings_form', 'option_tree_settings_nonce' );

      

      /* format setting outer wrapper */

      echo '<div class="format-setting type-textblock has-desc">';

        

       

        

        /* get the saved settings */

        $settings = get_option(  'rj_user_'.ot_settings_id() );



        /* wrap settings array */

        echo '<div class="format-setting-inner">';

          

          /* set count to zero */

          $count = 0;

  

          /* loop through each section and its settings */

          echo '<ul class="option-tree-setting-wrap option-tree-sortable" id="option_tree_settings_list" data-name="' . ot_settings_id() . '[settings]">';

          

          if ( isset( $settings['sections'] ) ) {

          

            foreach( $settings['sections'] as $section ) {

              

              /* section */

              echo '<li class="' . ( $count == 0 ? 'ui-state-disabled' : 'ui-state-default' ) . ' list-section">' . ot_user_sections_view( ot_settings_id() . '[sections]', $count, $section ) . '

			  </li>';

              

              /* increment item count */

              $count++;

              

              /* settings in this section */

              if ( isset( $settings['settings'] ) ) {

                

                foreach( $settings['settings'] as $setting ) {

                  

                  if ( isset( $setting['section'] ) && $setting['section'] == $section['id'] ) {

                    

                    echo '<li class="ui-state-default list-setting">' . ot_settings_view( ot_settings_id() . '[settings]', $count, $setting ) . '</li>';

                    

                    /* increment item count */

                    $count++;

                    

                  }

                  

                }

                

              }



            }

            

          }

          

          echo '</ul>';

          

          /* buttons */

          echo '<a href="javascript:void(0);" class="option-tree-section-add option-tree-ui-button button hug-left">' . __( 'Add Section', 'option-tree' ) . '</a>';

          echo '<a href="javascript:void(0);" class="option-tree-setting-add option-tree-ui-button button">' . __( 'Add Setting', 'option-tree' ) . '</a>';

          echo '<button class="option-tree-ui-button button button-primary right hug-right">' . __( 'Save Changes', 'option-tree' ) . '</button>';

          

          /* sidebar textarea */

          echo '

          <div class="format-setting-label" id="contextual-help-label">

            <h3 class="label">' . __( 'Contextual Help', 'option-tree' ) . '</h3>

          </div>

          <div class="format-settings" id="contextual-help-setting">

            <div class="format-setting type-textarea no-desc">

              <div class="description"><strong>' . __( 'Contextual Help Sidebar', 'option-tree' ) . '</strong>: ' . __( 'If you decide to add contextual help to the Theme Option page, enter the optional "Sidebar" HTML here. This would be an extremely useful place to add links to your themes documentation or support forum. Only after you\'ve added some content below will this display to the user.', 'option-tree' ) . '</div>

              <div class="format-setting-inner">

                <textarea class="textarea" rows="10" cols="40" name="' . ot_settings_id(). '[contextual_help][sidebar]">' . ( isset( $settings['contextual_help']['sidebar'] ) ? esc_html( $settings['contextual_help']['sidebar'] ) : '' ) . '</textarea>

              </div>

            </div>

          </div>';

          

          /* set count to zero */

          $count = 0;

          

          /* loop through each contextual_help content section */

          echo '<ul class="option-tree-setting-wrap option-tree-sortable" id="option_tree_settings_help" data-name="' . ot_settings_id(). '[contextual_help][content]">';

          

          if ( isset( $settings['contextual_help']['content'] ) ) {

          

            foreach( $settings['contextual_help']['content'] as $content ) {

              

              /* content */

              echo '<li class="ui-state-default list-contextual-help">' . ot_contextual_help_view( ot_settings_id() . '[contextual_help][content]',  $count, $content ) . '</li>';

              

              /* increment content count */

              $count++;



            }

            

          }

          

          echo '</ul>';



          echo '<a href="javascript:void(0);" class="option-tree-help-add option-tree-ui-button button hug-left">' . __( 'Add Contextual Help Content', 'option-tree' ) . '</a>';

          echo '<button class="option-tree-ui-button button button-primary right hug-right">' . __( 'Save Changes', 'option-tree' ) . '</button>';



        echo '</div>';

        

      echo '</div>';

    

    echo '</form>';



	

	

    

  }

  

}


if ( ! function_exists( 'ot_user_sections_view' ) ) {



  function ot_user_sections_view( $name, $key, $section = array() ) {

	  //if(isset($_GET['page']) and $_GET['page']=='rj_ot-settings'){

	  if(1==1){

	$post_types = get_post_types('', 'names'  );

	

	

	$section['page'] = (isset($section['page']) && is_array($section['page'])) ? $section['page'] : array();

	$section['pages'] = (isset($section['pages']) && is_array($section['pages'])) ? $section['pages'] : array();

	$section['template'] = (isset($section['template']) && is_array($section['template'])) ? $section['template'] : array();

	

	

	$disabled_post_types = array('page','attachment','revision','nav_menu_item','option-tree');

	

	 $post_type_template = "<select  name='" . esc_attr( $name ) . "[" . esc_attr( $key ) . "][pages][]"."' value=\"\" class=\"option-tree-ui-multiselect\" multiple style=\"height: 100px; width: 100%;\">";

	 if(in_array('page',$section['pages'])){

	 $post_type_template .="<option value=\"page\" selected>Page</option>";

	 }else{

	 $post_type_template .="<option value=\"page\" >Page</option>";

	 }

	  foreach ( $post_types as $keya => $value ) {

		  if(!in_array($value,$disabled_post_types)){

	 if(in_array($value,$section['pages'])){

	    $post_type_template.="<option value=\"$value\" selected>$keya</option>";

	 }else{

	    $post_type_template.="<option value=\"$value\">$keya</option>";

	 }

		  }

   }

	 $post_type_template .="</select>";

	

	

  $templates = get_page_templates();

  $page_template = "<select  name='" . esc_attr( $name ) . "[" . esc_attr( $key ) . "][template][]"."' value=\"\" class=\"option-tree-ui-multiselect\" multiple style=\"height: 100px; width: 100%;\">";

   if(in_array('default',$section['template'])){

 		 $page_template .= "<option value=\"default\" selected>Default</option>";

   }else{

  		$page_template .= "<option value=\"default\">Default</option>";

   }

 foreach ( $templates as $template_name => $template_filename ) {

	 if(in_array($template_filename,$section['template'])){

	    $page_template.="<option value=\"$template_filename\" selected>$template_name</option>";

	 }else{

	    $page_template.="<option value=\"$template_filename\">$template_name</option>";

	 }

   }

   $page_template .="</select>";

   

   

    $all_pages = get_posts('post_type=page');

  $all_pages_template = "<select  name='" . esc_attr( $name ) . "[" . esc_attr( $key ) . "][page][]"."' value=\"\" class=\"option-tree-ui-multiselect\" multiple style=\"height: 100px; width: 100%;\">";

  

   if(in_array('default',$section['page'])){

  		$all_pages_template .= "<option value=\"default\" selected>Default</option>";

   }else{

  		$all_pages_template .= "<option value=\"default\">Default</option>";

   }

 foreach ( $all_pages as $sin_page ) {

	 if(in_array($sin_page->ID,$section['page'])){

	    $all_pages_template.="<option value=\"{$sin_page->ID}\" selected>{$sin_page->post_title}</option>";

	 }else{

	    $all_pages_template.="<option value=\"{$sin_page->ID}\">{$sin_page->post_title}</option>";

	 }

   }

   $all_pages_template .="</select>";

   

   

   $page_field ='<div class="format-settings">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Page</strong>: Specify a page that you want to display this metabox. Seperated by comma', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

             '.$all_pages_template.'

            </div>

          </div>

        </div>';

   

   

    return '

    <div class="option-tree-setting is-section">

      <div class="open">' . ( isset( $section['title'] ) ? esc_attr( $section['title'] ) : 'Section ' . ( $key + 1 ) ) . '</div>

      <div class="button-section">

        <a href="javascript:void(0);" class="option-tree-setting-edit option-tree-ui-button button left-item" title="' . __( 'edit', 'option-tree' ) . '">

          <span class="icon ot-icon-pencil"></span>' . __( 'Edit', 'option-tree' ) . '

        </a>

        <a href="javascript:void(0);" class="option-tree-setting-remove option-tree-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'option-tree' ) . '">

          <span class="icon ot-icon-trash-o"></span>' . __( 'Delete', 'option-tree' ) . '

        </a>

      </div>

      <div class="option-tree-setting-body">

        <div class="format-settings">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Section Title</strong>: Displayed as a menu item on the Theme Options page.', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][title]" value="' . ( isset( $section['title'] ) ? esc_attr( $section['title'] ) : '' ) . '" class="widefat option-tree-ui-input option-tree-setting-title section-title" autocomplete="off" />

            </div>

          </div>

        </div>

        <div class="format-settings">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Section ID</strong>: A unique lower case alphanumeric string, underscores allowed.', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][id]" value="' . ( isset( $section['id'] ) ? esc_attr( $section['id'] ) : '' ) . '" class="widefat option-tree-ui-input section-id" autocomplete="off" />

            </div>

          </div>

        </div>

		

		

		

		

		

      </div>

    </div>';

	  }else{

  

    return '

    <div class="option-tree-setting is-section">

      <div class="open">' . ( isset( $section['title'] ) ? esc_attr( $section['title'] ) : 'Section ' . ( $key + 1 ) ) . '</div>

      <div class="button-section">

        <a href="javascript:void(0);" class="option-tree-setting-edit option-tree-ui-button button left-item" title="' . __( 'edit', 'option-tree' ) . '">

          <span class="icon ot-icon-pencil"></span>' . __( 'Edit', 'option-tree' ) . '

        </a>

        <a href="javascript:void(0);" class="option-tree-setting-remove option-tree-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'option-tree' ) . '">

          <span class="icon ot-icon-trash-o"></span>' . __( 'Delete', 'option-tree' ) . '

        </a>

      </div>

      <div class="option-tree-setting-body">

        <div class="format-settings">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Section Title</strong>: Displayed as a menu item on the Theme Options page.', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][title]" value="' . ( isset( $section['title'] ) ? esc_attr( $section['title'] ) : '' ) . '" class="widefat option-tree-ui-input option-tree-setting-title section-title" autocomplete="off" />

            </div>

          </div>

        </div>

        <div class="format-settings">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Section ID</strong>: A unique lower case alphanumeric string, underscores allowed.', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][id]" value="' . ( isset( $section['id'] ) ? esc_attr( $section['id'] ) : '' ) . '" class="widefat option-tree-ui-input section-id" autocomplete="off" />

            </div>

          </div>

        </div>

		

      </div>

    </div>';

	  }

    

  }



}






add_action( 'admin_init', 'rj_user_ot_show_settings');

function rj_user_ot_show_settings(){
	

	$meta_options = get_option( 'rj_user_'.ot_settings_id() );

	$post_id = (isset($post_id)) ? $post_id : '';

	

	

	

	

	//print_r($meta_options);

  

 // print_r($new_meta_boadx);

  //print_r($meta_options);

	

	

	if(!empty($meta_options['sections'])){
		


		$mi=0;
		foreach($meta_options['sections'] as $meta_option){

			


			$new_meta_box =array();

			$settings =array();

			if(!empty($meta_options['settings'])){

				$i=0;

				foreach($meta_options['settings'] as $meta_settings){

					if($meta_settings['section']==$meta_option['id']){

						$settings[$i] = $meta_settings;

					}

					$i++;

				}

			}

			

			$new_meta_box[$mi] = array(

		'id'          => $meta_option['id'],

		'title'       => __( $meta_option['title'], 'theme-text-domain' ),

		'desc'        => '',

		'pages'       => "",

		'context'     => 'normal',

		'priority'    => 'high',

		'fields'      => $settings

	  );


		rj_ot_user_register_meta_box( $new_meta_box[$mi] );

		$mi++;


			

		

		}

	}
}





//usage




if ( ! function_exists( 'ot_array' ) ) {

	

function ot_array($var){

		if ( function_exists( 'ot_get_option' ) ) {		  

		  /* get the slider array */

		  $output = ot_get_option($var, array() );

		  

		  if(is_array($output)){

	}else{

		$output = do_shortcode($output);

	}

		  return $output;

		}

	}

}











 




   function rj_user_ot_import_callback() {
  $plugins_url = plugins_url();
  
   if ( 
   ! isset( $_POST['import_user_ot_meta_settings_nonce'] ) 
    || ! wp_verify_nonce( $_POST['import_user_ot_meta_settings_nonce'], 'import_user_ot_meta_settings_form' ) 
) {
	$import_success = '';
}else{
	 $ot_meta_settings =  ot_decode( $_REQUEST['import_user_ot_meta_settings'] );
	 $ot_meta_settings = unserialize($ot_meta_settings);
       
		update_option( 'rj_user_'.ot_settings_id(),$ot_meta_settings );
		$import_success = '<p style="
	  border: 1px solid #86B384;
	  background: #A3C6A1;
	  display: block;
	  width: 62%;
	  padding: 5px;
	  color:#fff;">Imported Settings Successfully<p>';
}
  rj_ot_admin_styless();
    
    echo '<form method="post" id="import_ot_meta_settings_form">';
	echo '<h2>Import User MetaboxUI Settings</h2>';
	echo $import_success;
      
      /* form nonce */
      wp_nonce_field( 'import_user_ot_meta_settings_form', 'import_user_ot_meta_settings_nonce' );
      
      /* format setting outer wrapper */
      echo '<div class="format-setting type-textarea has-desc">';
           
        /* description */
        echo '<div class="description">';
          
          echo '<p>' . __( 'To import your Settings copy and paste what appears to be a random string of alpha numeric characters into this textarea and press the "Import Settings" button.', 'option-tree' ) . '</p>';
          echo '<p>' . __( 'Please note this will replace all of your current settings.', 'option-tree' ) . '</p>';
          
         
          
        echo '</div>';
        
        /* textarea */
        echo '<div class="format-setting-inner">';
          
          echo '<textarea rows="10" cols="40" name="import_user_ot_meta_settings" id="import_user_ot_meta_settings" class="textarea"></textarea>'; /* button */
          echo '<button class="option-tree-ui-button button button-primary right hug-right">' . __( 'Import Settings', 'option-tree' ) . '</button>';

        echo '</div>';
        
      echo '</div>';
    
    echo '</form>';
    
  
   }
   
   function rj_user_ot_export_callback() {
  $plugins_url = plugins_url();
  
    rj_ot_admin_styless();
    /* format setting outer wrapper */
    echo '<div class="format-setting type-textarea simple has-desc">';
	echo '<h2>Export User MetaboxUI Settings</h2>';
      
      /* description */
      echo '<div class="description">';
        
        echo '<p>' . __( 'Export your Settings by highlighting this text and doing a copy/paste into a blank .txt file. Then save the file for importing into another install of WordPress later. Alternatively, you could just paste it into the <code>Metaboxes->Import</code> <strong>Settings</strong> textarea on another web site.', 'option-tree' ) . '</p>';
        
      echo '</div>';
        
      /* get theme options data */
      $settings = get_option( 'rj_user_'.ot_settings_id() );
      $settings = ! empty( $settings ) ?  ot_encode( serialize( $settings ) ) : '';
        
      echo '<div class="format-setting-inner">';
        echo '<textarea rows="10" cols="40" name="export_user_ot_meta_settings" id="export_user_ot_meta_settings" class="textarea">' . $settings . '</textarea>';
      echo '</div>';
      
    echo '</div>';
    
  
   }


 function ot_user_meta_attachment($var,$echo=''){	
	$attachment_id = ot_user_meta($var);
	$attachment_url = wp_get_attachment_url( $attachment_id ); // returns an array
	if($echo==1){
		echo $attachment_url;
	}else{
		return $attachment_url;
	}
}
 function ot_user_meta_image($var,$echo='',$size='full'){	
	$attachment_id = ot_user_meta($var);
	$image_attributes = wp_get_attachment_image_src( $attachment_id,$size ); // returns an array
	if($echo==1){
		echo $image_attributes[0];
	}else{
		return $image_attributes[0];
	}
}



 function ot_user_meta_attachment_by_uid($id,$var,$echo=''){	
	$attachment_id = ot_user_meta($id,$var);
	$attachment_url = wp_get_attachment_url( $attachment_id ); // returns an array
	if($echo==1){
		echo $attachment_url;
	}else{
		return $attachment_url;
	}
}
 function ot_user_meta_image_by_uid($id,$var,$echo='',$size='full'){	
	$attachment_id = ot_user_meta($id,$var);
	$image_attributes = wp_get_attachment_image_src( $attachment_id,$size ); // returns an array
	if($echo==1){
		echo $image_attributes[0];
	}else{
		return $image_attributes[0];
	}
}


function ot_user_attachment($var,$echo=''){	
	$attachment_id = ot($var);
	$attachment_url = wp_get_attachment_url( $attachment_id); // returns an array
	if($echo==1){
		echo $attachment_url;
	}else{
		return $attachment_url;
	}
}
function ot_user_image($var,$echo='',$size='full'){	
	$attachment_id = ot($var);
	$image_attributes = wp_get_attachment_image_src( $attachment_id,$size ); // returns an array
	if($echo==1){
		echo $image_attributes[0];
	}else{
		return $image_attributes[0];
	}
}


function ot_user_attachment_by_uid($id,$var,$echo=''){	
	$attachment_id = ot($id,$var);
	$attachement_url = wp_get_attachment_url( $attachment_id ); // returns an array
	if($echo==1){
		echo $attachement_url;
	}else{
		return $attachement_url;
	}
}
function ot_user_image_by_uid($id,$var,$echo='',$size='full'){	
	$attachment_id = ot($id,$var);
	$image_attributes = wp_get_attachment_image_src( $attachment_id,$size ); // returns an array
	if($echo==1){
		echo $image_attributes[0];
	}else{
		return $image_attributes[0];
	}
}



function ot_user_meta_attachment_by_id($var,$echo='',$defaultVal=''){
	$attachement_url  = wp_get_attachment_url( $var ); // returns an array
	
	if( $attachement_url ) {
		if($echo==1){
			echo $attachement_url;
		}else{
			return $attachement_url;
		}	
	}else{
		if($echo==1){
			echo $defaultVal;
		}else{
			return $defaultVal;
		}	
	}
}
function ot_user_meta_image_by_id($var,$echo='',$defaultVal='',$size='full'){
	$image_attributes  = wp_get_attachment_image_src( $var,$size ); // returns an array
	
	if( $image_attributes ) {
		if($echo==1){
			echo $image_attributes[0];
		}else{
			return $image_attributes[0];
		}	
	}else{
		if($echo==1){
			echo $defaultVal;
		}else{
			return $defaultVal;
		}	
	}
}




if ( ! function_exists( 'ot_user_meta' ) ) {

	

function ot_user_meta($var,$echo=''){
	global $post;
	if($echo==1){
		echo ot_user_meta_by_uid($post->post_author,$var);
	}else{
		return ot_user_meta_by_uid($post->post_author,$var);
	}

} 

} 
if ( ! function_exists( 'ot_user_meta_by_id' ) ) {

	

function ot_user_meta_by_uid($id,$var,$echo=''){
	$output = get_user_meta($id,$var,true);

	 if(is_array($output)){

	}else{

		$output = do_shortcode($output);

	}

	

	if($echo==1){

		echo $output;

	}else{

		return $output;

	}

}
}