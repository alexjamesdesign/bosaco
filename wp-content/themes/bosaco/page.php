<?php

$context = Timber::context();
$timber_post = new Timber\Post();
$context['post'] = $timber_post;
$context['posts'] = new Timber\PostQuery();

$context['featured_image'] = get_the_post_thumbnail_url(852);


Timber::render( [ 'page-'.$timber_post->slug.'.twig', 'page.twig' ], $context );