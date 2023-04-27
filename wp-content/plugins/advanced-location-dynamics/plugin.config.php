<?php

return [

	/**
     * The version constraint.
     */
    'version' => '0.9.10',

    /**
     * The pack download url
     */
    'download_url'  => 'https://telecoms.adtrakdev.com/ald/',

    /**
     * The asset path.
     */
    'assets' => '/resources/assets/',

	/**
	 * Bugsnag API
	 */
    'bugsnag' => '',

    /**
     * Views
     */
    'views' => __DIR__ . '/resources/views',

    /**
     * Plugin URL
     */
    'url' => plugin_dir_url(__FILE__),

	/**
     * Templates
     */
	'templates'	=> 'advanced-ld', // folder in child theme to load

    /**
     * Activate
     */
    'activators' => [
        __DIR__ . '/app/activate.php'
    ],

    /**
     * Deactivate
     */
    'deactivators' => [
        __DIR__ . '/app/deactivate.php'
    ],

    /**
     * Loader
     */
    'loader' => [
        __DIR__ . '/app/loader.php'
    ],

    /**
     * The styles and scripts to auto-load.
     */
    'enqueue' => [
        __DIR__ . '/app/enqueue.php'
    ]
];
