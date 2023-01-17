<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$context = Timber::context();

$context['product'] = $product;
$args['quantity'] = isset( $args['quantity'] ) ? $args['quantity'] : 1;
$args['class'] = isset( $args['class'] ) ? $args['class'] : 'button';
$args['attributes'] = isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '';
$context['args'] = $args;

Timber::render( 'woocommerce/loop/add-to-cart-btn.twig', $context );
