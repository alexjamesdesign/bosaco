<?php

$context = Timber::context();
$context['posts'] = new Timber\PostQuery();

// $context['categories'] = get_categories( array(
//     'orderby' => 'name',
//     'order'   => 'ASC'
// ) );

$context['post'] = $timber_post; 

$context['categories'] = Timber::get_terms($args);

Timber::render( [ 'index.twig' ], $context );
