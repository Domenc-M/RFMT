<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Entity\Intrigue;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Npc;
use App\Form\EncounterType;
use App\Repository\EncounterRepository;
use Doctrine\Common\Collections\ArrayCollection;
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
     * @Route("/{id}", name="encounter_show", methods={"GET", "POST"})
     */
    public function show(Encounter $encounter, Request $request, ManagerRegistry $doctrine, Security $security,
    EntityManagerInterface $entityManager): Response
    {
        $npcUser = $doctrine->getRepository(Npc::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $npcLink = $encounter->getNpcs()->toArray();
        $npcAdd = array_diff($npcUser, $npcLink);
        $npcAdd = new ArrayCollection($npcAdd);

        $locationUser = $doctrine->getRepository(Location::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $locationLink = $encounter->getLocations()->toArray();
        $locationAdd = array_diff($locationUser, $locationLink);
        $locationAdd = new ArrayCollection($locationAdd);

        $itemUser = $doctrine->getRepository(Item::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $itemLink = $encounter->getItems()->toArray();
        $itemAdd = array_diff($itemUser, $itemLink);
        $itemAdd = new ArrayCollection($itemAdd);

        $intrigueUser = $doctrine->getRepository(Intrigue::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $intrigueLink = $encounter->getIntrigues()->toArray();
        $intrigueAdd = array_diff($intrigueUser, $intrigueLink);
        $intrigueAdd = new ArrayCollection($intrigueAdd);

        $encounterUser = $doctrine->getRepository(Encounter::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $encounterLink = $encounter->getEncounters()->toArray();
        array_push($encounterLink, $encounter);
        $encounterAdd = array_diff($encounterUser, $encounterLink);
        $encounterAdd = new ArrayCollection($encounterAdd);

        if($request->isMethod('POST'))
        {
            if($request->request->get('npcAdd'))
            {
                $npcToAdd = $doctrine->getRepository(Npc::class)->find($request->request->get('npcAdd'));
                if($npcToAdd->getCreator() == $security->getUser())
                {
                    $encounter->addNpc($npcToAdd);
                    $npcToAdd->addEncounter($encounter);
                }
            }

            if($request->request->get('locationAdd'))
            {
                $locationToAdd = $doctrine->getRepository(Location::class)->find($request->request->get('locationAdd'));
                if($locationToAdd->getCreator() == $security->getUser())
                {
                    $encounter->addLocation($locationToAdd);
                    $locationToAdd->addEncounter($encounter);
                }
            }

            if($request->request->get('itemAdd'))
            {
                $itemToAdd = $doctrine->getRepository(Item::class)->find($request->request->get('itemAdd'));
                if($itemToAdd->getCreator() == $security->getUser())
                {
                    $encounter->addItem($itemToAdd);
                    $itemToAdd->addEncounter($encounter);
                }
            }

            if($request->request->get('intrigueAdd'))
            {
                $intrigueToAdd = $doctrine->getRepository(Intrigue::class)->find($request->request->get('intrigueAdd'));
                if($intrigueToAdd->getCreator() == $security->getUser())
                {
                    $encounter->addIntrigue($intrigueToAdd);
                    $intrigueToAdd->addEncounter($encounter);
                }
            }

            if($request->request->get('encounterAdd'))
            {
                $encounterToAdd = $doctrine->getRepository(Encounter::class)->find($request->request->get('encounterAdd'));
                if($encounterToAdd->getCreator() == $security->getUser())
                {
                    $encounter->addEncounter($encounterToAdd);
                    $encounterToAdd->addEncounter($encounter);
                }
            }

            $entityManager->flush();
        }
        return $this->render('encounter/show.html.twig', [
            'encounter' => $encounter,
            'npcAdd' => $npcAdd,
            'locationAdd' => $locationAdd,
            'itemAdd' => $itemAdd,
            'intrigueAdd' => $intrigueAdd,
            'encounterAdd' => $encounterAdd
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
     * @Route("/{id}/delete", name="encounter_delete", methods={"POST"})
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
