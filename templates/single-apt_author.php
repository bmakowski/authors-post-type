<?php

get_header(); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main" role="main">
            <header class="entry-header">
		<h1 class="entry-title">Author</h1>
            </header><!-- .entry-header -->
            <?php
                global $post;
            ?>
            <table class="apt-author-table">
                <tr>
                    <td>First Name:</td>
                    <td><?php echo get_post_meta($post->ID, 'apt_first_name', true );?></td>
                </tr>
                <tr>
                    <td>Last Name:</td>
                    <td><?php echo get_post_meta($post->ID, 'apt_last_name', true );?></td>
                </tr>
                <tr>
                    <td>Biography:</td>
                    <td><?php echo get_post_meta($post->ID, 'apt_biography', true );?></td>
                </tr>
                <tr>
                    <td>Facebook URL:</td>
                    <td><a href="<?php echo get_post_meta($post->ID, 'apt_facebook', true );?>"><?php echo get_post_meta($post->ID, 'apt_facebook', true );?></a></td>
                </tr>
                <tr>
                    <td>Linkedin URL:</td>
                    <td><a href="<?php echo get_post_meta($post->ID, 'apt_linkedin', true );?>"><?php echo get_post_meta($post->ID, 'apt_linkedin', true );?></a></td>
                </tr>
                <tr>
                    <td>Google+ URL:</td>
                    <td><a href="<?php echo get_post_meta($post->ID, 'apt_google', true );?>"><?php echo get_post_meta($post->ID, 'apt_google', true );?></a></td>
                </tr>
                <tr>
                    <td>Author’s image:</td>
                    <td><?php 
                            $image_id = get_post_meta($post->ID, 'apt_authors_image', true );
                            if($image_id){
                                $image_src = wp_get_attachment_image_src( $image_id, 'full' );
                        ?>
                                <img src="<?php echo $image_src[0]; ?>" />
                        <?php
                            }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>Gallery:</td>
                    <td><?php 
                            $gallery_ids = get_post_meta($post->ID, 'apt_gallery', true );
                            if(!empty($gallery_ids)){
                                $gallery_shortcode = '[gallery ids="' . implode( ",", $gallery_ids )  . '"]';
                                echo do_shortcode( $gallery_shortcode );
                            }
                        ?> 
                    </td>
                </tr>
            </table>
            <?php
                $user_id = get_post_meta($post->ID, 'apt_wordpress_user', true);
                if($user_id){
                    $args = array(
                    'author'        =>  $user_id,
                    'orderby'       =>  'post_date',
                    'order'         =>  'ASC',
                    'posts_per_page' => -1
                    );
                    $posts = get_posts( $args );
                    if(count($posts) > 0){
            ?>
                        <h2>This author's posts:</h2>
            <?php
                        foreach( $posts as $post ){
                            setup_postdata( $post ); 
                            ?>
                            <li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
                            <?php
                            wp_reset_postdata();
                        }
                    }
                }
            ?>
            <h2></h2>
	</main><!-- .site-main -->

	<?php get_sidebar( 'content-bottom' ); ?>

</div><!-- .content-area -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>