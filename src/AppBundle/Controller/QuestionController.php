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
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * Question controller.
 *
 * @Route("/quiz/{_locale}/question")
 */
class QuestionController extends Controller {

  /**
   * Lists all Question entities.
   *
   * @Route("/", name="question")
   * @Route("/class/{id}/", name="question_classroom")
   * @Method("GET")
   * @Template()
   */
  public function indexAction(Request $request, $id = null) {
    $em = $this->getDoctrine()->getManager();

    $classroom = null;
    $classrooms = $this->getUser()->getClassrooms();

    if ($id) {
      // classroom change => reset scope
      $request->getSession()->remove('idScope');
    } else {
      $id = $request->getSession()->get('idClassroom');
    }

    if ($id) {
      $classroom = $em->getRepository('AppBundle:Classroom')->find($id);
    }

    if (!$classroom || !$classrooms->contains($classroom)) {
      $classroom = $classrooms->isEmpty() ? null : $classrooms->get(0);
    }

    if (!$classroom) {
      return $this->errorToFindClassroom($request, 'You need to select one classroom.');
      // throw $this->createNotFoundException('Unable to find classroom.');
    }

    /*
     * Les lignes suivantes vont tester si un idScope est récupéré dans le session
     * Si il n'y a pas d'idScope dans la session, alors l'application affichera toutes les questions
     * Sinon, elle affichera uniquement les questions du thème selectionné
     */
    $currentScope = $request->getSession()->get('idScope');
    $user = $this->getUser();

    if (!$currentScope) {
        $entities = $em->getRepository('AppBundle:Question')->lesQuestions($classroom, $user->getUsername());
    }
    else {
        $entities = $em->getRepository('AppBundle:Question')->questionsParTheme($classroom, $user->getUsername(), $currentScope);
    }

    $request->getSession()->set('ids', $this->getIdsAsArray($entities));
    $request->getSession()->set('idClassroom', $classroom->getId());

    return array(
        'entities' => $entities,
        'user' => $this->getUser(),
        'classroom' => $classroom,
        'classrooms' => $classrooms,
        'currentScope' => $currentScope
    );
  }

 /**
  * @Route("/classscope/{idScope}/", name="question_scope")
  * @Template("AppBundle:Question:index.html.twig")
  */
  public function changeScopeAction(Request $request, $idScope = null) {

    $request->getSession()->set('idScope', $idScope);
    return $this->indexAction($request, null);

  }


  /**
   * Rating a question by current user
   * @return Json
   * @Route("/rating/{id}", name="question_rating")
   * @Method("POST")
   *
   */
  public function ratingAction(Request $request, Question $question) {
    // the ParamConverter automatically queries for an object
    // whose $id property matches the {id} value.
    // It will also show a 404 page if no Question can be found.
    $res = 0;
    $value = floatval($request->request->get('value'));
    try {
      if ($value > 0) {
        $res = $this->doRating($this->getUser(), $question, $value);
      } else {
        $res = $this->doDeleteRating($this->getUser(), $question);
      }

      $em = $this->getDoctrine()->getManager();

      $em->persist($question);
      $em->flush();
    } catch (Exception $ex) {
      $res = 0;
    }
    return new JsonResponse(array('ok' => $res));
  }


    /**
     * @return Json
     * @Route("/comment/new/{id}",  name="comment_new")
     * @Method("GET")
     *
     */
    public function commentAction(Question $question)
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
                array('question' => $question)
            ),
            'text/html'
        );
        $this->get('mailer')->send($message);

        return new JsonResponse(array('ok' => 1));
    }


  /**
  * Export list of question in text format
  * @return text
  * @Route("/rawexport", name="question_raw_export")
  * @Method("GET")
  * @Template("AppBundle:Question:export.html.twig")
  *
  */
  public function exportRawAction(Request $request) {


  $aIds = $request->getSession()->get("ids");
  $em = $this->getDoctrine()->getManager();

  $questions = array();
  foreach ($aIds as $id) {
    $question = $em->getRepository('AppBundle:Question')->find($id);
    if ($question){
      $questions[] = $question;
    }
  }

  return array(
              'questions'=> $questions,
      );

    }

  /**
  * Export list of question in xml format
  * @return xml
  * @Route("/moodleexport", name="question_moodle_export")
  * @Method("GET")
  * @Template("AppBundle:Question:export.xml.twig")
  *
  */
  public function exportMoodleAction(Request $request) {


  $aIds = $request->getSession()->get("ids");
  $em = $this->getDoctrine()->getManager();

  $questions = array();
  foreach ($aIds as $id) {
    $question = $em->getRepository('AppBundle:Question')->find($id);
    if ($question){
      $questions[] = $question;
    }
  }

  foreach($questions as $q){
      $sumGoodMoodleValue = 0;
      $sumWrongMoodleValue = 0;
      $goodResValues = $q->getExpectedChoices();
      $wrongResValues = $q->getAllValues() - $goodResValues;
      foreach($q->getResponses() as $resp){

          if($resp->getValue() > 0){

              $moodleGoodValue = $resp->getValue() / $goodResValues * 100;
              $resp->moodleValue = round($moodleGoodValue);

              if($resp->moodleValue == 100){
                  $resp->moodleValue = 100;
              }
              else if($resp->moodleValue <= 99 && $resp->moodleValue >= 90){
                  $resp->moodleValue = 90;
              }
              else if($resp->moodleValue <= 89 && $resp->moodleValue >= 80){
                  $resp->moodleValue = 80;
              }
              else if($resp->moodleValue <= 79 && $resp->moodleValue >= 70){
                  $resp->moodleValue = 70;
              }
              else if($resp->moodleValue <= 69 && $resp->moodleValue >= 60){
                  $resp->moodleValue = 60;
              }
              else if($resp->moodleValue <= 59 && $resp->moodleValue >= 50){
                  $resp->moodleValue = 50;
              }
              else if($resp->moodleValue <= 49 && $resp->moodleValue >= 40){
                  $resp->moodleValue = 40;
              }
              else if($resp->moodleValue <= 39 && $resp->moodleValue >= 30){
                  $resp->moodleValue = 30;
              }
              else if($resp->moodleValue <= 29 && $resp->moodleValue >= 20){
                  $resp->moodleValue = 20;
              }
              else {
                  $resp->moodleValue = 10;
              }

              $sumGoodMoodleValue = $sumGoodMoodleValue + $resp->moodleValue;
          }
          else{
              $moodleWrongValue = $resp->getValue() / $wrongResValues * 100;
              $resp->moodleValue = round($moodleWrongValue);

              if($resp->moodleValue == 100){
                  $resp->moodleValue = 100;
              }
              else if($resp->moodleValue <= 99 && $resp->moodleValue >= 90){
                  $resp->moodleValue = 90;
              }
              else if($resp->moodleValue <= 89 && $resp->moodleValue >= 80){
                  $resp->moodleValue = 80;
              }
              else if($resp->moodleValue <= 79 && $resp->moodleValue >= 70){
                  $resp->moodleValue = 70;
              }
              else if($resp->moodleValue <= 69 && $resp->moodleValue >= 60){
                  $resp->moodleValue = 60;
              }
              else if($resp->moodleValue <= 59 && $resp->moodleValue >= 50){
                  $resp->moodleValue = 50;
              }
              else if($resp->moodleValue <= 49 && $resp->moodleValue >= 40){
                  $resp->moodleValue = 40;
              }
              else if($resp->moodleValue <= 39 && $resp->moodleValue >= 30){
                  $resp->moodleValue = 30;
              }
              else if($resp->moodleValue <= 29 && $resp->moodleValue >= 20){
                  $resp->moodleValue = 20;
              }
              else {
                  $resp->moodleValue = 10;
              }

              $sumWrongMoodleValue = $sumWrongMoodleValue + $resp->moodleValue;
          }
      }

      /* Ce deuxième foreach des "Responses" permet vérifier si la somme des bonnes et des mauvaises "Values" est égale à 100
       * Si la somme n'est pas égale à 100, alors elle est rectifiée par ce deuxième foreach.
       * Cet algorithme permet d'ajouter ou de soustraire la différence de 100 et la somme des bonnes ou mauvaises "Values" à la première "Response Value"
       * /!\ La limite de cet algo est 10 bonnes réponses ou 10 mauvaises réponses /!\
       * /!\ On peut donc créer des questions avec un maximum de 20 réponses avec au moins 10 bonnes réponses et 10 mauvaises /!\
       */
      foreach($q->getResponses() as $resp){

          if($resp->getValue() > 0){
              if ($sumGoodMoodleValue < 100){
                  $resp->moodleValue = $resp->moodleValue + (100 - $sumGoodMoodleValue);
                  $sumGoodMoodleValue = 100;
              }
              elseif ($sumGoodMoodleValue > 100) {
                  $resp->moodleValue = $resp->moodleValue + -(100 - $sumGoodMoodleValue);
                  $sumGoodMoodleValue = 100;
              }
              else {
                  $resp->moodleValue;
              }
          }
          else {
              if ($sumWrongMoodleValue < 100){
                  $resp->moodleValue = $resp->moodleValue + (100 - $sumWrongMoodleValue);
                  $sumWrongMoodleValue = 100;
              }
              elseif ($sumWrongMoodleValue > 100) {
                  $resp->moodleValue = $resp->moodleValue + -(100 - $sumWrongMoodleValue);
                  $sumWrongMoodleValue = 100;
              }
              else {
                  $resp->moodleValue;
              }
          }
      }
  }


  $response = new Response();
  $response->headers->set('Content-Type', 'xml');

  $compteur = 0;

  return $this->render(
          'AppBundle:Question:export.xml.twig',
          array(
              'questions'=> $questions,
              'compteur'=> $compteur,
          ),
          $response
      );
  }

  /**
   * Creates a new Question entity.
   *
   * @Route("/", name="question_create")
   * @Method("POST")
   * @Template("AppBundle:Question:edit.html.twig")
   */
  public function createAction(Request $request) {
    $classroom = $this->getClassroomFromSession($request);
    if (!$classroom) {
      return $this->errorToAccessRessource($request,
          'Unable to find ');
    }
    $entity = new Question();
    $entity->setClassroom($classroom);
    $form = $this->createCreateForm($entity, $classroom);
    $form->handleRequest($request);
    $entity->setDesigner($this->getUser());
    if ($form->isValid()) {
      // set date publication is doPublish is there and is checked
      if ($form->get('doPublish')->getData()) {
        $entity->setDatepub(new \DateTime());
      }

      $em = $this->getDoctrine()->getManager();
      $em->persist($entity);
      $em->flush();
      return $this->redirect(
          $this->generateUrl('question', array()));
    }

    return array(
        'entity' => $entity,
        'form' => $form->createView(),
    );
  }

  private function getClassroomFromSession($request) {
    $idClassroom = $request->getSession()->get('idClassroom');
    $classroom = null;
    if ($idClassroom) {
      $em = $this->getDoctrine()->getManager();
      $classroom = $em->getRepository('AppBundle:Classroom')->find($idClassroom);
    }
    return $classroom;
  }

  /**
   * Creates a form to create a Question entity.
   *
   * @param Question $entity The entity
   *
   * @return \Symfony\Component\Form\Form The form
   */
  private function createCreateForm(Question $entity, $classroom) {
    $form = $this->createForm(new QuestionType($classroom), $entity, array(
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
   * @Template("AppBundle:Question:edit.html.twig")
   */
  public function newAction(Request $request) {
    $entity = new Question();
    $classroom = $this->getClassroomFromSession($request);
    if (!$classroom) {
      return $this->errorToAccessRessource($request, 'Unable to find classroom.');
      //throw $this->createNotFoundException('Unable to find classroom.');
    }
    $entity->setClassroom($classroom);
    $entity->setDesigner($this->getUser());
    $form = $this->createCreateForm($entity, $classroom);

    return array(
        'entity' => $entity,
        'form' => $form->createView(),
    );
  }

  /**
   * Finds and displays a Question entity.
   *
   * @Route("/{id}", name="question_show")
   * @Method("GET")
   * @Template()
   */
  public function showAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();
    $entity = $em->getRepository('AppBundle:Question')->find($id);

    if (!$entity) {
      throw $this->createNotFoundException('Unable to find Question entity.');
    }
    $user = $this->getUser();

    $isDesigner = $entity->isDesigner($user);
    $isCoDesigner = false;
    // search if current user is a codesigner
    if (!$isDesigner) {
      $isCoDesigner = $entity->isCoDesigner($user);
    }

    if ($isDesigner || $isCoDesigner) {
      $rating = $entity->getAvgRatings();
      // or $rating = $this->getAvgRatings($entity); ?? performance ??
    } else {
      $rating = $this->getRating($entity, $user);
    }

    // $deleteForm = $this->createDeleteForm($id);

    $linksNav = $this->getLinksNav($request, $id);

    return array(
        'isDesigner' => $isDesigner,
        'isCoDesigner' => $isCoDesigner,
        'entity' => $entity,
        'rating' => $rating,
        'first' => $linksNav[0],
        'prev' => $linksNav[1],
        'next' => $linksNav[2],
        'last' => $linksNav[3],
        // 'delete_form' => $deleteForm->createView(),
    );
  }

  /**
   * Get array of entity id
   * @param Entity $entities
   * @return array
   */
  private function getIdsAsArray($entities) {
    $res = array();
    foreach ($entities as $entity) {
      $res[] = $entity->getId();
    }
    return $res;
  }

  /**
   * Get links for navigate
   *
   * @param Request $request
   */
  private function getLinksNav($request, $curId) {
    $aIds = $request->getSession()->get("ids");
    $prev = false;
    $next = false;
    $last = false;
    $first = false;
    // case no array ids in session (direct acces or session lost)
    if ($aIds) {
      $iCur = array_search($curId, $aIds);
      if ($iCur) {
        $prev = $aIds[$iCur - 1];
      }
      if ($iCur < count($aIds) - 1) {
        $next = $aIds[$iCur + 1];
      }
      if ($prev) {
        $first = $aIds[0];
      }
      if ($iCur < count($aIds) - 1) {
        $last = $aIds[count($aIds) - 1];
      }
    }
    return array($first, $prev, $next, $last);
  }

  /**
   * Displays a form to edit an existing Question entity.
   *
   * @Route("/{id}/edit", name="question_edit")
   * @Method("GET")
   * @Template("AppBundle:Question:edit.html.twig")
   */
  public function editAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();

    $entity = $em->getRepository('AppBundle:Question')->find($id);

    if (!$entity) {
      return $this->errorToAccessRessource($request, 'Unable to find question.');
      // throw $this->createNotFoundException('Unable to find Question entity.');
    }

    $user = $this->getUser();
    if ( !$entity->isCanUpdate($user) && !$this->isGranted('ROLE_ADMIN')) {
      return $this->errorToAccessRessource($request, 'Acces denied');
    }

    $editForm = $this->createEditForm($entity);
    $deleteForm = $this->createDeleteForm($id);

    return array(
        'entity' => $entity,
        'form' => $editForm->createView(),
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
  private function createEditForm(Question $entity) {
    $form = $this->createForm(new QuestionType($entity->getClassroom()), $entity, array(
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
  public function updateAction(Request $request, $id) {
    $em = $this->getDoctrine()->getManager();

    $question = $em->getRepository('AppBundle:Question')->find($id);

    // $savAvgRating = $question->getAvgRatings();
    $savAuthor = $question->getDesigner();

    if (!$question) {
      return $this->errorToAccessRessource($request, 'Unable to find question.');
      // throw $this->createNotFoundException('Unable to find Question entity.');
    }

    $user = $this->getUser();
    if ( !$question->isCanUpdate($user) && !$this->isGranted('ROLE_ADMIN')) {
      return $this->errorToAccessRessource($request, 'Acces denied');
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

    // prevent hack...
    // $question->setAvgRating($savAvgRating);
    $question->setDesigner($savAuthor);

    if ($editForm->isValid()) {
      // supprime la relation entre la response et la question
      foreach ($originalResponses as $resp) {
        if ($question->getResponses()->contains($resp) == false) {
          // suppression du lien
          $resp->setQuestion(null);
          $em->remove($resp);
        }
      }

      // set date publication is doPublish is there and is checked
      if ($editForm->get('doPublish')->getData()) {
        $question->setDatepub(new \DateTime());
      }

      $em->persist($question);
      $em->flush();

      return $this->redirect($this->generateUrl('question', array()));
    }

    return array(
        'entity' => $question,
        'form' => $editForm->createView(),
        'delete_form' => $deleteForm->createView(),
    );
  }

  /**
   * Deletes a Question entity.
   *
   * @Route("/{id}", name="question_delete")
   * @Method("DELETE")
   */
  public function deleteAction(Request $request, $id) {
    $form = $this->createDeleteForm($id);
    $form->handleRequest($request);

    if ($form->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $entity = $em->getRepository('AppBundle:Question')->find($id);

      if (!$entity) {
        return $this->errorToAccessRessource($request, 'Unable to find question.');
        // throw $this->createNotFoundException('Unable to find Question entity.');
      }

      $user = $this->getUser();
      if ( !$entity->getDesigner() == $user->getUsername() && !$this->isGranted('ROLE_ADMIN')) {
        return $this->errorToAccessRessource($request, 'Acces denied');
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
  private function createDeleteForm($id) {
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
   * @param User $user
   * @param Question $question
   * @param float $value
   * @return int 0=no opération, 1=create rating, 2=update rating
   */
  private function doRating($user, $question, $value) {
    $res = 0;
    $em = $this->getDoctrine()->getManager();
    $rating = $em->getRepository('AppBundle:Rating')
        ->findOneBy(array('user' => $user, 'question' => $question));
    if ($rating) {
      if ($rating->getValue() != $value) {
        $rating->setValue($value);
        $em->persist($rating);
        $em->flush();
        $res = 2;
      }
    } else {
      $rating = new \AppBundle\Entity\Rating();
      $rating->setQuestion($question);
      $rating->setUser($user);
      $rating->setValue($value);
      $em->persist($rating);
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
   * @return int 1=delete rating, 0= nothing to delete
   */
  private function doDeleteRating($user, $question) {
    $res = 0;
    $em = $this->getDoctrine()->getManager();
    $rating = $em->getRepository('AppBundle:Rating')
        ->findOneBy(array('user' => $user, 'question' => $question));
    if ($rating) {
      $em->remove($rating);
      $em->flush();
      $res = 3;
    }
    return $res;
  }

  /**
   * Get rating by current user of this question
   *
   * @param type $question
   * @param type $user
   * @return int
   */
  private function getRating($question, $user) {
    $em = $this->getDoctrine()->getManager();
    $rating = $em->getRepository('AppBundle:Rating')
        ->findOneBy(array('user' => $user, 'question' => $question));
    if ($rating) {
      return $rating->getValue();
    } else {
      return 0;
    }
  }

  /*
   * Get avg value rating for one question
   * @param Question $question
   * @return float avg rating
   */

  private function getAvgRatings($question) {
    $em = $this->getDoctrine()->getManager();
    $avgRating = $em->getRepository('AppBundle:Rating')
        ->getAvgRating($question);
    if ($avgRating) {
      return floatval($avgRating);
    } else {
      return 0.0;
    }
  }

  private function errorToAccessRessource($request, $msg){
    $session = $request->getSession();
    $session->getFlashBag()->add('warning', $msg);
    return $this->redirect($this->generateUrl('question'));
  }

  private function errorToFindClassroom($request, $msg){
    $session = $request->getSession();
    $session->getFlashBag()->add('warning', $msg);
    return $this->redirect($this->generateUrl('my_classrooms'));
  }

}
