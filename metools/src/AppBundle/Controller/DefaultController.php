<?php
namespace AppBundle\Controller;

use AppBundle\Entity\skill_lvl;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\DateTime;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $skills = $this->getDoctrine()
            ->getRepository('AppBundle:skill_lvl')
            ->findBy(["userId" => 1]);
        $res_skill = array();
        foreach ($skills as $skill){
            if (!isset($res_skill[$skill->getSkillId()])){
                $res_skill[$skill->getSkillId()] = $skill;
            } else {
                if ( $res_skill[$skill->getSkillId()]->getDate()< $skill->getDate()){
                    $res_skill[$skill->getSkillId()] = $skill;
                }
            }
        }
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'skills' => $res_skill,
            'all_skills' => $skills
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
        $game_end = false;
        $test_length = 40;
        /**Check anwser*/
        if (!isset($_SESSION["gamestate"])) {
            $_SESSION["gamestate"] = 1;
        }
        if (!isset($_SESSION["question"])) {
            $_SESSION["question"] = array();
        }
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
                if ($data->getValidLine() == $question_data['ligne']) {
                    $success = true;
                }
            }
            /** Resultat numerique */
            if (isset($question_data['q_anwser'])) {
                $data = $this->getDoctrine()
                    ->getRepository('AppBundle:questions')
                    ->findOneBy(["id" => $question_data['question']]);
                if ($data->getValidLine() == $question_data['q_anwser']) {
                    $success = true;
                }
            }
            /** Vrai Faux */
            if (isset($question_data['truefalse'])) {
                $data = $this->getDoctrine()
                    ->getRepository('AppBundle:questions')
                    ->findOneBy(["id" => $question_data['question']]);
                if ($data->getValidLine() == $question_data['truefalse']) {
                    $success = true;
                }
            }
            /**evolution data*/
            if (isset($_SESSION["gamestate"])) {
                $_SESSION["gamestate"] += 1;
            }
            if ($success) {
                $_SESSION["question"][$question_data['question']] = 1;
            } else {
                $_SESSION["question"][$question_data['question']] = 0;
            }
        }

        /**get current question*/
        $questions = $this->getDoctrine()
            ->getRepository('AppBundle:questions')
            ->findAll();
        $question_count = count($questions);
        $anwser_count = count($_SESSION['question']);
        $questions = $questions[rand(0, count($questions) - 1)];
        if ($question_count > $anwser_count) {
            while (key_exists($questions->getId(), $_SESSION["question"])) {
                $questions = $this->getDoctrine()
                    ->getRepository('AppBundle:questions')
                    ->findAll();
                $questions = $questions[rand(0, count($questions) - 1)];
            }
            $question_data = null;

            if ($questions->getQcm() == 1) {
                $question_data = $this->getDoctrine()
                    ->getRepository('AppBundle:qcm_entries')
                    ->findBy(["questionId" => $questions->getId()]);
            }
        } else {
            $game_end = true;
            $questions = null;
            $question_data = null;
        }
        if ($game_end) {
            $this->_endgame();
        }
        $progression = ($question_count < $test_length)?round(($anwser_count/$question_count)*100,1):round(($anwser_count/$test_length)*100,1);
        return $this->render('default/game.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'question' => $questions,
            'question_data' => $question_data,
            'success' => $success,
            'session' => $_SESSION,
            'test_length' => $test_length,
            'progression' => $progression
        ]);
    }

    public function _endgame()
    {
        $skill_res = array();
        $skill_total = array();
        foreach ($_SESSION["question"] as $question => $anwser) {
            $q_skills = $this->getDoctrine()
                ->getRepository('AppBundle:questionskill')
                ->findBy(["questionId" => $question]);
            foreach ($q_skills as $skill) {
                if (!isset($skill_res[$skill->getSkillId()])) {
                    $skill_res[$skill->getSkillId()] = 0;
                }
                if (!isset($skill_total[$skill->getSkillId()])) {
                    $skill_total[$skill->getSkillId()] = 0;
                }
                $skill_total[$skill->getSkillId()] += 1;
            }
            if ($anwser) {
                foreach ($q_skills as $skill) {
                    $skill_res[$skill->getSkillId()] += 1;
                }
            }
        }

        $em = $this->getDoctrine()->getManager();
        /**update database*/
        foreach ($skill_total as $k => $s_r) {
            $s_t = $skill_res[$k];
            $skill_lvl = new skill_lvl();
            $skill_lvl->setLevel(round(($s_t / $s_r) * 100, 1));
            $skill_lvl->setSkillId($k);
            $skill_lvl->setUserId(1);
            $skill_lvl->setDate(new \DateTime());
            $em->persist($skill_lvl);

        }
        $em->flush();
        unset($_SESSION["gamestate"]);
        unset($_SESSION["question"]);
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
