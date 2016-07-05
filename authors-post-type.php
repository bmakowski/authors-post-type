<?php
/*
  Plugin Name: Authors Post Type
  Plugin URI: http://404blog.pl/
  Description: Adding Authors post type
  Author: Bart Makowski
  Version: 1.0
  Author URI: http://404blog.pl/
 */

//include javascript

function apt_enqueue_scripts(){
    wp_enqueue_script('custom-js', plugin_dir_url( __FILE__ ) . '/js/custom-js.js', array('jquery'));
}
add_action( 'admin_enqueue_scripts', 'apt_enqueue_scripts' );

//Create apt_author post type//

function create_post_type() {
    register_post_type('apt_author', array(
        'labels' => array(
            'name' => __('Authors'),
            'singular_name' => __('Author'),
            'add_new' => __('Add New'),
            'all_items' => __('All Authors'),
            'add_new_item' => __('Add New Author'),
            'edit_item' => __('Edit Author'),
            'new_item' => __('New Author'),
            'view_item' => __('View Author'),
            'search_items' => __('Search Authors'),
            'not_found' => __('No Authors found'),
            'not_found_in_trash' => __('No Authors found in trash'),
            'parent_item_colon' => __('Parent Author:'),
            'menu_name' => __('Authors')
        ),
        'public' => true,
        'has_archive' => true,
        'menu_position' => 20,
        'supports' => array('thumbnail', 'revisions'),
        'rewrite' => array('slug' => 'authors'),
        )
    );
}

add_action('init', 'create_post_type');

//Flush revwite rules on plugin activation//

function my_rewrite_flush() {

    create_post_type();

    flush_rewrite_rules();
}

register_activation_hook(__FILE__, 'my_rewrite_flush');

//Add templates for custom post//

function apt_force_template($template) {
    global $post;

//    if (is_archive() && $post->post_name = 'authors') {
//        $template = dirname(__FILE__) . '/templates/archive-apt_author.php';
//    }

    if (is_singular('apt_author')) {
        $template = dirname(__FILE__) . '/templates/single-apt_author.php';
    }

    return $template;
}

add_filter('template_include', 'apt_force_template', 99);


//add metaboxes
add_action('add_meta_boxes', 'apt_author_meta_boxes');

function apt_author_meta_boxes() {
    add_meta_box(
            'apt_author_fields', 'Author Fields', 'render_meta_boxes', 'apt_author', 'normal', 'high'
    );
}

$prefix = 'apt_';
$custom_meta_fields = array(
    array(
        'label'=> 'First name',
        'desc'  => '',
        'id'    => $prefix.'first_name',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Last name',
        'desc'  => '',
        'id'    => $prefix.'last_name',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Biography',
        'desc'  => '',
        'id'    => $prefix.'biography',
        'type'  => 'textarea'
    ),
    array(
        'label'=> 'Facebook URL',
        'desc'  => '',
        'id'    => $prefix.'facebook',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Linkedin URL',
        'desc'  => '',
        'id'    => $prefix.'linkedin',
        'type'  => 'text'
    ),
    array(
        'label'=> 'Google+ URL',
        'desc'  => '',
        'id'    => $prefix.'google',
        'type'  => 'text'
    ),
    array(
        'label'=> 'WordPress User',
        'desc'  => '',
        'id'    => $prefix.'wordpress_user',
        'type'  => 'select',
        'options' => get_all_wp_users()
    ),
    array(
        'label'  => 'Author’s image',
        'desc'  => '',
        'id'    => $prefix.'authors_image',
        'type'  => 'image'
    ),
    array(
        'label' => 'Gallery',
        'desc'  => 'Insert images ids in order you want to display '
        . 'them. Use "," as separator. Use "Set gallery" link to choose images.',
        'id'    => $prefix.'gallery',
        'type'  => 'gallery'
    )
);

function get_all_wp_users(){
    $array = array(
        0 => array(
            'label' => 'None',
            'value' => 0
        )
    );
    
    $users = get_users(array(
        'orderby'      => 'login',
	'order'        => 'ASC'
    ));
    
    foreach ( $users as $user ){
        $array[$user->ID] = array(
            'label' => 'id:' . $user->ID . ' - ' . $user->display_name,
            'value' => $user->ID
        );
    }

    return $array;
}

function render_meta_boxes() {

    global $custom_meta_fields, $post;
    
    echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

    // Begin the field table and loop
    echo '<table class="form-table">';
    foreach ($custom_meta_fields as $field) {
        // get value of this field if it exists for this post
        $meta = get_post_meta($post->ID, $field['id'], true);
        // begin a table row with
        echo '<tr>
                <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                <td>';
                switch($field['type']) {
                    // case items will go here
                    case 'text':
                        echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30" />
                            <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    case 'textarea':
                        echo '<textarea name="'.$field['id'].'" id="'.$field['id'].'" cols="60" rows="4">'.$meta.'</textarea>
                            <br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    case 'select':
                        echo '<select name="'.$field['id'].'" id="'.$field['id'].'">';
                        foreach ($field['options'] as $option) {
                            echo '<option', $meta == $option['value'] ? ' selected="selected"' : '', ' value="'.$option['value'].'">'.$option['label'].'</option>';
                        }
                        echo '</select><br /><span class="description">'.$field['desc'].'</span>';
                    break;
                    case 'image':
                            // Get WordPress' media upload URL
                            $upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );

                            // See if there's a media id already saved as post meta
                            $image_id = get_post_meta( $post->ID, $field['id'], true );

                            // Get the image src
                            $image_id_src = wp_get_attachment_image_src( $image_id, 'full' );

                            // For convenience, see if the array is valid
                            $you_have_img = is_array( $image_id_src );
                            ?>

                            <!-- Your image container, which can be manipulated with js -->
                            <div class="custom-img-container">
                                <?php if ( $you_have_img ) : ?>
                                    <img src="<?php echo $image_id_src[0] ?>" alt="" style="max-width:100%;" />
                                <?php endif; ?>
                            </div>

                            <!-- Your add & remove image links -->
                            <p class="hide-if-no-js">
                                <a class="upload-custom-img <?php if ( $you_have_img  ) { echo 'hidden'; } ?>" 
                                   href="<?php echo $upload_link ?>">
                                    <?php _e('Set custom image') ?>
                                </a>
                                <a class="delete-custom-img <?php if ( ! $you_have_img  ) { echo 'hidden'; } ?>" 
                                  href="#">
                                    <?php _e('Remove this image') ?>
                                </a>
                            </p>

                            <input class="custom-img-id" name="custom-img-id" type="hidden" value="<?php echo esc_attr( $image_id ); ?>" />
                            <?php
                    break;
                    case 'gallery':
                        // Get WordPress' media upload URL
                        $upload_link = esc_url( get_upload_iframe_src( 'image', $post->ID ) );

                        // See if there's a media id already saved as post meta
                        $gallery_meta = get_post_meta( $post->ID, $field['id'], true );
                       
                        ?>

                        <div class="custom-gallery-container">
                            <input type="text" class="custom-gallery-input" name="<?php echo $field['id']; ?>"value="<?php echo esc_attr($gallery_meta); ?>"/>
                        </div>

                        <p class="hide-if-no-js">
                            <a class="setup-gallery <?php if ( $gallery_meta  ) { echo 'hidden'; } ?>" 
                               href="<?php echo $upload_link ?>">
                                <?php _e('Set gallery') ?>
                            </a>
                            <a class="clear-gallery <?php if ( ! $gallery_meta  ) { echo 'hidden'; } ?>" 
                              href="#">
                                <?php _e('Clear gallery') ?>
                            </a>
                        </p>
                        <span class="description"><?php echo $field['desc']; ?></span>

                        <?php
                        break;
                } //end switch
        echo '</td></tr>';
    } // end foreach
    echo '</table>'; // end table
}

// Save the Data
function save_custom_meta($post_id) {
    global $custom_meta_fields, $prefix;
    
    // verify nonce
    if (!isset($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) {
        return $post_id;
    }
   
    // check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return $post_id;
    
    // check permissions
    if ('page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id))
            return $post_id;
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
    }
     
    // loop through fields and save the data
    foreach ($custom_meta_fields as $field) {
        $old = get_post_meta($post_id, $field['id'], true);
        $new = $_POST[$field['id']];
        if ($new && $new != $old) {
            update_post_meta($post_id, $field['id'], $new);
        } elseif ('' == $new && $old) {
            delete_post_meta($post_id, $field['id'], $old);
        }
    } 
    
}
add_action('save_post', 'save_custom_meta');

function apt_update_title( $data , $postarr ) {
    $title = '';
  // do something with the post data
    if($data['post_type'] == 'apt_author') { //apply this only to apt_author and only if first name was submitted
        if(!empty($_POST['apt_first_name'])){
        $title = $_POST['apt_first_name'];
        }
        if(!empty($_POST['apt_last_name'])){
            $title .= ' - ' . $_POST['apt_last_name'];
        }
        if($title == ''){
            $title = 'Author - ' . date("d/m/Y G:i:s");
        }
        $data['post_title'] =  $title ; //Update post title to new title.
    }
    if ( ! in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft' ) ) && $title != '' ) {       
        $data['post_name'] = sanitize_title( $title );
    }
    
    return $data; // Returns modified data.
}

add_filter( 'wp_insert_post_data', 'apt_update_title', '99', 2 );
