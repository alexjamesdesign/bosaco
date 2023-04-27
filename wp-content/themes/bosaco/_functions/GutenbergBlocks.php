<?php 

// See: https://palmiak.github.io/timber-acf-wp-blocks/#/

// Using ACF Gutenberg Blocks package, look in this folder

add_filter( 'timber/acf-gutenberg-blocks-templates', function () {
  return ['_views/_blocks']; // default: ['views/blocks']
} );


// Disable all Gutenberg blocks, and then allow some defined ones

function filter_allowed_block_types_when_post_provided( $allowed_block_types, $editor_context ) {
    if ( ! empty( $editor_context->post ) ) {

      // Pre-defined Gutenberg blocks can be allowed here
      // For a list of all blocks, visit: https://wordpress.org/support/article/blocks/ or here https://gist.github.com/DavidPeralvarez/37c8c148f890d946fadb2c25589baf00 
      $allowedBlocks = array(
        // 'core/paragraph',
        // 'core/heading',
        // 'core/image',
        // 'core/gallery',
        // 'core/embed'
      );

      // Scan the directory for all our custom twig blocks
      $customBlocks = ( defined( 'WP_DEBUG' ) AND WP_DEBUG ) ? glob( __DIR__ . '/../_views/_blocks/*.twig', GLOB_ERR ) : glob( __DIR__ . '/../_views/_blocks/*.twig' );

      // For each of the twig files, add them to the allowedBlocks array
      foreach ($customBlocks as $block) {
        array_push($allowedBlocks, "acf/" . basename($block, ".twig"));
      }

      // And return the allowed blocks
      return $allowedBlocks;

    }

    return $allowed_block_types;
}
 
add_filter( 'allowed_block_types_all', 'filter_allowed_block_types_when_post_provided', 10, 2 );

// Add thumbnail to context for hero

add_filter( 'timber/acf-gutenberg-blocks-data/hero', function( $context ){
  $context['featured_image'] = get_the_post_thumbnail_url();
  return $context;
} );

// Add default parameters to blocks
// Commented out until tested further

// add_filter( 'timber/acf-gutenberg-blocks-default-data', function( $data ){
//   $data['default'] = array(
//       'post_type' => ['post','page'],
//   );
//   $data['pages'] = array(
//       'post_type' => 'page',
//   );
//   return $data;
// } );