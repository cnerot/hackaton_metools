<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/login.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/inscription ", name="inscription")
     */
    public function InscriptionAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/inscription.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }


    /**
     * @Route("/game", name="game")
     */
    public function gameAction(Request $request)
    {
        $success = false;
        $question_data = $request->request->all();
        if (!empty($question_data)) {

            /** QCM validation */
            if (isset($question_data["qcm"])) {
                if (is_array($question_data["qcm"])) {
                    foreach ($question_data["qcm"] as $element) {
                        $data = $this->getDoctrine()
                            ->getRepository('AppBundle:qcm_entries')
                            ->findOneBy(["id" => $element]);
                        if ($data != null && $data->isCorrect() == 1) {
                            $success = ($success && true);
                        }
                    }
                } else {
                    $data = $this->getDoctrine()
                        ->getRepository('AppBundle:qcm_entries')
                        ->findOneBy(["id" => $question_data["qcm"]]);

                    if ($data != null && $data->isCorrect() == 1) {
                        $success = true;
                    }

                }
            }
            /** Cherchez l'erreur */
            if (isset($question_data['ligne'])) {
                $data = $this->getDoctrine()
                    ->getRepository('AppBundle:questions')
                    ->findOneBy(["id" => $question_data['question']]);
                if ($data->getValidLine() == $question_data['ligne']){
                    $success = true;
                }
            }
            /** Resultat numerique */
            if (isset($question_data['q_anwser'])) {
                $data = $this->getDoctrine()
                    ->getRepository('AppBundle:questions')
                    ->findOneBy(["id" => $question_data['question']]);
                if ($data->getValidLine() == $question_data['q_anwser']){
                    $success = true;
                }
            }
            /** Vrai Faux */
            if (isset($question_data['truefalse'])) {
                $data = $this->getDoctrine()
                    ->getRepository('AppBundle:questions')
                    ->findOneBy(["id" => $question_data['question']]);
                if ($data->getValidLine() == $question_data['truefalse']){
                    $success = true;
                }
            }

        }

        $questions = $this->getDoctrine()
            ->getRepository('AppBundle:questions')
            ->findOneBy(["id" => 15]);

        $question_data = null;
        if ($questions->getQcm() == 1) {
            $question_data = $this->getDoctrine()
                ->getRepository('AppBundle:qcm_entries')
                ->findBy(["questionId" => $questions->getId()]);
        }

// replace this example code with whatever you need
        return $this->render('default/game.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'question' => $questions,
            'question_data' => $question_data,
            'success' => $success,
        ]);
    }

    /**
     * @Route("/results", name="results")
     */
    public
    function resultsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/results.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/evolution", name="evolution")
     */
    public
    function evolutionAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/evolution.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }
}
