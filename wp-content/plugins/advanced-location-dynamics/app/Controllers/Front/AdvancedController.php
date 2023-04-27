<?php
namespace Adtrak\AdvancedLocationDynamics\Controllers\Front;

use Adtrak\AdvancedLocationDynamics\View;
use Adtrak\AdvancedLocationDynamics\Models\Location;
use Adtrak\AdvancedLocationDynamics\Controllers\Controller;

class AdvancedController extends Controller
{

    /**
     * Undocumented function
     */
    public function __construct()
    {
    }

    /**
     * Serve the required function of the class for the loader.php
     *
     * @return void
     */
    public function classActions()
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
        add_action('ald_single', [$this, 'showSingle'], 10, 2);
        add_action('ald_default', [$this, 'showDefault'], 10, 1);
        add_action('ald_list', [$this, 'showList'], 10, 2);
        add_action('ald_location', [$this, 'showLocation'], 10, 1);
        add_action('ald_set', [$this, 'isSet'], 10, 0);


        add_action('ctm_single', [$this, 'showSingle'], 10, 2);
        add_action('ctm_default', [$this, 'showDefault'], 10, 1);
        add_action('ctm_list', [$this, 'showList'], 10, 2);
        add_action('ctm_location', [$this, 'showLocation'], 10, 0);
        add_action('ctm_set', [$this, 'isSet'], 10, 0);
    }

    /**
     * Create the shortcodes
     *
     * @return void
     */
    public function addShortcodes()
    {
        add_shortcode('ald_single', array( $this, 'showSingleShortcode' ));
        add_shortcode('ald_default', array( $this, 'showDefaultShortcode' ));
        add_shortcode('ald_list', array( $this, 'showListShortcode' ));
        add_shortcode('ald_location', array( $this, 'showLocationShortcode' ));
        add_shortcode('ald_set', array( $this, 'checkSetShortcode' ));
        add_shortcode('ald_not_set', array( $this, 'checkNotSetShortcode' ));


        add_shortcode('ctm_single', array( $this, 'showSingleShortcode' ));
        add_shortcode('ctm_default', array( $this, 'showDefaultShortcode' ));
        add_shortcode('ctm_list', array( $this, 'showListShortcode' ));
        add_shortcode('ctm_location', array( $this, 'showLocationShortcode' ));
    }


    public function showSingleShortcode($atts = null)
    {
        $location = (isset($atts['location'])) ? filter_var($atts['location'], FILTER_SANITIZE_STRIPPED) : 'UK';
        $calltag = (isset($atts['calltag'])) ? filter_var($atts['calltag'], FILTER_VALIDATE_BOOLEAN) : true;
        return $this->showSingle($location, $calltag, true);
    }

    /**
     * Show a single number dependant on the location param
     *
     * @param boolean $calltag
     * @return void
     */
    public function showSingle($location, $calltag = true, $isShortcode = false)
    {
        $location = strval($location);
        $calltag = boolval($calltag);
        $isShortcode = (is_array($isShortcode)) ? false : boolval($isShortcode);

        return (new LDController)->showSingle($location, $calltag, $isShortcode);
    }

    /**
     * Show the default phone number within a shortcode
     *
     * @param array $atts
     * @return void
     */
    public function showDefaultShortcode($atts = null)
    {        
        $calltag = (isset($atts['calltag'])) ? filter_var($atts['calltag'], FILTER_VALIDATE_BOOLEAN) : true;

        return $this->showDefault($calltag, true);
    }

    /**
     * Show the default phone number
     *
     * @param boolean $calltag
     * @return void
     */
    public function showDefault($calltag = true, $isShortcode = false)
    {
        $isShortcode = (is_array($isShortcode)) ? false : boolval($isShortcode);

        if ($this->checkForAdvancedCookie() && $this->checkAdvancedEligible()) {
            ob_start();

            echo $this->numberEngine(
                ALD_DEFAULT,
                [
                    'calltag'   => $calltag,
                ]
            );

            $content = ob_get_contents();
            ob_end_clean();

            if ($isShortcode) return $content;
            echo $content;
        } else {
            return (new LDController)->showDefault($calltag, $isShortcode);
        }
    }

    /**
     * Show a list of phone numbers within a shortcode
     *
     * @param array $atts
     * @return void
     */
    public function showListShortcode($atts = null)
    {        
        $type = (isset($atts['type'])) ? filter_var($atts['type'], FILTER_SANITIZE_STRIPPED) : 'dropdown';
        $label = (isset($atts['label'])) ? filter_var($atts['label'], FILTER_SANITIZE_STRIPPED) : 'Other Numbers';

        return $this->showList($type, $label, true);
    }

    /**
     * Show a list of phone numbers
     *
     * @param string $type
     * @param string $label
     * @return void
     */
    public function showList($type = 'dropdown', $label = 'Other Numbers', $isShortcode = true)
    {
        $isShortcode = (is_array($isShortcode)) ? false : boolval($isShortcode);

        $type = strval($type);
        $label = strval($label); 

        return (new LDController)->showList($type, $label, $isShortcode);
    }

    public function showLocationShortcode($atts = null)
    {
        return $this->showLocation(true);
    }

    /**
     * Show the location from a query string or default location
     *
     * @return void
     */
    public function showLocation($isShortcode = false)
    {
        $isShortcode = (is_array($isShortcode)) ? false : boolval($isShortcode);

        if ($this->checkForAdvancedCookie() && $this->checkAdvancedEligible()) {
            ob_start();

            $location = Location::where('ids', 'LIKE', '%'. $this->getCookie() .'%')->first();
            echo $location->location;

            $content = ob_get_contents();
            ob_end_clean();

            if ($isShortcode) return $content;
            echo $content;
        } else {
            if ($isShortcode) {
                return (new LDController)->showLocationShortcode();
            } else {
                return (new LDController)->showLocation();
            }
        }
    }

    public function isSet()
    {
        if (!$this->checkAdvancedEligible()) return false;
        return $this->checkForAdvancedCookie();
    }

    public function isNotSet()
    {
        if (!$this->checkAdvancedEligible()) return true;
        return ($this->checkForAdvancedCookie()) ? false : true;
    }

    public function checkSetShortcode($atts = [], $content = null) 
    {            
        ob_start();

        if ($this->isSet()) {
            echo do_shortcode($content);
        }

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
    }

    public function checkNotSetShortcode($atts = [], $content = null) 
    {            
        ob_start();

        if ($this->isNotSet()) {
            echo do_shortcode($content);
        }

        $content = ob_get_contents();
        ob_end_clean();

        return $content;
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
