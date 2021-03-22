<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductListController extends AbstractController
{
    /**
     * @Route("/product/list", name="product_list")
     */
    public function index(ProductRepository $productRepository, CategoryRepository $categoryRepository): Response
    {
        // on récupère tous les produits de la table product dans la bdd avec la méthode findAll() de la classe ProductRepository
        // on stocke cette liste dans la variable $productList sous forme de tableau contenant des objets
        $productListPhp = $productRepository->findAll();
        // on pourrait aussi implémenter de la sorte suivante, sans mettre de paramètre dans la fonction index()
        // $productListPhp = $this->getDoctrine()->getRepository(Product::class)->findAll();
        $categoryListPhp = $categoryRepository->findAll();
        // on envoie la la variable $productListPhp vers le template par l'intermédiaire du paramètre 'productListTwig'
        return $this->render('product_list/index.html.twig', [
            'productListTwig' => $productListPhp,
            'categoryListTwig' => $categoryListPhp
        ]);
    }

    /**
     * @Route("/product/list_by_category/{id}", name="product_list_by_category")
     */
    public function byCategory(ProductRepository $productRepository, CategoryRepository $categoryRepository, $id): Response
    {
        // on récupère les produits de la table product dans la bdd, pour une certaine catégorie, avec la méthode "findBy()" de la classe ProductRepository
        // cette méthode demande en paramètre un tableau qui contient les critère nécessaire à la recherche dans la bdd
        // on stocke cette liste dans la variable $productList sous forme de tableau contenant des objets

        // on peut ajouter un ordre de rangement des résultats et une quantité de résultats à ne pas dépasser
        $productListPhp = $productRepository->findBy(['category' => $id], ['price' => 'DESC'],2);
        $categoryListPhp = $categoryRepository->findAll();

        return $this->render('product_list/index.html.twig', [
            'productListTwig' => $productListPhp,
            'categoryListTwig' => $categoryListPhp
        ]);
    }

    /**
     * @Route("/product/{id}", name="product_single")
     */
    public function productSingle(ProductRepository $productRepository, $id): Response
    {
        $productPhp = $productRepository->find($id);

        return $this->render('product_list/single.html.twig', [
            'productTwig' => $productPhp
        ]);
    }

}
