<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends Controller
{
    /**
     * @param Request $request
     */
    public function listAction(Request $request)
    {
        $restresult = $this->getDoctrine()->getRepository('AppBundle:Task')->findAll();

        return $restresult;
    }


}
