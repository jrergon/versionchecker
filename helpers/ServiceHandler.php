<?php
namespace App\Helpers;

class ServiceHandler {
    /**
     * @var String
     */
    private const USER_AGENT = "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13";

    public static function get(String $url) : Array {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_USERAGENT, self::USER_AGENT);        
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_URL, $url);
        
        $response = curl_exec($curl);
        curl_close($curl);
        
        return json_decode($response, true);
    }
}

?>