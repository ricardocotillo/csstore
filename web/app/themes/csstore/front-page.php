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
$context[ 'post' ] = $timber_post;
$context[ 'slider' ] = $slider;

$context[ 'items_grid_title' ] = carbon_get_post_meta( $post_id, 'rc_items_grid_title' );
$items = carbon_get_post_meta( $post_id, 'rc_items_grid_items' );
$context[ 'items_grid_items' ] = array_map( function($item) {
    switch ( $item[ 'type' ] ) {
        case 'term':
            return  new Timber\Term( $item[ 'id' ] );
            break;
        default:
            return new Timber\Post( $item[ 'id' ] );
            break;
    }
}, $items );

$context[ 'products_grid_title' ] = carbon_get_post_meta( $post_id, 'rc_products_grid_title' );
$products = carbon_get_post_meta( $post_id, 'rc_products_grid' );
$context[ 'products_grid' ] = array_map( function( $p ) {
    return wc_get_product( $p[ 'id' ] );
}, $products );

$context[ 'tiles_title' ] = carbon_get_post_meta( $post_id, 'rc_tiles_title' );
$context[ 'tiles' ] = carbon_get_post_meta( $post_id, 'rc_tiles' );
$context[ 'tiles' ] = array_map( function($t) {
    $t[ 'products' ] = array_map( function( $p ) {
        return wc_get_product( $p[ 'id' ] );
    }, $t[ 'products' ] );
    return $t;
}, $context[ 'tiles' ]);
Timber::render( array( 'front-page.twig' ), $context );
