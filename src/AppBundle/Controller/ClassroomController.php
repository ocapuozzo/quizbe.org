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
 * @Route("/classroom")
 */
class ClassroomController extends Controller
{
    
    /**
     * Lists all Classroom entities.
     *
     * @Route("/", name="classroom_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:Classroom')->findAll();
        
        return array(
            'entities' => $entities
        );
        
    }
        
    /**
     * Creates a new Classroom entity.
     *
     * @Route("/", name="classroom_create")
     * @Method("POST")
     * @Template("AppBundle:Classroom:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Classroom();
        $entity->setUser($this->getUser());
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('classroom_index', array()));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Classroom entity.
     *
     * @param Classroom $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Classroom $entity)
    {
        $form = $this->createForm(new ClassroomType(), $entity, array(
            'action' => $this->generateUrl('classroom_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Classroom entity.
     *
     * @Route("/new", name="classroom_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Classroom();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
  
    /**
     * Displays a form to edit an existing Classroom entity.
     *
     * @Route("/{id}/edit", name="classroom_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Classroom')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Classroom entity.');
        }

        $editForm = $this->createEditForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),            
        );
    }

    /**
    * Creates a form to edit a Classroom entity.
    *
    * @param Classroom $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Classroom $entity)
    {
        $form = $this->createForm(new ClassroomType(), $entity, array(
            'action' => $this->generateUrl('classroom_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Classroom entity.
     *
     * @Route("/u/{id}", name="classroom_update")
     * @Method("PUT")
     * @Template("AppBundle:Classroom:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Classroom')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Classroom entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('classroom_index', array()));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
  
    
    
    /**
     * Displays a form to remove an existing Classroom entity.
     *
     * @Route("/{id}", name="classroom_delete")
     * @Method("GET")
     * @Template("AppBundle:Classroom:delete.html.twig")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Classroom')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Classroom entity.');
        }

        $remove = true;    
        $deleteForm = $this->createDeleteForm($entity);

        return array(
            'remove' => $remove,
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    
    
    /**
     * Deletes a Classroom entity.
     *
     * @Route("/{id}", name="classroom_dodelete")
     * @Method("DELETE")
     */
    public function doDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Classroom')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Classroom entity.');
        }

        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Classroom')->find($id);

            if (!$entity) {
              throw $this->createNotFoundException('Unable to find Classroom entity.');                
            }
            $user = $this->getUser();
            
            // only owner can delete
            if ($user->getId() != $entity->getUser()->getId()) {
                throw new AccessDeniedException();
            }
            
            $em->remove($entity);
            $em->flush();
            
        }        
        return $this->redirect($this->generateUrl('classroom_index'));
    }

    /**
     * Creates a form to delete a Classroom entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Classroom $entity)
    {
        $form = $this->createForm(new ClassroomType(), $entity, array(
            'action' => $this->generateUrl('classroom_dodelete', array('id' => $entity->getId())),
            'method' => 'DELETE',
        ));

        $form->add('submit', 'submit', array('label' => 'Delete'));

        return $form;
      /*
      return $this->createFormBuilder()
            ->setAction($this->generateUrl('classroom_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
       
       */
    }
}
