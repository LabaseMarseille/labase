<?php

namespace App\Controller;

use App\Entity\Collectif;
use App\Form\CollectifType;
use App\Repository\CollectifRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
#[Route('/collectif')]
class CollectifController extends AbstractController
{
    public function __construct(
        private readonly AuthorizationCheckerInterface $authorizationChecker
    )  {
    }

    #[Route('/', name: 'app_collectif_index', methods: ['GET'])]
    public function index(CollectifRepository $collectifRepository): Response
    {
        //
        if ($this->authorizationChecker->isGranted('ROLE_REFPROG') or $this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            $collectifs=$collectifRepository->findAll();
        }else{
            $collectifs=$collectifRepository->findBy(array('confidentiel'=>false));
        }

        return $this->render('collectif/index.html.twig', [
            'collectifs' =>$collectifs
        ]);
    }

    #[Route('/collectifson', name: 'app_collectif_json', methods: ['GET'])]
    public function collectifJson(EntityManagerInterface $em): JsonResponse
    {
        $collectifs = $em->getRepository(Collectif::class)->listeCollectif();
        $collectifJson = [];
        foreach ($collectifs as $collectif) {
            $collectifJson[] = [
                'id' => $collectif['id'],'nom' => $collectif['nom'], 'abreviation' => $collectif['abreviation'], 'telephone' => $collectif['telephone'],
                'mail' => $collectif['mail'],'siret' => $collectif['siret'],'codepostal' => $collectif['codepostal'],
                'datecreation' => $collectif['datecreation'] ? $collectif['datecreation']->format('d/m/Y') : '-'];
        }
        return new JsonResponse($collectifJson);
    }

    #[Route('/new', name: 'app_collectif_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $collectif = new Collectif();
        $form = $this->createForm(CollectifType::class, $collectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=$userRepository->findOneBy(array('email'=>$this->getUser()->getUserIdentifier()));
            $collectif->setCreatedby($user);
            $entityManager->persist($collectif);
            $entityManager->flush();

            return $this->redirectToRoute('app_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('collectif/new.html.twig', [
            'collectif' => $collectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_collectif_show', methods: ['GET'])]
    public function show(Collectif $collectif): Response
    {
        return $this->render('collectif/show.html.twig', [
            'collectif' => $collectif,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_collectif_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Collectif $collectif, EntityManagerInterface $entityManager, UserRepository $userRepository): Response
    {
        $form = $this->createForm(CollectifType::class, $collectif);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user=$userRepository->findOneBy(array('email'=>$this->getUser()->getUserIdentifier()));
            $collectif->setModifiedby($user);
            $entityManager->persist($collectif);
            $entityManager->flush();

            return $this->redirectToRoute('app_collectif_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('collectif/edit.html.twig', [
            'collectif' => $collectif,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_collectif_delete', methods: ['POST'])]
    public function delete(Request $request, Collectif $collectif, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collectif->getId(), $request->request->get('_token'))) {
            $entityManager->remove($collectif);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_collectif_index', [], Response::HTTP_SEE_OTHER);
    }
}
