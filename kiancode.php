<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @package KianCode for LearnDash
 * @version 1.0.2
 */
/**
 * Plugin Name: KianCode
 * Plugin URI: https://
 * Description: Creates new capabilities of deafult LearnDash Quiz
 * Version: 1.0.3
 * Author: Tradesouthwest
 * Author URI: https://tradesouthwest.com
 * Text Domain: kiancode
 * License: GPLv2 or later
 */

//activate/deactivate hooks
function kiancode_plugin_activation() 
{
    // Check for LearnDash
    if ( !class_exists( 'SFWD_LMS' ) ) {
        exit( __('This plugin requires that LearnDash LMS is installed and activated.',
        'kiancode' ) );
    }
}
  
function kiancode_plugin_deactivation() 
{
    remove_shortcode( 'code' );
    //return false;
}

//activate and deactivate registered plugin scripts
register_activation_hook( __FILE__, 'kiancode_plugin_activation');
register_deactivation_hook( __FILE__, 'kiancode_plugin_deactivation');

/**
 * Could be used for validation
 */
function kiancode_addtosite_scripts() {
    // Register Scripts
    wp_register_script( 'kiancode-plugin', 
       plugins_url( 'lib/kiancode-plugin.js', __FILE__ ), 
       array( 'jquery' ), true );
    
    //wp_enqueue_script( 'kiancode-plugin' );     
}

//load language scripts     
function kiancode_load_text_domain() 
{
    load_plugin_textdomain( 'kiancode', false, 
    basename( dirname( __FILE__ ) ) . '/languages' ); 
}

/**
 * init shortcode
 * https://codex.wordpress.org/Shortcode_API
 * 
 * @uses if_class_exists
 * @since 1.0.2
 */
add_action( 'init', 'kiancode_quiz_shortcode_custom_init' );
function kiancode_quiz_shortcode_custom_init() 
{   
    if ( ! class_exists( 'SFWD_LMS' ) ) {
        return false;
    }
    add_shortcode( 'code', 'kiancode_quiz_include_code' );
}


/**
 * Shortcode callback
 * 
 * [code]
 * @param string $box     Merely a placeholder to identify field.
 * @param string $content WP global.
 * @since 1.0.1
 * 
 * @return HTML
 */
function kiancode_quiz_include_code( $atts, $content = null ) 
{
   
    $box = '<span id="kianCode" class="code-box" style="background:#ffa;">';
		$box .= $content;
	$box .= '</span>';

	return $box;
}


/**
 * Filters the content to remove any extra paragraph or break tags
 * caused by shortcodes.
 *
 * @since 1.0.1
 *
 * @param string $content  String of HTML content.
 * @return string $content Amended string of HTML content.
 */
function kiancode_shortcode_break_paragraph_tags( $new_content ) 
{
    $new_content = '';
    global $post, $content;
    if ( ( $post instanceof WP_Post ) && 
         ( $post->post_type == 'sfwd-quiz' ) ) 
    {

	$pattern_full = '{(\[raw\].*?\[/raw\])}is';
	$pattern_contents = '{\[raw\](.*?)\[/raw\]}is';
	$pieces = preg_split($pattern_full, $content, -1, PREG_SPLIT_DELIM_CAPTURE);

	    foreach ($pieces as $piece) {
		    if (preg_match($pattern_contents, $piece, $matches)) {
	    		$new_content .= $matches[1];
    		} else {
			    $new_content .= wptexturize(wpautop($piece));
		    }
        }
    }
	return $new_content;
}
add_filter( 'the_content', 'kiancode_shortcode_break_paragraph_tags', 99 ); 

//Prevent LearnDash from converting the answer before we get it
add_filter('learndash_quiz_question_cloze_answers_to_lowercase', '__return_false' );

/**
 * LearnDash filter to prevent converting answer values to lowercase
 *
 * @possibly use ld_adv_quiz_pro_ajax() {
 * @uses stripslashes( strtolower( trim( $userResponse ) ) )
 * @since 1.0
 * @from WpProQuiz_View_FrontQuiz.php
 * post_type=sfwd-quiz
 */
function kiancode_learndash_quiz_recheck_using_original( $checked, $type, $answer,  $correctArray, $answerIndex, $questionModel )
{
    $shortcode='[code]';
    $has_shortcode = strpos($questionModel->getAnswerData(true), $shortcode);
    if( $has_shortcode)
    {
        return in_array( $answer, $correctArray );
    }

    $lower_correct=array_map('strtolower', $correctArray);
    $lower_answer=strtolower($answer);

    return in_array($lower_answer, $lower_correct);

}

add_filter( 'learndash_quiz_check_answer', 'kiancode_learndash_quiz_recheck_using_original', 15, 6 );

//Adding CSS inline style to an existing CSS stylesheet
function kiancode_add_inline_css() {
    global $post;
    if ( ( $post instanceof WP_Post ) && 
         ( $post->post_type == 'sfwd-quiz' ) ) 
    {
    $kiancode_css = 
'.wpProQuiz_question ul[data-type="cloze_answer"] li p:not([style]){color:transparent}';
    wp_add_inline_style( 'kiancodecss', $kiancode_css ); 
    }
}
add_action( 'wp_enqueue_scripts', 'kiancode_add_inline_css' );
?>
