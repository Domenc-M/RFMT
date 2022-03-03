<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Entity\Intrigue;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Npc;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(): Response
    {
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    /**
     * @Route("/searchown", name="app_searchOwn")
     */
    public function searchOwn(Request $request, ManagerRegistry $doctrine): Response
    {
        
        dump($request);

        dump($request->query->get('npc'));

        $searchAll = false;

        if($request->query->get('npc') == null && 
           $request->query->get('location') == null &&
           $request->query->get('encounter') == null &&
           $request->query->get('item') == null &&
           $request->query->get('intrigue') == null)
           {
               $searchAll = true;
           }

        $result = [];

        //NPC
        if($request->query->get('npc') == "on" || $searchAll == true) {
            $npcRepo = $doctrine->getRepository(Npc::class);
            $npc = $npcRepo->findAll();
        } else { $npc = false; }

        //LOCATION
        if($request->query->get('npc') == "on" || $searchAll == true)
        {
            $locationRepo = $doctrine->getRepository(Location::class);
            $location = $locationRepo->findAll();
        } else { $location = false; }

        //ITEM
        if($request->query->get('npc') == "on" || $searchAll == true)
        {
            $itemRepo = $doctrine->getRepository(Item::class);
            $item = $itemRepo->findAll();
        } else { $item = false; }

        //ENCOUNTER
        if($request->query->get('npc') == "on" || $searchAll == true)
        {
            $encounterRepo = $doctrine->getRepository(Encounter::class);
            $encounter = $encounterRepo->findAll();
        } else { $encounter = false; }

        //ENCOUNTER
        if($request->query->get('npc') == "on" || $searchAll == true)
        {
            $intrigueRepo = $doctrine->getRepository(Intrigue::class);
            $intrigue = $intrigueRepo->findAll();
        } else { $intrigue = false; }

        return $this->render('app/searchOwn.html.twig', [
            'items' => $result,
        ]);
    }


    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
            $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_index');
        }

        return $this->render('app/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
    * @Route("/login", name="app_login")
    */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
    // get the login error if there is one
    $error = $authenticationUtils->getLastAuthenticationError();
    // last username entered by the user
    $lastUsername = $authenticationUtils->getLastUsername();
    return $this->render('app/login.html.twig', [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]);
        }
    }