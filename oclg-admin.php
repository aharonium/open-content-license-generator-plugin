<?php
add_action('admin_menu', 'oclg_menu', '0');

function oclg_menu() {
	oclg_set_default();
	add_options_page(
		__( 'Open Content License Generator Options', 'oclg-domain' ), 
		'OCLG Options', 
		'manage_options', 
		'oclg-settings', 
		'oclg_settings_page'
	);
}

function oclg_set_default() {
	
	if ( !get_option( 'oclg_options' ) ) {
		
		$oclg_options = array(
			'active' => TRUE,
			'mod' => 'yes',
			'disallow_mon' => TRUE,
			'show_title' => FALSE,
			'title' => '',
			'show_author' => TRUE,
			'author_name' => "",
			'show_author_url' => TRUE,
			'author_url' => '',
			'source_url' => '',
			'more_url' => '',
			'format' => 'other'
		);
		
		update_option( 'oclg_options', $oclg_options );
	}
	
}

function oclg_get_banner( $oclg_options, $who_call ) {
	if ( !$oclg_options['active'] && !is_admin() ) return "";
	
	$banner = "";
	$islicensed ="";
	$title = "";
	$author ="";
	$source = "";
	$more = "";

	$licensedir = "licenses";
	$attributes = "by";
	$version = "4.0";
	$versiontype = "International";
	$image_attributes = "CC-BY";
	$cctype = "license";
	$attributes_text = __( 'Creative Commons Attribution', 'oclg-domain' );
	
	if ( $oclg_options['mod'] == "but" ) {
		$licensedir = "licenses";
		$attributes .= "-sa";
		$version = "4.0";
		$versiontype = "International";
		$image_attributes .= "-SA";
		$cctype = "copyleft license";
		$attributes_text .= __( '-ShareAlike', 'oclg-domain' );
	}

	if ( $oclg_options['mod'] == "zero" ) {
		$licensedir = "publicdomain";
		$attributes = "zero";
		$version = "1.0";
		$versiontype = "Universal";
		$image_attributes = "CC-0-PD";
		$cctype = "license";
		$attributes_text = __( 'Creative Commons Public Domain Dedication', 'oclg-domain' );
	}

	if ( $oclg_options['mod'] == "fu" ) {
		$licensedir = "fairuse";
		$attributes = "fu";
		$version = "";
		$versiontype = "";
		$image_attributes = "copyright+fair-use";
		$cctype = "right";
		$attributes_text = __( 'United States Copyright Law: Fair Use Right', 'oclg-domain' );
	}
	
	if ( $oclg_options['mod'] == "fu" ) {
	$banner = "<a target='_blank' rel='license nofollow' href='https://www.law.cornell.edu/uscode/text/17/107'><img class='alignright' alt='17 U.S. Code  107 - Limitations on exclusive rights: Fair use' style='border-width:0' src='/images/" . $image_attributes . ".svg.150x100.png' /></a><br />";
	$islicensed = ' under the contributor&#39;s <a target="_blank" rel="license nofollow" href="https://www.law.cornell.edu/uscode/text/17/107">Fair Use Right</a> (17 U.S. Code  107 - Limitations on exclusive rights: Fair use), in respect to the copyrighted material included. Additional material resides in the Public Domain. Original work is shared under a Creative Commons Attribution-ShareAlike 4.0 Unported license.<br />';
	} else {
	$banner = "<a target='_blank' rel='license nofollow' href='http://creativecommons.org/" . $licensedir . '/' . $attributes . '/' . $version ."/'><img class='alignright' alt=' . $attributes_text . $version . $versiontype .' style='border-width:0' src='/images/" . $image_attributes . ".svg.150x100.png' /></a><br />";
	$islicensed = ' with a <a target="_blank" rel="license nofollow" href="http://creativecommons.org/' . $licensedir . '/' . $attributes . '/' . $version .'/">' . $attributes_text . '&nbsp;' . $version . '&nbsp;' . $versiontype .'</a> ' . $cctype . '.<br />';
	}
	
	switch ( $who_call ) {
		case "admin":
			$title = get_the_title();
			$author_name = get_the_author();
			$author_url = esc_url( get_author_posts_url(get_the_author_meta( 'ID' ) ) );
			break;
		case "publish": 
			$title = get_the_title();
			$author_name = get_the_author();
			$author_url = esc_url( get_author_posts_url(get_the_author_meta( 'ID' ) ) );
			break;
		case "meta_exist":
			$title = get_the_title();
			$author_name = get_the_author();
			$author_url = esc_url( get_author_posts_url(get_the_author_meta( 'ID' ) ) );
			break;
		case "no_meta":
			$title = get_the_title();
			$author_name = get_the_author();
			$author_url = esc_url( get_author_posts_url(get_the_author_meta( 'ID' ) ) );
			break;
	}

	if( $oclg_options['show_title'] == FALSE || $title == "" )	$title = __( 'This work', 'oclg-domain' );
	
	//Format and Title 
	if ($oclg_options['format'] != "other")	$format_type = 'href="http://purl.org/dc/dcmitype/' . $oclg_options['format'] . '"  rel="dct:type"'; 
	else $format_type = "";
	$title = '<span xmlns:dct="http://purl.org/dc/terms/" property="dct:title" ' . $format_type . ' >&#8220;' . $title . '&#8221;</span>';
	
	//Show Author and URL
	if ( $oclg_options['show_author'] == TRUE && $author_name != "" ) {
		if ( $oclg_options['show_author_url'] == TRUE && $author_url != "") 
			// $author = ' is shared by <a target="_blank" xmlns:cc="http://creativecommons.org/ns#" href="' . $author_url . '" property="cc:attributionName" rel="cc:attributionURL nofollow">'. $author_name.'</a>';
			$author = ' is shared ';
		else
			// $author = ' is shared by <span xmlns:cc="http://creativecommons.org/ns#" property="cc:attributionName">' . $author_name . '</span>';
			$author = ' is shared ';
	}

	//Show Source URL
	if ( $oclg_options['source_url'] != "" )	$source = 'Based on a work at <a target="_blank" xmlns:dct="http://purl.org/dc/terms/" href="' . $oclg_options["source_url"] . '" rel="dct:source nofollow">' . $oclg_options["source_url"] . '</a><br />';
	
	//Show More Permissions
	if ($oclg_options['more_url'] != "")	$more = 'Permissions beyond the scope of this license may be available at <a target="_blank" xmlns:cc="http://creativecommons.org/ns#" href="' . $oclg_options['more_url'] . '" rel="cc:morePermissions nofollow">' . $oclg_options['more_url'] . '</a>';
	
	$result = "<div class='print-only' class='copyright' class='delete-no' class='oclg-banner'>" . $banner . $title . $author . $islicensed . $source . $more . "</div>";
	return $result;
}

function oclg_set_options( $form ) {
	$oclg_options = array();

	if ( isset( $form['oclg_all'] ) ) $oclg_options['active'] = TRUE; else $oclg_options['active'] = FALSE;
	if ( isset( $form['oclg_mod'] ) ) $oclg_options['mod'] = $form['oclg_mod']; else $oclg_options['mod'] = "yes";
	if ( isset( $form['oclg_mon'] ) ) $oclg_options['disallow_mon'] = TRUE; else $oclg_options['disallow_mon'] = FALSE;
	if ( isset( $form['oclg_show_title'] ) ) $oclg_options['show_title'] = TRUE; else $oclg_options['show_title'] = FALSE;
	if ( isset( $form['oclg_title'] ) ) $oclg_options['title'] = esc_attr( $form['oclg_title'] ); else $oclg_options['title'] = "";
	if ( isset( $form['oclg_show_author'] ) ) $oclg_options['show_author'] = TRUE; else $oclg_options['show_author'] = FALSE;
	if ( isset( $form['oclg_author_name'] ) ) $oclg_options['author_name'] = esc_attr( $form['oclg_author_name'] ); else $oclg_options['author_name'] = "";
	if ( isset( $form['oclg_show_author_url'] ) ) $oclg_options['show_author_url'] = TRUE; else $oclg_options['show_author_url'] = FALSE;
	if ( isset( $form['oclg_author_url'] ) ) $oclg_options['author_url'] = esc_attr( esc_url( $form['oclg_author_url'] ) );  else $oclg_options['author_url'] = "";
	if ( isset( $form['oclg_source'] ) ) $oclg_options['source_url'] = esc_attr( esc_url( $form['oclg_source'] ) ); else $oclg_options['source_url'] = "";
	$oclg_options['more_url'] = esc_attr( esc_url( $form['oclg_more'] ) );
	$oclg_options['format'] = $form['oclg_format'];
	
	return $oclg_options;
}

function oclg_get_table( $options ) {
	?>
	<table class="widefat">
		<thead>
			<tr>
				<th><?php _e( 'Options', 'oclg-domain' ); ?></th>
				<th><?php _e( 'Description', 'oclg-domain' ); ?></th>       
			</tr>
		</thead>
		<tbody>
		   <tr>
			 <td>
				<p><label>
					<input name="oclg_mod" type="radio" value="yes" <?php checked( $options['mod'], 'yes' ); ?> /> <?php _e( 'Allow modifications of your work', 'oclg-domain' ); ?>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'The licensor permits others to copy, distribute, display and perform the work, as well as make derivative works based on it.', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   <tr>
			 <td>
				<p><label>
					<input name="oclg_mod" type="radio" value="but" <?php checked( $options['mod'], 'but' ); ?> /> <?php _e( 'Allow modifications as long as others share alike', 'oclg-domain' ); ?>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'The licensor permits others to create and distribute derivative works but only under the same or a compatible license.', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   <tr>
			 <td>
				<p><label>
					<input name="oclg_mod" type="radio" value="zero" <?php checked( $options['mod'], 'zero' ); ?> /> <?php _e( 'Dedicate your work to the Public Domain', 'oclg-domain' ); ?>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'The licensor dedicates the work to the Public Domain by waiving all of their rights to the work worldwide under copyright law, including all related and neighboring rights, to the extent allowed by law.', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   <tr>
			 <td>
				<p><label>
					<input name="oclg_mod" type="radio" value="fu" <?php checked( $options['mod'], 'fu' ); ?> /> <?php _e( 'Express your Fair Use Right', 'oclg-domain' ); ?>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'The contributors exerts their Fair Use Rights under 17 U.S. Code § 107 - Limitations on exclusive rights: Fair use.', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   <tr>
			 <td>
				<p><label>
					<input type="checkbox" name="oclg_show_title" value="true" <?php checked( $options['show_title'] ); ?> /> <?php _e( 'Show title of work', 'oclg-domain' ); ?>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'The title of the work you are licensing.', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   <tr>
			 <td>
				<p><label>
					<input type="checkbox" name="oclg_show_author" value="true" <?php checked( $options['show_author'] ); ?>  id="oclg_author1" onclick="if(!this.checked)document.getElementById('oclg_author2').checked = false"/> <?php _e( 'Show author of work', 'oclg-domain' ); ?>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'The name of the person who should receive attribution for the work. Most often, this is the author.', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   <tr>
			 <td>
				<p><label>
					<input type="checkbox" name="oclg_show_author_url" value="true" <?php checked( $options['show_author_url'] ); ?> id="oclg_author2" onclick="if(this.checked)document.getElementById('oclg_author1').checked = true" /> <?php _e( 'Link to the author of the work', 'oclg-domain' ); ?>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( "The URL to which the work should be attributed. For example, the work's page on the author's site.", 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   <tr>
			 <td>
				<p><label>
					<?php _e( 'More permissions URL:', 'oclg-domain' ); ?>
					<input type="text" name="oclg_more" value="<?php echo $options['more_url']; ?>" />
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'A URL where a user can find information about obtaining rights that are not already permitted by the CC license.', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   <tr>
			 <td>
				<p><label>
					<?php _e( 'Format of work', 'oclg-domain' ); ?>
					<select name="oclg_format">
					  <option value="other" <?php selected( $options['format'], 'other' ); ?> ><?php _e( 'Other / Multiple formats', 'oclg-domain' );?></option>
					  <option value="Sound" <?php selected( $options['format'], 'Sound' ); ?> ><?php _e( 'Audio', 'oclg-domain' );?></option>
					  <option value="MovingImage" <?php selected( $options['format'], 'MovingImage' ); ?> ><?php _e( 'Video', 'oclg-domain' );?></option>
					  <option value="StillImage" <?php selected( $options['format'], 'StillImage' ); ?> ><?php _e( 'Image', 'oclg-domain' );?></option>
					  <option value="Text" <?php selected( $options['format'], 'Text' ); ?> ><?php _e( 'Text', 'oclg-domain' );?></option>
					  <option value="DataSet" <?php selected( $options['format'], 'DataSet' ); ?> ><?php _e( 'Dataset', 'oclg-domain' );?></option>
					  <option value="InteractiveResource" <?php selected( $options['format'], 'InteractiveResource' ); ?> ><?php _e( 'Interactive', 'oclg-domain' );?></option>
					</select>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'This describes what kind of work is being licensed. For example, a photograph would have the "Image" format. If unsure, choose "Other / Multiple formats".', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		   			<tr>
			 <td>
				<p><label>
					<input name="oclg_all" type="checkbox" value="true" <?php checked( $options['active'] ); ?> /> <?php _e( 'Enable Creative Commons Generator', 'oclg-domain' ); ?>
				</p></label>
			 </td>
			 <td>
				<p><?php _e( 'Creative Commons Generator display the banner below the post if you check this.', 'oclg-domain' ); ?></p>
			 </td>
		   </tr>
		</tbody>
	</table>
	<?php
}

function oclg_settings_page() {
?>
	<div class='wrap'>
		<div id="icon-tools" class="icon32"></div>
		<h2><?php _e( 'Creative Commons Generator - Options Page', 'oclg-domain' ); ?></h2>
<?php
	// Update options.
	if (isset($_POST['submit'])) {
		$oclg_options = oclg_set_options( $_POST );
		update_option( 'oclg_options', $oclg_options );
		
		echo "<div id='message' class='updated'><p>" . __( 'Successfully saved configuration.', 'oclg-domain' ) . "</p></div>";
	}	
?>		
		<h3><?php _e( 'Preview', 'oclg-domain' ); ?></h3>
		<?php echo oclg_get_banner( get_option('oclg_options'), 'admin' ); ?>
		
		<h3><?php _e( 'General Settings', 'oclg-domain' ); ?></h3>
		<p><?php _e( 'This setting is general and is used by default for all entries (old and new). However <strong>can be modified specifically for each entry</strong> from the Add/Edit section.', 'oclg-domain' ); ?></p>
		<form method="post">
			<?php oclg_get_table( get_option('oclg_options') ); ?>
			<p><input type="submit" class="button-primary" value="<?php esc_attr_e( __( 'Save Changes' ) ); ?>"/></p>
			<input type="hidden" name="submit" value="true" />
		</form>
		<p>* <?php _e( "Author's Name:", 'oclg-domain' ); ?> <?php _e( "The author's Display Name will be used by default.", 'oclg-domain' ); ?></p>
		<p>* <?php _e( 'Link to the author of the work', 'oclg-domain' ); ?>: <?php _e( "The author's link in WordPress will be used by default.", 'oclg-domain' ); ?></p>
		<p>* <?php _e( 'Title of work:', 'oclg-domain' ); ?> <?php _e( 'For entries created with disabled OCLG will use the title of the post by default. For those that are published with the OCLG enabled, the title must be entered manually.', 'oclg-domain' ); ?></p>
	</div>
<?php
}
?>