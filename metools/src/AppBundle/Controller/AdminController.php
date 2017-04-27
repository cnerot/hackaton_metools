<?php

namespace AppBundle\Controller;

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
        // replace this example code with whatever you need
        return $this->render('admin/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }

    /*
        public function skillscreateAction(Request $request)
        {
            // replace this example code with whatever you need
            return $this->render('admin/skillcreate.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            ]);
        }
    */
    public function questioncreateAction(Request $request)
    {
        $question = $this->getDoctrine()
            ->getRepository('AppBundle:questions')
            ->findAll();
        $form = $this->createFormBuilder($question)
            ->add("Fonctionnelle", CheckboxType::class)
            ->add("contenue", TextType::class)
            ->add("type", ChoiceType::class, array(
                'choices' => array(
                    'QCM' => 0,
                    'Trouver l`erreur' => 1,
                    'Reponse chiffre' => 2,
                    'Reponse text' => 3,
                )))
            ->add('save', SubmitType::class, array('label' => 'Create Post'))
            ->getForm()
            ->createView();
        // replace this example code with whatever you need
        return $this->render('admin/questioncreate.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'form' => $form,
        ]);
    }

    public function questionAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('admin/question.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ]);
    }
}
