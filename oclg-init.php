<?php
/*
Plugin Name: Open Content License Generator
Plugin URI:  http://aharon.varady.net/omphalos/2013/03/plugin-for-applying-freelibre-compatible-licenses-from-the-creative-commons-to-wordpress-posts
Description: Clearly indicate the Open Content license through which your work is shared! (CC BY, CC BY-SA, or CC0 - a Public Domain Dedication.) A fork of OptimalDevs' Creative Commons Generator.
Version:     1.0
Author:      Aharon Varady, the Jewish Free-Culture Society
Author URI:  http://aharon.varady.net/omphalos
License:     LGPL3
License URI: https://www.gnu.org/licenses/lgpl-3.0.html
*/
load_plugin_textdomain( 'oclg-domain', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
require_once( 'oclg-admin.php' );
require_once( 'oclg-post-options.php' );
require_once( 'oclg-frontend.php' );
?>