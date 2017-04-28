<?php

namespace AppBundle\Controller;

use AppBundle\Entity\qcm_entries;
use AppBundle\Entity\questions;
use AppBundle\Entity\questionskill;
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
        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();
        $skill = $this->getDoctrine()
            ->getRepository('AppBundle:skill')
            ->findAll();

        // replace this example code with whatever you need
        return $this->render('admin/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'users' => $users,
            'skill' => $skill
        ]);
    }

    public function filteredAction(Request $request)
    {
        $req = $request->request->all();

        if ($req['filter'] == "") {
            $users = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findAll();
        } else {
            $skills = $this->getDoctrine()
                ->getRepository('AppBundle:skill_lvl')
                ->findBy(['skillId' => explode('_', $req['filter'])]);
            $users_ids = array();
            foreach ($skills as $skill) {
                if ($skill->getlevel() > 50) {
                    $users_ids[] = $skill->getUserId();
                }
            }
            $users = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findById($users_ids);
        }
        return $this->render('admin/table.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'users' => $users,
        ]);
    }

    public
    function profileAction(Request $request)
    {
        $r_skill = [];
        $skills = $this->getDoctrine()
            ->getRepository('AppBundle:skill_lvl')
            ->findBy(["userId" => $request->query->get('user')]);
        $res_skill = array();
        foreach ($skills as $skill) {
            if (!isset($res_skill[$skill->getSkillId()])) {
                $res_skill[$skill->getSkillId()] = $skill;
            } else {
                if ($res_skill[$skill->getSkillId()]->getDate() < $skill->getDate()) {
                    $res_skill[$skill->getSkillId()] = $skill;
                }
            }
        }
        // replace this example code with whatever you need
        $request->request->all();
        //var_dump($request);

        if (isset($_POST['_login']) && isset($_POST['_password'])) {
            $login = $_POST['_login'];
            $password = $_POST['_password'];

            $user = $this->getDoctrine()
                ->getRepository('AppBundle:User')
                ->findBy(
                    array('login' => $login, 'password' => $password)
                );
        }


        return $this->render('admin/profile.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'skills' => $res_skill,
            'all_skills' => $skills,
            'admin' => 1
        ]);
    }

    public
    function skillsAction(Request $request)
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

    public
    function skillscreateAction(Request $request)
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

    public
    function questioncreateAction(Request $request)
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
                    $question->setValidline($question_data["truefalse"]);
                } elseif ($question_data["question_type"] == "number") {
                    $question->setType(3);
                    $question->setValidline($question_data["num_res"]);
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

    public
    function questionAction(Request $request)
    {

        $data = $request->request->all();

        if (isset($data["question"]) && isset($data["skill"])) {
            $skill = $this->getDoctrine()
                ->getRepository('AppBundle:questionskill')
                ->findOneBy([
                    "questionId" => $data["question"],
                    "skillId" => $data["skill"]
                ]);
            if ($skill == null) {
                $questionskill = new questionskill();
                $questionskill->setQuestionId($data["question"]);
                $questionskill->setSkillId($data["skill"]);
                $em = $this->getDoctrine()->getManager();
                $em->persist($questionskill);
                $em->flush();
            }
        }
        $questions = $this->getDoctrine()
            ->getRepository('AppBundle:questions')
            ->findAll();
        $q_skills[] = array();
        foreach ($questions as $q) {
            $q_skills[$q->getId()] = $this->getDoctrine()
                ->getRepository('AppBundle:questionskill')
                ->findBy(["questionId" => $q->getId()]);
        }
        $question_skills[] = array();
        foreach ($q_skills as $k => $s) {
            $temp = array();
            foreach ($s as $skill) {
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
