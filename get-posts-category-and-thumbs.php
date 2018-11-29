<?php
/**
 * Make a Wordpress Custom Endpoint adding information in the get_post() objecct
 *
 * @param array $data Options for the function.
 * @return string|null Post info avaible in get_post() plus custom info or null if none.
 */
function custom_endpoint ( $data ) {
	$posts = get_posts( array(
		'numberposts'   => -1,
		'post_type'     => array('event', 'post'), //Here we can get more than one post type. Useful to a home page.
	) );
 
	if ( empty( $posts ) ) {
		return null;
	}
	
   $args = array();	
   $content = '';
   $title = '';
   
   foreach ( $posts as $post ) {
       
     //Get informations that is not avaible in get_post() function and store it in variables.
	   $category = get_the_category( $post->ID );
	   $img_thumb = get_the_post_thumbnail_url( $post->ID, 'thumbnail' );       // Thumbnail (default 150px x 150px max)
     $img_medium = get_the_post_thumbnail_url( $post->ID, 'medium' );          // Medium resolution (default 300px x 300px max)
     $img_large = get_the_post_thumbnail_url( $post->ID, 'large' );           // Large resolution (default 640px x 640px max)
     $img_full = get_the_post_thumbnail_url( $post->ID, 'full' );            // Full resolution (original size uploaded)
  
     //Adds the informations to the post object.
	   $post->category = $category; 
	   $post->img_tumb = $img_thumb; 
	   $post->img_medium = $img_medium; 
	   $post->img_large = $img_large; 
	   $post->img_full = $img_full;
	   
	   array_push($args, $post);
   }
 
	return $args;
}
 
 
add_action( 'rest_api_init', function () {
	register_rest_route( 'wp/v1', '/custom-endpoint/', array(
		'methods' => 'GET',
		'callback' => 'custom_endpoint',
	) );
} );
?>
