<?php 
namespace Adtrak\AdvancedLocationDynamics\Controllers;

class Controller
{

    public function __construct()
    {
    }

    public function menu()
    {
    }

    public function getCookie()
    {
        if (!is_admin()) {
            if (boolval(get_option('ald_advanced')) === true) {
                if (!empty($_GET['physical_loc'])) {
                    return filter_var($_GET['physical_loc'], FILTER_SANITIZE_STRING);
                }
                if (!empty($_GET['interest_loc'])) {
                    return filter_var($_GET['interest_loc'], FILTER_SANITIZE_STRING);
                }
            }

            if (isset($_COOKIE['area'])) {
                return $_COOKIE['area'];
            }

            if (isset($_GET['a'])) {
                return filter_var($_GET['a'], FILTER_SANITIZE_STRING);
            }

            return false;
        }
    }

    public function checkForAdvancedCookie()
    {
        if (!is_admin()) {
            if ((isset($_COOKIE['area']) && is_numeric($_COOKIE['area'])) || isset($_GET['physical_loc']) || isset($_GET['interest_loc'])) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function setCookie()
    {
        if (!is_admin()) {
            if (isset($_GET['a']) && !$this->checkForAdvancedCookie()) {
                $_GET['a'] = ($_GET['a'] == 'gen') ? 'uk' : $_GET['a'];
                setcookie('area', filter_var($_GET['a'], FILTER_SANITIZE_STRING), time()+60*60*24*30, '/');
            }

            if (boolval(get_option('ald_advanced')) === true) {
                if (isset($_GET['physical_loc']) || isset($_GET['interest_loc'])) {
                    $checkSet = ( isset($_GET['physical_loc']) ) ? $_GET['physical_loc'] : $_GET['interest_loc'];
                    setcookie('area', filter_var($checkSet, FILTER_SANITIZE_NUMBER_INT), time()+60*60*24*30, '/');
                }
            } else {
                if (isset($_COOKIE['area']) && is_numeric($_COOKIE['area'])) {
                    unset($_COOKIE['area']);
                }
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

    /**
     * Function to get the filesize of an external file
     *
     * @param [type] $url
     * @return void
     */
    protected function curl_get_size($url)
    {
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_HEADER, true); 
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); 
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
        $head = curl_exec($ch);
        $errNo = curl_errno($ch);

        $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);
        curl_close($ch);

        if ($errNo) {
            return $errNo;
        }

        return $size;
    }

    /**
     * Function to replace file_get_contents with a curl option
     *
     * @param  [type]  $url
     * @param  array   $post_data
     * @param  boolean $verbose
     * @param  boolean $ref_url
     * @param  boolean $cookie_location
     * @param  boolean $return_transfer
     * @return void
     */
    protected function curl_get_contents($url,array $post_data=array(),$verbose=false,$ref_url=false,$cookie_location=false,$return_transfer=true)
    {
        $return_val = false;
 
        try {
            $ch = curl_init();
 
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return_transfer);
            // Set the user agent to Chrome 20.0.1092.0
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1092.0 Safari/536.6");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_HEADER, false);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_AUTOREFERER, true);
 
            if ($cookie_location !== false) {
                curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_location);
                curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_location);
                curl_setopt($ch, CURLOPT_COOKIE, session_name() . '=' . session_id());
            }
 
            if ($verbose !== false) {
                $verbose_ch = fopen($verbose, 'w');
                curl_setopt($ch, CURLOPT_VERBOSE, true);
                curl_setopt($ch, CURLOPT_STDERR, $verbose_ch);
            }
 
            if ($ref_url !== false) {
                curl_setopt($ch, CURLOPT_REFERER, $ref_url);
            }
 
            if (count($post_data) > 0) {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            }
 
            $return_val = curl_exec($ch);
            $errNo = curl_errno($ch);
 
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
 
            if ($http_code == 404) {
                return false;
            }
 
            curl_close($ch);
 
            unset($ch);

            if ($errNo) {
                return $errNo;
            }
 
            return $return_val;
        } catch(Exception $e) {
            trigger_error(
                sprintf(
                    'Curl failed with error #%d: %s',
                    $e->getCode(), $e->getMessage()
                ),
                E_USER_ERROR
            );
        }
    }

    public function redirect($url)
    {
        echo '<script>window.location.replace("'. $url .'");</script>';
        echo ' <meta http-equiv="refresh" content="0;url='. $url .'">';
        die();
    }

    /**
     * Check the key and verify
     *
     * @param string $hash
     * @return boolean
     */
    public function checkKey(string $hash) : bool
    {
        $key = str_replace('www.', '', $_SERVER['HTTP_HOST']);
        return password_verify($key, $hash);
    }

}
