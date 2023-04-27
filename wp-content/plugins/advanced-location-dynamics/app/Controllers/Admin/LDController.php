<?php

namespace Adtrak\AdvancedLocationDynamics\Controllers\Admin;

use Adtrak\AdvancedLocationDynamics\Controllers\Controller;
use Adtrak\AdvancedLocationDynamics\View;

use Adtrak\AdvancedLocationDynamics\Models\Number;

class LDController extends Controller
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
            'Location Dynamics',
            'Location Dynamics',
            'publish_posts',
            'advanced-location-dynamics-simple-dashboard',
            [$this, 'showSimpleDashboard']
        );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function showSimpleDashboard()
    {
        if($_POST) {
            update_option('ald_insight_type', $_REQUEST['insight-type']);
            update_option('ald_insight_id', $_REQUEST['tracking-id']);
        }

        $limit = 32;
        $pagenum = isset($_GET['pagenum']) ? absint($_GET['pagenum']) : 1;
        $offset = $limit * ($pagenum - 1);
        $total = Number::count();
        $totalPages = ceil($total / $limit);

        $numbers = Number::orderBy('created_at', 'asc')
            ->skip($offset)
            ->take($limit)
            ->get();

        $pagination = paginate_links(array(
            'base'      => add_query_arg('pagenum', '%#%'),
            'format'    => '',
            'prev_text' => __('&laquo;', 'text-domain'),
            'next_text' => __('&raquo;', 'text-domain'),
            'total'     => $totalPages,
            'current'   => $pagenum
        ));

        return View::render('admin/ld/index.twig', [
            'numbers'       => $numbers,
            'pagination'    => $pagination,
            'tracking_type' => get_option('ald_insight_type'),
            'tracking_id' => get_option('ald_insight_id')
        ]);
    }

    public function getNumber()
    {
        $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

        $number = Number::find($id)->toArray();

        echo json_encode([
            'id' => $id,
            'number' => $number
        ]);

        wp_die();
    }

    public function saveNumber()
    {
        if (isset($_POST['id'])) {
            $number = Number::find(filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT));
        } else {
            $number = new Number;
        }

        $number->location_name = filter_var($_POST['location-name'], FILTER_SANITIZE_STRING);
        $number->location_label = filter_var($_POST['location-label'], FILTER_SANITIZE_STRING);
        $number->calltag = filter_var($_POST['calltag'], FILTER_SANITIZE_STRING);
        $number->seo = filter_var($_POST['seo'], FILTER_SANITIZE_STRING);
        $number->ppc = filter_var($_POST['ppc'], FILTER_SANITIZE_STRING);
        $number->insights = filter_var($_POST['insights'], FILTER_SANITIZE_STRING);
        $number->tracking_label = filter_var($_POST['tracking-label'], FILTER_SANITIZE_STRING);

        try {
            $number->save();
        } catch (PDOExeption $e) {
            print_r($e);
        }

        echo json_encode([
            'type' => 'success',
            'number' => $number
        ]);

        wp_die();
    }

    public function deleteNumber()
    {
        $id = filter_var($_REQUEST['id'], FILTER_SANITIZE_NUMBER_INT);

        $number = Number::find($id)->delete();

        echo json_encode([
            'status' => 'success'
        ]);

        wp_die();
    }
}
