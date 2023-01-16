<?php
/**
 * @package  WordPress
 * @subpackage  Timber
 * @since    Timber 0.1
 */

$context = Timber::context();

$timber_post = new Timber\Post();
$post_id = get_the_ID();
$slider = carbon_get_post_meta( $post_id, 'rc_slider' );
$context['post'] = $timber_post;
$context['slider'] = $slider;
Timber::render( array( 'front-page.twig' ), $context );
