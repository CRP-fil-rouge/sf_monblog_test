<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(CategoryRepository $categoryRepository): Response
    {
        $user = $this->getUser();
        $categoryListPhp = $categoryRepository->findAll();
        // $categoryListPhp est un tableau d'objets
        return $this->render('home/home.html.twig', [
            'categoryListTwig' => $categoryListPhp,
            'user' => $user
        ]);
    }
}
