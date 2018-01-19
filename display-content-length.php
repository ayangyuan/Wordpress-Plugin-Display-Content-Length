<?php
/**
 * Display Content Length.
 *
 * Display Content Length is the only posts plugin for WordPress that
 * allows you to display the length of posts and sort them.
 *
 * @package   Display Content Length 
 * @author    Mr.ING <ayangyuan@gmail.com>
 * @license   GPL-2.0+
 * @link      https://squaredaway.studio/wordpress-plugin-display-content-length/ 
 * @copyright 1999-2018 
 *
 * @wordpress-plugin
 * Plugin Name: Display Content Length 
 * Plugin URI:  https://wordpress.org/plugins/display-content-length/ 
 * GitHub URI:  https://github.com/ayangyuan/Wordpress-Plugin-Display-Content-Length 
 * Author URI:  https://squaredaway.studio/wordpress-plugin-display-content-length/ 
 * Author:      Mr.ING 
 * Version:     1.0.3 
 * Text Domain: display-content-length 
 * Domain Path: /res/lang
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Description: Display content length of posts on your posts manage panel and sort them. 
 */

if ( ! defined( 'MR_ING_DCL_PLUGIN_FILE' ) ) {define( 'MR_ING_DCL_PLUGIN_FILE', __FILE__ );}

add_filter('manage_post_posts_columns', function ( $columns ) 
{
    $_columns = [];

    foreach( (array) $columns as $key => $label )
    {
        $_columns[$key] = $label; 
        if( 'title' === $key )
            $_columns['wpse_post_content_length'] = __( 'Length' );     
    }
    return $_columns;
} );

add_action( 'manage_post_posts_custom_column', function ( $column_name, $post_id ) 
{
    if ( $column_name == 'wpse_post_content_length')
        echo mb_strlen( get_post( $post_id )->post_content );

}, 10, 2 );

add_filter( 'manage_edit-post_sortable_columns', function ( $columns ) 
{
  $columns['wpse_post_content_length'] = 'wpse_post_content_length';
  return $columns;
} );

add_filter( 'posts_orderby', function( $orderby, \WP_Query $q )
{
    $_orderby = $q->get( 'orderby' );
    $_order   = $q->get( 'order' );

    if( 
           is_admin() 
        && $q->is_main_query() 
        && 'wpse_post_content_length' === $_orderby 
        && in_array( strtolower( $_order ), [ 'asc', 'desc' ] )
    ) {
        global $wpdb;
        $orderby = " LENGTH( {$wpdb->posts}.post_content ) " . $_order . " ";
    }
    return $orderby;
}, 10, 2 );


/**
 * Add links to the plugin action row.
 */
function mr_ing_dcl_plugin_actions( $links, $file ) {

        if ( plugin_basename( MR_ING_DCL_PLUGIN_FILE ) === $file ) {

                $new_links = array(
                        'support'    => '<a href = "http://wordpress.org/support/plugin/display_content_length">' . __( 'Support' ) . '</a>',
                        'donate'     => '<a href = "https://squaredaway.studio/donate/">' . __( 'Donate') . '</a>',
                        'contribute' => '<a href = "https://github.com/ayangyuan/Wordpress-Plugin-Display-Content-Length">' . __( 'Contribute' ) . '</a>',
                );

                $links = array_merge( $links, $new_links );
        }
        return $links;
}
add_filter( 'plugin_row_meta', 'mr_ing_dcl_plugin_actions', 10, 2 );

