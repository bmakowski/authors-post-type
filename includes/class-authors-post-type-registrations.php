<?php

class Authors_Post_Type_Registrations {

    public $post_type = 'apt_author';


    public function init() {
        // Add the team post type and taxonomies
        add_action('init', array($this, 'register'));
    }

    /**
     * Initiate registration of post type 
     * 
     */
    public function register() {
        $this->create_post_type();
    }

    /**
     * Register the custom post type.
     *
     */
    protected function create_post_type() {
        register_post_type($this->post_type, array(
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
}
