<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Classroom;
use AppBundle\Form\ClassroomType;
use AppBundle\Form\UserClassroomsType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Classroom controller.
 *
 * @Route("/myclassrooms")
 */
class UserClassroomController extends Controller
{

    /**
     * Lists all Classroom entities.
     *
     * @Route("/", name="my_classrooms")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $classrooms = $user->getClassrooms();
        
        $entities = $em->getRepository('AppBundle:Classroom')->findAll();
        $form = $this->createUserClassroomsForm($user);
        
        return array(
            'entities' => $entities,
            'myClassrooms' => $classrooms,
            'form' => $form->createView()
        );
        
    }
    
    
    /**
    */
    private function createUserClassroomsForm($user)
    {
        $form = $this->createForm(new UserClassroomsType(), $user, array(
            'action' => $this->generateUrl('user_classrooms_update', array('id' => $user->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    

    /**
     * Edits an user relation Classrooms entities.
     *
     * @Route("/{id}", name="user_classrooms_update")
     * @Method("PUT")
     * @Template("AppBundle:UserClassroom:index.html.twig")
     */
    public function updateUserClassroomsAction(Request $request, $id)
    {    
        $em = $this->getDoctrine()->getManager();

        $user = $em->getRepository('AppBundle:User')->find($id);

        if (!$user) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $form = $this->createUserClassroomsForm($user);        
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->flush();
            
            $this->get('session')->getFlashBag()->add('update', 'Classe modifiée');
            $this->get('session')->remove('idScope');
            return $this->redirect($this->generateUrl('question'));
        }

        $classrooms = $user->getClassrooms();        
        $entities = $em->getRepository('AppBundle:Classroom')->findAll();
        
        return array(
            'entities' => $entities,
            'myClassrooms' => $classrooms,
            'form' => $form->createView()
        );
         
    }
}
