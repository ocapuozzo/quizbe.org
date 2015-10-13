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
    public function homeAction(Request $request)
    { 
      $locale = $request->getLocale();
      return $this->redirectToRoute("homepage", array("_locale" => $locale));
      //return $this->render('default/index.html.twig', array());
    }
    
    /**
     * @Route("/index/{_locale}",  name="homepage")
     */
    public function indexAction(Request $request)
    {             
      return $this->render('default/index.html.twig', array());
    }
    
    
    /**
     * @return Json 
     * @Route("/comment/new/{id}",  name="comment_new")
     * @Method("GET")
     */
    public function commentAction(Request $request, Question $question)
    {  
        $user = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findOneByUsername($question->getDesigner());
        
        if (!$user) {
          return new JsonResponse(array('ok' => 0));
        }
        
        $message = \Swift_Message::newInstance()
        ->setSubject('QuizBe.org : New comment notification')
        ->setFrom('admin@quizbe.org')
        ->setTo($user->getEmail())
        ->setBody(
            $this->renderView(
                // app/Resources/views/Emails/newcomment.txt.twig
                'Emails/newcomment.txt.twig',
                array('name' => $question->getName(), 'question' => $question)
            ),
            'text/html'
        );
        $this->get('mailer')->send($message);
        
        return new JsonResponse(array('ok' => 1));
    }
}
