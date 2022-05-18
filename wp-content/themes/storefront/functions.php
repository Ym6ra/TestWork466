<?php
/**
 * Storefront engine room
 *
 * @package storefront
 */

/**
 * Assign the Storefront version to a var
 */
$theme              = wp_get_theme( 'storefront' );
$storefront_version = $theme['Version'];

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( ! isset( $content_width ) ) {
	$content_width = 980; /* pixels */
}

$storefront = (object) array(
	'version'    => $storefront_version,

	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-storefront.php',
	'customizer' => require 'inc/customizer/class-storefront-customizer.php',
);

require 'inc/storefront-functions.php';
require 'inc/storefront-template-hooks.php';
require 'inc/storefront-template-functions.php';
require 'inc/wordpress-shims.php';

if ( class_exists( 'Jetpack' ) ) {
	$storefront->jetpack = require 'inc/jetpack/class-storefront-jetpack.php';
}

if ( storefront_is_woocommerce_activated() ) {
	$storefront->woocommerce            = require 'inc/woocommerce/class-storefront-woocommerce.php';
	$storefront->woocommerce_customizer = require 'inc/woocommerce/class-storefront-woocommerce-customizer.php';

	require 'inc/woocommerce/class-storefront-woocommerce-adjacent-products.php';

	require 'inc/woocommerce/storefront-woocommerce-template-hooks.php';
	require 'inc/woocommerce/storefront-woocommerce-template-functions.php';
	require 'inc/woocommerce/storefront-woocommerce-functions.php';
}

if ( is_admin() ) {
	$storefront->admin = require 'inc/admin/class-storefront-admin.php';

	require 'inc/admin/class-storefront-plugin-install.php';
}

/**
 * NUX
 * Only load if wp version is 4.7.3 or above because of this issue;
 * https://core.trac.wordpress.org/ticket/39610?cversion=1&cnum_hist=2
 */
if ( version_compare( get_bloginfo( 'version' ), '4.7.3', '>=' ) && ( is_admin() || is_customize_preview() ) ) {
	require 'inc/nux/class-storefront-nux-admin.php';
	require 'inc/nux/class-storefront-nux-guided-tour.php';
	require 'inc/nux/class-storefront-nux-starter-content.php';
}

/**
 * Note: Do not add any custom code here. Please use a custom plugin so that your customizations aren't lost during updates.
 * https://github.com/woocommerce/theme-customisations
 */

//add image and delet button     

//failed to find a working function
add_theme_support( 'post-thumbnails', array( 'post' ) );//failed to find a working function
//failed to find a working function
add_action( 'woocommerce_product_options_general_product_data', 'add_image' );//failed to find a working function
 function add_image($post_id) {//failed to find a working function
 	?>
		<div>
			<button type="submit" class="upload_image_button button">Загрузить</button>
 			<button type="submit" class="remove_image_button button">×</button>
 		</div><?
 }//failed to find a working function

//add datatipe field
add_action('woocommerce_product_options_general_product_data', 'Add_field_of_create_data');
function Add_field_of_create_data(){	
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
};
//save value
add_action('woocommerce_process_product_meta', 'save_field_of_create_data', 10, 1);
function save_field_of_create_data($post_id){
	$new_data_field = isset($_POST['_data_field'] ) ? sanitize_text_field($_POST['_data_field'] ): '';
		update_post_meta($post_id, '_data_field', $new_data_field);
}


//add selector
add_action('woocommerce_product_options_general_product_data', 'Add_selector');
function Add_selector(){
	global $porduct, $post;
	?>
	<div class="options_group">;
	<?php
		woocommerce_wp_select(array(
			
				'id'      => '_select',
				'label'   => 'Rarity',
				'options' => array(
					'Rare'   => __( 'Rare', 'woocommerce' ),
					'Frequent'   => __( 'Frequent', 'woocommerce' ),
					'Unusual' => __( 'Unusual', 'woocommerce' ),
			),
		)
		);
	?></div>
	<?php
};
//save value
add_action('woocommerce_process_product_meta', 'save_selector', 10);
function save_selector($post_id){
	$meta_key = '_select';
	$custom_select = isset($_POST['_select'])? $_POST['_select']: '0';
		 if ( ! empty( $custom_select ) ) {
			update_post_meta( $post_id, $meta_key, $custom_select );
		}
};
//output for product page
add_action( 'woocommerce_before_add_to_cart_form', 'art_get_text_field_before_add_card' );
add_action( 'woocommerce_after_shop_loop_item_title', 'art_get_text_field_before_add_card' );
function art_get_text_field_before_add_card() {
	global $post , $product;
	$data_field = get_post_meta( $post->ID, '_data_field', true );
	$select_field = get_post_meta( $post->ID, '_select', true );
	if ( $data_field ) { ?>
		<div class="number-field">
			<strong>Дата создания: </strong>
			<?php echo $data_field; ?>
		</div>
	<?php }
	if ( $select_field ) { ?>
		<div class="select-field">
			<strong>Rarity: </strong>
			<?php echo $select_field; ?>
		</div>
	<?php
	}
}




// add button delet 
//failed to find a working function
add_action('woocommerce_product_options_general_product_data', 'Add_button_delete');//failed to find a working function
function Add_button_delete(){//failed to find a working function
	global $porduct, $post;//failed to find a working function
	?>
	<div class="options_group">;
	<form action="/wp-admin/post.php">
	<input type="submit" name="delete" id="deletepost" class="button button-primary button-large" value='Dlete custom information' ></button></div>
	</form>
	<?php
};//failed to find a working function

// add button update

add_action('woocommerce_product_options_general_product_data', 'Add_button_update');
function Add_button_update(){
	global $porduct, $post;
	?>
	<div class="options_group">;
	<input type="submit" name="save" id="publish" class="button button-primary button-large" value='Update All' ></button></div>
	<?php
};

