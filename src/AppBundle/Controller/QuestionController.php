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
use Symfony\Component\HttpFoundation\JsonResponse;
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
     * Rating a question by current user
     * @return Json 
     * @Route("/rating/{id}", name="question_rating")
     * @Method("POST")
     * 
     */
    public function ratingAction(Request $request, Question $question){
        // the ParamConverter automatically queries for an object 
        // whose $id property matches the {id} value. 
        // It will also show a 404 page if no Post can be found.
       $res = 0;
       $value = floatval($request->request->get('value'));
       try {
         if ($value > 0) {
           $res = $this->doRating($this->getUser(), $question, $value);
         } else {
           $res = $this->doDeleteRating($this->getUser(), $question);
         }
       } catch (Exception $ex) {
          $res = 0;
       }
       return new JsonResponse(array('ok'=>$res));
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

        $rating = $this->getRating($entity, $this->getUser());
        
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'rating'      => $rating,
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
                if ($question->getResponses()->contains($resp) == false) {
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
    
    /**
     *  Create or update a rating by one user for one question
     * 
     * @param type $user
     * @param type $question
     * @param type $value
     * @return int 0=no opération, 1=create vote, 2=update vote
     */
    private function doRating($user, $question, $value){
       $res = 0;
       $em = $this->getDoctrine()->getManager();
       $vote = $em->getRepository('AppBundle:Vote')
           ->findOneBy(array('user'=>$user, 'question'=>$question));
       if ($vote) {
         if ($vote->getValue() != $value) {
           $vote->setValue($value);
           $em->persist($vote);
           $em->flush();
           $res = 2;
         }           
       }else {
         $vote = new \AppBundle\Entity\Vote();
         $vote->setQuestion($question);
         $vote->setUser($user);
         $vote->setValue($value);
         $em->persist($vote);
         $em->flush();
         $res = 1;
       }
       return $res;
    }
    
    /**
     *  Delete a rating by one user for one question
     * 
     * @param type $user
     * @param type $question
     * @return int 1=delete vote, 0= nothing to delete
     */
    private function doDeleteRating($user, $question){
       $res = 0;
       $em = $this->getDoctrine()->getManager();
       $vote = $em->getRepository('AppBundle:Vote')
           ->findOneBy(array('user'=>$user, 'question'=>$question));
       if ($vote) {
           $em->remove($vote);
           $em->flush();
           $res = 3;
       }
       return $res;
    }

 private function getRating($question, $user) {
    $em = $this->getDoctrine()->getManager();
    $vote = $em->getRepository('AppBundle:Vote')
        ->findOneBy(array('user' => $user, 'question' => $question));
    if ($vote) {
      return $vote->getValue();
    } else {
      return 0;
    }
  }
    
    
}
