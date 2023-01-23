<?php /* Template Name: Contact Page Template */
$context = Timber::context();

$timber_post     = new Timber\Post();
$context['post'] = $timber_post;
Timber::render( array( 'contact-page.twig' ), $context );
