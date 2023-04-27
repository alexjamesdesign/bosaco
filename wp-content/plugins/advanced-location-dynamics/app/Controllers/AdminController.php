<?php

namespace Adtrak\AdvancedLocationDynamics\Controllers;

use Billy\Framework\Models\Option;
use Adtrak\AdvancedLocationDynamics\View;

use Adtrak\AdvancedLocationDynamics\Helper;
use Adtrak\AdvancedLocationDynamics\Controllers\Admin\LDController;
use Adtrak\AdvancedLocationDynamics\Controllers\Admin\CountryController;
use Adtrak\AdvancedLocationDynamics\Controllers\Admin\AdvancedController;
use Adtrak\AdvancedLocationDynamics\Controllers\Admin\SettingsController;

class AdminController extends Controller
{

    private $_ldController;
    private $_countryController;
    private $_advancedController;
    private $_settingsController;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->_ldController = new LDController;
        $this->_countryController = new CountryController;
        $this->_advancedController = new AdvancedController;
        $this->_settingsController = new SettingsController;
    }

    public function setBaseUrl()
    {
        wp_localize_script('ald-admin', 'ALDajax', ['ajaxurl' => admin_url('admin-ajax.php'), 'plugin_url' => Helper::get('url')]);
    }

    /**
     * Add pages to the WordPress admin menu along with child controllers
     *
     * @return void
     */
    public function menu()
    {
        add_menu_page(
            'Advanced Location Dynamics',
            'Advanced Location Dynamics',
            'manage_options',
            'advanced-location-dynamics',
            [$this, 'displayMainPage'],
            'dashicons-phone'
        );

        $this->_ldController->menu();

        if ($this->checkAdvancedEligible()) {
            $this->_advancedController->menu();
        }

        $this->_countryController->menu();
        $this->_settingsController->menu();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function displayMainPage()
    {
        $allowedAdvanced = $this->checkAdvancedEligible();

        $licensevalidity = Option::where('option_name', '=', 'adv-ld_license_status')->first();
        $validity = $licensevalidity->option_value;

        if ((!isset($validity) || $validity === NULL) || $validity !== 'valid') {
            $validity = false;
        }

        return View::render('admin/index.twig', [
            'advancedEligible'  => $allowedAdvanced,
            'licenseValidity'   => $validity,
        ]);
    }

    public function checkForNewLocations()
    {
        $packs = ['uk_locations'];

        foreach ($packs as $pack) {
            $urlBuilder = Helper::get('download_url') . $pack . '.csv';
            $externalFile = self::curl_get_size($urlBuilder);
            $internalFile = filesize(Helper::asset('csv/' . $pack . '.csv'));
            if ($externalFile !== $internalFile) {
                Helper::processCountryPack($urlBuilder, false);
            }
        }
    }

    /**
     * Check if the plugin is activated to use the advanced feature set
     *
     * @return boolean
     */
    public function checkAdvancedEligible() : bool
    {
        $key = getenv('ADV_PRODUCTION_KEY');
        if (getenv('ADV_ENV') == 'local') {
            $key = getenv('ADV_LOCAL_KEY');
        } elseif (getenv('ADV_ENV') == 'staging') {
            $key = getenv('ADV_STAGING_KEY');
        }

        $local = false;
        if (getenv('ADV_ENV') == 'local') {
            $local = true;
        }

        if ((filter_var($local, FILTER_VALIDATE_BOOLEAN) && filter_var(getenv('ADV_LDMODE'), FILTER_VALIDATE_BOOLEAN) === false) ||
            (filter_var($local, FILTER_VALIDATE_BOOLEAN) === false && !empty(filter_var($key, FILTER_SANITIZE_STRING)) && $this->checkKey(filter_var($key, FILTER_SANITIZE_STRING)))
        ) {
            return true;
        }
        return false;
    }
}
