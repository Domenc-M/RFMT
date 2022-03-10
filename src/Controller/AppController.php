<?php

namespace App\Controller;

use App\Entity\Encounter;
use App\Entity\Hook;
use App\Entity\Intrigue;
use App\Entity\Item;
use App\Entity\Location;
use App\Entity\Npc;
use App\Entity\Table;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $hook = $doctrine->getRepository(Hook::class)->findBy(
            [],
            ['updatedAt' => 'DESC'],
            4
        );
        $table = $doctrine->getRepository(Table::class)->findBy(
            [],
            ['updatedAt' => 'DESC'],
            4
        );
        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
            'hook' => $hook,
            'table' => $table
        ]);
    }

    /**
     * @Route("/searchown", name="app_searchOwn")
     */
    public function searchOwn(Request $request, ManagerRegistry $doctrine, Security $security): Response
    {

        if($request->query->get('filter') == "summary")
        {
            $filter = "summary";
        }
        else
        {
            $filter = "name";
        }

        $currentUserId = $security->getUser()->getId();

        //REQUEST HANDLING
        $searchAll = false;

        if($request->query->get('npc') == null && 
           $request->query->get('location') == null &&
           $request->query->get('encounter') == null &&
           $request->query->get('item') == null &&
           $request->query->get('intrigue') == null)
           {
               $searchAll = true;
           }
        
        $searchKey = $request->query->get('searchKey');

        if(!$searchKey)
        {
            $searchKey = "%";
        }

        //NPC
        if($request->query->get('npc') == "on" || $searchAll == true)
            $npc = $doctrine->getRepository(Npc::class)->searchBy($filter, $searchKey, $currentUserId);
         else
            $npc = false; 

        //LOCATION
        if($request->query->get('location') == "on" || $searchAll == true)
            $location = $doctrine->getRepository(Location::class)->searchBy($filter, $searchKey, $currentUserId);
        else 
            $location = false;

        //ITEM
        if($request->query->get('item') == "on" || $searchAll == true)
            $item = $doctrine->getRepository(Item::class)->searchBy($filter, $searchKey, $currentUserId);
        else 
            $item = false;

        //ENCOUNTER
        if($request->query->get('encounter') == "on" || $searchAll == true)
            $encounter = $doctrine->getRepository(Encounter::class)->searchBy($filter, $searchKey, $currentUserId);
        else
            $encounter = false;

        //Intrigue
        if($request->query->get('intrigue') == "on" || $searchAll == true)
            $intrigue = $doctrine->getRepository(Intrigue::class)->searchBy($filter, $searchKey, $currentUserId);
        else
            $intrigue = false;

        return $this->render('app/searchOwn.html.twig', [
            'npcs' => $npc,
            'locations' => $location,
            'items' => $item,
            'encounters' => $encounter,
            'intrigues' => $intrigue,
        ]);
    }

    /**
     * @Route("/searchother", name="app_searchOther")
     */
    public function searchOther(Request $request, ManagerRegistry $doctrine): Response
    {

        if($request->query->get('filter') == "summary")
        {
            $filter = "summary";
        }
        else
        {
            $filter = "name";
        }

        $searchKey = $request->query->get('searchKey');

        if(!$searchKey)
        {
            $searchKey = "%";
        }

        //REQUEST HANDLING

        if($request->query->get('category') == "table")
        {
            $category = "Tables";
            $result = $doctrine->getRepository(Table::class)->searchBy($filter, $searchKey);
        }
        else
        {
            $category = "Amorces";
            $result = $doctrine->getRepository(Hook::class)->searchBy($filter, $searchKey);
        }

        return $this->render('app/searchOther.html.twig', [
            'results' => $result,
            'category' => $category
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
    

    /**
    * @Route("/logout", name="app_logout")
    */
    public function logout(AuthenticationUtils $authenticationUtils): Response
    {
        return $this->redirectToRoute('app_index', [], Response::HTTP_SEE_OTHER);
    }
}