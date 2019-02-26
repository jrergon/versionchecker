<?php
namespace App\Helpers;

class JsonHelper {

    /**
     * @var String
     */
    protected $apiPath;

    protected function readFile(String $url) : array {
        $file = ServiceHandler::get($url);
        $fileContent = base64_decode($file["content"]);

        return json_decode($fileContent, true);
    }

    protected function convertVersionToInt(String $version) : int {
        return intval(preg_replace("/[^0-9]/", "", $version));
    }

    protected function versionCompare(String $currentVersion, String $latestVersion) : bool {
        $currentVersionArray = explode(".", $currentVersion);
        $latestVersionArray = explode(".", $latestVersion);
        $currentMajor = $this->convertVersionToInt($currentVersionArray[0]);
        $latestMajor = $this->convertVersionToInt($latestVersionArray[0]);

        if($latestMajor > $currentMajor) {
            return true;
        }else if($latestVersionArray[1] > $currentVersionArray[1]) {
            return true;
        }else if($latestVersionArray[2] > $currentVersionArray[2]) {
            return true;
        }

        return false;
    }
}

?>