<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Entity\Intrigue;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Npc;
use App\Form\LocationType;
use App\Repository\LocationRepository;
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
 * @Route("/location")
 */
class LocationController extends AbstractController
{
    /**
     * @Route("/new", name="location_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger, Security $security): Response
    {
        $location = new Location();
        $form = $this->createForm(LocationType::class, $location);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('imgFile')->getData();
            if(!$img)
            {
                $location->setImg("basic.png");
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
                $location->setImg($newFilename);
            }
            $location->setCreator($security->getUser());
            $entityManager->persist($location);
            $entityManager->flush();
            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('location/new.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="location_show", methods={"GET", "POST"})
     */
    public function show(Location $location, Request $request, ManagerRegistry $doctrine, Security $security,
    EntityManagerInterface $entityManager): Response
    {
        $npcUser = $doctrine->getRepository(Npc::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $npcLink = $location->getNpcs()->toArray();
        $npcAdd = array_diff($npcUser, $npcLink);
        $npcAdd = new ArrayCollection($npcAdd);

        $locationUser = $doctrine->getRepository(Location::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $locationLink = $location->getLocation()->toArray();
        array_push($locationLink, $location);
        $locationAdd = array_diff($locationUser, $locationLink);
        $locationAdd = new ArrayCollection($locationAdd);

        $itemUser = $doctrine->getRepository(Item::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $itemLink = $location->getItem()->toArray();
        $itemAdd = array_diff($itemUser, $itemLink);
        $itemAdd = new ArrayCollection($itemAdd);

        $intrigueUser = $doctrine->getRepository(Intrigue::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $intrigueLink = $location->getIntrigue()->toArray();
        $intrigueAdd = array_diff($intrigueUser, $intrigueLink);
        $intrigueAdd = new ArrayCollection($intrigueAdd);

        $encounterUser = $doctrine->getRepository(Encounter::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $encounterLink = $location->getEncounter()->toArray();
        $encounterAdd = array_diff($encounterUser, $encounterLink);
        $encounterAdd = new ArrayCollection($encounterAdd);

        if($request->isMethod('POST'))
        {
            if($request->request->get('npcAdd'))
            {
                $npcToAdd = $doctrine->getRepository(Npc::class)->find($request->request->get('npcAdd'));
                if($npcToAdd->getCreator() == $security->getUser())
                {
                    $location->addNpc($npcToAdd);
                    $npcToAdd->addLocation($location);
                }
            }

            if($request->request->get('locationAdd'))
            {
                $locationToAdd = $doctrine->getRepository(Location::class)->find($request->request->get('locationAdd'));
                if($locationToAdd->getCreator() == $security->getUser())
                {
                    $location->addLocation($locationToAdd);
                    $locationToAdd->addLocation($location);
                }
            }

            if($request->request->get('itemAdd'))
            {
                $itemToAdd = $doctrine->getRepository(Item::class)->find($request->request->get('itemAdd'));
                if($itemToAdd->getCreator() == $security->getUser())
                {
                    $location->addItem($itemToAdd);
                    $itemToAdd->addLocation($location);
                }
            }

            if($request->request->get('intrigueAdd'))
            {
                $intrigueToAdd = $doctrine->getRepository(Intrigue::class)->find($request->request->get('intrigueAdd'));
                if($intrigueToAdd->getCreator() == $security->getUser())
                {
                    $location->addIntrigue($intrigueToAdd);
                    $intrigueToAdd->addLocation($location);
                }
            }

            if($request->request->get('encounterAdd'))
            {
                $encounterToAdd = $doctrine->getRepository(Encounter::class)->find($request->request->get('encounterAdd'));
                if($encounterToAdd->getCreator() == $security->getUser())
                {
                    $location->addEncounter($encounterToAdd);
                    $encounterToAdd->addLocation($location);
                }
            }

            $entityManager->flush();
        }
        return $this->render('location/show.html.twig', [
            'location' => $location,
            'npcAdd' => $npcAdd,
            'locationAdd' => $locationAdd,
            'itemAdd' => $itemAdd,
            'intrigueAdd' => $intrigueAdd,
            'encounterAdd' => $encounterAdd
        ]);
    }

    /**
     * @Route("/{id}/edit", name="location_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Location $location, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger): Response
    {
        $form = $this->createForm(LocationType::class, $location);
        $oldImg = $location->getImg();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $img = $form->get('imgFile')->getData();

            if(!$img || $img->getClientOriginalName() == $oldImg)
            {
                $location->setImg($oldImg);
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

                $location->setImg($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('location/edit.html.twig', [
            'location' => $location,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="location_delete", methods={"POST"})
     */
    public function delete(Request $request, Location $location, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$location->getId(), $request->request->get('_token'))) {
            if($location->getImg() != "basic.png")
            {
                unlink($this->getParameter('img_directory')."/".$location->getImg());
            }
            $entityManager->remove($location);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
    }
}
