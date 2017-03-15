<?php
namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\JsonResponse;

class FormLoginAuthenticator extends AbstractFormLoginAuthenticator
{
    private $router;
    private $encoder;

    public function __construct(RouterInterface $router, UserPasswordEncoderInterface $encoder)
    {
        $this->router = $router;
        $this->encoder = $encoder;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != '/login') {
            return;
        }

        $username = $request->request->get('username');
        $request->getSession()->set(Security::LAST_USERNAME, $username);
        $password = $request->request->get('password');

        return [
            'username' => $username,
            'password' => $password,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['username'];

        $user = $userProvider->loadUserByUsername($username);

        if(!$user->getIsActive())
            throw new AuthenticationException('The client is not active');
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $plainPassword = $credentials['password'];
        if ($this->encoder->isPasswordValid($user, $plainPassword)) {
            return true;
        }

        throw new BadCredentialsException();
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $response = new JsonResponse('', Response::HTTP_UNAUTHORIZED);

        if($exception instanceof UsernameNotFoundException)
            $message = 'Login failed';
        elseif ($exception instanceof BadCredentialsException)
            $message = 'Login failed';
        elseif ($exception instanceof AuthenticationException)
            $message = $exception->getMessage();
        else
            $message = 'Login Failed';

        $response->setData(array(
            'status' => 'failure',
            'message' => $message
        ));

        return $response;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $response = new JsonResponse();

        $response->setData(array(
            'status' => 'success',
            'message' => 'Login successful'
        ));

        return $response;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('login');
    }

    protected function getDefaultSuccessRedirectUrl()
    {
        return $this->router->generate('welcome');
    }

    public function supportsRememberMe()
    {
        return false;
    }

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