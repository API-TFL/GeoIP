<?php

/*
 * GeoIP Class
 *
 *
 * References:
 * http://php.net/manual/en/book.geoip.php
 * http://dev.maxmind.com/geoip/legacy/geolite/
 * https://github.com/maxmind/geoip-api-php
 * http://ipinfodb.com/ip_database.php
 * http://lite.ip2location.com/database-ip-country-region-city-latitude-longitude-zipcode-timezone#ipv4-mysql
 * http://lite.ip2location.com/database-ip-country-region-city-latitude-longitude
 * http://www.ip2nation.com/
 * http://www.geoplugin.com/webservices/php
 *
 * GeoNames
 *
 */
class GeoIP
{
    private static $geoplugin = '//www.geoplugin.net/php.gp?ip=';
    private static $ipinfo    = '//ipinfo.io/';
    private static $freegeoip = '//freegeoip.net/json/';
    private static $hostip    = '//api.hostip.info/?ip=';
    private static $ipinfodb  = '//ipinfodb.com/ip_locator.php?ip=';
    private static $ip2c      = '//ip2c.org/';

    public function __construct()
    {
        // round-houses the services
    }

    public static function geoplugin($ipv4)
    {
        $data = unserialize(file_get_contents('http:'.self::$geoplugin.$ipv4));

        if (isset($data['geoplugin_latitude']) && isset($data['geoplugin_longitude']))
        {
            return (object) array
            (
                'city'     => (string) $data['geoplugin_city'],
                'region'   => (string) $data['geoplugin_region'],
                'areaCode' => (int)    $data['geoplugin_areaCode'],
                'countryCode'  => (string) $data['geoplugin_countryCode'],
                'continentCode'=> (string) $data['geoplugin_continentCode'],
                'regionCode'   => (string) $data['geoplugin_regionCode'],
                'regionName'   => (string) $data['geoplugin_regionName'],
                'currencyCode' => (string) $data['geoplugin_currencyCode'],
                'currencySymbol'    => (string) $data['geoplugin_currencySymbol'],
                'currencyConverter' => (float)  $data['currencyConverter'],
                'latitude'  => (float) $data['geoplugin_latitude'],
                'longitude' => (float) $data['geoplugin_longitude']
            );
        }
        else
        {
            trigger_error('geoplugin failed to return (geoplugin_latitude) & (geoplugin_longitude) values', E_USER_NOTICE);

            return FALSE;
        }
    }

    public static function ip2c($ipv4)
    {
        $s = file_get_contents('http:'.self::$ip2c.$ipv4);

        switch ($s[0])
        {
            case '0':
                trigger_error('ip2c failed - unknown error.', E_USER_NOTICE);
                return FALSE;
            case '1':
                $reply = explode(';', $s);
                return (object) array
                (
                    'ios3166_alpha2'  => (string) $reply[1],
                    'iso3166'         => (string) $reply[2],
                    'country'         => (string) $reply[3],
                );
            case '2':
                trigger_error('ip2c failed - Not found in database.', E_USER_NOTICE);
                return FALSE;
        }

    }

    private static function insertRecord() {}
}

require_once 'CoordinateInfo.php';

$geoip = GeoIP::geoplugin('83.99.17.115');

var_dump($geoip);


// http://api.geonames.org/findNearbyPlaceNameJSON?lat=
// http://api.geonames.org/postalCodeLookupJSON?placename=