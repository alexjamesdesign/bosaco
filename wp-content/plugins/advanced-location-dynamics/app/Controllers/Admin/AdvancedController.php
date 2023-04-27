<?php

namespace Adtrak\AdvancedLocationDynamics\Controllers\Admin;

use Adtrak\AdvancedLocationDynamics\Controllers\Controller;
use Adtrak\AdvancedLocationDynamics\View;

class AdvancedController extends Controller
{

    /**
     * Undocumented function
     */
    public function __construct()
    {
    }

    /**
     * Add pages to the WordPress admin menu
     *
     * @return void
     */
    public function menu()
    {
        add_submenu_page(
            'advanced-location-dynamics',
            'Advanced Dynamics',
            'Advanced Dynamics',
            'publish_posts',
            'advanced-location-dynamics-advanced-dashboard',
            [$this, 'showAdvancedDashboard']
        );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function showAdvancedDashboard()
    {
        if ($_POST) {
            update_option('ald_advanced', $_POST['ald_advanced']);
            update_option('ald_advanced_number', $_POST['ald_advanced_number']);
        }

        $advanced = get_option('ald_advanced');
        $advanced_number = get_option('ald_advanced_number');

        return View::render('admin/ald/index.twig', [
            'advanced'  => (isset($advanced)) ? $advanced : 0,
            'advanced_number'  => $advanced_number,
        ]);
    }

}
