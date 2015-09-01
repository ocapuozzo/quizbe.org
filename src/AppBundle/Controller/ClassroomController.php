<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Classroom;
use AppBundle\Form\ClassroomType;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Classroom controller.
 *
 * @Route("/quiz/{_locale}/classroom")
 *
 */
class ClassroomController extends Controller {

  /**
   * Lists all Classroom or one whith scopes.
   *
   * @Route("/", name="classroom_index")
   * @Route("/cl/{id}", name="classroom")
   * @Method("GET")
   * @Template()
   */
  public function indexAction(Request $request, $id = null) {
    $em = $this->getDoctrine()->getManager();

    if ($id) {
       $user = $em->getRepository('AppBundle:User')->find($id);
    } else {
       $user = $this->getUser();
    }
    
    if (!$user) {
      throw $this->createNotFoundException('Unable to find user.');
    }
    
    $entities = $em->getRepository('AppBundle:Classroom')->findByUser($user);
      // so we get also classroom->user
      // $user->getClassrooms(); <= this, no

    return array(
        'entities' => $entities,
        'user' => $user,
    );
  }

  /**
   * Creates a new Classroom entity.
   *
   * @Route("/", name="classroom_create")
   * @Method("POST")
   * @Template("AppBundle:Classroom:edit.html.twig")
   */
  public function createAction(Request $request) {
    $entity = new Classroom();
    $form = $this->createCreateForm($entity);
    $form->handleRequest($request);
    $entity->setUser($this->getUser());
    
    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $em->persist($entity);
      $em->flush();

      return $this->redirect($this->generateUrl('classroom_index', array()));
    }

    return array(
        'entity' => $entity,
        'form' => $form->createView(),
    );
  }

  /**
   * Creates a form to create a Classroom entity.
   *
   * @param Classroom $entity The entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createCreateForm(Classroom $entity) {
    $form = $this->createForm(new ClassroomType($this->getUser()), $entity, array(
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
   * @Template("AppBundle:Classroom:edit.html.twig")
   */
  public function newAction() {
    $entity = new Classroom();    
    $form = $this->createCreateForm($entity);

    return array(
        'entity' => $entity,
        'form' => $form->createView(),
    );
  }

  /**
   * Finds and displays a Classroom entity.
   *
   * @Route("/{id}", name="classroom_show")
   * @Method("GET")
   * @Template()
   */
  public function showAction($id) {
    $em = $this->getDoctrine()->getManager();
    $entity = $em->getRepository('AppBundle:Classroom')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find Classroom entity.');
    }
    
    // TODO : nice presentation...
    
    return array(
        'entity' => $entity,
        // 'delete_form' => $deleteForm->createView(),
    );
  }

  /**
   * Displays a form to edit an existing Classroom entity.
   *
   * @Route("/{id}/edit", name="classroom_edit")
   * @Method("GET")
   * @Template("AppBundle:Classroom:edit.html.twig")
   */
  public function editAction($id) {
    $em = $this->getDoctrine()->getManager();
    
    $user = $this->getUser();
     
    $entity = $em->getRepository('AppBundle:Classroom')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find Classroom entity.');
    }

    $editForm = $this->createEditForm($entity);
    $deleteForm = $this->createDeleteForm($id);

    return array(
        'entity' => $entity,
        'form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
        'user' => $user,
    );
  }

  /**
   * Creates a form to edit a Classroom entity.
   *
   * @param Classroom $entity The entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createEditForm(Classroom $entity) {
    $form = $this->createForm(new ClassroomType($this->getUser()), $entity, array(
        'action' => $this->generateUrl('classroom_update', array('id' => $entity->getId())),
        'method' => 'PUT',
    ));

    $form->add('submit', 'submit', array('label' => 'Update'));

    return $form;
  }

  /**
   * Edits an existing Classroom entity.
   *
   * @Route("/{id}", name="classroom_update")
   * @Method("PUT")
   * @Template("AppBundle:Classroom:edit.html.twig")
   */
  public function updateAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();

    $classroom = $em->getRepository('AppBundle:Classroom')->find($id);
    
    if (!$classroom) {
      throw $this->createNotFoundException('Unable to find Classroom entity.');
    }

    if ($this->getUser() != $classroom->getUser()){
      throw new AccessDeniedException();
    }

    // http://symfony.com/fr/doc/current/cookbook/form/form_collections.html
    $originalScopes = new ArrayCollection();

    // Crée un tableau contenant les objets Response courants de la
    // base de données
    foreach ($classroom->getScopes() as $scope) {
      $originalScopes->add($scope);
    }

    $editForm = $this->createEditForm($classroom);
    $editForm->handleRequest($request);

    if ($editForm->isValid()) {
      // supprime la relation entre scope et classroom
      foreach ($originalScopes as $scope) {
        if ($classroom->getScopes()->contains($scope) == false) {
          // suppression du lien
          $scope->setClassroom(null);
          $em->remove($scope);
        }
      }

      $em->persist($classroom);
      $em->flush();

      return $this->redirect($this->generateUrl('classroom', array()));
    }

    $deleteForm = $this->createDeleteForm($id);
    $user = $this->getUser();
    
    return array(
        'entity' => $classroom,
        'form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
        'user' => $user,
    );

  }

  /**
   * Deletes a Classroom entity.
   *
   * @Route("/{id}", name="classroom_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, $id) {
    $form = $this->createDeleteForm($id);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('AppBundle:Classroom')->find($id);

      if (!$entity) {
        throw $this->createNotFoundException('Unable to find Classroom entity.');
      }

      if ($this->getUser() != $entity->getUser()){
        throw new AccessDeniedException();
      }
      
      $em->remove($entity);
      $em->flush();
    }

    return $this->redirect($this->generateUrl('classroom'));
  }

  /**
   * Creates a form to delete a Classroom entity by id.
   *
   * @param mixed $id The entity id
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createDeleteForm($id) {
    return $this->createFormBuilder()
            ->setAction($this->generateUrl('classroom_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
    ;
  }


}
