<?php
namespace AppBundle\Utils;

use Symfony\Bridge\Monolog\Logger;

class CustomLogger
{
    // handlers
    const SF        = 0;
    const PRODUCT   = 1;

    // levels
    const INFO      = 100;
    const NOTICE    = 250;
    const WARNING   = 300;
    const ERROR     = 400;
    const CRITICAL  = 500; 
    const ALERT     = 550;
    const EMERGENCY = 600;

    private $sfLogger;
    private $prodLogger;

    public function __construct(Logger $sfLogger, Logger $prodLogger)
    {
        $this->sfLogger     = $sfLogger;
        $this->prodLogger   = $prodLogger;
    }

    public function log($title, $message, $handler, $level, $username = NULL)
    {
        if(is_string($message))
            $message = array('message' => $message);

        if(!empty($username))
            $message['username'] = $username;

        $logger = $this->getLogger($handler);
        
        $logger->addRecord($level, $title, $message);  
    }

    private function getLogger($handler)
    {
        switch ($handler) 
        {
            case self::SF:
                return $this->sfLogger;
                break;
            case self::PRODUCT:
                return $this->prodLogger;
        }
    }
}

?>