<?php

namespace Adtrak\AdvancedLocationDynamics;

// Admin Classes
$admin = new Controllers\AdminController;
$ldAdmin = new Controllers\Admin\LDController;

// Front Classes
$ldFront = new Controllers\Front\LDController;
$advanced = new Controllers\Front\AdvancedController;


/**
 * Admin Loads
 */

$loader->action([
    'method' =>     'admin_menu',
    'uses'   =>     [$admin, 'menu']
]);

$loader->action([
    'method' =>     'admin_head',
    'uses'   =>     [$admin, 'setBaseUrl']
]);

// $loader->action([
//  	'method' => 	'init',
//  	'uses'   => 	[$admin, 'checkForNewLocations']
// ]);

/**
 * Front Loads
 */

$loader->action([
 	'method' => 	'init',
 	'uses'   => 	[$ldFront, 'classActions']
]);

$loader->action([
 	'method' => 	'init',
 	'uses'   => 	[$advanced, 'classActions']
]);

$loader->action([
 	'method' => 	'init',
 	'uses'   => 	[$ldFront, 'setCookie']
]);

$loader->action([
 	'method' => 	'wp_head',
 	'uses'   => 	[$ldFront, 'getInsightCode']
]);

/**
 * AJAX Requests
 */

$loader->action([
    'method'     => 'wp_ajax_get_number',
    'uses'     => [$ldAdmin, 'getNumber']
]);

$loader->action([
    'method'     => 'wp_ajax_save_number',
    'uses'     => [$ldAdmin, 'saveNumber']
]);

$loader->action([
    'method'     => 'wp_ajax_delete_number',
    'uses'     => [$ldAdmin, 'deleteNumber']
]);