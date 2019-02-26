<?php
namespace App\Helpers;


class MailHelper {
    private $mailer;

    /**
     * @var String
     */
    private $toAddress;

    function __construct(String $toAddress) {
        $this->toAddress = $toAddress;
        $transport = (new \Swift_SmtpTransport(getenv("MAIL_SMTP"), getenv("MAIL_PORT"), 'ssl'))
            ->setUsername(getenv("MAIL_USERNAME"))
            ->setPassword(getenv("MAIL_PASSWORD"));

        $this->mailer = new \Swift_Mailer($transport);
    }

    public function notifyOutdated(Array $packageList) : void {
        $mailBody = $this->createMessage($packageList);
        
        $message = (new \Swift_Message('Outdated Packages'))
            ->setFrom(getenv("MAIL_FROM"))
            ->setTo($this->toAddress)
            ->setBody($mailBody);

        $this->mailer->send($message);
    }

    private function createMessage(Array $packageList) : String {
        $messageRow = array();

        foreach($packageList as $package) {
            $messageRow[] = $package["packageName"] . " outdated. (current : " 
                . $package["currentVersion"] . " latest : " . $package["latestVersion"] . " )";
        }

        return implode("\n", $messageRow);
    }
}

?>