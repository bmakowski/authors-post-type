<?php
/*
  Plugin Name: Authors Post Type
  Plugin URI: http://404blog.pl/
  Description: Adding Authors post type
  Author: Bart Makowski
  Version: 1.0
  Author URI: http://404blog.pl/
 */

//called directly - abort
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'APT_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'APT_PLUGIN_PATH', dirname(__FILE__) );

// Required files for registering the post type and taxonomies.
require plugin_dir_path( __FILE__ ) . 'includes/class-authors-post-type.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-authors-post-type-registrations.php';
require plugin_dir_path( __FILE__ ) . 'includes/class-authors-post-type-metaboxes.php';

// Instantiate registration class, so we can add it as a dependency to main plugin class.
$post_type_registrations = new Authors_Post_Type_Registrations();

// Instantiate main plugin file, so activation callback does not need to be static.
$post_type = new Authors_Post_Type( $post_type_registrations );

// Register callback that is fired when the plugin is activated.
register_activation_hook( __FILE__, array( $post_type, 'activate' ) );

// Initialize registrations for post-activation requests.
$post_type_registrations->init();

// Initialize metaboxes
$post_type_metaboxes = new Authors_Post_Type_Metaboxes;
$post_type_metaboxes->init();

if ( is_admin() ) {
    
    require plugin_dir_path( __FILE__ ) . 'includes/class-authors-post-type-admin.php';

    $post_type_admin = new Authors_Post_Type_Admin( $post_type_registrations );
    $post_type_admin->init();
}

require plugin_dir_path( __FILE__ ) . 'includes/class-authors-post-type-public.php';

$post_type_public = new Authors_Post_Type_Public( $post_type_registrations );
$post_type_public->init();




