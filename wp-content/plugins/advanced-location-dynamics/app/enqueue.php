<?php 
namespace Adtrak\AdvancedLocationDynamics;

/**
 * @var \Billy\Framework\Enqueue $enqueue 
*/

$enqueue->admin(
    [
        'as' => 'ald-admin',
        'src' => Helper::assetUrl('css/admin.css')
    ]
);

$enqueue->admin(
    [
        'as' => 'ald-admin',
        'src' => Helper::assetUrl('js/admin-dist.js')
    ], 'footer'
);

$enqueue->front(
    [
        'as' => 'ald-front',
        'src' => Helper::assetUrl('js/front-dist.js')
    ], 'footer'
);

$enqueue->admin(
    [
        'as' => 'fa-admin',
        'src' => 'https://kit.fontawesome.com/64121b2dbe.js'
    ], 'footer'
);