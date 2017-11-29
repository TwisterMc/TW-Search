<?php
/**
 * @wordpress-plugin
 * Plugin Name:       TW Search
 * Plugin URI:        https://www.twistermc.com/43150/tw-search-overlay-for-wordpress/
 * Description:       Adds a search icon to menu and displays search input in an overlay.
 * Version:           0.2.1
 * Author:            Thomas McMahon
 * Author URI:        http://www.twistermc.com/
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       tw-search
 * Domain Path:       /languages
 * GitHub Plugin URI: TwisterMc/TW-Search
 */

/**
 * Exit early if directly accessed via URL.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * The plugin version.
 *
 * This should match the version string in the plugin bootstrap header.
 * If you update the version number in the plugin header, update this constant to match, and vice versa.
 *
 * @var string TWS_VERSION
 */
define( 'TWS_VERSION', '0.2.1' );

/**
 * The full path and filename of this plugin file with symlinks resolved.
 *
 * @var string TWS_FILE
 */
define( 'TWS_FILE', __FILE__ );

/**
 * The full path to this plugin directory with symlinks resolved.
 *
 * @var string TWS_DIR
 */
define( 'TWS_DIR', dirname( TWS_FILE ) . '/' );

/**
 * The relative path to this plugin directory, from WP_PLUGIN_DIR.
 *
 * @var string TWS_DIR
 */
define( 'TWS_REL_DIR', basename( TWS_DIR ) . '/' );

/**
 * The URL to this plugin directory, with trailing slash.
 *
 * Example: https://example.local/wp-content/plugins/tw-search/
 *
 * @const string TWS_URL
 */
define( 'TWS_URL', plugins_url( '/', TWS_FILE ) );

/**
 * Load the plugin textdomain.
 */
function tw_search_load_plugin() {
	load_plugin_textdomain( 'tw-search', false, TWS_REL_DIR . 'languages/' );
}

add_action( 'plugins_loaded', 'tw_search_load_plugin' );

/**
 * Add plugin settings to the Customizer.
 *
 * Adds the individual sections, settings, and controls to the theme customizer.
 *
 * @action customize_register
 *
 * @param WP_Customize_Manager $wp_customize WP_Customize_Manager instance.
 */
function tw_search_settings( $wp_customize ) {

	/**
	 * Add TW Search customize section.
	 */
	$wp_customize->add_section(
		'twSearch',
		array(
			'title'       => __( 'TW Search Settings', 'tw-search' ),
			'description' => __( 'Customize the search settings.', 'tw-search' ),
			'priority'    => 35,
		)
	);

	/**
	 * Add background appearance customize setting.
	 */
	$wp_customize->add_setting(
		'twSearch_color',
		array(
			'default' => 'dark',
		)
	);

	/**
	 * Add background appearance customize control.
	 */
	$wp_customize->add_control(
		'twSearch_color',
		array(
			'label'   => __( 'Overlay Background', 'tw-search' ),
			'section' => 'twSearch',
			'type'    => 'radio',
			'choices' => array(
				'dark'  => __( 'Dark', 'tw-search' ),
				'light' => __( 'Light', 'tw-search' ),
			),
		)
	);

	/**
	 * Add location customize setting.
	 */
	$wp_customize->add_setting(
		'twSearch_location',
		array(
			'default' => 'none',
		)
	);

	/**
	 * Add location customize control.
	 */
	$wp_customize->add_control(
		'twSearch_location',
		array(
			'label'   => __( 'Add To Menu', 'tw-search' ),
			'section' => 'twSearch',
			'type'    => 'radio',
			'choices' => tw_search_get_nav_menus(),
		)
	);

	/**
	 * Add display customize setting.
	 */
	$wp_customize->add_setting(
		'twSearch_display',
		array(
			'default' => 'icon',
		)
	);

	/**
	 * Add display customize control.
	 *
	 * If you change the 'Magnifying Glass Icon' label text, it needs to be updated in tw_search_esc_html as well.
	 *
	 * @see tw_search_esc_html()
	 */
	$wp_customize->add_control(
		'twSearch_display',
		array(
			'label'   => __( 'Display As', 'tw-search' ),
			'section' => 'twSearch',
			'type'    => 'radio',
			'choices' => array(
				'icon' => __( 'Magnifying Glass Icon', 'tw-search' ),
				'word' => __( 'Search (word)', 'tw-search' ),
				'both' => __( 'Both', 'tw-search' ),
			),
		)
	);
}

add_action( 'customize_register', 'tw_search_settings' );

/**
 * Add dashicon to radio button label.
 *
 * The use of esc_html in Customizer is preventing us from using a dashicon on a radio button label,
 * we can bypass that here. Be careful what you do with the esc_html filter.
 *
 * @filter esc_html
 *
 * @param $safe_text
 * @param $text
 *
 * @return string
 */
function tw_search_esc_html( $safe_text, $text ) {

	if ( __( 'Magnifying Glass Icon', 'tw-search' ) !== $text ) {
		return $safe_text;
	}

	return __( 'Magnifying Glass Icon', 'tw-search' ) . ' <span class="dashicons dashicons-search"></span>';
}

add_filter( 'esc_html', 'tw_search_esc_html', 10, 2 );

/**
 * Add Search Overlay
 */
function twSearch() {

	$twSearchColor = get_theme_mod( 'twSearch_color' );
	if ( ! $twSearchColor ) {
		$twSearchColor = 'dark'; // default
	}

	?>

	<!-- meet and potatoes -->
	<div class="twSearchPopup">
		<div class="twSearchBg twSearchBg-<?php echo $twSearchColor; ?>"></div>
		<div class="twSearchFormWrapper twSearchFormWrapper-<?php echo $twSearchColor; ?>">
			<form action="/">
				<div class="twSearchForm">
					<input type="search" class="twSearchBox" value="<?php echo get_search_query(); ?>" name="s"
						   placeholder="<?php _e( 'input search string and hit enter' ); ?>">
					<label for="search"><?php _e( 'Search' ); ?></label>
					<input type="submit" value="Search" class="searchButton">
					<div class="twSearchBoxDesc"><?php _e( 'input search string and hit enter' ); ?></div>
				</div>
			</form>
		</div>
	</div>

	<?php
}

add_action( 'get_footer', 'twSearch' );

/**
 * Add Custom CSS
 * For Search Overlay
 */

function twSearch_addCSSJS() {
	// Respects SSL, Style.css is relative to the current file
	wp_enqueue_style( 'twSearch-css', plugins_url( 'tw-search-style.css', __FILE__ ) );
	wp_enqueue_script( 'twSearch-js',
	                   plugin_dir_url( __FILE__ ) . 'tw-search-scripts.js',
	                   array( 'jquery' ),
	                   '1.0.1',
	                   false );
}

add_action( 'wp_enqueue_scripts', 'twSearch_addCSSJS' );

/**
 * Add Search link to menu
 */
add_filter( 'wp_nav_menu_items', 'FE_twSearch', 10, 2 );
function FE_twSearch( $items, $args ) {

	$twSearchLocation = get_theme_mod( 'twSearch_location' );
	$twSearchDisplay  = get_theme_mod( 'twSearch_display' );

	if ( ! $twSearchDisplay ) {
		$twSearchDisplay = 'icon'; // default;
	}

	$menuSlug = $args->menu->slug;

	// Sometimes things don't return like we expected.
	if ( ! $menuSlug ) {
		$menuSlug = get_object_vars( $args );
		$menuSlug = $menuSlug[ menu ];
	}

	if ( $twSearchLocation ) {
		if ( $menuSlug == $twSearchLocation ) {
			$items .= '<li class="twSearch">';
			if ( $twSearchDisplay == 'icon' ) {
				$items .= '<a href="#" class="js-twSearch twSearchIcon"><span class="dashicons dashicons-search"></span><span class="twSearchIsHidden">' . __( 'Search' ) . '</span></a>';
			} else if ( $twSearchDisplay == 'word' ) {
				$items .= '<a href="#" class="js-twSearch">' . __( 'Search' ) . '</a>';
			} else {
				$items .= '<a href="#" class="js-twSearch twSearchIcon"><span class="dashicons dashicons-search"></span> ' . __( 'Search' ) . '</a>';
			}
			$items .= '</li>';

			return $items;
		}

		return $items;
	}
}

/**
 * Get registered navigation menus utility function.
 */
function tw_search_get_nav_menus() {

	$menus = get_terms(
		'nav_menu',
		array(
			'hide_empty' => true,
		)
	);

	$nav_menus = array(
		'none' => __( 'None (use .js-twSearch class)', 'tw-search' ),
	);

	foreach ( $menus as $key => $value ) {
		$nav_menus[ $value->slug ] = $value->name;
	}

	return $nav_menus;
}
