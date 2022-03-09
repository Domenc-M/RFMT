<?php

namespace App\Controller;

use App\Entity\Hook;
use App\Form\HookType;
use App\Repository\HookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/hook")
 */
class HookController extends AbstractController
{
    /**
     * @Route("/new", name="hook_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,
                        Security $security, SluggerInterface $slugger): Response
    {
        $hook = new Hook();
        $form = $this->createForm(HookType::class, $hook);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('imgFile')->getData();
            if(!$img)
            {
                $hook->setImg("basic.png");
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
                $hook->setImg($newFilename);
            }

            $hook->setCreator($security->getUser());
            $entityManager->persist($hook);
            $entityManager->flush();
            return $this->redirectToRoute('app_searchOther', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hook/new.html.twig', [
            'hook' => $hook,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="hook_show", methods={"GET"})
     */
    public function show(Hook $hook): Response
    {
        return $this->render('hook/show.html.twig', [
            'hook' => $hook,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hook_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Hook $hook, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger): Response
    {
        $form = $this->createForm(HookType::class, $hook);
        $oldImg = $hook->getImg();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $img = $form->get('imgFile')->getData();

            if(!$img || $img->getClientOriginalName() == $oldImg)
            {
                $hook->setImg($oldImg);
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

                $hook->setImg($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_searchOther', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('hook/edit.html.twig', [
            'hook' => $hook,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="hook_delete", methods={"POST"})
     */
    public function delete(Request $request, Hook $hook, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hook->getId(), $request->request->get('_token'))) {
            if($hook->getImg() != "basic.png")
            {
                unlink($this->getParameter('img_directory')."/".$hook->getImg());
            }
            $entityManager->remove($hook);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hook_index', [], Response::HTTP_SEE_OTHER);
    }
}
