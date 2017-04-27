<?php

namespace AppBundle\Controller;

use AppBundle\Entity\qcm_entries;
use AppBundle\Entity\questions;
use AppBundle\Entity\skill;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AdminController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('admin/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }

    public function profileAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('admin/profile.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }

    public function skillsAction(Request $request)
    {
        $skills = $this->getDoctrine()
            ->getRepository('AppBundle:skill')
            ->findAll();
        // replace this example code with whatever you need
        return $this->render('admin/skill.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'skills' => $skills,
        ]);
    }

    public function skillscreateAction(Request $request)
    {
        $skill_data = $request->request->all();
        if (!empty($skill_data)) {
            $skill = new skill();
            $skill->setCode($skill_data['code']);
            $skill->setName($skill_data['name']);
            $skill->setCategory($skill_data['competence']);
            $em = $this->getDoctrine()->getManager();
            // needed to get question id
            $em->persist($skill);
            $em->flush();
        }
        return $this->render('admin/skillcreate.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }

    public function questioncreateAction(Request $request)
    {
        $error = false;
        $question_data = $request->request->all();
        if (!empty($question_data)) {
            /*save initial data questions*/
            if (!is_string($question_data["content_large"]) ||
                empty($question_data["content_large"])
            ) {
                $error = true;
            }
            $question = new questions();
            $question->setQcm(($question_data["func"] == "question_func") ? true : false); //TODO: change to functionnal/human
            $question->setContent($question_data["content_large"]);
            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            if ($question_data["func"] == "question_func") {
                /**
                 * Functionnal type question
                 */
                if ($question_data["question_type"] == "qcm") {
                    for ($i = 1; $i <= 4; $i++) {
                        $question->setType(0);
                        // needed to get question id
                        $em->flush();
                        $entry = new qcm_entries();
                        $entry->setQuestionId($question->getId());
                        $entry->setAwnser($question_data["qcm_" . $i]);
                        $entry->setCorrect((isset($question_data["qcm_" . $i . "_valid"])) ? 1 : 0);
                        $em->persist($entry);
                    }
                } elseif ($question_data["question_type"] == "error") {
                    $question->setType(1);
                    $question->setValidline($question_data["num_res"]);
                    $question->setCode($question_data["error"]);
                } elseif ($question_data["question_type"] == "valid") {
                    $question->setType(2);
                    $question->getValidline($question_data["truefalse"]);
                } elseif ($question_data["question_type"] == "number") {
                    $question->setType(3);
                    $question->getValidline($question_data["num_res"]);
                } else {
                    /*Error*/
                }
            } else {
                if ($question_data["h_question_type"] == "qcm") {
                    $question->setType(0);
                    //$question->setCode();
                } elseif ($question_data["question_type"] == "error") {
                    $question->setType(1);
                }
            }
            $em->flush();
            // replace this example code with whatever you need
        }
        return $this->render('admin/questioncreate.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }
    public function questionAction(Request $request)
    {
        $questions = $this->getDoctrine()
            ->getRepository('AppBundle:questions')
            ->findAll();

        $q_skills[] = array();
        foreach ($questions as $q){
            $q_skills[$q->getId()] = $this->getDoctrine()
                ->getRepository('AppBundle:questionskill')
                ->findBy(["questionId" => $q->getId()]);
        }
        $question_skills[] = array();
        foreach ($q_skills as $k=>$s){
            $temp = array();
            foreach ($s as $skill){
                $temp[] = $this->getDoctrine()
                    ->getRepository('AppBundle:skill')
                    ->findOneBy(["id" => $skill->getSkillId()]);
            }
            $question_skills[$k] = $temp;
        }


        $skills = $this->getDoctrine()
            ->getRepository('AppBundle:skill')
            ->findAll();
        // replace this example code with whatever you need
        return $this->render('admin/question.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'questions' => $questions,
            'skills' => $skills,
            'qskills' => $question_skills,
        ]);
    }
}
