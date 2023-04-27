<?php

namespace Adtrak\AdvancedLocationDynamics\Controllers\Admin;

use Billy\Framework\Models\Option;
use Adtrak\AdvancedLocationDynamics\View;
use Adtrak\AdvancedLocationDynamics\Controllers\Controller;

class SettingsController extends Controller
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
    public function menu() : void
    {
        add_submenu_page(
            'advanced-location-dynamics',
            'Settings',
            'Settings',
            'publish_posts',
            'advanced-location-dynamics-settings',
            [$this, 'showSettings']
        );
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public function showSettings()
    {
        if ($_POST) {
            echo $this->activateLicense();
        }

        $licensevalidity = Option::where('option_name', '=', 'adv-ld_license_status')->first();
        $key = Option::where('option_name', '=', 'adv-ld_license')->first();
        $validity = $licensevalidity->option_value;

        if ($key === NULL) {
            $licenseKey = '';
        } else {
            $licenseKey = $key->option_value;
        }

        if ((!isset($validity) || $validity === NULL) || $validity !== 'valid') {
            $validity = false;
        } else {
            $validity = true;
        }

        return View::render('admin/settings/index.twig', [
            'licenseValidity'   => $validity,
            'licenseKey'        => $licenseKey,
        ]);
    }

    private function activateLicense()
    {
        $this->sanitizeLicense($_REQUEST['license-key']);

        update_option('adv-ld_license', $_REQUEST['license-key']);

        // retrieve the license from the database
        $license = trim(get_option('adv-ld_license', ''));

        // data to send in our API request
        $api_params = [
            'edd_action' => 'activate_license',
            'license'    => $license,
            'item_name'  => urlencode('Advanced Location Dynamics'), // the name of our product in EDD
            'url'        => home_url()
        ];

        // Call the custom API.
        $response = wp_remote_post(ADTK_HOME_URL, ['timeout' => 15, 'sslverify' => false, 'body' => $api_params]);

        // make sure the response came back okay
        if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {
            $message = (is_wp_error($response) && !empty($response->get_error_message())) ? $response->get_error_message() : __('An error occurred, please try again.');
        } else {
            $license_data = json_decode(wp_remote_retrieve_body($response));
            if (false === $license_data->success) {
                switch ($license_data->error) {
                    case 'expired':
                        $message = sprintf(
                            'Your license key expired on %s.',
                            date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                        );
                        break;
                    case 'revoked':
                        $message = 'Your license key has been disabled.';
                        break;
                    case 'missing':
                        $message = 'Invalid license.';
                        break;
                    case 'invalid':
                    case 'site_inactive':
                        $message = 'Your license is not active for this URL.';
                        break;
                    case 'item_name_mismatch':
                        $message = sprintf('This appears to be an invalid license key for %s.', 'Advanced Location Dynamics');
                        break;
                    case 'no_activations_left':
                        $message = 'Your license key has reached its activation limit.';
                        break;
                    default:
                        $message = 'An error occurred, please try again.';
                        break;
                }
            }
        }

        update_option('adv-ld_license_status', $license_data->license);

        // Check if anything passed on a message constituting a failure
        if (!empty($message)) {
            echo $message;
        }
    }

    /**
     * Check new key against old
     *
     * @param string $new
     * @return void
     */
    private function sanitizeLicense($new) : void
    {
        $old = get_option('adv-ld_license');

        if ($old && $old != $new) {
            delete_option('adv-ld_license_status');
        }
    }
}
