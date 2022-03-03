<?php

namespace App\Controller;

use App\Entity\Intrigue;
use App\Form\IntrigueType;
use App\Repository\IntrigueRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/intrigue")
 */
class IntrigueController extends AbstractController
{
    /**
     * @Route("/new", name="intrigue_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger, Security $security): Response
    {
        $intrigue = new Intrigue();
        $form = $this->createForm(IntrigueType::class, $intrigue);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('imgFile')->getData();
            if(!$img)
            {
                $intrigue->setImg("basic.png");
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
                $intrigue->setImg($newFilename);
            }
            $intrigue->setCreator($security->getUser());
            $entityManager->persist($intrigue);
            $entityManager->flush();
            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
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
    public function edit(Request $request, Intrigue $intrigue, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger): Response
    {
        $form = $this->createForm(IntrigueType::class, $intrigue);
        $oldImg = $intrigue->getImg();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $img = $form->get('imgFile')->getData();

            if(!$img || $img->getClientOriginalName() == $oldImg)
            {
                $intrigue->setImg($oldImg);
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

                $intrigue->setImg($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
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
            if($intrigue->getImg() != "basic.png")
            {
                unlink($this->getParameter('img_directory')."/".$intrigue->getImg());
            }
            $entityManager->remove($intrigue);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
    }
}
