<?php

namespace AppBundle\Services;

use OldSound\RabbitMqBundle\RabbitMq\ConsumerInterface;
use PhpAmqplib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class Consumer implements ConsumerInterface
{
  	public function execute(AMQPMessage $message)
  	{
    	$body = json_decode(unserialize($message->body));
    	echo sprintf ("received message: first name: '%s' last name: '%s' count: %s msgId: %s\n",$body->firstName,$body->lastName, $body->count, $body->msgId);

        echo $message->get('redelivered') ? "==========================Re-delivered=============================" : "First seen"; 
        echo "\n";
        echo "Delivery tag:". $message->get('delivery_tag');
        echo "\n";
        echo "Consumer tag:". $message->get('consumer_tag');
        echo "\n";

        
        // rabbitmq doen no support counters of how many times a message has been resent. The following shows how to do that in the application level
        /*try 
        {
            $headers = $message->get('application_headers');

            if(isset($headers->getNativeData()['headers']['ttl']))
            {
                $ttl = $headers->getNativeData()['headers']['ttl'];
                echo 'ttl: '.$ttl."\n";

                if($ttl > 0)
                {
                    $ttl--;
                    $headersTable = new AMQPTable(array('headers' => array('ttl' => $ttl)));
                    $message->set('application_headers', $headersTable);
                    // PUBLISH THE MESSAGE AGAIN
                    sleep(3);
                    return false;
                }
                else
                {

                }
            }


        } 
        catch (\Exception $e) 
        {
            echo $e->getMessage();
        }*/
    	
    	if($body->count % 2 == 0)
        {
    		sleep(1);
            echo "low\n";
    	}
    	else
        {
    		sleep(1);
            echo "high\n";
    	}
  	}
}