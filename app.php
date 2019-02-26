<?php
require 'vendor/autoload.php';

use App\Helpers\ServiceHandler;
use App\Helpers\GitHelper;
use App\Helpers\PackageHelper;
use App\Helpers\ComposerHelper;
use App\Helpers\MailHelper;

$dotenv = Dotenv\Dotenv::create(__DIR__);
$dotenv->load();

$gitHelper = new GitHelper($argv[1]);
$packageFile = $gitHelper->getPackageFile();

$outdatedList = array();

if($packageFile["path"] == "package.json") {
    $packageHelper = new PackageHelper();
    $outdatedList = $packageHelper->findOutdated($packageFile["url"]);
}else if($packageFile["path"] == "composer.json") {
    $composerHelper = new ComposerHelper();
    $outdatedList = $composerHelper->findOutdated($packageFile["url"]);
}

if(count($outdatedList) > 0) {
    $mailHelper = new MailHelper($argv[2]);
    $mailHelper->notifyOutdated($outdatedList);
}

exit;

?>
