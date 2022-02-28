<?php

namespace App\Controller;

use App\Entity\Npc;
use App\Form\NpcType;
use App\Repository\NpcRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/npc")
 */
class NpcController extends AbstractController
{
    /**
     * @Route("/", name="npc_index", methods={"GET"})
     */
    public function index(NpcRepository $npcRepository): Response
    {
        return $this->render('npc/index.html.twig', [
            'npcs' => $npcRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="npc_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $npc = new Npc();
        $form = $this->createForm(NpcType::class, $npc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($npc);
            $entityManager->flush();

            return $this->redirectToRoute('npc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('npc/new.html.twig', [
            'npc' => $npc,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="npc_show", methods={"GET"})
     */
    public function show(Npc $npc): Response
    {
        return $this->render('npc/show.html.twig', [
            'npc' => $npc,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="npc_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Npc $npc, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NpcType::class, $npc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('npc_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('npc/edit.html.twig', [
            'npc' => $npc,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="npc_delete", methods={"POST"})
     */
    public function delete(Request $request, Npc $npc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$npc->getId(), $request->request->get('_token'))) {
            $entityManager->remove($npc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('npc_index', [], Response::HTTP_SEE_OTHER);
    }
}
