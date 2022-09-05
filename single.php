<?php

/**
 * The Template for displaying all single posts
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context         = Timber::context();
$timber_post     = Timber::get_post();
$context['post'] = $timber_post;

$args_news = array(
	'numberposts' => 6,
	'post_type' => 'post',
	'post__not_in' => array($timber_post->ID),

);
$context['posts'] = Timber::get_posts($args_news);

$args_prod = array(
	'numberposts' => 4,
	'post_type' => 'prodotti',
	'post__not_in' => array($timber_post->ID),

);
$context['prodotti'] = Timber::get_posts($args_prod);
$context['homelang'] = pll_home_url();
$context['curlang'] = pll_current_language();

$context['foo']   = 'single.php';


if (post_password_required($timber_post->ID)) {
	Timber::render('single-password.twig', $context);
} else {
	Timber::render(array('single-' . $timber_post->ID . '.twig', 'single-' . $timber_post->post_type . '.twig', 'single-' . $timber_post->slug . '.twig', 'single.twig'), $context);
}
