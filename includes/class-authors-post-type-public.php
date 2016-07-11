<?php

class Authors_Post_Type_Public {

	protected $registration_handler;

	public function __construct( $registration_handler ) {
		$this->registration_handler = $registration_handler;
	}

	public function init() {

                //Force template use
                add_filter('template_include', array($this, 'apt_force_template'), 99);

	}

	function apt_force_template($template) {
        //    Uncomment this code when you want to customize archive page
        //    global $post;
        //    if (is_archive() && $post->post_name = 'authors') {
        //        $template = dirname(__FILE__) . '/templates/archive-apt_author.php';
        //    }

            if (is_singular('apt_author')) {
                $template = APT_PLUGIN_PATH . '/templates/single-apt_author.php';
            }

            return $template;
        }

}
