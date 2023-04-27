<?php 
namespace Adtrak\AdvancedLocationDynamics;

use Adtrak\AdvancedLocationDynamics\Models\Location;
use Adtrak\AdvancedLocationDynamics\Models\Country;
use Adtrak\AdvancedLocationDynamics\Classes\CURL;

class Helper
{

    /**
     * The booted state.
     *
     * @var boolean
     */
    protected static $booted = false;

    /**
     * The base path
     *
     * @var string
     */
    protected static $base;
    
    /**
     * The config.php content
     *
     * @var array
     */
    protected static $config = [];

    /**
     * Boots the Helper.
     */
    public static function boot()
    {
        self::$base = plugin_directory();
        self::$base = self::$base . '/' . basename(plugin_dir_url(__DIR__)) . '/';
        
        self::$config = @include self::$base . '/plugin.config.php';
        self::$booted = true;
    }

    /**
     * Gets a config variable.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    public static function get($key = null, $default = null)
    {
        if (!self::$booted) {
            self::boot();
        }

        if ($key === null) {
            return self::$config;
        }

        return array_get(Self::$config, $key, $default);
    }

        /**
         * Gets a path to a relative file.
         *
         * @param  string $file
         * @return string
         */
    public static function path($file)
    {
        if (!self::$booted) {
            self::boot();
        }

        return self::$base . $file;
    }

    /**
     * Gets a path to a relative asset.
     *
     * @param  string $file
     * @return string
     */
    public static function asset($file = null)
    {
        $asset = trim(self::get('assets', 'assets'), '/');

        if ($file !== null) {
            $asset .= '/' . trim($file, '/');
        }

        return self::path($asset);
    }

    /**
     * Gets a url to a relative asset.
     *
     * @param  string $file
     * @return string
     */
    public static function assetUrl($file = null)
    {
        return content_url(
            substr(self::asset($file), strlen(content_directory()))
        );
    }

    /**
     * Process country csv file into the database
     *
     * @param string $pack_url
     * @param string $country
     * @return boolean
     */
    public static function processCountryPack($pack_url = null, $local = true, $country = 'UK') : object
    {
        $i = 0;
        if ($local) {
            $handle = fopen(self::asset($pack_url), 'r');

            while ( ($data = fgetcsv($handle) ) !== false ) {

                if ($i != 0 && $data[0] != null) {
                    Location::firstOrCreate(['location' => $data[0], 'ids' => $data[1], 'area_code' => $data[2], 'country' => $country]);
                }

                $i++;
            }
            fclose($handle);
        } else {
            $handle = explode(PHP_EOL, CURL::curl_get_contents($pack_url));
            if (count($handle) <= 1) {
                return self::systemMessage('error', 'Pack does not exist.');
            }
            foreach ($handle as $row) {
                $data = str_getcsv($row);
                if ($i != 0 && $data[0] != null) {
                    Location::firstOrCreate(['location' => $data[0], 'ids' => $data[1], 'area_code' => $data[2], 'country' => $country]);
                }
                $i++;
            }
        }

        $country = Country::where('code', $country)->first();
        $country->installed = 1;
        $country->save();

        return true;
    }

    public static function processCountries()
    {
        $handle = explode(PHP_EOL, CURL::curl_get_contents('https://telecoms.adtrakdev.com/ald/countries.csv'));
        foreach ($handle as $row) {
            $data = str_getcsv($row);
            if ($i != 0 && $data[0] != null) {
                Country::firstOrCreate(['name' => $data[0], 'code' => $data[1], 'filename' => $data[2], 'last_updated' => date("Y-m-d H:i:s"), 'last_checked' => date("Y-m-d H:i:s")]);
            }
            $i++;
        }
    }

    public static function systemMessage($status, $message)
    {
        return (object) [
            'status'    => $status,
            'message'   => $message
        ];
    }
}
