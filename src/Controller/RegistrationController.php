<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\UserAuthenticator;
use App\Service\JWTService;
use App\Service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator,
                             UserAuthenticator $authenticator, EntityManagerInterface $entityManager, SendMail $sendMail,
                            JWTService $jwt): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $user->setRoles(array('ROLE_USER'));
            $entityManager->persist($user);
            $entityManager->flush();

            //generation du JWT de l'utilisateur
            //création header
            $header=[
              'typ'=>'JWT',
              'alg'=>'HS256'
            ];
            //creation payload
            $payload=[
                'user_id'=>$user->getId()
            ];
            //generation token
            $token=$jwt->generate($header,$payload,$this->getParameter(('app.jwtsecret')));

            // do anything else you need here, like send an email
            $sendMail->send('labase@noreply.fr', $user->getEmail(), 'Activation du compte', 'register',compact('user', 'token'));


            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'app_verifyuser')]
    public function verifUser($token, JWTService $jwt, UserRepository $userRepository, EntityManagerInterface $em) : Response
    {
        //verif si token est valide, non expiré et non modifié
        if ($jwt->isValid($token) && $jwt->isExpired($token) && $jwt->check($token, $this->getParameter('app.jwtsecret'))){
            //recup payload
            $payload=$jwt->getPayload($token);
            //recup user du token
            $user=$userRepository->find($payload['user_id']);
            //verif si user existe et n'a pas encore activé son compte
            if ($user && !$user->isIsverified()){
                $user->setIsverified(true);
                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('app_user_index');
            }
        }

        $this->addFlash('danger', 'Le token est invalide ou a expiré.' );
        return $this->redirectToRoute('app_login');


    }


}
