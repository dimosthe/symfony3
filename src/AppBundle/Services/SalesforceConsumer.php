<?php

namespace AppBundle\Services;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqplib\Message\AMQPMessage;
use Symfony\Bridge\Monolog\Logger;
use AppBundle\Entity\Product;

class SalesforceConsumer implements ConsumerInterface
{
  	private $logger;
    private $docrine;

    public function __construct(Logger $logger, $doctrine)
    {
        $this->logger = $logger;
        $this->doctrine = $doctrine;
    }

    public function execute(AMQPMessage $message)
  	{
    	$productId = json_decode(unserialize($message->body));
    	
        $this->logger->info('productId: '.$productId);  

        echo sprintf ("Salesforce received message: id: %s\n", $productId);

        $repository = $this->doctrine->getRepository('AppBundle:Product');
        $product = $repository->find($productId);
        
        $this->logger->info(print_r($product, true));

        $product->setIsValid(true);

        $this->doctrine->persist($product);
        $this->doctrine->flush();
  	}
}