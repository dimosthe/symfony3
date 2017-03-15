<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Comment;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Bridge\Monolog\Logger;


class ProductCreatedListener
{
    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function onProductCreated(GenericEvent $event)
    {
        $product = $event->getSubject();

        $this->logger->info(print_r($product, true));

    }
}

?>