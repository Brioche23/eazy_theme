<?php

/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * To generate specific templates for your pages you can use:
 * /mytheme/templates/page-mypage.twig
 * (which will still route through this PHP file)
 * OR
 * /mytheme/page-mypage.php
 * (in which case you'll want to duplicate this file and save to the above path)
 *
 * Methods for TimberHelper can be found in the /lib sub-directory
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$args_news = array(
    'numberposts' => -1,
    'post_type' => 'post',

);
$context['posts'] = Timber::get_posts($args_news);

$args_prod = array(
    'numberposts' => -1,
    'post_type' => 'prodotti',

);
$context['prodotti'] = Timber::get_posts($args_prod);

$context['foo']   = 'page.php';
$context['homelang'] = pll_home_url();


$timber_post     = new Timber\Post();
$context['post'] = $timber_post;

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
    case "contatti-it":
        $pName = "contatti";
        break;
    case "shop-it":
        $pName = "shop";
        break;
    default:
        $pName = $timber_post->post_name;
}

$context['pname'] = $pName;

Timber::render(array('page-' . $pName . '.twig', 'page.twig'), $context);
