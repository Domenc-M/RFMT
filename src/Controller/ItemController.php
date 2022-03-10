<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Entity\Intrigue;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Npc;
use App\Form\ItemType;
use App\Repository\ItemRepository;
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
 * @Route("/item")
 */
class ItemController extends AbstractController
{
    /**
     * @Route("/new", name="item_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager, 
                        Security $security, SluggerInterface $slugger): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $img = $form->get('imgFile')->getData();
            if(!$img)
            {
                $item->setImg("basic.png");
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
                $item->setImg($newFilename);
            }
            $item->setCreator($security->getUser());
            $entityManager->persist($item);
            $entityManager->flush();
            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('item/new.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET", "POST"})
     */
    public function show(Item $item, Request $request, ManagerRegistry $doctrine, Security $security,
    EntityManagerInterface $entityManager): Response
    {
        $npcUser = $doctrine->getRepository(Npc::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $npcLink = $item->getNpcs()->toArray();
        $npcAdd = array_diff($npcUser, $npcLink);
        $npcAdd = new ArrayCollection($npcAdd);

        $locationUser = $doctrine->getRepository(Location::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $locationLink = $item->getLocations()->toArray();
        $locationAdd = array_diff($locationUser, $locationLink);
        $locationAdd = new ArrayCollection($locationAdd);

        $itemUser = $doctrine->getRepository(Item::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $itemLink = $item->getItem()->toArray();
        array_push($itemLink, $item);
        $itemAdd = array_diff($itemUser, $itemLink);
        $itemAdd = new ArrayCollection($itemAdd);

        $intrigueUser = $doctrine->getRepository(Intrigue::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $intrigueLink = $item->getIntrigue()->toArray();
        $intrigueAdd = array_diff($intrigueUser, $intrigueLink);
        $intrigueAdd = new ArrayCollection($intrigueAdd);

        $encounterUser = $doctrine->getRepository(Encounter::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $encounterLink = $item->getEncounter()->toArray();
        $encounterAdd = array_diff($encounterUser, $encounterLink);
        $encounterAdd = new ArrayCollection($encounterAdd);

        if($request->isMethod('POST'))
        {
            if($request->request->get('npcAdd'))
            {
                $npcToAdd = $doctrine->getRepository(Npc::class)->find($request->request->get('npcAdd'));
                if($npcToAdd->getCreator() == $security->getUser())
                {
                    $item->addNpc($npcToAdd);
                    $npcToAdd->addItem($item);
                }
            }

            if($request->request->get('locationAdd'))
            {
                $locationToAdd = $doctrine->getRepository(Location::class)->find($request->request->get('locationAdd'));
                if($locationToAdd->getCreator() == $security->getUser())
                {
                    $item->addLocation($locationToAdd);
                    $locationToAdd->addItem($item);
                }
            }

            if($request->request->get('itemAdd'))
            {
                $itemToAdd = $doctrine->getRepository(Item::class)->find($request->request->get('itemAdd'));
                if($itemToAdd->getCreator() == $security->getUser())
                {
                    $item->addItem($itemToAdd);
                    $itemToAdd->addItem($item);
                }
            }

            if($request->request->get('intrigueAdd'))
            {
                $intrigueToAdd = $doctrine->getRepository(Intrigue::class)->find($request->request->get('intrigueAdd'));
                if($intrigueToAdd->getCreator() == $security->getUser())
                {
                    $item->addIntrigue($intrigueToAdd);
                    $intrigueToAdd->addItem($item);
                }
            }

            if($request->request->get('encounterAdd'))
            {
                $encounterToAdd = $doctrine->getRepository(Encounter::class)->find($request->request->get('encounterAdd'));
                if($encounterToAdd->getCreator() == $security->getUser())
                {
                    $item->addEncounter($encounterToAdd);
                    $encounterToAdd->addItem($item);
                }
            }

            $entityManager->flush();
        }
        return $this->render('item/show.html.twig', [
            'item' => $item,
            'npcAdd' => $npcAdd,
            'locationAdd' => $locationAdd,
            'itemAdd' => $itemAdd,
            'intrigueAdd' => $intrigueAdd,
            'encounterAdd' => $encounterAdd
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Item $item, EntityManagerInterface $entityManager,
                        SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ItemType::class, $item);
        $oldImg = $item->getImg();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $img = $form->get('imgFile')->getData();

            if(!$img || $img->getClientOriginalName() == $oldImg)
            {
                $item->setImg($oldImg);
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

                $item->setImg($newFilename);
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('item/edit.html.twig', [
            'item' => $item,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="item_delete", methods={"POST"})
     */
    public function delete(Request $request, Item $item, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            if($item->getImg() != "basic.png")
            {
                unlink($this->getParameter('img_directory')."/".$item->getImg());
            }
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_searchOwn', [], Response::HTTP_SEE_OTHER);
    }
}
