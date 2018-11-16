<?php 
/**
 * Check if a shortcode is already registered
 *
 * @since 1.0
 *
 * @param $shortcode string The shortcode slug to test
 *
 * @return void
 */
function kiancode_asc_shortcode_exists( $shortcode = false ) {
	
	global $shortcode_tags;

	//echo '<pre>'; var_dump($shortcode_tags); echo '</pre>';
 
	if ( ! $shortcode )
		return false;
 
	if ( array_key_exists( $shortcode, $shortcode_tags ) )
		return true;
 
	return false;

}