<?php

namespace AppBundle\Controller;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * Class AccessController
 * @package AppBundle\Controller
 */
class AccessController extends BaseController
{
    /**
     * @ApiDoc(
     *  section="Authorization",
     *  description="Get token",
     *  requirements={
     *      {
     *          "name"="name",
     *          "dataType"="string",
     *          "description"="User name"
     *      },
     *     {
     *          "name"="password",
     *          "dataType"="string",
     *          "description"="User password"
     *      },
     *   },
     *   statusCodes={
     *      200="Returned when successful",
     *      401="Returned when the user not found"
     *  }
     * )
     *
     * @param Request $request
     * @return array
     */
    public function getTokenAction(Request $request)
    {
        return [];
    }
}
