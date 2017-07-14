<?php
namespace AppBundle\Services;

class Producer
{
    private $producer;
    private $logger;

    public function __Construct($producer, $logger)
    {
        $this->producer = $producer;
        $this->logger = $logger;
    }

    public function publish($message)
    {
        //Rabbit MQ want the message to be serialized
        //$this->producer->publish(serialize($message), '', array(), array('headers' => array('ttl' => 3)));

        try 
        {
            $this->producer->publish(serialize($message));
        } 
        catch (\Exception $e) 
        {
            $this->logger->error(print_r($e->getMessage(), true));
        }
        
    }
}