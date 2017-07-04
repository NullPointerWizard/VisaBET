<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use AppBundle\Form\LoginFormType;

class SecurityController extends Controller
{
    /**
    * @Route("/login", name="visa_login")
    */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');
       // get the login error if there is one
       $error = $authenticationUtils->getLastAuthenticationError();
       // last username entered by the user
       $lastUsername = $authenticationUtils->getLastUsername();

       $form = $this->createForm(LoginFormType::class, [
                   '_username' => $lastUsername,
               ]);

       return $this->render(
           'security/login.html.twig',
           array(
               // last username entered by the user
               'form'           => $form->createView(),
               'error'          => $error,
           )
       );
    }
}
