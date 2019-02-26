<?php
namespace App\Helpers;

class PackageHelper extends JsonHelper {
    
    function __construct() {
        $this->apiPath = getenv("NPM_API");
    }

    public function findOutdated(String $fileUrl) : array {
        $packageFile = $this->readFile($fileUrl);
        $depencies = $packageFile["dependencies"];
        $outdatedPackages = array();
        
        foreach($depencies as $depency => $version) {
            if($version == "*" || strpos($version, "^") === 0) {
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
        $packageUrl = $this->apiPath . "/" . $packageName;
        $packageObject = ServiceHandler::get($packageUrl);
        $latest = $packageObject["dist-tags"]["latest"];

        return $latest;
    }
}

?>