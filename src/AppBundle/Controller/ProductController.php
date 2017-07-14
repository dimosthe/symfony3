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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;

class ProductController extends Controller
{
    /**
     * @Route("/products")
     * @Method("GET")
     */
    public function indexAction()
    {
        $repository = $this->getDoctrine()->getRepository(Product::class);
        $products = $repository->findAll();

        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $serializer = new Serializer(array($normalizer));

        $productsNorm = $serializer->normalize($products, null, array('groups' => array('limited')));
        $productsLog = $serializer->normalize($products, null, array('groups' => array('full')));

        $this->get('monolog.logger.product')->info('/products', array('products' => $productsLog));

        $response = new JsonResponse();

        $response->setData(array(
            'message' => 'Products list',
            'data' => array('products' => $productsNorm)
        ));

        return $response;
    }

    /**
     * @Route("/product/create")
     * @Method("POST")
     */
    public function createAction(Request $request)
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

            $this->get('monolog.logger.product')->error('/product/create', array('error' => $errorsString));

            return $this->json(
                array(
                    'data' => $errorsString
            ));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        $event = new GenericEvent($product);
        $this->get('event_dispatcher')->dispatch(Events::PRODUCT_CREATED, $event);

        return $this->json(array('response' => $product->getId()));
    }

    /** 
     * @Route("/product/produce", name="produce") 
     */ 
    public function produceAction() 
    {
        for($i = 1; $i < 1001; $i++)
        { 
            $timestamp = microtime(true)*10000;
            $msgId = $timestamp.random_int(10000000, 99999999);

            $msg = array('firstName' => "first name".$i, 'lastName' => "last name".$i, "count" => $i, "msgId" => $msgId); 
            $this->get("email_producer")->publish(json_encode($msg)); 
        } 
        
        $response = new JsonResponse();

        $response->setData(array(
            'status' => 'success' 
        ));

        return $response;
    }
}