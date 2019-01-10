<?php
/**
 *
 * Plugin Name:       Tidsrapportering
 * Plugin URI:        bananbyran.se
 * Description:       En enkel tidsrapporteringsfunktion för chaufförer.
 * Version:           1.0.0
 * Author:            BananByrån
 * Author URI:        bananbyran.se
 *  
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'TIDSRAPPORTERING_VERSION', '1.0.0' );
define( 'PLUGIN_NAME', 'Tidsrapportering' );
require plugin_dir_path( __FILE__ ) . 'includes/class-tidsrapportering.php';

function run_tidsrapportering() {
	$plugin = new Tidsrapportering();
	$plugin->run();
}
run_tidsrapportering();
