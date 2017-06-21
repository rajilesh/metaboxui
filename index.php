<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*

Plugin Name: MetaboxUI

Plugin URI: http://rajilesh.in/metaboxui-pro/

Description: This is for developers who love best admin interface.

Author: Rajilesh Panoli

Version: 7.160

Contributors: rajilesh

Author URI: http://rajilesh.in

*/
 define( 'RJ_OT_THEME_MODE', apply_filters( 'rj_ot_theme_mode', false ) );

 define( 'RJ_OT_CHILD_THEME_MODE', apply_filters( 'rj_ot_child_theme_mode', false ) );

	
 if ( false == RJ_OT_THEME_MODE && false == RJ_OT_CHILD_THEME_MODE ) {
        define( 'RJ_OT_DIR', plugin_dir_path( __FILE__ ) );
        define( 'RJ_OT_URL', plugin_dir_url( __FILE__ ) );
      } else {
        if ( true == RJ_OT_CHILD_THEME_MODE ) {
          $path = ltrim( end( @explode( get_stylesheet(), str_replace( '\\', '/', dirname( __FILE__ ) ) ) ), '/' );
          define( 'RJ_OT_DIR', trailingslashit( trailingslashit( get_stylesheet_directory() ) . $path ) );
          define( 'RJ_OT_URL', trailingslashit( trailingslashit( get_stylesheet_directory_uri() ) . $path ) );
        } else {
          $path = ltrim( end( @explode( get_template(), str_replace( '\\', '/', dirname( __FILE__ ) ) ) ), '/' );
          define( 'RJ_OT_DIR', trailingslashit( trailingslashit( get_template_directory() ) . $path ) );
          define( 'RJ_OT_URL', trailingslashit( trailingslashit( get_template_directory_uri() ) . $path ) );
        }
      }


include_once( ABSPATH . 'wp-admin/includes/plugin.php' );	
include_once( 'includes/user_meta_box.php' );		
include_once( 'includes/taxonomy_meta_box.php' );
include_once( 'includes/widget_meta_box.php' );	
include_once( 'includes/option-types.php' );	


if(!is_plugin_active('option-tree/ot-loader.php')){

include_once( 'compatibility/ot-loader.php' ); 
	/* deactivate_plugins( plugin_basename( __FILE__ ) );

			wp_die( 'This plugin requires <a href="'.get_bloginfo('url').'/wp-admin/plugin-install.php?tab=search&type=term&s=optiontree">Optiontree</a> plugin' );


			die(); */

}

add_action( 'admin_init', 'rj_ot_save_settings', 6 );


function rj_ot_admin_styless(){
    global $wp_styles, $post,$pagenow;;
    
    /* execute styles before actions */
    do_action( 'ot_admin_styles_before' );
    
    /* load WP colorpicker */
    wp_enqueue_style( 'wp-color-picker' );
    
    /* load admin styles */
   
    wp_enqueue_style( 'ot-admin-css', OT_URL . 'assets/css/ot-admin.css', false, OT_VERSION );
    if($pagenow=='widgets.php'){
    wp_enqueue_style( 'ot-metabox-admin-css', RJ_OT_URL . 'css/metabox-widget-admin.css', false, OT_VERSION );
     }
    
    /* load the RTL stylesheet */
    $wp_styles->add_data( 'ot-admin-css','rtl', true );
    
    /* Remove styles added by the Easy Digital Downloads plugin */
    if ( isset( $post->post_type ) && $post->post_type == 'post' )
      wp_dequeue_style( 'jquery-ui-css' );

    /**
     * Filter the screen IDs used to dequeue `jquery-ui-css`.
     *
     * @since 2.5.0
     *
     * @param array $screen_ids An array of screen IDs.
     */
    $screen_ids = apply_filters( 'ot_dequeue_jquery_ui_css_screen_ids', array( 
      'toplevel_page_ot-settings', 
      'optiontree_page_ot-documentation', 
      'appearance_page_ot-theme-options' 
    ) );
    
   
    
    /* execute styles after actions */
    do_action( 'ot_admin_styles_after' );

  
}
function rj_ot_admin_scriptss(){
	global $pagenow;
    
   
    
    /* execute scripts before actions */
    do_action( 'ot_admin_scripts_before' );
    
    if ( function_exists( 'wp_enqueue_media' ) ) {
      /* WP 3.5 Media Uploader */
      wp_enqueue_media();
    } else {
      /* Legacy Thickbox */
      add_thickbox();
    }

    /* load jQuery-ui slider */
    wp_enqueue_script( 'jquery-ui-slider' );

    /* load jQuery-ui datepicker */
    wp_enqueue_script( 'jquery-ui-datepicker' );

    /* load WP colorpicker */
    wp_enqueue_script( 'wp-color-picker' );

    /* load Ace Editor for CSS Editing */
    wp_enqueue_script( 'ace-editor', RJ_OT_URL.'js/ace.js', null, '1.1.3' );   

    /* load jQuery UI timepicker addon */
    wp_enqueue_script( 'jquery-ui-timepicker', OT_URL . 'assets/js/vendor/jquery/jquery-ui-timepicker.js', array( 'jquery', 'jquery-ui-slider', 'jquery-ui-datepicker' ), '1.4.3' );

    /* load the post formats */
    if ( OT_META_BOXES == true && OT_POST_FORMATS == true ) {
      wp_enqueue_script( 'ot-postformats', OT_URL . 'assets/js/ot-postformats.js', array( 'jquery' ), '1.0.1' );
    }
    if($pagenow=='widgets.php'){
    /* load all the required scripts */
    wp_enqueue_script( 'ot-admin-js', RJ_OT_URL . 'js/metabox-widget-admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-sortable', 'jquery-ui-slider', 'wp-color-picker', 'ace-editor', 'jquery-ui-datepicker', 'jquery-ui-timepicker' ), OT_VERSION );
    }else{
    /* load all the required scripts */
    wp_enqueue_script( 'ot-admin-js', OT_URL . 'assets/js/ot-admin.js', array( 'jquery', 'jquery-ui-tabs', 'jquery-ui-sortable', 'jquery-ui-slider', 'wp-color-picker', 'ace-editor', 'jquery-ui-datepicker', 'jquery-ui-timepicker' ), OT_VERSION );
    }
    

    /* create localized JS array */
    $localized_array = array( 
      'ajax'                  => admin_url( 'admin-ajax.php' ),
      'upload_text'           => apply_filters( 'ot_upload_text', __( 'Send to OptionTree', 'option-tree' ) ),
      'remove_media_text'     => __( 'Remove Media', 'option-tree' ),
      'reset_agree'           => __( 'Are you sure you want to reset back to the defaults?', 'option-tree' ),
      'remove_no'             => __( 'You can\'t remove this! But you can edit the values.', 'option-tree' ),
      'remove_agree'          => __( 'Are you sure you want to remove this?', 'option-tree' ),
      'activate_layout_agree' => __( 'Are you sure you want to activate this layout?', 'option-tree' ),
      'setting_limit'         => __( 'Sorry, you can\'t have settings three levels deep.', 'option-tree' ),
      'delete'                => __( 'Delete Gallery', 'option-tree' ), 
      'edit'                  => __( 'Edit Gallery', 'option-tree' ), 
      'create'                => __( 'Create Gallery', 'option-tree' ), 
      'confirm'               => __( 'Are you sure you want to delete this Gallery?', 'option-tree' ),
      'date_current'          => __( 'Today', 'option-tree' ),
      'date_time_current'     => __( 'Now', 'option-tree' ),
      'date_close'            => __( 'Close', 'option-tree' ),
      'replace'               => __( 'Featured Image', 'option-tree' ),
      'with'                  => __( 'Image', 'option-tree' )
    );
    
    /* localized script attached to 'option_tree' */
    wp_localize_script( 'ot-admin-js', 'option_tree', $localized_array );
    
    /* execute scripts after actions */
    do_action( 'ot_admin_scripts_after' );

  
}


function rj_ot_save_settings(){
global $pagenow;
	if(isset($_REQUEST['page']) && $_REQUEST['page']=='rj_ot-settings'){

	  rj_ot_admin_scriptss();

	  rj_ot_admin_styless();
     if($pagenow=='widgets.php'){
       
     }else{  
         wp_enqueue_script( 'rj_ot_metabox_script', plugin_dir_url(__FILE__) . 'js/metabox-ui-settings.js' );
     }
     

	  

}

	  

	



    /* check and verify import settings nonce */

    if ( isset( $_POST['option_tree_settings_nonce'] ) && wp_verify_nonce( $_POST['option_tree_settings_nonce'], 'rj_option_tree_settings_form' ) && isset($_GET['page']) && $_GET['page']=='rj_ot-settings' ) {



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

		

     

       update_option( 'rj'.ot_settings_id(), $settings );

        $message = 'success';

        

      }

      

      /* redirect */

      wp_redirect( add_query_arg( array( 'action' => 'save-settings', 'message' => $message ), $_POST['_wp_http_referer'] ) );

      exit;

      

    }

    

    return false;



  

}







if ( ! function_exists( 'rj_ot_type_theme_options_ui' ) ) {

  

  function rj_ot_type_theme_options_ui() {

    global $blog_id;

    

    echo '

	<h2>Metaboxes UI</h2>

	<form method="post" id="option-tree-settings-form">';

      

      /* form nonce */

      wp_nonce_field( 'rj_option_tree_settings_form', 'option_tree_settings_nonce' );

      

      /* format setting outer wrapper */

      echo '<div class="format-setting type-textblock has-desc">';

        

       

        

        /* get the saved settings */

        $settings = get_option(  'rj'.ot_settings_id() );



        /* wrap settings array */

        echo '<div class="format-setting-inner">';

          

          /* set count to zero */

          $count = 0;

  

          /* loop through each section and its settings */

          echo '<ul class="option-tree-setting-wrap option-tree-sortable" id="option_tree_settings_list" data-name="' . ot_settings_id() . '[settings]">';

          

          if ( isset( $settings['sections'] ) ) {

          

            foreach( $settings['sections'] as $section ) {

              

              /* section */

              echo '<li class="' . ( $count == 0 ? 'ui-state-disabled' : 'ui-state-default' ) . ' list-section">' . ot_sections_view( ot_settings_id() . '[sections]', $count, $section ) . '

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









if ( ! function_exists( 'ot_sections_view' ) ) {



  function ot_sections_view( $name, $key, $section = array() ) {

	  //if(isset($_GET['page']) and $_GET['page']=='rj_ot-settings'){

	  if($_GET['page'] !='ot-settings'){
	   $default_widget_template = '{before_widget} <div class="your_class_name"> {before_title}{if:title}{title}{else:title}no title{endif:title} {after_title}
</div> {after_widget}';

	$post_types = get_post_types('', 'names'  );

	

	

	$section['page'] = (isset($section['page']) && is_array($section['page'])) ? $section['page'] : array();

	$section['pages'] = (isset($section['pages']) && is_array($section['pages'])) ? $section['pages'] : array();

	$section['template'] = (isset($section['template']) && is_array($section['template'])) ? $section['template'] : array();
          
	$section['taxonomies'] = (isset($section['taxonomies']) && is_array($section['taxonomies'])) ? $section['taxonomies'] : array();

	

	

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
          
          
          
     $disabled_taxonomies = array('link_category','post_format');
          $all_taxonomies = get_taxonomies();

  $all_taxonomies_template = "<select  name='" . esc_attr( $name ) . "[" . esc_attr( $key ) . "][taxonomies][]"."' value=\"\" class=\"option-tree-ui-multiselect\" multiple style=\"height: 100px; width: 100%;\">";

  
 

 foreach ( $all_taxonomies as $sin_taxnomy ) {
       if(!in_array($sin_taxnomy,$disabled_taxonomies)){

             if(in_array($sin_taxnomy,$section['taxonomies'])){

                $all_taxonomies_template.="<option value=\"{$sin_taxnomy}\" selected>{$sin_taxnomy}</option>";

             }else{

                $all_taxonomies_template.="<option value=\"{$sin_taxnomy}\">{$sin_taxnomy}</option>";

             }
       }

   }

   $all_taxonomies_template .="</select>";      

   

   

   $page_field ='<div class="format-settings">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Page</strong>: Specify a page that you want to display this metabox. Seperated by comma', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

             '.$all_pages_template.'

            </div>

          </div>

        </div>';

  $widget_template_field = '<div class="format-settings widget_template_section_field">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Widget Template</strong>: Write some html that you want to display this widget on front-end. 
            <br /><br />
            Eg code: <code>
            {before_widget}
            &lt;div class="your_class_name"&gt;{before_title}{if:title}{title}{else:title}no title{endif:title} {after_title}<br /><br />
{loop:my_first_slider_id}<br />
{title}<br />
{description}<br />
&lt;img src="{image}" /&gt;<br />
{endloop:my_first_slider_id}<br /><br />

{loop:my_second_slider_id}<br />
{title}<br />
{description}<br />
&lt;img src="{image}" /&gt;<br />
{endloop:my_second_slider_id}<br /><br />



{image:url}<br />
{attachment_id:url}
<br /><br />
&lt;/div&gt;<br />
            {after_widget}</code>
            <br />
            <br />
            Usage : {your_field_id} will replace with value of "your_field_id"
            <br />
            <br />
            Usage : {your_field_id} will replace with value of "your_field_id"<br />
            {your_field_id:url} will replace id to url
            <br />
            <br />
            <strong>To write conditional statements</strong><br />
            {if:title}{title}{else:title}no title{endif:title}
            <br />
            <br />
            <strong>To write a loop </strong>
            <br />
            {loop:your_repeating_field_id}
            <br />
            // inside of this space, you can call sub fields of this field like {title}
            <br />
            {endloop:your_repeating_field_id}
            <br /><br />
            <strong>To Convert attachment ID or to url</strong> <br />
            Use ":url" suffix<br />
            Eg: {image:url} this will convert {image}  to url<br />
            Eg: {attachment_id:url} this will convert {attachment_id}  to url
            
            
             <br /><br />
            <strong>To Write your own shortcode</strong> <br />
            Use [your_custom_shortcode]<br />
            Note you can also use global variables such as $args and $instance to get this widget datas
            
             <br /><br />
            <strong>To Customise this widget via hooks</strong> <br />
            Use  <code>do_action( "wp_widget_design_' . ( isset( $section['id'] ) ? esc_attr( $section['id'] ) : 'widget_id' ) . '", $args, $instance);</code><br />    
            ', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

            <textarea type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][widget_template]" value="" class="widefat option-tree-ui-input section-id" rows=10>' . ( isset( $section['widget_template'] ) ? esc_attr( $section['widget_template'] ) : $default_widget_template ) . '</textarea>

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

		

		 <div class="format-settings post_type_section_field">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Post type</strong>:  Specify a post type that you want to display this metabox. Seperated by comma', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

             '. $post_type_template.'

            </div>

          </div>

        </div>

		

		 <div class="format-settings template_page_section_field">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Template</strong>: Specify a template that you want to display this metabox. Seperated by comma', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

             '.$page_template.'

            </div>

          </div>

        </div>
        
        <div class="format-settings taxonomies_section_field">

          <div class="format-setting type-text">

            <div class="description">' . __( '<strong>Taxonomy</strong>: Specify a template that you want to display this metabox. Seperated by comma', 'option-tree' ) . '</div>

            <div class="format-setting-inner">

             '.$all_taxonomies_template.'

            </div>

          </div>

        </div>

		

		'.$widget_template_field.'

		

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





   
add_action( 'admin_menu', 'register_my_custom_menu_page' );

   


function register_my_custom_menu_page(){
    
    add_menu_page('Metaboxes', 'Metaboxes UI', 'manage_options', 'rj_ot-settings', 'rj_ot_type_theme_options_ui','', 99 );
	
	add_submenu_page( 
          'rj_ot-settings'   //or 'options.php' 
        , 'UserMetabox' 
        , 'User Profile Fields  UI'
        , 'manage_options'
        , 'rj-ot-user_metabox'
        , 'rj_user_ot_metabox_callback'
    );
    add_submenu_page( 
          'rj_ot-settings'   //or 'options.php' 
        , 'TaxonomyMetabox' 
        , 'Taxonomy Custom Fields  UI'
        , 'manage_options'
        , 'rj-ot-taxonomy_metabox'
        , 'rj_taxonomy_ot_metabox_callback'
    );
    add_submenu_page( 
          'rj_ot-settings'   //or 'options.php' 
        , 'WidgetMetabox' 
        , 'Widget Fields UI'
        , 'manage_options'
        , 'rj-ot-widget_metabox'
        , 'rj_widget_ot_metabox_callback'
    );
  add_submenu_page( 
          'rj_ot-settings'   //or 'options.php' 
        , 'ThemeOptions' 
        , 'Theme Options UI'
        , 'manage_options'
        , 'admin.php?page=ot-settings'
    );
    
	add_submenu_page( 
          'rj_ot-settings'   //or 'options.php' 
        , 'Import' 
        , 'Import'
        , 'manage_options'
        , 'rj-ot-import'
        , 'rj_ot_import_callback'
    );
	add_submenu_page( 
          'rj_ot-settings'   //or 'options.php' 
        , 'Export' 
        , 'Export'
        , 'manage_options'
        , 'rj-ot-export'
        , 'rj_ot_export_callback'
    );
	add_submenu_page( 
          'rj_ot-settings'   //or 'options.php' 
        , 'Documentation' 
        , 'Documentation'
        , 'manage_options'
        , 'rj-ot-documentation'
        , 'rj_ot_documentation_callback'
    );
	
	

}



add_action( 'admin_init', 'rj_ot_show_settings');

function rj_ot_show_settings(){

	$meta_options = get_option( 'rj'.ot_settings_id() );

	$post_id = (isset($post_id)) ? $post_id : '';

	

	

	

	

	//print_r($meta_options);

  

 // print_r($new_meta_boadx);

  //print_r($meta_options);

	

	

	if(!empty($meta_options['sections'])){
		


		$mi=0;
		foreach($meta_options['sections'] as $meta_option){

			if(isset($_REQUEST['post']) && $_REQUEST['post']){

				$post_id =$_REQUEST['post'];

			}else if(isset($_REQUEST['post_ID']) && $_REQUEST['post_ID']){

				$post_id =$_REQUEST['post_ID'];

			}
            
            $current_post_type = get_post_type($post_id);
            
			$post_template = get_post_meta($post_id,'_wp_page_template',true);

			$post_template = ($post_template || $post_template !='') ? $post_template : 'default';
            
           
			$new_meta_box =array();

			$settings =array();

			//$template_array = explode(',',$meta_option['template']);

			

			$pages_arr = (isset($meta_option['page']) && is_array($meta_option['page'])) ? $meta_option['page'] :array();

			

			$template_array = (isset($meta_option['template']) && is_array($meta_option['template'])) ? $meta_option['template'] : array('default');

			if(in_array($post_template,$template_array)){

				

			if(!empty($meta_options['settings'])){

				$i=0;

				foreach($meta_options['settings'] as $meta_settings){

					if((isset($meta_settings['section']) && isset($meta_option['id'])) && $meta_settings['section']==$meta_option['id']){

						$settings[$i] = $meta_settings;

					}

					$i++;

				}

			}

			

			$new_meta_box[$mi] = array(

		'id'          => $meta_option['id'],

		'title'       => __( $meta_option['title'], 'theme-text-domain' ),

		'desc'        => '',

		'pages'       => $meta_option['pages'],

		'context'     => 'normal',

		'priority'    => 'high',

		'fields'      => $settings

	  );

	  

		ot_register_meta_box( $new_meta_box[$mi] );

		$mi++;

			}

			

		

		}

	}
}





//usage

if ( ! function_exists( 'ot' ) ) {

	

	function ot($var,$echo='',$defVal=''){

		if ( function_exists( 'ot_get_option' ) ) {

		  $output = ot_get_option( $var,$defVal);

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

}

if ( ! function_exists( 'ot_typography' ) ) {
function ot_typography($var,$echo='',$defVal=array()){
      $styles = "";
    if(is_array($var)){
    }else{
        $var = ot($var);
    }
    if(!empty($var)){
        foreach($var as $key => $val){
            if($val !=''){
                if($key=='font-color'){
                    $styles  .= "color : $val;";
                }else{
                    $styles  .= "$key : $val;";
                }
            }
        }
    }
    if($echo==1){
        echo $styles;
    }else{
        return $styles;
    }
}
}
function ot_meta_attachment($var,$echo=''){	
	$attachment_id = ot_meta($var);
	$attachment_url = wp_get_attachment_url( $attachment_id); // returns an array
	if($echo==1){
		echo $attachment_url;
	}else{
		return $attachment_url;
	}
}
function ot_meta_image($var,$echo='',$size='full'){	
	$attachment_id = ot_meta($var);
	$image_attributes = wp_get_attachment_image_src( $attachment_id,$size ); // returns an array
	if($echo==1){
		echo $image_attributes[0];
	}else{
		return $image_attributes[0];
	}
}




function ot_attachment($var,$echo=''){	
	$attachment_id = ot($var);
	$attachment_url = wp_get_attachment_url( $attachment_id); // returns an array
	if($echo==1){
		echo $attachment_url;
	}else{
		return $attachment_url;
	}
}
function ot_image($var,$echo='',$size='full'){	
	$attachment_id = ot($var);
	$image_attributes = wp_get_attachment_image_src( $attachment_id,$size ); // returns an array
	if($echo==1){
		echo $image_attributes[0];
	}else{
		return $image_attributes[0];
	}
}



function ot_meta_attachment_by_id($var,$echo='',$defaultVal=''){
	$attachment_url  = wp_get_attachment_url( $var ); // returns an array
	
	if( $attachment_url ) {
		if($echo==1){
			echo $attachment_url;
		}else{
			return $attachment_url;
		}	
	}else{
		if($echo==1){
			echo $defaultVal;
		}else{
			return $defaultVal;
		}	
	}
}

function ot_meta_image_by_id($var,$echo='',$defaultVal='',$size='full'){
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



function ot_attachment_by_id($var,$echo='',$defaultVal='',$size='full'){
	$attachment_url  = wp_get_attachment_url( $var); // returns an array
	
	if( $attachment_url ) {
		if($echo==1){
			echo $attachment_url;
		}else{
			return $attachment_url;
		}	
	}else{
		if($echo==1){
			echo $defaultVal;
		}else{
			return $defaultVal;
		}	
	}
}

function ot_image_by_id($var,$echo='',$defaultVal='',$size='full'){
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



if ( ! function_exists( 'ot_meta' ) ) {

	

function ot_meta($var,$echo=''){

	$output = get_post_meta(get_the_ID(),$var,true);

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

if ( ! function_exists( 'ot_meta_short' ) ) {

	

function ot_meta_short($var){
    
    $atts = shortcode_atts( array(
		'id' => '',
        'type'=>'image'
	), $var, 'rj_ot' );



	 

	 if($atts['type']=='image'){
        $output = ot_meta_attachment($atts['id']);
    }else{
         $output = ot_meta($atts['id']);
     }
if(is_array($output)){

	}else{

		$output = do_shortcode($output);

	}
	return $output;

}
add_shortcode( 'ot_meta_short', 'ot_meta_short' );

}


if ( ! function_exists( 'ot_meta_image_short' ) ) {

	

function ot_meta_image_short($var){
    
    $atts = shortcode_atts( array(
		'id' => ''
	), $var, 'rj_ot' );



	 

	$output = ot_meta_attachment($atts['id']);
if(is_array($output)){

	}else{

		$output = do_shortcode($output);

	}
	return $output;

}
add_shortcode( 'ot_meta_image_short', 'ot_meta_image_short' );

}


if ( ! function_exists( 'ot_short' ) ) {

	

function ot_short($var){
    
    $atts = shortcode_atts( array(
		'id' => '',
        'type'=>'image'
	), $var, 'rj_ot' );


	
    
    if($atts['type']=='image'){
        $output = ot_attachment($atts['id']);
    }else{
        $output = ot($atts['id']);
    }
	

	return $output;

}
add_shortcode( 'ot_short', 'ot_short' );
}

if ( ! function_exists( 'ot_image_short' ) ) {

	

function ot_image_short($var){
    
    $atts = shortcode_atts( array(
		'id' => ''
	), $var, 'rj_ot' );


	
    
   $output = ot_attachment($atts['id']);
	

	return $output;

}
add_shortcode( 'ot_image_short', 'ot_image_short' );
}


if ( ! function_exists( 'ot_meta_typography' ) ) {
function ot_meta_typography($var,$echo='',$defVal=array()){
      $styles = "";
    if(is_array($var)){
    }else{
        $var = ot_meta($var);
    }
    if(!empty($var)){
        foreach($var as $key => $val){
            if($val !=''){
                if($key=='font-color'){
                    $styles  .= "color : $val;";
                }else{
                    $styles  .= "$key : $val;";
                }
            }
        }
    }
    if($echo==1){
        echo $styles;
    }else{
        return $styles;
    }
}
}

add_filter( 'ot_override_forced_textarea_simple','__return_true' );

if ( ! function_exists( 'base_url' ) ) {

function base_url($echo=''){

	if($echo==1){

		echo get_bloginfo('wpurl').'/';

	}else{

		return get_bloginfo('wpurl').'/';

	}

}



add_shortcode('base_url','base_url');

}

if ( ! function_exists( 'temp_url' ) ) {

function temp_url($echo=''){

	if($echo==1){

		echo get_stylesheet_directory_uri().'/';

	}else{

		return get_stylesheet_directory_uri().'/';

	}

}

add_shortcode('temp_url','temp_url');

}

if ( ! function_exists( 'page_link' ) ) {

function page_link($atts){
$atts = shortcode_atts( array(
		'id' => '',
		'echo'=>''
	), $atts, 'metaboxui' );
	
	if($atts['echo']==1){

		echo get_permalink($atts['id']).'/';

	}else{

		return get_permalink($atts['id']).'/';

	}

}



add_shortcode('page_link','page_link');

}










/* End of file ot-functions-option-types.php */

/* Location: ./includes/ot-functions-option-types.php */







 /* add scripts for metaboxes to post-new.php & post.php */

        //add_action( 'admin_print_scripts-post-new.php', 'rj_ot_admin_scripts', 11 );

        add_action( 'admin_footer', 'rj_ot_admin_scripts', 11 );

		

		

        /* add styles for metaboxes to post-new.php & post.php */

        //add_action( 'admin_print_styles-post-new.php', 'rj_ot_admin_styles', 11 );

        add_action( 'admin_footer', 'rj_ot_admin_styles', 11 );





 function rj_ot_admin_scripts() {
     
     global $pagenow;

	  wp_register_script( 'rj-metaboxui', plugin_dir_url( __FILE__ ) . 'js/metabox-ui.js' );

	 /* load jQuery UI timepicker addon */

    wp_enqueue_script( 'rj-metaboxui');

 }

 

 function rj_ot_admin_styles(){

	 wp_enqueue_style( 'rj-metaboxui',  plugin_dir_url( __FILE__ ) . 'css/metabox-ui.css' );

 }
 
 /**
 * This filter is used to use http://base_url in your navigation menu
 */
function pph_dynamic_menu_items( $menu_items ) {

    foreach ( $menu_items as $menu_item ) {
		
		$menu_url = str_replace('http://base_url',get_bloginfo('url'),$menu_item->url);
		$menu_item->url = $menu_url;
        
    }

    return $menu_items;
}
add_filter( 'wp_nav_menu_objects', 'pph_dynamic_menu_items' );


function ot_get_settings_label( $id,$echo ='' ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option( 'option_tree_settings');

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {

    if ( $setting['id'] == $id && isset( $setting['label'] ) ) {
        
      if($echo==1){
                echo $setting['label'];
            }else{
                return $setting['label'];
            }

    }

  }

}


function ot_get_settings_desc( $id,$echo ='' ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option( 'option_tree_settings');

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {

    if ( $setting['id'] == $id && isset( $setting['desc'] ) ) {
        
      if($echo==1){
                echo $setting['desc'];
            }else{
                return $setting['desc'];
            }

    }

  }

}

function ot_get_settings_data( $id ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option('option_tree_settings');

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {
    if ( $setting['id'] == $id ) {

      return $setting;

    }

  }

}

    function ot_get_meta_label( $id,$echo ='' ) {
      if ( empty( $id ) )
        return false;

      $settings = get_option('rj'.ot_settings_id());

      if ( empty( $settings['settings'] ) )
        return false;
      foreach( $settings['settings'] as $setting ) {

        if ( $setting['id'] == $id && isset( $setting['label'] ) ) {
            
            
            if($echo==1){
                echo $setting['label'];
            }else{
                return $setting['label'];
            }

        }

      }

    }
function ot_get_meta_desc( $id,$echo='' ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option('rj'.ot_settings_id());

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {

    if ( $setting['id'] == $id && isset( $setting['desc'] ) ) {

      if($echo==1){
                echo $setting['desc'];
            }else{
                return $setting['desc'];
            }

    }

  }

}
function ot_get_meta_data( $id ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option('rj'.ot_settings_id());

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {
    if ( $setting['id'] == $id ) {

      return $setting;

    }

  }

}


    function ot_user_get_meta_label( $id,$echo ='' ) {
      if ( empty( $id ) )
        return false;

      $settings = get_option('rj_user_'.ot_settings_id());

      if ( empty( $settings['settings'] ) )
        return false;
      foreach( $settings['settings'] as $setting ) {

        if ( $setting['id'] == $id && isset( $setting['label'] ) ) {
            
            
            if($echo==1){
                echo $setting['label'];
            }else{
                return $setting['label'];
            }

        }

      }

    }
function ot_user_get_meta_desc( $id,$echo='' ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option('rj_user_'.ot_settings_id());

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {

    if ( $setting['id'] == $id && isset( $setting['desc'] ) ) {

      if($echo==1){
                echo $setting['desc'];
            }else{
                return $setting['desc'];
            }

    }

  }

}

function ot_user_get_meta_data( $id ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option('rj_user_'.ot_settings_id());

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {
    if ( $setting['id'] == $id ) {

      return $setting;

    }

  }

}


 function ot_taxonomy_get_meta_label( $id,$echo ='' ) {
      if ( empty( $id ) )
        return false;

      $settings = get_option('rj_taxonomy_'.ot_settings_id());

      if ( empty( $settings['settings'] ) )
        return false;
      foreach( $settings['settings'] as $setting ) {

        if ( $setting['id'] == $id && isset( $setting['label'] ) ) {
            
            
            if($echo==1){
                echo $setting['label'];
            }else{
                return $setting['label'];
            }

        }

      }

    }
function ot_taxonomy_get_meta_desc( $id,$echo='' ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option('rj_taxonomy_'.ot_settings_id());

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {

    if ( $setting['id'] == $id && isset( $setting['desc'] ) ) {

      if($echo==1){
                echo $setting['desc'];
            }else{
                return $setting['desc'];
            }

    }

  }

}

function ot_taxonomy_get_meta_data( $id ) {

  if ( empty( $id ) )
    return false;

  $settings = get_option('rj_taxonomy_'.ot_settings_id());

  if ( empty( $settings['settings'] ) )
    return false;

  foreach( $settings['settings'] as $setting ) {
    if ( $setting['id'] == $id ) {

      return $setting;

    }

  }

}








   function rj_ot_import_callback() {
  $plugins_url = plugins_url();
  
   if ( 
   ! isset( $_POST['import_ot_meta_settings_nonce'] ) 
    || ! wp_verify_nonce( $_POST['import_ot_meta_settings_nonce'], 'import_ot_meta_settings_form' ) 
) {
	$import_success = '';
}else{
 $ot_meta_settings =  ot_decode( $_REQUEST['import_ot_meta_settings'] );
 $ot_meta_settings = unserialize($ot_meta_settings);
       
	update_option( 'rj'.ot_settings_id(),$ot_meta_settings );
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
	echo '<h2>Import MetaboxUI Settings</h2>';
	echo $import_success;
      
      /* form nonce */
      wp_nonce_field( 'import_ot_meta_settings_form', 'import_ot_meta_settings_nonce' );
      
      /* format setting outer wrapper */
      echo '<div class="format-setting type-textarea has-desc">';
           
        /* description */
        echo '<div class="description">';
          
          echo '<p>' . __( 'To import your Settings copy and paste what appears to be a random string of alpha numeric characters into this textarea and press the "Import Settings" button.', 'option-tree' ) . '</p>';
          echo '<p>' . __( 'Please note this will replace all of your current settings.', 'option-tree' ) . '</p>';
          
         
          
        echo '</div>';
        
        /* textarea */
        echo '<div class="format-setting-inner">';
          
          echo '<textarea rows="10" cols="40" name="import_ot_meta_settings" id="import_ot_meta_settings" class="textarea"></textarea>'; /* button */
          echo '<button class="option-tree-ui-button button button-primary right hug-right">' . __( 'Import Settings', 'option-tree' ) . '</button>';

        echo '</div>';
        
      echo '</div>';
    
    echo '</form>';
	
	rj_user_ot_import_callback();
	rj_taxonomy_ot_import_callback();
       rj_widget_ot_import_callback();
    
  
   }
   
   function rj_ot_export_callback() {
  $plugins_url = plugins_url();
  
    rj_ot_admin_styless();
    /* format setting outer wrapper */
    echo '<div class="format-setting type-textarea simple has-desc">';
	echo '<h2>Export MetaboxUI Settings</h2>';
      
      /* description */
      echo '<div class="description">';
        
        echo '<p>' . __( 'Export your Settings by highlighting this text and doing a copy/paste into a blank .txt file. Then save the file for importing into another install of WordPress later. Alternatively, you could just paste it into the <code>Metaboxes->Import</code> <strong>Settings</strong> textarea on another web site.', 'option-tree' ) . '</p>';
        
      echo '</div>';
        
      /* get theme options data */
      $settings = get_option( 'rj'.ot_settings_id() );
      $settings = ! empty( $settings ) ?  ot_encode( serialize( $settings ) ) : '';
        
      echo '<div class="format-setting-inner">';
        echo '<textarea rows="10" cols="40" name="export_ot_meta_settings" id="export_ot_meta_settings" class="textarea">' . $settings . '</textarea>';
      echo '</div>';
      
    echo '</div>';
	
	rj_user_ot_export_callback();
    rj_taxonomy_ot_export_callback();
    rj_widget_ot_export_callback();
    
  
   }



 function rj_ot_documentation_callback() {
  $plugins_url = plugins_url();
    /* format setting outer wrapper */
    echo '<div class="format-setting type-textblock wide-desc">';
      
      /* description */
      echo '<div class="description">';
        
        echo '<h4>'. __( 'How-to-guide', 'rj-option-tree' ) . '</h4>';
        
        echo '<p>' . __( 'It work same as optionTree but also you can manage extra options such as templates and post types.', 'option-tree' ) . '</p>';
        echo '<p><img src="'.plugins_url( 'screenshot-1.png', __FILE__ ).'" /></p>';
		
		 echo '<p> &nbsp;</p>'; echo '<p> &nbsp;</p>';
		 
		 echo '<h4>'. __( 'MetaboxUI Usages', 'rj-option-tree' ) . '</h4>';
		 
		  echo '<p>' . __( '
			<code>To make multi select dropdown users add "multiple" in rows field.</code>
		', 'option-tree' ) . '</p>
		';
		
		
		 echo '<p>' . __( '
			<code>Instead of get_post_meta($post_id,$var,true);  You can use &lt;?php ot_meta($var,[$echo=1]); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		 echo '<p>' . __( '
			<code>To get image by variable  &lt;?php ot_meta_attachment($var,[$echo=1]); ?&gt;. It is useful for field "Attachement ID"</code>
		', 'option-tree' ) . '</p>
		';
		
		echo '<p>' . __( '
			<code>To get image by variable  &lt;?php ot_meta_image($var,[$echo=1,$size]); ?&gt;. It is useful for field "Attachement ID"</code>
		', 'option-tree' ) . '</p>
		';
		
		 echo '<p>' . __( '
			<code>To get image by attachment id  &lt;?php  ot_meta_attachment_by_id($var,$echo=\'\',$defaultVal=\'\'); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		echo '<p>' . __( '
			<code>To get image by attachment id  &lt;?php  ot_meta_image_by_id($var,$echo=\'\',$defaultVal=\'\',$size= \'full\'); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		
		// User metabox
		 echo '<p>&nbsp; </p>'; echo '<p>&nbsp; </p>';
		echo '<h4>'. __( 'User MetaboxUI Usages', 'rj-option-tree' ) . '</h4>';
		 echo '<p>' . __( '
			<code>To get user meta  You can use &lt;?php ot_user_meta($var,[$echo=1]); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		 echo '<p>' . __( '
			<code>To get user meta by id  You can use &lt;?php ot_user_meta_by_uid($user_id,$var,[$echo=1]); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		 echo '<p>' . __( '
			<code>To get image by variable  &lt;?php ot_user_meta_attachment($var,[$echo=1]); ?&gt;. It is useful for field "Attachement ID"</code>
		', 'option-tree' ) . '</p>
		';
		
		echo '<p>' . __( '
			<code>To get image by variable  &lt;?php ot_user_meta_image($var,[$echo=1,$size]); ?&gt;. It is useful for field "Attachement ID"</code>
		', 'option-tree' ) . '</p>
		';
		
		
		 echo '<p>' . __( '
			<code>To get image by attachment id  &lt;?php  ot_user_meta_attachment_by_id($var,$echo=\'\',$defaultVal=\'\'); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		echo '<p>' . __( '
			<code>To get image by attachment id  &lt;?php  ot_user_meta_image_by_id($var,$echo=\'\',$defaultVal=\'\',$size= \'full\'); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		
		// Taxonomy metabox
		 echo '<p>&nbsp; </p>'; echo '<p>&nbsp; </p>';
		echo '<h4>'. __( 'Taxonomy MetaboxUI Usages', 'rj-option-tree' ) . '</h4>';
		 echo '<p>' . __( '
			<code>To get a taxonomy meta value  You can use &lt;?php ot_taxonomy_meta($term_id,$var,[$echo=1]); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		 echo '<p>' . __( '
			<code>To get all taxonomy meta values  You can use &lt;?php ot_taxonomy($term_id,[$echo=1]); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		
		// for optiontree
		 echo '<p>&nbsp; </p>'; echo '<p>&nbsp; </p>';
		 echo '<h4>'. __( 'Usages for getting values from Theme options (Some handy functions)', 'rj-option-tree' ) . '</h4>';
		
		 echo '<p>' . __( '
			<code>For theme options instead of adding writing long codes, you can use &lt;?php ot($var,[$echo=1,$default_value]); ?></code>
		', 'option-tree' ) . '</p>';
		
		 echo '<p>' . __( '
			<code>To get image by variable for theme options  &lt;?php ot_attachment($var,[$echo=1]); ?&gt;. It is useful for field "Attachement ID"</code>
		', 'option-tree' ) . '</p>
		';
		
		echo '<p>' . __( '
			<code>To get image by variable for theme options  &lt;?php ot_image($var,[$echo=1,$size]); ?&gt;. It is useful for field "Attachement ID"</code>
		', 'option-tree' ) . '</p>
		';
		
		
		 echo '<p>' . __( '
			<code>To get image by attachment id for theme options  &lt;?php  ot_attachment_by_id($var,$echo=\'\',$defaultVal=\'\'); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		echo '<p>' . __( '
			<code>To get image by attachment id for theme options  &lt;?php  ot_image_by_id($var,$echo=\'\',$defaultVal=\'\',$size= \'full\'); ?&gt;</code>
		', 'option-tree' ) . '</p>';
		
		
		 echo '<p>&nbsp; </p>'; echo '<p>&nbsp; </p>';
		 echo '<h4>'. __( 'Some extra handy functions for wordpress templating', 'rj-option-tree' ) . '</h4>';
        
        echo '<p>' . __( '
			<code>&lt;?php base_url([$echo=1]); ?&gt; shortcode [base_url] as same as bloginfo(\'url\')</code>
		', 'option-tree' ) . '</p>';
		 echo '<p>' . __( '
			<code>  &lt;?php temp_url([$echo=1]); ?> shortcode [temp_url] as same as bloginfo(\'template_url\')</code>
		', 'option-tree' ) . '</p>';
     
		 echo '<p>' . __( '
			<code>  &lt;?php page_link(array("id"=>18,"echo"=>1)); ?&gt; shortcode [page_link id=18 echo=1] as same as get_permalink(18)</code>
		', 'option-tree' ) . '</p>';
     
     
     
     // to get settings
      echo '<p>&nbsp; </p>'; echo '<p>&nbsp; </p>';
		 echo '<h4>'. __( 'To get settings info', 'rj-option-tree' ) . '</h4>';
     
     echo '<p>' . __( '
			<code>  &lt;?php ot_get_settings_label($id); ?&gt; to get the label of a settings</code>
		', 'option-tree' ) . '</p>';
     
echo '<p>' . __( '
			<code>  &lt;?php ot_get_settings_desc($id); ?&gt; to get the description of a settings</code>
		', 'option-tree' ) . '</p>';
     
echo '<p>' . __( '
			<code>  &lt;?php ot_get_settings_data($id); ?&gt; to get the all settings data of a settings</code>
		', 'option-tree' ) . '</p>';
        
echo '<p>' . __( '
			<code>  &lt;?php ot_get_meta_label($id); ?&gt; to get the metabox label of a settings</code>
		', 'option-tree' ) . '</p>';
     
echo '<p>' . __( '
			<code>  &lt;?php ot_get_meta_desc($id); ?&gt; to get the metabox description of a settings</code>
		', 'option-tree' ) . '</p>';
     
echo '<p>' . __( '
			<code>  &lt;?php ot_get_meta_data($id); ?&gt; to get the all metabox data of a settings</code>
		', 'option-tree' ) . '</p>';

        
echo '<p>' . __( '
			<code>  &lt;?php ot_user_get_meta_label($id); ?&gt; to get the user metabox label of a settings</code>
		', 'option-tree' ) . '</p>';
     
echo '<p>' . __( '
			<code>  &lt;?php ot_user_get_meta_desc($id); ?&gt; to get the user metabox description of a settings</code>
		', 'option-tree' ) . '</p>';

echo '<p>' . __( '
			<code>  &lt;?php ot_user_get_meta_data($id); ?&gt; to get the all user metabox data of a settings</code>
		', 'option-tree' ) . '</p>';

        
echo '<p>' . __( '
			<code>  &lt;?php ot_taxonomy_get_meta_label($id); ?&gt; to get the taxonomy metabox label of a settings</code>
		', 'option-tree' ) . '</p>';
     
echo '<p>' . __( '
			<code>  &lt;?php ot_taxonomy_get_meta_desc($id); ?&gt; to get the taxonomy metabox description of a settings</code>
		', 'option-tree' ) . '</p>';
     
echo '<p>' . __( '
			<code>  &lt;?php ot_taxonomy_get_meta_data($id); ?&gt; to get the all taxonomy metabox data of a settings</code>
		', 'option-tree' ) . '</p>';
     
     
 // Shortcodes
      echo '<p>&nbsp; </p>'; echo '<p>&nbsp; </p>';
		 echo '<h4>'. __( 'Shortcodes', 'rj-option-tree' ) . '</h4>';
     
     echo '<p>' . __( '
			<code>  [ot_short id="field_id" type="image"] shortcode to get optiontree settings field</code>
		', 'option-tree' ) . '</p>';
     
     echo '<p>' . __( '
			<code>  [ot_image_short id="field_id"] shortcode to get optiontree settings image field</code>
		', 'option-tree' ) . '</p>';
     
     echo '<p>' . __( '
			<code>  [ot_meta_short id="field_id" type="image"] shortcode to get optiontree meta value</code>
		', 'option-tree' ) . '</p>';
     
     echo '<p>' . __( '
			<code>  [ot_meta_image_short id="field_id"] shortcode to get meta value image url</code>
		', 'option-tree' ) . '</p>';
     
    // Shortcodes
      echo '<p>&nbsp; </p>'; echo '<p>&nbsp; </p>';
		 echo '<h4>'. __( 'Using Widget Metabox', 'rj-option-tree' ) . '</h4>';  
     
       echo '<p>' . __( '
			You can easily customise a widget using Widget template field (reccommended method).
		', 'option-tree' ) . '</p>';
     echo '<p>' . __( '
     If the above method will not suit for you, then use<br />
     <code><pre>
            //Copy this code in your functions.php or plugins file to design your widget
                function wp_widget_display_hook_{your_widget_id}($args,$instance){
                extract($args, EXTR_SKIP);
                echo $before_widget;
                
                $title = empty($instance["title"]) ? " " : apply_filters("widget_title", $instance["title"]);
                // WIDGET CODE GOES HERE
                echo "&lt;h1&gt;$title&lt;/h1&gt;";
                echo "&lt;p&gt;This is my new widget! Edit your html here. &lt;/p&gt;";
                echo $after_widget;
                }
                add_action("wp_widget_design_{your_widget_id}","wp_widget_display_hook_{your_widget_id}",10,2);
                
                // Thats all!
                // Happy editing...
                // Before pasting code replace {your_widget_id} with your widget id
                // Please note that, you are seeing this beacuse you are logged in as administator
                </pre></code>
		', 'option-tree' ) . '</p>';
     
     
		 
		 
        
        
        
      echo '</div>';
      
    echo '</div>
    
    
    
    
    ';
    
  
  }


  if(!function_exists("ot_settings_id")){
    function ot_settings_id(){
         return apply_filters( 'rj_ot_settings_id', 'option_tree_settings' );
    }
}
function rj_ot_admin_notice() {
   $ary = get_option("rj_ot_dismissed_upgrades");
        if(!is_array($ary)){
    ?>
    <div class="updated" id="rj_ot_dashboard_message">
        <p><b><?php _e( 'Do you like MetaboxUI plugin? <a href="http://rajilesh.in/metaboxui-pro/" target="_blank">Buy Pro</a> version or just <a href="https://wordpress.org/support/view/plugin-reviews/metabox-ui" target="_blank">rate this plugin</a> ', 'my-text-domain' ); ?></b><a href="javascript:void(0);" onclick="HideMetaboxUIMsg();" style="float:right;">Dismiss</a></p>
    </div>
    <script type="text/javascript">
                function HideMetaboxUIMsg(){
                    jQuery("#rj_ot_dashboard_message").slideUp();
                    jQuery.post(ajaxurl, {action:"rj_dismiss_notification", version:"6.09"});
                }
            </script>
    <?php
  }
}
add_action( 'admin_notices', 'rj_ot_admin_notice' );

add_action("wp_ajax_rj_dismiss_notification", 'rj_dismiss_notification');

 function rj_dismiss_notification(){
        $ary = get_option("rj_ot_dismissed_upgrades");
        if(!is_array($ary))
            $ary = array();

        $ary[] = $_POST["version"];
        update_option("rj_ot_dismissed_upgrades", $ary);
}

function myplugin_activate() {
        update_option("rj_ot_dismissed_upgrades", '');
}
register_activation_hook( __FILE__, 'myplugin_activate' );


function hide_ot_via_css(){
?>
<style type="text/css">
  #toplevel_page_ot-settings{
    display: none;
  }
</style>
<?php 
}
add_action('admin_head','hide_ot_via_css');



function ot_settings_view( $name, $key, $setting = array() ) {
    
    $child = ( strpos( $name, '][settings]') !== false ) ? true : false;
    $type = isset( $setting['type'] ) ? $setting['type'] : '';
    $std = isset( $setting['std'] ) ? $setting['std'] : '';
    $operator = isset( $setting['operator'] ) ? esc_attr( $setting['operator'] ) : 'and';
    
    // Serialize the standard value just incase
    if ( is_array( $std ) ) {
      $std = maybe_serialize( $std );
    }
    
    if ( in_array( $type, array( 'css', 'javascript', 'textarea', 'textarea-simple' ) ) ) {
      $std_form_element = '<textarea class="textarea" rows="10" cols="40" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][std]">' . esc_html( $std ) . '</textarea>';
    } else {
      $std_form_element = '<input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][std]" value="' . esc_attr( $std ) . '" class="widefat option-tree-ui-input" autocomplete="off" />';
    }
    
    return '
    <div class="option-tree-setting">
    
      <div class="open rjot_open">' . ( isset( $setting['label'] ) ? esc_attr( $setting['label'] ) : 'Setting ' . ( $key + 1 ) ) . '
       
       
       
        
        
      </div>
      
      <div class="rjot_open_settings">
      <div class="format-settings form_settings_rjot">
        
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Label</strong>: <span>Displayed as the label of a form element on the Theme Options page.</span>', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][label]" value="' . ( isset( $setting['label'] ) ? esc_attr( $setting['label'] ) : '' ) . '" class="widefat option-tree-ui-input option-tree-setting-title" autocomplete="off" />
            </div>
            
          </div>
          </div>
          
       
       <div class="format-settings form_settings_rjot aln_right">
        <div class="format-setting type-select wide-desc ">
            <div class="description">' . __( '<strong>Type</strong>: <span>Choose one of the available option types from the dropdown.</span>', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <select name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][type]" value="' . esc_attr( $type ) . '" class="option-tree-ui-select">
              ' . ot_loop_through_option_types( $type, $child ) . '                     
               
              </select>
            </div>
          </div>
        </div>
    </div>
      
      
      <div class="button-section">
        <a href="javascript:void(0);" class="option-tree-setting-edit option-tree-ui-button button left-item" title="' . __( 'Edit', 'option-tree' ) . '">
          <span class="icon ot-icon-pencil"></span>' . __( 'Edit', 'option-tree' ) . '
        </a>
        <a href="javascript:void(0);" class="option-tree-setting-remove option-tree-ui-button button button-secondary light right-item" title="' . __( 'Delete', 'option-tree' ) . '">
          <span class="icon ot-icon-trash-o"></span>' . __( 'Delete', 'option-tree' ) . '
        </a>
      </div>
      <div class="option-tree-setting-body">
        <!--<div class="format-settings">
        
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Label</strong>: Displayed as the label of a form element on the Theme Options page.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][label]" value="' . ( isset( $setting['label'] ) ? esc_attr( $setting['label'] ) : '' ) . '" class="widefat option-tree-ui-input option-tree-setting-title" autocomplete="off" />
            </div>
            
          </div>
        </div>-->
        
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>ID</strong>: A unique lower case alphanumeric string, underscores allowed.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][id]" value="' . ( isset( $setting['id'] ) ? esc_attr( $setting['id'] ) : '' ) . '" class="widefat option-tree-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        
        <!--<div class="format-settings">
          <div class="format-setting type-select wide-desc">
            <div class="description">' . __( '<strong>Type</strong>: Choose one of the available option types from the dropdown.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <select name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][type]" value="' . esc_attr( $type ) . '" class="option-tree-ui-select">
              ' . ot_loop_through_option_types( $type, $child ) . '                     
               
              </select>
            </div>
          </div>
        </div>
        -->
        
        <div class="format-settings">
          <div class="format-setting type-textarea wide-desc">
            <div class="description">' . __( '<strong>Description</strong>: Enter a detailed description for the users to read on the Theme Options page, HTML is allowed. This is also where you enter content for both the Textblock & Textblock Titled option types.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <textarea class="textarea" rows="10" cols="40" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][desc]">' . ( isset( $setting['desc'] ) ? esc_html( $setting['desc'] ) : '' ) . '</textarea>
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-textblock wide-desc">
            <div class="description">' . __( '<strong>Choices</strong>: This will only affect the following option types: Checkbox, Radio, Select & Select Image.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <ul class="option-tree-setting-wrap option-tree-sortable" data-name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . ']">
                ' . ( isset( $setting['choices'] ) ? ot_loop_through_choices( $name . '[' . $key . ']', $setting['choices'] ) : '' ) . '
              </ul>
              <a href="javascript:void(0);" class="option-tree-choice-add option-tree-ui-button button hug-left">' . __( 'Add Choice', 'option-tree' ) . '</a>
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-textblock wide-desc">
            <div class="description">' . __( '<strong>Settings</strong>: This will only affect the List Item option type.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <ul class="option-tree-setting-wrap option-tree-sortable" data-name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . ']">
                ' . ( isset( $setting['settings'] ) ? ot_loop_through_sub_settings( $name . '[' . $key . '][settings]', $setting['settings'] ) : '' ) . '
              </ul>
              <a href="javascript:void(0);" class="option-tree-list-item-setting-add option-tree-ui-button button hug-left">' . __( 'Add Setting', 'option-tree' ) . '</a>
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Standard</strong>: Setting the standard value for your option only works for some option types. Read the <code>OptionTree->Documentation</code> for more information on which ones.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              ' . $std_form_element . '
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Rows</strong>: Enter a numeric value for the number of rows in your textarea. This will only affect the following option types: CSS, Textarea, & Textarea Simple.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][rows]" value="' . ( isset( $setting['rows'] ) ? esc_attr( $setting['rows'] ) : '' ) . '" class="widefat option-tree-ui-input" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Post Type</strong>: Add a comma separated list of post type like \'post,page\'. This will only affect the following option types: Custom Post Type Checkbox, & Custom Post Type Select.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][post_type]" value="' . ( isset( $setting['post_type'] ) ? esc_attr( $setting['post_type'] ) : '' ) . '" class="widefat option-tree-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Taxonomy</strong>: Add a comma separated list of any registered taxonomy like \'category,post_tag\'. This will only affect the following option types: Taxonomy Checkbox, & Taxonomy Select.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][taxonomy]" value="' . ( isset( $setting['taxonomy'] ) ? esc_attr( $setting['taxonomy'] ) : '' ) . '" class="widefat option-tree-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>Min, Max, & Step</strong>: Add a comma separated list of options in the following format <code>0,100,1</code> (slide from <code>0-100</code> in intervals of <code>1</code>). The three values represent the minimum, maximum, and step options and will only affect the Numeric Slider option type.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][min_max_step]" value="' . ( isset( $setting['min_max_step'] ) ? esc_attr( $setting['min_max_step'] ) : '' ) . '" class="widefat option-tree-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . __( '<strong>CSS Class</strong>: Add and optional class to this option type.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][class]" value="' . ( isset( $setting['class'] ) ? esc_attr( $setting['class'] ) : '' ) . '" class="widefat option-tree-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-text wide-desc">
            <div class="description">' . sprintf( __( '<strong>Condition</strong>: Add a comma separated list (no spaces) of conditions in which the field will be visible, leave this setting empty to always show the field. In these examples, <code>value</code> is a placeholder for your condition, which can be in the form of %s.', 'option-tree' ), '<code>field_id:is(value)</code>, <code>field_id:not(value)</code>, <code>field_id:contains(value)</code>, <code>field_id:less_than(value)</code>, <code>field_id:less_than_or_equal_to(value)</code>, <code>field_id:greater_than(value)</code>, or <code>field_id:greater_than_or_equal_to(value)</code>' ) . '</div>
            <div class="format-setting-inner">
              <input type="text" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][condition]" value="' . ( isset( $setting['condition'] ) ? esc_attr( $setting['condition'] ) : '' ) . '" class="widefat option-tree-ui-input" autocomplete="off" />
            </div>
          </div>
        </div>
        <div class="format-settings">
          <div class="format-setting type-select wide-desc">
            <div class="description">' . __( '<strong>Operator</strong>: Choose the logical operator to compute the result of the conditions.', 'option-tree' ) . '</div>
            <div class="format-setting-inner">
              <select name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][operator]" value="' . $operator . '" class="option-tree-ui-select">
                <option value="and" ' . selected( $operator, 'and', false ) . '>' . __( 'and', 'option-tree' ) . '</option>
                <option value="or" ' . selected( $operator, 'or', false ) . '>' . __( 'or', 'option-tree' ) . '</option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    ' . ( ! $child ? '<input type="hidden" class="hidden-section" name="' . esc_attr( $name ) . '[' . esc_attr( $key ) . '][section]" value="' . ( isset( $setting['section'] ) ? esc_attr( $setting['section'] ) : '' ) . '" />' : '' );
  
  }

