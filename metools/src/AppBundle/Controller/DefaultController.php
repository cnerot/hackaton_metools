<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
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
        $request->request->all();
        //var_dump($request);

        if (isset($_POST['_login']) && isset($_POST['_password'])){
            $login = $_POST['_login'];
            $password = $_POST['_password'];

            $user = $this->getDoctrine()
                ->getRepository('AppBundle:user')
                ->findBy(
                    array('login' => $login,'password' => $password)

                );

            var_dump($user);


        }


        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function loginAction(Request $request)
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('default/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
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
     * @Route("/CreateUser", name="CreateUser")
     */
    public function CreateUserAction(Request $request)
    {
       $request->request->all();

        if (isset($_REQUEST['_login']) && isset($_REQUEST['_password']) && isset($_REQUEST['_password_confirm']) && $_REQUEST['_password_confirm']==$_REQUEST['_password'])
        {
            $user = new user();
            $user -> setLogin($_REQUEST['_login']);
            $user -> setName(' ');
            $user -> setSurname(' ');
            $user -> setAge('5');
            $user->setAddress('xxx   ');
            $user -> setPassword($_REQUEST['_password']);
            $em = $this->getDoctrine()->getManager();

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($user);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();
            return $this->render('default/success.html.twig');
        }
        else{

            return $this->render('default/inscription_erreur.html.twig');
        }



    }

    /**
     * @Route("/game", name="game")
     */
    public function gameAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/game.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/results", name="results")
     */
    public function resultsAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/results.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }

    /**
     * @Route("/evolution", name="evolution")
     */
    public function evolutionAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/evolution.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ]);
    }
    /**
     * @Route("/evolution", name="evolution")
     */
    function  getloggedAction(Request $request)
    {
        return $this->render('default/inscription.html.twig');
    }
}
