<?php
namespace Adtrak\AdvancedLocationDynamics\Classes;

class CURL
{
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
    public static function curl_get_contents($url, array $post_data = array(), $verbose = false, $ref_url = false, $cookie_location = false, $return_transfer = true)
    {
        $return_val = false;
 
        try {
            $ch = curl_init();
 
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, $return_transfer);
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
}
    