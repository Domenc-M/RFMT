<?php

namespace App\Controller;

use App\Entity\Intrigue;
use App\Form\IntrigueType;
use App\Repository\IntrigueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intrigue")
 */
class IntrigueController extends AbstractController
{
    /**
     * @Route("/", name="intrigue_index", methods={"GET"})
     */
    public function index(IntrigueRepository $intrigueRepository): Response
    {
        return $this->render('intrigue/index.html.twig', [
            'intrigues' => $intrigueRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="intrigue_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $intrigue = new Intrigue();
        $form = $this->createForm(IntrigueType::class, $intrigue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($intrigue);
            $entityManager->flush();

            return $this->redirectToRoute('intrigue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intrigue/new.html.twig', [
            'intrigue' => $intrigue,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="intrigue_show", methods={"GET"})
     */
    public function show(Intrigue $intrigue): Response
    {
        return $this->render('intrigue/show.html.twig', [
            'intrigue' => $intrigue,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="intrigue_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Intrigue $intrigue, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(IntrigueType::class, $intrigue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('intrigue_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('intrigue/edit.html.twig', [
            'intrigue' => $intrigue,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="intrigue_delete", methods={"POST"})
     */
    public function delete(Request $request, Intrigue $intrigue, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$intrigue->getId(), $request->request->get('_token'))) {
            $entityManager->remove($intrigue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('intrigue_index', [], Response::HTTP_SEE_OTHER);
    }
}
