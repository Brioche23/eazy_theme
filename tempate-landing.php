<?php

/**
 * Template Name: Landing
 * Description: A Page Template for the 4 landing pages

 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
$context['foo']   = 'template-landing.php';
$context['homelang'] = pll_home_url();


$args_news = array(
    'numberposts' => 6,
    'post_type' => 'post',

);
$context['posts'] = Timber::get_posts($args_news);
$args_pord = array(
    'numberposts' => 6,
    'post_type' => 'prodotti',

);
$context['prodotti'] = Timber::get_posts($args_pord);

$pName = "ciao";
$name = $timber_post->post_name;
switch ($name) {
    case "group-it":
        $pName = "group";
        break;
    case "green-it":
        $pName = "green";
        break;
    case "bunker-it":
        $pName = "bunker";
        break;
    case "yachting-it":
        $pName = "yachting";
        break;
    default:
        $pName = $timber_post->post_name;
}

$context['pname'] = $pName;

Timber::render(array('page-' . $pName . '.twig', 'page.twig'), $context);
