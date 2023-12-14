<?php

namespace App\Controller;

use App\Entity\Possession;
use App\Form\PossessionType;
use App\Repository\PossessionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/possession')]
class PossessionController extends AbstractController
{
    #[Route('/', name: 'app_possession_index', methods: ['GET'])]
    public function index(PossessionRepository $possessionRepository): Response
    {
        return $this->render('possession/index.html.twig', [
            'possessions' => $possessionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_possession_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $possession = new Possession();
        $form = $this->createForm(PossessionType::class, $possession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($possession);
            $entityManager->flush();

            return $this->redirectToRoute('app_possession_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('possession/new.html.twig', [
            'possession' => $possession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_possession_show', methods: ['GET'])]
    public function show(Possession $possession): Response
    {
        return $this->render('possession/show.html.twig', [
            'possession' => $possession,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_possession_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Possession $possession, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PossessionType::class, $possession);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_possession_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('possession/edit.html.twig', [
            'possession' => $possession,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_possession_delete', methods: ['POST'])]
    public function delete(Request $request, Possession $possession, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$possession->getId(), $request->request->get('_token'))) {
            $entityManager->remove($possession);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_possession_index', [], Response::HTTP_SEE_OTHER);
    }
}
