<?php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

class BasicAuthRequestMatcher implements RequestMatcherInterface
{
    public function matches(Request $request)
    {
        return $request->headers->has('authorization');
    }
}