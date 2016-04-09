<?php
function oclg_display($content) {
	if( is_single() ) {
		global $post;
		if ( get_post_meta( $post->ID, '_oclg_options' ) )	$content .= oclg_get_banner( get_post_meta( $post->ID, '_oclg_options', true ), 'meta_exist' );
		else	$content .= oclg_get_banner( get_option( 'oclg_options' ), 'no_meta' );
	}
	return $content;
}
add_filter( 'the_content', 'oclg_display', 99);
?>