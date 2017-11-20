<?php
/**
* Plugin Name: TW Search
* Plugin URI: http://www.twistermc.com/
* Description: Adding search on an overlay.
* Version: 0.1
* Author: Thomas McMahon
* Author URI: http://www.twistermc.com/
* License: A "Slug" license name e.g. GPL12
*/

/**
 * Get registered navigation menus
 */
function twSearchGetNavMenus() {
    $menus = get_terms( 'nav_menu', array( 'hide_empty' => true ) );
    $navMenus = array( 'none'   => __( 'None (use .js-twSearch class)' ), );
    foreach ( $menus as $key => $value ) {
        $navMenus[$value->slug] = $value->name;
    }
    return $navMenus;
}

/**
 * Settings
 * Adds the individual sections, settings, and controls to the theme customizer
 */
function twSearchSettings( $wp_customize ) {
    $wp_customize->add_section(
        'twSearch',
        array(
            'title' => 'TW Search Settings',
            'description' => 'Customize the search settings.',
            'priority' => 35,
        )
    );
    /* Background Appearance */
    $wp_customize->add_setting(
        'twSearch_color',
        array(
            'default' => 'dark',
        )
    );
    $wp_customize->add_control(
        'twSearch_color',
        array(
            'label' => 'Overlay Background',
            'section' => 'twSearch',
            'type' => 'radio',
            'choices'        => array(
                'dark'   => __( 'Dark' ),
                'light'  => __( 'Light' )
            )
        )
    );
    /* Location */
    $wp_customize->add_setting(
        'twSearch_location',
        array(
            'default' => 'none',
        )
    );
    $wp_customize->add_control(
        'twSearch_location',
        array(
            'label' => 'Add To Menu',
            'section' => 'twSearch',
            'type' => 'radio',
            'choices'        => twSearchGetNavMenus()
        )
    );
    /* Display */
    $wp_customize->add_setting(
        'twSearch_display',
        array(
            'default' => 'icon',
        )
    );
    $wp_customize->add_control(
        'twSearch_display',
        array(
            'label' => 'Display As',
            'section' => 'twSearch',
            'type' => 'radio',
            'choices'        => array(
                'icon'   => __( 'Icon' ),
                'word'  => __( 'Search' ),
                'both'  => __( 'Both' )
            )
        )
    );
}
add_action( 'customize_register', 'twSearchSettings' );

/**
 * Add Search Overlay
 */
function twSearch(){

    $twSearchColor = 'dark'; // default
    $twSearchColor = get_theme_mod('twSearch_color');

    ?>

    <!-- meet and potatoes -->
    <div class="twSearchPopup">
      <div class="twSearchBg twSearchBg-<?php echo $twSearchColor; ?>"></div>
      <div class="twSearchFormWrapper">
        <form action="/">
          <div class="twSearchForm">
            <input type="search" class="twSearchBox" value="<?php echo get_search_query(); ?>" name="s" placeholder="input search string and hit enter">
            <label for="search">Search</label>
              <input type="submit" value="Search" class="searchButton">
              <div class="twSearchBoxDesc">input search string and hit enter</div>
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
    wp_enqueue_style( 'twSearch-css', plugins_url('twStyle.css', __FILE__) );
    wp_enqueue_script( 'twSearch-js', plugin_dir_url( __FILE__ ) . 'twScripts.js', array('jquery'), '1.0.1', false );
}

add_action( 'wp_enqueue_scripts', 'twSearch_addCSSJS' );

/**
 * Add Search link to menu
 */
add_filter( 'wp_nav_menu_items', 'FE_twSearch', 10, 2 );
function FE_twSearch( $items, $args ) {

    $twSearchLocation = get_theme_mod( 'twSearch_location');
    $twSearchDisplay = 'icon'; // default;
    $twSearchDisplay = get_theme_mod( 'twSearch_display');

    $menuSlug = $args->menu->slug;

    // Sometimes things don't return like we expected.
    if (!$menuSlug) {
        $menuSlug = get_object_vars($args);
        $menuSlug = $menuSlug[menu];
    }

    if ($twSearchLocation) {
        if( $menuSlug == $twSearchLocation ) {
            $items .= '<li class="twSearch">';
            if ($twSearchDisplay == 'icon') {
                $items .= '<a href="#" class="js-twSearch twSearchIcon"><i class="fa fa-search"></i><span class="twSearchIsHidden">Search</span></a>';
            } else if ($twSearchDisplay == 'word') {
                $items .= '<a href="#" class="js-twSearch">Search</a>';
            } else {
                $items .= '<a href="#" class="js-twSearch twSearchIcon"><i class="fa fa-search"></i> Search</a>';
            }
            $items .= '</li>';
            return $items;
        }
        return $items;
    }
}
