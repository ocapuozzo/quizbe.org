<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Scope;
use AppBundle\Form\ScopeType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Scope controller.
 *
 * @Route("/scope")
 */
class ScopeController extends Controller
{
    
    /**
     * Lists all Scope entities.
     *
     * @Route("/", name="scope_index")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('AppBundle:Scope')->findAll();
        
        return array(
            'entities' => $entities
        );
        
    }
        
    /**
     * Creates a new Scope entity.
     *
     * @Route("/", name="scope_create")
     * @Method("POST")
     * @Template("AppBundle:Scope:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Scope();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('scope_index', array()));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Scope entity.
     *
     * @param Scope $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Scope $entity)
    {
        $form = $this->createForm(new ScopeType(), $entity, array(
            'action' => $this->generateUrl('scope_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Scope entity.
     *
     * @Route("/new", name="scope_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Scope();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }
  
    /**
     * Displays a form to edit an existing Scope entity.
     *
     * @Route("/{id}/edit", name="scope_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Scope')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scope entity.');
        }

        $editForm = $this->createEditForm($entity);
       // $deleteForm = $this->createDeleteForm($entity);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            //'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Scope entity.
    *
    * @param Scope $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Scope $entity)
    {
        $form = $this->createForm(new ScopeType(), $entity, array(
            'action' => $this->generateUrl('scope_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Scope entity.
     *
     * @Route("/u/{id}", name="scope_update")
     * @Method("PUT")
     * @Template("AppBundle:Scope:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Scope')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scope entity.');
        }

        $deleteForm = $this->createDeleteForm($entity);
        $editForm = $this->createEditForm($entity);
        
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('scope_index', array()));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    
  
    
    
    /**
     * Displays a form to remove an existing Scope entity.
     *
     * @Route("/{id}", name="scope_delete")
     * @Method("GET")
     * @Template("AppBundle:Scope:delete.html.twig")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Scope')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scope entity.');
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
     * Deletes a Scope entity.
     *
     * @Route("/{id}", name="scope_dodelete")
     * @Method("DELETE")
     */
    public function doDeleteAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Scope')->find($id);
        
        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Scope entity.');
        }

        $form = $this->createDeleteForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Scope')->find($id);

            if (!$entity) {
              throw $this->createNotFoundException('Unable to find Scope entity.');                
            }
            
            $em->remove($entity);
            $em->flush();
            
        }        
        return $this->redirect($this->generateUrl('scope_index'));
    }

    /**
     * Creates a form to delete a Scope entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Scope $entity)
    {
        $form = $this->createForm(new ScopeType(), $entity, array(
            'action' => $this->generateUrl('scope_dodelete', array('id' => $entity->getId())),
            'method' => 'DELETE',
        ));

        $form->add('submit', 'submit', array('label' => 'Delete'));

        return $form;
      /*
      return $this->createFormBuilder()
            ->setAction($this->generateUrl('scope_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
       
       */
    }
}
