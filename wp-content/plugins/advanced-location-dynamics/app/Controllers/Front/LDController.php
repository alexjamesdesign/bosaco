<?php
namespace Adtrak\AdvancedLocationDynamics\Controllers\Front;

use Adtrak\AdvancedLocationDynamics\View;
use Adtrak\AdvancedLocationDynamics\Models\Number;
use Adtrak\AdvancedLocationDynamics\Controllers\Controller;

class LDController extends Controller
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
        add_action('ld_default', [$this, 'showDefault'], 10, 1);
        add_action('ld_single', [$this, 'showSingle'], 10, 2);
        add_action('ld_list', [$this, 'listRouter'], 10, 3);
        add_action('ld_location', [$this, 'showLocation'], 10);
        add_action('ld_mobile_top', [$this, 'showMobile'], 10);
    }

    /**
     * Create the shortcodes
     *
     * @return void
     */
    public function addShortcodes()
    {
        add_shortcode('ld_single', array( $this, 'showSingleShortcode' ), 10);
        add_shortcode('ld_default', array( $this, 'showDefaultShortcode' ), 10);
        add_shortcode('ld_list', array( $this, 'showListShortcode' ), 10);
        add_shortcode('ld_location', array( $this, 'showLocationShortcode' ), 10);
        add_shortcode('ld_mobile_top', array( $this, 'showMobile' ), 10);
    }

    /**
     * Set the insights JS code
     *
     * @return void
     */
    public function getInsightCode()
    {
        ob_start();

        $insightType = get_option('ald_insight_type');
        if ($insightType != null) {
            $insightID = get_option('ald_insight_id');
            if ($insightType === LD_IT_CTM) {
                echo '<script async src="//'. $insightID .'.tctm.co/t.js"></script>';
            } elseif ($insightType === LD_IT_RT) {
                echo '<script type="text/javascript">var adiInit="'. $insightID .'",adiRVO=!0,adiFunc=null;!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src=("https:"==document.location.protocol?"https://static-ssl":"http://static-cdn")+".responsetap.com/static/scripts/rTapTrack.min.js";var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(t,a)}();</script>';
            }
        }

        $content = ob_get_contents();
        ob_end_clean();

        echo $content;
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

        ob_start();

        echo $this->numberEngine(
            LD_DEFAULT,
            [
                'calltag'   => $calltag,
            ]
        );

        $content = ob_get_contents();
        ob_end_clean();

        if ($isShortcode) return $content;
        echo $content;
    }

    public function showSingleShortcode($atts = null)
    {
        $location = (isset($atts['location'])) ? filter_var($atts['location'], FILTER_SANITIZE_STRIPPED) : 'UK';
        $calltag = (isset($atts['calltag'])) ? filter_var($atts['calltag'], FILTER_VALIDATE_BOOLEAN) : true;
        return $this->showSingle($location, $calltag, true);
    }

    /**
     * Show a single number dependant on the location input
     *
     * @param string $location
     * @param boolean $calltag
     * @return void
     */
    public function showSingle($location, $calltag = true, $isShortcode = false)
    {
        $isShortcode = (is_array($isShortcode)) ? false : boolval($isShortcode);

        ob_start();

        if (!isset($location) || $location === null || empty($location)) {

            echo 'ERROR: Location not set';

        } else {

            echo $this->numberEngine(
                LD_SINGLE,
                [
                    'location'  => $location,
                    'calltag'   => $calltag
                ]
            );

        }

        $content = ob_get_contents();
        ob_end_clean();

        if ($isShortcode) return $content;
        echo $content;
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
     * Backwards compatibility router to either serve the old way/params or the newer way
     *
     * Param 1: PPC or List Type
     * Param 2: List Type or List Label
     * Param 3; List Label
     *
     * @param [type] $param1
     * @param string $param2
     * @param string $param3
     * @return void
     */
    public function listRouter($param1 = null, $param2 = null, $param3 = null)
    {
	    $isShortcode = strtolower($param1) != 'dropdown';

	    ob_start();

	    if (is_bool($param1)) {
		    echo ($param3 == null) ? $this->showList($param2) : $this->showList($param2, $param3, $isShortcode);
	    } else {
		    echo ($param2 == null) ? $this->showList($param1) : $this->showList($param1, $param2, $isShortcode);
	    }

        $content = ob_get_contents();
        ob_end_clean();

        echo $content;
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

        if($isShortcode) {
            $type = 'inline';
        }

        ob_start();

        echo $this->numberEngine(
            LD_LIST,
            [
                'type'  => $type,
                'label' => $label
            ]
        );

        $content = ob_get_contents();
        ob_end_clean();


        if ($isShortcode) return $content;
        echo $content;
    }


    public function showLocationShortcode($atts = null)
    {
        return $this->showLocation(true);
    }

    /**
     * Show the location from a query string or default location
     *
     * TODO: Add a check for the default location
     *
     * @return void
     */
    public function showLocation($isShortcode = false)
    {
        $isShortcode = (is_array($isShortcode)) ? false : boolval($isShortcode);

        ob_start();

        if ($this->getCookie() && !$this->checkForAdvancedCookie()) {
            $number = Number::where('location_name', $this->getCookie())->first();
            if (isset($number) && !empty($number)) {
                echo $number->location_label;
            }
        } else {
            echo 'UK';
        }

        $content = ob_get_contents();
        ob_end_clean();

        if ($isShortcode) return $content;
        echo $content;
    }

    /**
     * Show the mobile button and dropdown
     *
     * @param string $label
     * @return void
     */
    public function showMobile($label = 'Call us today')
    {
        ob_start();

        echo $this->numberEngine(
            LD_MOBILE,
            [
                'label' => $label
            ]
        );

        $content = ob_get_contents();
        ob_end_clean();

        echo $content;
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
        $numbers = Number::all();
        $data['cookie'] = ($this->checkForAdvancedCookie()) ? false : $this->getCookie();

        if (empty($numbers)) {
            echo 'ERROR: No numbers set!';
        }

        switch ($type) {
            case LD_SINGLE:
                $number = $numbers->where('location_name', $data['location'])->first();
                $data['number'] = $number;

                return View::render('front/ld/single.twig', $data);
                break;

            case LD_DEFAULT:
                if (!$this->getCookie() || $this->checkForAdvancedCookie()) {
                    $number = $numbers->where('location_name', 'uk')->first();
                } else {
                    $number = $numbers->where('location_name', $this->getCookie())->first();
                }

                $data['number'] = $number;
                return View::render('front/ld/single.twig', $data);
                break;

            case LD_LIST:
                $numbers = Number::where('location_name', '!=', 'uk')->get();
                $data['numbers'] = $numbers;

                return View::render('front/ld/list.twig', $data);
                break;

            case LD_LOCATION:
                break;

            case LD_MOBILE:
                if ($this->getCookie() === false && $numbers->count() != 1) {
                    echo "<a class='js-toggle-location-numbers'>" . $data['label'] . "</a>";
                } else {
                    echo $this->showDefault(false);
                }
                break;

            default:
                echo 'default phone number';
                break;
        }
    }

    /**
     * Constant definitions
     */
    public function typeDefinitions()
    {
        define('LD_DEFAULT', 0);
        define('LD_SINGLE', 1);
        define('LD_LIST', 2);
        define('LD_LOCATION', 3);
        define('LD_MOBILE', 4);

        define('LD_IT_CTM', '1');
        define('LD_IT_RT', '2');
    }

}
