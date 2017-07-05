<?php

namespace AppBundle\Security;

use AppBundle\Form\LoginFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Form\FormFactoryInterface;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $formFactory; //On construit le formulaire sans l'appel de createView dans le controller
    private $entityManager; //Pour recuperer l'utilisateur dans la BDD
    private $router; //Permet la redirection sur la page login en cas d'echec d'authentification
    private $encoder; //Pour la verification du mot de passe dans checkCredentials()

    public function __construct(FormFactoryInterface $formFactory, EntityManagerInterface $entityManager, RouterInterface $router, UserPasswordEncoderInterface $encoder)
    {
        $this->formFactory = $formFactory;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->encoder = $encoder;
    }

    /*
    * Renvoie les identifiants fournis par l'utilisateur
    */
    public function getCredentials(Request $request)
    {
        $isLoginSubmit = $request->getPathInfo() == '/login' && $request->isMethod('POST');
        if (!$isLoginSubmit) {
            // skip authentication
            return;
        }

        $form = $this->formFactory->create(LoginFormType::class);
        $form->handleRequest($request);

        $data = $form->getData();
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $data['_username']
        );

        return $data;
    }

    /*
    * Renvoie un objet Utilisateur associe aux identifiants fournis precedement
    */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];

        return $this->entityManager->getRepository('AppBundle\Entity\Utilisateur')
            ->findOneBy(['mail' => $username]);
    }

    /*
    * Verification du mot de passe
    */
    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];
        if ($this->encoder->isPasswordValid($user, $password)) {
            return true;
        }

        return false;
    }

    protected function getLoginUrl()
   {
       return $this->router->generate('visa_login');
   }

   // Obsolete dans Symfony 3 remplace par onAuthenticationSuccess
   // protected function getDefaultSuccessRedirectUrl()
   // {
   // }


   /*
   * Redirige apres l'authentication vers la page souhaitee
   */
   public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
   {
       // if the user hits a secure page and start() was called, this was
       // the URL they were on, and probably where you want to redirect to
       //$targetPath = $this->getTargetPath($request->getSession(), $providerKey);

       //if (!$targetPath) {
            $targetPath = $this->router->generate('travaux');
       //}

       return new RedirectResponse($targetPath);
   }
}
