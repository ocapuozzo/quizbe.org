<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use AppBundle\Entity\Question;
use AppBundle\Form\QuestionType;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Question controller.
 *
 * @Route("/question")
 */
class QuestionController extends Controller
{

    /**
     * Lists all Question entities.
     *
     * @Route("/", name="question")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('AppBundle:Question')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new Question entity.
     *
     * @Route("/", name="question_create")
     * @Method("POST")
     * @Template("AppBundle:Question:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Question();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('question_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a Question entity.
     *
     * @param Question $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Question $entity)
    {
        $form = $this->createForm(new QuestionType(), $entity, array(
            'action' => $this->generateUrl('question_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Question entity.
     *
     * @Route("/new", name="question_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Question();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a Question entity.
     *
     * @Route("/{id}", name="question_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Question entity.
     *
     * @Route("/{id}/edit", name="question_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Question')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a Question entity.
    *
    * @param Question $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Question $entity)
    {
        $form = $this->createForm(new QuestionType(), $entity, array(
            'action' => $this->generateUrl('question_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    
    /**
     * Edits an existing Question entity.
     *
     * @Route("/{id}", name="question_update")
     * @Method("PUT")
     * @Template("AppBundle:Question:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $question = $em->getRepository('AppBundle:Question')->find($id);

        if (!$question) {
            throw $this->createNotFoundException('Unable to find Question entity.');
        }

      // http://symfony.com/fr/doc/current/cookbook/form/form_collections.html
         $originalResponses = new ArrayCollection();

        // Crée un tableau contenant les objets Response courants de la
        // base de données
        foreach ($question->getResponses() as $resp) {
           $originalResponses->add($resp);
        }
                
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($question);
        $editForm->handleRequest($request);
       
        if ($editForm->isValid()) {
            // supprime la relation entre la response et la question
            foreach ($originalResponses as $resp) {
                if ($question->getReponses()->contains($tag) == false) {
                    // suppression du lien
                    $resp->setQuestion(null);
                    $em->remove($resp);
                }
            }
            $em->persist($question);
            $em->flush();

            return $this->redirect($this->generateUrl('question_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $question,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a Question entity.
     *
     * @Route("/{id}", name="question_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Question')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Question entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('question'));
    }

    /**
     * Creates a form to delete a Question entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('question_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
