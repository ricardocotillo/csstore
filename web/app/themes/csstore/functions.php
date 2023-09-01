<?php
/**
 * Timber starter-theme
 * https://github.com/timber/starter-theme
 *
 * @package  WordPress
 * @subpackage  Timber
 * @since   Timber 0.1
 */

use Carbon_Fields\Container;
use Carbon_Fields\Field;
use Timber\Timber;

/**
 * If you are installing Timber as a Composer dependency in your theme, you'll need this block
 * to load your dependencies and initialize Timber. If you are using Timber via the WordPress.org
 * plug-in, you can safely delete this block.
 */
$composer_autoload = __DIR__ . '/vendor/autoload.php';
include 'inc/inc.vite.php';
if ( file_exists( $composer_autoload ) ) {
	require_once $composer_autoload;
	$timber = new Timber();

	// init timber woocommerce
	if ( class_exists( 'WooCommerce' ) ) {
		\Timber\Integrations\WooCommerce\WooCommerce::init();
	}
}

/**
 * This ensures that Timber is loaded and available as a PHP class.
 * If not, it gives an error message to help direct developers on where to activate
 */
if ( ! class_exists( 'Timber' ) ) {

	add_action(
		'admin_notices',
		function() {
			echo '<div class="error"><p>Timber not activated. Make sure you activate the plugin in <a href="' . esc_url( admin_url( 'plugins.php#timber' ) ) . '">' . esc_url( admin_url( 'plugins.php' ) ) . '</a></p></div>';
		}
	);

	add_filter(
		'template_include',
		function( $template ) {
			return get_stylesheet_directory() . '/static/no-timber.html';
		}
	);
	return;
}

/**
 * Sets the directories (inside your theme) to find .twig files
 */
Timber::$dirname = array( 'templates', 'views' );

/**
 * By default, Timber does NOT autoescape values. Want to enable Twig's autoescape?
 * No prob! Just set this value to true
 */
Timber::$autoescape = false;


/**
 * We're going to configure our theme inside of a subclass of Timber\Site
 * You can move this to its own file and include here via php's include("MySite.php")
 */
class StarterSite extends \Timber\Site {
	/** Add timber support. */
	public function __construct() {
		add_action( 'after_setup_theme', array( $this, 'theme_supports' ) );
		add_filter( 'timber/context', array( $this, 'add_to_context' ) );
		add_filter( 'timber/twig', array( $this, 'add_to_twig' ) );
		add_action( 'init', array( $this, 'register_post_types' ) );
		add_action( 'init', array( $this, 'register_taxonomies' ) );
		add_action( 'init', array( $this, 'rc_menus' ) );
		add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
		add_filter('use_block_editor_for_post', '__return_false', 10);
		add_action( 'carbon_fields_register_fields', array( $this, 'rc_register_fields' ) );
		add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'rc_fragments' ), 10, 1 );
		// add_action( 'woocommerce_after_add_to_cart_button', array( $this, 'rc_add_after_add_to_cart_button' ) );
		add_action( 'rest_api_init', array( $this, 'rc_rest_routes' ) );
		parent::__construct();
	}
	/** This is where you can register custom post types. */
	public function register_post_types() {

	}
	/** This is where you can register custom taxonomies. */
	public function register_taxonomies() {

	}

	public function rc_rest_routes() {
		register_rest_route( 'rc', '/contact', array(
			'methods' => 'POST',
			'callback' => 'rc_rest_contact',
		) );
	}

	public function rc_menus() {
		register_nav_menu( 'main-menu', 'Main Menu' );
		register_nav_menu( 'footer-menu', 'Footer Menu' );
	}

	public function rc_add_after_add_to_cart_button() {
		Timber::render('partial/after_add_to_cart_button.twig');
	}

	public function rc_fragments( $f ) {
		$count = WC()->cart->get_cart_contents_count();
		$class = 'cart-count';
		$class .= $count > 0 ? '' : ' empty';
		$count = $count > 0 ? (string)$count : '';
		$f['.cart-count'] = '<span class="'.$class.'">'.$count.'</span>';
		return $f;
	}

	/** This is where you add some context
	 *
	 * @param string $context context['this'] Being the Twig's {{ this }}.
	 */
	public function add_to_context( $context ) {
		$context['menu']  = new \Timber\Menu( 'main-menu' );
		$context['footer_menu']  = new \Timber\Menu( 'footer-menu' );
		$whatsapp = carbon_get_theme_option( 'rc_whatsapp' );
		$wsp_msg = carbon_get_theme_option( 'rc_whatsapp_message' );
		$context['social'] = array(
			'phone'			=> carbon_get_theme_option( 'rc_phone' ),
			'whatsapp'		=> $whatsapp,
			'email'			=> carbon_get_theme_option( 'rc_email' ),
			'facebook'		=> carbon_get_theme_option( 'rc_facebook' ),
			'instagram'		=> carbon_get_theme_option( 'rc_instagram' ),
			'tiktok'		=> carbon_get_theme_option( 'rc_tiktok' ),
			'whatsapp_link'	=> 'https://wa.me/51' . $whatsapp . '?text=' . ($wsp_msg ? urlencode($wsp_msg) : ''),
		);
		$context['site']  = $this;
		return $context;
	}

	public function rc_register_fields() {
		Container::make( 'theme_options', 'Opciones' )
			->set_icon( 'dashicons-carrot' )
			->add_fields( array(
				Field::make( 'text', 'rc_phone', 'Teléfono' )
					->set_attribute( 'type', 'tel' ),
				Field::make( 'text', 'rc_whatsapp', 'Whatsapp' )
					->set_attribute( 'type', 'tel' ),
				Field::make( 'rich_text', 'rc_whatsapp_message', 'Mensaje de whatsapp' ),
				Field::make( 'text', 'rc_email', 'Email' )
					->set_attribute( 'type', 'email' ),
				Field::make( 'text', 'rc_facebook', 'Facebook' )
					->set_attribute( 'type', 'url' ),
				Field::make( 'text', 'rc_instagram', 'Instagram' )
					->set_attribute( 'type', 'url' ),
				Field::make( 'text', 'rc_tiktok', 'Tik Tok' )
					->set_attribute( 'type', 'url' ),
			) );
		Container::make( 'post_meta', 'Custom Data' )
		->where( 'post_id', '=', get_option( 'page_on_front' ) )
		->add_fields( array(
			Field::make( 'complex', 'rc_slider', __( 'Slider' ) )
				->add_fields( array(
					Field::make( 'image', 'image', 'Slide Photo' ),
					Field::make( 'text', 'button_text', 'Slide Button Text' ),
					Field::make( 'text', 'link', 'Slide Button Link' )
						->set_attribute( 'type', 'url' ),
				) ),
			Field::make( 'separator', 'rc_items_grid_separator', 'Items Grid' ),
			Field::make( 'text', 'rc_items_grid_title', 'Título' ),
			Field::make( 'association', 'rc_items_grid_items', 'Items' )
				->set_types( array(
					array(
						'type'	=> 'post'
					),
					array(
						'type'	=> 'term'
					),
				) ),
			Field::make( 'separator', 'rc_products_slider', 'Slider de Productos' ),
			Field::make( 'text', 'rc_products_grid_title', 'Título' ),
			Field::make( 'association', 'rc_products_grid', 'Productos' )
				->set_types( array(
					array(
						'type'		=> 'post',
						'post_type'	=> 'product',
					),
				) ),
			Field::make( 'separator', 'rc_tiles_separator', 'Grupos de Mosaicos' ),
			Field::make( 'text', 'rc_tiles_title', 'Título' ),
			Field::make( 'complex', 'rc_tiles', 'Mosaicos' )
				->add_fields( array(
					Field::make( 'image', 'image', 'Imagen' ),
					Field::make( 'text', 'title', 'Título' ),
					Field::make( 'association', 'products', 'Productos' )
						->set_types( array(
							array(
								'type'		=> 'post',
								'post_type'	=> 'product'
							)
						) )
				) ),
			Field::make( 'separator', 'rc_products_separator', 'Filas de Productos' ),
			Field::make( 'complex', 'rc_products', 'Productos' )
				->add_fields( array(
					Field::make( 'text', 'title', 'Título' ),
					Field::make( 'association', 'products', 'Productos' )
						->set_types( array(
							array(
								'type'		=> 'post',
								'post_type'	=> 'product'
							)
						) )
				) ),
		));
	}

	public function theme_supports() {
		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		/*
		 * Enable support for Post Formats.
		 *
		 * See: https://codex.wordpress.org/Post_Formats
		 */
		add_theme_support(
			'post-formats',
			array(
				'aside',
				'image',
				'video',
				'quote',
				'link',
				'gallery',
				'audio',
			)
		);

		add_theme_support( 'menus' );

		// add support for woocommerce
		add_theme_support( 'woocommerce' );

		// add support for woocommerce gallery
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );

		// boot carbon fields
		\Carbon_Fields\Carbon_Fields::boot();
	}

	/** This Would return 'foo bar!'.
	 *
	 * @param string $text being 'foo', then returned 'foo bar!'.
	 */
	public function myfoo( $text ) {
		$text .= ' bar!';
		return $text;
	}

	/** This is where you can add your own functions to twig.
	 *
	 * @param object $twig get extension.
	 */
	public function add_to_twig( $twig ) {
		$twig->addExtension( new Twig\Extension\StringLoaderExtension() );
		$twig->addFilter( new Twig\TwigFilter( 'myfoo', array( $this, 'myfoo' ) ) );
		$twig->addFunction( new \Timber\Twig_Function( 'set_global_product', 'set_global_product' ) );
		return $twig;
	}

}

new StarterSite();

function set_global_product( $p ) {
	global $product;
	global $post;
	$product = $p;
	$post = get_post( $p->id );
}

function rc_rest_contact( $req ) {
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	$to = carbon_get_theme_option( 'rc_email' );
	$subject = 'Nuevo mensaje de contacto';
	$context = array(
		'name' 		=> $req['name'],
		'email' 	=> $req['email'],
		'message' 	=> $req['message'],
	);
	$message = Timber::compile( 'email/contact.twig', $context );
	wp_mail( $to, $subject, $message, $headers );
	$res = new WP_REST_Response();
	$res->set_status(200);

	return ['response' => $res];
}