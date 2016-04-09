<?php
add_action( 'admin_init', 'oclg_add_meta_box', 1);
add_action( 'save_post', 'oclg_save_post_data' );

function oclg_add_meta_box() {
	
	$args	= array(
        'public'	=> true,
        '_builtin'	=> false
    ); 

    $output 	= 'names'; // names or objects, note names is the default
    $operator 	= 'and'; // 'and' or 'or'
    $post_types = get_post_types($args,$output,$operator);
	$posttypes_array = array();

    foreach ($post_types  as $post_type ) {
        $posttypes_array[] = $post_type;
    }
	
	$posttypes_array[] = 'post';

	foreach ( $posttypes_array as $post_type ) {
		
		add_meta_box( 
			'oclg_metabox',
			__( 'Open Content License Generator', 'oclg-domain' ),
			'oclg_meta_box',
			$post_type 
		);
		
	}
	
}

function oclg_save_post_data( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	if ( !isset( $_POST['oclg_nonce'] ) ) return;
	if ( !wp_verify_nonce( $_POST['oclg_nonce'], plugin_basename( __FILE__ ) ) )	return;
	if ( !current_user_can( 'edit_post', $post_id ) )	return;

	$oclg_options = oclg_set_options( $_POST );	
	update_post_meta( $post_id, '_oclg_options', $oclg_options );
}

function oclg_meta_box( $post ) {
	wp_nonce_field( plugin_basename( __FILE__ ), 'oclg_nonce' );
	
	// Get the current options
	if ( get_post_meta( $post->ID, '_oclg_options' ) ) {
		$oclg_old_options = get_post_meta( $post->ID, '_oclg_options', true );
		$oclg_author_name = $oclg_old_options['author_name'];
		$oclg_author_url = $oclg_old_options['author_url'];
		echo oclg_get_banner( $oclg_old_options, 'meta_exist' ); 
	} else {
		$oclg_old_options = get_option('oclg_options');
		global $current_user;
		$oclg_author_name = esc_attr( $current_user->display_name );
		$oclg_author_url = esc_url( get_author_posts_url( $current_user->ID ) );
		echo oclg_get_banner( $oclg_old_options, 'publish' ); 
	}
	
?>

<?php
	oclg_get_table( $oclg_old_options );
}
?>