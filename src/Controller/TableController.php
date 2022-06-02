<?php

namespace App\Controller;

use App\Entity\Table;
use App\Form\TableType;
use App\Repository\TableRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/table")
 */
class TableController extends AbstractController
{
    /**
     * @Route("/public/new", name="table_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,
                        Security $security, SluggerInterface $slugger): Response
    {
        $table = new Table();
        $form = $this->createForm(TableType::class, $table);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            
            $tableEntries = [];
            //HANDLE ENTRIES
            for ($i=1; $i <= 20; $i++) { 
                $entry = $request->request->get("tableEntry".$i);
                array_push($tableEntries, $entry);
            }
            if(in_array("", $tableEntries))
            {
                $route = $request->headers->get('referer');

                return $this->redirect($route);
            }
            else
            {
                $table->setContent($tableEntries);
            }

            //HANDLE IMG
            $img = $form->get('imgFile')->getData();
            if(!$img)
            {
                $table->setImg("basic.png");
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
                $table->setImg($newFilename);
            }

            $table->setCreator($security->getUser());
            $entityManager->persist($table);
            $entityManager->flush();
            return $this->redirectToRoute('app_searchOther', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('table/new.html.twig', [
            'table' => $table,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="table_show", methods={"GET"})
     */
    public function show(Table $table): Response
    {
        return $this->render('table/show.html.twig', [
            'table' => $table,
        ]);
    }

    /**
     * @Route("/public/{id}/edit", name="table_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Table $table, EntityManagerInterface $entityManager,
                            SluggerInterface $slugger): Response
    {
        $form = $this->createForm(TableType::class, $table);
        $oldImg = $table->getImg();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('imgFile')->getData();

            if(!$img || $img->getClientOriginalName() == $oldImg)
            {
                $table->setImg($oldImg);
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

                $table->setImg($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('table_index', ['category' => 'table'], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('table/edit.html.twig', [
            'table' => $table,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="table_delete", methods={"POST"})
     */
    public function delete(Request $request, Table $table, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$table->getId(), $request->request->get('_token'))) {
            $entityManager->remove($table);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_searchOther', [], Response::HTTP_SEE_OTHER);
    }
}
