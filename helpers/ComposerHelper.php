<?php
namespace App\Helpers;

class ComposerHelper extends JsonHelper {

    function __construct() {
        $this->apiPath = getenv("COMPOSER_API");
    }

    public function findOutdated(String $fileUrl) : array {
        $packageFile = $this->readFile($fileUrl);
        $depencies = $packageFile["require"];
        $outdatedPackages = array();
        
        foreach($depencies as $depency => $version) {
            if(strpos($depency, "/") === false || $version == "*" || strpos($version, "^") === 0) {
                continue;
            }
            
            $latestVersion = $this->getLatestVersion($depency);
            
            if($this->versionCompare($version, $latestVersion)) {
                $outdatedPackages[] = array(
                    "packageName" => $depency,
                    "currentVersion" => $version,
                    "latestVersion" => $latestVersion
                );
            }
        }

        return $outdatedPackages;
    }

    private function getLatestVersion(String $packageName) : String {
        $packageUrl = $this->apiPath . "/p/" . $packageName . ".json";
        $packageList = ServiceHandler::get($packageUrl);
        $package = $packageList["packages"][$packageName];

        return array_pop($package)["version"];
    }
}

?>