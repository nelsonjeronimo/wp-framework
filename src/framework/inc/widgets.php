<?php

// Socials Widget

class the_socials_widget extends WP_Widget {

    // Set up the widget name and description.
    public function __construct() {
        $widget_options = array( 'classname' => 'socials_widget', 'description' => 'Display the Icons with links for the social networks' );
        parent::__construct( 'socials_widget', 'Socials Widget', $widget_options );
    }


    // Create the widget output.
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance[ 'title' ] );
        echo $args['before_widget'] . $args['before_title'] . $title . $args['after_title']; 
        the_socials();
        echo $args['after_widget'];
    }

    // Create the admin area widget settings form.
    public function form( $instance ) {
        $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
        <p>
        <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
        <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
        </p><?php
    }
    
    // Apply settings to the widget instance.
    public function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
        return $instance;
    }



}

// Register the widget.
function the_socials_register_socials_widget() { 
  register_widget( 'the_socials_widget' );
}

add_action( 'widgets_init', 'the_socials_register_socials_widget' );


