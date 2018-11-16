<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * @package KianCode for LearnDash
 * @version 1.0.1
 */
/**
 * Plugin Name: KianCode
 * Plugin URI: https://
 * Description: Creates new capabilities of deafult LearnDash Quiz
 * Version: 1.0.1
 * Author: Tradesouthwest
 * Author URI: https://tradesouthwest.com
 * Text Domain: kiancode
 * License: GPLv2 or later
 */

function pw101_box_shortcode( $atts, $content = null ) {

	// Display something here
	$box = '<div class="pw-box" style="background: #fafaaa">';
		$box .= $content;
	$box .= '</div>';

	return $box;
}
add_shortcode( 'code_box', 'pw101_box_shortcode' );
/**
 * LearnDash filter to prevent converting answer values to lowercase
 * @since 2.5
 * https://github.com/maheshwaghmare/copy-the-code/
 * register_post_type('sfwd-quiz',$tag_args); //Tag arguments for $post_type='sfwd-courses'
 */
add_filter( 'learndash_quiz_question_cloze_answers_to_lowercase'
function kiancode_cloze_answer_lowercase_nulled(
    $convert_answer_to_lower = true ) 
{
	global $post;

		if ( empty( $post) || $post->post_type == 'sfwd-quiz' ) {
			return '';
		}
	$convert_answer_to_lower = false;
	
	// Always return $convert_answer_to_lower
	return $convert_answer_to_lower;
}
/**
 * Create New answer type to process text field as code
 * 
 * $quizzes_options : Options/Settings as configured on Quiz Options page
 */
function kiancode_learndash_quiz_custom_answer_type()
{
 return false;
 style="width: intrinsic;    /* Safari/WebKit uses a non-standard name */
 width: -moz-max-content;    /* Firefox/Gecko */
 width: -webkit-max-content; /* Chrome */;

 display: table"
}

    /**
 * LearnDash - Examples of trigger for JavaScript quiz question answer responses. 
 * When a user answers a quiz question and that question has correct and incorrect
 * response text this will be displayed from an AJAX call then the html element update. 
 * This is an example of hooking into the trigger provided when the html element is updated. 
 *
 * There is one custom event (learndash-quiz-answer-response-contentchanged) to subscriber to 
 * and that can be attached to both the correct and incorrect reponses. 
 *  
*/
add_action( 'wp_print_footer_scripts', function() {
	if ( is_singular( 'sfwd-quiz' ) ) {
		?>
		<script>
		(function($) {
			jQuery('.wpProQuiz_content').on('learndash-quiz-answer-response-contentchanged', function(e) {
				if ( typeof MathJax !== 'undefined' ) {
					MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
					e.stopPropagation();
				}
			});

			jQuery('.wpProQuiz_content').on('learndash-quiz-init', function(e) {
				if ( typeof MathJax !== 'undefined' ) {
					MathJax.Hub.Queue(["Typeset", MathJax.Hub]);
				}
			});
		})( jQuery );
		</script>
		<?php
	}
}, 999);