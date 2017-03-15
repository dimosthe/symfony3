<?php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutRestHandler implements  LogoutSuccessHandlerInterface{

    public function onLogoutSuccess(Request $request)
    {
        $response = new JsonResponse();

        $response->setData(array(
            'data' => 'success'
        ));

        return $response;
    }
}