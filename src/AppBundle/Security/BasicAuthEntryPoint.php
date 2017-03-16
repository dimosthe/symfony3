<?php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\JsonResponse;

#http://symfony.com/doc/current/components/security/firewall.html#entry-points

class BasicAuthEntryPoint implements AuthenticationEntryPointInterface
{

    public function start(Request $request, AuthenticationException $authException = null)
    {
        $response = new JsonResponse('', Response::HTTP_FORBIDDEN);

        $response->setData(array(
            'status' => 'failure',
            'message' => 'Forbidden'
        ));

        return $response;
    }
}