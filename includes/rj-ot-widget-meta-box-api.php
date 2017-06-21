<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
  
if ( ! class_exists( 'RJ_OT_Widget_Meta_Box' ) ) {
$classes = '';
  class RJ_OT_Widget_Meta_Box {
  
    /* variable to store the meta box array */
    private $meta_box;
    private $widget_class;
  
    /**
     * PHP5 constructor method.
     *
     * This method adds other methods of the class to specific hooks within WordPress.
     *
     * @uses      add_action()
     *
     * @return    void
     *
     * @access    public
     * @since     1.0
     */
    function __construct( $meta_box ) {
      
      

      global $ot_meta_boxes;
	  

      if ( ! isset( $ot_meta_boxes ) ) {
        $ot_meta_boxes = array();
      }

      $ot_meta_boxes[] = $meta_box;

      $this->meta_box = $meta_box;

      //add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );

      //add_action( 'save_post', array( $this, 'save_meta_box' ), 1, 2 );
        
   // add_action( 'widgets_init', array( $this, 'add_widgets' ) );
        
        
	
  //add_action( 'init', $this->add_widgets($this->meta_box));
  //add_action( 'init', array($this,'add_widgets'));
    }
    
    
    
    /**
     * Adds meta box to any post type
     *
     * @uses      add_meta_box()
     *
     * @return    void
     *
     * @access    public
     * @since     1.0
     */
    
    public function add_widgets($widget_data=array()) {
      $widget_data = $this->meta_box;
        global $page_now;
        if(is_admin()){
        rj_ot_admin_scriptss();
		rj_ot_admin_styless();
        }
        $widget_class_name = 'Dynamic_Widget_'.$widget_data['id'];
        $widget_datas = var_export($widget_data, true);
        
        
       $dynamic_class = '
       if(!class_exists("' . $widget_class_name . '")){
            class ' . $widget_class_name . ' extends WP_Widget {
            
               public $widget_datas =  '.$widget_datas.';
                function ' . $widget_class_name . '() {
                
                    $widget_ops = array("classname" => "' . $widget_class_name . '"
                                        , "description" => "' . $widget_data["description"] . '" );
                      
                    parent::__construct( "' . $widget_class_name . '", "' . $widget_data["title"] . '", $widget_ops );        
                }

                function form($instance) {
                
               
               $dynamic_instances = array("title"=>"");
               $fields = $this->widget_datas["fields"];
               if(!empty($fields)){
                   foreach( $fields as  $field ){
                   $dynamic_instances[$field["id"]]=$field["std"];
                   }
               }
               
               
                    $instance = wp_parse_args( (array) $instance, $dynamic_instances );
                    
                   $field_data = array();
                   $field_keys = array();
                   foreach($dynamic_instances as $key=>$val){
                  $field_data[$this->get_field_name($key)] = $instance[$key];
                  $field_keys[$key] = $this->get_field_name($key);
                   }
                    
                    $title = $instance["title"];
                    echo "<p><label for=\"" . $this->get_field_id("title") . "\">Title: <input class=\"widefat\" id=\"";
                    echo $this->get_field_id("title") . "\" name=\"" . $this->get_field_name("title") . "\" type=\"text\" value=\"" . attribute_escape($title) . "\" /></label></p>";
                    RJ_OT_Widget_Meta_Box::build_widget_meta_box("",$this->widget_datas,$instance,$field_data,$field_keys);
                    
                    
                }

                function update($new_instance, $old_instance) {
                $instance = $old_instance;
                 $dynamic_instances[0] = array("id"=>"title");
                $fields = $this->widget_datas["fields"];
                 $fields = array_merge( $fields,$dynamic_instances);
                
               if(!empty($fields)){
                   foreach( $fields as  $field ){
                    $fid = $field["id"];
                    
                    $instance[$fid] = $new_instance[$fid];
                   }
               }
               
                
                    return $instance;
                }

                function widget($args, $instance) {
                $data = array_merge($args,$instance);
                $widget_template = $this->widget_datas["widget_template"];
                $engine = new TemplateEngine();
                if(has_action("wp_widget_design_'.$widget_data['id'].'")) {
                 do_action("wp_widget_design_'.$widget_data['id'].'",$args,$instance);
                }else{ 
                    echo do_shortcode($engine->process($widget_template, $data));
                
                }
                  
                }
                
               
            };
            }
        ';
        return $dynamic_class;
       // eval($dynamic_class);
        //register_widget($widget_class_name);

   
		
        
    }
	




    
    /**
     * Meta box view
     *
     * @return    string
     *
     * @access    public
     * @since     1.0
     */
    function build_widget_meta_box( $post='', $metabox='' ,$fnames,$fdata,$fkeys) {
        $new_fkeys = $fkeys;
      
        unset($new_fkeys['title']);
        
       
        
      echo '<div class="ot-metabox-wrapper">';

        /* Use nonce for verification */
        echo '<input type="hidden" name="' . $metabox['id'] . '_nonce" value="' . wp_create_nonce( $metabox['id'] ) . '" />';
        
        /* meta box description */
        echo isset( $metabox['desc'] ) && ! empty( $metabox['desc'] ) ? '<div class="description" style="padding-top:10px;">' . htmlspecialchars_decode( $metabox['desc'] ) . '</div>' : '';
      
        /* loop through meta box fields */
        foreach ( $metabox['fields'] as $field ) {
        
          /* get current post meta data */
          $field_name = $new_fkeys[$field['id']];
          $field_value = $fdata[$field_name];
          
          /* set standard value */
          if ( isset( $field['std'] ) ) {  
            $field_value = ot_filter_std_value( $field_value, $field['std'] );
          }
          
          /* build the arguments array */
          $_args = array(
            'type'              => $field['type'],
            'field_id'          => $field_name,
            'field_name'        => $field_name,
            'field_value'       => $field_value,
            'field_desc'        => isset( $field['desc'] ) ? $field['desc'] : '',
            'field_std'         => isset( $field['std'] ) ? $field['std'] : '',
            'field_rows'        => isset( $field['rows'] ) && ! empty( $field['rows'] ) ? $field['rows'] : 10,
            'field_post_type'   => isset( $field['post_type'] ) && ! empty( $field['post_type'] ) ? $field['post_type'] : 'post',
            'field_taxonomy'    => isset( $field['taxonomy'] ) && ! empty( $field['taxonomy'] ) ? $field['taxonomy'] : 'category',
            'field_min_max_step'=> isset( $field['min_max_step'] ) && ! empty( $field['min_max_step'] ) ? $field['min_max_step'] : '0,100,1',
            'field_class'       => isset( $field['class'] ) ? $field['class'] : '',
            'field_condition'   => isset( $field['condition'] ) ? $field['condition'] : '',
            'field_operator'    => isset( $field['operator'] ) ? $field['operator'] : 'and',
            'field_choices'     => isset( $field['choices'] ) ? $field['choices'] : array(),
            'field_settings'    => isset( $field['settings'] ) && ! empty( $field['settings'] ) ? $field['settings'] : array(),
            'post_id'           => $post->ID,
            'meta'              => true
          );
          
          $conditions = '';
          
          /* setup the conditions */
          if ( isset( $field['condition'] ) && ! empty( $field['condition'] ) ) {
  
            $conditions = ' data-condition="' . $field['condition'] . '"';
            $conditions.= isset( $field['operator'] ) && in_array( $field['operator'], array( 'and', 'AND', 'or', 'OR' ) ) ? ' data-operator="' . $field['operator'] . '"' : '';
  
          }
          
          /* only allow simple textarea due to DOM issues with wp_editor() */
          if ( apply_filters( 'ot_override_forced_textarea_simple', false, $field['id'] ) == false && $_args['type'] == 'textarea' )
            $_args['type'] = 'textarea-simple';

          // Build the setting CSS class
          if ( ! empty( $_args['field_class'] ) ) {
            
            $classes = explode( ' ', $_args['field_class'] );

            foreach( $classes as $key => $value ) {
            
              $classes[$key] = $value . '-wrap';
              
            }

            $class = 'format-settings ' . implode( ' ', $classes );
            
          } else {
          
            $class = 'format-settings';
            
          }
          
          /* option label */
          echo '<div id="setting_' . $field['id'] . '" class="' . $class . '"' . $conditions . '>';
            
            echo '<div class="format-setting-wrap">';
            
              /* don't show title with textblocks */
              if ( $_args['type'] != 'textblock' && ! empty( $field['label'] ) ) {
                echo '<div class="format-setting-label">';
                  echo '<label for="' . $field['id'] . '" class="label">' . $field['label'] . '</label>';
                echo '</div>';
              }
              /* get the option HTML */
              echo ot_display_by_type( $_args );
              
            echo '</div>';
            
          echo '</div>';
          
        }

        echo '<div class="clear"></div>';
      
      echo '</div>';

    }
    
    
    /**
     * Saves the meta box values
     *
     * @return    void
     *
     * @access    public
     * @since     1.0
     */
    /**
     * Saves the meta box values
     *
     * @return    void
     *
     * @access    public
     * @since     1.0
     */
    function save_meta_box( $post_id, $post_object ) {
      global $pagenow;

      /* don't save if $_POST is empty */
      if ( empty( $_POST ) || ( isset( $_POST['vc_inline'] ) && $_POST['vc_inline'] == true ) )
        return $post_id;
      
      /* don't save during quick edit */
      if ( $pagenow == 'admin-ajax.php' )
        return $post_id;
        
      /* don't save during autosave */
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        return $post_id;

      /* don't save if viewing a revision */
      if ( $post_object->post_type == 'revision' || $pagenow == 'revision.php' )
        return $post_id;
  
      /* verify nonce */
      if ( isset( $_POST[ $this->meta_box['id'] . '_nonce'] ) && ! wp_verify_nonce( $_POST[ $this->meta_box['id'] . '_nonce'], $this->meta_box['id'] ) )
        return $post_id;
    
      /* check permissions */
      if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_page', $post_id ) )
          return $post_id;
      } else {
        if ( ! current_user_can( 'edit_post', $post_id ) )
          return $post_id;
      }
      
      foreach ( $this->meta_box['fields'] as $field ) {
        
        $old = get_post_meta( $post_id, $field['id'], true );
        $new = '';
        
        /* there is data to validate */
        if ( isset( $_POST[$field['id']] ) ) {
        
          /* slider and list item */
          if ( in_array( $field['type'], array( 'list-item', 'slider' ) ) ) {
              
            /* required title setting */
            $required_setting = array(
              array(
                'id'        => 'title',
                'label'     => __( 'Title', 'option-tree' ),
                'desc'      => '',
                'std'       => '',
                'type'      => 'text',
                'rows'      => '',
                'class'     => 'option-tree-setting-title',
                'post_type' => '',
                'choices'   => array()
              )
            );
            
            /* get the settings array */
            $settings = isset( $_POST[$field['id'] . '_settings_array'] ) ? unserialize( ot_decode( $_POST[$field['id'] . '_settings_array'] ) ) : array();
            
            /* settings are empty for some odd ass reason get the defaults */
            if ( empty( $settings ) ) {
              $settings = 'slider' == $field['type'] ? 
              ot_slider_settings( $field['id'] ) : 
              ot_list_item_settings( $field['id'] );
            }
            
            /* merge the two settings array */
            $settings = array_merge( $required_setting, $settings );
            
            foreach( $_POST[$field['id']] as $k => $setting_array ) {
  
              foreach( $settings as $sub_setting ) {
                
                /* verify sub setting has a type & value */
                if ( isset( $sub_setting['type'] ) && isset( $_POST[$field['id']][$k][$sub_setting['id']] ) ) {
                  
                  $_POST[$field['id']][$k][$sub_setting['id']] = ot_validate_setting( $_POST[$field['id']][$k][$sub_setting['id']], $sub_setting['type'], $sub_setting['id'] );
                  
                }
                
              }
            
            }
            
            /* set up new data with validated data */
            $new = $_POST[$field['id']];
          
          } else if ( $field['type'] == 'social-links' ) {
            
            /* get the settings array */
            $settings = isset( $_POST[$field['id'] . '_settings_array'] ) ? unserialize( ot_decode( $_POST[$field['id'] . '_settings_array'] ) ) : array();
            
            /* settings are empty get the defaults */
            if ( empty( $settings ) ) {
              $settings = ot_social_links_settings( $field['id'] );
            }
            
            foreach( $_POST[$field['id']] as $k => $setting_array ) {
  
              foreach( $settings as $sub_setting ) {
                
                /* verify sub setting has a type & value */
                if ( isset( $sub_setting['type'] ) && isset( $_POST[$field['id']][$k][$sub_setting['id']] ) ) {
                  
                  $_POST[$field['id']][$k][$sub_setting['id']] = ot_validate_setting( $_POST[$field['id']][$k][$sub_setting['id']], $sub_setting['type'], $sub_setting['id'] );
                  
                }
                
              }
            
            }
            
            /* set up new data with validated data */
            $new = $_POST[$field['id']];

          } else {
            
            /* run through validattion */
            $new = ot_validate_setting( $_POST[$field['id']], $field['type'], $field['id'] );
            
          }
          
          /* insert CSS */
          if ( $field['type'] == 'css' ) {
            
            /* insert CSS into dynamic.css */
            if ( '' !== $new ) {
              
              ot_insert_css_with_markers( $field['id'], $new, true );
            
            /* remove old CSS from dynamic.css */
            } else {
            
              ot_remove_old_css( $field['id'] );
              
            }
          
          }
        
        }
        
        if ( isset( $new ) && $new !== $old ) {
          update_post_meta( $post_id, $field['id'], $new );
        } else if ( '' == $new && $old ) {
          delete_post_meta( $post_id, $field['id'], $old );
        }
      }
  
    }
  
  }

}

/**
 * This method instantiates the meta box class & builds the UI.
 *
 * @uses     OT_Meta_Box()
 *
 * @param    array    Array of arguments to create a meta box
 * @return   void
 *
 * @access   public
 * @since    2.0
 */
if ( ! function_exists( 'rj_ot_widget_register_meta_box' ) ) {

  function rj_ot_widget_register_meta_box( $args ) {
    
      if ( ! $args ){
      return;
      }
      
 $ot_meta_box = new RJ_OT_Widget_Meta_Box( $args );
 $ot_meta_box_widget = $ot_meta_box->add_widgets();
 //echo $ot_meta_box_widget;
 eval($ot_meta_box_widget);
 register_widget('Dynamic_Widget_'.$args['id']);
// return $ot_meta_box_widget;
      
  }

}

/* End of file ot-meta-box-api.php */
/* Location: ./includes/ot-meta-box-api.php */

   
class TemplateEngine {
    function showVariable($name) {
        
       if (isset($this->data[$name])) {
            echo $this->data[$name];
        } else {
            echo '{' . $name . '}';
        }
    }
     function showAttachmentURL($name) {
          $name = str_replace(':url','',$name);
           if (isset($this->data[$name])) {
               echo wp_get_attachment_url($this->data[$name]);
           }else {
            echo '{' . $name . '}';
        }
     }
    function wrap($element) {
        $this->stack[] = $this->data;
        foreach ($element as $k => $v) {
            $this->data[$k] = $v;
        }
    }
    function unwrap() {
        $this->data = array_pop($this->stack);
    }
    function run() {
        ob_start ();
        eval (func_get_arg(0));
        return ob_get_clean();
    }
    function process($template, $data) {
        $this->data = $data;
        $this->stack = array();
        
        
        $template = str_replace('<', '<?php echo \'<\'; ?>', $template);
        
        // to replace all variable inside curly braces
        $template = preg_replace('~\{(\w+)\}~', '<?php $this->showVariable(\'$1\'); ?>', $template);
        
        // to replace url
        $template = preg_replace('~\{(\w+):url\}~', '<?php $this->showAttachmentURL(\'$1\'); ?>', $template);
        $template = preg_replace('~\{loop:(\w+)\}~', '<?php 
        if(!empty($this->data[\'$1\'])){
        foreach ($this->data[\'$1\'] as $ELEMENT): $this->wrap($ELEMENT); ?>', $template);
        $template = preg_replace('~\{endloop:(\w+)\}~', '<?php $this->unwrap(); endforeach;
} ?>', $template);
        
        
         $template = preg_replace('~\{if:(\w+)\}~', '<?php 
        if(!empty($this->data[\'$1\'])){
         ?>', $template);
        $template = preg_replace('~\{else:(\w+)\}~', '<?php  } else{ ?>', $template);
        $template = preg_replace('~\{endif:(\w+)\}~', '<?php 
} ?>', $template);
        $template = '?>' . $template;
        
      
        
     
        return $this->run($template);
    }
}