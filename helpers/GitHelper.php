<?php
namespace App\Helpers;

class GitHelper {
    /**
     * @var String
     */
    private $repoName;

    /**
     * @var String
     */
    private $apiPath; 

    function __construct($repoName) {
        $this->repoName = $repoName;
        $this->apiPath = getenv("GITHUB_API");
    }

    public function getPackageFile() : array {
        $fileTree = $this->getFileTree();

        $file = array_filter($fileTree, array(
            $this,
            "findPackManFile"
        ));

        $file = array_values($file);

        return $file[0];
    }

    private function getFileTree() : array {
        $servicePath = $this->apiPath . "/repos/" . $this->repoName . "/git/trees/master";
        $fileTree = ServiceHandler::get($servicePath);

        return $fileTree["tree"];
    }

    private function findPackManFile($item) : bool {
        return ($item["path"] === "composer.json" || $item["path"] === "package.json");
    }
}

?>