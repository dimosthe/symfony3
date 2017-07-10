<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Bridge\Monolog\Logger;
use AppBundle\Services\SalesforceProducer;

class ProductCreatedListener
{
    private $logger;
    private $producer;

    public function __construct(Logger $logger, SalesforceProducer $producer)
    {
        $this->logger = $logger;
        $this->producer = $producer;
    }

    public function onProductCreated(GenericEvent $event)
    {
        $product = $event->getSubject();

        $this->logger->info('onProductCreated Event: '.$product->getName());
        $this->producer->publish(json_encode($product->getId()));  
    }
}

?>