<?php
namespace AppBundle\Services;

class SalesforceProducer
{
    private $producer;
    public function __Construct($producer)
    {
        $this->producer = $producer;
    }

    public function publish($message)
    {
        //Rabbit MQ want the message to be serialized
        $this->producer->publish(serialize($message));
    }
}