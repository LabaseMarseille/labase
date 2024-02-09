<?php

namespace App\Controller;

use App\Form\ResetPasswordRequestType;
use App\Form\ResetPasswordType;
use App\Repository\UserRepository;
use App\Service\SendMail;
use Container7D1zzHx\getMailer_TransportFactory_SendmailService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //utilisateur deja connecté ==>redirection vers autre page
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/oublipass', name: 'app_forgetpassword')]
    public function forgottenPassword(Request $request, UserRepository $userRepository, TokenGeneratorInterface $tokenGenerator,
        EntityManagerInterface $entityManager, SendMail $sendMail): Response
    {

        $form=$this->createForm(ResetPasswordRequestType::class);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            $user= $userRepository->findOneBy(array('email'=>$form->get('email')->getData()));
            if ($user){
                //génération token
                $token=$tokenGenerator->generateToken();
                $user->setResetToken($token);
                $entityManager->persist($user);
                $entityManager->flush();

                // lien de réinitialisation mot de passe
                $url=$this->generateUrl('app_resetpass',['token'=>$token],UrlGeneratorInterface::ABSOLUTE_URL);

                //mail
                $context=compact('url','user');
                $sendMail->send('labase@noreply.fr', $user->getEmail(), 'Réinitialisation du mot de passe', 'password-reset', $context);

                $this->addFlash('success', 'E-mail envoyé avec succès');
                return $this->redirectToRoute('app_login');
            }else {
                dump("gfdgg");
                $this->addFlash('danger', 'Cet e-mail n\'est pas valide.');
                return $this->redirectToRoute('app_login');
            }

        }

        return $this->render('security/reset_password_request.html.twig',
        [
            'form'=>$form->createView()
        ]);
    }

    #[Route(path: '/oublipass/{token}', name: 'app_resetpass')]
    public function resetPass($token,Request $request, UserRepository $userRepository,EntityManagerInterface $entityManager,UserPasswordHasherInterface $passwordHasher) : Response
    {
        //verif token
        $user=$userRepository->findOneByResetToken($token);
        if ($user){
            $form=$this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()){
                $user->setResetToken('');
                $user->setPassword(
                  $passwordHasher->hashPassword($user,$form->get('password')->getData())
                );
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'Mot de passe changé avec succès.');
                return $this->redirectToRoute('app_login');
            }


            return $this->render('security/reset_password.html.twig', [
                'form'=>$form
            ]);
        }else{
            $this->addFlash('danger','Jeton invalide');
            return $this->redirectToRoute('app_login');
        }

    }

}
