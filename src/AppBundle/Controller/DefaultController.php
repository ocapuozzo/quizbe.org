<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Entity\Question;

class DefaultController extends Controller
{

    /**
     * @Route("/", name="homepage_")     
     */
    public function homeAction()
    { 
      //$locale = $request->getLocale();
      //return $this->redirectToRoute("homepage", array("_locale" => $locale));
      return $this->render('default/index.html.twig', array());
    }
    
    /**
     * @Route("/index/{_locale}",  name="homepage")
     */
    public function indexAction(Request $request)
    {                   
      return $this->render('default/index.html.twig', array());
    }
    
}
