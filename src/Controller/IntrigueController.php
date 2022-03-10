<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Entity\Intrigue;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Npc;
use App\Form\IntrigueType;
use App\Repository\IntrigueRepository;
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
     * @Route("/{id}", name="intrigue_show", methods={"GET", "POST"})
     */
    public function show(Intrigue $intrigue, Request $request, ManagerRegistry $doctrine, Security $security,
    EntityManagerInterface $entityManager): Response
    {
        $npcUser = $doctrine->getRepository(Npc::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $npcLink = $intrigue->getNpcs()->toArray();
        array_push($npcLink, $intrigue);
        $npcAdd = array_diff($npcUser, $npcLink);
        $npcAdd = new ArrayCollection($npcAdd);

        $locationUser = $doctrine->getRepository(Location::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $locationLink = $intrigue->getLocations()->toArray();
        $locationAdd = array_diff($locationUser, $locationLink);
        $locationAdd = new ArrayCollection($locationAdd);

        $itemUser = $doctrine->getRepository(Item::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $itemLink = $intrigue->getItems()->toArray();
        $itemAdd = array_diff($itemUser, $itemLink);
        $itemAdd = new ArrayCollection($itemAdd);

        $intrigueUser = $doctrine->getRepository(Intrigue::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $intrigueLink = $intrigue->getIntrigue()->toArray();
        $intrigueAdd = array_diff($intrigueUser, $intrigueLink);
        $intrigueAdd = new ArrayCollection($intrigueAdd);

        $encounterUser = $doctrine->getRepository(Encounter::class)->findBy(
            ['creator' => $security->getUser()],
            ['name' => 'ASC']
        );
        $encounterLink = $intrigue->getEncounter()->toArray();
        $encounterAdd = array_diff($encounterUser, $encounterLink);
        $encounterAdd = new ArrayCollection($encounterAdd);

        if($request->isMethod('POST'))
        {
            if($request->request->get('npcAdd'))
            {
                $npcToAdd = $doctrine->getRepository(Npc::class)->find($request->request->get('npcAdd'));
                if($npcToAdd->getCreator() == $security->getUser())
                {
                    $intrigue->addNpc($npcToAdd);
                    $npcToAdd->addIntrigue($intrigue);
                }
            }

            if($request->request->get('locationAdd'))
            {
                $locationToAdd = $doctrine->getRepository(Location::class)->find($request->request->get('locationAdd'));
                if($locationToAdd->getCreator() == $security->getUser())
                {
                    $intrigue->addLocation($locationToAdd);
                    $locationToAdd->addIntrigue($intrigue);
                }
            }

            if($request->request->get('itemAdd'))
            {
                $itemToAdd = $doctrine->getRepository(Item::class)->find($request->request->get('itemAdd'));
                if($itemToAdd->getCreator() == $security->getUser())
                {
                    $intrigue->addItem($itemToAdd);
                    $itemToAdd->addIntrigue($intrigue);
                }
            }

            if($request->request->get('intrigueAdd'))
            {
                $intrigueToAdd = $doctrine->getRepository(Intrigue::class)->find($request->request->get('intrigueAdd'));
                if($intrigueToAdd->getCreator() == $security->getUser())
                {
                    $intrigue->addIntrigue($intrigueToAdd);
                    $intrigueToAdd->addIntrigue($intrigue);
                }
            }

            if($request->request->get('encounterAdd'))
            {
                $encounterToAdd = $doctrine->getRepository(Encounter::class)->find($request->request->get('encounterAdd'));
                if($encounterToAdd->getCreator() == $security->getUser())
                {
                    $intrigue->addEncounter($encounterToAdd);
                    $encounterToAdd->addIntrigue($intrigue);
                }
            }

            $entityManager->flush();
        }
        return $this->render('intrigue/show.html.twig', [
            'intrigue' => $intrigue,
            'npcAdd' => $npcAdd,
            'locationAdd' => $locationAdd,
            'itemAdd' => $itemAdd,
            'intrigueAdd' => $intrigueAdd,
            'encounterAdd' => $encounterAdd
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
