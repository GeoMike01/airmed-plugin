<?php
// Creating the widget 
class wp_am_listing_widget extends WP_Widget {
  
  function __construct() {
    parent::__construct(
    
      // Base ID of your widget
      'wp_am_listing_widget', 
    
      // Widget name will appear in UI
      __('WPBeginner Widget', 'wpb_widget_domain'), 
    
      // Widget description
      array( 'description' => __( 'Sample widget based on WPBeginner Tutorial', 'wpb_widget_domain' ), ) 
    );
  }
    
  // Creating widget front-end
  public function widget( $args, $instance ) {
    extract( $args );
    $title = apply_filters( 'widget_title', $instance['title'] );
    
    // WordPress core before_widget hook (always include )
    echo $before_widget;
    
    // Display the widget
    echo '<div class="widget-text wp_widget_plugin_box">';
    
    // Display title
    if ( ! empty( $title ) )
    echo $before_title . $title . $after_title;
      
    // This is where you run the code and display the output
    echo __( 'Airmed Listing Test', 'wpb_widget_domain' );
    
    
    echo '</div>';
    
    // WordPress core after_widget hook (always include )
    echo $after_widget;
    
  }
              
  // Widget Backend 
  public function form( $instance ) {
    if ( isset( $instance[ 'title' ] ) ) {
      $title = $instance[ 'title' ];
    }
    else {
      $title = __( 'New title', 'wpb_widget_domain' );
    }
    if ( isset( $instance[ 'list_columns' ] ) ) {
      $list_columns = $instance[ 'list_columns' ];
    }
    else {
      $list_columns = '4';
    }


    // Widget admin form
    ?>
    <p>
    <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
    <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php // Number of columns ?>
    <p>
		<label for="<?php echo $this->get_field_id( 'list_columns' ); ?>"><?php _e( 'Columns', 'text_domain' ); ?></label>
		<select name="<?php echo $this->get_field_name( 'list_columns' ); ?>" id="<?php echo $this->get_field_id( 'list_columns' ); ?>" class="widefat">
		<?php
		// Your options array
		$options = array(
			'option_2' => __( '2', 'text_domain' ),
			'option_3' => __( '3', 'text_domain' ),
			'option_4' => __( '4', 'text_domain' ),
		);

		// Loop through options and add each one to the select dropdown
		foreach ( $options as $key => $name ) {
			echo '<option value="' . esc_attr( $key ) . '" id="' . esc_attr( $key ) . '" '. selected( $list_columns, $key, false ) . '>'. $name . '</option>';

		} ?>
		</select>
	</p>

    <?php 
  }
        
  // Updating widget replacing old instances with new
  public function update( $new_instance, $old_instance ) {
    //$instance = array();
    $instance = $old_instance;
    $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
    $instance['list_columns'] = ( ! empty( $new_instance['list_columns'] ) ) ? strip_tags( $new_instance['list_columns'] ) : '';
    return $instance;
  }
 
// Class wpb_widget ends here
} 
 
 
// Register and load the widget
function wp_am_listing_load_widget() {
  register_widget( 'wp_am_listing_widget' );
}
add_action( 'widgets_init', 'wp_am_listing_load_widget' );

?>