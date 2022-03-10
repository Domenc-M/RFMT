<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Entity\Intrigue;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Npc;
use App\Form\NpcType;
use App\Repository\NpcRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
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
            $img = $form->get('imgFile')->getData();
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
     * @Route("/{id}", name="npc_show", methods={"GET", "POST"})
     */
    public function show(Npc $npc, Request $request, ManagerRegistry $doctrine, Security $security,
                        EntityManagerInterface $entityManager): Response
    {
        $npcUser = $doctrine->getRepository(Npc::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $npcLink = $npc->getNpc()->toArray();
        array_push($npcLink, $npc);
        $npcAdd = array_diff($npcUser, $npcLink);
        $npcAdd = new ArrayCollection($npcAdd);

        $locationUser = $doctrine->getRepository(Location::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $locationLink = $npc->getLocation()->toArray();
        $locationAdd = array_diff($locationUser, $locationLink);
        $locationAdd = new ArrayCollection($locationAdd);

        $itemUser = $doctrine->getRepository(Item::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $itemLink = $npc->getItem()->toArray();
        $itemAdd = array_diff($itemUser, $itemLink);
        $itemAdd = new ArrayCollection($itemAdd);

        $intrigueUser = $doctrine->getRepository(Intrigue::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $intrigueLink = $npc->getIntrigue()->toArray();
        $intrigueAdd = array_diff($intrigueUser, $intrigueLink);
        $intrigueAdd = new ArrayCollection($intrigueAdd);

        $encounterUser = $doctrine->getRepository(Encounter::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $encounterLink = $npc->getEncounter()->toArray();
        $encounterAdd = array_diff($encounterUser, $encounterLink);
        $encounterAdd = new ArrayCollection($encounterAdd);

        if($request->isMethod('POST'))
        {
            if($request->request->get('npcAdd'))
            {
                $npcToAdd = $doctrine->getRepository(Npc::class)->find($request->request->get('npcAdd'));
                if($npcToAdd->getCreator() == $security->getUser())
                {
                    $npc->addNpc($npcToAdd);
                    $npcToAdd->addNpc($npc);
                }
            }

            if($request->request->get('locationAdd'))
            {
                $locationToAdd = $doctrine->getRepository(Location::class)->find($request->request->get('locationAdd'));
                if($locationToAdd->getCreator() == $security->getUser())
                {
                    $npc->addLocation($locationToAdd);
                    $locationToAdd->addNpc($npc);
                }
            }

            if($request->request->get('itemAdd'))
            {
                $itemToAdd = $doctrine->getRepository(Item::class)->find($request->request->get('itemAdd'));
                if($itemToAdd->getCreator() == $security->getUser())
                {
                    $npc->addItem($itemToAdd);
                    $itemToAdd->addNpc($npc);
                }
            }

            if($request->request->get('intrigueAdd'))
            {
                $intrigueToAdd = $doctrine->getRepository(Intrigue::class)->find($request->request->get('intrigueAdd'));
                if($intrigueToAdd->getCreator() == $security->getUser())
                {
                    $npc->addIntrigue($intrigueToAdd);
                    $intrigueToAdd->addNpc($npc);
                }
            }

            if($request->request->get('encounterAdd'))
            {
                $encounterToAdd = $doctrine->getRepository(Encounter::class)->find($request->request->get('encounterAdd'));
                if($encounterToAdd->getCreator() == $security->getUser())
                {
                    $npc->addEncounter($encounterToAdd);
                    $encounterToAdd->addNpc($npc);
                }
            }

            $entityManager->flush();
        }

        return $this->render('npc/show.html.twig', [
            'npc' => $npc,
            'npcAdd' => $npcAdd,
            'locationAdd' => $locationAdd,
            'itemAdd' => $itemAdd,
            'intrigueAdd' => $intrigueAdd,
            'encounterAdd' => $encounterAdd
        ]);
    }

    /**
     * @Route("/{id}/edit", name="npc_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Npc $npc, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger ): Response
    {
        $form = $this->createForm(NpcType::class, $npc);
        $oldImg = $npc->getImg();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $img = $form->get('imgFile')->getData();

            if(!$img || $img->getClientOriginalName() == $oldImg)
            {
                $npc->setImg($oldImg);
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

                $npc->setImg($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
        }

         return $this->renderform('npc/edit.html.twig', [
             'npc' => $npc,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="npc_delete", methods={"POST"})
     */
    public function delete(Request $request, Npc $npc, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$npc->getId(), $request->request->get('_token'))) {
            if($npc->getImg() != "basic.png")
            {
                unlink($this->getParameter('img_directory')."/".$npc->getImg());
            }
            
            $entityManager->remove($npc);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
    }
}
