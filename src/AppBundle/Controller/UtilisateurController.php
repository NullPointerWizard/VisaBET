<?php

namespace AppBundle\Controller;

use AppBundle\Form\CreerUtilisateurFormType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

class UtilisateurController extends Controller
{
    /**
    * @Route(
    *   "/creerCompte",
    *   name="creer_compte"
    * )
    */
    public function creerCompteAction(Request $request)
    {
        $form = $this->createForm(CreerUtilisateurFormType::class);

        $form->handleRequest($request);
        if ($form->isValid())
        {
            $nouvelUtilisateur = $form->getData();
            $nouvelUtilisateur->setStatut('Visa');

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($nouvelUtilisateur);
            $entityManager->flush();

            $this->addFlash('success', 'Bienvenue '.$nouvelUtilisateur->getPrenom().' '.$nouvelUtilisateur->getNom().'. Votre compte a bien ete cree !');

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $nouvelUtilisateur,
                    $request,
                    $this->get('AppBundle\Security\LoginFormAuthenticator'),
                    'main'
                );
            //return $this->redirectToRoute('visa_login');
        }

        return $this->render(
            'applicationVisa/creer_utilisateur.html.twig',
            [
                'form'  => $form->createView()
            ]
        );
    }
}
