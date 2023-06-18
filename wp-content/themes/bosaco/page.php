<?php

$context = Timber::context();
$timber_post = new Timber\Post();
$context['post'] = $timber_post;
$context['posts'] = new Timber\PostQuery();


Timber::render( [ 'page-'.$timber_post->slug.'.twig', 'page.twig' ], $context );