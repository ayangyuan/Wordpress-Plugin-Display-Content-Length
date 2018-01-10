<?php
/*
Plugin Name: Display Content Length 
Plugin URI: https://wordpress.org/plugins/display-content-length/
Github: https://github.com/ayangyuan/Wordpress-Plugin-Display-Content-Length.git 
Description: Show post content lenth and sort.
Version: 1.0.0 
Author: Yuan Yang
Author URI: https://84361749.com
Text Domain: display-content-length

*/

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
