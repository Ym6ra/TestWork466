<?php
/**
 * Twenty Twenty-Two functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_Two
 * @since Twenty Twenty-Two 1.0
 */


if ( ! function_exists( 'twentytwentytwo_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

	}

endif;

add_action( 'after_setup_theme', 'twentytwentytwo_support' );

if ( ! function_exists( 'twentytwentytwo_styles' ) ) :

	/**
	 * Enqueue styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_styles() {
		// Register theme stylesheet.
		$theme_version = wp_get_theme()->get( 'Version' );

		$version_string = is_string( $theme_version ) ? $theme_version : false;
		wp_register_style(
			'twentytwentytwo-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$version_string
		);

		// Add styles inline.
		wp_add_inline_style( 'twentytwentytwo-style', twentytwentytwo_get_font_face_styles() );

		// Enqueue theme stylesheet.
		wp_enqueue_style( 'twentytwentytwo-style' );

	}

endif;

add_action( 'wp_enqueue_scripts', 'twentytwentytwo_styles' );

if ( ! function_exists( 'twentytwentytwo_editor_styles' ) ) :

	/**
	 * Enqueue editor styles.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_editor_styles() {

		// Add styles inline.
		wp_add_inline_style( 'wp-block-library', twentytwentytwo_get_font_face_styles() );

	}

endif;

add_action( 'admin_init', 'twentytwentytwo_editor_styles' );


if ( ! function_exists( 'twentytwentytwo_get_font_face_styles' ) ) :

	/**
	 * Get font face styles.
	 * Called by functions twentytwentytwo_styles() and twentytwentytwo_editor_styles() above.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return string
	 */
	function twentytwentytwo_get_font_face_styles() {

		return "
		@font-face{
			font-family: 'Source Serif Pro';
			font-weight: 200 900;
			font-style: normal;
			font-stretch: normal;
			font-display: swap;
			src: url('" . get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Roman.ttf.woff2' ) . "') format('woff2');
		}

		@font-face{
			font-family: 'Source Serif Pro';
			font-weight: 200 900;
			font-style: italic;
			font-stretch: normal;
			font-display: swap;
			src: url('" . get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Italic.ttf.woff2' ) . "') format('woff2');
		}
		";

	}

endif;

if ( ! function_exists( 'twentytwentytwo_preload_webfonts' ) ) :

	/**
	 * Preloads the main web font to improve performance.
	 *
	 * Only the main web font (font-style: normal) is preloaded here since that font is always relevant (it is used
	 * on every heading, for example). The other font is only needed if there is any applicable content in italic style,
	 * and therefore preloading it would in most cases regress performance when that font would otherwise not be loaded
	 * at all.
	 *
	 * @since Twenty Twenty-Two 1.0
	 *
	 * @return void
	 */
	function twentytwentytwo_preload_webfonts() {
		?>
		<link rel="preload" href="<?php echo esc_url( get_theme_file_uri( 'assets/fonts/SourceSerif4Variable-Roman.ttf.woff2' ) ); ?>" as="font" type="font/woff2" crossorigin>
		<?php
	}

endif;

add_action( 'wp_head', 'twentytwentytwo_preload_webfonts' );

// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';




//add image and delet button




//add datatipe field
add_action('woocommerce_product_options_general_product_data',
function() {
	?>
	<div class="options_group">
		<?php	
		$arg = array(
			'id'                => '_data_field',
			'label'             => __( 'Product creation date', 'woocommerce' ),
			'placeholder'       => 'Ввод чисел',
			'description'       => __( 'Сhoose date', 'woocommerce' ),
			'type'              => 'date',
		);
		woocommerce_wp_text_input($arg);	
		?>
	</div>
	<?php 	
});
add_action( 'woocommerce_before_add_to_cart_form', 'art_get_text_field_before_add_card' );
function art_get_text_field_before_add_card() {

	// Вызываем объект товара
	$product = wc_get_product();

	// Записываем значения полей в переменные
	$text_field     = $product->get_meta( '_text_field', true );
	$num_field      = $product->get_meta( '_number_field', true );
	$textarea_field = $product->get_meta( '_textarea', true );

	// Выводим значения полей
	if ( $text_field ) :
		?>
		<div class="text-field">
			<strong>Текстовое поле: </strong>
			<?php echo $text_field; ?>
		</div>
	<?php endif;
	if ( $num_field ) : ?>
		<div class="number-field">
			<strong>Цифровое поле: </strong>
			<?php echo $num_field; ?>
		</div>
	<?php endif;
	if ( $textarea_field ) : ?>
		<div class="textarea-field">
			<strong>Область текста: </strong>
			<?php echo $textarea_field; ?>
		</div>
	<?php
	endif;
}
//add selector
add_action('woocommerce_product_options_general_product_data',
function() {
	?>
	<div class="options_group">
		<?php	
		$arg = array(
				'id'      => '_select',
				'label'   => 'Сhoose rarity',
				'options' => array(
				   'one'   => __( 'Rare', 'woocommerce' ),
				   'two'   => __( 'Frecuent', 'woocommerce' ),
				   'three' => __( 'Unusual', 'woocommerce' ),
				),
			 );
		woocommerce_wp_select($arg);
		?>
	</div>
	<?php
});

