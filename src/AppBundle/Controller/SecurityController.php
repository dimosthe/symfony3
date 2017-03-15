<?php
namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Method("POST")
     */
    public function loginAction(Request $request)
    {
        throw new \Exception('This should never be reached!');
    }

    /**
     * @Route("/logout", name="logout")
     * @Method("POST")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}