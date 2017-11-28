<?php

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class SecurityController
 * @package AppBundle\Security
 */
class SecurityController extends Controller
{
    /**
     * @return AppBundle:User
     */
    public function getUser()
    {
        try {
            $credentials = $this->container
                ->get('lexik_jwt_authentication.jwt_token_authenticator')
                ->getCredentials($this->container->get('request_stack')
                    ->getCurrentRequest());
        } catch (ExpiredTokenException $e) {
            throw new AccessDeniedHttpException('Token is expired');
        }

        return $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(array('id' => $credentials->getPayload()['id']));
    }
}
