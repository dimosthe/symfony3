<?php
// src/AppBundle/Controller/LuckyController.php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Product;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\EventDispatcher\GenericEvent;
use AppBundle\Events;

class LuckyController extends Controller
{
    /**
     * @Route("/lucky/number")
     */
    public function numberAction()
    {
        $number = mt_rand(0, 100);
        $this->get('monolog.logger.product')->emergency('this is a test', array('info' => 'foo'));

        /*return $this->render('lucky/number.html.twig', array(
            'number' => $number,
        ));*/
        /*return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);*/



        $response = new JsonResponse();

        $response->setData(array(
            'data' => $number
        ));

        return $response;
    }

    /**
     * @Route("/lucky/product")
     * @Method("POST")
     */
    public function productAction(Request $request)
    {
        $allowedFields = array('name', 'price', 'description');
        $request = $request->request->all();

        if(!$this->get('helper')->checkRequestfields($allowedFields, $request))
        {
            $response = new JsonResponse();

            $response->setData(array(
                'data' => 'invalid parameters'
            ));

            return $response;
        }

        $product = new Product();

        $product->fromArray($request, $allowedFields);

        $validator = $this->get('validator');
        $errors = $validator->validate($product);

        if (count($errors) > 0)
        {
            /*
             * Uses a __toString method on the $errors variable which is a
             * ConstraintViolationList object. This gives us a nice string
             * for debugging.
             */
            $errorsString = (string) $errors;

            return new Response($errorsString);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $event = new GenericEvent($product);
        $this->get('event_dispatcher')->dispatch(Events::PRODUCT_CREATED, $event);

        return $this->json(array('response' => $product->getId()));
        //return new Response('Saved new product with id '.$product->getId());
    }

    /** 
     * @Route("/lucky/produce", name="produce") 
     */ 
    public function produceAction() 
    {
        for($i = 1; $i < 21; $i++)
        { 
            $msg = array('firstName' => "first name".$i, 'lastName' => "last name".$i); 
            $this->get("producer_service")->publish(json_encode($msg)); 
        } 
       $response = new JsonResponse();

        $response->setData(array(
            'status' => 'success' 
        ));

        return $response;
    }
}