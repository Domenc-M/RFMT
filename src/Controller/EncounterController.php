<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Form\EncounterType;
use App\Repository\EncounterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/encounter")
 */
class EncounterController extends AbstractController
{
    /**
     * @Route("/new", name="encounter_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger, Security $security): Response
    {
        $encounter = new Encounter();
        $form = $this->createForm(EncounterType::class, $encounter);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('imgFile')->getData();
            if(!$img)
            {
                $encounter->setImg("basic.png");
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
                $encounter->setImg($newFilename);
            }
            $encounter->setCreator($security->getUser());
            $entityManager->persist($encounter);
            $entityManager->flush();
            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('encounter/new.html.twig', [
            'encounter' => $encounter,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="encounter_show", methods={"GET"})
     */
    public function show(Encounter $encounter): Response
    {
        return $this->render('encounter/show.html.twig', [
            'encounter' => $encounter,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="encounter_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Encounter $encounter, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger): Response
    {
        $form = $this->createForm(EncounterType::class, $encounter);
        $oldImg = $encounter->getImg();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $img = $form->get('imgFile')->getData();

            if(!$img || $img->getClientOriginalName() == $oldImg)
            {
                $encounter->setImg($oldImg);
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

                if($oldImg != "basic.png")
                {
                    unlink($this->getParameter('img_directory')."/".$oldImg);
                }

                $encounter->setImg($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('encounter/edit.html.twig', [
            'encounter' => $encounter,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="encounter_delete", methods={"POST"})
     */
    public function delete(Request $request, Encounter $encounter, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$encounter->getId(), $request->request->get('_token'))) {
            if($encounter->getImg() != "basic.png")
            {
                unlink($this->getParameter('img_directory')."/".$encounter->getImg());
            }
            $entityManager->remove($encounter);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
    }
}
