<?php

class Authors_Post_Type_Metaboxes {
    
    public static $apt_author_meta_fields;

    public function init() {
                $this->init_fields();
		add_action( 'add_meta_boxes', array( $this, 'apt_author_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_meta_boxes' ),  10, 2 );
	}

        public function init_fields(){
            self::$apt_author_meta_fields = array(
                array(
                    'label'=> 'First name',
                    'desc'  => '',
                    'id'    => 'apt_first_name',
                    'type'  => 'text',
                    'required' => true
                ),
                array(
                    'label'=> 'Last name',
                    'desc'  => '',
                    'id'    => 'apt_last_name',
                    'type'  => 'text',
                    'required' => true
                ),
                array(
                    'label'=> 'Biography',
                    'desc'  => '',
                    'id'    => 'apt_biography',
                    'type'  => 'textarea'
                ),
                array(
                    'label'=> 'Facebook URL',
                    'desc'  => '',
                    'id'    => 'apt_facebook',
                    'type'  => 'url'
                ),
                array(
                    'label'=> 'Linkedin URL',
                    'desc'  => '',
                    'id'    => 'apt_linkedin',
                    'type'  => 'url'
                ),
                array(
                    'label'=> 'Google+ URL',
                    'desc'  => '',
                    'id'    => 'apt_google',
                    'type'  => 'url'
                ),
                array(
                    'label'=> 'WordPress User',
                    'desc'  => '',
                    'id'    => 'apt_wordpress_user',
                    'type'  => 'select',
                    'options' => $this->_get_all_wp_users_for_select()
                ),
                array(
                    'label'  => 'Author’s image',
                    'desc'  => '',
                    'id'    => 'apt_authors_image',
                    'type'  => 'image'
                ),
                array(
                    'label' => 'Gallery',
                    'desc'  => 'Insert images ids in order you want to display '
                    . 'them. Use "," as separator. Use "Set gallery" link to choose images.',
                    'id'    => 'apt_gallery',
                    'type'  => 'gallery'
                )
            );
        }

        private function _get_all_wp_users_for_select(){
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
        
	/**
	 * Register the metaboxes
	 *
	 */
        public function apt_author_meta_boxes() {
            add_meta_box(
                    'apt_author_fields', 'Author Fields', array( $this, 'render_meta_boxes' ), 'apt_author', 'normal', 'high'
            );
        }
        
         /**
	* Render HTML for the fields
	*
	*/
	function render_meta_boxes() {

            global $post;

            echo '<input type="hidden" name="custom_meta_box_nonce" value="'.wp_create_nonce(basename(__FILE__)).'" />';

            // Begin the field table and loop
            echo '<table class="form-table">';
            foreach (self::$apt_author_meta_fields as $field) {
                // get value of this field if it exists for this post
                $meta = get_post_meta($post->ID, $field['id'], true);
                // begin a table row with
                echo '<tr>
                        <th><label for="'.$field['id'].'">'.$field['label'].'</label></th>
                        <td>';
                        switch($field['type']) {
                            // case items will go here
                            case 'text':
                                echo '<input type="text" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30"'
                                    . ((isset($field['required']) && $field['required']) ? ' required="required"' : '')
                                    . '/>
                                    <br /><span class="description">'.$field['desc'].'</span>';
                            break;
                            case 'url':
                                echo '<input type="url" name="'.$field['id'].'" id="'.$field['id'].'" value="'.$meta.'" size="30"'
                                    . ((isset($field['required']) && $field['required']) ? ' required="required"' : '')
                                    . '/>
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

                                    <input class="custom-img-id" name="<?php echo $field['id'];?>" type="hidden" value="<?php echo esc_attr( $image_id ); ?>" />
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

   /**
	* Save metaboxes
	*
	* @since 0.1.0
	*/

        
        function save_meta_boxes($post_id) {
            global $post;

            $custom_meta_fields = self::$apt_author_meta_fields;
            
            // verify nonce
            if (!isset($_POST['custom_meta_box_nonce']) || !wp_verify_nonce($_POST['custom_meta_box_nonce'], basename(__FILE__))) {
                return $post_id;
            }
            
            // Check Autosave
            if ( (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) || ( defined('DOING_AJAX') && DOING_AJAX) || isset($_REQUEST['bulk_edit']) ) {
                    return $post_id;
            }

            // Don't save if only a revision
            if ( isset( $post->post_type ) && $post->post_type == 'revision' ) {
                    return $post_id;
            }

            // Check permissions
            if ( !current_user_can( 'edit_post', $post->ID ) ) {
                    return $post_id;
            }

            // loop through fields and save the data
            foreach ($custom_meta_fields as $field) {
                $value = $_POST[$field['id']];
                update_post_meta($post_id, $field['id'], $value);
            }
        }

}
