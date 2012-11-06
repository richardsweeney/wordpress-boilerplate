<?php

add_theme_support('menus');
add_theme_support('post-thumbnails');
// add_image_size('test-thumbnail', 360, 176, TRUE);

/** Custom header function */
$defaults = array(
	'width'           => false,
	'height'         	=> false,
	'default-image'		=> get_bloginfo('template_directory') . '/images/logo.png',
	'random-default'	=> false,
	'flex-height'     => false,
	'flex-width'      => false,
	'header-text'     => false,
	'uploads'         => true
);
add_theme_support('custom-header', $defaults);


/** Register navigation menus */
function rps_register_menus() {
  register_nav_menus(
  	array(
  		'main_menu' => __('Huvudmeny', 'xbrdr'),
  		'about_us_menu' => __('Om Oss meny', 'xbrdr')
  	)
  );
}
add_action('init', 'rps_register_menus');

/** Register widget areas */
function rps_register_widget_areas() {
  register_sidebar(
  	array(
			'name' => __('Footer', 'xbrdr'),
			'id' => 'rps-footer-widget',
	    'before_widget' => '',
	    'after_widget' => '',
	    'before_title' => '<div class="title">',
	    'after_title' => '</div>'
		)
	);
	register_sidebar(
  	array(
			'name' => __('Press Sida Widget', 'xbrdr'),
			'id' => 'rps-press-widget',
	    'before_widget' => '',
	    'after_widget' => '',
	    'before_title' => '<strong>',
	    'after_title' => '</strong>'
		)
	);
}
add_action('init', 'rps_register_widget_areas');


/** Set useful site constants */
function rps_set_constants() {
	define('ROOT', get_bloginfo('template_directory'));
	define('IMG', get_bloginfo('template_directory') . '/images');
	define('JS', get_bloginfo('template_directory') . '/js');
	define('CSS', get_bloginfo('template_directory') . '/css');
	define('URL', get_bloginfo('url'));
	define('AKISMET_KEY', '9fd1d87831df');
}
add_action('init', 'rps_set_constants');

/** I18n */
function rps_set_language() {
	load_theme_textdomain('xbrdr', get_template_directory() . '/languages');
}
add_action('after_setup_theme', 'rps_set_language');


/** Stop WordPress from hardcoding width and height attributes */
function remove_width_attribute( $html ) {
   $html = preg_replace( '/(width|height)="\d*"\s/', '', $html );
   return $html;
}
add_filter('post_thumbnail_html', 'remove_width_attribute', 10);
add_filter('image_send_to_editor', 'remove_width_attribute', 10);


/** Remove dashboard widgets */
function rps_remove_dashboard_widgets() {
	remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
	remove_meta_box('dashboard_incoming_links', 'dashboard', 'normal');
	remove_meta_box('dashboard_recent_drafts', 'dashboard', 'side');
	remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
}
add_action('wp_dashboard_setup', 'rps_remove_dashboard_widgets' );


/** Enqueue CSS + JS */
function rps_enqueue_js_and_css() {
	$jsglobals = array(
		'templateDirectory'  => ROOT,
		'url'								 => URL,
	);

	// CSS
	wp_register_style('rps-main-css', CSS . '/main.css');
	wp_enqueue_style('rps-main-css');

  // JS
	wp_enqueue_script('jquery');
	wp_enqueue_script('rps-main-js', JS . '/main.js');
	wp_localize_script('rps-main-js', 'jsGlobals', $jsglobals);
}
add_action('wp_enqueue_scripts', 'rps_enqueue_js_and_css');

/** Enqueue Admin CSS + JS */
function rps_load_custom_wp_admin_style() {
	$jsglobals = array(
		'templateDirectory'  => ROOT,
		'url'								 => URL,
	);
	wp_enqueue_script('rps-admin-js', JS . '/admin.js');
  wp_register_style('rps-admin-css', CSS . '/admin.css', false, '1.0.0' );
  wp_enqueue_style('rps-admin-css');
	wp_localize_script('rps-admin-js', 'jsGlobals', $jsglobals);
}
add_action('admin_enqueue_scripts', 'rps_load_custom_wp_admin_style');


/** Remove WP version from header */
remove_action('wp_head', 'wp_generator');
function blank_version() {
  return '';
}
add_filter('the_generator','blank_version');


/** Tidy up the main navigation code */
function rps_print_main_navigation() {
  $walker = new rps_nav_walker();
  $menuClass = 'main-navigation';
  $id = 'main-navigation';
	$defaults = array(
		'menu'            => 'Main menu',
		'container'       => '',
		'container_class' => '',
		'container_id'    => '',
		'menu_class'      => $menuClass,
		'menu_id'					=> $id,
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'depth'           => 0,
		'walker'          => $walker,
	);
	return wp_nav_menu($defaults);
}


/** Custom navigation function to add active classes to stuff */
class rps_nav_walker extends Walker_Nav_Menu {
  function start_el(&$output, $item, $depth, $args) {
		global $wp_query, $post;
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$class_names = $value = '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = $class_names ? ' class="' . esc_attr( $class_names ) . '"' : '';

		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . '<li' . $id . $value . $class_names .'>';

		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


/** Include the custom post type class */
include_once('includes/Custom-post-type-class.php');

/** Create custom post type objects */
// $cptArray = null;
// $cptArray = array(
// 	'cptName' => 'front_push',
// 	'singularName' => __('front push', 'xbrdr'),
// 	'pluralName' => __('front pushes', 'xbrdr'),
// 	'slug' => 'front-push',
// 	'supports' => array('title'),
// );
// $push = new RPS_CreateCustomPostType($cptArray);
// add_action('init', array(&$push, 'createPostType'));




/** Change the text of 'Enter title here' for Commitee members */
// function rps_change_default_title($title) {
//   $screen = get_current_screen();
//   switch ($screen->post_type) {
//   	case 'test':
//   		$title = __('Ange testens namn', 'xbrdr');
//   		break;
//   }
//   return $title;
// }
// add_filter('enter_title_here', 'rps_change_default_title');


/**
 * Register meta boxes
 */
// function rps_add_meta_boxes() {
// 	global $post;
// 	add_meta_box(
// 		'rps-meta',
// 		__('Post Meta', 'xbrdr'),
// 		'rps_print_post_meta',
// 		'product',
// 		'normal',
// 		'high'
// 	);
// }
// add_action('add_meta_boxes', 'rps_add_meta_boxes');


/** Add extra meta to custom post types */
// function rps_print_post_meta() {
// 	global $post;
// 	$subheader = get_post_meta($post->ID, '_produkt-subheader', true);
// 	$excerpt = get_post_meta($post->ID, '_produkt-excerpt', true);
/*?>
  	<label for="produkt-subheader"><?php _e('Ange en underrubrik', 'xbrdr'); ?></label>
  	<input type="text" class="rps produkt-subheader" name="produkt-subheader" value="<?php echo esc_attr($subheader); ?>" />
  	<label for="produkt-excerpt"><?php _e('Lägg till ett utdrag till produkten som visas ut på alla produkt sidor', 'xbrdr'); ?></label>
  		<textarea class="rps produkt-excerpt" name="produkt-excerpt"><?php echo $excerpt; ?></textarea>
 	<?php
// }
*/


/** Save post meta */
// function rps_save_custom_meta() {
// 	global $post;

// 	// Stops WP from clearing post meta when autosaving
// 	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
// 	  return $post->ID;
// 	}
// 	if (isset($_POST['produkt-subheader'])) {
// 		$clean = sanitize_text_field($_POST['produkt-subheader']);
// 		update_post_meta($post->ID, '_produkt-subheader', $clean);
// 	}
// 	if (isset($_POST['produkt-excerpt'])) {
// 		$clean = esc_textarea($_POST['produkt-excerpt']);
// 		update_post_meta($post->ID, '_produkt-excerpt', $clean);
// 	}

// }
// add_action('save_post', 'rps_save_custom_meta');


/** Footer Widget - show a language switcher in the header */
// class Logo_Widget extends WP_Widget {

// 	public function __construct() {
// 		parent::__construct(
// 	 		'logo_widget',
// 			__('Logo Widget', 'xbrdr'),
// 			array('description' => __('Displays a small logo', 'xbrdr'))
// 		);
// 	}

//  	public function form($instance) {
//  		_e('This widget will automatically a small L J Hotels logo. No configuration is required.', 'xbrdr');
// 	}

// 	public function update($new_instance, $old_instance) {
// 	}

// 	public function widget($args, $instance) {
/*
// 	?>
// 		<img src="<?php echo IMG; ?>/ljh-logo-small.png" class="small-logo">
// 	<?php
// 	}
*/
// }

// /** Register the widget */
// add_action('widgets_init', 'rps_register_widgets');
// function rps_register_widgets() {
// 	register_widget('Logo_Widget');
// }


/* My nicer excerpt function */
function rps_nicer_excerpt($args = array()) {
	global $post;
	$defaults = array(
		'echo' => true,
		'words' => 28,
		'ellipsis' => '&hellip;',
		'link' => true,
		'linkClass' => 'read-more-link',
		'linkText' => __('Läs mer', 'xbrdr'),
		'linkContainer' => 'p',
		'allowedTags' => '<p><a><i><em><b><strong><ul><ol><li><span><blockquote>'
	);
	$args = wp_parse_args( $args, $defaults );
  $text = trim( strip_tags( $post->post_content, $args['allowedTags'] ) );
	$text = preg_replace( '/(?:(?:\r\n|\r|\n)\s*){2}/s', ' ', $text );
  $text = explode( ' ', $text );
  $numWords = count( $text );
  if( $numWords > $args['words'] ) {
	  array_splice( $text, $args['words'] );
	  $text = implode( ' ', $text );
	  if( $args['ellipsis'] != false ) {
		  $text .= $args['ellipsis'];
		}
	} else {
	  $text = implode( ' ', $text );
	}
	$text = force_balance_tags( $text );
  if( $numWords > $args['words'] && $args['link'] == true ) {
  	$text .= '<' . $args['linkContainer'] . ' class="' . $args['linkClass'] . '"><a href="' . get_permalink( $post->ID ) .  '" title="' . get_the_title( $post->ID ) . '">' . $args['linkText'] . '</a></' . $args['linkContainer'] . '>';
	}
	if( $args['echo'] ) {
 		echo apply_filters('the_content', $text);
 	} else {
 		return apply_filters('the_content', $text);
 	}
}


/** Change crop point for cropped images */
function rps_image_resize_dimensions($payload, $orig_w, $orig_h, $dest_w, $dest_h, $crop) {

	// Change this to a conditional that decides whether you
	// want to override the defaults for this image or not.
	if( false )
		return $payload;

	if ( $crop ) {
		// crop the largest possible portion of the original image that we can size to $dest_w x $dest_h
		$aspect_ratio = $orig_w / $orig_h;
		$new_w = min($dest_w, $orig_w);
		$new_h = min($dest_h, $orig_h);

		if ( !$new_w ) {
			$new_w = intval($new_h * $aspect_ratio);
		}

		if ( !$new_h ) {
			$new_h = intval($new_w / $aspect_ratio);
		}

		$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

		$crop_w = round($new_w / $size_ratio);
		$crop_h = round($new_h / $size_ratio);

		$s_x = 0; // [[ formerly ]] ==> floor( ($orig_w - $crop_w) / 2 );
		$s_y = 0; // [[ formerly ]] ==> floor( ($orig_h - $crop_h) / 2 );
	} else {
		// don't crop, just resize using $dest_w x $dest_h as a maximum bounding box
		$crop_w = $orig_w;
		$crop_h = $orig_h;

		$s_x = 0;
		$s_y = 0;

		list( $new_w, $new_h ) = wp_constrain_dimensions( $orig_w, $orig_h, $dest_w, $dest_h );
	}

	// if the resulting image would be the same size or larger we don't want to resize it
	if ( $new_w >= $orig_w && $new_h >= $orig_h )
		return false;

	// the return array matches the parameters to imagecopyresampled()
	// int dst_x, int dst_y, int src_x, int src_y, int dst_w, int dst_h, int src_w, int src_h
	return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );

}
add_filter('image_resize_dimensions', 'rps_image_resize_dimensions', 10, 6);


