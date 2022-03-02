<?php

namespace App\Controller;

use App\Entity\Npc;
use App\Form\NpcType;
use App\Repository\NpcRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/npc")
 */
class NpcController extends AbstractController
{
    /**
     * @Route("/new", name="npc_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,
                        Security $security, SluggerInterface $slugger): Response
    {
        $npc = new Npc();
        $form = $this->createForm(NpcType::class, $npc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('img')->getData();
            if(!$img)
            {
                $npc->setImg("basic.png");
            }
            else
            {
                $originalFilename = pathinfo($img->getClientOriginalName(), PATHINFO_FILENAME);
                //Slug to make file name safe
                $safeFilename = $slugger->slug($originalFilename);
                //Uniqid to prevent twice the same name in DB, so they dont replace each other.
                $newFilename = $safeFilename.'-'.uniqid().'.'.$img->guessExtension();

                try {
                    $img->move(
                        $this->getParameter('img_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // TODO
                }
                $npc->setImg($newFilename);
            }

            $npc->setCreator($security->getUser());
            $entityManager->persist($npc);
            $entityManager->flush();
            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
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
