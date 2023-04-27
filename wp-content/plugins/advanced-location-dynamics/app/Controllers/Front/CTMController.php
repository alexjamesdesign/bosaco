<?php
namespace Adtrak\AdvancedLocationDynamics\Controllers\Front;

use Adtrak\AdvancedLocationDynamics\View;
use Adtrak\AdvancedLocationDynamics\Models\Number;
use Adtrak\AdvancedLocationDynamics\Models\Location;
use Adtrak\AdvancedLocationDynamics\Controllers\Controller;

class CTMController extends Controller
{

    /**
     * Undocumented function
     */
    public function __construct()
    {
        $this->typeDefinitions();
        $this->addActions();
        $this->addShortcodes();
    }

    /**
     * Create the actions
     *
     * @return void
     */
    public function addActions()
    {
        add_action('ctm_single', [$this, 'showSingle'], 10, 2);
        add_action('ctm_default', [$this, 'showDefault'], 10, 1);
        add_action('ctm_list', [$this, 'showList'], 10, 2);
        add_action('ctm_location', [$this, 'showLocation'], 10, 0);
    }

    /**
     * Create the shortcodes
     *
     * @return void
     */
    public function addShortcodes()
    {
        add_shortcode('ctm_single', array( $this, 'showSingle' ), 10);
        add_shortcode('ctm_default', array( $this, 'showDefault' ), 10);
        add_shortcode('ctm_list', array( $this, 'showList' ), 10);
        add_shortcode('ctm_location', array( $this, 'showLocation' ), 10);
    }

    /**
     * Show a single number dependant on the location param
     *
     * @param boolean $calltag
     * @return void
     */
    public function showSingle($location, $calltag = true)
    {
        return (new AdvancedController)->showSingle($location, $calltag);
    }

    /**
     * Show the default phone number
     *
     * @param boolean $calltag
     * @return void
     */
    public function showDefault($calltag = true)
    {
        return (new AdvancedController)->showDefault($calltag);
    }

    /**
     * Show a list of phone numbers
     *
     * @param string $type
     * @param string $label
     * @return void
     */
    public function showList($type = 'dropdown', $label = 'Other Numbers')
    {
        return (new AdvancedController)->showList($type, $label);
    }

    /**
     * Show the location from a query string or default location
     *
     * @return void
     */
    public function showLocation()
    {
        return (new AdvancedController)->showLocation();
    }

    /**
     * Number engine for most actions, routes to the correct switch with constants and serves a template
     *
     * @param integer $type
     * @param array $data
     * @return void
     */
    public function numberEngine($type = 0, $data = [])
    {
        $number = get_option('ald_advanced_number');
        $data['cookie'] = $this->getCookie();

        $data['location'] = Location::where('ids', 'LIKE', '%'. $this->getCookie() .'%')->first();

        switch ($type) {

            case ALD_DEFAULT:
                $data['number'] = $this->numberFormatter($number, strlen($data['location']->area_code) + 1);
                return View::render('front/ald/single.twig', $data);
                break;
            
            default:
                echo 'default phone number';
                break;
        }
    }

    /**
     * Format phone number to include the correct amount of spaces based on the area code
     *
     * @param string $number
     * @param integer $pattern
     * @return void
     */
    public function numberFormatter($number, $pattern = 4)
    {
        $number = str_replace(' ', '', $number);
        switch ($pattern) {
            case 3:
                $number = substr_replace($number, ' ', 7, -strlen($number));
                $number = substr_replace($number, ' ', 3, -strlen($number));
                return $number;
                break;

            case 4:
                $number = substr_replace($number, ' ', 7, -strlen($number));
                $number = substr_replace($number, ' ', 4, -strlen($number));
                return $number;
                break;

            case 5:
                $number = substr_replace($number, ' ', 8, -strlen($number));
                $number = substr_replace($number, ' ', 5, -strlen($number));
                return $number;
                break;
            
            default:
                # code...
                break;
        }
    }

    /**
     * Constant definitions
     *
     * @return void
     */
    public function typeDefinitions()
    {
        define('ALD_DEFAULT', 0);
    }

}
