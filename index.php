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
 * Add Search
 */
function twSearch(){
    ?>

    <!-- optional code button -->
    <div class="search-button"><button class="js-twSearch"><i class="fa fa-search"></i></button></div>

    <!-- meet and potatoes -->
    <div class="twSearchPopup">
      <div class="twSearchBg"></div>
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
 * For Search
 */

function twSearch_addCSSJS() {
    // Respects SSL, Style.css is relative to the current file
    wp_enqueue_style( 'twSearch-css', plugins_url('twStyle.css', __FILE__) );
    wp_enqueue_script( 'twSearch-js', plugin_dir_url( __FILE__ ) . 'twScripts.js', array('jquery'), '1.0.1', false );
}

add_action( 'wp_enqueue_scripts', 'twSearch_addCSSJS' );
