<?php

class Authors_Post_Type_Admin {

	protected $registration_handler;

	public function __construct( $registration_handler ) {
		$this->registration_handler = $registration_handler;
	}

	public function init() {

                //Add js file
                add_action( 'admin_enqueue_scripts', array( $this, 'apt_enqueue_scripts' ) );
                
                //Filter post title
                add_filter( 'wp_insert_post_data', array($this, 'apt_update_title'), '99', 2 );

	}

	public function apt_enqueue_scripts(){
            wp_enqueue_style( 'apt-styles', APT_PLUGIN_URL . '/css/apt-styles.css');
            
            wp_enqueue_script('jquery-validate', APT_PLUGIN_URL . 'js/jquery.validate.min.js', array('jquery'));
            wp_enqueue_script('custom-js', APT_PLUGIN_URL . 'js/custom-js.js', array('jquery'));
        }
        
        public function apt_update_title( $data , $postarr ) {
            $title = '';

            if($data['post_type'] == 'apt_author') { //apply this only to apt_author and only if first name was submitted
                if(!empty($_POST['apt_first_name'])){
                $title = $_POST['apt_first_name'];
                }
                if(!empty($_POST['apt_last_name'])){
                    $title .= ' - ' . $_POST['apt_last_name'];
                }
                $data['post_title'] =  $title ; //Update post title to new title.
            }
            if ( ! in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft', 'trash' ) ) && $title != '' ) {       
                $data['post_name'] = sanitize_title( $title );
            }

            return $data; // Returns modified data.
        }

}
