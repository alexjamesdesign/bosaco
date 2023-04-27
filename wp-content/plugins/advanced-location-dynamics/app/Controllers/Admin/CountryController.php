<?php 
namespace Adtrak\AdvancedLocationDynamics\Controllers\Admin;

use Adtrak\AdvancedLocationDynamics\Controllers\Controller;
use Adtrak\AdvancedLocationDynamics\View;
use Adtrak\AdvancedLocationDynamics\Helper;

use Adtrak\AdvancedLocationDynamics\Models\Country;
use Adtrak\AdvancedLocationDynamics\Models\Location;

/**
 * Class to manage the country packs
 */
class CountryController extends Controller
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
            'Manage Countries',
            'Manage Countries',
            'manage_options',
            'advanced-location-dynamics-manage-countries',
            [$this, 'manageCountryOptions']
        );
    }

    /**
     * Function to route to the correct function depending on the value of the do var
     *
     * @return void
     */
    public function manageCountryOptions()
    {
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

        $do = $_GET['do'];

        switch($do) {

        default:
            $this->showCountryManager();
            break;

        case 'install':
            $this->installCountry();
            break;

        case 'delete':
            $this->deleteCountry();
            break;

        }
    }

    /**
     * Show the available countries
     *
     * @return void
     */
    public function showCountryManager()
    {
        $installedCountries = Country::where('installed', 1)->get();
        $availableCountries = Country::where('installed', 0)->get();
        return View::render(
            'admin/countries/index.twig', [
                'installedCountries' => $installedCountries,
                'availableCountries' => $availableCountries,
            ]
        );
    }

    /**
     * Install the selected country pack and check if this country is already installed
     *
     * @return void
     */
    public function installCountry()
    {
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

        $code = $_GET['cc'];

        if (isset($code)) {
            $checkCountry = Country::where('code', $code)->where('installed', 0)->get();
            if ($checkCountry->count() != 0) {
                $findPack = Helper::get('download_url') . strtolower($code) . '_locations.csv';
                echo $findPack;
                $pack = Helper::processCountryPack($findPack, false, $code);
                echo $pack->message; // TODO: return to view
            } else {
                echo 'This country is already installed'; // TODO: return to view
            }
        } else {
            return $this->redirect('admin.php?page=advanced-location-dynamics-manage-countries');
        }
    }

    /**
     * Delete the selected country pack 
     *
     * @return void
     */
    public function deleteCountry()
    {
        $_GET = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);

        $code = $_GET['code'];

        if (isset($code)) {
            Location::where('code', $code)->delete();
            $country = Country::where('code', $code)->first();
            $country->installed = 0;
            $country->save();
        } else {
            return $this->redirect('admin.php?page=advanced-location-dynamics-manage-countries');
        }
    }

}
