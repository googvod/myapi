<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Lexik\Bundle\JWTAuthenticationBundle\Exception\ExpiredTokenException;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseController
 * @package AppBundle
 */
class BaseController extends FOSRestController
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

        if (!$credentials) {
            throw new AccessDeniedHttpException('Token not found');
        }

        return $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(array('id' => $credentials->getPayload()['id']));
    }
}
